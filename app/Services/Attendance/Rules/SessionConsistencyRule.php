<?php

namespace App\Services\Attendance\Rules;

use App\Services\Attendance\AttendanceFraudContext;
use App\Services\Attendance\FraudSignal;

/**
 * Session/User-Agent consistency (§18) — weak signal.
 *
 * User-Agent dan fingerprint adalah attacker-controlled → tidak pernah
 * hard block. Hanya perubahan tiba-tiba yang tercatat sebagai konteks.
 */
class SessionConsistencyRule extends AttendanceFraudRule
{
    public function code(): string
    {
        return 'UA_CHANGE';
    }

    public function category(): string
    {
        return 'session';
    }

    public function evaluate(AttendanceFraudContext $context): ?FraudSignal
    {
        $currentUa = $context->userAgent;
        $previousUa = $context->previousUserAgent;

        if ($currentUa === null || $previousUa === null || trim($previousUa) === '') {
            return null;
        }

        if (hash_equals(trim($previousUa), trim($currentUa))) {
            return null;
        }

        $s = config('attendance.scores');

        return new FraudSignal(
            'UA_CHANGE',
            $this->category(),
            'low',
            (int) $s['session_anomaly'],
            [
                'previous_ua_hash' => hash('sha256', trim($previousUa)),
                'current_ua_hash' => hash('sha256', trim($currentUa)),
                'note' => 'Perubahan User-Agent — attacker-controlled, signal lemah (§18)',
            ],
        );
    }
}
