<?php

namespace App\Http\Requests\Internship;

use Illuminate\Foundation\Http\FormRequest;

class ValidateDailyLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPortalRole(['admin_instansi', 'pembimbing_lapangan']);
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:disetujui,ditolak,revisi'],
            'komentar' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
