<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'internship_position_id',
        'cv_path',
        'surat_pengantar_path',
        'status',
        'is_automatic_placement',
        'tanggal_mulai',
        'tanggal_selesai',
        'pembimbing_lapangan_id',
        'nilai_angka',
        'predikat',
        'nilai_kerajinan',
        'nilai_disiplin',
        'nilai_adaptasi',
        'nilai_kreatifitas',
        'nilai_skill_pengetahuan',
        'nilai_rata_rata',
        'catatan_pembimbing_lapangan',
        'saran_peserta',
        'saran_pembimbing',
        'nomor_sertifikat',
        'token_verifikasi',
    ];

    // Event Boot untuk generate nomor otomatis saat status diubah jadi selesai
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            if ($model->isDirty('status') && $model->status === 'selesai' && empty($model->nomor_sertifikat)) {
                
                // Format: MG-{TAHUN}-{URUTAN} (e.g. MG-2026-00001)
                $year = date('Y');
                $num = 1;
                do {
                    $number = 'MG-' . $year . '-' . str_pad($num, 5, '0', STR_PAD_LEFT);
                    $num++;
                } while (static::where('nomor_sertifikat', $number)->exists());
                
                $model->nomor_sertifikat = $number;
                $model->token_verifikasi = Str::random(40); // Token acak 40 karakter
            }
        });

        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('expired_internships_checked');
        });

        static::updated(function ($model) {
            // Jika aplikasi ini baru saja diselesaikan
            if ($model->wasChanged('status') && $model->status === 'selesai') {
                // Cari peserta dengan status 'menunggu' untuk posisi yang sama
                $nextWaiting = self::where('internship_position_id', $model->internship_position_id)
                                   ->where('status', 'menunggu')
                                   ->orderBy('created_at', 'asc')
                                   ->first();
                
                if ($nextWaiting) {
                    // Hitung durasi magang yang diajukan sebelumnya
                    $startDate = \Carbon\Carbon::parse($nextWaiting->tanggal_mulai);
                    $endDate = \Carbon\Carbon::parse($nextWaiting->tanggal_selesai);
                    $durationDays = $startDate->diffInDays($endDate);

                    // Set tanggal mulai ke besoknya setelah tanggal selesai dari peserta yang baru selesai ini
                    $newStartDate = \Carbon\Carbon::parse($model->tanggal_selesai)->addDay();
                    
                    // Jika tanggal selesai di masa lalu, maka mulai dari besok hari ini agar realistis
                    if ($newStartDate->isPast()) {
                        $newStartDate = \Carbon\Carbon::tomorrow();
                    }

                    $newEndDate = $newStartDate->copy()->addDays($durationDays);

                    // Update peserta yang menunggu
                    $nextWaiting->update([
                        'status' => 'diterima',
                        'tanggal_mulai' => $newStartDate->format('Y-m-d'),
                        'tanggal_selesai' => $newEndDate->format('Y-m-d'),
                    ]);
                }
            }
        });
    }


    public function user() { return $this->belongsTo(User::class); }
    public function position() { return $this->belongsTo(InternshipPosition::class, 'internship_position_id'); }
    public function pembimbing_lapangan() { return $this->belongsTo(User::class, 'pembimbing_lapangan_id'); }
    public function logs() { return $this->hasMany(DailyLog::class); }
    public function attendances() { return $this->hasMany(Attendance::class); }

    // Accessor untuk status yang memperhitungkan tanggal mulai
    public function getDisplayStatusAttribute()
    {
        if ($this->status === 'diterima') {
            if (\Carbon\Carbon::now()->startOfDay()->lt(\Carbon\Carbon::parse($this->tanggal_mulai)->startOfDay())) {
                return 'belum mulai';
            }
        }
        return $this->status;
    }
}