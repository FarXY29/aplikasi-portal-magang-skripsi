<?php

namespace App\Services\Attendance;

use App\Models\Application;
use App\Models\Attendance;
use App\Models\Instansi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Konteks immutable yang dilempar ke seluruh fraud rule.
 *
 * Semua field berasal dari client → UNTRUSTED, kecuali yang diambil
 * langsung dari database server (instansi, histori attendance).
 */
class AttendanceFraudContext
{
    public function __construct(
        public readonly User $user,
        public readonly Application $application,
        public readonly ?Instansi $instansi,
        public readonly string $attendanceType, // clock_in | clock_out
        public readonly ?float $latitude,
        public readonly ?float $longitude,
        public readonly ?float $accuracy,
        public readonly ?float $altitude,
        public readonly ?float $speed,
        public readonly ?float $heading,
        public readonly ?int $clientTimestampMs,
        public readonly Carbon $serverReceivedAt,
        public readonly ?string $ipAddress,
        public readonly ?string $userAgent,
        public readonly ?string $sessionHash,
        /** IP attempt terakhir user (weak signal network §17) */
        public readonly ?string $previousIpAddress,
        /** User-Agent attempt terakhir user (weak signal session §18) */
        public readonly ?string $previousUserAgent,
        /** Attendance terakhir user (untuk impossible travel), null bila belum ada */
        public readonly ?Attendance $previousAttendance,
        /** Histori attendance user (untuk location consistency) */
        public readonly Collection $attendanceHistory,
        /** Jumlah attempt dalam window request-frequency */
        public readonly int $recentAttemptCount,
        /** Jarak meter ke instansi (dihitung geofence) */
        public readonly ?float $distanceToInstance,
    ) {
    }

    /**
     * Selisih waktu client vs server dalam detik (positif = client lebih baru).
     * Null bila client timestamp tidak tersedia.
     */
    public function clientServerDriftSeconds(): ?float
    {
        if ($this->clientTimestampMs === null || $this->clientTimestampMs <= 0) {
            return null;
        }

        return ($this->clientTimestampMs / 1000) - $this->serverReceivedAt->getTimestamp();
    }

    public function hasClientLocation(): bool
    {
        return $this->latitude !== null && $this->longitude !== null;
    }
}
