<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    public const PORTAL_ROLES = [
        'admin_kota',
        'admin_instansi',
        'pembimbing_lapangan',
        'pembimbing',
        'peserta',
    ];

    private const LEGACY_PERMISSIONS = [
        'admin_instansi' => [
            'create-lowongan', 'edit-lowongan', 'delete-lowongan', 'view-lowongan',
            'verify-lamaran', 'shortlist-lamaran', 'approve-lamaran', 'reject-lamaran',
            'verify-attendance', 'batch-approve-logbook', 'view-grading',
            'generate-certificate', 'verify-certificate', 'view-executive-report', 'export-reports',
        ],
        'pembimbing_lapangan' => [
            'view-lowongan', 'verify-attendance', 'batch-approve-logbook',
            'input-grading', 'view-grading', 'verify-certificate',
        ],
        'pembimbing' => ['view-lowongan', 'view-grading', 'verify-certificate'],
        'peserta' => [
            'view-lowongan', 'apply-magang', 'cancel-lamaran', 'checkin-attendance',
            'checkout-attendance', 'create-logbook', 'view-grading', 'verify-certificate',
        ],
    ];

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
     * Role pada kolom legacy dipertahankan untuk redirect/dashboard hingga seluruh
     * data lama selesai dimigrasikan. Akses baru selalu mencoba role Spatie dahulu.
     */
    public function hasPortalRole(string|array $roles): bool
    {
        foreach ((array) $roles as $role) {
            if ($this->role === $role || $this->hasRole($role)) {
                return true;
            }
        }

        return false;
    }

    /** Sinkronkan tepat satu role utama ke Spatie tanpa menghapus data user lama. */
    public function syncPrimaryRole(): bool
    {
        if (! in_array($this->role, self::PORTAL_ROLES, true)) {
            return false;
        }

        try {
            if (! Role::query()->where('name', $this->role)->where('guard_name', 'web')->exists()) {
                return false;
            }

            $this->syncRoles([$this->role]);

            return true;
        } catch (\Throwable) {
            // Instalasi lama yang belum memiliki tabel/seed RBAC tetap dapat berjalan
            // sampai command backfill dijalankan.
            return false;
        }
    }

    /**
     * Permission Spatie berlaku penuh setelah user memiliki role Spatie.
     * Fallback hanya digunakan sementara untuk akun legacy yang belum dibackfill.
     */
    public function hasPortalPermission(string $permission): bool
    {
        if ($this->role === 'admin_kota') {
            return true;
        }

        try {
            if ($this->hasPermissionTo($permission)) {
                return true;
            }

            if ($this->roles()->exists()) {
                return false;
            }
        } catch (PermissionDoesNotExist) {
            return false;
        } catch (\Throwable) {
            // Fallback legacy di bawah dipakai bila tabel RBAC belum tersedia.
        }

        return in_array($permission, self::LEGACY_PERMISSIONS[$this->role] ?? [], true);
    }

    /**
     * Tentukan apakah email pengguna telah diverifikasi.
     * Hanya role 'peserta' dan 'pembimbing' (pembimbing sekolah/akademik) yang wajib verifikasi email.
     * Role lain (admin_kota, admin_instansi, pembimbing_lapangan) otomatis dianggap sudah terverifikasi.
     */
    public function hasVerifiedEmail()
    {
        if (! $this->hasPortalRole(['peserta', 'pembimbing'])) {
            return true;
        }

        return ! is_null($this->email_verified_at);
    }

    /**
     * Kirim notifikasi verifikasi email hanya jika role membutuhkan verifikasi.
     */
    public function sendEmailVerificationNotification()
    {
        if ($this->hasPortalRole(['peserta', 'pembimbing'])) {
            $this->notify(new \Illuminate\Auth\Notifications\VerifyEmail);
        }
    }

    public function fresh($with = [])
    {
        $fresh = parent::fresh($with);
        if ($fresh && $fresh->trashed()) {
            return null;
        }
        return $fresh;
    }
}
