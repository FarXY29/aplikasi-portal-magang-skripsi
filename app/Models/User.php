<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    public const PORTAL_ROLES = [
        'admin_kota',
        'admin_instansi',
        'pembimbing_lapangan',
        'pembimbing',
        'peserta',
    ];

    public const EMAIL_VERIFICATION_EXEMPT_ROLES = [
        'admin_kota',
        'admin_instansi',
        'pembimbing_lapangan',
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
        'instansi_id',
        'university_id',
        'school_id',
        'nik',
        'phone',
        'asal_instansi', 
        'major',
        'major_id',
        'nama_pembimbing_sekolah',
        'pembimbing_sekolah_id',
        'signature',
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

    // Relasi ke Master Data Jurusan / Program Studi
    public function majorDetail() {
        return $this->belongsTo(Major::class, 'major_id');
    }

    // Relasi ke LAMARAN (APPLICATIONS)
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

    /** Accessor agar panggilan ->nim, ->npm, atau ->nomor_induk tetap mengembalikan nomor induk/nik peserta/pembimbing */
    public function getNimAttribute(): ?string
    {
        return $this->attributes['nik'] ?? null;
    }

    public function getNpmAttribute(): ?string
    {
        return $this->attributes['nik'] ?? null;
    }

    public function getNomorIndukAttribute(): ?string
    {
        return $this->attributes['nik'] ?? null;
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($user) {
            if ($user->isEmailVerificationExempt() && empty($user->email_verified_at)) {
                $user->email_verified_at = now();
            }
        });

        static::deleting(function ($user) {
            Application::where('pembimbing_lapangan_id', $user->id)->update(['pembimbing_lapangan_id' => null]);
            User::where('pembimbing_sekolah_id', $user->id)->update(['pembimbing_sekolah_id' => null]);

            $user->applications()->get()->each(function ($application) {
                $application->delete();
            });
        });
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

    /**
     * Ambil satu role portal yang menjadi sumber tampilan utama.
     * Role Spatie diprioritaskan, lalu kolom legacy dipakai sebagai fallback.
     */
    public function getPrimaryPortalRole(): ?string
    {
        $roleNames = $this->relationLoaded('roles')
            ? $this->roles->pluck('name')
            : $this->roles()->where('guard_name', 'web')->pluck('name');

        foreach (self::PORTAL_ROLES as $role) {
            if ($roleNames->contains($role)) {
                return $role;
            }
        }

        return in_array($this->role, self::PORTAL_ROLES, true) ? $this->role : null;
    }

    /** Query akun berdasarkan role Spatie dengan fallback kolom role legacy. */
    public function scopePortalRole($query, string $role)
    {
        return $query->where(function ($roleQuery) use ($role) {
            $roleQuery->where($this->qualifyColumn('role'), $role)
                ->orWhereHas('roles', function ($spatieRoleQuery) use ($role) {
                    $spatieRoleQuery->where('name', $role)
                        ->where('guard_name', 'web');
                });
        });
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
    /**
     * Sensor nomor NIK untuk proteksi data pribadi di tampilan publik.
     */
    public function getMaskedNikAttribute(): ?string
    {
        if (empty($this->nik)) {
            return null;
        }

        $nik = (string) $this->nik;
        $len = strlen($nik);

        if ($len <= 4) {
            return str_repeat('*', $len);
        }

        if ($len <= 8) {
            return substr($nik, 0, 2) . str_repeat('*', $len - 4) . substr($nik, -2);
        }

        return substr($nik, 0, 4) . str_repeat('*', max(4, $len - 8)) . substr($nik, -4);
    }

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
     * Tentukan apakah role pengguna dikecualikan dari kewajiban verifikasi email.
     * Super Admin (admin_kota), Admin Instansi (admin_instansi), dan Pembimbing Lapangan (pembimbing_lapangan)
     * tidak memerlukan verifikasi email.
     */
    public function isEmailVerificationExempt(): bool
    {
        return $this->hasPortalRole(self::EMAIL_VERIFICATION_EXEMPT_ROLES)
            || in_array($this->role, self::EMAIL_VERIFICATION_EXEMPT_ROLES, true);
    }

    /**
     * Tentukan apakah email pengguna telah diverifikasi.
     * Role internal (Super Admin, Admin Instansi, Pembimbing Lapangan) selalu dianggap terverifikasi.
     */
    public function hasVerifiedEmail(): bool
    {
        if ($this->isEmailVerificationExempt()) {
            return true;
        }

        return ! is_null($this->email_verified_at);
    }

    /**
     * Kirim notifikasi verifikasi email.
     * Tidak mengirim ke role yang dikecualikan dari verifikasi email.
     */
    public function sendEmailVerificationNotification()
    {
        if ($this->isEmailVerificationExempt()) {
            return;
        }

        try {
            $this->notify(new \Illuminate\Auth\Notifications\VerifyEmail);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Gagal mengirim email verifikasi: ' . $e->getMessage());
        }
    }

    /**
     * Cek apakah role menggunakan kolom instansi_id.
     */
    public static function usesInstansiId(string $role): bool
    {
        return in_array($role, ['admin_instansi', 'pembimbing_lapangan'], true);
    }

    /**
     * Cek apakah role menggunakan kolom asal_instansi.
     */
    public static function usesAsalInstansi(string $role): bool
    {
        return in_array($role, ['peserta', 'pembimbing'], true);
    }
}
