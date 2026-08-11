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

        // Data mahasiswa bimbingan lain untuk dropdown switcher/filter
        $pembimbing = Auth::user();
        $applications = Application::with(['user', 'position.instansi'])
            ->whereHas('user', function ($query) use ($pembimbing) {
                $query->where('role', 'peserta')
                      ->where('pembimbing_sekolah_id', $pembimbing->id);
            })
            ->whereIn('status', ['diterima', 'selesai'])
            ->latest()
            ->get();

        // Filter logbook
        $filterType = $request->input('filter_type', 'semua');
        $selectedDate = $request->input('date', date('Y-m-d'));
        try {
            $carbonDate = \Carbon\Carbon::parse($selectedDate);
        } catch (\Exception $e) {
            $carbonDate = \Carbon\Carbon::today();
            $selectedDate = $carbonDate->format('Y-m-d');
        }

        $query = DailyLog::where('application_id', $app->id);

        if ($filterType === 'harian') {
            $query->where('tanggal', $selectedDate);
        } elseif ($filterType === 'mingguan') {
            $startOfWeek = $carbonDate->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
            $endOfWeek = $carbonDate->copy()->endOfWeek(\Carbon\Carbon::SUNDAY);
            $query->whereBetween('tanggal', [$startOfWeek, $endOfWeek]);
        } elseif ($filterType === 'bulanan') {
            $query->whereMonth('tanggal', $carbonDate->month)->whereYear('tanggal', $carbonDate->year);
        }

        // Filter berdasarkan Status Validasi
        if ($request->filled('status_validasi') && in_array($request->status_validasi, ['pending', 'disetujui', 'revisi'])) {
            $query->where('status_validasi', $request->status_validasi);
        }

        // Filter pencarian kata kunci kegiatan
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('kegiatan', 'like', "%{$search}%");
        }

        $logs = $query->orderBy('tanggal', 'desc')->get();

        return view('pembimbing.logbook', compact('app', 'logs', 'filterType', 'selectedDate', 'applications'));
    }

    /**
     * Menampilkan absensi mahasiswa secara read-only
     */
    public function absensi(Request $request, $id)
    {
        $app = Application::with('user', 'position.instansi')->findOrFail($id);
        $this->authorize('view', $app);

        // Data mahasiswa bimbingan lain untuk dropdown switcher/filter
        $pembimbing = Auth::user();
        $applications = Application::with(['user', 'position.instansi'])
            ->whereHas('user', function ($query) use ($pembimbing) {
                $query->where('role', 'peserta')
                      ->where('pembimbing_sekolah_id', $pembimbing->id);
            })
            ->whereIn('status', ['diterima', 'selesai'])
            ->latest()
            ->get();

        // Filter absensi
        $filterType = $request->input('filter_type', 'semua');
        $selectedDate = $request->input('date', date('Y-m-d'));
        try {
            $carbonDate = \Carbon\Carbon::parse($selectedDate);
        } catch (\Exception $e) {
            $carbonDate = \Carbon\Carbon::today();
            $selectedDate = $carbonDate->format('Y-m-d');
        }

        $query = Attendance::where('application_id', $app->id);

        if ($filterType === 'harian') {
            $query->where('date', $selectedDate);
        } elseif ($filterType === 'mingguan') {
            $startOfWeek = $carbonDate->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
            $endOfWeek = $carbonDate->copy()->endOfWeek(\Carbon\Carbon::SUNDAY);
            $query->whereBetween('date', [$startOfWeek, $endOfWeek]);
        } elseif ($filterType === 'bulanan') {
            $query->whereMonth('date', $carbonDate->month)->whereYear('date', $carbonDate->year);
        }

        // Filter berdasarkan Status Kehadiran
        if ($request->filled('status') && in_array($request->status, ['hadir', 'izin', 'sakit', 'alpa'])) {
            $query->where('status', $request->status);
        }

        // Filter pencarian kata kunci catatan
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('description', 'like', "%{$search}%");
        }

        // Ambil data absensi
        $attendances = $query->orderBy('date', 'desc')->get();

        return view('pembimbing.absensi', compact('app', 'attendances', 'filterType', 'selectedDate', 'applications'));
    }
}
