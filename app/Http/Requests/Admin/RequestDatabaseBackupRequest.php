<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RequestDatabaseBackupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPortalRole('admin_kota')
            && $this->user()?->hasPortalPermission('manage-settings');
    }

    public function rules(): array
    {
        return [
            'password' => ['required', 'current_password'],
        ];
    }
}
