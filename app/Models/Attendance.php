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
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
