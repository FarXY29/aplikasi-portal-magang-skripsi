<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\InternshipPosition;
use App\Models\Instansi;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class MagangController extends Controller
{
    /**
     * TAMPILKAN LIST LOWONGAN
     * Logika: Lowongan tetap muncul selamanya selama Admin tidak menutupnya manual (status != tutup).
     * Kita TIDAK mengecek sisa kuota di sini, karena kuota tergantung tanggal yang dipilih user nanti.
     */
    public function index(Request $request)
    {
        // 1. Query Dasar
        // Hapus where('kuota', '>', 0) jika Anda ingin posisi tetap muncul meski penuh di bulan ini
        // Tapi demi kerapian, kita asumsikan 'kuota' adalah KAPASITAS TOTAL (misal: 1), jadi tetap > 0.
        $query = InternshipPosition::with('instansi')
                    ->where('status', 'buka')
                    ->where('kuota', '>', 0); 

        // 2. Filter Dinas
        if ($request->has('instansi_id') && $request->instansi_id != '') {
            $query->where('instansi_id', $request->instansi_id);
        }

        // 3. Filter Posisi
        if ($request->has('posisi') && $request->posisi != '') {
            $query->where('judul_posisi', 'like', '%' . $request->posisi . '%');
        }

        // 4. Filter Jurusan / Keahlian
        if ($request->has('jurusan') && $request->jurusan != '') {
            $jurusan = $request->jurusan;
            $query->where(function($q) use ($jurusan) {
                $q->where('required_major', 'like', '%' . $jurusan . '%')
                  ->orWhere('required_major', 'like', '%semua%')
                  ->orWhere('required_major', '=', '-')
                  ->orWhereNull('required_major');
            });
        }

        // 5. Search Global
        if ($request->has('search') && $request->search != '') {
             $search = $request->search;
             $query->where(function($q) use ($search) {
                 $q->where('judul_posisi', 'like', "%{$search}%")
                   ->orWhereHas('instansi', function($sq) use ($search) {
                       $sq->where('nama_dinas', 'like', "%{$search}%");
                   });
             });
        }

        // Pagination
        $lowongans = $query->latest()->paginate(9);
        $lowongans->appends($request->query()); 

        // Data Pendukung View
        $instansis = Instansi::orderBy('nama_dinas', 'asc')->get();
        $totalInstansi = Instansi::count();
        $totalLowongan = InternshipPosition::where('status', 'buka')->count();
        $totalAlumni = Application::where('status', 'selesai')->count();

        return view('welcome', compact(
            'lowongans', 'instansis', 
            'totalInstansi', 'totalLowongan', 'totalAlumni'
        )); 
    }

    /**
     * FORM APPLY
     */
    public function showApplyForm($id)
    {
        $position = InternshipPosition::with('instansi')->findOrFail($id);
        $user = Auth::user();

        // 1. Validasi Jurusan
        $syaratJurusan = strtolower($position->required_major);
        $jurusanPelamar = strtolower($user->major);
        if (!str_contains($syaratJurusan, 'semua jurusan') && !str_contains($syaratJurusan, $jurusanPelamar)) {
            return redirect()->route('home')->with('error', "Posisi ini khusus jurusan: {$position->required_major}.");
        }

        // 2. Cek Kuota Master (Kapasitas Ruangan)
        // Jika kapasitas ruangan 0, berarti memang ditutup selamanya.
        if ($position->kuota <= 0) {
            return redirect()->route('home')->with('error', 'Lowongan ini sedang ditutup (Kapasitas 0).');
        }

        return view('peserta.apply', compact('position'));
    }

    /**
     * PROSES LAMARAN (INTI LOGIKA HOTEL BOOKING)
     */
    public function storeApplication(Request $request, $id)
    {
        $user = Auth::user();

        // 1. Validasi Input
        $request->validate([
            'surat' => 'required|mimes:pdf|max:2048',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        $reqStart = $request->tanggal_mulai;
        $reqEnd   = $request->tanggal_selesai;

        // 2. Cek Double Submit (User sama, Posisi sama, Status Aktif)
        $existingApp = Application::where('user_id', $user->id)
                        ->where('internship_position_id', $id)
                        ->whereIn('status', ['pending', 'diterima'])
                        ->exists();

        if ($existingApp) {
            return redirect()->route('peserta.dashboard')->with('error', 'Anda sudah melamar posisi ini.');
        }

        // 3. --- LOGIKA HOTEL / BENTROK TANGGAL ---
        $position = InternshipPosition::findOrFail($id);
        $kapasitasMaksimal = $position->kuota; // Misal: 1

        // Kita hitung: Ada berapa orang yang SEDANG MAGANG (Diterima/Pending)
        // di rentang tanggal yang beririsan dengan permintaan user?
        // Rumus Irisan: (StartA <= EndB) AND (EndA >= StartB)
        
        $bentrokCount = Application::where('internship_position_id', $id)
            ->whereIn('status', ['diterima', 'pending']) // Hitung yang pending juga biar aman
            ->where(function($q) use ($reqStart, $reqEnd) {
                $q->where('tanggal_mulai', '<=', $reqEnd)
                  ->where('tanggal_selesai', '>=', $reqStart);
            })
            ->count();

        // CONTOH KASUS SESUAI PERMINTAAN:
        // Kapasitas = 1.
        // Si A: 1 Nov - 30 Nov (Sudah ada di DB).
        
        // Kasus B: User request 1 Des - 31 Des.
        // Cek: (1 Nov <= 31 Des) AND (30 Nov >= 1 Des) ??? -> TRUE AND FALSE -> FALSE (Tidak Bentrok).
        // Hasil $bentrokCount = 0.
        // 0 < 1 -> LOLOS.
        
        // Kasus C: User request 15 Nov - 15 Des.
        // Cek: (1 Nov <= 15 Des) AND (30 Nov >= 15 Nov) ??? -> TRUE AND TRUE -> TRUE (Bentrok).
        // Hasil $bentrokCount = 1.
        // 1 >= 1 -> DITOLAK.

        $status = 'pending';

        if ($bentrokCount >= $kapasitasMaksimal) {
             if ($request->is_waiting_list == '1') {
                 $status = 'menunggu';
             } else {
                 return redirect()->back()->with('error', 
                    "Gagal! Kuota penuh untuk tanggal tersebut. Sudah ada {$bentrokCount} orang terjadwal di periode yang beririsan dengan tanggal pilihan Anda."
                 );
             }
        }

        // 4. Simpan Data (JIKA LOLOS ATAU WAITING LIST)
        $suratPath = $request->file('surat')->store('documents/surat', 'public');

        Application::create([
            'user_id' => $user->id,
            'internship_position_id' => $id,
            'cv_path' => '-', 
            'surat_pengantar_path' => $suratPath,
            'status' => $status,
            'tanggal_mulai' => $reqStart,
            'tanggal_selesai' => $reqEnd,
        ]);

        $successMessage = $status === 'menunggu' 
            ? 'Anda berhasil masuk Daftar Tunggu! Anda akan otomatis diterima dan jadwal disesuaikan saat ada peserta yang selesai.' 
            : 'Lamaran berhasil dikirim! Slot tanggal aman.';

        return redirect()->route('peserta.dashboard')->with('success', $successMessage);
    }

    /**
     * Dashboard Peserta
     */
    public function dashboard(Request $request)
    {
        $user = Auth::user();

        $statusFilter = $request->input('status');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Application::where('user_id', $user->id)
                            ->with(['position.instansi'])
                            ->latest();

        if ($statusFilter && $statusFilter !== 'semua') {
            $query->where('status', $statusFilter);
        }

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $myApplications = $query->get();

        $activeApp = Application::where('user_id', $user->id)
                        ->whereIn('status', ['diterima', 'selesai'])
                        ->with(['position.instansi', 'pembimbing_lapangan'])
                        ->latest('updated_at')
                        ->first();

        // Cek Absensi Hari Ini
        $attendanceToday = null;
        $jamKerja = null;
        $stats = null;
        $daysRemaining = null;

        if ($activeApp) {
            $attendanceToday = Attendance::where('application_id', $activeApp->id)
                                ->where('date', Carbon::now()->format('Y-m-d'))
                                ->first();
            $jamKerja = $activeApp->position->instansi;

            // Hitung statistik magang aktif
            $logsCount = $activeApp->logs()->count();
            $logsValidated = $activeApp->logs()->where('status_validasi', 'valid')->count();

            $attendances = Attendance::where('application_id', $activeApp->id)->get();
            $attendanceStats = [
                'hadir' => $attendances->where('status', 'hadir')->count(),
                'izin' => $attendances->whereIn('status', ['izin', 'sakit'])->count(),
                'alpa' => $attendances->where('status', 'alpa')->count(),
            ];

            // Hitung persentase durasi magang
            $startDate = Carbon::parse($activeApp->tanggal_mulai)->startOfDay();
            $endDate = Carbon::parse($activeApp->tanggal_selesai)->startOfDay();
            
            $totalDays = (int) $startDate->diffInDays($endDate) + 1;
            $elapsedDays = (int) $startDate->diffInDays(Carbon::now()->startOfDay(), false) + 1;
            
            $elapsedDays = max(0, min($elapsedDays, $totalDays));
            
            $progressPercent = $totalDays > 0 ? round(($elapsedDays / $totalDays) * 100) : 0;

            $stats = [
                'logs_count' => $logsCount,
                'logs_validated' => $logsValidated,
                'attendance' => $attendanceStats,
                'total_days' => $totalDays,
                'elapsed_days' => $elapsedDays,
                'progress_percent' => $progressPercent,
            ];

            // Hitung sisa hari magang
            $daysRemaining = Carbon::now()->startOfDay()->diffInDays($endDate->startOfDay(), false);
        }

        return view('peserta.dashboard', compact('myApplications', 'activeApp', 'attendanceToday', 'jamKerja', 'stats', 'daysRemaining'));
    }

    // ... (Sisa method downloadCertificate dll sama seperti sebelumnya) ...
    public function downloadCertificate()
    {
        $user = Auth::user();
        
        $finishedApp = Application::where('user_id', $user->id)
                        ->where('status', 'selesai')
                        ->with(['position.instansi', 'user'])
                        ->latest('updated_at')
                        ->first();

        if (!$finishedApp) {
            return back()->with('error', 'Anda belum menyelesaikan program magang manapun.');
        }

        if (empty($user->nik) || empty($user->asal_instansi)) {
            return redirect()->route('profile.edit')->with('error', 'Mohon lengkapi NIK dan Asal Instansi sebelum download sertifikat.');
        }

        $pdf = Pdf::loadView('pdf.sertifikat', ['app' => $finishedApp]);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream('Sertifikat-Magang-'.$user->name.'.pdf');
    }

    public function downloadLoA($id)
    {
        // 1. Ambil data aplikasi milik user yang sedang login
        $app = Application::with(['user', 'position.instansi', 'pembimbing_lapangan'])
                    ->where('id', $id)
                    ->where('user_id', Auth::id()) // Keamanan: Hanya milik sendiri
                    ->firstOrFail();

        // 2. Validasi Status
        if ($app->status != 'diterima' && $app->status != 'selesai') {
            return back()->with('error', 'Surat hanya tersedia bagi peserta yang sudah DITERIMA.');
        }

        // 3. Generate PDF
        $pdf = Pdf::loadView('pdf.loa', compact('app'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->stream('LoA_' . $app->user->name . '.pdf');
    }

    public function downloadIdCard($id)
    {
        // 1. Fetch application data
        $app = Application::with(['user', 'position.instansi'])
                    ->where('id', $id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

        // 2. Validation
        if (!in_array($app->status, ['diterima', 'selesai'])) {
            return back()->with('error', 'ID Card hanya tersedia bagi peserta yang sudah DITERIMA.');
        }

        if (empty($app->user->nik) || empty($app->user->asal_instansi)) {
            return redirect()->route('profile.edit')->with('error', 'Mohon lengkapi NIK dan Asal Instansi sebelum mencetak ID Card.');
        }

        // 3. Generate PDF
        $pdf = Pdf::loadView('pdf.id_card', compact('app'));
        // Standard CR80 ID Card size: 54mm x 85.6mm -> approx 153.07 pt x 242.64 pt
        $pdf->setPaper([0, 0, 153.07, 242.64], 'portrait');
        
        return $pdf->stream('ID_Card_' . $app->user->name . '.pdf');
    }

    /**
     * AJAX CHECK AVAILABILITY
     * Dipanggil oleh JavaScript di form apply untuk cek kuota real-time.
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

        // Query untuk mencari peserta yang bentrok
        // Kita simpan query builder-nya dulu agar bisa digunakan untuk count() dan get()
        $conflictingAppsQuery = Application::where('internship_position_id', $id)
            ->whereIn('status', ['diterima', 'pending'])
            ->where(function($q) use ($reqStart, $reqEnd) {
                $q->where('tanggal_mulai', '<=', $reqEnd)
                  ->where('tanggal_selesai', '>=', $reqStart);
            });

        // Hitung jumlah yang bentrok
        $bentrokCount = $conflictingAppsQuery->count();

        $isAvailable = $bentrokCount < $kapasitasMaksimal;

        if ($isAvailable) {
            return response()->json([
                'status' => 'available',
                'message' => "Kuota Tersedia! (Terisi: {$bentrokCount} dari {$kapasitasMaksimal} kursi)",
                'class' => 'text-green-600 bg-green-50 border-green-200'
            ]);
        } else {
            // JIKA PENUH: Cari peserta terakhir yang selesai magang
            // Ambil data peserta yang bentrok, urutkan berdasarkan tanggal selesai paling akhir (descending)
            $lastParticipant = $conflictingAppsQuery->orderBy('tanggal_selesai', 'desc')->first();
            
            $suggestionMessage = "";
            $suggestionDate = "";
            $suggestionDateText = "";

            if ($lastParticipant) {
                // Ambil tanggal selesai peserta tsb
                $finishDate = Carbon::parse($lastParticipant->tanggal_selesai);
                
                // Tanggal kosong adalah besoknya (H+1)
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
                'class' => 'text-red-600 bg-red-50 border-red-200',
                // Data tambahan untuk frontend (tombol saran)
                'suggestion_date' => $suggestionDate, 
                'suggestion_text' => $suggestionDateText
            ]);
        }
    }

    public function downloadTranskrip($id)
    {
        // Ambil data aplikasi milik peserta yang sedang login
        $app = Application::where('id', $id)
                    ->where('user_id', Auth::id()) // Keamanan: Pastikan data milik user sendiri
                    ->firstOrFail();

        // Validasi: Cek apakah sudah dinilai dan status selesai
        if ($app->status !== 'selesai' || !$app->nilai_rata_rata) {
            return back()->with('error', 'Transkrip belum tersedia. Tunggu penilaian dari pembimbing_lapangan.');
        }

        // Generate PDF
        $pdf = Pdf::loadView('pdf.transkrip_nilai', compact('app'));
        return $pdf->stream('Transkrip-Magang-'.$app->user->name.'.pdf');
    }

    public function showAutomaticApplyForm()
    {
        $user = Auth::user();

        // Cek apakah sudah melamar di posisi lain dengan status aktif
        $existingApp = Application::where('user_id', $user->id)
                        ->whereIn('status', ['pending', 'diterima'])
                        ->exists();

        if ($existingApp) {
            return redirect()->route('peserta.dashboard')->with('error', 'Anda sudah memiliki pendaftaran magang yang aktif.');
        }

        return view('peserta.apply_automatic');
    }

    public function storeAutomaticApplication(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi Input
        $request->validate([
            'surat' => 'required|mimes:pdf|max:2048',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        $reqStart = $request->tanggal_mulai;
        $reqEnd   = $request->tanggal_selesai;

        // 2. Cek pendaftaran aktif
        $existingApp = Application::where('user_id', $user->id)
                        ->whereIn('status', ['pending', 'diterima'])
                        ->exists();

        if ($existingApp) {
            return redirect()->route('peserta.dashboard')->with('error', 'Anda sudah memiliki pendaftaran magang yang aktif.');
        }

        // 3. Ambil semua lowongan buka
        $openPositions = InternshipPosition::with('instansi')
                            ->where('status', 'buka')
                            ->where('kuota', '>', 0)
                            ->get();

        // 4. Filter berdasarkan jurusan peserta magang
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

        // 5. Cek kuota dan bentrok tanggal (Logika Hotel Booking)
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
                // Hitung total peserta magang (diterima + pending) di INSTANSI ini untuk balancing
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

        // 6. Urutkan berdasarkan INSTANSI yang memiliki peserta PALING SEDIKIT (Balancing)
        usort($availablePositions, function($a, $b) {
            return $a['instansi_interns_count'] <=> $b['instansi_interns_count'];
        });

        // Pilih posisi terbaik
        $selectedPosition = $availablePositions[0]['position'];

        // 7. Simpan Data Lamaran Otomatis
        $suratPath = $request->file('surat')->store('documents/surat', 'public');

        Application::create([
            'user_id' => $user->id,
            'internship_position_id' => $selectedPosition->id,
            'cv_path' => '-', 
            'surat_pengantar_path' => $suratPath,
            'status' => 'pending',
            'tanggal_mulai' => $reqStart,
            'tanggal_selesai' => $reqEnd,
            'is_automatic_placement' => true,
        ]);

        return redirect()->route('peserta.dashboard')->with('success', 'Pendaftaran berhasil! Anda telah otomatis ditempatkan di ' . $selectedPosition->instansi->nama_dinas . ' karena memiliki jumlah peserta magang paling sedikit saat ini.');
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