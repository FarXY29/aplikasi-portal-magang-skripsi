<?php

namespace App\Models;

use App\Enums\ApplicationStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'internship_position_id',
        'letter_number',
        'cv_path',
        'surat_pengantar_path',
        'status',
        'verified_by',
        'rejected_reason',
        'canceled_at',
        'is_automatic_placement',
        'tanggal_mulai',
        'tanggal_selesai',
        'pembimbing_lapangan_id',
        'nilai_angka',
        'predikat',
        'nilai_kerajinan',
        'nilai_disiplin',
        'nilai_kinerja',
        'nilai_adaptasi',
        'nilai_kreatifitas',
        'nilai_skill_pengetahuan',
        'nilai_rata_rata',
        'catatan_pembimbing_lapangan',
        'saran_peserta',
        'saran_pembimbing',
        'nomor_registrasi',
        'nomor_sertifikat',
        'token_verifikasi',
    ];

    protected $casts = [
        'status' => ApplicationStatus::class,
    ];

    // Event Boot
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->nomor_registrasi)) {
                $prefix = 'REG-' . now()->format('Ym') . '-';
                do {
                    $regNumber = $prefix . strtoupper(Str::random(5));
                } while (static::where('nomor_registrasi', $regNumber)->exists());
                $model->nomor_registrasi = $regNumber;
            }

            if (empty($model->token_verifikasi)) {
                $model->token_verifikasi = Str::random(32);
            }

            if (empty($model->cv_path)) {
                $model->cv_path = '-';
            }

            if (empty($model->surat_pengantar_path)) {
                $model->surat_pengantar_path = '-';
            }
        });

        static::saving(function ($model) {
            if (empty($model->token_verifikasi)) {
                $model->token_verifikasi = Str::random(32);
            }
        });

        static::deleting(function ($application) {
            $application->logs()->delete();
            $application->attendances()->delete();
            if ($application->certificate) {
                $application->certificate->delete();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function position()
    {
        return $this->belongsTo(InternshipPosition::class, 'internship_position_id');
    }

    public function pembimbing_lapangan()
    {
        return $this->belongsTo(User::class, 'pembimbing_lapangan_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function logs()
    {
        return $this->hasMany(DailyLog::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function certificate()
    {
        return $this->hasOne(Certificate::class);
    }

    // Accessor untuk status yang memperhitungkan tanggal mulai (mendukung Enum & String)
    public function getDisplayStatusAttribute()
    {
        $statusValue = $this->status instanceof ApplicationStatus ? $this->status->value : $this->status;
        if ($statusValue === 'diterima') {
            if (Carbon::now()->startOfDay()->lt(Carbon::parse($this->tanggal_mulai)->startOfDay())) {
                return 'belum mulai';
            }
        }

        return $statusValue;
    }

    public function getStatusValueAttribute(): string
    {
        return $this->status instanceof ApplicationStatus ? $this->status->value : (string) $this->status;
    }

    /**
     * Predikat (grade label) untuk rata-rata nilai akhir magang.
     * Satu-satunya sumber kebenaran untuk band predikat — dipakai oleh
     * PembimbingLapanganController (simpanNilai) dan view penilaian (JS).
     * Bands: A >= 90, B >= 80, C >= 70, D < 70.
     */
    public static function predikatFor(float $avg): string
    {
        return match (true) {
            $avg >= 90 => 'A (Sangat Baik)',
            $avg >= 80 => 'B (Baik)',
            $avg >= 70 => 'C (Cukup)',
            default    => 'D (Kurang)',
        };
    }
}
