<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationTimeline extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    public const EVENT_SUBMITTED = 'submitted';
    public const EVENT_VERIFIED = 'verified';
    public const EVENT_ACCEPTED = 'accepted';
    public const EVENT_REJECTED = 'rejected';
    public const EVENT_WAITING_LIST = 'waiting_list';
    public const EVENT_PROMOTED = 'promoted';
    public const EVENT_CANCELLED = 'cancelled';
    public const EVENT_STARTED = 'started';
    public const EVENT_COMPLETED = 'completed';
    public const EVENT_EXPELLED = 'expelled';
    public const EVENT_CERTIFICATE_ISSUED = 'certificate_issued';

    protected $fillable = [
        'application_id',
        'actor_id',
        'event',
        'old_status',
        'new_status',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function getEventLabel(): string
    {
        return match ($this->event) {
            self::EVENT_SUBMITTED => 'Lamaran Diajukan',
            self::EVENT_VERIFIED => 'Dokumen Diverifikasi',
            self::EVENT_ACCEPTED => 'Lamaran Diterima',
            self::EVENT_REJECTED => 'Lamaran Ditolak',
            self::EVENT_WAITING_LIST => 'Masuk Antrean (Waiting List)',
            self::EVENT_PROMOTED => 'Dipromosikan dari Antrean',
            self::EVENT_CANCELLED => 'Lamaran Dibatalkan',
            self::EVENT_STARTED => 'Magang Dimulai',
            self::EVENT_COMPLETED => 'Magang Selesai (Lulus)',
            self::EVENT_EXPELLED => 'Peserta Dikeluarkan',
            self::EVENT_CERTIFICATE_ISSUED => 'Sertifikat Diterbitkan',
            default => ucfirst(str_replace('_', ' ', $this->event)),
        };
    }
}
