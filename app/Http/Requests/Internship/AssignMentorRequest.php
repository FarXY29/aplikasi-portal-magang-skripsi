<?php

namespace App\Http\Requests\Internship;

use Illuminate\Foundation\Http\FormRequest;

class AssignMentorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPortalRole('admin_instansi')
            && $this->user()?->hasPortalPermission('approve-lamaran');
    }

    public function rules(): array
    {
        return ['pembimbing_lapangan_id' => ['required', 'integer', 'exists:users,id']];
    }
}
