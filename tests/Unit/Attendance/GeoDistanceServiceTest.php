<?php

namespace Tests\Unit\Attendance;

use App\Services\Attendance\GeoDistanceService;
use PHPUnit\Framework\TestCase;

class GeoDistanceServiceTest extends TestCase
{
    private GeoDistanceService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new GeoDistanceService();
    }

    public function test_same_point_distance_is_zero(): void
    {
        $this->assertSame(0.0, $this->service->distanceKm(-3.316694, 114.590111, -3.316694, 114.590111));
    }

    public function test_distance_equator_one_degree_longitude(): void
    {
        // 1 derajat bujur di ekuator ≈ 111.19 km (Haversine, R=6371)
        $km = $this->service->distanceKm(0.0, 0.0, 0.0, 1.0);
        $this->assertEqualsWithDelta(111.19, $km, 0.5);
    }

    public function test_distance_banjarmasin_to_kotabaru(): void
    {
        // Banjarmasin → Kotabaru (Kalsel) ≈ 100 km (lebih kurang)
        $km = $this->service->distanceKm(-3.316694, 114.590111, -3.4147, 116.2);
        $this->assertGreaterThan(150, $km);
        $this->assertLessThan(180, $km);
    }

    public function test_distance_meters_conversion(): void
    {
        $m = $this->service->distanceMeters(0.0, 0.0, 0.0, 1.0);
        $this->assertEqualsWithDelta(111195.0, $m, 500.0);
    }

    public function test_identical_with_legacy_implementation(): void
    {
        // Implementasi Haversine legacy (persis dari controller existing).
        $legacy = function ($lat1, $lon1, $lat2, $lon2) {
            $earthRadius = 6371;
            $dLat = deg2rad($lat2 - $lat1);
            $dLon = deg2rad($lon2 - $lon1);
            $a = sin($dLat / 2) * sin($dLat / 2) +
                 cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
                 sin($dLon / 2) * sin($dLon / 2);
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

            return $earthRadius * $c;
        };

        $this->assertEquals(
            $legacy(-3.316694, 114.590111, -6.914744, 107.609810),
            $this->service->distanceKm(-3.316694, 114.590111, -6.914744, 107.609810)
        );
    }
}
