<?php

namespace App\Services\Attendance\Rules;

use App\Services\Attendance\AttendanceFraudContext;
use App\Services\Attendance\FraudSignal;

/**
 * Base class fraud rule. Controller TIDAK berisi logic fraud (§9) —
 * setiap rule bertanggung jawab atas satu kategori signal.
 */
abstract class AttendanceFraudRule
{
    abstract public function code(): string;

    abstract public function category(): string;

    /**
     * Evaluasi konteks. Null = tidak ada signal (normal).
     */
    abstract public function evaluate(AttendanceFraudContext $context): ?FraudSignal;
}
