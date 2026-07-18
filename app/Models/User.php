<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

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
        'university_id',
        'school_id',
        'nik',
        'phone',
        'asal_instansi', 
        'major',
        'nama_pembimbing_sekolah',
        'pembimbing_sekolah_id', // TAMBAHAN: Relasi langsung ke akun pembimbing
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

    // Relasi ke Universitas (Master Data PT)
    public function university() {
        return $this->belongsTo(University::class);
    }

    // Relasi ke Sekolah (Master Data Sekolah Menengah)
    public function school() {
        return $this->belongsTo(School::class);
    }

    // --- TAMBAHAN PENTING: RELASI KE LAMARAN (APPLICATIONS) ---
    // Diperlukan agar Super Admin bisa melihat logbook peserta
    public function applications() {
        return $this->hasMany(Application::class);
    }

    public function bimbingan() {
        return $this->hasMany(Application::class, 'pembimbing_lapangan_id');
    }

    // Relasi peserta ke pembimbing sekolahnya
    public function pembimbing_sekolah() {
        return $this->belongsTo(User::class, 'pembimbing_sekolah_id');
    }

    // Relasi pembimbing sekolah ke mahasiswanya
    public function mahasiswa_bimbingan() {
        return $this->hasMany(User::class, 'pembimbing_sekolah_id');
    }

    /**
     * Tentukan apakah email pengguna telah diverifikasi.
     * Hanya role 'peserta' dan 'pembimbing' (pembimbing sekolah/akademik) yang wajib verifikasi email.
     * Role lain (admin_kota, admin_instansi, pembimbing_lapangan) otomatis dianggap sudah terverifikasi.
     */
    public function hasVerifiedEmail()
    {
        if (! in_array($this->role, ['peserta', 'pembimbing'])) {
            return true;
        }

        return ! is_null($this->email_verified_at);
    }

    /**
     * Kirim notifikasi verifikasi email hanya jika role membutuhkan verifikasi.
     */
    public function sendEmailVerificationNotification()
    {
        if (in_array($this->role, ['peserta', 'pembimbing'])) {
            $this->notify(new \Illuminate\Auth\Notifications\VerifyEmail);
        }
    }
}