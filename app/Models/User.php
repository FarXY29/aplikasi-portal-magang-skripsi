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

    // =============================================
    // MULTI-DEVICE SESSION MANAGEMENT
    // =============================================

    /**
     * Relasi ke sesi aktif user.
     */
    public function activeSessions()
    {
        return $this->hasMany(UserSession::class);
    }

    /**
     * Relasi ke log percobaan login yang diblokir.
     */
    public function loginAttempts()
    {
        return $this->hasMany(LoginAttempt::class);
    }

    /**
     * Bersihkan sesi yang sudah expired (tidak ada aktivitas > SESSION_LIFETIME menit).
     * @return int Jumlah sesi yang dihapus
     */
    public function cleanExpiredSessions(): int
    {
        $lifetime = config('session.lifetime', 120);

        return $this->activeSessions()
            ->where(function ($query) use ($lifetime) {
                $query->where('last_activity_at', '<', now()->subMinutes($lifetime))
                      ->orWhereNull('last_activity_at');
            })
            ->delete();
    }

    /**
     * Cek apakah user masih bisa menerima sesi baru (slot < 3).
     * Otomatis membersihkan sesi expired terlebih dahulu.
     */
    public function canAcceptNewSession(): bool
    {
        $this->cleanExpiredSessions();
        return $this->activeSessions()->count() < 3;
    }

    /**
     * Cek apakah session_id tertentu termasuk sesi aktif milik user ini.
     */
    public function hasActiveSession(string $sessionId): bool
    {
        return $this->activeSessions()->where('session_id', $sessionId)->exists();
    }

    /**
     * Hitung jumlah percobaan login yang diblokir dalam 24 jam terakhir.
     */
    public function recentBlockedAttempts(): int
    {
        return $this->loginAttempts()
            ->where('attempted_at', '>=', now()->subHours(24))
            ->count();
    }
}