<?php

namespace App\Services\Attendance\Rules;

use App\Services\Attendance\AttendanceFraudContext;
use App\Services\Attendance\FraudSignal;
use App\Services\Attendance\GeoDistanceService;

/**
 * Location consistency (§14) — WEAK signal.
 *
 * "Same office every day" adalah perilaku NORMAL → same coordinate
 * != fraud. Hanya pola koordinat identik PERSIS (sub-meter) berulang
 * yang dinilai, karena GPS asli hampir selalu bergerak beberapa meter.
 */
class LocationConsistencyRule extends AttendanceFraudRule
{
    private const EXACT_MATCH_METERS = 1.0;

    public function __construct(
        private readonly GeoDistanceService $geo,
    ) {
    }

    public function code(): string
    {
        return 'STATIC_COORDINATE_PATTERN';
    }

    public function category(): string
    {
        return 'behavior';
    }

    public function evaluate(AttendanceFraudContext $context): ?FraudSignal
    {
        if (!$context->hasClientLocation()) {
            return null;
        }

        // Kumpulkan semua fix histori + fix sekarang.
        $fixes = collect();

        foreach ($context->attendanceHistory as $attendance) {
            foreach ([
                [$attendance->latitude_in, $attendance->longitude_in],
                [$attendance->latitude_out, $attendance->longitude_out],
            ] as [$lat, $lng]) {
                if ($lat !== null && $lng !== null) {
                    $fixes->push([(float) $lat, (float) $lng]);
                }
            }
        }

        if ($fixes->count() < 3) {
            return null; // histori terlalu sedikit untuk menilai pola
        }

        // Hitung berapa fix histori identik (≤1m) dengan posisi sekarang.
        $exactMatches = $fixes->filter(
            fn (array $fix) => $this->geo->distanceMeters(
                $fix[0],
                $fix[1],
                $context->latitude,
                $context->longitude,
            ) <= self::EXACT_MATCH_METERS
        )->count();

        if ($exactMatches < 3) {
            return null;
        }

        $s = config('attendance.scores');
        $min = (int) $s['static_pattern_min'];
        $max = (int) $s['static_pattern_max'];

        // Skor naik seiring jumlah kecocokan identik (cap max).
        $delta = min($max, $min + ($exactMatches - 3));

        return new FraudSignal(
            'STATIC_COORDINATE_PATTERN',
            $this->category(),
            'low',
            $delta,
            [
                'exact_match_count' => $exactMatches,
                'match_threshold_m' => self::EXACT_MATCH_METERS,
                'note' => 'Koordinat identik sub-meter berulang (GPS asli umumnya bervariasi)',
            ],
        );
    }
}
