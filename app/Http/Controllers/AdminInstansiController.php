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
        
        // 1. Query Dasar
        $query = Application::with(['user', 'position', 'pembimbing_lapangan'])
            ->whereHas('position', function($q) use ($user) {
                $q->where('instansi_id', $user->instansi_id);
            });

        // 2. Filter Status
        if ($request->has('status') && $request->status != '') {
            if ($request->status === 'pending') {
                $query->whereIn('status', ['pending', 'menunggu']);
            } else {
                $query->where('status', $request->status);
            }
        }

        // 3. Filter Asal Sekolah/Universitas
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
                    ->orderBy('users.name', 'asc')
                    ->select('applications.*');
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

        $stats = [
            'total' => $applications->count(),
            'aktif' => $applications->where('status', 'diterima')->count(),
            'selesai' => $applications->where('status', 'selesai')->count(),
            'pending' => $applications->whereIn('status', ['pending', 'menunggu'])->count(),
            'ditolak' => $applications->where('status', 'ditolak')->count(),
            'total_kampus' => $applications->pluck('user.asal_instansi')->unique()->filter()->count()
        ];

        return view('dinas.laporan.rekap', compact('applications', 'stats'));
    }

    public function printRekap(Request $request)
    {
        $user = Auth::user();
        
        // 1. Query Dasar
        $query = Application::with(['user', 'position', 'pembimbing_lapangan'])
            ->whereHas('position', function($q) use ($user) {
                $q->where('instansi_id', $user->instansi_id);
            });

        // 2. Filter Status
        if ($request->has('status') && $request->status != '') {
            if ($request->status === 'pending') {
                $query->whereIn('status', ['pending', 'menunggu']);
            } else {
                $query->where('status', $request->status);
            }
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
        $instansi = $user->instansi; 

        $stats = [
            'total' => $applications->count(),
            'aktif' => $applications->where('status', 'diterima')->count(),
            'selesai' => $applications->where('status', 'selesai')->count(),
            'pending' => $applications->whereIn('status', ['pending', 'menunggu'])->count(),
            'ditolak' => $applications->where('status', 'ditolak')->count(),
            'total_kampus' => $applications->pluck('user.asal_instansi')->unique()->filter()->count()
        ];

        // Generate PDF
        $pdf = Pdf::loadView('dinas.pdf.rekap_peserta', compact('applications', 'instansi', 'request', 'stats'));
        $pdf->setPaper('a4', 'landscape'); 
        
        return $pdf->stream('Laporan_Rekap_Peserta.pdf');
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

    // --- 1. LAPORAN KINERJA MAHASISWA ---
    public function laporanKinerjaMahasiswa()
    {
        $instansiId = Auth::user()->instansi_id;
        $kinerja = Application::whereHas('position', function($q) use ($instansiId) {
            $q->where('instansi_id', $instansiId);
        })->whereIn('status', ['diterima', 'selesai'])
        ->with(['user', 'position', 'logs', 'attendances', 'pembimbing_lapangan'])
        ->get()->map(function($app) {
            // Hitung Logbook
            $total_logs = $app->logs->count();
            $approved_logs = $app->logs->where('status_validasi', 'disetujui')->count();
            $log_rate = $total_logs > 0 ? ($approved_logs / $total_logs) * 100 : 0;

            // Hitung Kehadiran (Attendances)
            $total_attendance = $app->attendances->count();
            $hadir = $app->attendances->where('status', 'hadir')->count();
            $attendance_rate = $total_attendance > 0 ? ($hadir / $total_attendance) * 100 : 0;

            // Hitung Nilai Akhir
            $avg_nilai = 0;
            if ($app->nilai_rata_rata) {
                $avg_nilai = (float) $app->nilai_rata_rata;
            } else {
                $t = (float) $app->nilai_teknis;
                $d = (float) $app->nilai_disiplin;
                $p = (float) $app->nilai_perilaku;
                if ($t > 0 || $d > 0 || $p > 0) {
                    $avg_nilai = ($t + $d + $p) / 3;
                }
            }

            $app->log_rate = $log_rate;
            $app->attendance_rate = $attendance_rate;
            $app->avg_nilai = $avg_nilai;

            return $app;
        })->sortByDesc('avg_nilai');

        // Hitung statistik instansi keseluruhan
        $stats = [
            'total_peserta' => $kinerja->count(),
            'aktif' => $kinerja->where('status', 'diterima')->count(),
            'selesai' => $kinerja->where('status', 'selesai')->count(),
            'avg_kehadiran' => $kinerja->count() > 0 ? round($kinerja->avg('attendance_rate'), 1) : 0,
            'avg_logbook' => $kinerja->count() > 0 ? round($kinerja->avg('log_rate'), 1) : 0,
            'avg_nilai' => $kinerja->where('status', 'selesai')->where('avg_nilai', '>', 0)->count() > 0
                ? round($kinerja->where('status', 'selesai')->where('avg_nilai', '>', 0)->avg('avg_nilai'), 1)
                : 0
        ];

        return view('dinas.laporan.kinerja_mahasiswa', compact('kinerja', 'stats'));
    }

    public function printKinerjaMahasiswa()
    {
        $instansiId = Auth::user()->instansi_id;
        $kinerja = Application::whereHas('position', function($q) use ($instansiId) {
            $q->where('instansi_id', $instansiId);
        })->whereIn('status', ['diterima', 'selesai'])
        ->with(['user', 'position', 'logs', 'attendances', 'pembimbing_lapangan'])
        ->get()->map(function($app) {
            $total_logs = $app->logs->count();
            $approved_logs = $app->logs->where('status_validasi', 'disetujui')->count();
            $app->log_rate = $total_logs > 0 ? ($approved_logs / $total_logs) * 100 : 0;

            $total_attendance = $app->attendances->count();
            $hadir = $app->attendances->where('status', 'hadir')->count();
            $app->attendance_rate = $total_attendance > 0 ? ($hadir / $total_attendance) * 105 : 0; // limit at 100 inside PDF render if needed
            // Wait, let's keep it consistent:
            $app->attendance_rate = $total_attendance > 0 ? ($hadir / $total_attendance) * 100 : 0;

            $avg_nilai = 0;
            if ($app->nilai_rata_rata) {
                $avg_nilai = (float) $app->nilai_rata_rata;
            } else {
                $t = (float) $app->nilai_teknis;
                $d = (float) $app->nilai_disiplin;
                $p = (float) $app->nilai_perilaku;
                if ($t > 0 || $d > 0 || $p > 0) {
                    $avg_nilai = ($t + $d + $p) / 3;
                }
            }
            $app->avg_nilai = $avg_nilai;

            return $app;
        })->sortByDesc('avg_nilai');

        $stats = [
            'total_peserta' => $kinerja->count(),
            'aktif' => $kinerja->where('status', 'diterima')->count(),
            'selesai' => $kinerja->where('status', 'selesai')->count(),
            'avg_kehadiran' => $kinerja->count() > 0 ? round($kinerja->avg('attendance_rate'), 1) : 0,
            'avg_logbook' => $kinerja->count() > 0 ? round($kinerja->avg('log_rate'), 1) : 0,
            'avg_nilai' => $kinerja->where('status', 'selesai')->where('avg_nilai', '>', 0)->count() > 0
                ? round($kinerja->where('status', 'selesai')->where('avg_nilai', '>', 0)->avg('avg_nilai'), 1)
                : 0
        ];

        $pdf = Pdf::loadView('dinas.pdf.kinerja_mahasiswa', compact('kinerja', 'stats'));
        $pdf->setPaper('a4', 'landscape'); 
        return $pdf->stream('Laporan-Kinerja-Mahasiswa.pdf');
    }

    // --- 2. LAPORAN BEBAN & KINERJA PEMBIMBING LAPANGAN ---
    public function laporanBebanPembimbing()
    {
        $instansiId = Auth::user()->instansi_id;
        $beban = User::where('instansi_id', $instansiId)->where('role', 'pembimbing_lapangan')
            ->with(['bimbingan' => function($q) {
                $q->whereIn('status', ['diterima', 'selesai'])
                  ->with(['user', 'position', 'logs', 'attendances']);
            }])
            ->get()->map(function($pl) {
                $pl->total_bimbingan_aktif = $pl->bimbingan->where('status', 'diterima')->count();
                $pl->total_lulus = $pl->bimbingan->where('status', 'selesai')->count();
                
                $pending_logs = 0;
                $total_nilai = 0;
                $count_nilai = 0;

                // Detail mahasiswa aktif
                $pl->mahasiswa_aktif = $pl->bimbingan->where('status', 'diterima')->map(function($app) use (&$pending_logs) {
                    $total_l = $app->logs->count();
                    $approved_l = $app->logs->where('status_validasi', 'disetujui')->count();
                    $pending_l = $app->logs->where('status_validasi', 'pending')->count();
                    $revisi_l = $app->logs->where('status_validasi', 'revisi')->count();
                    $pending_logs += $pending_l;

                    $total_att = $app->attendances->count();
                    $hadir_att = $app->attendances->where('status', 'hadir')->count();
                    $pending_att = $app->attendances->where('validation_status', 'pending')->count();
                    
                    return [
                        'id' => $app->id,
                        'nama' => $app->user->name ?? '-',
                        'kampus' => $app->user->asal_instansi ?? '-',
                        'jurusan' => $app->user->major ?? '-',
                        'posisi' => $app->position->judul_posisi ?? '-',
                        'mulai' => $app->tanggal_mulai,
                        'selesai' => $app->tanggal_selesai,
                        'logbook' => [
                            'total' => $total_l,
                            'disetujui' => $approved_l,
                            'pending' => $pending_l,
                            'revisi' => $revisi_l,
                            'rate' => $total_l > 0 ? round(($approved_l / $total_l) * 100) : 0,
                        ],
                        'absensi' => [
                            'total' => $total_att,
                            'hadir' => $hadir_att,
                            'pending' => $pending_att,
                        ]
                    ];
                })->values();

                // Detail mahasiswa lulus
                $pl->mahasiswa_lulus = $pl->bimbingan->where('status', 'selesai')->map(function($app) use (&$total_nilai, &$count_nilai) {
                    $grade = 0;
                    if ($app->nilai_rata_rata) {
                        $grade = (float) $app->nilai_rata_rata;
                    } elseif ($app->nilai_teknis) {
                        $grade = ((float) $app->nilai_teknis + (float) $app->nilai_disiplin + (float) $app->nilai_perilaku) / 3;
                    }

                    if ($grade > 0) {
                        $total_nilai += $grade;
                        $count_nilai++;
                    }

                    return [
                        'nama' => $app->user->name ?? '-',
                        'kampus' => $app->user->asal_instansi ?? '-',
                        'jurusan' => $app->user->major ?? '-',
                        'posisi' => $app->position->judul_posisi ?? '-',
                        'nilai' => $grade > 0 ? round($grade, 2) : '-',
                        'predikat' => $app->predikat ?? '-',
                        'catatan' => $app->catatan_pembimbing_lapangan ?? '-',
                        'nomor_sertifikat' => $app->nomor_sertifikat ?? '-',
                    ];
                })->values();

                $pl->logbook_tertunda = $pending_logs;
                $pl->rata_nilai_diberikan = $count_nilai > 0 ? $total_nilai / $count_nilai : 0;

                return $pl;
            });

        // Hitung statistik instansi keseluruhan
        $stats = [
            'total_pembimbing' => $beban->count(),
            'total_bimbingan_aktif' => $beban->sum('total_bimbingan_aktif'),
            'total_lulus' => $beban->sum('total_lulus'),
            'total_logbook_tertunda' => $beban->sum('logbook_tertunda'),
            'rata_nilai_semua' => $beban->where('rata_nilai_diberikan', '>', 0)->count() > 0 
                ? $beban->where('rata_nilai_diberikan', '>', 0)->avg('rata_nilai_diberikan') 
                : 0,
            'pembimbing_teraktif' => $beban->sortByDesc('total_bimbingan_aktif')->first()?->name ?? '-',
            'pembimbing_teraktif_jumlah' => $beban->sortByDesc('total_bimbingan_aktif')->first()?->total_bimbingan_aktif ?? 0,
            'tertib_validasi' => $beban->where('logbook_tertunda', 0)->where('total_bimbingan_aktif', '>', 0)->count()
        ];

        return view('dinas.laporan.beban_pembimbing', compact('beban', 'stats'));
    }

    public function printBebanPembimbing()
    {
        $instansiId = Auth::user()->instansi_id;
        $beban = User::where('instansi_id', $instansiId)->where('role', 'pembimbing_lapangan')
            ->with(['bimbingan' => function($q) {
                $q->whereIn('status', ['diterima', 'selesai'])
                  ->with(['user', 'position', 'logs', 'attendances']);
            }])
            ->get()->map(function($pl) {
                $pl->total_bimbingan_aktif = $pl->bimbingan->where('status', 'diterima')->count();
                $pl->total_lulus = $pl->bimbingan->where('status', 'selesai')->count();
                
                $pending_logs = 0;
                $total_nilai = 0;
                $count_nilai = 0;

                $pl->mahasiswa_aktif = $pl->bimbingan->where('status', 'diterima')->map(function($app) use (&$pending_logs) {
                    $total_l = $app->logs->count();
                    $approved_l = $app->logs->where('status_validasi', 'disetujui')->count();
                    $pending_l = $app->logs->where('status_validasi', 'pending')->count();
                    $revisi_l = $app->logs->where('status_validasi', 'revisi')->count();
                    $pending_logs += $pending_l;

                    $total_att = $app->attendances->count();
                    $hadir_att = $app->attendances->where('status', 'hadir')->count();
                    $pending_att = $app->attendances->where('validation_status', 'pending')->count();
                    
                    return [
                        'nama' => $app->user->name ?? '-',
                        'kampus' => $app->user->asal_instansi ?? '-',
                        'jurusan' => $app->user->major ?? '-',
                        'posisi' => $app->position->judul_posisi ?? '-',
                        'mulai' => $app->tanggal_mulai,
                        'selesai' => $app->tanggal_selesai,
                        'logbook' => [
                            'total' => $total_l,
                            'disetujui' => $approved_l,
                            'pending' => $pending_l,
                            'revisi' => $revisi_l,
                            'rate' => $total_l > 0 ? round(($approved_l / $total_l) * 100) : 0,
                        ],
                        'absensi' => [
                            'total' => $total_att,
                            'hadir' => $hadir_att,
                            'pending' => $pending_att,
                        ]
                    ];
                })->values();

                $pl->mahasiswa_lulus = $pl->bimbingan->where('status', 'selesai')->map(function($app) use (&$total_nilai, &$count_nilai) {
                    $grade = 0;
                    if ($app->nilai_rata_rata) {
                        $grade = (float) $app->nilai_rata_rata;
                    } elseif ($app->nilai_teknis) {
                        $grade = ((float) $app->nilai_teknis + (float) $app->nilai_disiplin + (float) $app->nilai_perilaku) / 3;
                    }

                    if ($grade > 0) {
                        $total_nilai += $grade;
                        $count_nilai++;
                    }

                    return [
                        'nama' => $app->user->name ?? '-',
                        'kampus' => $app->user->asal_instansi ?? '-',
                        'jurusan' => $app->user->major ?? '-',
                        'posisi' => $app->position->judul_posisi ?? '-',
                        'nilai' => $grade > 0 ? round($grade, 2) : '-',
                        'predikat' => $app->predikat ?? '-',
                        'catatan' => $app->catatan_pembimbing_lapangan ?? '-',
                        'nomor_sertifikat' => $app->nomor_sertifikat ?? '-',
                    ];
                })->values();

                $pl->logbook_tertunda = $pending_logs;
                $pl->rata_nilai_diberikan = $count_nilai > 0 ? $total_nilai / $count_nilai : 0;

                return $pl;
            });

        $stats = [
            'total_pembimbing' => $beban->count(),
            'total_bimbingan_aktif' => $beban->sum('total_bimbingan_aktif'),
            'total_lulus' => $beban->sum('total_lulus'),
            'total_logbook_tertunda' => $beban->sum('logbook_tertunda'),
            'rata_nilai_semua' => $beban->where('rata_nilai_diberikan', '>', 0)->count() > 0 
                ? $beban->where('rata_nilai_diberikan', '>', 0)->avg('rata_nilai_diberikan') 
                : 0,
            'pembimbing_teraktif' => $beban->sortByDesc('total_bimbingan_aktif')->first()?->name ?? '-',
            'pembimbing_teraktif_jumlah' => $beban->sortByDesc('total_bimbingan_aktif')->first()?->total_bimbingan_aktif ?? 0,
            'tertib_validasi' => $beban->where('logbook_tertunda', 0)->where('total_bimbingan_aktif', '>', 0)->count()
        ];

        $pdf = Pdf::loadView('dinas.pdf.beban_pembimbing', compact('beban', 'stats'));
        $pdf->setPaper('a4', 'landscape'); 
        return $pdf->stream('Laporan-Beban-Pembimbing.pdf');
    }

    // --- 3. LAPORAN DEMOGRAFI KAMPUS / SEKOLAH ---
    public function laporanDemografiKampus()
    {
        $instansiId = Auth::user()->instansi_id;
        
        $applications = Application::whereHas('position', function($q) use ($instansiId) {
            $q->where('instansi_id', $instansiId);
        })
        ->with(['user', 'position'])
        ->get();

        // 1. Demografi per Kampus
        $demografi = $applications
        ->groupBy(function($app) {
            return empty($app->user->asal_instansi) ? 'Lainnya / Tidak Diketahui' : $app->user->asal_instansi;
        })
        ->map(function($group, $kampus) {
            $diterima = $group->whereIn('status', ['diterima', 'selesai']);
            $selesai = $group->where('status', 'selesai');
            return [
                'total_pelamar' => $group->count(),
                'diterima' => $diterima->count(),
                'selesai' => $selesai->count(),
                'ditolak' => $group->where('status', 'ditolak')->count(),
                'pending' => $group->where('status', 'pending')->count(),
                'dibatalkan' => $group->whereIn('status', ['dibatalkan', 'dikeluarkan'])->count(),
                'acceptance_rate' => $group->count() > 0 ? round(($diterima->count() / $group->count()) * 100) : 0,
                'jurusan' => $group->groupBy(function($app) {
                    return $app->user->major ?? 'Tidak Diketahui';
                })->map->count()->sortByDesc(fn($v) => $v),
                'posisi' => $group->groupBy(function($app) {
                    return $app->position->judul_posisi ?? 'Tidak Diketahui';
                })->map->count()->sortByDesc(fn($v) => $v),
                'peserta_aktif' => $diterima->map(function($app) {
                    return [
                        'nama' => $app->user->name ?? '-',
                        'jurusan' => $app->user->major ?? '-',
                        'posisi' => $app->position->judul_posisi ?? '-',
                        'status' => $app->status,
                        'mulai' => $app->tanggal_mulai,
                        'selesai' => $app->tanggal_selesai,
                    ];
                })->values(),
            ];
        })
        ->sortByDesc('total_pelamar');

        // 2. Demografi per Jurusan
        $demografiJurusan = $applications
        ->groupBy(function($app) {
            return empty($app->user->major) ? 'Tidak Diketahui' : $app->user->major;
        })
        ->map(function($group) {
            $diterima = $group->whereIn('status', ['diterima', 'selesai']);
            return [
                'total' => $group->count(),
                'diterima' => $diterima->count(),
                'acceptance_rate' => $group->count() > 0 ? round(($diterima->count() / $group->count()) * 100) : 0,
            ];
        })
        ->sortByDesc('total');

        // 3. Statistik Ringkasan
        $stats = [
            'total_kampus' => $demografi->count(),
            'total_jurusan' => $demografiJurusan->count(),
            'total_pelamar' => $applications->count(),
            'total_diterima' => $applications->whereIn('status', ['diterima', 'selesai'])->count(),
            'total_selesai' => $applications->where('status', 'selesai')->count(),
            'total_ditolak' => $applications->where('status', 'ditolak')->count(),
            'total_pending' => $applications->where('status', 'pending')->count(),
            'kampus_terbanyak' => $demografi->count() > 0 ? $demografi->keys()->first() : '-',
            'kampus_terbanyak_jumlah' => $demografi->count() > 0 ? $demografi->first()['total_pelamar'] : 0,
        ];

        return view('dinas.laporan.demografi_kampus', compact('demografi', 'demografiJurusan', 'stats'));
    }

    public function printDemografiKampus()
    {
        $instansiId = Auth::user()->instansi_id;
        
        $applications = Application::whereHas('position', function($q) use ($instansiId) {
            $q->where('instansi_id', $instansiId);
        })
        ->with(['user', 'position'])
        ->get();

        $demografi = $applications
        ->groupBy(function($app) {
            return empty($app->user->asal_instansi) ? 'Lainnya / Tidak Diketahui' : $app->user->asal_instansi;
        })
        ->map(function($group) {
            $diterima = $group->whereIn('status', ['diterima', 'selesai']);
            $selesai = $group->where('status', 'selesai');
            return [
                'total_pelamar' => $group->count(),
                'diterima' => $diterima->count(),
                'selesai' => $selesai->count(),
                'ditolak' => $group->where('status', 'ditolak')->count(),
                'pending' => $group->where('status', 'pending')->count(),
                'dibatalkan' => $group->whereIn('status', ['dibatalkan', 'dikeluarkan'])->count(),
                'acceptance_rate' => $group->count() > 0 ? round(($diterima->count() / $group->count()) * 100) : 0,
                'jurusan' => $group->groupBy(function($app) {
                    return $app->user->major ?? 'Tidak Diketahui';
                })->map->count()->sortByDesc(fn($v) => $v),
                'peserta' => $diterima->map(function($app) {
                    return [
                        'nama' => $app->user->name ?? '-',
                        'jurusan' => $app->user->major ?? '-',
                        'posisi' => $app->position->judul_posisi ?? '-',
                        'status' => $app->status,
                    ];
                })->values(),
            ];
        })
        ->sortByDesc('total_pelamar');

        $demografiJurusan = $applications
        ->groupBy(function($app) {
            return empty($app->user->major) ? 'Tidak Diketahui' : $app->user->major;
        })
        ->map(function($group) {
            $diterima = $group->whereIn('status', ['diterima', 'selesai']);
            return [
                'total' => $group->count(),
                'diterima' => $diterima->count(),
                'acceptance_rate' => $group->count() > 0 ? round(($diterima->count() / $group->count()) * 100) : 0,
            ];
        })
        ->sortByDesc('total');

        $stats = [
            'total_kampus' => $demografi->count(),
            'total_jurusan' => $demografiJurusan->count(),
            'total_pelamar' => $applications->count(),
            'total_diterima' => $applications->whereIn('status', ['diterima', 'selesai'])->count(),
            'total_selesai' => $applications->where('status', 'selesai')->count(),
        ];

        $pdf = Pdf::loadView('dinas.pdf.demografi_kampus', compact('demografi', 'demografiJurusan', 'stats'));
        $pdf->setPaper('a4', 'landscape'); 
        return $pdf->stream('Laporan-Demografi-Kampus.pdf');
    }

    // --- 4. LAPORAN JURNAL HARIAN MAHASISWA ---
    public function laporanJurnalHarian(Request $request)
    {
        $instansiId = Auth::user()->instansi_id;
        $filter = $request->query('filter', 'semua');
        
        $query = \App\Models\DailyLog::whereHas('application.position', function($q) use ($instansiId) {
            $q->where('instansi_id', $instansiId);
        })
        ->with(['application.user', 'application.position', 'application.pembimbing_lapangan']);

        if ($filter == 'hari') {
            $query->whereDate('tanggal', \Carbon\Carbon::today());
        } elseif ($filter == 'minggu') {
            $query->whereBetween('tanggal', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()]);
        } elseif ($filter == 'bulan') {
            $query->whereMonth('tanggal', \Carbon\Carbon::now()->month)
                  ->whereYear('tanggal', \Carbon\Carbon::now()->year);
        }

        $jurnal = $query->orderBy('tanggal', 'desc')->get();

        $total_peserta_aktif = Application::whereHas('position', function($q) use ($instansiId) {
            $q->where('instansi_id', $instansiId);
        })->where('status', 'diterima')->count();

        $stats = [
            'total_jurnal' => $jurnal->count(),
            'disetujui' => $jurnal->where('status_validasi', 'disetujui')->count(),
            'pending' => $jurnal->where('status_validasi', 'pending')->count(),
            'revisi' => $jurnal->where('status_validasi', 'revisi')->count(),
            'total_peserta_aktif' => $total_peserta_aktif,
            'rasio_validasi' => $jurnal->count() > 0 
                ? round(($jurnal->where('status_validasi', 'disetujui')->count() / $jurnal->count()) * 100) 
                : 0
        ];

        return view('dinas.laporan.jurnal_harian', compact('jurnal', 'filter', 'stats'));
    }

    public function printJurnalHarian(Request $request)
    {
        $instansiId = Auth::user()->instansi_id;
        $filter = $request->query('filter', 'semua');
        
        $query = \App\Models\DailyLog::whereHas('application.position', function($q) use ($instansiId) {
            $q->where('instansi_id', $instansiId);
        })
        ->with(['application.user', 'application.position', 'application.pembimbing_lapangan']);

        $label_waktu = 'Semua Waktu';
        if ($filter == 'hari') {
            $query->whereDate('tanggal', \Carbon\Carbon::today());
            $label_waktu = 'Hari Ini (' . \Carbon\Carbon::today()->format('d M Y') . ')';
        } elseif ($filter == 'minggu') {
            $query->whereBetween('tanggal', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()]);
            $label_waktu = 'Minggu Ini (' . \Carbon\Carbon::now()->startOfWeek()->format('d M') . ' - ' . \Carbon\Carbon::now()->endOfWeek()->format('d M Y') . ')';
        } elseif ($filter == 'bulan') {
            $query->whereMonth('tanggal', \Carbon\Carbon::now()->month)
                  ->whereYear('tanggal', \Carbon\Carbon::now()->year);
            $label_waktu = 'Bulan Ini (' . \Carbon\Carbon::now()->format('F Y') . ')';
        }

        $jurnal = $query->orderBy('tanggal', 'desc')->get();

        $total_peserta_aktif = Application::whereHas('position', function($q) use ($instansiId) {
            $q->where('instansi_id', $instansiId);
        })->where('status', 'diterima')->count();

        $stats = [
            'total_jurnal' => $jurnal->count(),
            'disetujui' => $jurnal->where('status_validasi', 'disetujui')->count(),
            'pending' => $jurnal->where('status_validasi', 'pending')->count(),
            'revisi' => $jurnal->where('status_validasi', 'revisi')->count(),
            'total_peserta_aktif' => $total_peserta_aktif,
            'rasio_validasi' => $jurnal->count() > 0 
                ? round(($jurnal->where('status_validasi', 'disetujui')->count() / $jurnal->count()) * 100) 
                : 0
        ];

        $pdf = Pdf::loadView('dinas.pdf.jurnal_harian', compact('jurnal', 'label_waktu', 'stats'));
        $pdf->setPaper('a4', 'landscape'); 
        return $pdf->stream('Laporan-Jurnal-Harian.pdf');
    }
}