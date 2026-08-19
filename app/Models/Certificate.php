<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'status',
        'revoked_at',
        'revoked_reason',
        'revoked_by',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function revokedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revoked_by');
    }

    public function isRevoked(): bool
    {
        return $this->status === 'revoked';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
