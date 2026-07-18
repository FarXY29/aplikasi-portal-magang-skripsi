<?php

namespace App\Http\Requests\Peserta;

use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan membuat request ini.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'peserta';
    }

    /**
     * Aturan validasi input untuk pendaftaran magang.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'letter_number' => ['nullable', 'string', 'max:100'],
            'surat' => ['required', 'file', 'mimetypes:application/pdf', 'max:2048'],
            'tanggal_mulai' => ['required', 'date', 'after_or_equal:today'],
            'tanggal_selesai' => ['required', 'date', 'after:tanggal_mulai'],
        ];
    }

    /**
     * Pesan kesalahan khusus dalam bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'letter_number.max' => 'Nomor surat pengantar maksimal 100 karakter.',
            'surat.required' => 'Surat pengantar dari kampus/sekolah wajib diunggah.',
            'surat.file' => 'Surat pengantar harus berupa berkas yang valid.',
            'surat.mimetypes' => 'Surat pengantar harus berformat PDF resmi.',
            'surat.max' => 'Ukuran berkas surat pengantar maksimal 2 MB.',
            'tanggal_mulai.required' => 'Tanggal mulai magang wajib diisi.',
            'tanggal_mulai.date' => 'Format tanggal mulai tidak valid.',
            'tanggal_mulai.after_or_equal' => 'Tanggal mulai magang minimal hari ini atau di masa depan.',
            'tanggal_selesai.required' => 'Tanggal selesai magang wajib diisi.',
            'tanggal_selesai.date' => 'Format tanggal selesai tidak valid.',
            'tanggal_selesai.after' => 'Tanggal selesai magang harus setelah tanggal mulai magang.',
        ];
    }
}
