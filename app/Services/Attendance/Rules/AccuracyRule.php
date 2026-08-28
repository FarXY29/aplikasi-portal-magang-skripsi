<?php

namespace App\Services\Attendance\Rules;

use App\Services\Attendance\AttendanceFraudContext;
use App\Services\Attendance\FraudSignal;

/**
 * Analisis akurasi GPS (§10.1) — confidence signal SAJA.
 *
 * Jangan langsung reject hanya karena accuracy buruk; accuracy buruk
 * meningkatkan risk score.
 */
class AccuracyRule extends AttendanceFraudRule
{
    public function code(): string
    {
        return 'ACCURACY';
    }

    public function category(): string
    {
        return 'location_confidence';
    }

    public function evaluate(AttendanceFraudContext $context): ?FraudSignal
    {
        $accuracy = $context->accuracy;

        if ($accuracy === null || $accuracy < 0) {
            // Accuracy tidak dikirim → tidak menilai di sini (request
            // frequency / nonce rule yang menangani pola incomplete).
            return null;
        }

        $t = config('attendance.thresholds');
        $s = config('attendance.scores');

        if ($accuracy > $t['accuracy_high']) {
            return new FraudSignal(
                'ACCURACY_VERY_HIGH',
                $this->category(),
                'high',
                (int) $s['accuracy_high'],
                ['accuracy_m' => round($accuracy, 2)],
            );
        }

        if ($accuracy > $t['accuracy_suspicious']) {
            return new FraudSignal(
                'ACCURACY_SUSPICIOUS',
                $this->category(),
                'medium',
                (int) $s['accuracy_suspicious'],
                ['accuracy_m' => round($accuracy, 2)],
            );
        }

        if ($accuracy > $t['accuracy_warning']) {
            return new FraudSignal(
                'ACCURACY_LOW_CONCERN',
                $this->category(),
                'low',
                (int) $s['accuracy_warning'],
                ['accuracy_m' => round($accuracy, 2)],
            );
        }

        return null;
    }
}
