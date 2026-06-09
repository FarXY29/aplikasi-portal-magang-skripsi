<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\DailyLog;
use App\Models\InternshipPosition;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationAcceptedMail;
use App\Mail\ApplicationRejectedMail;


class AdminInstansiController extends Controller
{
    /**
     * Dashboard Utama Admin Dinas
     */
    public function index()
    {
        $user = Auth::user();
        $instansi = $user->instansi;

        // Ambil ID semua lowongan milik INSTANSI ini
        $positionIds = InternshipPosition::where('instansi_id', $instansi->id)->pluck('id');

        // 1. WIDGET STATUS (Khusus INSTANSI ini)
        $stats = Application::whereIn('internship_position_id', $positionIds)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $widget = [
            'pending'   => $stats['pending'] ?? 0,
            'active'    => $stats['diterima'] ?? 0,
            'completed' => $stats['selesai'] ?? 0,
            'rejected'  => $stats['ditolak'] ?? 0,
        ];

        // 2. TOP 5 SEKOLAH/KAMPUS (Khusus yang magang di INSTANSI ini)
        $topInstansi = DB::table('applications')
            ->join('users', 'applications.user_id', '=', 'users.id')
            ->whereIn('applications.internship_position_id', $positionIds) // Filter Lowongan INSTANSI
            ->whereIn('applications.status', ['diterima', 'selesai'])
            ->whereNotNull('users.asal_instansi')
            ->select('users.asal_instansi', DB::raw('count(*) as total_peserta'))
            ->groupBy('users.asal_instansi')
            ->orderByDesc('total_peserta')
            ->limit(5)
            ->get();

        // Data lowongan untuk tabel bawah (kode lama Anda mungkin seperti ini)
        $recentPositions = InternshipPosition::where('instansi_id', $instansi->id)->latest()->take(5)->get();

        return view('dinas.dashboard', compact('instansi', 'widget', 'topInstansi', 'recentPositions'));
    }

    // --- MANAJEMEN PEMBIMBING LAPANGAN (PEGAWAI DINAS) ---
    
    public function indexPembimbingLapangan()
    {
        $instansiId = Auth::user()->instansi_id;
        $pembimbing_lapangan = User::where('instansi_id', $instansiId)->where('role', 'pembimbing_lapangan')->get();
        return view('dinas.pembimbing_lapangan.index', compact('pembimbing_lapangan'));
    }

    public function storePembimbingLapangan(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'nip' => 'nullable' 
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pembimbing_lapangan',
            'instansi_id' => Auth::user()->instansi_id,
            'nik' => $request->nip
        ]);

        return back()->with('success', 'Akun Pembimbing Lapangan berhasil dibuat.');
    }

    public function editPembimbingLapangan($id)
    {
        $pembimbing_lapangan = User::where('id', $id)
                    ->where('instansi_id', Auth::user()->instansi_id)
                    ->where('role', 'pembimbing_lapangan')
                    ->firstOrFail();

        return view('dinas.pembimbing_lapangan.edit', compact('pembimbing_lapangan'));
    }

    public function updatePembimbingLapangan(Request $request, $id)
    {
        $pembimbing_lapangan = User::where('id', $id)
                    ->where('instansi_id', Auth::user()->instansi_id)
                    ->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$pembimbing_lapangan->id, 
            'nip' => 'nullable|string|max:20',
            'password' => 'nullable|min:6'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'nik' => $request->nip
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $pembimbing_lapangan->update($data);

        return redirect()->route('dinas.pembimbing_lapangan.index')->with('success', 'Data pembimbing_lapangan berhasil diperbarui.');
    }

    public function destroyPembimbingLapangan($id)
    {
        $user = User::where('id', $id)->where('instansi_id', Auth::user()->instansi_id)->firstOrFail();
        $user->delete();
        return back()->with('success', 'Akun pembimbing_lapangan dihapus.');
    }

    // --- MANAJEMEN PELAMAR ---

    public function applicants(Request $request)
    {
        $instansiId = Auth::user()->instansi_id;
        $query = Application::whereHas('position', function($q) use ($instansiId) {
            $q->where('instansi_id', $instansiId);
        })->with(['user', 'position'])->orderBy('created_at', 'desc');

        if ($request->has('status') && $request->status != 'semua') {
            $query->where('status', $request->status);
        }

        $applicants = $query->get();

        return view('dinas.pelamar', compact('applicants'));
    }

    public function downloadSurat($id)
    {
        $app = Application::with('position')->findOrFail($id);
        
        // Keamanan: Cek apakah pelamar ini melamar ke INSTANSI milik admin yang login
        if ($app->position->instansi_id != Auth::user()->instansi_id) {
            abort(403, 'Akses ditolak.');
        }

        if (!$app->surat_pengantar_path || !Storage::disk('public')->exists($app->surat_pengantar_path)) {
            return back()->with('error', 'Berkas surat pengantar tidak ditemukan.');
        }

        // Return PDF inline response so browser can display it
        return Storage::disk('public')->response($app->surat_pengantar_path);
    }

    /**
     * TERIMA PELAMAR (Logika Booking Hotel)
     */
    public function acceptApplicant($id)
    {
        $app = Application::with('position')->findOrFail($id);
        
        // Cek kapasitas (opsional, bisa dihapus jika ingin bypass)
        if ($app->position->kuota <= 0) {
            return back()->with('error', 'Peringatan: Posisi ini memiliki kapasitas 0 (Ditutup).');
        }

        // Update Status (Tanpa mengurangi kuota, Tanpa overwrite tanggal)
        $app->update([
            'status' => 'diterima',
        ]);

        // Kirim email notifikasi (dijalankan di background karena ShouldQueue)
        try {
            Mail::to($app->user->email)->send(new ApplicationAcceptedMail($app));
        } catch (\Exception $e) {
            // Log error tapi jangan gagalkan proses
            \Log::error('Gagal mengirim email penerimaan: ' . $e->getMessage());
        }

        return back()->with('success', 'Peserta diterima! Jadwal telah dikunci sesuai pengajuan, dan email pemberitahuan telah dikirim.');
    }

    public function rejectApplicant($id)
    {
        $app = Application::with('position.instansi', 'user')->findOrFail($id);
        $app->update(['status' => 'ditolak']);

        // Kirim email notifikasi penolakan
        try {
            Mail::to($app->user->email)->send(new ApplicationRejectedMail($app));
        } catch (\Exception $e) {
            \Log::error('Gagal mengirim email penolakan: ' . $e->getMessage());
        }

        return back()->with('success', 'Peserta ditolak dan email pemberitahuan telah dikirim.');
    }

    // --- MANAJEMEN LOWONGAN ---

    public function indexLowongan()
    {
        $instansiId = Auth::user()->instansi_id;
        $lowongans = InternshipPosition::where('instansi_id', $instansiId)->get();
        return view('dinas.lowongan.index', compact('lowongans'));
    }

    public function createLowongan() { return view('dinas.lowongan.create'); }

    public function editPejabat()
    {
        // Mengambil data INSTANSI milik user yang sedang login
        $instansi = Auth::user()->instansi;
        
        return view('dinas.profil.edit_pejabat', compact('instansi'));
    }

    /**
     * Memproses Update Data ke Database
     */
    public function updatePejabat(Request $request)
    {
        $request->validate([
            'nama_pejabat' => 'required|string|max:255',
            'nip_pejabat' => 'required|string|max:50',
            'jabatan_pejabat' => 'required|string|max:100',
            'ttd_kepala' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]); 

        $instansi = Auth::user()->instansi;

        $dataToUpdate = [
            'nama_pejabat' => $request->nama_pejabat,
            'nip_pejabat' => $request->nip_pejabat,
            'jabatan_pejabat' => $request->jabatan_pejabat,
        ];

        // 2. Proses Upload Tanda Tangan
        if ($request->hasFile('ttd_kepala')) {
            // Hapus file lama jika ada
            if ($instansi->ttd_kepala && Storage::exists('public/' . $instansi->ttd_kepala)) {
                Storage::delete('public/' . $instansi->ttd_kepala);
            }

            // Simpan file baru ke folder 'signatures' di storage publik
            $path = $request->file('ttd_kepala')->store('signatures', 'public');
            
            // Masukkan path ke array data yang akan diupdate
            $dataToUpdate['ttd_kepala'] = $path;
        }

        // 3. Update Database
        $instansi->update($dataToUpdate);

        return back()->with('success', 'Data pejabat penandatangan berhasil diperbarui!');
    }
    public function storeLowongan(Request $request)
    {
        // 1. Hapus Validasi 'judul_posisi' dan 'batas_daftar'
        $request->validate([
            'required_major' => 'required',
            'deskripsi' => 'nullable|string',
            'kuota' => 'required|numeric',
        ]);

        InternshipPosition::create([
            'instansi_id' => Auth::user()->instansi_id,
            'judul_posisi' => 'Peserta Magang', // Default Value
            'required_major' => $request->required_major,
            'deskripsi' => $request->deskripsi,
            'kuota' => $request->kuota,
            'batas_daftar' => null, // Default NULL
            'status' => 'buka'
        ]);

        return redirect()->route('dinas.lowongan.index')->with('success', 'Lowongan berhasil dibuat!');
    }

    public function editLowongan($id)
    {
        $loker = InternshipPosition::where('id', $id)
                    ->where('instansi_id', Auth::user()->instansi_id)
                    ->firstOrFail();

        return view('dinas.lowongan.edit', compact('loker'));
    }

    public function updateLowongan(Request $request, $id)
    {
        $loker = InternshipPosition::where('id', $id)
                    ->where('instansi_id', Auth::user()->instansi_id)
                    ->firstOrFail();

        // 1. Hapus Validasi 'judul_posisi' dan 'batas_daftar'
        $request->validate([
            // 'judul_posisi' => 'required', // DIHAPUS
            'required_major' => 'required',
            'deskripsi' => 'nullable|string',
            'kuota' => 'required|numeric',
            // 'batas_daftar' => 'required|date', // DIHAPUS
            'status' => 'required|in:buka,tutup'
        ]);

        $loker->update([
            'judul_posisi' => 'Peserta Magang', // Default Value
            'required_major' => $request->required_major,
            'deskripsi' => $request->deskripsi,
            'kuota' => $request->kuota,
            'batas_daftar' => null, // Default NULL
            'status' => $request->status
        ]);

        return redirect()->route('dinas.lowongan.index')->with('success', 'Lowongan berhasil diperbarui!');
    }

    public function destroyLowongan($id)
    {
        $loker = InternshipPosition::where('id', $id)->where('instansi_id', Auth::user()->instansi_id)->firstOrFail();
        $loker->delete();
        return back()->with('success', 'Lowongan dihapus.');
    }

    // --- MONITORING PESERTA & VALIDASI ---

    public function activeInterns(Request $request)
    {
        $instansiId = Auth::user()->instansi_id;
        $pembimbing_lapangan = User::where('instansi_id', $instansiId)->where('role', 'pembimbing_lapangan')->get();

        $status = $request->input('status', 'semua');

        $query = Application::whereHas('position', function($q) use ($instansiId) {
            $q->where('instansi_id', $instansiId);
        })
        ->with(['user', 'position', 'pembimbing_lapangan'])
        ->orderBy('status', 'asc');

        if ($status === 'aktif') {
            $query->where('status', 'diterima');
        } elseif ($status === 'selesai') {
            $query->where('status', 'selesai');
        } else {
            $query->whereIn('status', ['diterima', 'selesai']);
        }

        $interns = $query->get();

        // Count all active interns for the header stats (irrespective of selected filter)
        $activeCount = Application::whereHas('position', function($q) use ($instansiId) {
            $q->where('instansi_id', $instansiId);
        })
        ->where('status', 'diterima')
        ->count();

        return view('dinas.peserta.index', compact('interns', 'pembimbing_lapangan', 'activeCount'));
    }

    public function assignPembimbingLapangan(Request $request, $id)
    {
        $app = Application::findOrFail($id);
        if($app->position->instansi_id != Auth::user()->instansi_id) abort(403);

        $app->update(['pembimbing_lapangan_id' => $request->pembimbing_lapangan_id]);
        return back()->with('success', 'Pembimbing lapangan berhasil ditetapkan.');
    }

    public function finishIntern($id)
    {
        $app = Application::with(['user', 'position.instansi'])->findOrFail($id);
        if($app->position->instansi_id != Auth::user()->instansi_id) abort(403);
        
        $app->update([
            'status' => 'selesai',
        ]);
        
        // Send Email Notification
        try {
            \Illuminate\Support\Facades\Mail::to($app->user->email)->send(new \App\Mail\InternshipCompleted($app));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send internship completed email: ' . $e->getMessage());
        }
        
        return back()->with('success', 'Peserta berhasil diluluskan! Sertifikat kini tersedia.');
    }

    public function expelIntern($id)
    {
        $app = Application::where('id', $id)
            ->whereHas('position.instansi', function($q) {
                $q->where('instansi_id', Auth::user()->instansi_id);
            })
            ->firstOrFail();

        if ($app->status !== 'diterima') {
            return back()->with('error', 'Hanya peserta dengan status aktif yang dapat dikeluarkan.');
        }

        $app->update(['status' => 'dikeluarkan']);

        return back()->with('success', 'Peserta berhasil dikeluarkan dari magang.');
    }
    
    public function showLogbooks($applicationId)
    {
        $app = Application::with(['user', 'position'])->findOrFail($applicationId);
        if($app->position->instansi_id != Auth::user()->instansi_id) abort(403);

        $logs = DailyLog::where('application_id', $applicationId)->orderBy('tanggal', 'desc')->get();
        return view('dinas.peserta.detail', compact('app', 'logs'));
    }
    
    public function validateLogbook(Request $request, $id)
    {
        $log = DailyLog::findOrFail($id);
        $log->update([
            'status_validasi' => $request->status,
            'komentar_pembimbing_lapangan' => $request->komentar ?? null
        ]);
        return back()->with('success', 'Status logbook diperbarui.');
    }

    public function showAbsensi(Request $request, $id)
    {
        // Ambil data Aplikasi (Peserta) berdasarkan ID
        $app = Application::with(['user', 'position'])->findOrFail($id);

        // Keamanan: Pastikan peserta ini melamar di INSTANSI milik user yang sedang login
        if ($app->position->instansi_id != Auth::user()->instansi_id) {
            abort(403, 'Akses ditolak');
        }

        // Query Absensi (Gunakan model Attendance, bukan Absensi)
        $query = Attendance::where('application_id', $id)->orderBy('date', 'desc');

        // Filter Bulan (Jika ada input dropdown)
        if ($request->has('bulan') && $request->bulan != '') {
            $query->whereMonth('date', $request->bulan);
        }

        $absensi = $query->get();

        // Hitung Statistik (Dari semua data tanpa filter bulan)
        $allData = Attendance::where('application_id', $id)->get();

        $stats = [
            // Hitung Tepat Waktu (Hadir & Jam Masuk <= 08:00)
            'tepat_waktu' => $allData->filter(function ($item) {
                return $item->status == 'hadir' && $item->clock_in && $item->clock_in <= '08:00:00';
            })->count(),

            // Hitung Terlambat (Hadir & Jam Masuk > 08:00)
            'terlambat' => $allData->filter(function ($item) {
                return $item->status == 'hadir' && $item->clock_in && $item->clock_in > '08:00:00';
            })->count(),

            // Hitung Izin/Sakit
            'izin' => $allData->whereIn('status', ['izin', 'sakit'])->count(),

            // Hitung Alpha
            'alpha' => $allData->where('status', 'alpa')->count(),
        ];

        // Arahkan ke view yang sesuai
        return view('dinas.peserta.absensi', compact('app', 'absensi', 'stats'));
    }

    public function printAbsensi(Request $request, $id)
    {
        // 1. Ambil data Aplikasi dengan relasi bertingkat (position.instansi)
        $app = Application::with(['user', 'position.instansi', 'pembimbing_lapangan'])->findOrFail($id);

        // Keamanan: Cek hak akses INSTANSI
        // Akses data INSTANSI via position
        if ($app->position->instansi_id != Auth::user()->instansi_id) {
            abort(403);
        }

        // 2. Query Data Absensi
        $query = Attendance::where('application_id', $id)->orderBy('date', 'asc');

        if ($request->has('bulan') && $request->bulan != '') {
            $query->whereMonth('date', $request->bulan);
        }

        $data = $query->get();

        // 3. Generate PDF
        $pdf = Pdf::loadView('dinas.pdf.rekap_absensi', [
            'data'   => $data,
            'app'    => $app,
            'bulan'  => $request->bulan ? Carbon::create()->month($request->bulan)->translatedFormat('F') : 'Semua Periode'
        ]);

        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan-Absensi-' . $app->user->name . '.pdf');
    }

    // --- LAPORAN REKAPITULASI ---
    public function laporanRekap(Request $request)
    {
        $user = Auth::user();
        
        // 1. Query Dasar: Ambil aplikasi yang melamar ke INSTANSI user yang login
        $query = Application::with(['user', 'position'])
            ->whereHas('position', function($q) use ($user) {
                $q->where('instansi_id', $user->instansi_id); // Filter hanya INSTANSI login
            });

        // 2. Filter Status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // 3. Filter Asal Sekolah/Universitas (Instansi)
        if ($request->has('asal_instansi') && $request->asal_instansi != '') {
            $searchInstansi = $request->asal_instansi;
            $query->whereHas('user', function($q) use ($searchInstansi) {
                $q->where('asal_instansi', 'like', '%' . $searchInstansi . '%');
            });
        }

        // 4. Filter Periode (Berdasarkan Tanggal Mulai atau Selesai yang beririsan)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = $request->start_date;
            $end = $request->end_date;
            
            $query->where(function($q) use ($start, $end) {
                // Logika: Tampilkan jika tanggal magang peserta beririsan dengan filter
                $q->whereBetween('tanggal_mulai', [$start, $end])
                ->orWhereBetween('tanggal_selesai', [$start, $end]);
            });
        }

        // 5. Sorting (Urutan Nama)
        // Kita perlu JOIN table users karena kolom 'name' ada di tabel users, bukan applications
        if ($request->has('sort')) {
            $sort = $request->sort;
            if ($sort == 'name_asc') {
                $query->join('users', 'applications.user_id', '=', 'users.id')
                    ->orderBy('users.name', 'asc')
                    ->select('applications.*'); // Penting: Select apps saja agar ID tidak tertimpa
            } elseif ($sort == 'name_desc') {
                $query->join('users', 'applications.user_id', '=', 'users.id')
                    ->orderBy('users.name', 'desc')
                    ->select('applications.*');
            } else {
                $query->latest();
            }
        } else {
            $query->latest();
        }

        $applications = $query->get();

        return view('dinas.laporan.rekap', compact('applications'));
    }

    

    public function printRekap(Request $request)
    {
        $user = Auth::user();
        
        // 1. Query Dasar
        $query = Application::with(['user', 'position'])
            ->whereHas('position', function($q) use ($user) {
                $q->where('instansi_id', $user->instansi_id);
            });

        // 2. Filter Status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // 3. Filter Asal Sekolah
        if ($request->has('asal_instansi') && $request->asal_instansi != '') {
            $searchInstansi = $request->asal_instansi;
            $query->whereHas('user', function($q) use ($searchInstansi) {
                $q->where('asal_instansi', 'like', '%' . $searchInstansi . '%');
            });
        }

        // 4. Filter Periode
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = $request->start_date;
            $end = $request->end_date;
            $query->where(function($q) use ($start, $end) {
                $q->whereBetween('tanggal_mulai', [$start, $end])
                  ->orWhereBetween('tanggal_selesai', [$start, $end]);
            });
        }

        // 5. Sorting
        if ($request->has('sort')) {
            $sort = $request->sort;
            if ($sort == 'name_asc') {
                $query->join('users', 'applications.user_id', '=', 'users.id')
                      ->orderBy('users.name', 'asc')->select('applications.*');
            } elseif ($sort == 'name_desc') {
                $query->join('users', 'applications.user_id', '=', 'users.id')
                      ->orderBy('users.name', 'desc')->select('applications.*');
            } else {
                $query->latest();
            }
        } else {
            $query->latest();
        }

        $applications = $query->get();
        $instansi = $user->instansi; // Data dinas untuk kop surat

        // Generate PDF
        $pdf = Pdf::loadView('dinas.pdf.rekap_peserta', compact('applications', 'instansi', 'request'));
        $pdf->setPaper('a4', 'landscape'); // Landscape agar tabel muat
        
        return $pdf->stream('Laporan_Rekap_Peserta.pdf');
    }

    // --- LAPORAN GRADING ---

    public function laporanGradingDinas()
    {
        $instansiId = Auth::user()->instansi_id;

        $query = Application::with(['user', 'position'])
                    ->whereHas('position', fn($q) => $q->where('instansi_id', $instansiId))
                    ->whereNotNull('nilai_teknis')
                    ->get();

        $gradedData = $query->map(function($app) {
            $t = (float) $app->nilai_teknis;
            $d = (float) $app->nilai_disiplin;
            $p = (float) $app->nilai_perilaku;
            $avg = ($t + $d + $p) / 3;

            return [
                'nama' => $app->user->name,
                'posisi' => $app->position->judul_posisi,
                'teknis' => $t,
                'disiplin' => $d,
                'perilaku' => $p,
                'rata_rata' => round($avg, 2),
                'predikat' => $avg >= 86 ? 'Sangat Baik' : ($avg >= 71 ? 'Baik' : 'Cukup')
            ];
        });

        $ranking = $gradedData->sortByDesc('rata_rata')->values();
        
        $distribusi = [
            'Sangat Baik' => $gradedData->where('predikat', 'Sangat Baik')->count(),
            'Baik' => $gradedData->where('predikat', 'Baik')->count(),
            'Cukup' => $gradedData->where('predikat', 'Cukup')->count(),
        ];

        // PERBAIKAN: Menghitung statistik global khusus dinas ini
        $statsGlobal = [
            'avg_teknis' => $gradedData->count() > 0 ? round($gradedData->avg('teknis'), 1) : 0,
            'avg_disiplin' => $gradedData->count() > 0 ? round($gradedData->avg('disiplin'), 1) : 0,
            'avg_perilaku' => $gradedData->count() > 0 ? round($gradedData->avg('perilaku'), 1) : 0,
        ];

        // PERBAIKAN: Sertakan statsGlobal dalam compact()
        return view('dinas.laporan.grading', compact('ranking', 'distribusi', 'statsGlobal'));
    }

    public function printGradingDinas()
    {
        $instansiId = Auth::user()->instansi_id;

        $query = Application::with(['user', 'position'])
                    ->whereHas('position', fn($q) => $q->where('instansi_id', $instansiId))
                    ->whereNotNull('nilai_teknis')
                    ->get();

        $gradedData = $query->map(function($app) {
            $t = (float) $app->nilai_teknis;
            $d = (float) $app->nilai_disiplin;
            $p = (float) $app->nilai_perilaku;
            $avg = ($t + $d + $p) / 3;

            return [
                'nama' => $app->user->name,
                'posisi' => $app->position->judul_posisi,
                'teknis' => $t,
                'disiplin' => $d,
                'perilaku' => $p,
                'rata_rata' => round($avg, 2),
                'predikat' => $avg >= 86 ? 'Sangat Baik' : ($avg >= 71 ? 'Baik' : 'Cukup')
            ];
        });

        $ranking = $gradedData->sortByDesc('rata_rata')->values();
        
        $distribusi = [
            'Sangat Baik' => $gradedData->where('predikat', 'Sangat Baik')->count(),
            'Baik' => $gradedData->where('predikat', 'Baik')->count(),
            'Cukup' => $gradedData->where('predikat', 'Cukup')->count(),
        ];

        $statsGlobal = [
            'avg_teknis' => $gradedData->count() > 0 ? round($gradedData->avg('teknis'), 1) : 0,
            'avg_disiplin' => $gradedData->count() > 0 ? round($gradedData->avg('disiplin'), 1) : 0,
            'avg_perilaku' => $gradedData->count() > 0 ? round($gradedData->avg('perilaku'), 1) : 0,
        ];

        $pdf = Pdf::loadView('dinas.pdf.grading', compact('ranking', 'distribusi', 'statsGlobal'));
        $pdf->setPaper('a4', 'portrait'); 
        return $pdf->stream('Laporan-Grading-Dinas.pdf');
    }

    // Pengaturan
    public function settings()
    {
        $instansi = Auth::user()->instansi;
        return view('dinas.settings', compact('instansi'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'jam_mulai_masuk' => 'required',
            'jam_mulai_pulang' => 'required',
        ]);

        $instansi = Auth::user()->instansi;
        
        $instansi->update([
            'jam_mulai_masuk' => $request->jam_mulai_masuk,
            'jam_mulai_pulang' => $request->jam_mulai_pulang,
        ]);

        return back()->with('success', 'Pengaturan jam kerja berhasil diperbarui.');
    }

    // --- PUSAT LAPORAN HUB ---
    public function laporanHub()
    {
        return view('dinas.laporan_hub');
    }

    // --- 8 BARU: LAPORAN EVALUASI PEMBIMBING LAPANGAN ---
    public function laporanEvaluasiPembimbing()
    {
        $instansiId = Auth::user()->instansi_id;
        $evaluasi = User::where('instansi_id', $instansiId)->where('role', 'pembimbing_lapangan')
            ->withCount(['bimbingan as total_bimbingan' => function($q) {
                $q->whereIn('status', ['diterima', 'selesai']);
            }])
            ->with(['bimbingan' => function($q) {
                $q->whereIn('status', ['diterima', 'selesai'])->whereNotNull('nilai_angka');
            }])
            ->get()->map(function($pl) {
                $pl->rata_nilai = $pl->bimbingan->avg('nilai_angka');
                return $pl;
            });
        return view('dinas.laporan.evaluasi_pembimbing', compact('evaluasi'));
    }

    public function printEvaluasiPembimbing()
    {
        $instansiId = Auth::user()->instansi_id;
        $evaluasi = User::where('instansi_id', $instansiId)->where('role', 'pembimbing_lapangan')
            ->withCount(['bimbingan as total_bimbingan' => function($q) {
                $q->whereIn('status', ['diterima', 'selesai']);
            }])
            ->with(['bimbingan' => function($q) {
                $q->whereIn('status', ['diterima', 'selesai'])->whereNotNull('nilai_angka');
            }])
            ->get()->map(function($pl) {
                $pl->rata_nilai = $pl->bimbingan->avg('nilai_angka');
                return $pl;
            });
            
        $pdf = Pdf::loadView('dinas.pdf.evaluasi_pembimbing', compact('evaluasi'));
        $pdf->setPaper('a4', 'portrait'); 
        return $pdf->stream('Laporan-Evaluasi-Pembimbing.pdf');
    }

    // --- 9 BARU: LAPORAN TREN PENDAFTARAN ---
    public function laporanTrenPendaftaran()
    {
        $instansiId = Auth::user()->instansi_id;
        $tren = Application::whereHas('position', function($q) use ($instansiId) {
            $q->where('instansi_id', $instansiId);
        })->select(DB::raw('MONTH(created_at) as bulan'), DB::raw('YEAR(created_at) as tahun'), DB::raw('count(*) as total'))
          ->groupBy('tahun', 'bulan')
          ->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')
          ->get();
        return view('dinas.laporan.tren_pendaftaran', compact('tren'));
    }

    public function printTrenPendaftaran()
    {
        $instansiId = Auth::user()->instansi_id;
        $tren = Application::whereHas('position', function($q) use ($instansiId) {
            $q->where('instansi_id', $instansiId);
        })->select(DB::raw('MONTH(created_at) as bulan'), DB::raw('YEAR(created_at) as tahun'), DB::raw('count(*) as total'))
          ->groupBy('tahun', 'bulan')
          ->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')
          ->get();
          
        $pdf = Pdf::loadView('dinas.pdf.tren_pendaftaran', compact('tren'));
        $pdf->setPaper('a4', 'portrait'); 
        return $pdf->stream('Laporan-Tren-Pendaftaran.pdf');
    }

    // --- 10 BARU: LAPORAN PRODUKTIVITAS LOGBOOK ---
    public function laporanProduktivitasLogbook()
    {
        $instansiId = Auth::user()->instansi_id;
        $produktivitas = Application::whereHas('position', function($q) use ($instansiId) {
            $q->where('instansi_id', $instansiId);
        })->whereIn('status', ['diterima', 'selesai'])
        ->withCount('logs')
        ->with(['user', 'position', 'logs' => function($q) {
            $q->where('status_validasi', 'disetujui');
        }])
        ->get()->map(function($app) {
            $app->approved_logs = $app->logs->count();
            $app->approval_rate = $app->logs_count > 0 ? ($app->approved_logs / $app->logs_count) * 100 : 0;
            return $app;
        })->sortByDesc('logs_count');
        return view('dinas.laporan.produktivitas_logbook', compact('produktivitas'));
    }

    public function printProduktivitasLogbook()
    {
        $instansiId = Auth::user()->instansi_id;
        $produktivitas = Application::whereHas('position', function($q) use ($instansiId) {
            $q->where('instansi_id', $instansiId);
        })->whereIn('status', ['diterima', 'selesai'])
        ->withCount('logs')
        ->with(['user', 'position', 'logs' => function($q) {
            $q->where('status_validasi', 'disetujui');
        }])
        ->get()->map(function($app) {
            $app->approved_logs = $app->logs->count();
            $app->approval_rate = $app->logs_count > 0 ? ($app->approved_logs / $app->logs_count) * 100 : 0;
            return $app;
        })->sortByDesc('logs_count');
        
        $pdf = Pdf::loadView('dinas.pdf.produktivitas_logbook', compact('produktivitas'));
        $pdf->setPaper('a4', 'portrait'); 
        return $pdf->stream('Laporan-Produktivitas-Logbook.pdf');
    }

    // --- 9 BARU: LAPORAN KOTAK SARAN (ANONIM) ---
    public function laporanSaranPeserta()
    {
        $instansiId = Auth::user()->instansi_id;
        
        // Hanya ambil aplikasi yang sudah selesai dan memiliki saran, urutkan dari yang terbaru
        $sarans = Application::with('position')
                    ->whereHas('position', function($q) use ($instansiId) {
                        $q->where('instansi_id', $instansiId);
                    })
                    ->where('status', 'selesai')
                    ->whereNotNull('saran_peserta')
                    ->where('saran_peserta', '!=', '')
                    ->orderBy('updated_at', 'desc')
                    ->get();

        return view('dinas.laporan.saran_peserta', compact('sarans'));
    }

    public function printSaranPeserta()
    {
        $instansiId = Auth::user()->instansi_id;
        
        $sarans = Application::with('position')
                    ->whereHas('position', function($q) use ($instansiId) {
                        $q->where('instansi_id', $instansiId);
                    })
                    ->where('status', 'selesai')
                    ->whereNotNull('saran_peserta')
                    ->where('saran_peserta', '!=', '')
                    ->orderBy('updated_at', 'desc')
                    ->get();

        $pdf = Pdf::loadView('dinas.pdf.saran_peserta', compact('sarans'));
        $pdf->setPaper('a4', 'portrait'); 
        return $pdf->stream('Laporan-Saran-Peserta.pdf');
    }

    // --- 9 BARU: LAPORAN KETERISIAN POSISI (OCCUPANCY RATE) ---
    public function laporanKeterisianPosisi()
    {
        $instansiId = Auth::user()->instansi_id;
        $keterisian = InternshipPosition::where('instansi_id', $instansiId)
            ->withCount(['applications as total_pelamar', 'applications as diterima' => function($q) {
                $q->whereIn('status', ['diterima', 'selesai']);
            }])->get()->map(function($pos) {
                $pos->occupancy_rate = $pos->kuota > 0 ? ($pos->diterima / $pos->kuota) * 100 : 0;
                return $pos;
            });
        return view('dinas.laporan.keterisian_posisi', compact('keterisian'));
    }

    public function printKeterisianPosisi()
    {
        $instansiId = Auth::user()->instansi_id;
        $keterisian = InternshipPosition::where('instansi_id', $instansiId)
            ->withCount(['applications as total_pelamar', 'applications as diterima' => function($q) {
                $q->whereIn('status', ['diterima', 'selesai']);
            }])->get()->map(function($pos) {
                $pos->occupancy_rate = $pos->kuota > 0 ? ($pos->diterima / $pos->kuota) * 100 : 0;
                return $pos;
            });
            
        $pdf = Pdf::loadView('dinas.pdf.keterisian_posisi', compact('keterisian'));
        $pdf->setPaper('a4', 'portrait'); 
        return $pdf->stream('Laporan-Keterisian-Posisi.pdf');
    }
}