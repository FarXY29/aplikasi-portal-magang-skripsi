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
    public function index(Request $request)
    {
        $period = $request->query('period', '30_hari');
        
        $startDate = match ($period) {
            'hari_ini' => now()->startOfDay(),
            '7_hari' => now()->subDays(7)->startOfDay(),
            '30_hari' => now()->subDays(30)->startOfDay(),
            'semester' => now()->subMonths(6)->startOfDay(),
            'tahun' => now()->subYear()->startOfDay(),
            default => now()->subDays(30)->startOfDay(),
        };
        $endDate = now()->endOfDay();

        $periodText = match ($period) {
            'hari_ini' => 'Hari Ini',
            '7_hari' => '7 Hari Terakhir',
            '30_hari' => '30 Hari Terakhir',
            'semester' => 'Semester Ini',
            'tahun' => 'Tahun Ini',
            default => '30 Hari Terakhir',
        };

        $totalInstansi = Instansi::count();
        $totalUser = User::count();
        
        $appQuery = Application::whereBetween('created_at', [$startDate, $endDate]);
        $periodAppCount = (clone $appQuery)->count();
        
        $useFilter = $periodAppCount > 0;
        
        $totalApplications = $useFilter ? $periodAppCount : Application::count();
        $activeInterns = $useFilter 
            ? (clone $appQuery)->where('status', 'diterima')->count() 
            : Application::where('status', 'diterima')->count();
        $completedInterns = $useFilter 
            ? (clone $appQuery)->where('status', 'selesai')->count() 
            : Application::where('status', 'selesai')->count();
        $pendingApplications = $useFilter 
            ? (clone $appQuery)->where('status', 'pending')->count() 
            : Application::where('status', 'pending')->count();
        $rejectedApplications = $useFilter 
            ? (clone $appQuery)->where('status', 'ditolak')->count() 
            : Application::where('status', 'ditolak')->count();

        // --- GRAFIK TREN PENDAFTARAN (LINE CHART) ---
        $trendLabels = [];
        $trendData = [];

        if ($period === 'hari_ini') {
            for ($i = 0; $i < 24; $i += 3) {
                $hStart = now()->startOfDay()->addHours($i);
                $hEnd = (clone $hStart)->addHours(3);
                $trendLabels[] = $hStart->format('H:i');
                $trendData[] = Application::whereBetween('created_at', [$hStart, $hEnd])->count();
            }
        } elseif ($period === '7_hari') {
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $trendLabels[] = $date->translatedFormat('d M');
                $trendData[] = Application::whereDate('created_at', $date->toDateString())->count();
            }
        } elseif ($period === 'semester') {
            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $trendLabels[] = $month->translatedFormat('M Y');
                $trendData[] = Application::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count();
            }
        } elseif ($period === 'tahun') {
            for ($i = 11; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $trendLabels[] = $month->translatedFormat('M Y');
                $trendData[] = Application::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count();
            }
        } else { // 30_hari
            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $trendLabels[] = $date->translatedFormat('d M');
                $trendData[] = Application::whereDate('created_at', $date->toDateString())->count();
            }
        }

        $lolosCount = $activeInterns + $completedInterns;
        $lolosPercentage = $totalApplications > 0 ? round(($lolosCount / $totalApplications) * 100, 1) : 0;
        $tolakPercentage = $totalApplications > 0 ? round(($rejectedApplications / $totalApplications) * 100, 1) : 0;

        $statusLabels = ['Pending', 'Aktif', 'Selesai', 'Ditolak'];
        $statusData = [$pendingApplications, $activeInterns, $completedInterns, $rejectedApplications];

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'totalInstansi' => number_format($totalInstansi),
                'totalUser' => number_format($totalUser),
                'totalApplications' => number_format($totalApplications),
                'activeInterns' => number_format($activeInterns),
                'completedInterns' => number_format($completedInterns),
                'pendingApplications' => number_format($pendingApplications),
                'rejectedApplications' => number_format($rejectedApplications),
                'lolosPercentage' => $lolosPercentage,
                'tolakPercentage' => $tolakPercentage,
                'statusLabels' => $statusLabels,
                'statusData' => $statusData,
                'trendLabels' => $trendLabels,
                'trendData' => $trendData,
                'periodText' => $periodText,
                'period' => $period,
                'updatedAt' => now()->translatedFormat('l, d F Y - H:i:s'),
            ]);
        }
        
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

        // --- QUICK ACTIONS (NAVIGASI CEPAT DASHBOARD) ---
        $quickActions = [
            ['label' => 'Instansi', 'icon' => 'fas fa-building', 'route' => route('admin.instansi.index'), 'color' => 'teal'],
            ['label' => 'Pengguna', 'icon' => 'fas fa-users', 'route' => route('admin.users.index'), 'color' => 'blue'],
            ['label' => 'Pusat Laporan', 'icon' => 'fas fa-chart-pie', 'route' => route('admin.laporan.hub'), 'color' => 'indigo'],
            ['label' => 'Logbook', 'icon' => 'fas fa-book-open', 'route' => route('admin.users.logbooks'), 'color' => 'purple'],
            ['label' => 'Audit Trail', 'icon' => 'fas fa-clipboard-list', 'route' => route('admin.audit_trail'), 'color' => 'amber'],
            ['label' => 'Pengaturan', 'icon' => 'fas fa-cog', 'route' => route('admin.settings.index'), 'color' => 'rose'],
        ];

        // --- GRAFIK DEMOGRAFI KAMPUS / SEKOLAH ---
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
            'rejectedApplications',
            'lolosPercentage',
            'tolakPercentage',
            'recentInstansis', 
            'recentApplications',
            'instansiStats',
            'instansiChartLabels',
            'instansiChartData',
            'maxPelamar',
            'statusLabels',
            'statusData',
            'trendLabels',
            'trendData',
            'periodText',
            'period',
            'kampusLabels',
            'kampusData',
            'quickActions'
        ));
    }
}
