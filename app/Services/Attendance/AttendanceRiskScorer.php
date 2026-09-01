<?php

namespace App\Services\Attendance;

use App\Enums\AttendanceFraudStatus;

/**
 * Skoring risiko fraud (§21, §22, §23).
 *
 * - Range 0-100, di-cap min(100, score).
 * - Category caps mencegah double-counting signal berakar masalah sama.
 * - Invalid/replayed nonce = 100 (CRITICAL).
 */
class AttendanceRiskScorer
{
    /**
     * @param Collection<int, FraudSignal> $signals
     */
    public function score(iterable $signals): AttendanceFraudResult
    {
        $signals = collect($signals);
        $scores = config('attendance.scores');
        $caps = config('attendance.category_caps');

        // Hard rule: nonce invalid → langsung CRITICAL 100.
        $nonceInvalid = $signals->first(
            fn (FraudSignal $s) => $s->code === 'INVALID_NONCE'
        );

        if ($nonceInvalid !== null) {
            $critical = new FraudSignal(
                'INVALID_NONCE',
                'request_integrity',
                'critical',
                (int) ($scores['nonce_invalid'] ?? 100),
                $nonceInvalid->metadata,
            );

            return new AttendanceFraudResult(
                (int) ($scores['nonce_invalid'] ?? 100),
                AttendanceFraudStatus::Critical,
                collect([$critical]),
            );
        }

        // Agregasi per-category dengan cap.
        $perCategory = [];
        foreach ($signals as $signal) {
            $perCategory[$signal->category] = ($perCategory[$signal->category] ?? 0) + $signal->scoreDelta;
        }

        $total = 0;
        foreach ($perCategory as $category => $sum) {
            $cap = $caps[$category] ?? 100;
            $total += min($sum, $cap);
        }

        $score = min(100, $total);

        return new AttendanceFraudResult(
            $score,
            AttendanceFraudStatus::fromScore($score),
            $signals->values(),
        );
    }
}
