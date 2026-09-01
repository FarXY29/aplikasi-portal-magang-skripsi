<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;

class ClockInRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan membuat request ini.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'peserta';
    }

    /**
     * Aturan validasi input untuk absen masuk/pulang.
     *
     * SEMUA data lokasi berasal dari client → UNTRUSTED. Validasi hanya
     * memastikan format & rentang wajar; kebenaran lokasi tetap dinilai
     * server-side geofence + fraud engine.
     *
     * Catatan: kewajiban latitude/longitude saat instansi memiliki geofence
     * aktif di-resolve di controller (butuh konteks instansi user).
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Lokasi (dari navigator.geolocation)
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'accuracy' => ['nullable', 'numeric', 'min:0', 'max:100000'],
            'altitude' => ['nullable', 'numeric', 'min:-10000', 'max:100000'],
            'speed' => ['nullable', 'numeric', 'min:0', 'max:10000'],
            'heading' => ['nullable', 'numeric', 'min:0', 'max:360'],

            // Timestamp client (epoch milidetik) — hanya fraud signal,
            // TIDAK pernah menjadi sumber waktu attendance.
            'client_timestamp' => ['nullable', 'integer', 'min:0', 'max:99999999999999'],

            // Anti-replay (P0 §5.3) — single-use, user-bound, short-lived.
            'nonce' => ['nullable', 'string', 'max:64'],

            // Idempotency (P0 §5.2) — juga bisa dari header Idempotency-Key.
            'idempotency_key' => ['nullable', 'string', 'max:64'],

            // Dynamic QR token kantor (Dual-Factor Presence)
            'qr_token' => ['nullable', 'string', 'max:500'],

            'status' => ['nullable', 'string', 'in:hadir,izin,sakit'],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Pesan kesalahan khusus dalam bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'latitude.numeric' => 'Koordinat garis lintang (latitude) GPS tidak valid.',
            'latitude.between' => 'Nilai koordinat garis lintang berada di luar rentang GPS.',
            'longitude.numeric' => 'Koordinat garis bujur (longitude) GPS tidak valid.',
            'longitude.between' => 'Nilai koordinat garis bujur berada di luar rentang GPS.',
            'accuracy.numeric' => 'Nilai akurasi GPS tidak valid.',
            'accuracy.min' => 'Nilai akurasi GPS tidak valid.',
            'altitude.numeric' => 'Nilai ketinggian GPS tidak valid.',
            'speed.numeric' => 'Nilai kecepatan GPS tidak valid.',
            'heading.numeric' => 'Nilai arah (heading) GPS tidak valid.',
            'heading.between' => 'Nilai arah (heading) harus antara 0 sampai 360 derajat.',
            'client_timestamp.integer' => 'Timestamp perangkat tidak valid.',
            'nonce.string' => 'Token keamanan absensi tidak valid.',
            'nonce.max' => 'Token keamanan absensi tidak valid.',
            'idempotency_key.string' => 'Idempotency key tidak valid.',
            'idempotency_key.max' => 'Idempotency key tidak valid.',
            'qr_token.string' => 'Token Dynamic QR kantor tidak valid.',
            'qr_token.max' => 'Token Dynamic QR kantor melebihi batas panjang yang diizinkan.',
            'status.in' => 'Status kehadiran tidak dikenali.',
            'keterangan.max' => 'Keterangan izin maksimal 500 karakter.',
        ];
    }
}
