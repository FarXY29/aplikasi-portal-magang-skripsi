<?php

namespace App\Http\Controllers\AdminInstansi;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\InternshipPosition;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Dashboard Utama Admin Dinas/Instansi
     */
    public function index()
    {
        $user = Auth::user();
        $instansi = $user->instansi;

        // Ambil ID semua lowongan milik INSTANSI ini
        $positionIds = InternshipPosition::where('instansi_id', $instansi->id)->pluck('id');

        // 1. WIDGET STATUS (Khusus INSTANSI ini)
        $stats = Application::whereIn('internship_position_id', $positionIds)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $widget = [
            'pending'   => $stats['pending'] ?? 0,
            'active'    => $stats['diterima'] ?? 0,
            'completed' => $stats['selesai'] ?? 0,
            'rejected'  => $stats['ditolak'] ?? 0,
        ];

        // 2. TOP 5 SEKOLAH/KAMPUS (Khusus yang magang di INSTANSI ini)
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

        return view('admin_instansi.dashboard', compact('instansi', 'widget', 'topInstansi', 'recentPositions'));
    }
}
