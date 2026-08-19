<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Instansi;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminDashboardService
{
    /**
     * Hitung seluruh data dashboard superadmin (di-cache 60 detik).
     *
     * @return array{
     *     totalInstansi: int,
     *     totalUser: int,
     *     totalApplications: int,
     *     activeInterns: int,
     *     completedInterns: int,
     *     pendingApplications: int,
     *     lolosPercentage: float,
     *     tolakPercentage: float,
     *     recentInstansis: \Illuminate\Database\Eloquent\Collection,
     *     instansiStats: LengthAwarePaginator,
     *     maxPelamar: int,
     *     statusLabels: string[],
     *     statusData: int[],
     *     trendLabels: string[],
     *     trendData: int[]
     * }
     */
    public function buildData(string $period, int $page = 1): array
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

        $statusCounts = $periodCounts;
        $totalApplications = $periodAppCount;
        $activeInterns = (int) ($statusCounts['diterima'] ?? 0);
        $completedInterns = (int) ($statusCounts['selesai'] ?? 0);
        $pendingApplications = (int) ($statusCounts['pending'] ?? 0)
            + (int) ($statusCounts['menunggu'] ?? 0);
        $rejectedApplications = (int) ($statusCounts['ditolak'] ?? 0);

        [$trendLabels, $trendData] = $this->buildTrend($period);

        $lolosCount = $activeInterns + $completedInterns;
        $lolosPercentage = $totalApplications > 0 ? round(($lolosCount / $totalApplications) * 100, 1) : 0;
        $tolakPercentage = $totalApplications > 0 ? round(($rejectedApplications / $totalApplications) * 100, 1) : 0;

        $statusLabels = ['Pending', 'Aktif', 'Selesai', 'Ditolak'];
        $statusData = [$pendingApplications, $activeInterns, $completedInterns, $rejectedApplications];

        $recentInstansis = Instansi::latest()->take(5)->get();

        // Statistik pelamar per instansi (terurut dari peminat terbanyak).
        $instansiBase = Instansi::withCount('applications')->orderByDesc('applications_count');
        $instansiStats = (clone $instansiBase)->paginate(10, ['*'], 'page', $page);

        // Max peminat global untuk normalisasi progress bar (list terurut desc).
        $maxPelamar = (clone $instansiBase)->take(1)->get()->first()?->applications_count ?? 1;
        if ($maxPelamar == 0) {
            $maxPelamar = 1;
        }

        return compact(
            'totalInstansi',
            'totalUser',
            'totalApplications',
            'activeInterns',
            'completedInterns',
            'pendingApplications',
            'lolosPercentage',
            'tolakPercentage',
            'recentInstansis',
            'instansiStats',
            'maxPelamar',
            'statusLabels',
            'statusData',
            'trendLabels',
            'trendData'
        );
    }

    /**
     * Tren pendaftar: satu query GROUP BY menggantikan count per bucket.
     *
     * @return array{0: array<int, string>, 1: array<int, int>}
     */
    public function buildTrend(string $period): array
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