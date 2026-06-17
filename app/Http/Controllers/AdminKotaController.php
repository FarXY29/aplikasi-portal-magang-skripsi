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
        return view('admin.instansi.edit', compact('instansi'));
    }

    public function update(Request $request, $id)
    {
        $instansi = Instansi::findOrFail($id);

        $request->validate([
            'nama_dinas' => 'required|string|max:255',
            'kode_unit_kerja' => 'required|string|max:50|unique:instansis,kode_unit_kerja,'.$instansi->id, 
            'alamat' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius_absen' => 'required|numeric|min:10',
        ]);

        $instansi->update($request->only(['nama_dinas','kode_unit_kerja','alamat','latitude','longitude', 'radius_absen']));

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
    
    public function report()
    {
        $laporan = Instansi::with(['positions.applications'])->get()->map(function($dinas) {
            $positions = $dinas->positions;
            $applications = $positions->flatMap->applications;
            
            $totalPelamar = $applications->count();
            $totalDiterima = $applications->whereIn('status', ['diterima', 'selesai'])->count();
            $totalPosisi = $positions->count();
            
            // --- PROSES DATA (LOGIKA TAMBAHAN) ---
            // Menghitung berapa persen pelamar yang berhasil diterima (Efektivitas Seleksi)
            $seleksiRate = $totalPelamar > 0 ? round(($totalDiterima / $totalPelamar) * 100, 1) : 0;
            
            // Menghitung rata-rata pelamar per posisi untuk melihat instansi terpopuler
            $avgPelamar = $totalPosisi > 0 ? round($totalPelamar / $totalPosisi, 1) : 0;

            return [
                'nama_dinas' => $dinas->nama_dinas,
                'lowongan_aktif' => $positions->where('status', 'buka')->count(),
                'total_pelamar' => $totalPelamar,
                'total_magang' => $totalDiterima,
                'seleksi_rate' => $seleksiRate . '%', // Hasil Proses
                'avg_peminat' => $avgPelamar . ' orang/posisi', // Hasil Proses
            ];
        });

        $laporan = $laporan->sortByDesc('total_pelamar');
        return view('admin.laporan', compact('laporan'));
    }

    public function printLaporan()
    {
        // Copy the same query from report()
        $instansis = Instansi::with(['positions.applications'])->get();
        $totalDinas = $instansis->count();
        $totalPositions = InternshipPosition::count();
        
        $allApplications = Application::whereIn('status', ['diterima', 'selesai'])->get();
        $totalDiterima = $allApplications->count();

        $laporan = $instansis->map(function($dinas) {
            $positions = $dinas->positions;
            $totalPosisi = $positions->count();
            $totalPelamar = $positions->sum(function($pos) {
                return $pos->applications->count();
            });
            
            $totalDiterima = $positions->sum(function($pos) {
                return $pos->applications->whereIn('status', ['diterima', 'selesai'])->count();
            });

            $seleksiRate = $totalPelamar > 0 ? round(($totalDiterima / $totalPelamar) * 100, 1) : 0;
            $avgPelamar = $totalPosisi > 0 ? round($totalPelamar / $totalPosisi, 1) : 0;

            return [
                'nama_dinas' => $dinas->nama_dinas,
                'lowongan_aktif' => $positions->where('status', 'buka')->count(),
                'total_pelamar' => $totalPelamar,
                'total_magang' => $totalDiterima,
                'seleksi_rate' => $seleksiRate . '%',
                'avg_peminat' => $avgPelamar . ' orang/posisi',
            ];
        });

        $laporan = $laporan->sortByDesc('total_pelamar')->values();

        $pdf = Pdf::loadView('admin.pdf.laporan', compact('laporan'));
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

        // 2. Filter Status (Jika ada parameter, filter sesuai status, jika tidak default diterima/selesai)
        if ($request->has('status')) {
            if ($request->filled('status') && $request->status !== 'semua') {
                $query->where('status', $request->status);
            }
        } else {
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

        $allInterns = $query->orderBy('created_at', 'desc')->get();

        // --- DATA UNTUK DROPDOWN FILTER ---
        // Ambil list semua INSTANSI untuk dropdown
        $listDinas = Instansi::orderBy('nama_dinas', 'asc')->get();
        
        // Ambil list semua Instansi yang unik dari tabel users
        // Hanya ambil user yang pernah melamar (agar list tidak kosong)
        $listInstansi = User::where('role', 'peserta')
                            ->whereNotNull('asal_instansi')
                            ->distinct()
                            ->pluck('asal_instansi');

        return view('admin.laporan.peserta_global', compact('allInterns', 'listDinas', 'listInstansi'));
    }

    public function printPesertaGlobal(Request $request)
    {
        // Copy-paste logika query yang sama agar hasil cetak sesuai filter
        $query = Application::with(['user', 'position.instansi']);

        if ($request->has('status')) {
            if ($request->filled('status') && $request->status !== 'semua') {
                $query->where('status', $request->status);
            }
        } else {
            $query->whereIn('status', ['diterima', 'selesai']);
        }

        if ($request->has('instansi_id') && $request->instansi_id != '') {
            $query->whereHas('position.instansi', function($q) use ($request) {
                $q->where('id', $request->instansi_id);
            });
        }

        if ($request->has('instansi') && $request->instansi != '') {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('asal_instansi', $request->instansi);
            });
        }

        if ($request->has('posisi') && $request->posisi != '') {
            $query->whereHas('position', function($q) use ($request) {
                $q->where('judul_posisi', 'like', '%' . $request->posisi . '%');
            });
        }

        // Urutkan berdasarkan INSTANSI agar rapi di laporan PDF
        $allInterns = $query->get()->sortBy(function($query){
            return $query->position->instansi->nama_dinas;
        });

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

        if ($request->has('status') && $request->status !== 'semua') {
            $subtitles[] = "STATUS " . strtoupper($request->status);
        }

        if(!empty($subtitles)) {
            $title .= "\n(" . implode(' | ', $subtitles) . ")";
        }

        $pdf = Pdf::loadView('admin.pdf.laporan_global', compact('allInterns', 'title'));
        $pdf->setPaper('a4', 'landscape'); 

        return $pdf->stream('Laporan-Global-Peserta.pdf');
    }

    public function laporanGrading()
    {
        // 1. Ambil data aplikasi yang sudah memiliki nilai (status selesai/diterima)
        $query = Application::with(['user', 'position.instansi'])
                    ->whereNotNull('nilai_teknis') // Pastikan kolom nilai tidak kosong
                    ->get();

        // 2. PROSES DATA: Menghitung Rata-rata Kategori & Total
        $gradedData = $query->map(function($app) {
            $avg = ($app->nilai_teknis + $app->nilai_disiplin + $app->nilai_perilaku) / 3;
            
            // Penentuan Predikat
            if ($avg >= 86) $predikat = 'Sangat Baik';
            elseif ($avg >= 71) $predikat = 'Baik';
            elseif ($avg >= 56) $predikat = 'Cukup';
            else $predikat = 'Kurang';

            return [
                'nama' => $app->user->name,
                'instansi' => $app->position->instansi->nama_dinas,
                'teknis' => $app->nilai_teknis,
                'disiplin' => $app->nilai_disiplin,
                'perilaku' => $app->nilai_perilaku,
                'rata_rata' => round($avg, 2),
                'predikat' => $predikat
            ];
        });

        // 3. LOGIKA PEMERINGKATAN (Ranking)
        $ranking = $gradedData->sortByDesc('rata_rata')->values()->take(10); // Ambil 10 Besar

        // 4. LOGIKA DISTRIBUSI NILAI
        $distribusi = [
            'Sangat Baik' => $gradedData->where('predikat', 'Sangat Baik')->count(),
            'Baik' => $gradedData->where('predikat', 'Baik')->count(),
            'Cukup' => $gradedData->where('predikat', 'Cukup')->count(),
            'Kurang' => $gradedData->where('predikat', 'Kurang')->count(),
        ];

        // 5. RATA-RATA GLOBAL PER KATEGORI
        $statsGlobal = [
            'avg_teknis' => round($gradedData->avg('teknis'), 1),
            'avg_disiplin' => round($gradedData->avg('disiplin'), 1),
            'avg_perilaku' => round($gradedData->avg('perilaku'), 1),
        ];

        return view('admin.laporan.grading', compact('ranking', 'distribusi', 'statsGlobal'));
    }

    public function printGrading()
    {
        // 1. Ambil data aplikasi yang sudah memiliki nilai (status selesai/diterima)
        $query = Application::with(['user', 'position.instansi'])
                    ->whereNotNull('nilai_teknis') // Pastikan kolom nilai tidak kosong
                    ->get();

        // 2. PROSES DATA: Menghitung Rata-rata Kategori & Total
        $gradedData = $query->map(function($app) {
            $avg = ($app->nilai_teknis + $app->nilai_disiplin + $app->nilai_perilaku) / 3;
            
            if ($avg >= 86) $predikat = 'Sangat Baik';
            elseif ($avg >= 71) $predikat = 'Baik';
            elseif ($avg >= 56) $predikat = 'Cukup';
            else $predikat = 'Kurang';

            return [
                'nama' => $app->user->name,
                'instansi' => $app->position->instansi->nama_dinas,
                'teknis' => $app->nilai_teknis,
                'disiplin' => $app->nilai_disiplin,
                'perilaku' => $app->nilai_perilaku,
                'rata_rata' => round($avg, 2),
                'predikat' => $predikat
            ];
        });

        $ranking = $gradedData->sortByDesc('rata_rata')->values()->take(10); // Ambil 10 Besar

        $distribusi = [
            'Sangat Baik' => $gradedData->where('predikat', 'Sangat Baik')->count(),
            'Baik' => $gradedData->where('predikat', 'Baik')->count(),
            'Cukup' => $gradedData->where('predikat', 'Cukup')->count(),
            'Kurang' => $gradedData->where('predikat', 'Kurang')->count(),
        ];

        $statsGlobal = [
            'avg_teknis' => round($gradedData->avg('teknis'), 1),
            'avg_disiplin' => round($gradedData->avg('disiplin'), 1),
            'avg_perilaku' => round($gradedData->avg('perilaku'), 1),
        ];

        $pdf = Pdf::loadView('admin.pdf.grading', compact('ranking', 'distribusi', 'statsGlobal'));
        $pdf->setPaper('a4', 'portrait'); 
        return $pdf->stream('Laporan-Grading-Global.pdf');
    }

    // --- PUSAT LAPORAN HUB ---
    public function laporanHub()
    {
        return view('admin.laporan_hub');
    }

    // --- 12 BARU: LAPORAN INSTANSI PALING DISIPLIN ---
    public function laporanInstansiDisiplin()
    {
        $instansis = Instansi::with(['applications' => function($q) {
            $q->whereIn('applications.status', ['diterima', 'selesai'])->with('attendances');
        }])->get()->map(function($instansi) {
            $totalAttendances = 0;
            $totalTerlambat = 0;
            $totalAlpa = 0;

            foreach($instansi->applications as $app) {
                foreach($app->attendances as $att) {
                    $totalAttendances++;
                    if ($att->status == 'alpa') $totalAlpa++;
                    if ($att->status == 'hadir' && $att->clock_in > '08:00:00') $totalTerlambat++;
                }
            }

            $instansi->total_attendances = $totalAttendances;
            $instansi->total_pelanggaran = $totalTerlambat + $totalAlpa;
            $instansi->tingkat_disiplin = $totalAttendances > 0 ? 100 - (($instansi->total_pelanggaran / $totalAttendances) * 100) : 0;
            
            return $instansi;
        })->sortByDesc('tingkat_disiplin');

        return view('admin.laporan.instansi_disiplin', compact('instansis'));
    }

    public function printInstansiDisiplin()
    {
        $instansis = Instansi::with(['applications' => function($q) {
            $q->whereIn('applications.status', ['diterima', 'selesai'])->with('attendances');
        }])->get()->map(function($instansi) {
            $totalAttendances = 0;
            $totalTerlambat = 0;
            $totalAlpa = 0;

            foreach($instansi->applications as $app) {
                foreach($app->attendances as $att) {
                    $totalAttendances++;
                    if ($att->status == 'alpa') $totalAlpa++;
                    if ($att->status == 'hadir' && $att->clock_in > '08:00:00') $totalTerlambat++;
                }
            }

            $instansi->total_attendances = $totalAttendances;
            $instansi->total_pelanggaran = $totalTerlambat + $totalAlpa;
            $instansi->tingkat_disiplin = $totalAttendances > 0 ? 100 - (($instansi->total_pelanggaran / $totalAttendances) * 100) : 0;
            
            return $instansi;
        })->sortByDesc('tingkat_disiplin');

        $pdf = Pdf::loadView('admin.pdf.instansi_disiplin', compact('instansis'));
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