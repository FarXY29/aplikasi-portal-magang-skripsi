<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Attendance;
use App\Models\DailyLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembimbingSekolahController extends Controller
{
    /**
     * Status aplikasi yang termasuk dalam daftar bimbingan (aktif & riwayat selesai).
     */
    private const MENTORED_STATUSES = ['diterima', 'selesai'];

    /**
     * Status absensi yang boleh dipakai sebagai filter.
     */
    private const ATTENDANCE_STATUSES = ['hadir', 'izin', 'sakit', 'alpa'];

    /**
     * Status validasi logbook yang boleh dipakai sebagai filter.
     */
    private const LOG_VALIDATION_STATUSES = ['pending', 'disetujui', 'revisi', 'ditolak'];

    /**
     * Tampilkan halaman dashboard utama untuk Pembimbing Sekolah.
     */
    public function index(Request $request)
    {
        $pembimbing = Auth::user();
        $statusFilter = $request->input('status', 'aktif');

        $statusQuery = match ($statusFilter) {
            'aktif' => ['diterima'],
            'selesai' => ['selesai'],
            default => self::MENTORED_STATUSES,
        };

        $applications = $this->mentoredApplicationsQuery($pembimbing)
            ->whereIn('status', $statusQuery)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pembimbing.dashboard', compact('applications', 'statusFilter'));
    }

    /**
     * Menampilkan logbook harian mahasiswa secara read-only.
     */
    public function logbook(Request $request, $id)
    {
        $app = Application::with(['user', 'position.instansi'])->findOrFail($id);
        $this->authorize('view', $app);

        $pembimbing = Auth::user();
        $applications = $this->mentoredApplicationsQuery($pembimbing)
            ->latest()
            ->get();

        $filterType = $request->input('filter_type', 'semua');
        $carbonDate = $this->parseSelectedDate($request->input('date'));
        $selectedDate = $carbonDate->toDateString();

        $query = DailyLog::with('application.user')
            ->where('application_id', $app->id);

        $this->applyRangeFilter($query, 'tanggal', $filterType, $carbonDate);

        if ($request->filled('status_validasi') && in_array($request->status_validasi, self::LOG_VALIDATION_STATUSES, true)) {
            $query->where('status_validasi', $request->status_validasi);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('kegiatan', 'like', "%{$search}%");
        }

        $logs = $query->orderBy('tanggal', 'desc')->paginate(15)->withQueryString();

        return view('pembimbing.logbook', compact('app', 'logs', 'filterType', 'selectedDate', 'applications'));
    }

    /**
     * Menampilkan absensi mahasiswa secara read-only.
     */
    public function absensi(Request $request, $id)
    {
        $app = Application::with(['user', 'position.instansi'])->findOrFail($id);
        $this->authorize('view', $app);

        $pembimbing = Auth::user();
        $applications = $this->mentoredApplicationsQuery($pembimbing)
            ->latest()
            ->get();

        $filterType = $request->input('filter_type', 'semua');
        $carbonDate = $this->parseSelectedDate($request->input('date'));
        $selectedDate = $carbonDate->toDateString();

        $query = Attendance::with('application.user')
            ->where('application_id', $app->id);

        $this->applyRangeFilter($query, 'date', $filterType, $carbonDate);

        if ($request->filled('status') && in_array($request->status, self::ATTENDANCE_STATUSES, true)) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('description', 'like', "%{$search}%");
        }

        $attendances = $query->orderBy('date', 'desc')->paginate(15)->withQueryString();

        return view('pembimbing.absensi', compact('app', 'attendances', 'filterType', 'selectedDate', 'applications'));
    }

    /**
     * Query dasar aplikasi milik peserta binaan pembimbing sekolah ini.
     */
    private function mentoredApplicationsQuery(User $pembimbing)
    {
        return Application::with(['user', 'position.instansi'])
            ->whereHas('user', function ($query) use ($pembimbing) {
                $query->where('role', 'peserta')
                    ->where('pembimbing_sekolah_id', $pembimbing->id);
            })
            ->whereIn('status', self::MENTORED_STATUSES);
    }

    /**
     * Terapkan filter rentang waktu ke query (harian/mingguan/bulanan).
     */
    private function applyRangeFilter($query, string $column, string $filterType, Carbon $carbonDate): void
    {
        if ($filterType === 'harian') {
            $query->whereDate($column, $carbonDate->toDateString());
        } elseif ($filterType === 'mingguan') {
            $query->whereBetween($column, [
                $carbonDate->copy()->startOfWeek(Carbon::MONDAY),
                $carbonDate->copy()->endOfWeek(Carbon::SUNDAY),
            ]);
        } elseif ($filterType === 'bulanan') {
            $query->whereMonth($column, $carbonDate->month)
                ->whereYear($column, $carbonDate->year);
        }
    }

    /**
     * Parse input tanggal; fallback ke hari ini bila tidak valid.
     */
    private function parseSelectedDate(?string $value): Carbon
    {
        try {
            return Carbon::parse($value);
        } catch (\Throwable) {
            return Carbon::today();
        }
    }
}
