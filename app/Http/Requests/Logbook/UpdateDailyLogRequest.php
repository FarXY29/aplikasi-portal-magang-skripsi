<?php

namespace App\Http\Requests\Logbook;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDailyLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPortalRole('peserta')
            && $this->user()?->hasPortalPermission('create-logbook');
    }

    public function rules(): array
    {
        return [
            'kegiatan' => ['required', 'string', 'max:2000'],
            'foto' => ['nullable', 'image', 'mimetypes:image/jpeg,image/png,image/webp', 'max:5120', 'dimensions:max_width=4096,max_height=4096'],
        ];
    }
}
