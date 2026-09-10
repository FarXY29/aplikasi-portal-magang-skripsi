<?php

namespace Tests\Unit\Attendance;

use App\Enums\AttendanceFraudStatus;
use App\Enums\AttendanceOperationalDecision;
use App\Services\Attendance\AttendanceFraudResult;
use App\Services\Attendance\FraudSignal;
use Tests\TestCase;

class AttendanceFraudDecisionTest extends TestCase
{
    public function test_clean_result_resolves_to_accepted_decision(): void
    {
        $result = AttendanceFraudResult::clean();

        $this->assertSame(AttendanceFraudStatus::Low, $result->status);
        $this->assertSame(AttendanceOperationalDecision::Accepted, $result->operationalDecision('shadow'));
        $this->assertSame(AttendanceOperationalDecision::Accepted, $result->operationalDecision('soft'));
        $this->assertSame(AttendanceOperationalDecision::Accepted, $result->operationalDecision('enforce'));
    }

    public function test_high_and_critical_risk_resolves_to_accepted_for_review(): void
    {
        $highSignal = new FraudSignal('IMPOSSIBLE_TRAVEL', 'location_physics', 'high', 60);
        $highResult = new AttendanceFraudResult(60, AttendanceFraudStatus::High, collect([$highSignal]));

        $this->assertSame(AttendanceOperationalDecision::AcceptedForReview, $highResult->operationalDecision('shadow'));
        $this->assertSame(AttendanceOperationalDecision::AcceptedForReview, $highResult->operationalDecision('soft'));
        $this->assertSame(AttendanceOperationalDecision::AcceptedForReview, $highResult->operationalDecision('enforce'));
    }

    public function test_critical_invalid_nonce_blocks_in_enforce_mode_only(): void
    {
        $criticalSignal = new FraudSignal('INVALID_NONCE', 'request_integrity', 'critical', 100);
        $criticalResult = new AttendanceFraudResult(100, AttendanceFraudStatus::Critical, collect([$criticalSignal]));

        // In shadow and soft mode, it is accepted for review
        $this->assertSame(AttendanceOperationalDecision::AcceptedForReview, $criticalResult->operationalDecision('shadow'));
        $this->assertSame(AttendanceOperationalDecision::AcceptedForReview, $criticalResult->operationalDecision('soft'));

        // In enforce mode, hard rule rejects
        $this->assertTrue($criticalResult->shouldBlock('enforce'));
        $this->assertSame(AttendanceOperationalDecision::Rejected, $criticalResult->operationalDecision('enforce'));
    }

    public function test_medium_risk_in_soft_mode_resolves_to_review(): void
    {
        $signal = new FraudSignal('ACCURACY_HIGH', 'device_telemetry', 'medium', 30);
        $result = new AttendanceFraudResult(30, AttendanceFraudStatus::Medium, collect([$signal]));

        $this->assertSame(AttendanceOperationalDecision::Accepted, $result->operationalDecision('shadow'));
        $this->assertSame(AttendanceOperationalDecision::AcceptedForReview, $result->operationalDecision('soft'));
    }
}
