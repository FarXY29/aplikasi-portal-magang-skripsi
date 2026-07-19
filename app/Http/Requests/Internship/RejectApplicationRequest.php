<?php

namespace App\Http\Requests\Internship;

use Illuminate\Foundation\Http\FormRequest;

class RejectApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPortalRole('admin_instansi')
            && $this->user()?->hasPortalPermission('reject-lamaran');
    }

    public function rules(): array
    {
        return ['alasan' => ['required', 'string', 'max:1000']];
    }
}
