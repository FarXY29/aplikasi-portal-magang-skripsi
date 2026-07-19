<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'peserta';
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:izin,sakit'],
            'description' => ['required', 'string', 'max:255'],
            'proof_file' => ['required', 'image', 'mimetypes:image/jpeg,image/png,image/webp', 'max:2048', 'dimensions:max_width=4096,max_height=4096'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Jenis pengajuan wajib dipilih.',
            'status.in' => 'Jenis pengajuan hanya boleh izin atau sakit.',
            'description.required' => 'Keterangan izin/sakit wajib diisi.',
            'description.max' => 'Keterangan maksimal 255 karakter.',
            'proof_file.required' => 'Bukti pendukung wajib diunggah.',
            'proof_file.image' => 'Bukti pendukung harus berupa gambar.',
            'proof_file.mimes' => 'Bukti pendukung harus berformat JPEG, PNG, atau JPG.',
            'proof_file.max' => 'Ukuran bukti pendukung maksimal 2 MB.',
        ];
    }
}
