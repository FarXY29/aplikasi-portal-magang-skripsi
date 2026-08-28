<?php

namespace App\Services\Attendance;

/**
 * Centralisasi perhitungan jarak geografis (Haversine).
 *
 * Behavior identik dengan AttendanceController::calculateDistance() dan
 * AttendanceService::calculateDistance() existing (earth radius 6371 KM),
 * sebagai langkah deduplikasi — bukan perubahan rule geofence.
 */
class GeoDistanceService
{
    public const EARTH_RADIUS_KM = 6371;

    /**
     * Jarak Haversine antara dua koordinat dalam KILOMETER.
     */
    public function distanceKm(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = self::EARTH_RADIUS_KM;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Jarak Haversine dalam METER.
     */
    public function distanceMeters(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        return $this->distanceKm($lat1, $lon1, $lat2, $lon2) * 1000;
    }
}
