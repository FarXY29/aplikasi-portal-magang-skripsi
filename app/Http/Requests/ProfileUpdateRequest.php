<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        // 1. Define Basic Rules
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'username' => ['nullable', 'string', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'photo' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'signature' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ];

        // 2. Add Logic for Peserta
        if ($this->user()->hasPortalRole('peserta') || $this->user()->role === 'peserta') {
            $rules['nik'] = ['nullable', 'string', 'max:50'];
            $rules['asal_instansi'] = ['nullable', 'string', 'max:255'];
            $rules['major_id'] = ['nullable', 'exists:majors,id'];
            $rules['major'] = ['nullable', 'string', 'max:255'];
            $rules['pembimbing_sekolah_id'] = [
                'nullable',
                Rule::exists(User::class, 'id')->where('role', 'pembimbing'),
            ];
        }

        // 3. Add Logic for Pembimbing Sekolah, Pembimbing Lapangan, & Admin Instansi
        if ($this->user()->hasPortalRole(['pembimbing', 'pembimbing_lapangan', 'admin_instansi']) || in_array($this->user()->role, ['pembimbing', 'pembimbing_lapangan', 'dinas', 'admin_instansi'], true)) {
            $rules['nik'] = ['nullable', 'string', 'max:50']; // NIP / NIDN / NIK
            $rules['asal_instansi'] = ['nullable', 'string', 'max:255'];
        }

        // 4. Return All Rules
        return $rules; 
    }
}
