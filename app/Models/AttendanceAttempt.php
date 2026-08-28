<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Audit SETIAP attempt absensi — termasuk yang ditolak (§7).
 * Sumber bukti investigasi fraud, bukan pengganti tabel attendances.
 */
class AttendanceAttempt extends Model
{
    protected $fillable = [
        'user_id',
        'application_id',
        'instance_id',
        'attendance_id',
        'attendance_type',
        'attempt_uuid',
        'idempotency_key',
        'latitude',
        'longitude',
        'accuracy',
        'altitude',
        'speed',
        'heading',
        'client_timestamp',
        'server_received_at',
        'distance_to_instance',
        'location_margin',
        'ip_address',
        'user_agent',
        'session_hash',
        'risk_score',
        'fraud_status',
        'risk_indicators',
    ];

    protected $casts = [
        'server_received_at' => 'datetime',
        'risk_indicators' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function instance()
    {
        return $this->belongsTo(Instansi::class);
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function fraudEvents()
    {
        return $this->hasMany(AttendanceFraudEvent::class);
    }
}
