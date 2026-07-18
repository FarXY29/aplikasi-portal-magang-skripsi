<?php

namespace App\Http\Controllers\AdminKota;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Instansi;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Tampilkan Dashboard Admin Kota (Superadmin)
     */
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

        // --- STATISTIK PELAMAR PER INSTANSI (UNTUK TABEL & CHART) ---
        $instansiStats = Instansi::withCount('applications')->orderByDesc('applications_count')->paginate(10);
        $instansiChart = Instansi::withCount('applications')->orderByDesc('applications_count')->take(10)->get();
        $instansiChartLabels = $instansiChart->pluck('nama_dinas')->toArray();
        $instansiChartData = $instansiChart->pluck('applications_count')->toArray();
        
        // Cari pelamar terbanyak untuk referensi progress bar di view
        $maxInstansi = Instansi::withCount('applications')->orderByDesc('applications_count')->first();
        $maxPelamar = $maxInstansi ? $maxInstansi->applications_count : 1;
        if ($maxPelamar == 0) $maxPelamar = 1;
        
        // --- GRAFIK STATUS APLIKASI ---
        $statusLabels = ['Pending', 'Aktif', 'Selesai', 'Ditolak'];
        $statusData = [$pendingApplications, $activeInterns, $completedInterns, $rejectedApplications];

        // --- GRAFIK DEMOGRAFI KAMPUS / SEKOLAH ---
        // Menggunakan field asal_instansi untuk group by
        $demografiKampus = User::where('role', 'peserta')
                                ->whereNotNull('asal_instansi')
                                ->select('asal_instansi', \DB::raw('count(*) as total'))
                                ->groupBy('asal_instansi')
                                ->orderByDesc('total')
                                ->take(7)
                                ->get();
        $kampusLabels = $demografiKampus->pluck('asal_instansi')->toArray();
        $kampusData = $demografiKampus->pluck('total')->toArray();
        
        return view('admin_kota.dashboard', compact(
            'totalInstansi', 
            'totalUser', 
            'totalApplications',
            'activeInterns',
            'completedInterns',
            'pendingApplications',
            'recentInstansis', 
            'recentApplications',
            'instansiStats',
            'instansiChartLabels',
            'instansiChartData',
            'maxPelamar',
            'statusLabels',
            'statusData',
            'kampusLabels',
            'kampusData'
        ));
    }
}
