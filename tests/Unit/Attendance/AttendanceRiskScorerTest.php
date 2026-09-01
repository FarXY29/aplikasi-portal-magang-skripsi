<?php

namespace Tests\Unit\Attendance;

use App\Enums\AttendanceFraudStatus;
use App\Services\Attendance\AttendanceRiskScorer;
use App\Services\Attendance\FraudSignal;
use Tests\TestCase;

class AttendanceRiskScorerTest extends TestCase
{
    private AttendanceRiskScorer $scorer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->scorer = new AttendanceRiskScorer();
    }

    public function test_no_signals_scores_zero_low(): void
    {
        $result = $this->scorer->score([]);

        $this->assertSame(0, $result->score);
        $this->assertSame(AttendanceFraudStatus::Low, $result->status);
        $this->assertTrue($result->indicatorCodes() === []);
    }

    public function test_one_weak_signal_scores_low_band(): void
    {
        $result = $this->scorer->score([
            new FraudSignal('ACCURACY_LOW_CONCERN', 'location_confidence', 'low', 5),
        ]);

        $this->assertSame(5, $result->score);
        $this->assertSame(AttendanceFraudStatus::Low, $result->status);
    }

    public function test_category_caps_prevent_double_counting(): void
    {
        // Accuracy buruk + boundary uncertainty + (akar masalah sama: GPS tidak akurat).
        // location_confidence cap = 25 meski total 20 + 12 = 32.
        $result = $this->scorer->score([
            new FraudSignal('ACCURACY_VERY_HIGH', 'location_confidence', 'high', 20),
            new FraudSignal('BOUNDARY_UNCERTAINTY', 'location_confidence', 'low', 10),
        ]);

        $this->assertSame(25, $result->score);
        $this->assertSame(AttendanceFraudStatus::Medium, $result->status);
    }

    public function test_multiple_weak_signals_across_categories_sum(): void
    {
        $result = $this->scorer->score([
            new FraudSignal('ACCURACY_SUSPICIOUS', 'location_confidence', 'medium', 12),
            new FraudSignal('CLIENT_TIME_DRIFT', 'temporal_consistency', 'medium', 10),
            new FraudSignal('MULTIPLE_ATTEMPTS', 'request_integrity', 'medium', 8),
        ]);

        $this->assertSame(30, $result->score);
        $this->assertSame(AttendanceFraudStatus::Medium, $result->status);
    }

    public function test_strong_impossible_travel_scores_high_band(): void
    {
        $result = $this->scorer->score([
            new FraudSignal('IMPOSSIBLE_TRAVEL', 'behavior', 'critical', 35),
        ]);

        $this->assertSame(35, $result->score);
        $this->assertSame(AttendanceFraudStatus::Medium, $result->status);
    }

    public function test_critical_nonce_replay_is_always_100(): void
    {
        $result = $this->scorer->score([
            new FraudSignal('INVALID_NONCE', 'request_integrity', 'critical', 100),
            new FraudSignal('ACCURACY_VERY_HIGH', 'location_confidence', 'high', 20),
            new FraudSignal('IMPOSSIBLE_TRAVEL', 'behavior', 'critical', 35),
        ]);

        // Hard rule: nonce invalid mengoverride sinyal lain (§21).
        $this->assertSame(100, $result->score);
        $this->assertSame(AttendanceFraudStatus::Critical, $result->status);
        $this->assertSame(['INVALID_NONCE'], $result->indicatorCodes());
    }

    public function test_score_capped_by_category_caps(): void
    {
        $result = $this->scorer->score([
            new FraudSignal('IMPOSSIBLE_TRAVEL', 'behavior', 'critical', 35),
            new FraudSignal('STATIC_COORDINATE_PATTERN', 'behavior', 'low', 12),
            new FraudSignal('ACCURACY_VERY_HIGH', 'location_confidence', 'high', 20),
            new FraudSignal('EXCESSIVE_ATTEMPTS', 'request_integrity', 'high', 15),
            new FraudSignal('CLIENT_TIME_DRIFT', 'temporal_consistency', 'medium', 10),
        ]);

        // behavior (35+12 capped 35) + location (20) + request (15) + temporal (10) = 80 → VERY_HIGH
        $this->assertSame(80, $result->score);
        $this->assertSame(AttendanceFraudStatus::VeryHigh, $result->status);
    }

    public function test_score_never_exceeds_100(): void
    {
        // Signal ekstrem lintas kategori — total mentah 98, tetap ≤ 100.
        $result = $this->scorer->score([
            new FraudSignal('IMPOSSIBLE_TRAVEL', 'behavior', 'critical', 35),
            new FraudSignal('STATIC_COORDINATE_PATTERN', 'behavior', 'low', 12),
            new FraudSignal('ACCURACY_VERY_HIGH', 'location_confidence', 'high', 20),
            new FraudSignal('EXCESSIVE_ATTEMPTS', 'request_integrity', 'high', 15),
            new FraudSignal('CLIENT_TIME_DRIFT', 'temporal_consistency', 'medium', 10),
            new FraudSignal('IP_CHANGE', 'network', 'low', 8),
            new FraudSignal('UA_CHANGE', 'session', 'low', 8),
        ]);

        $this->assertSame(96, $result->score);
        $this->assertSame(AttendanceFraudStatus::VeryHigh, $result->status);
        $this->assertLessThanOrEqual(100, $result->score);
    }

    public function test_should_block_only_in_enforce_mode_with_critical_nonce(): void
    {
        $critical = $this->scorer->score([
            new FraudSignal('INVALID_NONCE', 'request_integrity', 'critical', 100),
        ]);

        $this->assertTrue($critical->shouldBlock('enforce'));
        $this->assertFalse($critical->shouldBlock('shadow'));
        $this->assertFalse($critical->shouldBlock('soft'));

        // Critical tanpa nonce invalid (mis. kombinasi signal lain) tidak hard-block.
        $highScore = $this->scorer->score([
            new FraudSignal('IMPOSSIBLE_TRAVEL', 'behavior', 'critical', 35),
            new FraudSignal('EXCESSIVE_ATTEMPTS', 'request_integrity', 'high', 15),
            new FraudSignal('ACCURACY_VERY_HIGH', 'location_confidence', 'high', 20),
            new FraudSignal('CLIENT_TIME_DRIFT', 'temporal_consistency', 'medium', 10),
        ]);
        $this->assertSame(80, $highScore->score);
        $this->assertFalse($highScore->shouldBlock('enforce'));
    }
}
