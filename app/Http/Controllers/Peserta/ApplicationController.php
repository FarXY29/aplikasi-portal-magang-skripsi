<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\InternshipPosition;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Peserta\StoreApplicationRequest;

class ApplicationController extends Controller
{
    /**
     * FORM APPLY
     */
    public function showApplyForm($id)
    {
        $position = InternshipPosition::with('instansi')->findOrFail($id);
        $user = Auth::user();

        // Cek Application Limiter (Maksimal 2 lamaran aktif)
        $activeApplicationsCount = Application::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'menunggu', 'diterima'])
            ->count();

        if ($activeApplicationsCount >= 2) {
            return redirect()->route('peserta.dashboard')->with('error', 'Peringatan: Anda telah mencapai batas maksimal 2 lamaran aktif bersamaan (pending/menunggu/diterima). Batalkan lamaran sebelumnya jika ingin mengajukan ke posisi baru.');
        }

        // 1. Validasi Jurusan
        $syaratJurusan = strtolower($position->required_major);
        $jurusanPelamar = strtolower($user->major);
        if (!str_contains($syaratJurusan, 'semua jurusan') && !str_contains($syaratJurusan, $jurusanPelamar)) {
            return redirect()->route('home')->with('error', "Posisi ini khusus jurusan: {$position->required_major}.");
        }

        // 2. Cek Kuota Master (Kapasitas Ruangan)
        if ($position->kuota <= 0) {
            return redirect()->route('home')->with('error', 'Lowongan ini sedang ditutup (Kapasitas 0).');
        }

        return view('peserta.apply', compact('position'));
    }

    /**
     * PROSES LAMARAN (INTI LOGIKA HOTEL BOOKING & ATOMIC QUOTA LOCKING)
     * Menggunakan DB::transaction dan lockForUpdate untuk mencegah race condition kuota magang.
     */
    public function storeApplication(StoreApplicationRequest $request, $id)
    {
        $user = Auth::user();

        // 1. Validasi Lowongan Terlebih Dahulu
        $position = InternshipPosition::find($id);
        if (!$position) {
            return redirect()->back()->with('error', 'Lowongan tidak ditemukan.');
        }

        if ($position->status !== 'buka') {
            return redirect()->back()->with('error', 'Pendaftaran lowongan ini telah ditutup.');
        }

        if ($position->batas_daftar && Carbon::now()->startOfDay()->gt(Carbon::parse($position->batas_daftar)->startOfDay())) {
            return redirect()->back()->with('error', 'Batas waktu pendaftaran untuk lowongan ini telah berakhir.');
        }

        // 2. Validasi Jurusan
        $syaratJurusan = strtolower($position->required_major);
        $jurusanPelamar = strtolower($user->major);
        if (!str_contains($syaratJurusan, 'semua jurusan') && !str_contains($syaratJurusan, $jurusanPelamar)) {
            return redirect()->back()->with('error', "Posisi ini khusus jurusan: {$position->required_major}.");
        }

        // 3. Cek magang aktif
        $hasActiveInternship = Application::where('user_id', $user->id)
            ->where('status', 'diterima')
            ->exists();

        if ($hasActiveInternship) {
            return redirect()->route('peserta.dashboard')->with('error', 'Anda tidak dapat mengajukan lowongan baru karena masih terdaftar dalam magang aktif.');
        }

        $reqStart = $request->tanggal_mulai;
        $reqEnd   = $request->tanggal_selesai;

        // Cek Application Limiter (Maksimal 2 lamaran aktif)
        $activeApplicationsCount = Application::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'menunggu', 'diterima'])
            ->count();

        if ($activeApplicationsCount >= 2) {
            return redirect()->route('peserta.dashboard')->with('error', 'Peringatan: Anda telah mencapai batas maksimal 2 lamaran aktif bersamaan (pending/menunggu/diterima). Batalkan lamaran sebelumnya jika ingin mengajukan ke posisi baru.');
        }

        $existingApp = Application::where('user_id', $user->id)
            ->where('internship_position_id', $id)
            ->whereIn('status', ['pending', 'menunggu', 'diterima'])
            ->exists();

        if ($existingApp) {
            return redirect()->route('peserta.dashboard')->with('error', 'Anda sudah melamar ke posisi ini dan statusnya masih aktif/pending.');
        }

        // Upload berkas di luar transaksi agar lock database tidak tertahan oleh proses I/O storage
        $suratPath = $request->file('surat')->store('documents/surat', 'private');

        $status = DB::transaction(function () use ($id, $user, $reqStart, $reqEnd, $suratPath, $request) {
            // Pessimistic Locking pada baris InternshipPosition untuk menjamin akurasi kuota instansi
            $position = InternshipPosition::where('id', $id)->lockForUpdate()->firstOrFail();
            $kapasitasMaksimal = $position->kuota;

            $conflictingAppsCount = Application::where('internship_position_id', $id)
                ->whereIn('status', ['diterima', 'pending'])
                ->where(function($q) use ($reqStart, $reqEnd) {
                    $q->where('tanggal_mulai', '<=', $reqEnd)
                      ->where('tanggal_selesai', '>=', $reqStart);
                })
                ->count();

            // Cek juga kuota global instansi jika diatur (max_total_quota > 0)
            $instansi = $position->instansi()->lockForUpdate()->first();
            $instansiFull = false;
            if ($instansi && $instansi->max_total_quota > 0) {
                $instansiActiveCount = Application::whereHas('position', fn($q) => $q->where('instansi_id', $instansi->id))
                    ->whereIn('status', ['diterima', 'pending'])
                    ->where(function($q) use ($reqStart, $reqEnd) {
                        $q->where('tanggal_mulai', '<=', $reqEnd)
                          ->where('tanggal_selesai', '>=', $reqStart);
                    })
                    ->count();
                if ($instansiActiveCount >= $instansi->max_total_quota) {
                    $instansiFull = true;
                }
            }

            $status = 'pending';
            if ($conflictingAppsCount >= $kapasitasMaksimal || $instansiFull) {
                $status = 'menunggu';
            }

            Application::create([
                'user_id' => $user->id,
                'internship_position_id' => $id,
                'letter_number' => $request->letter_number ?? null,
                'cv_path' => '-', 
                'surat_pengantar_path' => $suratPath,
                'status' => $status,
                'tanggal_mulai' => $reqStart,
                'tanggal_selesai' => $reqEnd,
            ]);

            return $status;
        });

        $successMessage = $status === 'menunggu' 
            ? 'Anda berhasil masuk Daftar Tunggu! Anda akan otomatis diterima dan jadwal disesuaikan saat ada peserta yang selesai.' 
            : 'Lamaran berhasil dikirim! Slot tanggal aman.';

        return redirect()->route('peserta.dashboard')->with('success', $successMessage);
    }

    /**
     * AJAX CHECK AVAILABILITY
     */
    public function checkAvailability(Request $request, $id)
    {
        $request->validate([
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);

        $reqStart = $request->start;
        $reqEnd = $request->end;

        $position = InternshipPosition::findOrFail($id);
        $kapasitasMaksimal = $position->kuota;

        $conflictingAppsQuery = Application::where('internship_position_id', $id)
            ->whereIn('status', ['diterima', 'pending'])
            ->where(function($q) use ($reqStart, $reqEnd) {
                $q->where('tanggal_mulai', '<=', $reqEnd)
                  ->where('tanggal_selesai', '>=', $reqStart);
            });

        $bentrokCount = $conflictingAppsQuery->count();
        $isAvailable = $bentrokCount < $kapasitasMaksimal;

        if ($isAvailable) {
            return response()->json([
                'status' => 'available',
                'message' => "Kuota Tersedia! (Terisi: {$bentrokCount} dari {$kapasitasMaksimal} kursi)",
                'class' => 'text-green-600 bg-green-50 border-green-200 dark:bg-green-950/20 dark:border-green-900/50 dark:text-green-400'
            ]);
        } else {
            $lastParticipant = $conflictingAppsQuery->orderBy('tanggal_selesai', 'desc')->first();
            
            $suggestionMessage = "";
            $suggestionDate = "";
            $suggestionDateText = "";

            if ($lastParticipant) {
                $finishDate = Carbon::parse($lastParticipant->tanggal_selesai);
                $nextAvailableDate = $finishDate->copy()->addDay();

                $suggestionDate = $nextAvailableDate->format('Y-m-d');
                $suggestionDateText = $nextAvailableDate->translatedFormat('d F Y');

                $suggestionMessage = " Kuota Penuh untuk rentang tanggal ini. Sudah ada {$bentrokCount} peserta terjadwal sampai " . $finishDate->translatedFormat('d F Y') . ".";
            } else {
                $suggestionMessage = " Kuota Penuh untuk rentang tanggal ini.";
            }

            return response()->json([
                'status' => 'full',
                'message' => $suggestionMessage,
                'class' => 'text-red-600 bg-red-50 border-red-200 dark:bg-red-950/20 dark:border-red-900/50 dark:text-red-400',
                'suggestion_date' => $suggestionDate, 
                'suggestion_text' => $suggestionDateText
            ]);
        }
    }

    public function showAutomaticApplyForm()
    {
        $user = Auth::user();

        $activeApplicationsCount = Application::where('user_id', $user->id)
                        ->whereIn('status', ['pending', 'menunggu', 'diterima'])
                        ->count();

        if ($activeApplicationsCount >= 2) {
            return redirect()->route('peserta.dashboard')->with('error', 'Peringatan: Anda telah mencapai batas maksimal 2 lamaran aktif bersamaan (pending/menunggu/diterima). Batalkan lamaran sebelumnya terlebih dahulu.');
        }

        return view('peserta.apply_automatic');
    }

    public function storeAutomaticApplication(Request $request)
    {
        $user = Auth::user();

        // Cek magang aktif
        $hasActiveInternship = Application::where('user_id', $user->id)
            ->where('status', 'diterima')
            ->exists();

        if ($hasActiveInternship) {
            return redirect()->route('peserta.dashboard')->with('error', 'Anda tidak dapat mengajukan lowongan baru karena masih terdaftar dalam magang aktif.');
        }

        $request->validate([
            'surat' => 'required|mimes:pdf|max:2048',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        $reqStart = $request->tanggal_mulai;
        $reqEnd   = $request->tanggal_selesai;

        $activeApplicationsCount = Application::where('user_id', $user->id)
                        ->whereIn('status', ['pending', 'menunggu', 'diterima'])
                        ->count();

        if ($activeApplicationsCount >= 2) {
            return redirect()->route('peserta.dashboard')->with('error', 'Peringatan: Anda telah mencapai batas maksimal 2 lamaran aktif bersamaan (pending/menunggu/diterima). Batalkan lamaran sebelumnya terlebih dahulu.');
        }

        $openPositions = InternshipPosition::with('instansi')
                            ->where('status', 'buka')
                            ->where('kuota', '>', 0)
                            ->get();

        $userMajor = strtolower(trim($user->major ?? ''));
        $eligiblePositions = $openPositions->filter(function($position) use ($userMajor) {
            $reqMajor = strtolower(trim($position->required_major ?? ''));
            return str_contains($reqMajor, 'semua jurusan') || 
                   str_contains($reqMajor, $userMajor) ||
                   $reqMajor == '' || 
                   $reqMajor == '-';
        });

        if ($eligiblePositions->isEmpty()) {
            return redirect()->back()->with('error', 'Maaf, saat ini tidak ada lowongan yang dibuka yang sesuai dengan jurusan Anda (' . ($user->major ?? '-') . ').');
        }

        $availablePositions = [];
        foreach ($eligiblePositions as $position) {
            $kapasitasMaksimal = $position->kuota;

            $bentrokCount = Application::where('internship_position_id', $position->id)
                ->whereIn('status', ['diterima', 'pending'])
                ->where(function($q) use ($reqStart, $reqEnd) {
                    $q->where('tanggal_mulai', '<=', $reqEnd)
                      ->where('tanggal_selesai', '>=', $reqStart);
                })
                ->count();

            if ($bentrokCount < $kapasitasMaksimal) {
                $instansiId = $position->instansi_id;
                $instansiInternsCount = Application::whereHas('position', function($q) use ($instansiId) {
                    $q->where('instansi_id', $instansiId);
                })
                ->whereIn('status', ['diterima', 'pending'])
                ->count();

                $availablePositions[] = [
                    'position' => $position,
                    'instansi_interns_count' => $instansiInternsCount,
                ];
            }
        }

        if (empty($availablePositions)) {
            return redirect()->back()->with('error', 'Maaf, semua kuota instansi yang sesuai dengan jurusan Anda sudah penuh untuk periode tersebut.');
        }

        usort($availablePositions, function($a, $b) {
            return $a['instansi_interns_count'] <=> $b['instansi_interns_count'];
        });

        $selectedPosition = $availablePositions[0]['position'];

        $suratPath = $request->file('surat')->store('documents/surat', 'private');

        $status = DB::transaction(function () use ($selectedPosition, $user, $reqStart, $reqEnd, $suratPath, $request) {
            $position = InternshipPosition::where('id', $selectedPosition->id)->lockForUpdate()->firstOrFail();
            $kapasitasMaksimal = $position->kuota;

            $conflictingAppsCount = Application::where('internship_position_id', $position->id)
                ->whereIn('status', ['diterima', 'pending'])
                ->where(function($q) use ($reqStart, $reqEnd) {
                    $q->where('tanggal_mulai', '<=', $reqEnd)
                      ->where('tanggal_selesai', '>=', $reqStart);
                })
                ->count();

            $instansi = $position->instansi()->lockForUpdate()->first();
            $instansiFull = false;
            if ($instansi && $instansi->max_total_quota > 0) {
                $instansiActiveCount = Application::whereHas('position', fn($q) => $q->where('instansi_id', $instansi->id))
                    ->whereIn('status', ['diterima', 'pending'])
                    ->where(function($q) use ($reqStart, $reqEnd) {
                        $q->where('tanggal_mulai', '<=', $reqEnd)
                          ->where('tanggal_selesai', '>=', $reqStart);
                    })
                    ->count();
                if ($instansiActiveCount >= $instansi->max_total_quota) {
                    $instansiFull = true;
                }
            }

            $status = 'pending';
            if ($conflictingAppsCount >= $kapasitasMaksimal || $instansiFull) {
                $status = 'menunggu';
            }

            Application::create([
                'user_id' => $user->id,
                'internship_position_id' => $position->id,
                'letter_number' => $request->letter_number ?? null,
                'cv_path' => '-', 
                'surat_pengantar_path' => $suratPath,
                'status' => $status,
                'tanggal_mulai' => $reqStart,
                'tanggal_selesai' => $reqEnd,
                'is_automatic_placement' => true,
            ]);

            return $status;
        });

        $msg = $status === 'menunggu'
            ? 'Pendaftaran berhasil! Anda masuk daftar tunggu di ' . $selectedPosition->instansi->nama_dinas . ' karena kuota terisi.'
            : 'Pendaftaran berhasil! Anda telah otomatis ditempatkan di ' . $selectedPosition->instansi->nama_dinas . '.';

        return redirect()->route('peserta.dashboard')->with('success', $msg);
    }

    public function submitSaran(Request $request, $id)
    {
        $app = Application::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        $request->validate([
            'saran_peserta' => 'required|string'
        ]);

        $app->update([
            'saran_peserta' => $request->saran_peserta
        ]);

        return back()->with('success', 'Terima kasih, saran dan evaluasi Anda telah disimpan secara anonim untuk admin instansi.');
    }
}
