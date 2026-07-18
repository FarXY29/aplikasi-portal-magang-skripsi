<?php

namespace App\Services;

use Carbon\Carbon;

class AttendanceService
{
    /**
     * Memeriksa apakah waktu saat ini berada dalam rentang jadwal buka absensi instansi.
     */
    public function verifyTimeWindow(Carbon $now, ?string $targetTimeStr, string $type = 'masuk'): array
    {
        $defaultTime = $type === 'masuk' ? '07:30:00' : '16:00:00';
        $timeStr = $targetTimeStr ?? $defaultTime;

        $targetTime = Carbon::createFromFormat('H:i:s', $timeStr);

        if ($now->lessThan($targetTime)) {
            $label = $type === 'masuk' ? 'datang' : 'pulang';
            return [
                'is_valid' => false,
                'message' => "Absen {$label} belum dibuka. Jadwal absen {$label} dimulai pukul " . $targetTime->format('H:i'),
            ];
        }

        return ['is_valid' => true, 'message' => 'Waktu valid.'];
    }

    /**
     * Memeriksa dan memvalidasi koordinat GPS peserta apakah berada dalam radius absensi kantor instansi.
     */
    public function verifyGpsLocation($requestLat, $requestLng, ?float $officeLat, ?float $officeLng, int $maxRadiusMeters = 100, string $type = 'Datang'): array
    {
        if ($officeLat && $officeLng) {
            if ($requestLat === null || $requestLng === null) {
                return [
                    'is_valid' => false,
                    'message' => "Gagal Absen {$type}! Lokasi GPS Anda tidak ditemukan. Pastikan izin lokasi (Location/GPS) diaktifkan di browser/HP Anda.",
                ];
            }

            $jarakKm = $this->calculateDistance($requestLat, $requestLng, $officeLat, $officeLng);
            $jarakMeter = $jarakKm * 1000;

            if ($jarakMeter > $maxRadiusMeters) {
                return [
                    'is_valid' => false,
                    'distance_meters' => $jarakMeter,
                    'message' => "Gagal Absen {$type}! Posisi Anda berada di luar radius kantor (" . number_format($jarakMeter, 0) . " meter, batas maksimal {$maxRadiusMeters} meter).",
                ];
            }

            return ['is_valid' => true, 'distance_meters' => $jarakMeter];
        }

        // Jika kantor tidak menentukan koordinat GPS, izinkan absen dari mana saja
        return ['is_valid' => true, 'distance_meters' => 0];
    }

    /**
     * Fungsi Matematika Haversine (Menghitung Jarak 2 Titik Koordinat dalam KM).
     */
    public function calculateDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371; // Radius bumi dalam KM

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return $distance;
    }
}
