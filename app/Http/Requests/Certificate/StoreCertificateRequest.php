<?php

namespace App\Http\Requests\Certificate;

use Illuminate\Foundation\Http\FormRequest;

class StoreCertificateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin_instansi';
    }

    public function rules(): array
    {
        $applicationId = $this->route('applicationId');

        return [
            'nomor_sertifikat' => ['required', 'string', 'max:100', 'unique:applications,nomor_sertifikat,' . $applicationId],
            'tanggal_sertifikat' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'nomor_sertifikat.required' => 'Nomor sertifikat wajib diisi.',
            'nomor_sertifikat.unique' => 'Nomor sertifikat sudah digunakan.',
            'tanggal_sertifikat.required' => 'Tanggal sertifikat wajib diisi.',
            'tanggal_sertifikat.date' => 'Tanggal sertifikat tidak valid.',
        ];
    }
}
