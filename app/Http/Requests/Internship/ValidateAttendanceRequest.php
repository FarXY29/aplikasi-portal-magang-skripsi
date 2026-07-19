<?php

namespace App\Http\Requests\Internship;

use Illuminate\Foundation\Http\FormRequest;

class ValidateAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPortalRole(['admin_instansi', 'pembimbing_lapangan']);
    }

    public function rules(): array
    {
        return [
            'status_validasi' => ['required', 'in:approved,rejected'],
            'pembimbing_lapangan_note' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
