<?php

namespace App\Services\Attendance\Rules;

use App\Services\Attendance\AttendanceFraudContext;
use App\Services\Attendance\FraudSignal;

/**
 * Anomali timestamp client vs server (§15).
 *
 * Client timestamp hanya SIGNAL — tidak pernah menjadi sumber waktu
 * attendance (server `now()` tetap authoritative).
 */
class TimestampRule extends AttendanceFraudRule
{
    public function code(): string
    {
        return 'CLIENT_TIMESTAMP';
    }

    public function category(): string
    {
        return 'temporal_consistency';
    }

    public function evaluate(AttendanceFraudContext $context): ?FraudSignal
    {
        $drift = $context->clientServerDriftSeconds();

        if ($drift === null) {
            return null;
        }

        $t = config('attendance.thresholds');
        $s = config('attendance.scores');

        // Timestamp di masa depan = manipulasi jam perangkat.
        if ($drift > $t['timestamp_warning_seconds']) {
            return new FraudSignal(
                'FUTURE_CLIENT_TIMESTAMP',
                $this->category(),
                'medium',
                (int) $s['future_timestamp'],
                ['drift_seconds' => round($drift, 1)],
            );
        }

        // Stale berlebihan (mis. lokasi lama di-replay).
        if (-$drift > $t['timestamp_high_seconds']) {
            return new FraudSignal(
                'CLIENT_TIME_DRIFT',
                $this->category(),
                'medium',
                (int) $s['timestamp_anomaly'],
                ['drift_seconds' => round($drift, 1)],
            );
        }

        return null;
    }
}
