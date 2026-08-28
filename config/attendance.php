<?php

/*
|--------------------------------------------------------------------------
| Konfigurasi Anti-Fake-GPS & Attendance Fraud Detection
|--------------------------------------------------------------------------
|
| Layer anti-fraud TAMBAHAN di atas sistem absensi existing.
| Tidak menggantikan geofence/radius existing.
|
| Semua nilai dapat diubah via environment tanpa code change.
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Master Switch & Mode Operasi
    |--------------------------------------------------------------------------
    |
    | enabled  : false = seluruh layer nonaktif, flow absensi kembali
    |            persis seperti existing (rollback instan).
    |
    | mode     : shadow  → detector aktif & mencatat, TIDAK menolak.
    |            soft    → attendance suspicious tetap masuk tetapi ditandai.
    |            enforce → hard rules (nonce invalid/replay) dapat memblok.
    |
    | require_nonce : true = clock-in/out wajib menyertakan nonce challenge
    |            yang valid (single-use, short-lived). Nonce BUKAN bukti GPS
    |            asli — hanya pengurang replay/old-request abuse.
    |
    */
    'enabled' => env('ATTENDANCE_FRAUD_ENABLED', true),
    'mode' => env('ATTENDANCE_FRAUD_MODE', 'shadow'),
    'require_nonce' => env('ATTENDANCE_REQUIRE_NONCE', true),

    /*
    |--------------------------------------------------------------------------
    | Nonce / Attendance Challenge
    |--------------------------------------------------------------------------
    */
    'nonce_ttl' => env('ATTENDANCE_NONCE_TTL', 60), // detik

    /*
    |--------------------------------------------------------------------------
    | Idempotency
    |--------------------------------------------------------------------------
    | Request duplikat (header Idempotency-Key atau field idempotency_key)
    | mengembalikan hasil request sebelumnya, tidak membuat attendance kedua.
    */
    'idempotency_ttl' => env('ATTENDANCE_IDEMPOTENCY_TTL', 300), // detik

    /*
    |--------------------------------------------------------------------------
    | Atomic Lock (anti double-click / multi-tab / race)
    |--------------------------------------------------------------------------
    */
    'lock_ttl' => env('ATTENDANCE_LOCK_TTL', 10), // detik

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting (per menit, by user id, fallback IP)
    |--------------------------------------------------------------------------
    | Jangan IP-only: banyak peserta dapat berbagi jaringan yang sama.
    */
    'challenge_rate_limit' => env('ATTENDANCE_CHALLENGE_RATE_LIMIT', 10),
    'clock_rate_limit' => env('ATTENDANCE_CLOCK_RATE_LIMIT', 6),

    /*
    |--------------------------------------------------------------------------
    | Threshold Deteksi
    |--------------------------------------------------------------------------
    */
    'thresholds' => [

        // Accuracy (meter) — confidence signal, bukan alasan reject langsung
        'accuracy_warning' => 50,
        'accuracy_suspicious' => 100,
        'accuracy_high' => 200,

        // Impossible travel (km/jam, setelah accuracy compensation)
        'travel_warning_kmh' => 100,
        'travel_high_kmh' => 180,
        'travel_critical_kmh' => 300,
        // Skip analisis travel jika selisih waktu terlalu lama (detik).
        // Contoh: Senin 17:00 Jakarta → Selasa 08:00 Bandung = normal.
        'travel_window_seconds' => 6 * 3600,

        // Client vs server timestamp (detik) — client hanya signal
        'timestamp_warning_seconds' => 30,
        'timestamp_high_seconds' => 120,

        // Jumlah attempt dalam window request_frequency (menit)
        'attempts_warning' => 3,
        'attempts_high' => 6,
    ],

    /*
    |--------------------------------------------------------------------------
    | Window analisis perilaku
    |--------------------------------------------------------------------------
    */
    'request_frequency_window' => 15, // menit
    'location_history_count' => 10,  // attendance terakhir yang dianalisis

    /*
    |--------------------------------------------------------------------------
    | Bobot Skor Fraud (0-100, di-cap min(100, score))
    |--------------------------------------------------------------------------
    */
    'scores' => [
        'nonce_invalid' => 100,          // replay/invalid → CRITICAL

        'travel_critical' => 35,         // >300 km/jam
        'travel_high' => 25,             // 180-300
        'travel_warning' => 12,          // 100-180

        'accuracy_high' => 20,           // >200m
        'accuracy_suspicious' => 12,     // 100-200m
        'accuracy_warning' => 5,         // 50-100m

        'boundary_uncertainty' => 5,     // dasar; hingga +10 proporsional
        'boundary_uncertainty_max' => 10,

        'timestamp_anomaly' => 10,       // >120 detik drift
        'future_timestamp' => 10,        // client time di masa depan

        'attempts_high' => 15,           // >=6 attempt dalam window
        'attempts_warning' => 8,         // 3-5 attempt

        'static_pattern_min' => 6,       // koordinat identik sub-meter berulang
        'static_pattern_max' => 12,

        'ip_anomaly' => 4,               // weak — contextual saja
        'session_anomaly' => 3,          // weak
    ],

    /*
    |--------------------------------------------------------------------------
    | Category Caps — Anti Double-Counting (§22)
    |--------------------------------------------------------------------------
    | Signal dari akar masalah sama tidak boleh dihitung penuh berkali-kali.
    | (contoh: accuracy buruk + boundary uncertainty + near radius berasal
    |  dari satu kondisi GPS tidak akurat)
    */
    'category_caps' => [
        'location_confidence' => 25,
        'temporal_consistency' => 10,
        'request_integrity' => 15,
        'behavior' => 35,
        'session' => 8,
        'network' => 8,
    ],

    /*
    |--------------------------------------------------------------------------
    | Risk Status Bands (§23)
    |--------------------------------------------------------------------------
    | LOW 0-24 accept | MEDIUM 25-49 accept+tandai | HIGH 50-74 review
    | VERY_HIGH 75-99 review | CRITICAL 100 block (mode enforce)
    */
    'status_bands' => [
        'low' => ['min' => 0, 'max' => 24],
        'medium' => ['min' => 25, 'max' => 49],
        'high' => ['min' => 50, 'max' => 74],
        'very_high' => ['min' => 75, 'max' => 99],
        'critical' => ['min' => 100, 'max' => 100],
    ],

    /*
    |--------------------------------------------------------------------------
    | Retention Data Attempt (hari) — privasi (§36)
    |--------------------------------------------------------------------------
    | Attempt yang menghasilkan attendance sukses LOW dapat dipruna lebih
    | agresif; attempt suspicious disimpan lebih lama untuk investigasi.
    */
    'retention_days' => [
        'clean' => env('ATTENDANCE_ATTEMPT_RETENTION_CLEAN', 90),
        'flagged' => env('ATTENDANCE_ATTEMPT_RETENTION_FLAGGED', 365),
    ],
];
