<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'nomor_sertifikat',
        'token_verifikasi',
        'qr_code_path',
        'signer_name',
        'signature_mock',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
