<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instansi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_dinas',
        'kode_unit_kerja',
        'alamat',
        'contact_whatsapp',
        'nama_pejabat',
        'nip_pejabat',
        'jabatan_pejabat',
        'max_total_quota',
        'jam_mulai_masuk', 
        'jam_mulai_pulang', 
        'latitude',
        'longitude',
        'radius_absen',
        'qr_absensi_enabled',
        'kiosk_token',
        'ttd_kepala', // Kolom Tanda Tangan Kepala Dinas
    ];

    protected $casts = [
        'qr_absensi_enabled' => 'boolean',
        'radius_absen' => 'integer',
    ];

    /**
     * Pastikan instansi memiliki kiosk_token rahasia untuk display Kiosk publik/lobi.
     */
    public function ensureKioskToken(): string
    {
        if (empty($this->kiosk_token)) {
            $this->kiosk_token = \Illuminate\Support\Str::random(32);
            $this->save();
        }

        return $this->kiosk_token;
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($instansi) {
            $instansi->users()->get()->each->delete();
            $instansi->positions()->get()->each->delete();
        });
    }

    // Relasi: Satu INSTANSI punya banyak Posisi Magang
    public function positions()
    {
        return $this->hasMany(InternshipPosition::class);
    }
    
    // Relasi: Satu INSTANSI punya banyak User (Admin Dinas/Pembimbing Lapangan)
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function applications()
    {
        // Menghubungkan INSTANSI langsung ke Application melewati InternshipPosition
        return $this->hasManyThrough(
            \App\Models\Application::class,
            \App\Models\InternshipPosition::class,
            'instansi_id',                // Foreign key di tabel internship_positions
            'internship_position_id', // Foreign key di tabel applications
            'id',                     // Local key di tabel instansis
            'id'                      // Local key di tabel internship_positions
        );
    }
}