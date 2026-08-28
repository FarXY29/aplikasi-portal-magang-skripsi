<?php

namespace App\Http\Controllers\AdminInstansi;

use App\Enums\AttendanceFraudStatus;
use App\Http\Controllers\Controller;
use App\Models\AttendanceAttempt;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Monitoring fraud absensi untuk admin instansi.
 *
 * Scoping KEKALAMAN server-side: setiap query dibatasi instance_id milik
 * admin yang login. Input filter peserta divalidasi agar tidak bocor
 * antar-instansi (anti IDOR).
 */
class FraudMonitoringController extends Controller
{
    public function index(Request $request)
    {
        $instansiId = Auth::user()->instansi_id;

        $attempts = $this->attemptsQuery($request, $instansiId)
            ->with(['user:id,name', 'fraudEvents'])
            ->paginate(20)->withQueryString();

        // Stat ringkas (window sama dengan filter).
        $days = $this->resolveDays($request);
        $windowStart = now()->subDays($days)->startOfDay();

        $baseFlagged = AttendanceAttempt::where('instance_id', $instansiId)
            ->where('server_received_at', '>=', $windowStart);

        $stats = [
            'total_attempts' => (clone $baseFlagged)->count(),
            'flagged_attempts' => (clone $baseFlagged)
                ->whereNotNull('fraud_status')->where('fraud_status', '!=', 'low')->count(),
            'rejected_attempts' => (clone $baseFlagged)
                ->whereJsonContains('risk_indicators', 'rejected')->count(),
            'average_risk' => (float) round((clone $baseFlagged)->avg('risk_score') ?? 0, 1),
        ];

        // Top 3 peserta paling sering tertanda.
        $topParticipants = AttendanceAttempt::where('instance_id', $instansiId)
            ->whereNotNull('fraud_status')->where('fraud_status', '!=', 'low')
            ->where('server_received_at', '>=', $windowStart)
            ->join('users', 'users.id', '=', 'attendance_attempts.user_id')
            ->groupBy('user_id', 'users.name')
            ->orderByDesc(DB::raw('COUNT(*)'))
            ->limit(3)
            ->get(['users.name', DB::raw('COUNT(*) as total'), 'attendance_attempts.user_id']);

        // Daftar peserta untuk dropdown filter (milik instansi admin).
        $participants = User::whereHas('applications.position', fn (Builder $q) => $q->where('instansi_id', $instansiId))
            ->orderBy('name')->get(['id', 'name']);

        return view('admin_instansi.monitoring_fraud', compact(
            'attempts', 'stats', 'topParticipants', 'participants'
        ));
    }

    public function show(Request $request, int $id)
    {
        $instansiId = Auth::user()->instansi_id;

        // Hard scoping: attempt harus milik instansi admin.
        $attempt = AttendanceAttempt::with(['user:id,name', 'fraudEvents', 'application'])
            ->where('instance_id', $instansiId)
            ->findOrFail($id);

        return response()->json([
            'attempt' => $attempt,
            'fraud_status_label' => AttendanceFraudStatus::tryFrom($attempt->fraud_status ?? 'low')?->label(),
        ]);
    }

    public function export(Request $request)
    {
        $instansiId = Auth::user()->instansi_id;

        $query = $this->attemptsQuery($request, $instansiId)->with('user');

        // Batas aman: bila terlalu banyak, minta filter lebih sempit.
        if ($query->count() > 50000) {
            return back()->with('error', 'Data terlalu banyak (>50.000 baris). Persempit filter rentang waktu atau peserta.');
        }

        $filename = 'laporan-fraud-' . now()->format('Y-m-d-Hi') . '.csv';

        return response()->stream(function () use ($query) {
            $out = fopen('php://output', 'w');
            // BOM UTF-8 agar Excel baca karakter Indonesia dengan benar.
            fwrite($out, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($out, [
                'Waktu Server', 'Peserta', 'Tipe', 'Risk Score', 'Status',
                'Latitude', 'Longitude', 'Jarak (m)', 'Akurasi (m)', 'Margin (m)',
                'IP', 'User-Agent', 'Sinyal Utama',
            ]);

            $query->lazy(500)->each(function ($a) use ($out) {
                $top = $a->fraudEvents->sortByDesc('score_delta')->first();
                fputcsv($out, [
                    $a->server_received_at->format('Y-m-d H:i'),
                    $a->user?->name,
                    $a->attendance_type === 'clock_in' ? 'Masuk' : 'Pulang',
                    $a->risk_score,
                    $a->fraud_status,
                    $a->latitude,
                    $a->longitude,
                    $a->distance_to_instance,
                    $a->accuracy,
                    $a->location_margin,
                    $a->ip_address,
                    $a->user_agent,
                    $top ? $top->code . ' (+' . $top->score_delta . ')' : '-',
                ]);
            });

            fclose($out);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-store',
        ]);
    }

    /**
     * Query builder dibagi dengan export() — filter identik.
     */
    private function attemptsQuery(Request $request, int $instansiId): Builder
    {
        $query = AttendanceAttempt::query()
            ->where('instance_id', $instansiId)
            ->latest('server_received_at');

        // Filter: status risiko spesifik / semua flagged.
        $status = $request->input('status');
        if (in_array($status, ['medium', 'high', 'very_high', 'critical'], true)) {
            $query->where('fraud_status', $status);
        } elseif ($status === 'flagged') {
            $query->whereNotNull('fraud_status')->where('fraud_status', '!=', 'low');
        }

        // Filter: rentang hari (default 7, max 90).
        $days = $this->resolveDays($request);
        $query->where('server_received_at', '>=', now()->subDays($days)->startOfDay());

        // Filter: peserta (validasi anti IDOR — harus milik instansi admin).
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'))
                ->whereHas('application.position', fn (Builder $q) => $q->where('instansi_id', $instansiId));
        }

        return $query;
    }

    private function resolveDays(Request $request): int
    {
        $days = (int) $request->input('days', 7);

        return min(max($days, 1), 90);
    }
}
