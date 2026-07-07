<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'ip_address',
        'user_agent',
        'device_name',
        'last_activity_at',
    ];

    protected $casts = [
        'last_activity_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Parse user agent string menjadi nama device yang mudah dibaca.
     */
    public static function parseDeviceName(?string $userAgent): string
    {
        if (!$userAgent) {
            return 'Perangkat Tidak Dikenal';
        }

        // Deteksi browser
        $browser = 'Browser';
        if (str_contains($userAgent, 'Edg/') || str_contains($userAgent, 'Edge/')) {
            $browser = 'Edge';
        } elseif (str_contains($userAgent, 'OPR/') || str_contains($userAgent, 'Opera')) {
            $browser = 'Opera';
        } elseif (str_contains($userAgent, 'Chrome/') && !str_contains($userAgent, 'Edg/')) {
            $browser = 'Chrome';
        } elseif (str_contains($userAgent, 'Firefox/')) {
            $browser = 'Firefox';
        } elseif (str_contains($userAgent, 'Safari/') && !str_contains($userAgent, 'Chrome/')) {
            $browser = 'Safari';
        }

        // Deteksi OS/Platform
        $platform = 'Unknown';
        if (str_contains($userAgent, 'iPhone')) {
            $platform = 'iPhone';
        } elseif (str_contains($userAgent, 'iPad')) {
            $platform = 'iPad';
        } elseif (str_contains($userAgent, 'Android')) {
            if (str_contains($userAgent, 'Mobile')) {
                $platform = 'Android Phone';
            } else {
                $platform = 'Android Tablet';
            }
        } elseif (str_contains($userAgent, 'Windows')) {
            $platform = 'Windows';
        } elseif (str_contains($userAgent, 'Macintosh') || str_contains($userAgent, 'Mac OS')) {
            $platform = 'Mac';
        } elseif (str_contains($userAgent, 'Linux')) {
            $platform = 'Linux';
        }

        return "{$browser} di {$platform}";
    }
}
