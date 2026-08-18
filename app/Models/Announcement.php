<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'type',
        'target_audience',
        'banner_image',
        'is_published',
        'published_at',
        'expires_at',
        'send_email_broadcast',
        'created_by',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'send_email_broadcast' => 'boolean',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $baseSlug = Str::slug($model->title);
                $slug = $baseSlug;
                $count = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $count++;
                }
                $model->slug = $slug;
            }

            if (empty($model->published_at) && $model->is_published) {
                $model->published_at = now();
            }
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function broadcastLogs(): HasMany
    {
        return $this->hasMany(BroadcastLog::class);
    }

    /** Scope pengumuman yang sedang aktif tayang */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>=', now());
            });
    }

    /** Scope audiens target (all atau role pengguna) */
    public function scopeForAudience($query, ?string $role)
    {
        if (empty($role) || $role === 'admin_kota') {
            return $query;
        }

        // Peta kelompok peran (misal pembimbing lapangan atau pembimbing sekolah masuk ke grup 'pembimbing')
        $targetAudienceRoles = ['all', $role];
        if (str_starts_with($role, 'pembimbing')) {
            $targetAudienceRoles[] = 'pembimbing';
        }

        return $query->whereIn('target_audience', $targetAudienceRoles);
    }

    public function scopeRecent($query)
    {
        return $query->orderByDesc('created_at');
    }

    public function getTypeBadgeClassAttribute(): string
    {
        return match ($this->type) {
            'urgent' => 'bg-rose-500/10 text-rose-600 dark:text-rose-400 border-rose-500/30',
            'warning' => 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-500/30',
            'event' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/30',
            default => 'bg-teal-500/10 text-teal-600 dark:text-teal-400 border-teal-500/30',
        };
    }

    public function getTypeIconAttribute(): string
    {
        return match ($this->type) {
            'urgent' => 'fas fa-exclamation-circle',
            'warning' => 'fas fa-exclamation-triangle',
            'event' => 'fas fa-calendar-check',
            default => 'fas fa-bullhorn',
        };
    }

    public function getTargetLabelAttribute(): string
    {
        return match ($this->target_audience) {
            'peserta' => 'Semua Peserta Magang',
            'admin_instansi' => 'Admin Instansi / OPD',
            'pembimbing' => 'Dosen / Pembimbing Lapangan',
            default => 'Seluruh Pengguna Portal',
        };
    }
}
