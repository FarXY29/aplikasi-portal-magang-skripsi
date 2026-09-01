<?php

namespace App\Services\Attendance\Rules;

use App\Services\Attendance\AttendanceFraudContext;
use App\Services\Attendance\FraudSignal;

/**
 * Boundary confidence (§11).
 *
 * location_margin = radius_absen - distance_to_instance
 *
 * Jika location_margin < accuracy → BOUNDARY_UNCERTAINTY:
 * secara geofence "inside" tetapi confidence lokasi buruk.
 * TIDAK auto-reject — hanya menaikkan risk (+5 s.d. +10 proporsional).
 */
class BoundaryConfidenceRule extends AttendanceFraudRule
{
    public function code(): string
    {
        return 'BOUNDARY_UNCERTAINTY';
    }

    public function category(): string
    {
        return 'location_confidence';
    }

    public function evaluate(AttendanceFraudContext $context): ?FraudSignal
    {
        if (!$context->hasClientLocation() || $context->instansi === null) {
            return null;
        }

        $distance = $context->distanceToInstance;
        if ($distance === null) {
            return null;
        }

        $radius = (float) ($context->instansi->radius_absen ?? 100);
        $accuracy = $context->accuracy;

        // Tanpa accuracy, nilai confidence tidak dapat dinilai.
        if ($accuracy === null || $accuracy <= 0) {
            return null;
        }

        $margin = $radius - $distance;

        if ($margin >= $accuracy) {
            return null; // margin cukup terhadap uncertainty GPS
        }

        // Proporsional: semakin dekat batas & semakin buruk accuracy,
        // semakin tinggi delta (cap di boundary_uncertainty_max).
        $s = config('attendance.scores');
        $min = (int) $s['boundary_uncertainty'];
        $max = (int) $s['boundary_uncertainty_max'];

        // ratio 0 (margin≈accuracy) → min; ratio -1 (di luar/injak batas) → max
        $ratio = $accuracy > 0 ? max(-1, min(0, $margin / $accuracy)) : 0;
        $delta = (int) round($min + (-$ratio) * ($max - $min));

        return new FraudSignal(
            'BOUNDARY_UNCERTAINTY',
            $this->category(),
            'low',
            max($min, min($max, $delta)),
            [
                'radius_m' => round($radius, 1),
                'distance_m' => round($distance, 1),
                'location_margin_m' => round($margin, 1),
                'accuracy_m' => round($accuracy, 1),
            ],
        );
    }
}
