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
     * Aturan validasi input untuk absen masuk.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
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
            'status.in' => 'Status kehadiran tidak dikenali.',
            'keterangan.max' => 'Keterangan izin maksimal 500 karakter.',
        ];
    }
}
