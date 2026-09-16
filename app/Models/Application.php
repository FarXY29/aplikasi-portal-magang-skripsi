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
            $application->timelines()->delete();
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

    public function timelines()
    {
        return $this->hasMany(ApplicationTimeline::class)->orderBy('created_at', 'asc')->orderBy('id', 'asc');
    }

    public function recordTimeline(
        string $event,
        ?string $oldStatus = null,
        ?string $newStatus = null,
        array $metadata = [],
        ?int $actorId = null
    ): ApplicationTimeline {
        return $this->timelines()->create([
            'actor_id' => $actorId ?? auth()->id(),
            'event' => $event,
            'old_status' => $oldStatus ?? ($this->status instanceof ApplicationStatus ? $this->status->value : (string) $this->status),
            'new_status' => $newStatus,
            'metadata' => $metadata ?: null,
        ]);
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
     * Label ramah pengguna untuk status (fallback ke ucfirst bila bukan enum).
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->status instanceof ApplicationStatus
            ? $this->status->label()
            : ucfirst((string) $this->status);
    }

    /**
     * Kelas Tailwind badge status (fallback netral bila bukan enum).
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return $this->status instanceof ApplicationStatus
            ? $this->status->badgeClass()
            : 'bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700';
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

    /**
     * Kolom nilai penilaian akhir yang valid (5 kriteria).
     * Nilai rata-rata = total 5 kriteria / 5.
     *
     * @var array<int, string>
     */
    public const CRITERIA_COLUMNS = [
        'nilai_kerajinan',
        'nilai_disiplin',
        'nilai_adaptasi',
        'nilai_kreatifitas',
        'nilai_skill_pengetahuan',
    ];

    /**
     * Persentase kehadiran (hadir / total absensi) dalam rentang 0-100.
     * Mengutamakan nilai persist (nilai_rata_rata) bila tersedia, jika tidak
     * dihitung dari 5 kolom kriteria. Menghindari dynamic-property yang rapuh.
     */
    public function getAttendanceRateAttribute(): float
    {
        $total = $this->attendances->count();
        if ($total === 0) {
            return 0.0;
        }

        $hadir = $this->attendances->where('status', 'hadir')->count();

        return round(($hadir / $total) * 100, 2);
    }

    /**
     * Persentase logbook yang telah disetujui pembimbing (0-100).
     */
    public function getLogRateAttribute(): float
    {
        $total = $this->logs->count();
        if ($total === 0) {
            return 0.0;
        }

        $disetujui = $this->logs->where('status_validasi', 'disetujui')->count();

        return round(($disetujui / $total) * 100, 2);
    }

    /**
     * Nilai akhir magang. Prioritas: nilai_rata_rata persisten, lalu rerata
     * 5 kolom kriteria (bila ada salah satu terisi), selain itu 0.
     */
    public function getAvgNilaiAttribute(): float
    {
        if ((float) $this->nilai_rata_rata > 0) {
            return (float) $this->nilai_rata_rata;
        }

        $criteria = array_map(
            fn ($column) => (float) ($this->{$column} ?? 0),
            self::CRITERIA_COLUMNS
        );

        if (array_sum($criteria) <= 0) {
            return 0.0;
        }

        return round(array_sum($criteria) / count(self::CRITERIA_COLUMNS), 2);
    }
}
