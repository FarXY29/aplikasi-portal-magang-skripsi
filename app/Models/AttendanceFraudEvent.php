<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Event fraud individual per attempt (§8) — admin dapat mengetahui
 * MENGAPA sebuah absensi dianggap suspicious.
 */
class AttendanceFraudEvent extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'attendance_attempt_id',
        'code',
        'severity',
        'score_delta',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function attempt()
    {
        return $this->belongsTo(AttendanceAttempt::class);
    }
}
