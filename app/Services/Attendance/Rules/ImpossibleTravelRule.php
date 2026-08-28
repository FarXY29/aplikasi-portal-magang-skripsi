<?php

namespace App\Services\Attendance\Rules;

use App\Models\Attendance;
use App\Services\Attendance\AttendanceFraudContext;
use App\Services\Attendance\FraudSignal;
use App\Services\Attendance\GeoDistanceService;
use Carbon\Carbon;

/**
 * Impossible travel (§12).
 *
 * Membandingkan attendance sebelumnya dengan posisi sekarang:
 *
 *   effective_distance = max(0, raw_distance - prev_accuracy - curr_accuracy)
 *   required_speed_kmh = (effective_distance / 1000) / (elapsed / 3600)
 *
 * GPS error handling (§13): accuracy compensation mencegah false positive
 * dari perpindahan beberapa meter.
 *
 * SKIP bila elapsed time > travel_window_seconds (mis. Senin 17:00 Jakarta
 * → Selasa 08:00 Bandung adalah normal).
 */
class ImpossibleTravelRule extends AttendanceFraudRule
{
    public function __construct(
        private readonly GeoDistanceService $geo,
    ) {
    }

    public function code(): string
    {
        return 'IMPOSSIBLE_TRAVEL';
    }

    public function category(): string
    {
        return 'behavior';
    }

    public function evaluate(AttendanceFraudContext $context): ?FraudSignal
    {
        $previous = $this->resolvePreviousFix($context);

        if ($previous === null || !$context->hasClientLocation()) {
            return null;
        }

        [$prevLat, $prevLng, $prevAccuracy, $prevTime] = $previous;

        $elapsed = $context->serverReceivedAt->getTimestamp() - $prevTime;
        $window = (int) config('attendance.thresholds.travel_window_seconds', 6 * 3600);

        // Jangan menilai travel untuk selisih waktu terlalu lama.
        if ($elapsed <= 0 || $elapsed > $window) {
            return null;
        }

        $rawDistanceM = $this->geo->distanceMeters(
            $prevLat,
            $prevLng,
            $context->latitude,
            $context->longitude,
        );

        // Accuracy compensation — GPS error tidak dianggap perpindahan fisik.
        $effectiveDistanceM = max(
            0,
            $rawDistanceM - $prevAccuracy - ($context->accuracy ?? 0),
        );

        if ($effectiveDistanceM < 100) {
            return null; // terlalu kecil untuk dinilai
        }

        $requiredSpeedKmh = ($effectiveDistanceM / 1000) / ($elapsed / 3600);

        $t = config('attendance.thresholds');
        $s = config('attendance.scores');

        $metadata = [
            'distance_km' => round($rawDistanceM / 1000, 3),
            'effective_distance_m' => round($effectiveDistanceM, 1),
            'elapsed_seconds' => $elapsed,
            'required_speed_kmh' => round($requiredSpeedKmh, 2),
        ];

        if ($requiredSpeedKmh > $t['travel_critical_kmh']) {
            return new FraudSignal('IMPOSSIBLE_TRAVEL', $this->category(), 'critical', (int) $s['travel_critical'], $metadata);
        }

        if ($requiredSpeedKmh > $t['travel_high_kmh']) {
            return new FraudSignal('IMPOSSIBLE_TRAVEL', $this->category(), 'high', (int) $s['travel_high'], $metadata);
        }

        if ($requiredSpeedKmh > $t['travel_warning_kmh']) {
            return new FraudSignal('IMPOSSIBLE_TRAVEL', $this->category(), 'medium', (int) $s['travel_warning'], $metadata);
        }

        return null;
    }

    /**
     * Titik fix sebelumnya: koordinat terakhir tersedia dari attendance
     * (clock-out hari sama bila sedang clock-in ulang, atau clock-in terbaru).
     *
     * @return array{0: float, 1: float, 2: float, 3: int}|null
     */
    private function resolvePreviousFix(AttendanceFraudContext $context): ?array
    {
        $previous = $context->previousAttendance;

        if (!$previous instanceof Attendance) {
            return null;
        }

        $lat = $previous->latitude_out ?? $previous->latitude_in;
        $lng = $previous->longitude_out ?? $previous->longitude_in;

        if ($lat === null || $lng === null) {
            return null;
        }

        // Waktu fix: gabung date + jam clock terakhir tersedia.
        $timeStr = $previous->clock_out ?? $previous->clock_in;
        if ($timeStr === null) {
            return null;
        }

        $dateStr = $previous->date instanceof Carbon
            ? $previous->date->format('Y-m-d')
            : (string) $previous->date;

        $at = Carbon::parse($dateStr . ' ' . $timeStr);

        return [
            (float) $lat,
            (float) $lng,
            0.0, // accuracy fix sebelumnya tidak tersedia (tidak disimpan lama)
            $at->getTimestamp(),
        ];
    }
}
