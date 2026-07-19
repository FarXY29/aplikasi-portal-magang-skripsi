<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Application;
use App\Models\DailyLog;
use App\Models\Attendance;

class PembimbingSekolahController extends Controller
{
    /**
     * Tampilkan halaman dashboard utama untuk Pembimbing Sekolah
     */
    public function index(Request $request)
    {
        $pembimbing = Auth::user();
        $statusFilter = $request->input('status', 'aktif'); // Default ke aktif

        // Tentukan status yang dicari berdasarkan filter
        $statusQuery = [];
        if ($statusFilter === 'aktif') {
            $statusQuery = ['diterima'];
        } elseif ($statusFilter === 'selesai') {
            $statusQuery = ['selesai'];
        } else {
            $statusQuery = ['diterima', 'selesai']; // Semua riwayat
        }

        // Ambil aplikasi aktif dari peserta yang secara eksplisit memilih pembimbing ini
        $applications = Application::with(['user', 'position.instansi', 'pembimbing_lapangan'])
            ->whereHas('user', function ($query) use ($pembimbing) {
                $query->where('role', 'peserta')
                      ->where('pembimbing_sekolah_id', $pembimbing->id);
            })
            ->whereIn('status', $statusQuery)
            ->latest()
            ->get();

        return view('pembimbing.dashboard', compact('applications', 'statusFilter'));
    }

    /**
     * Menampilkan logbook harian mahasiswa secara read-only
     */
    public function logbook(Request $request, $id)
    {
        $app = Application::with('user', 'position.instansi')->findOrFail($id);
        $this->authorize('view', $app);

        // Filter logbook
        $filterType = $request->input('filter_type', 'semua');
        $selectedDate = $request->input('date', date('Y-m-d'));

        $query = DailyLog::where('application_id', $app->id);

        if ($filterType === 'mingguan') {
            $startOfWeek = \Carbon\Carbon::parse($selectedDate)->startOfWeek(\Carbon\Carbon::MONDAY);
            $endOfWeek = \Carbon\Carbon::parse($selectedDate)->endOfWeek(\Carbon\Carbon::SUNDAY);
            $query->whereBetween('tanggal', [$startOfWeek, $endOfWeek]);
        } elseif ($filterType === 'bulanan') {
            $month = \Carbon\Carbon::parse($selectedDate)->format('m');
            $year = \Carbon\Carbon::parse($selectedDate)->format('Y');
            $query->whereMonth('tanggal', $month)->whereYear('tanggal', $year);
        }

        $logs = $query->orderBy('tanggal', 'desc')->get();

        return view('pembimbing.logbook', compact('app', 'logs', 'filterType', 'selectedDate'));
    }

    /**
     * Menampilkan absensi mahasiswa secara read-only
     */
    public function absensi(Request $request, $id)
    {
        $app = Application::with('user', 'position.instansi')->findOrFail($id);
        $this->authorize('view', $app);

        // Filter absensi
        $filterType = $request->input('filter_type', 'semua');
        $selectedDate = $request->input('date', date('Y-m-d'));

        $query = Attendance::where('application_id', $app->id);

        if ($filterType === 'mingguan') {
            $startOfWeek = \Carbon\Carbon::parse($selectedDate)->startOfWeek(\Carbon\Carbon::MONDAY);
            $endOfWeek = \Carbon\Carbon::parse($selectedDate)->endOfWeek(\Carbon\Carbon::SUNDAY);
            $query->whereBetween('date', [$startOfWeek, $endOfWeek]);
        } elseif ($filterType === 'bulanan') {
            $month = \Carbon\Carbon::parse($selectedDate)->format('m');
            $year = \Carbon\Carbon::parse($selectedDate)->format('Y');
            $query->whereMonth('date', $month)->whereYear('date', $year);
        }

        // Ambil data absensi
        $attendances = $query->orderBy('date', 'desc')->get();

        return view('pembimbing.absensi', compact('app', 'attendances', 'filterType', 'selectedDate'));
    }
}
