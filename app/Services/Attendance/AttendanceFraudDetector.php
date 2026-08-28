<?php

namespace App\Services\Attendance;

use App\Services\Attendance\Rules\AccuracyRule;
use App\Services\Attendance\Rules\BoundaryConfidenceRule;
use App\Services\Attendance\Rules\ImpossibleTravelRule;
use App\Services\Attendance\Rules\LocationConsistencyRule;
use App\Services\Attendance\Rules\NetworkAnomalyRule;
use App\Services\Attendance\Rules\RequestFrequencyRule;
use App\Services\Attendance\Rules\SessionConsistencyRule;
use App\Services\Attendance\Rules\TimestampRule;
use Illuminate\Support\Collection;

/**
 * Orkestrator fraud detection (§9) — controller hanya memanggil detect().
 *
 * Fraud engine TIDAK menggantikan geofence existing; hasilnya berupa
 * risk score + evidence untuk keputusan berbasis mode (shadow/soft/enforce).
 */
class AttendanceFraudDetector
{
    /**
     * @param AttendanceFraudRule[] $rules
     */
    public function __construct(
        private readonly AttendanceRiskScorer $scorer,
        private readonly array $rules = [],
    ) {
    }

    public static function make(self $resolver): self
    {
        return $resolver; // helper untuk container binding bila diperlukan
    }

    /**
     * Rule default engine (dapat dioverride via container).
     *
     * @return AttendanceFraudRule[]
     */
    public static function defaultRules(): array
    {
        $geo = app(GeoDistanceService::class);

        return [
            app(AccuracyRule::class),
            app(TimestampRule::class),
            new ImpossibleTravelRule($geo),
            app(BoundaryConfidenceRule::class),
            new LocationConsistencyRule($geo),
            app(RequestFrequencyRule::class),
            app(NetworkAnomalyRule::class),
            app(SessionConsistencyRule::class),
        ];
    }

    /**
     * Jalankan seluruh rule terhadap konteks → FraudResult.
     *
     * Fail-open: error pada satu rule tidak menghentikan rule lain
     * (detector tidak boleh merusak flow absensi utama).
     */
    public function detect(AttendanceFraudContext $context): AttendanceFraudResult
    {
        $rules = $this->rules !== [] ? $this->rules : self::defaultRules();

        $signals = collect();

        foreach ($rules as $rule) {
            try {
                $signal = $rule->evaluate($context);
                if ($signal !== null) {
                    $signals->push($signal);
                }
            } catch (\Throwable $e) {
                \Log::warning('Attendance fraud rule failed', [
                    'rule' => $rule::class,
                    'error' => $e->getMessage(),
                    'user_id' => $context->user->id,
                ]);
            }
        }

        return $this->scorer->score($signals);
    }

    /**
     * Tandai nonce invalid sebagai signal hard (dipakai controller sebelum
     * rule lain bila nonce wajib dan gagal dikonsumsi).
     */
    public function nonceInvalidSignal(array $metadata = []): FraudSignal
    {
        return new FraudSignal(
            'INVALID_NONCE',
            'request_integrity',
            'critical',
            (int) config('attendance.scores.nonce_invalid', 100),
            $metadata,
        );
    }

    /**
     * Score khusus nonce invalid (langsung CRITICAL 100).
     */
    public function scoreNonceInvalid(array $metadata = []): AttendanceFraudResult
    {
        return $this->scorer->score(collect([$this->nonceInvalidSignal($metadata)]));
    }
}
