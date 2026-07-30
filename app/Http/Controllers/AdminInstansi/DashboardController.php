<?php

namespace App\Http\Controllers\AdminInstansi;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\InternshipPosition;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        // Lowongan & Pembimbing counts
        $totalLowongan = InternshipPosition::where('instansi_id', $instansi->id)->count();
        $totalPembimbing = User::where('instansi_id', $instansi->id)->where('role', 'pembimbing_lapangan')->count();

        // Position IDs for this instansi
        $positionIds = InternshipPosition::where('instansi_id', $instansi->id)->pluck('id');

        // Query applications for this instansi in period
        $appQuery = Application::whereIn('internship_position_id', $positionIds)
            ->whereBetween('created_at', [$startDate, $endDate]);
        
        $periodAppCount = (clone $appQuery)->count();
        $useFilter = $periodAppCount > 0;

        $totalApplications = $useFilter 
            ? $periodAppCount 
            : Application::whereIn('internship_position_id', $positionIds)->count();

        $activeInterns = $useFilter 
            ? (clone $appQuery)->where('status', 'diterima')->count() 
            : Application::whereIn('internship_position_id', $positionIds)->where('status', 'diterima')->count();

        $completedInterns = $useFilter 
            ? (clone $appQuery)->where('status', 'selesai')->count() 
            : Application::whereIn('internship_position_id', $positionIds)->where('status', 'selesai')->count();

        $pendingApplications = $useFilter 
            ? (clone $appQuery)->where('status', 'pending')->count() 
            : Application::whereIn('internship_position_id', $positionIds)->where('status', 'pending')->count();

        $rejectedApplications = $useFilter 
            ? (clone $appQuery)->where('status', 'ditolak')->count() 
            : Application::whereIn('internship_position_id', $positionIds)->where('status', 'ditolak')->count();

        // --- GRAFIK TREN PENDAFTARAN (LINE CHART) ---
        $trendLabels = [];
        $trendData = [];

        if ($period === 'hari_ini') {
            for ($i = 0; $i < 24; $i += 3) {
                $hStart = now()->startOfDay()->addHours($i);
                $hEnd = (clone $hStart)->addHours(3);
                $trendLabels[] = $hStart->format('H:i');
                $trendData[] = Application::whereIn('internship_position_id', $positionIds)
                    ->whereBetween('created_at', [$hStart, $hEnd])->count();
            }
        } elseif ($period === '7_hari') {
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $trendLabels[] = $date->translatedFormat('d M');
                $trendData[] = Application::whereIn('internship_position_id', $positionIds)
                    ->whereDate('created_at', $date->toDateString())->count();
            }
        } elseif ($period === 'semester') {
            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $trendLabels[] = $month->translatedFormat('M Y');
                $trendData[] = Application::whereIn('internship_position_id', $positionIds)
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count();
            }
        } elseif ($period === 'tahun') {
            for ($i = 11; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $trendLabels[] = $month->translatedFormat('M Y');
                $trendData[] = Application::whereIn('internship_position_id', $positionIds)
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count();
            }
        } else { // 30_hari
            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $trendLabels[] = $date->translatedFormat('d M');
                $trendData[] = Application::whereIn('internship_position_id', $positionIds)
                    ->whereDate('created_at', $date->toDateString())->count();
            }
        }

        $lolosCount = $activeInterns + $completedInterns;
        $lolosPercentage = $totalApplications > 0 ? round(($lolosCount / $totalApplications) * 100, 1) : 0;
        $tolakPercentage = $totalApplications > 0 ? round(($rejectedApplications / $totalApplications) * 100, 1) : 0;

        $statusLabels = ['Pending', 'Aktif', 'Selesai', 'Ditolak'];
        $statusData = [$pendingApplications, $activeInterns, $completedInterns, $rejectedApplications];

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'totalLowongan' => number_format($totalLowongan),
                'totalPembimbing' => number_format($totalPembimbing),
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

        $recentPositions = InternshipPosition::where('instansi_id', $instansi->id)->latest()->take(5)->get();

        return view('admin_instansi.dashboard', compact(
            'instansi', 
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
            'periodText',
            'period',
            'topInstansi', 
            'recentPositions'
        ));
    }
}
