<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;
use App\Models\Application;
use App\Models\InternshipPosition;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminKotaController extends Controller
{
    public function index()
    {
        $totalInstansi = Instansi::count();
        $totalUser = User::count();
        $totalApplications = Application::count();
        $activeInterns = Application::where('status', 'diterima')->count();
        $completedInterns = Application::where('status', 'selesai')->count();
        $pendingApplications = Application::where('status', 'pending')->count();
        $rejectedApplications = Application::where('status', 'ditolak')->count();
        
        $recentInstansis = Instansi::latest()->take(5)->get();
        $recentApplications = Application::with(['user', 'position.instansi'])->latest()->take(5)->get();

        // --- STATISTIK PELAMAR PER INSTANSI (UNTUK TABEL) ---
        $instansiStats = Instansi::withCount('applications')->orderByDesc('applications_count')->paginate(10);
        
        // Cari pelamar terbanyak untuk referensi progress bar di view
        $maxInstansi = Instansi::withCount('applications')->orderByDesc('applications_count')->first();
        $maxPelamar = $maxInstansi ? $maxInstansi->applications_count : 1;
        if ($maxPelamar == 0) $maxPelamar = 1;
        
        $statusLabels = ['Pending', 'Aktif', 'Selesai', 'Ditolak'];
        $statusData = [$pendingApplications, $activeInterns, $completedInterns, $rejectedApplications];
        
        return view('admin.dashboard', compact(
            'totalInstansi', 
            'totalUser', 
            'totalApplications',
            'activeInterns',
            'completedInterns',
            'pendingApplications',
            'recentInstansis', 
            'recentApplications',
            'instansiStats',
            'maxPelamar',
            'statusLabels',
            'statusData'
        ));
    }

    // --- MANAJEMEN INSTANSI ---

    public function indexInstansi()
    {
        // --- OPTIMASI: PAGINATION & EAGER LOADING ---
        // Gunakan paginate(10) agar tidak meload ratusan data sekaligus
        // Gunakan withCount untuk menghitung pelamar tanpa query n+1
        $instansis = Instansi::with('positions') // Muat posisi jika perlu ditampilkan list-nya
                    ->withCount('applications') // Hitung total pelamar
                    ->orderBy('nama_dinas', 'asc')
                    ->paginate(10); // Pagination halaman

        return view('admin.instansi.index', compact('instansis'));
    }

    public function create()
    {
        return view('admin.instansi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_dinas' => 'required|string|max:255',
            'kode_unit_kerja' => 'required|string|max:50|unique:instansis',
            'alamat' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius_absen' => 'required|numeric|min:10',
            'email_admin' => 'required|email|unique:users,email',
            'password_admin' => 'required|min:8',
        ]);

        $instansi = Instansi::create($request->only(['nama_dinas','kode_unit_kerja','alamat','latitude','longitude', 'radius_absen']));

        User::create([
            'name' => 'Admin ' . $request->nama_dinas,
            'email' => $request->email_admin,
            'password' => Hash::make($request->password_admin),
            'role' => 'admin_instansi',
            'instansi_id' => $instansi->id,
        ]);

        return redirect()->route('admin.instansi.index')->with('success', 'INSTANSI Baru & Akun Admin berhasil dibuat!');
    }

    public function edit($id)
    {
        $instansi = Instansi::findOrFail($id);
        $adminUser = User::where('instansi_id', $instansi->id)->where('role', 'admin_instansi')->first();
        return view('admin.instansi.edit', compact('instansi', 'adminUser'));
    }

    public function update(Request $request, $id)
    {
        $instansi = Instansi::findOrFail($id);
        $adminUser = User::where('instansi_id', $instansi->id)->where('role', 'admin_instansi')->first();

        $request->validate([
            'nama_dinas' => 'required|string|max:255',
            'kode_unit_kerja' => 'required|string|max:50|unique:instansis,kode_unit_kerja,'.$instansi->id, 
            'alamat' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius_absen' => 'required|numeric|min:10',
            'email_admin' => $adminUser ? 'required|email|unique:users,email,'.$adminUser->id : 'nullable|email|unique:users,email',
            'password_admin' => 'nullable|min:8',
        ]);

        $instansi->update($request->only(['nama_dinas','kode_unit_kerja','alamat','latitude','longitude', 'radius_absen']));

        if ($adminUser && $request->email_admin) {
            $adminUser->email = $request->email_admin;
            if ($request->filled('password_admin')) {
                $adminUser->password = Hash::make($request->password_admin);
            }
            $adminUser->save();
        } elseif (!$adminUser && $request->email_admin && $request->filled('password_admin')) {
            User::create([
                'name' => 'Admin ' . $request->nama_dinas,
                'email' => $request->email_admin,
                'password' => Hash::make($request->password_admin),
                'role' => 'admin_instansi',
                'instansi_id' => $instansi->id,
            ]);
        }

        return redirect()->route('admin.instansi.index')->with('success', 'Data INSTANSI berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $instansi = Instansi::findOrFail($id);
        User::where('instansi_id', $instansi->id)->delete();
        $instansi->delete();
        return back()->with('success', 'Data INSTANSI dan Akun Admin terkait telah dihapus.');
    }

    // --- LAPORAN & EXCEL ---
    
    public function report(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'pelamar_desc');

        $query = Instansi::with(['positions.applications']);
        
        if ($search) {
            $query->where('nama_dinas', 'like', '%' . $search . '%');
        }

        $instansis = $query->get();

        // 1. Hitung Statistik Global (seluruh kota)
        $allInstansis = Instansi::with(['positions.applications'])->get();
        $totalInstansi = $allInstansis->count();
        $totalLowongan = $allInstansis->flatMap->positions->where('status', 'buka')->count();
        
        $allApps = $allInstansis->flatMap->positions->flatMap->applications;
        $totalPelamar = $allApps->count();
        $totalDiterima = $allApps->whereIn('status', ['diterima', 'selesai'])->count();
        
        $avgSeleksiRate = $totalPelamar > 0 ? round(($totalDiterima / $totalPelamar) * 100, 1) : 0;
        
        // Instansi Terfavorit
        $favDinas = $allInstansis->map(function($d) {
            return [
                'nama' => $d->nama_dinas,
                'count' => $d->positions->flatMap->applications->count()
            ];
        })->sortByDesc('count')->first();
        $favDinasName = $favDinas && $favDinas['count'] > 0 ? $favDinas['nama'] : '-';

        $stats = [
            'total_instansi' => $totalInstansi,
            'total_lowongan' => $totalLowongan,
            'total_pelamar' => $totalPelamar,
            'total_diterima' => $totalDiterima,
            'avg_seleksi_rate' => $avgSeleksiRate,
            'fav_dinas' => $favDinasName
        ];

        // 2. Map data untuk dinas terfilter
        $laporan = $instansis->map(function($dinas) {
            $positions = $dinas->positions;
            $applications = $positions->flatMap->applications;
            
            $totalPelamar = $applications->count();
            $totalDiterima = $applications->whereIn('status', ['diterima', 'selesai'])->count();
            $totalPosisi = $positions->count();
            
            $seleksiRate = $totalPelamar > 0 ? round(($totalDiterima / $totalPelamar) * 100, 1) : 0;
            $avgPelamar = $totalPosisi > 0 ? round($totalPelamar / $totalPosisi, 1) : 0;

            return [
                'nama_dinas' => $dinas->nama_dinas,
                'lowongan_aktif' => $positions->where('status', 'buka')->count(),
                'total_pelamar' => $totalPelamar,
                'total_magang' => $totalDiterima,
                'seleksi_rate' => $seleksiRate,
                'avg_peminat' => $avgPelamar,
            ];
        });

        // 3. Sorting
        if ($sort === 'name_asc') {
            $laporan = $laporan->sortBy('nama_dinas');
        } elseif ($sort === 'name_desc') {
            $laporan = $laporan->sortByDesc('nama_dinas');
        } elseif ($sort === 'pelamar_asc') {
            $laporan = $laporan->sortBy('total_pelamar');
        } elseif ($sort === 'pelamar_desc') {
            $laporan = $laporan->sortByDesc('total_pelamar');
        } elseif ($sort === 'lowongan_desc') {
            $laporan = $laporan->sortByDesc('lowongan_aktif');
        } elseif ($sort === 'lowongan_asc') {
            $laporan = $laporan->sortBy('lowongan_aktif');
        } elseif ($sort === 'seleksi_desc') {
            $laporan = $laporan->sortByDesc('seleksi_rate');
        } elseif ($sort === 'seleksi_asc') {
            $laporan = $laporan->sortBy('seleksi_rate');
        } else {
            $laporan = $laporan->sortByDesc('total_pelamar');
        }

        $laporan = $laporan->values();

        return view('admin.laporan', compact('laporan', 'stats'));
    }

    public function printLaporan(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'pelamar_desc');

        $query = Instansi::with(['positions.applications']);
        
        if ($search) {
            $query->where('nama_dinas', 'like', '%' . $search . '%');
        }

        $instansis = $query->get();

        // 1. Hitung Statistik Global (seluruh kota)
        $allInstansis = Instansi::with(['positions.applications'])->get();
        $totalInstansi = $allInstansis->count();
        $totalLowongan = $allInstansis->flatMap->positions->where('status', 'buka')->count();
        
        $allApps = $allInstansis->flatMap->positions->flatMap->applications;
        $totalPelamar = $allApps->count();
        $totalDiterima = $allApps->whereIn('status', ['diterima', 'selesai'])->count();
        
        $avgSeleksiRate = $totalPelamar > 0 ? round(($totalDiterima / $totalPelamar) * 100, 1) : 0;
        
        // Instansi Terfavorit
        $favDinas = $allInstansis->map(function($d) {
            return [
                'nama' => $d->nama_dinas,
                'count' => $d->positions->flatMap->applications->count()
            ];
        })->sortByDesc('count')->first();
        $favDinasName = $favDinas && $favDinas['count'] > 0 ? $favDinas['nama'] : '-';

        $stats = [
            'total_instansi' => $totalInstansi,
            'total_lowongan' => $totalLowongan,
            'total_pelamar' => $totalPelamar,
            'total_diterima' => $totalDiterima,
            'avg_seleksi_rate' => $avgSeleksiRate,
            'fav_dinas' => $favDinasName
        ];

        // 2. Map data untuk dinas terfilter
        $laporan = $instansis->map(function($dinas) {
            $positions = $dinas->positions;
            $applications = $positions->flatMap->applications;
            
            $totalPelamar = $applications->count();
            $totalDiterima = $applications->whereIn('status', ['diterima', 'selesai'])->count();
            $totalPosisi = $positions->count();
            
            $seleksiRate = $totalPelamar > 0 ? round(($totalDiterima / $totalPelamar) * 100, 1) : 0;
            $avgPelamar = $totalPosisi > 0 ? round($totalPelamar / $totalPosisi, 1) : 0;

            return [
                'nama_dinas' => $dinas->nama_dinas,
                'lowongan_aktif' => $positions->where('status', 'buka')->count(),
                'total_pelamar' => $totalPelamar,
                'total_magang' => $totalDiterima,
                'seleksi_rate' => $seleksiRate,
                'avg_peminat' => $avgPelamar,
            ];
        });

        // 3. Sorting
        if ($sort === 'name_asc') {
            $laporan = $laporan->sortBy('nama_dinas');
        } elseif ($sort === 'name_desc') {
            $laporan = $laporan->sortByDesc('nama_dinas');
        } elseif ($sort === 'pelamar_asc') {
            $laporan = $laporan->sortBy('total_pelamar');
        } elseif ($sort === 'pelamar_desc') {
            $laporan = $laporan->sortByDesc('total_pelamar');
        } elseif ($sort === 'lowongan_desc') {
            $laporan = $laporan->sortByDesc('lowongan_aktif');
        } elseif ($sort === 'lowongan_asc') {
            $laporan = $laporan->sortBy('lowongan_aktif');
        } elseif ($sort === 'seleksi_desc') {
            $laporan = $laporan->sortByDesc('seleksi_rate');
        } elseif ($sort === 'seleksi_asc') {
            $laporan = $laporan->sortBy('seleksi_rate');
        } else {
            $laporan = $laporan->sortByDesc('total_pelamar');
        }

        $laporan = $laporan->values();

        $pdf = Pdf::loadView('admin.pdf.laporan', compact('laporan', 'stats', 'request'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream('Laporan-Statistik-Magang.pdf');
    }

    // --- FITUR BARU: LAPORAN GLOBAL SEMUA PESERTA (SUPER ADMIN) ---
    // public function laporanPesertaGlobal()
    // {
    //     // Ambil semua aplikasi yang diterima/selesai dari SELURUH dinas
    //     $allInterns = Application::with(['user', 'position.instansi'])
    //         ->whereIn('status', ['diterima', 'selesai'])
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     return view('admin.laporan.peserta_global', compact('allInterns'));
    // }

        public function laporanInstansi()
    {
        // Ambil semua data INSTANSI
        $instansis = Instansi::orderBy('nama_dinas', 'asc')->get();
        
        return view('admin.laporan_instansi', compact('instansis'));
    }

    public function printInstansi()
    {
        // 1. Ambil Data
        $instansis = Instansi::with(['positions.applications'])->orderBy('nama_dinas', 'asc')->get();

        // 2. Setup PDF
        // Load view yang baru kita buat tadi
        $pdf = Pdf::loadView('admin.pdf.instansi', compact('instansis'));

        // Setup Kertas: A4 Portrait agar kolom alamat muat lega
        $pdf->setPaper('a4', 'portrait');

        // 3. Output (Stream biar bisa preview dulu di browser, kalau mau langsung download ganti jadi ->download())
        return $pdf->stream('Laporan-Master-INSTANSI.pdf');
    }

    public function laporanPesertaGlobal(Request $request)
    {
        // 1. Siapkan Query Dasar
        $query = Application::with(['user', 'position.instansi']);

        // 2. Filter Status
        if ($request->has('status') && $request->status !== '') {
            if ($request->status === 'pending') {
                $query->whereIn('status', ['pending', 'menunggu']);
            } elseif ($request->status !== 'semua') {
                $query->where('status', $request->status);
            }
        } else {
            // Default show active/completed if no status filter parameter
            $query->whereIn('status', ['diterima', 'selesai']);
        }

        // 3. Filter Berdasarkan INSTANSI (Lokasi Magang)
        if ($request->has('instansi_id') && $request->instansi_id != '') {
            $query->whereHas('position.instansi', function($q) use ($request) {
                $q->where('id', $request->instansi_id);
            });
        }

        // 4. Filter Berdasarkan Instansi (Kampus/Sekolah)
        if ($request->has('instansi') && $request->instansi != '') {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('asal_instansi', $request->instansi);
            });
        }

        // 5. Filter Berdasarkan Nama Posisi (Programming, Admin, dll)
        if ($request->has('posisi') && $request->posisi != '') {
            $query->whereHas('position', function($q) use ($request) {
                $q->where('judul_posisi', 'like', '%' . $request->posisi . '%');
            });
        }

        // 6. Filter Berdasarkan Tanggal (Periode Magang)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = $request->start_date;
            $end = $request->end_date;
            
            $query->where(function($q) use ($start, $end) {
                $q->whereBetween('tanggal_mulai', [$start, $end])
                  ->orWhereBetween('tanggal_selesai', [$start, $end]);
            });
        } 
        elseif ($request->filled('start_date')) {
            $query->where('tanggal_mulai', '>=', $request->start_date);
        }
        elseif ($request->filled('end_date')) {
            $query->where('tanggal_selesai', '<=', $request->end_date);
        }

        // Urutkan berdasarkan dinas
        $allInterns = $query->get()->sortBy(function($app) {
            return $app->position->instansi->nama_dinas ?? '';
        })->values();

        // 7. Hitung Statistik Dinamis (Berdasarkan data terfilter)
        $stats = [
            'total' => $allInterns->count(),
            'aktif' => $allInterns->where('status', 'diterima')->count(),
            'selesai' => $allInterns->where('status', 'selesai')->count(),
            'pending' => $allInterns->whereIn('status', ['pending', 'menunggu'])->count(),
            'total_dinas' => $allInterns->pluck('position.instansi.id')->unique()->filter()->count(),
            'total_kampus' => $allInterns->pluck('user.asal_instansi')->unique()->filter()->count()
        ];

        // --- DATA UNTUK DROPDOWN FILTER ---
        $listDinas = Instansi::orderBy('nama_dinas', 'asc')->get();
        $listInstansi = User::where('role', 'peserta')
                            ->whereNotNull('asal_instansi')
                            ->distinct()
                            ->pluck('asal_instansi');

        return view('admin.laporan.peserta_global', compact('allInterns', 'listDinas', 'listInstansi', 'stats'));
    }

    public function printPesertaGlobal(Request $request)
    {
        // Copy-paste logika query yang sama agar hasil cetak sesuai filter
        $query = Application::with(['user', 'position.instansi']);

        // Filter Status
        if ($request->has('status') && $request->status !== '') {
            if ($request->status === 'pending') {
                $query->whereIn('status', ['pending', 'menunggu']);
            } elseif ($request->status !== 'semua') {
                $query->where('status', $request->status);
            }
        } else {
            $query->whereIn('status', ['diterima', 'selesai']);
        }

        // Filter Berdasarkan INSTANSI (Lokasi Magang)
        if ($request->has('instansi_id') && $request->instansi_id != '') {
            $query->whereHas('position.instansi', function($q) use ($request) {
                $q->where('id', $request->instansi_id);
            });
        }

        // Filter Berdasarkan Instansi (Kampus/Sekolah)
        if ($request->has('instansi') && $request->instansi != '') {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('asal_instansi', $request->instansi);
            });
        }

        // Filter Berdasarkan Nama Posisi (Programming, Admin, dll)
        if ($request->has('posisi') && $request->posisi != '') {
            $query->whereHas('position', function($q) use ($request) {
                $q->where('judul_posisi', 'like', '%' . $request->posisi . '%');
            });
        }

        // Filter Berdasarkan Tanggal (Periode Magang)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = $request->start_date;
            $end = $request->end_date;
            
            $query->where(function($q) use ($start, $end) {
                $q->whereBetween('tanggal_mulai', [$start, $end])
                  ->orWhereBetween('tanggal_selesai', [$start, $end]);
            });
        } 
        elseif ($request->filled('start_date')) {
            $query->where('tanggal_mulai', '>=', $request->start_date);
        }
        elseif ($request->filled('end_date')) {
            $query->where('tanggal_selesai', '<=', $request->end_date);
        }

        // Urutkan berdasarkan INSTANSI agar rapi di laporan PDF
        $allInterns = $query->get()->sortBy(function($app) {
            return $app->position->instansi->nama_dinas ?? '';
        })->values();

        // Hitung Statistik Dinamis (Berdasarkan data terfilter)
        $stats = [
            'total' => $allInterns->count(),
            'aktif' => $allInterns->where('status', 'diterima')->count(),
            'selesai' => $allInterns->where('status', 'selesai')->count(),
            'pending' => $allInterns->whereIn('status', ['pending', 'menunggu'])->count(),
            'total_dinas' => $allInterns->pluck('position.instansi.id')->unique()->filter()->count(),
            'total_kampus' => $allInterns->pluck('user.asal_instansi')->unique()->filter()->count()
        ];

        // Generate Dynamic Title
        $title = "LAPORAN REKAPITULASI PESERTA MAGANG";
        $subtitles = [];

        if ($request->has('instansi_id') && $request->instansi_id != '') {
            $instansiName = Instansi::find($request->instansi_id)->nama_dinas ?? '';
            if($instansiName) $subtitles[] = strtoupper($instansiName);
        }

        if ($request->has('instansi') && $request->instansi != '') {
            $subtitles[] = "ASAL " . strtoupper($request->instansi);
        }

        if ($request->has('status') && $request->status !== '') {
            if ($request->status === 'pending') {
                $subtitles[] = "STATUS PENDING";
            } elseif ($request->status !== 'semua') {
                $subtitles[] = "STATUS " . strtoupper($request->status === 'diterima' ? 'aktif' : $request->status);
            }
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $subtitles[] = "PERIODE " . \Carbon\Carbon::parse($request->start_date)->format('d/m/Y') . " - " . \Carbon\Carbon::parse($request->end_date)->format('d/m/Y');
        }

        if ($request->filled('posisi')) {
            $subtitles[] = "POSISI " . strtoupper($request->posisi);
        }

        if(!empty($subtitles)) {
            $title .= "\n(" . implode(' | ', $subtitles) . ")";
        }

        $pdf = Pdf::loadView('admin.pdf.laporan_global', compact('allInterns', 'title', 'stats', 'request'));
        $pdf->setPaper('a4', 'landscape'); 

        return $pdf->stream('Laporan-Global-Peserta.pdf');
    }

    public function laporanGrading(Request $request)
    {
        // 1. Ambil data aplikasi yang sudah memiliki nilai (status selesai/diterima)
        $query = Application::with(['user', 'position.instansi'])
                    ->where(function($q) {
                        $q->whereNotNull('nilai_rata_rata')
                          ->orWhereNotNull('nilai_teknis');
                    })
                    ->get();

        // 2. PROSES DATA: Menghitung Rata-rata Kategori & Total
        $gradedData = $query->map(function($app) {
            if ($app->nilai_rata_rata !== null) {
                $avg = (float) $app->nilai_rata_rata;
                $kerajinan = (float) ($app->nilai_kerajinan ?? 0);
                $disiplin = (float) ($app->nilai_disiplin ?? 0);
                $adaptasi = (float) ($app->nilai_adaptasi ?? 0);
                $kreatifitas = (float) ($app->nilai_kreatifitas ?? 0);
                $skill = (float) ($app->nilai_skill_pengetahuan ?? 0);
                
                $teknis = $skill; 
                $perilaku = ($adaptasi + $kreatifitas + $kerajinan) / 3;
            } else {
                $teknis = (float) ($app->nilai_teknis ?? 0);
                $disiplin = (float) ($app->nilai_disiplin ?? 0);
                $perilaku = (float) ($app->nilai_perilaku ?? 0);
                $avg = ($teknis + $disiplin + $perilaku) / 3;
                
                $kerajinan = $disiplin;
                $adaptasi = $perilaku;
                $kreatifitas = $perilaku;
                $skill = $teknis;
            }
            
            // Penentuan Predikat
            if ($avg >= 86) $predikat = 'Sangat Baik';
            elseif ($avg >= 71) $predikat = 'Baik';
            elseif ($avg >= 56) $predikat = 'Cukup';
            else $predikat = 'Kurang';

            return [
                'id' => $app->id,
                'nama' => $app->user->name ?? '-',
                'asal_instansi' => $app->user->asal_instansi ?? '-',
                'instansi_id' => $app->position->instansi->id ?? null,
                'instansi' => $app->position->instansi->nama_dinas ?? '-',
                'posisi' => $app->position->judul_posisi ?? '-',
                'nilai_rata_rata' => $app->nilai_rata_rata,
                'nilai_teknis' => $app->nilai_teknis,
                'teknis' => round($teknis, 2),
                'disiplin' => round($disiplin, 2),
                'perilaku' => round($perilaku, 2),
                'kerajinan' => round($kerajinan, 2),
                'adaptasi' => round($adaptasi, 2),
                'kreatifitas' => round($kreatifitas, 2),
                'skill' => round($skill, 2),
                'rata_rata' => round($avg, 2),
                'predikat' => $predikat
            ];
        });

        // 3. LOGIKA PEMERINGKATAN GLOBAL (Podium Top 3 - unfiltered)
        $podium = $gradedData->sortByDesc('rata_rata')->values()->take(3);

        // 4. TERAPKAN FILTER
        $filteredData = $gradedData;

        if ($request->filled('q')) {
            $q = strtolower($request->q);
            $filteredData = $filteredData->filter(function($item) use ($q) {
                return str_contains(strtolower($item['nama']), $q);
            });
        }

        if ($request->filled('instansi')) {
            $instansi = $request->instansi;
            $filteredData = $filteredData->filter(function($item) use ($instansi) {
                return $item['asal_instansi'] === $instansi;
            });
        }

        if ($request->filled('instansi_id')) {
            $instansiId = (int) $request->instansi_id;
            $filteredData = $filteredData->filter(function($item) use ($instansiId) {
                return (int)$item['instansi_id'] === $instansiId;
            });
        }

        if ($request->filled('predikat')) {
            $predikat = $request->predikat;
            $filteredData = $filteredData->filter(function($item) use ($predikat) {
                return $item['predikat'] === $predikat;
            });
        }

        // Graded List sorted by score descending
        $gradedList = $filteredData->sortByDesc('rata_rata')->values();

        // 5. STATS CARDS GRID (Berdasarkan data terfilter)
        $stats = [
            'total' => $filteredData->count(),
            'sangat_baik' => $filteredData->where('predikat', 'Sangat Baik')->count(),
            'baik' => $filteredData->where('predikat', 'Baik')->count(),
            'cukup' => $filteredData->where('predikat', 'Cukup')->count(),
            'kurang' => $filteredData->where('predikat', 'Kurang')->count(),
            'avg_nilai' => $filteredData->count() > 0 ? round($filteredData->avg('rata_rata'), 1) : 0,
        ];

        // 6. RATA-RATA GLOBAL PER KATEGORI (Berdasarkan data terfilter)
        $statsGlobal = [
            'avg_teknis' => $filteredData->count() > 0 ? round($filteredData->avg('teknis'), 1) : 0,
            'avg_disiplin' => $filteredData->count() > 0 ? round($filteredData->avg('disiplin'), 1) : 0,
            'avg_perilaku' => $filteredData->count() > 0 ? round($filteredData->avg('perilaku'), 1) : 0,
        ];

        // 7. DATA DROPDOWNS
        $listDinas = Instansi::orderBy('nama_dinas', 'asc')->get();
        $listCampus = User::where('role', 'peserta')
                            ->whereNotNull('asal_instansi')
                            ->distinct()
                            ->orderBy('asal_instansi', 'asc')
                            ->pluck('asal_instansi');

        return view('admin.laporan.grading', compact('podium', 'gradedList', 'stats', 'statsGlobal', 'listDinas', 'listCampus', 'request'));
    }

    public function printGrading(Request $request)
    {
        // 1. Ambil data aplikasi yang sudah memiliki nilai (status selesai/diterima)
        $query = Application::with(['user', 'position.instansi'])
                    ->where(function($q) {
                        $q->whereNotNull('nilai_rata_rata')
                          ->orWhereNotNull('nilai_teknis');
                    })
                    ->get();

        // 2. PROSES DATA: Menghitung Rata-rata Kategori & Total
        $gradedData = $query->map(function($app) {
            if ($app->nilai_rata_rata !== null) {
                $avg = (float) $app->nilai_rata_rata;
                $kerajinan = (float) ($app->nilai_kerajinan ?? 0);
                $disiplin = (float) ($app->nilai_disiplin ?? 0);
                $adaptasi = (float) ($app->nilai_adaptasi ?? 0);
                $kreatifitas = (float) ($app->nilai_kreatifitas ?? 0);
                $skill = (float) ($app->nilai_skill_pengetahuan ?? 0);
                
                $teknis = $skill; 
                $perilaku = ($adaptasi + $kreatifitas + $kerajinan) / 3;
            } else {
                $teknis = (float) ($app->nilai_teknis ?? 0);
                $disiplin = (float) ($app->nilai_disiplin ?? 0);
                $perilaku = (float) ($app->nilai_perilaku ?? 0);
                $avg = ($teknis + $disiplin + $perilaku) / 3;
                
                $kerajinan = $disiplin;
                $adaptasi = $perilaku;
                $kreatifitas = $perilaku;
                $skill = $teknis;
            }
            
            // Penentuan Predikat
            if ($avg >= 86) $predikat = 'Sangat Baik';
            elseif ($avg >= 71) $predikat = 'Baik';
            elseif ($avg >= 56) $predikat = 'Cukup';
            else $predikat = 'Kurang';

            return [
                'nama' => $app->user->name ?? '-',
                'asal_instansi' => $app->user->asal_instansi ?? '-',
                'instansi_id' => $app->position->instansi->id ?? null,
                'instansi' => $app->position->instansi->nama_dinas ?? '-',
                'posisi' => $app->position->judul_posisi ?? '-',
                'teknis' => round($teknis, 2),
                'disiplin' => round($disiplin, 2),
                'perilaku' => round($perilaku, 2),
                'rata_rata' => round($avg, 2),
                'predikat' => $predikat
            ];
        });

        // 3. TERAPKAN FILTER
        $filteredData = $gradedData;

        if ($request->filled('q')) {
            $q = strtolower($request->q);
            $filteredData = $filteredData->filter(function($item) use ($q) {
                return str_contains(strtolower($item['nama']), $q);
            });
        }

        if ($request->filled('instansi')) {
            $instansi = $request->instansi;
            $filteredData = $filteredData->filter(function($item) use ($instansi) {
                return $item['asal_instansi'] === $instansi;
            });
        }

        if ($request->filled('instansi_id')) {
            $instansiId = (int) $request->instansi_id;
            $filteredData = $filteredData->filter(function($item) use ($instansiId) {
                return (int)$item['instansi_id'] === $instansiId;
            });
        }

        if ($request->filled('predikat')) {
            $predikat = $request->predikat;
            $filteredData = $filteredData->filter(function($item) use ($predikat) {
                return $item['predikat'] === $predikat;
            });
        }

        // Graded List sorted by score descending
        $gradedList = $filteredData->sortByDesc('rata_rata')->values();

        // 4. STATS CARDS GRID (Berdasarkan data terfilter)
        $stats = [
            'total' => $filteredData->count(),
            'sangat_baik' => $filteredData->where('predikat', 'Sangat Baik')->count(),
            'baik' => $filteredData->where('predikat', 'Baik')->count(),
            'cukup' => $filteredData->where('predikat', 'Cukup')->count(),
            'kurang' => $filteredData->where('predikat', 'Kurang')->count(),
            'avg_nilai' => $filteredData->count() > 0 ? round($filteredData->avg('rata_rata'), 1) : 0,
        ];

        // 5. RATA-RATA GLOBAL PER KATEGORI (Berdasarkan data terfilter)
        $statsGlobal = [
            'avg_teknis' => $filteredData->count() > 0 ? round($filteredData->avg('teknis'), 1) : 0,
            'avg_disiplin' => $filteredData->count() > 0 ? round($filteredData->avg('disiplin'), 1) : 0,
            'avg_perilaku' => $filteredData->count() > 0 ? round($filteredData->avg('perilaku'), 1) : 0,
        ];

        // 6. GENERATE TITLE
        $title = "LAPORAN ANALISIS KOMPETENSI & PERFORMA PESERTA";
        $subtitles = [];

        if ($request->has('instansi_id') && $request->instansi_id != '') {
            $instansiName = Instansi::find($request->instansi_id)->nama_dinas ?? '';
            if($instansiName) $subtitles[] = strtoupper($instansiName);
        }

        if ($request->has('instansi') && $request->instansi != '') {
            $subtitles[] = "ASAL " . strtoupper($request->instansi);
        }

        if ($request->has('predikat') && $request->predikat != '') {
            $subtitles[] = "PREDIKAT " . strtoupper($request->predikat);
        }

        if ($request->has('q') && $request->q != '') {
            $subtitles[] = "PENCARIAN '" . strtoupper($request->q) . "'";
        }

        if(!empty($subtitles)) {
            $title .= "\n(" . implode(' | ', $subtitles) . ")";
        }

        $pdf = Pdf::loadView('admin.pdf.grading', compact('gradedList', 'stats', 'statsGlobal', 'title', 'request'));
        $pdf->setPaper('a4', 'portrait'); 
        return $pdf->stream('Laporan-Grading-Global.pdf');
    }

    // --- PUSAT LAPORAN HUB ---
    public function laporanHub()
    {
        return view('admin.laporan_hub');
    }

    // --- 12 BARU: LAPORAN INSTANSI PALING DISIPLIN ---
    public function laporanInstansiDisiplin(Request $request)
    {
        $query = Instansi::with(['applications' => function($q) {
            $q->whereIn('applications.status', ['diterima', 'selesai'])->with('attendances');
        }]);

        if ($request->filled('q')) {
            $query->where('nama_dinas', 'like', '%' . $request->q . '%');
        }

        $instansis = $query->get()->map(function($instansi) {
            $totalAttendances = 0;
            $totalTerlambat = 0;
            $totalAlpa = 0;
            $totalHadir = 0;
            $totalSakit = 0;
            $totalIzin = 0;

            $pelanggarList = [];

            foreach($instansi->applications as $app) {
                $pTerlambat = 0;
                $pAlpa = 0;

                foreach($app->attendances as $att) {
                    $totalAttendances++;
                    if ($att->status == 'alpa') {
                        $totalAlpa++;
                        $pAlpa++;
                    }
                    if ($att->status == 'hadir') {
                        $totalHadir++;
                        $jamMasuk = $instansi->jam_mulai_masuk ?: '08:00:00';
                        if ($att->clock_in > $jamMasuk) {
                            $totalTerlambat++;
                            $pTerlambat++;
                        }
                    }
                    if ($att->status == 'sakit') $totalSakit++;
                    if ($att->status == 'izin') $totalIzin++;
                }

                if ($pTerlambat > 0 || $pAlpa > 0) {
                    $pelanggarList[] = [
                        'nama' => $app->user->name ?? '-',
                        'kampus' => $app->user->asal_instansi ?? '-',
                        'posisi' => $app->position->judul_posisi ?? '-',
                        'terlambat' => $pTerlambat,
                        'alpa' => $pAlpa,
                    ];
                }
            }

            $instansi->total_attendances = $totalAttendances;
            $instansi->total_hadir = $totalHadir;
            $instansi->total_sakit = $totalSakit;
            $instansi->total_izin = $totalIzin;
            $instansi->total_alpa = $totalAlpa;
            $instansi->total_terlambat = $totalTerlambat;
            $instansi->total_pelanggaran = $totalTerlambat + $totalAlpa;
            $instansi->tingkat_disiplin = $totalAttendances > 0 ? 100 - (($instansi->total_pelanggaran / $totalAttendances) * 100) : 100;
            $instansi->pelanggar_list = collect($pelanggarList)->sortByDesc(function($p) {
                return $p['terlambat'] + $p['alpa'];
            })->values()->all();

            return $instansi;
        });

        if ($request->filled('disiplin_range')) {
            $range = $request->disiplin_range;
            $instansis = $instansis->filter(function($instansi) use ($range) {
                if ($range === 'sangat') {
                    return $instansi->tingkat_disiplin >= 90;
                } elseif ($range === 'cukup') {
                    return $instansi->tingkat_disiplin >= 70 && $instansi->tingkat_disiplin < 90;
                } elseif ($range === 'kurang') {
                    return $instansi->tingkat_disiplin < 70;
                }
                return true;
            });
        }

        $instansis = $instansis->sortByDesc('tingkat_disiplin')->values();

        // 3. LOGIKA PEMERINGKATAN GLOBAL (Podium Top 3 - only active instansis)
        $podium = $instansis->filter(function($i) { return $i->total_attendances > 0; })
                            ->sortByDesc('tingkat_disiplin')
                            ->values()
                            ->take(3);

        // 4. STATS CARDS GRID (Berdasarkan data terfilter)
        $stats = [
            'total_instansi' => $instansis->count(),
            'avg_disiplin' => $instansis->count() > 0 ? round($instansis->avg('tingkat_disiplin'), 1) : 100,
            'total_kehadiran' => $instansis->sum('total_attendances'),
            'total_pelanggaran' => $instansis->sum('total_pelanggaran'),
            'total_terlambat' => $instansis->sum('total_terlambat'),
            'total_alpa' => $instansis->sum('total_alpa'),
        ];

        return view('admin.laporan.instansi_disiplin', compact('podium', 'instansis', 'stats', 'request'));
    }

    public function printInstansiDisiplin(Request $request)
    {
        $query = Instansi::with(['applications' => function($q) {
            $q->whereIn('applications.status', ['diterima', 'selesai'])->with('attendances');
        }]);

        if ($request->filled('q')) {
            $query->where('nama_dinas', 'like', '%' . $request->q . '%');
        }

        $instansis = $query->get()->map(function($instansi) {
            $totalAttendances = 0;
            $totalTerlambat = 0;
            $totalAlpa = 0;
            $totalHadir = 0;
            $totalSakit = 0;
            $totalIzin = 0;

            foreach($instansi->applications as $app) {
                foreach($app->attendances as $att) {
                    $totalAttendances++;
                    if ($att->status == 'alpa') $totalAlpa++;
                    if ($att->status == 'hadir') {
                        $totalHadir++;
                        $jamMasuk = $instansi->jam_mulai_masuk ?: '08:00:00';
                        if ($att->clock_in > $jamMasuk) {
                            $totalTerlambat++;
                        }
                    }
                    if ($att->status == 'sakit') $totalSakit++;
                    if ($att->status == 'izin') $totalIzin++;
                }
            }

            $instansi->total_attendances = $totalAttendances;
            $instansi->total_hadir = $totalHadir;
            $instansi->total_sakit = $totalSakit;
            $instansi->total_izin = $totalIzin;
            $instansi->total_alpa = $totalAlpa;
            $instansi->total_terlambat = $totalTerlambat;
            $instansi->total_pelanggaran = $totalTerlambat + $totalAlpa;
            $instansi->tingkat_disiplin = $totalAttendances > 0 ? 100 - (($instansi->total_pelanggaran / $totalAttendances) * 100) : 100;

            return $instansi;
        });

        if ($request->filled('disiplin_range')) {
            $range = $request->disiplin_range;
            $instansis = $instansis->filter(function($instansi) use ($range) {
                if ($range === 'sangat') {
                    return $instansi->tingkat_disiplin >= 90;
                } elseif ($range === 'cukup') {
                    return $instansi->tingkat_disiplin >= 70 && $instansi->tingkat_disiplin < 90;
                } elseif ($range === 'kurang') {
                    return $instansi->tingkat_disiplin < 70;
                }
                return true;
            });
        }

        $instansis = $instansis->sortByDesc('tingkat_disiplin')->values();

        $stats = [
            'total_instansi' => $instansis->count(),
            'avg_disiplin' => $instansis->count() > 0 ? round($instansis->avg('tingkat_disiplin'), 1) : 100,
            'total_kehadiran' => $instansis->sum('total_attendances'),
            'total_pelanggaran' => $instansis->sum('total_pelanggaran'),
            'total_terlambat' => $instansis->sum('total_terlambat'),
            'total_alpa' => $instansis->sum('total_alpa'),
        ];

        $title = "LAPORAN REKAPITULASI KEDISIPLINAN INSTANSI";
        $subtitles = [];
        if ($request->filled('q')) {
            $subtitles[] = "PENCARIAN '" . strtoupper($request->q) . "'";
        }
        if ($request->filled('disiplin_range')) {
            $rangeLabel = [
                'sangat' => 'SANGAT DISIPLIN (>=90%)',
                'cukup' => 'CUKUP DISIPLIN (70-89%)',
                'kurang' => 'KURANG DISIPLIN (<70%)'
            ];
            $subtitles[] = "KATEGORI " . ($rangeLabel[$request->disiplin_range] ?? '');
        }

        if(!empty($subtitles)) {
            $title .= "\n(" . implode(' | ', $subtitles) . ")";
        }

        $pdf = Pdf::loadView('admin.pdf.instansi_disiplin', compact('instansis', 'stats', 'title', 'request'));
        $pdf->setPaper('a4', 'portrait'); 
        return $pdf->stream('Laporan-Instansi-Disiplin.pdf');
    }

    // --- 13 BARU: LAPORAN DURASI MAGANG SE-KOTA ---
    public function laporanDurasiMagang()
    {
        $instansis = Instansi::with(['applications' => function($q) {
            $q->whereIn('applications.status', ['diterima', 'selesai'])->whereNotNull('tanggal_mulai')->whereNotNull('tanggal_selesai');
        }])->get()->map(function($instansi) {
            $totalHari = 0;
            $count = 0;

            foreach($instansi->applications as $app) {
                $mulai = \Carbon\Carbon::parse($app->tanggal_mulai);
                $selesai = \Carbon\Carbon::parse($app->tanggal_selesai);
                $totalHari += $mulai->diffInDays($selesai);
                $count++;
            }

            $instansi->avg_durasi_hari = $count > 0 ? round($totalHari / $count) : 0;
            $instansi->avg_durasi_bulan = $count > 0 ? round(($totalHari / $count) / 30, 1) : 0;
            
            return $instansi;
        })->sortByDesc('avg_durasi_hari');

        return view('admin.laporan.durasi_magang', compact('instansis'));
    }

    public function printDurasiMagang()
    {
        $instansis = Instansi::with(['applications' => function($q) {
            $q->whereIn('applications.status', ['diterima', 'selesai'])->whereNotNull('tanggal_mulai')->whereNotNull('tanggal_selesai');
        }])->get()->map(function($instansi) {
            $totalHari = 0;
            $count = 0;

            foreach($instansi->applications as $app) {
                $mulai = \Carbon\Carbon::parse($app->tanggal_mulai);
                $selesai = \Carbon\Carbon::parse($app->tanggal_selesai);
                $totalHari += $mulai->diffInDays($selesai);
                $count++;
            }

            $instansi->avg_durasi_hari = $count > 0 ? round($totalHari / $count) : 0;
            $instansi->avg_durasi_bulan = $count > 0 ? round(($totalHari / $count) / 30, 1) : 0;
            
            return $instansi;
        })->sortByDesc('avg_durasi_hari');

        $pdf = Pdf::loadView('admin.pdf.durasi_magang', compact('instansis'));
        $pdf->setPaper('a4', 'portrait'); 
        return $pdf->stream('Laporan-Durasi-Magang.pdf');
    }

    // --- 14 BARU: LAPORAN DEMOGRAFI JURUSAN PALING DICARI ---
    public function laporanDemografiJurusan()
    {
        $jurusans = InternshipPosition::select('required_major', DB::raw('count(*) as total_lowongan'), DB::raw('sum(kuota) as total_kuota'))
            ->groupBy('required_major')
            ->orderBy('total_kuota', 'desc')
            ->get();
            
        return view('admin.laporan.demografi_jurusan', compact('jurusans'));
    }

    public function printDemografiJurusan()
    {
        $jurusans = InternshipPosition::select('required_major', DB::raw('count(*) as total_lowongan'), DB::raw('sum(kuota) as total_kuota'))
            ->groupBy('required_major')
            ->orderBy('total_kuota', 'desc')
            ->get();
            
        $pdf = Pdf::loadView('admin.pdf.demografi_jurusan', compact('jurusans'));
        $pdf->setPaper('a4', 'portrait'); 
        return $pdf->stream('Laporan-Demografi-Jurusan.pdf');
    }

    // --- 15 BARU: LAPORAN KINERJA PENYERAPAN KUOTA ---
    public function laporanPenyerapanKuota()
    {
        $penyerapan = Instansi::with(['positions' => function($q) {
            $q->withCount(['applications as diterima' => function($query) {
                $query->whereIn('applications.status', ['diterima', 'selesai']);
            }]);
        }])->get()->map(function($instansi) {
            $totalKuota = $instansi->positions->sum('kuota');
            $totalTerserap = $instansi->positions->sum('diterima');
            
            $instansi->total_kuota = $totalKuota;
            $instansi->total_terserap = $totalTerserap;
            $instansi->persentase_penyerapan = $totalKuota > 0 ? ($totalTerserap / $totalKuota) * 100 : 0;
            
            return $instansi;
        })->sortByDesc('persentase_penyerapan');

        return view('admin.laporan.penyerapan_kuota', compact('penyerapan'));
    }

    public function printPenyerapanKuota()
    {
        $penyerapan = Instansi::with(['positions' => function($q) {
            $q->withCount(['applications as diterima' => function($query) {
                $query->whereIn('applications.status', ['diterima', 'selesai']);
            }]);
        }])->get()->map(function($instansi) {
            $totalKuota = $instansi->positions->sum('kuota');
            $totalTerserap = $instansi->positions->sum('diterima');
            
            $instansi->total_kuota = $totalKuota;
            $instansi->total_terserap = $totalTerserap;
            $instansi->persentase_penyerapan = $totalKuota > 0 ? ($totalTerserap / $totalKuota) * 100 : 0;
            
            return $instansi;
        })->sortByDesc('persentase_penyerapan');

        $pdf = Pdf::loadView('admin.pdf.penyerapan_kuota', compact('penyerapan'));
        $pdf->setPaper('a4', 'portrait'); 
        return $pdf->stream('Laporan-Penyerapan-Kuota.pdf');
    }
}