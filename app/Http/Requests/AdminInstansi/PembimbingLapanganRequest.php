<?php

namespace App\Http\Requests\AdminInstansi;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PembimbingLapanganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin_instansi';
    }

    public function rules(): array
    {
        $userId = $this->route('id');
        $isUpdate = $this->isMethod('put') || $this->isMethod('patch');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique(User::class, 'email')->ignore($userId),
            ],
            'nip' => ['nullable', 'string', 'max:20'],
            'password' => [$isUpdate ? 'nullable' : 'required', 'string', 'min:6'],
        ];
    }
}
