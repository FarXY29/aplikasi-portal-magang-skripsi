<?php

namespace Tests\Unit\Attendance;

use App\Models\Application;
use App\Models\Attendance;
use App\Models\Instansi;
use App\Models\User;
use App\Services\Attendance\AttendanceFraudContext;
use App\Services\Attendance\GeoDistanceService;
use App\Services\Attendance\Rules\ImpossibleTravelRule;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ImpossibleTravelRuleTest extends TestCase
{
    private ImpossibleTravelRule $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new ImpossibleTravelRule(new GeoDistanceService());
    }

    private function makeContext(array $overrides = []): AttendanceFraudContext
    {
        $defaults = [
            'user' => new User(),
            'application' => new Application(),
            'instansi' => null,
            'attendanceType' => 'clock_in',
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'accuracy' => 10.0,
            'altitude' => null,
            'speed' => null,
            'heading' => null,
            'clientTimestampMs' => null,
            'serverReceivedAt' => Carbon::now(),
            'ipAddress' => null,
            'userAgent' => null,
            'sessionHash' => null,
            'previousIpAddress' => null,
            'previousUserAgent' => null,
            'previousAttendance' => null,
            'attendanceHistory' => collect(),
            'recentAttemptCount' => 0,
            'distanceToInstance' => null,
        ];

        return new AttendanceFraudContext(...array_merge($defaults, $overrides));
    }

    public function test_no_previous_attendance_returns_null(): void
    {
        $this->assertNull($this->rule->evaluate($this->makeContext()));
    }

    public function test_normal_travel_same_city_returns_null(): void
    {
        // 3 km dalam 30 menit = 6 km/jam — jalan kaki.
        $previous = new Attendance([
            'date' => now()->toDateString(),
            'clock_in' => now()->subMinutes(30)->format('H:i:s'),
            'latitude_in' => -3.3000,
            'longitude_in' => 114.590111,
        ]);
        $previous->setRelation('application', new Application());

        $result = $this->rule->evaluate($this->makeContext([
            'previousAttendance' => $previous,
            'serverReceivedAt' => Carbon::now(),
        ]));

        $this->assertNull($result);
    }

    public function test_fast_but_possible_travel_returns_null(): void
    {
        // 60 km dalam 1 jam = 60 km/jam — mobil, wajar.
        $previous = new Attendance([
            'date' => now()->toDateString(),
            'clock_in' => now()->subHours(1)->format('H:i:s'),
            'latitude_in' => -3.316694 - 0.54, // ±60 km selatan
            'longitude_in' => 114.590111,
        ]);

        $this->assertNull($this->rule->evaluate($this->makeContext([
            'previousAttendance' => $previous,
            'serverReceivedAt' => Carbon::now(),
        ])));
    }

    public function test_clearly_impossible_travel_flags_critical(): void
    {
        // Banjarmasin → Banjarmasin 60 km dalam 2 menit = 1800 km/jam.
        $previous = new Attendance([
            'date' => now()->toDateString(),
            'clock_in' => now()->subMinutes(2)->format('H:i:s'),
            'latitude_in' => -3.316694 - 0.54,
            'longitude_in' => 114.590111,
        ]);

        $signal = $this->rule->evaluate($this->makeContext([
            'previousAttendance' => $previous,
            'serverReceivedAt' => Carbon::now(),
        ]));

        $this->assertNotNull($signal);
        $this->assertSame('IMPOSSIBLE_TRAVEL', $signal->code);
        $this->assertSame('critical', $signal->severity);
        $this->assertGreaterThan(300, $signal->metadata['required_speed_kmh']);
    }

    public function test_gps_accuracy_compensation_neutralizes_small_distance(): void
    {
        // Jarak 80 m tapi accuracy 50m + 50m → effective = 0 → bukan travel.
        $previous = new Attendance([
            'date' => now()->toDateString(),
            'clock_in' => now()->subSeconds(30)->format('H:i:s'),
            'latitude_in' => -3.316694 + 0.0004, // ±44 m
            'longitude_in' => 114.590111,
        ]);

        $this->assertNull($this->rule->evaluate($this->makeContext([
            'previousAttendance' => $previous,
            'accuracy' => 50.0,
            'serverReceivedAt' => Carbon::now(),
        ])));
    }

    public function test_long_time_gap_skips_analysis(): void
    {
        // Senin 17:00 Jakarta → Selasa 08:00 Bandung = normal (§12).
        $previous = new Attendance([
            'date' => now()->subDay()->toDateString(),
            'clock_out' => '17:00:00',
            'latitude_in' => -6.2088,
            'longitude_in' => 106.8456,
            'latitude_out' => -6.2088,
            'longitude_out' => 106.8456,
        ]);

        // Posisi sekarang: Bandung.
        $this->assertNull($this->rule->evaluate($this->makeContext([
            'previousAttendance' => $previous,
            'latitude' => -6.9175,
            'longitude' => 107.6191,
            'serverReceivedAt' => Carbon::now(),
        ])));
    }

    public function test_multiple_hours_gap_still_evaluated_within_window(): void
    {
        // 5 jam gap (dalam window 6 jam) dengan jarak 600 km → 120 km/jam → medium.
        $previous = new Attendance([
            'date' => now()->toDateString(),
            'clock_in' => now()->subHours(5)->format('H:i:s'),
            'latitude_in' => -3.316694 - 5.4,
            'longitude_in' => 114.590111,
        ]);

        $signal = $this->rule->evaluate($this->makeContext([
            'previousAttendance' => $previous,
            'serverReceivedAt' => Carbon::now(),
        ]));

        $this->assertNotNull($signal);
        $this->assertSame('medium', $signal->severity);
        $this->assertEqualsWithDelta(120, $signal->metadata['required_speed_kmh'], 25);
    }
}
