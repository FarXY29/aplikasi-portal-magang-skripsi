<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\InternshipPosition;
use App\Models\User;
use App\Models\DailyLog;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Support\Facades\DB;

class KepalaDinasController extends Controller
{
    /**
     * Dashboard Utama Kepala Dinas
     * (Versi Baru dengan Grafik & Statistik)
     */
    public function index()
    {
        $user = Auth::user();
        
        // Validasi: Pastikan user memiliki INSTANSI
        if (!$user->instansi_id) {
            return view('dashboard'); 
        }

        $instansiId = $user->instansi_id;

        // --- 1. KARTU STATISTIK UTAMA ---
        $totalPeserta = Application::whereHas('position', fn($q) => $q->where('instansi_id', $instansiId))
                        ->whereIn('status', ['diterima', 'selesai'])->count();

        $totalLowongan = InternshipPosition::where('instansi_id', $instansiId)->where('status', 'buka')->count();
        
        $rataRataNilai = Application::whereHas('position', fn($q) => $q->where('instansi_id', $instansiId))
                        ->whereNotNull('nilai_angka')
                        ->avg('nilai_angka'); // Rata-rata kompetensi peserta

        // --- 2. DATA UNTUK GRAFIK PIE: ASAL INSTANSI (TOP 5) ---
        $topInstansi = Application::whereHas('position', fn($q) => $q->where('instansi_id', $instansiId))
                        ->join('users', 'applications.user_id', '=', 'users.id')
                        ->select('users.asal_instansi', DB::raw('count(*) as total'))
                        ->whereIn('applications.status', ['diterima', 'selesai'])
                        ->groupBy('users.asal_instansi')
                        ->orderByDesc('total')
                        ->take(5)
                        ->get();

        // Data Lainnya digabung agar grafik rapi
        $totalTop5 = $topInstansi->sum('total');
        $totalAll  = $totalPeserta;
        $lainnya   = $totalAll - $totalTop5;

        // Format untuk ChartJS
        $chartInstansiLabels = $topInstansi->pluck('asal_instansi')->toArray();
        $chartInstansiData   = $topInstansi->pluck('total')->toArray();
        if($lainnya > 0) {
            $chartInstansiLabels[] = 'Instansi Lainnya';
            $chartInstansiData[]   = $lainnya;
        }

        // --- 3. DATA UNTUK GRAFIK BAR: TREN PELAMAR BULANAN (Tahun Ini) ---
        $monthlyStats = Application::whereHas('position', fn($q) => $q->where('instansi_id', $instansiId))
                        ->select(
                            DB::raw('MONTH(created_at) as bulan'), 
                            DB::raw('count(*) as total_pelamar'),
                            DB::raw('SUM(CASE WHEN status = "diterima" OR status = "selesai" THEN 1 ELSE 0 END) as diterima')
                        )
                        ->whereYear('created_at', date('Y'))
                        ->groupBy('bulan')
                        ->orderBy('bulan')
                        ->get();
        
        // Siapkan array kosong untuk 12 bulan
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $dataPelamar = array_fill(0, 12, 0);
        $dataDiterima = array_fill(0, 12, 0);

        foreach ($monthlyStats as $stat) {
            $index = $stat->bulan - 1; // Array index mulai dari 0
            $dataPelamar[$index] = $stat->total_pelamar;
            $dataDiterima[$index] = (int) $stat->diterima;
        }

        return view('kepala_dinas.dashboard', compact(
            'totalPeserta', 'totalLowongan', 'rataRataNilai',
            'chartInstansiLabels', 'chartInstansiData',
            'months', 'dataPelamar', 'dataDiterima'
        ));
    }

    public function printPeserta()
    {
        $instansiId = Auth::user()->instansi_id;
        
        $interns = Application::whereHas('position', function($q) use ($instansiId) {
            $q->where('instansi_id', $instansiId);
        })
        ->whereIn('status', ['diterima', 'selesai'])
        ->with(['user', 'position', 'pembimbing_lapangan', 'position.instansi'])
        ->orderBy('created_at', 'desc')
        ->get();

        $pdf = Pdf::loadView('kepala_dinas.pdf.peserta', compact('interns'));
        $pdf->setPaper('a4', 'landscape'); // Landscape agar kolom nilai muat

        return $pdf->stream('Laporan-Eksekutif-Peserta.pdf');
    }

    /**
     * CETAK PDF: Laporan Statistik & Demografi
     */
    public function printStatistik()
    {
        $instansiId = Auth::user()->instansi_id;

        // 1. Data Statistik
        $stats = InternshipPosition::where('instansi_id', $instansiId)
            ->withCount(['applications as total_pelamar', 'applications as diterima' => function($q) {
                $q->where('status', 'diterima')->orWhere('status', 'selesai');
            }])
            ->orderBy('total_pelamar', 'desc')
            ->get();

        // 2. Data Demografi
        $demografi = Application::whereHas('position', function($q) use ($instansiId) {
                $q->where('instansi_id', $instansiId);
            })
            ->join('users', 'applications.user_id', '=', 'users.id')
            ->select('users.asal_instansi', DB::raw('count(*) as total'))
            ->whereIn('applications.status', ['diterima', 'selesai'])
            ->groupBy('users.asal_instansi')
            ->orderBy('total', 'desc')
            ->get();

        $pdf = Pdf::loadView('kepala_dinas.pdf.statistik', compact('stats', 'demografi'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan-Statistik-Demografi.pdf');
    }

    /**
     * REPORT 1: LAPORAN KETERSEDIAAN LOWONGAN
     */
    public function laporanLowongan()
    {
        $instansiId = Auth::user()->instansi_id;
        
        $lowongans = InternshipPosition::where('instansi_id', $instansiId)
            ->withCount('applications')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('kepala_dinas.laporan_lowongan', compact('lowongans'));
    }

    /**
     * REPORT 2: LAPORAN DATA PESERTA (LENGKAP)
     */
    public function laporanPeserta()
    {
        $instansiId = Auth::user()->instansi_id;
        
        $interns = Application::whereHas('position', function($q) use ($instansiId) {
            $q->where('instansi_id', $instansiId);
        })
        ->whereIn('status', ['diterima', 'selesai'])
        ->with(['user', 'position', 'pembimbing_lapangan', 'position.instansi'])
        ->orderBy('created_at', 'desc')
        ->get();

        return view('kepala_dinas.laporan_peserta', compact('interns'));
    }

    /**
     * REPORT 3: LAPORAN DEMOGRAFI (ASAL INSTANSI)
     */
    public function laporanDemografi()
    {
        $instansiId = Auth::user()->instansi_id;
        
        $demografi = Application::whereHas('position', function($q) use ($instansiId) {
                $q->where('instansi_id', $instansiId);
            })
            ->join('users', 'applications.user_id', '=', 'users.id')
            ->select('users.asal_instansi', DB::raw('count(*) as total'))
            ->whereIn('applications.status', ['diterima', 'selesai'])
            ->groupBy('users.asal_instansi')
            ->orderBy('total', 'desc')
            ->get();

        return view('kepala_dinas.laporan_demografi', compact('demografi'));
    }

    /**
     * REPORT 4: LAPORAN PENILAIAN AKHIR (TRANSKRIP)
     */
    public function laporanNilai()
    {
        $instansiId = Auth::user()->instansi_id;
        
        $scores = Application::whereHas('position', function($q) use ($instansiId) {
            $q->where('instansi_id', $instansiId);
        })
        ->whereNotNull('nilai_angka')
        ->with(['user', 'position', 'pembimbing_lapangan'])
        ->orderBy('nilai_angka', 'desc')
        ->get();

        return view('kepala_dinas.laporan_nilai', compact('scores'));
    }

    /**
     * REPORT 5: LAPORAN KEAKTIFAN (ABSENSI LOGBOOK)
     */
    public function laporanAbsensi()
    {
        $instansiId = Auth::user()->instansi_id;

        $activity = Application::whereHas('position', function($q) use ($instansiId) {
            $q->where('instansi_id', $instansiId);
        })
        ->whereIn('status', ['diterima', 'selesai'])
        ->withCount(['logs as total_log', 'logs as valid_log' => function($q) {
            $q->where('status_validasi', 'disetujui');
        }])
        ->with(['user', 'position'])
        ->orderBy('valid_log', 'desc')
        ->get();

        return view('kepala_dinas.laporan_absensi', compact('activity'));
    }

    /**
     * REPORT 6: LAPORAN STATISTIK PEMINAT
     */
    public function laporanStatistik()
    {
        $instansiId = Auth::user()->instansi_id;

        // 1. DATA STATISTIK PEMINAT (Per Lowongan)
        $stats = InternshipPosition::where('instansi_id', $instansiId)
            ->withCount(['applications as total_pelamar', 'applications as diterima' => function($q) {
                $q->where('status', 'diterima')->orWhere('status', 'selesai');
            }])
            ->orderBy('total_pelamar', 'desc')
            ->get();

        // 2. DATA DEMOGRAFI (Per Asal Instansi)
        // Menghitung jumlah peserta diterima/lulus berdasarkan asal kampusnya
        $demografi = Application::whereHas('position', function($q) use ($instansiId) {
                $q->where('instansi_id', $instansiId);
            })
            ->join('users', 'applications.user_id', '=', 'users.id')
            ->select('users.asal_instansi', DB::raw('count(*) as total'))
            ->whereIn('applications.status', ['diterima', 'selesai'])
            ->groupBy('users.asal_instansi')
            ->orderBy('total', 'desc')
            ->get();

        return view('kepala_dinas.laporan_statistik', compact('stats', 'demografi'));
    }
}