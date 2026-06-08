<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyLog extends Model
{
    use HasFactory;

    // Mendaftarkan kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'application_id',
        'tanggal',
        'kegiatan',
        'bukti_foto_path',
        'status_validasi',
        'komentar_pembimbing_lapangan',
    ];

    // Relasi: Satu Logbook milik satu Lamaran (Application)
    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}