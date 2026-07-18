<?php

namespace App\Http\Requests\AdminInstansi;

use Illuminate\Foundation\Http\FormRequest;

class LowonganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin_instansi';
    }

    public function rules(): array
    {
        $rules = [
            'judul_posisi' => ['required', 'string', 'max:255'],
            'required_major' => ['nullable', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'kuota' => ['required', 'integer', 'min:1'],
            'batas_daftar' => ['nullable', 'date', 'after_or_equal:today'],
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['status'] = ['required', 'in:buka,tutup'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'judul_posisi.required' => 'Nama posisi / jabatan magang wajib diisi.',
            'judul_posisi.string' => 'Nama posisi / jabatan magang harus berupa teks.',
            'judul_posisi.max' => 'Nama posisi / jabatan magang maksimal 255 karakter.',
            'required_major.string' => 'Syarat jurusan harus berupa teks.',
            'required_major.max' => 'Syarat jurusan maksimal 255 karakter.',
            'kuota.required' => 'Kuota wajib diisi.',
            'kuota.integer' => 'Kuota harus berupa angka bulat.',
            'kuota.min' => 'Kuota minimal adalah 1.',
            'batas_daftar.date' => 'Batas pendaftaran harus berupa tanggal yang valid.',
            'batas_daftar.after_or_equal' => 'Batas pendaftaran tidak boleh sebelum tanggal hari ini.',
            'status.required' => 'Status lowongan wajib diisi.',
            'status.in' => 'Status lowongan hanya boleh buka atau tutup.',
        ];
    }
}
