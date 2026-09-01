<?php

namespace App\Services\Attendance;

use App\Enums\AttendanceFraudStatus;
use App\Models\Attendance;
use App\Models\AttendanceAttempt;
use App\Models\AttendanceFraudEvent;
use App\Services\AuditLogService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Persistensi bukti attempt + fraud events + integrasi audit log (§7, §8, §31).
 *
 * PRINSIP: penyimpanan evidence TIDAK BOLEH merusak flow absensi utama —
 * semua kegagalan ditelan dengan logging (fail-safe).
 */
class AttendanceAttemptService
{
    public function __construct(
        private readonly AuditLogService $auditLog,
    ) {
    }

    /**
     * Simpan attempt + fraud events.
     */
    public function record(
        AttendanceFraudContext $context,
        AttendanceFraudResult $result,
        ?Attendance $attendance,
        string $outcome, // accepted | rejected | blocked
    ): ?AttendanceAttempt {
        try {
            return DB::transaction(function () use ($context, $result, $attendance, $outcome) {
                $attempt = AttendanceAttempt::create([
                    'user_id' => $context->user->id,
                    'application_id' => $context->application->id,
                    'instance_id' => $context->instansi?->id,
                    'attendance_id' => $attendance?->id,
                    'attendance_type' => $context->attendanceType,
                    'attempt_uuid' => (string) \Illuminate\Support\Str::uuid(),
                    'idempotency_key' => null, // di-set terpisah bila ada
                    'latitude' => $context->latitude,
                    'longitude' => $context->longitude,
                    'accuracy' => $context->accuracy,
                    'altitude' => $context->altitude,
                    'speed' => $context->speed,
                    'heading' => $context->heading,
                    'client_timestamp' => $context->clientTimestampMs,
                    'server_received_at' => $context->serverReceivedAt,
                    'distance_to_instance' => $context->distanceToInstance,
                    'location_margin' => $this->resolveMargin($context),
                    'ip_address' => $context->ipAddress,
                    'user_agent' => $context->userAgent,
                    'session_hash' => $context->sessionHash,
                    'risk_score' => $result->score,
                    'fraud_status' => $result->status->value,
                    'risk_indicators' => array_merge(
                        $result->indicatorCodes(),
                        ['outcome' => $outcome],
                    ),
                ]);

                foreach ($result->signals as $signal) {
                    AttendanceFraudEvent::create([
                        'attendance_attempt_id' => $attempt->id,
                        'code' => $signal->code,
                        'severity' => $signal->severity,
                        'score_delta' => $signal->scoreDelta,
                        'metadata' => $signal->metadata,
                    ]);
                }

                $this->writeAuditLogs($context, $result, $attempt, $outcome);

                return $attempt;
            });
        } catch (\Throwable $e) {
            Log::error('Gagal menyimpan attendance attempt evidence', [
                'user_id' => $context->user->id,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Jumlah attempt user dalam window request-frequency (MENIT).
     * Dipanggil SEBELUM attempt baru disimpan.
     */
    public function recentAttemptCount(int $userId): int
    {
        $windowMinutes = (int) config('attendance.request_frequency_window', 15);

        return AttendanceAttempt::where('user_id', $userId)
            ->where('created_at', '>=', Carbon::now()->subMinutes($windowMinutes))
            ->count();
    }

    /**
     * IP + UA attempt terakhir user sebelum sekarang (untuk weak signal).
     *
     * @return array{ip: ?string, user_agent: ?string}
     */
    public function lastAttemptNetwork(int $userId): array
    {
        $last = AttendanceAttempt::where('user_id', $userId)
            ->whereNotNull('ip_address')
            ->latest('id')
            ->first();

        return [
            'ip' => $last?->ip_address,
            'user_agent' => $last?->user_agent,
        ];
    }

    /**
     * Set idempotency key pada attempt (dipanggil controller).
     */
    public function attachIdempotencyKey(AttendanceAttempt $attempt, ?string $key): void
    {
        if ($key === null) {
            return;
        }

        try {
            $attempt->forceFill(['idempotency_key' => $key])->save();
        } catch (\Throwable $e) {
            // unique conflict (key tercatat di attempt lain) — biarkan,
            // perlindungan idempotency utama ada di cache + DB uniqueness.
        }
    }

    private function resolveMargin(AttendanceFraudContext $context): ?float
    {
        if ($context->instansi === null || $context->distanceToInstance === null) {
            return null;
        }

        $radius = (float) ($context->instansi->radius_absen ?? 100);

        return round($radius - $context->distanceToInstance, 2);
    }

    private function writeAuditLogs(
        AttendanceFraudContext $context,
        AttendanceFraudResult $result,
        AttendanceAttempt $attempt,
        string $outcome,
    ): void {
        // Event minimal §31 — metadata ringkas, structured.
        if ($result->score >= 25) {
            $this->auditLog->record('attendance.fraud.flagged', $attempt, [
                'application_id' => $context->application->id,
                'risk_score' => $result->score,
                'fraud_status' => $result->status->value,
                'indicators' => $result->indicatorCodes(),
                'mode' => config('attendance.mode'),
            ]);
        }

        if ($outcome === 'blocked') {
            $this->auditLog->record('attendance.fraud.blocked', $attempt, [
                'application_id' => $context->application->id,
                'risk_score' => $result->score,
                'indicators' => $result->indicatorCodes(),
            ]);
        }

        // Signal anomali lokasi / travel spesifik (§31)
        $this->recordSpecificEvents($context, $result, $attempt);
    }

    private function recordSpecificEvents(
        AttendanceFraudContext $context,
        AttendanceFraudResult $result,
        AttendanceAttempt $attempt,
    ): void {
        $codes = $result->indicatorCodes();

        if (in_array('IMPOSSIBLE_TRAVEL', $codes, true)) {
            $signal = $result->signals->first(
                fn ($s) => $s->code === 'IMPOSSIBLE_TRAVEL'
            );

            $this->auditLog->record('attendance.impossible_travel', $attempt, [
                'application_id' => $context->application->id,
                'metadata' => $signal?->metadata,
                'risk_score' => $result->score,
            ]);
        }

        $locationAnomalies = array_intersect(
            ['BOUNDARY_UNCERTAINTY', 'STATIC_COORDINATE_PATTERN', 'ACCURACY_VERY_HIGH'],
            $codes
        );

        if ($locationAnomalies !== []) {
            $this->auditLog->record('attendance.location.anomaly', $attempt, [
                'application_id' => $context->application->id,
                'indicators' => array_values($locationAnomalies),
            ]);
        }
    }
}
