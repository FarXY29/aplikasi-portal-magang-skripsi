<?php

namespace App\Services\Attendance\Rules;

use App\Services\Attendance\AttendanceFraudContext;
use App\Services\Attendance\FraudSignal;

/**
 * Request behavior (§16) — multiple attempts, rapid retries, duplicate,
 * repeated failed challenge, expired nonce usage, concurrent requests.
 *
 * 1-2 request normal | 3-5 warning | >=6 suspicious. Threshold configurable.
 */
class RequestFrequencyRule extends AttendanceFraudRule
{
    public function code(): string
    {
        return 'ATTEMPT_FREQUENCY';
    }

    public function category(): string
    {
        return 'request_integrity';
    }

    public function evaluate(AttendanceFraudContext $context): ?FraudSignal
    {
        $t = config('attendance.thresholds');
        $s = config('attendance.scores');

        $attempts = $context->recentAttemptCount; // attempt SEBELUM request ini
        $total = $attempts + 1; // termasuk attempt sekarang

        $metadata = [
            'attempts_in_window' => $total,
            'window_minutes' => (int) config('attendance.request_frequency_window', 15),
        ];

        if ($total >= $t['attempts_high']) {
            return new FraudSignal(
                'EXCESSIVE_ATTEMPTS',
                $this->category(),
                'high',
                (int) $s['attempts_high'],
                $metadata,
            );
        }

        if ($total >= $t['attempts_warning']) {
            return new FraudSignal(
                'MULTIPLE_ATTEMPTS',
                $this->category(),
                'medium',
                (int) $s['attempts_warning'],
                $metadata,
            );
        }

        return null;
    }
}
