<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'date',
        'status',
        'clock_in',
        'clock_out',
        'description',
        'proof_file',
        'validation_status',
        'pembimbing_lapangan_note',
        'latitude_in',
        'longitude_in',
        'latitude_out',
        'longitude_out',
        'ip_address',
        'device_info',
        'risk_score',
        'fraud_status',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * Attempt absensi (clock-in/out) yang menghasilkan record ini —
     * bukti audit anti-fraud (§28).
     */
    public function attempts()
    {
        return $this->hasMany(AttendanceAttempt::class);
    }
}
