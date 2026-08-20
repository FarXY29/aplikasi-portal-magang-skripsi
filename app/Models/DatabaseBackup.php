<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatabaseBackup extends Model
{
    use HasFactory;

    protected $fillable = [
        'requested_by',
        'filename',
        'stored_path',
        'status',
        'error_message',
        'completed_at',
        'expires_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function isDownloadable(): bool
    {
        return $this->status === 'completed'
            && ! $this->isExpired()
            && ! empty($this->stored_path)
            && \Illuminate\Support\Facades\Storage::disk('private')->exists($this->stored_path);
    }

    public function getFileSizeAttribute(): ?string
    {
        if ($this->stored_path && \Illuminate\Support\Facades\Storage::disk('private')->exists($this->stored_path)) {
            $bytes = \Illuminate\Support\Facades\Storage::disk('private')->size($this->stored_path);
            if ($bytes < 1024) {
                return $bytes . ' B';
            } elseif ($bytes < 1048576) {
                return round($bytes / 1024, 1) . ' KB';
            } else {
                return round($bytes / 1048576, 2) . ' MB';
            }
        }

        return null;
    }
}
