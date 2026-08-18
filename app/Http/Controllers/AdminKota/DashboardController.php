<?php

namespace App\Http\Controllers\AdminKota;

use App\Http\Controllers\Controller;
use App\Services\AdminDashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function __construct(private AdminDashboardService $dashboardService)
    {
    }

    /**
     * Tampilkan Dashboard Admin Kota (Superadmin)
     */
    public function index(Request $request)
    {
        $period = $request->query('period', '30_hari');
        $allowedPeriods = ['hari_ini', '7_hari', '30_hari', 'semester', 'tahun'];
        if (! in_array($period, $allowedPeriods, true)) {
            $period = '30_hari';
        }

        $page = max(1, $request->integer('page', 1));

        $periodText = match ($period) {
            'hari_ini' => 'Hari Ini',
            '7_hari' => '7 Hari Terakhir',
            '30_hari' => '30 Hari Terakhir',
            'semester' => 'Semester Ini',
            'tahun' => 'Tahun Ini',
            default => '30 Hari Terakhir',
        };

        $data = Cache::remember(
            'admin_kota_dashboard:'.$period.':page:'.$page,
            60,
            fn () => $this->dashboardService->buildData($period, $page)
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'totalInstansi' => number_format($data['totalInstansi']),
                'totalUser' => number_format($data['totalUser']),
                'totalApplications' => number_format($data['totalApplications']),
                'activeInterns' => number_format($data['activeInterns']),
                'completedInterns' => number_format($data['completedInterns']),
                'pendingApplications' => number_format($data['pendingApplications']),
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
}
