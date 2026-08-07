<?php

namespace App\Http\Controllers\AdminKota;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Instansi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Tampilkan Dashboard Admin Kota (Superadmin)
     */
    public function index(Request $request)
    {
        $period = $request->query('period', '30_hari');

        $periodText = match ($period) {
            'hari_ini' => 'Hari Ini',
            '7_hari' => '7 Hari Terakhir',
            '30_hari' => '30 Hari Terakhir',
            'semester' => 'Semester Ini',
            'tahun' => 'Tahun Ini',
            default => '30 Hari Terakhir',
        };

        $data = Cache::remember('admin_kota_dashboard:'.$period, 60, fn () => $this->buildDashboardData($period));

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'totalInstansi' => number_format($data['totalInstansi']),
                'totalUser' => number_format($data['totalUser']),
                'totalApplications' => number_format($data['totalApplications']),
                'activeInterns' => number_format($data['activeInterns']),
                'completedInterns' => number_format($data['completedInterns']),
                'pendingApplications' => number_format($data['pendingApplications']),
                'rejectedApplications' => number_format($data['rejectedApplications']),
                'lolosPercentage' => $data['lolosPercentage'],
                'tolakPercentage' => $data['tolakPercentage'],
                'statusLabels' => $data['statusLabels'],
                'statusData' => $data['statusData'],
                'trendLabels' => $data['trendLabels'],
                'trendData' => $data['trendData'],
                'periodText' => $periodText,
                'period' => $period,
                'updatedAt' => now()->translatedFormat('l, d F Y - H:i:s'),
            ]);
        }

        return view('admin_kota.dashboard', array_merge($data, [
            'periodText' => $periodText,
            'period' => $period,
        ]));
    }

    /**
     * Hitung seluruh data dashboard sekali jalan (di-cache 60 detik).
     */
    private function buildDashboardData(string $period): array
    {
        $startDate = match ($period) {
            'hari_ini' => now()->startOfDay(),
            '7_hari' => now()->subDays(7)->startOfDay(),
            '30_hari' => now()->subDays(30)->startOfDay(),
            'semester' => now()->subMonths(6)->startOfDay(),
            'tahun' => now()->subYear()->startOfDay(),
            default => now()->subDays(30)->startOfDay(),
        };
        $endDate = now()->endOfDay();

        $totalInstansi = Instansi::count();
        $totalUser = User::count();

        // Satu query GROUP BY menggantikan 4 query count terpisah
        $periodCounts = Application::whereBetween('created_at', [$startDate, $endDate])
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');
        $periodAppCount = (int) $periodCounts->sum();

        $statusCounts = $periodAppCount > 0
            ? $periodCounts
            : Application::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status');

        $totalApplications = $periodAppCount > 0 ? $periodAppCount : (int) $statusCounts->sum();
        $activeInterns = (int) ($statusCounts['diterima'] ?? 0);
        $completedInterns = (int) ($statusCounts['selesai'] ?? 0);
        $pendingApplications = (int) ($statusCounts['pending'] ?? 0);
        $rejectedApplications = (int) ($statusCounts['ditolak'] ?? 0);

        [$trendLabels, $trendData] = $this->buildTrend($period);

        $lolosCount = $activeInterns + $completedInterns;
        $lolosPercentage = $totalApplications > 0 ? round(($lolosCount / $totalApplications) * 100, 1) : 0;
        $tolakPercentage = $totalApplications > 0 ? round(($rejectedApplications / $totalApplications) * 100, 1) : 0;

        $statusLabels = ['Pending', 'Aktif', 'Selesai', 'Ditolak'];
        $statusData = [$pendingApplications, $activeInterns, $completedInterns, $rejectedApplications];

        $recentInstansis = Instansi::latest()->take(5)->get();
        $recentApplications = Application::with(['user', 'position.instansi'])->latest()->take(5)->get();

        // Statistik pelamar per instansi: 2 query (paginate + chart) menggantikan 3 query berulang
        $instansiBase = Instansi::withCount('applications')->orderByDesc('applications_count');
        $instansiStats = (clone $instansiBase)->paginate(10);
        $instansiChart = (clone $instansiBase)->take(10)->get();
        $instansiChartLabels = $instansiChart->pluck('nama_dinas')->toArray();
        $instansiChartData = $instansiChart->pluck('applications_count')->toArray();

        $maxPelamar = $instansiChart->first()?->applications_count ?? 1;
        if ($maxPelamar == 0) {
            $maxPelamar = 1;
        }

        $quickActions = [
            ['label' => 'Instansi', 'icon' => 'fas fa-building', 'route' => route('admin.instansi.index'), 'color' => 'teal'],
            ['label' => 'Pengguna', 'icon' => 'fas fa-users', 'route' => route('admin.users.index'), 'color' => 'blue'],
            ['label' => 'Pusat Laporan', 'icon' => 'fas fa-chart-pie', 'route' => route('admin.laporan.hub'), 'color' => 'indigo'],
            ['label' => 'Logbook', 'icon' => 'fas fa-book-open', 'route' => route('admin.users.logbooks'), 'color' => 'purple'],
            ['label' => 'Audit Trail', 'icon' => 'fas fa-clipboard-list', 'route' => route('admin.audit_trail'), 'color' => 'amber'],
            ['label' => 'Pengaturan', 'icon' => 'fas fa-cog', 'route' => route('admin.settings.index'), 'color' => 'rose'],
        ];

        $demografiKampus = User::where('role', 'peserta')
            ->whereNotNull('asal_instansi')
            ->select('asal_instansi', DB::raw('count(*) as total'))
            ->groupBy('asal_instansi')
            ->orderByDesc('total')
            ->take(7)
            ->get();
        $kampusLabels = $demografiKampus->pluck('asal_instansi')->toArray();
        $kampusData = $demografiKampus->pluck('total')->toArray();

        return compact(
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
            'kampusLabels',
            'kampusData',
            'quickActions'
        );
    }

    /**
     * Tren pendaftar: satu query GROUP BY menggantikan count per bucket.
     *
     * @return array{0: array<int, string>, 1: array<int, int>}
     */
    private function buildTrend(string $period): array
    {
        $labels = [];
        $data = [];

        if ($period === 'hari_ini') {
            $rows = Application::where('created_at', '>=', now()->startOfDay())
                ->where('created_at', '<=', now())
                ->selectRaw('FLOOR(HOUR(created_at) / 3) as bucket, COUNT(*) as total')
                ->groupBy('bucket')
                ->pluck('total', 'bucket');

            for ($i = 0; $i < 24; $i += 3) {
                $labels[] = now()->startOfDay()->addHours($i)->format('H:i');
                $data[] = (int) ($rows[intdiv($i, 3)] ?? 0);
            }

            return [$labels, $data];
        }

        if ($period === '7_hari' || $period === '30_hari') {
            $days = $period === '7_hari' ? 7 : 30;
            $start = now()->subDays($days - 1)->startOfDay();
            $rows = Application::whereBetween('created_at', [$start, now()->endOfDay()])
                ->selectRaw('DATE(created_at) as hari, COUNT(*) as total')
                ->groupBy('hari')
                ->pluck('total', 'hari');

            for ($i = $days - 1; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $labels[] = $date->translatedFormat('d M');
                $data[] = (int) ($rows[$date->toDateString()] ?? 0);
            }

            return [$labels, $data];
        }

        $months = $period === 'tahun' ? 12 : 6;
        $start = now()->subMonths($months - 1)->startOfMonth();
        $rows = Application::where('created_at', '>=', $start)
            ->where('created_at', '<=', now())
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as bulan, COUNT(*) as total")
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        for ($i = $months - 1; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = $month->translatedFormat('M Y');
            $data[] = (int) ($rows[$month->format('Y-m')] ?? 0);
        }

        return [$labels, $data];
    }
}
