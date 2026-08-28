<?php

namespace App\Http\Controllers\AdminInstansi;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Attendance;
use App\Models\AttendanceAttempt;
use App\Models\InternshipPosition;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Dashboard Utama Admin Dinas/Instansi
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $instansi = $user->instansi;

        $period = $request->query('period', '30_hari');

        $periodText = match ($period) {
            'hari_ini' => 'Hari Ini',
            '7_hari' => '7 Hari Terakhir',
            '30_hari' => '30 Hari Terakhir',
            'semester' => 'Semester Ini',
            'tahun' => 'Tahun Ini',
            default => '30 Hari Terakhir',
        };

        $data = Cache::remember(
            'admin_instansi_dashboard:'.$instansi->id.':'.$period,
            60,
            fn () => $this->buildDashboardData($instansi->id, $period)
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'totalLowongan' => number_format($data['totalLowongan']),
                'totalPembimbing' => number_format($data['totalPembimbing']),
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
                'flaggedAttendances' => number_format($data['flaggedAttendances']),
                'flaggedAttempts' => number_format($data['flaggedAttempts']),
            ]);
        }

        return view('admin_instansi.dashboard', array_merge($data, [
            'instansi' => $instansi,
            'periodText' => $periodText,
            'period' => $period,
        ]));
    }

    /**
     * Hitung seluruh data dashboard instansi sekali jalan (di-cache 60 detik).
     */
    private function buildDashboardData(int $instansiId, string $period): array
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

        $totalLowongan = InternshipPosition::where('instansi_id', $instansiId)->count();
        $totalPembimbing = User::where('instansi_id', $instansiId)
            ->portalRole('pembimbing_lapangan')
            ->count();

        $positionIds = InternshipPosition::where('instansi_id', $instansiId)->pluck('id');

        // Satu query GROUP BY menggantikan 5 query count terpisah
        $periodCounts = Application::whereIn('internship_position_id', $positionIds)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');
        $periodAppCount = (int) $periodCounts->sum();

        $statusCounts = $periodAppCount > 0
            ? $periodCounts
            : Application::whereIn('internship_position_id', $positionIds)
                ->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status');

        $totalApplications = $periodAppCount > 0 ? $periodAppCount : (int) $statusCounts->sum();
        $activeInterns = (int) ($statusCounts['diterima'] ?? 0);
        $completedInterns = (int) ($statusCounts['selesai'] ?? 0);
        $pendingApplications = (int) ($statusCounts['pending'] ?? 0);
        $rejectedApplications = (int) ($statusCounts['ditolak'] ?? 0);

        [$trendLabels, $trendData] = $this->buildTrend($positionIds, $period);

        $lolosCount = $activeInterns + $completedInterns;
        $lolosPercentage = $totalApplications > 0 ? round(($lolosCount / $totalApplications) * 100, 1) : 0;
        $tolakPercentage = $totalApplications > 0 ? round(($rejectedApplications / $totalApplications) * 100, 1) : 0;

        $statusLabels = ['Pending', 'Aktif', 'Selesai', 'Ditolak'];
        $statusData = [$pendingApplications, $activeInterns, $completedInterns, $rejectedApplications];

        $topInstansi = DB::table('applications')
            ->join('users', 'applications.user_id', '=', 'users.id')
            ->whereIn('applications.internship_position_id', $positionIds)
            ->whereIn('applications.status', ['diterima', 'selesai'])
            ->whereNotNull('users.asal_instansi')
            ->select('users.asal_instansi', DB::raw('count(*) as total_peserta'))
            ->groupBy('users.asal_instansi')
            ->orderByDesc('total_peserta')
            ->limit(5)
            ->get();

        $recentPositions = InternshipPosition::where('instansi_id', $instansiId)->latest()->take(5)->get();

        // Ringkasan fraud: attendance ditandai (medium ke atas) + attempt mencurigakan.
        $flaggedAttendances = Attendance::whereHas('application', function ($q) use ($positionIds) {
                $q->whereIn('internship_position_id', $positionIds);
            })
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->whereNotNull('fraud_status')
            ->where('fraud_status', '!=', 'low')
            ->count();

        $flaggedAttempts = AttendanceAttempt::where('instance_id', $instansiId)
            ->whereBetween('server_received_at', [$startDate, $endDate])
            ->whereNotNull('fraud_status')
            ->where('fraud_status', '!=', 'low')
            ->count();

        return compact(
            'totalLowongan',
            'totalPembimbing',
            'totalApplications',
            'activeInterns',
            'completedInterns',
            'pendingApplications',
            'rejectedApplications',
            'lolosPercentage',
            'tolakPercentage',
            'statusLabels',
            'statusData',
            'trendLabels',
            'trendData',
            'topInstansi',
            'recentPositions',
            'flaggedAttendances',
            'flaggedAttempts'
        );
    }

    /**
     * Tren pendaftar: satu query GROUP BY menggantikan count per bucket.
     *
     * @return array{0: array<int, string>, 1: array<int, int>}
     */
    private function buildTrend($positionIds, string $period): array
    {
        $labels = [];
        $data = [];

        if ($period === 'hari_ini') {
            $rows = Application::whereIn('internship_position_id', $positionIds)
                ->where('created_at', '>=', now()->startOfDay())
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
            $rows = Application::whereIn('internship_position_id', $positionIds)
                ->whereBetween('created_at', [$start, now()->endOfDay()])
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
        $rows = Application::whereIn('internship_position_id', $positionIds)
            ->where('created_at', '>=', $start)
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
