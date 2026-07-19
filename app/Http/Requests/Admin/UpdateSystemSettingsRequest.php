<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSystemSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPortalRole('admin_kota')
            && $this->user()?->hasPortalPermission('manage-settings');
    }

    public function rules(): array
    {
        return [
            'app_name' => ['nullable', 'string', 'max:120'],
            'announcement' => ['nullable', 'string', 'max:5000'],
            'pejabat_name' => ['nullable', 'string', 'max:255'],
            'pejabat_nip' => ['nullable', 'string', 'max:50'],
            'pejabat_jabatan' => ['nullable', 'string', 'max:255'],
            'ttd_image' => ['nullable', 'image', 'mimetypes:image/jpeg,image/png,image/webp', 'max:2048', 'dimensions:max_width=4096,max_height=4096'],
        ];
    }
}
