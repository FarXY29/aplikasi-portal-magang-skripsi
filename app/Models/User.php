<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'google_id',
        'instansi_id',
        'nik',
        'phone',
        'asal_instansi', 
        'major',
        'nama_pembimbing_sekolah',
        'signature', // Kolom Tanda Tangan Pembimbing Lapangan
        'photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi ke INSTANSI (Untuk Admin Dinas / Pembimbing Lapangan)
    public function instansi() {
        return $this->belongsTo(Instansi::class);
    }

    // --- TAMBAHAN PENTING: RELASI KE LAMARAN (APPLICATIONS) ---
    // Diperlukan agar Super Admin bisa melihat logbook peserta
    public function applications() {
        return $this->hasMany(Application::class);
    }

    public function bimbingan() {
        return $this->hasMany(Application::class, 'pembimbing_lapangan_id');
    }
}