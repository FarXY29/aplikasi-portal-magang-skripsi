<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipPosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'instansi_id',
        'judul_posisi',
        'required_major', // Syarat Jurusan
        'deskripsi',
        'kuota',
        'batas_daftar',
        'status',
    ];

    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }

    // --- TAMBAHAN PENTING (RELASI KE PELAMAR) ---
    public function applications()
    {
        // Satu lowongan bisa memiliki BANYAK pelamar (applications)
        return $this->hasMany(Application::class);
    }
}