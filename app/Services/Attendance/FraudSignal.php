<?php

namespace App\Services\Attendance;

/**
 * Satu signal fraud individual (akan dipersist ke attendance_fraud_events).
 */
class FraudSignal
{
    public function __construct(
        public readonly string $code,
        public readonly string $category, // location_confidence | temporal_consistency | ...
        public readonly string $severity, // low | medium | high | critical
        public readonly int $scoreDelta,
        public readonly array $metadata = [],
    ) {
    }

    public function describe(): string
    {
        return match ($this->code) {
            'INVALID_NONCE' => 'Token keamanan absensi tidak valid/kadaluarsa/dipakai ulang',
            'ACCURACY_VERY_HIGH' => 'Akurasi GPS sangat buruk (>200m)',
            'ACCURACY_SUSPICIOUS' => 'Akurasi GPS buruk (100-200m)',
            'ACCURACY_LOW_CONCERN' => 'Akurasi GPS kurang baik (50-100m)',
            'BOUNDARY_UNCERTAINTY' => 'Posisi dekat batas radius dengan kepercayaan lokasi rendah',
            'IMPOSSIBLE_TRAVEL' => 'Perpindahan lokasi tidak mungkin secara fisik',
            'STATIC_COORDINATE_PATTERN' => 'Pola koordinat identik berulang (mencurigakan)',
            'CLIENT_TIME_DRIFT' => 'Waktu perangkat tidak sinkron dengan server',
            'FUTURE_CLIENT_TIMESTAMP' => 'Waktu perangkat lebih maju dari server',
            'MULTIPLE_ATTEMPTS' => 'Beberapa percobaan absensi dalam waktu singkat',
            'EXCESSIVE_ATTEMPTS' => 'Percobaan absensi sangat sering dalam waktu singkat',
            'IP_CHANGE' => 'Perubahan alamat IP dari attempt sebelumnya',
            'UA_CHANGE' => 'Perubahan User-Agent dari attempt sebelumnya',
            default => $this->code,
        };
    }
}
