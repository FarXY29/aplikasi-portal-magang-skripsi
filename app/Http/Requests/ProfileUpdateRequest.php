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
            'photo' => ['nullable', 'image', 'max:2048'], // Add photo validation (max 2MB)
        ];

        // 2. Add Logic for Peserta
        if ($this->user()->role === 'peserta') {
            $rules['nik'] = ['nullable', 'string', 'max:20'];
            $rules['asal_instansi'] = ['nullable', 'string', 'max:255'];
            $rules['major'] = ['nullable', 'string', 'max:255'];
            $rules['pembimbing_sekolah_id'] = [
                'nullable',
                Rule::exists(User::class, 'id')->where('role', 'pembimbing'),
            ];
        }

        // 3. Add Logic for Pembimbing Sekolah
        if ($this->user()->role === 'pembimbing') {
            $rules['nik'] = ['nullable', 'string', 'max:20']; // NIP / NIDN
            $rules['asal_instansi'] = ['nullable', 'string', 'max:255'];
        }

        // 4. Return All Rules
        return $rules; 
    }
}
