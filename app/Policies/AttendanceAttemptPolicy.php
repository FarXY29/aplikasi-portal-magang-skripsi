<?php

namespace App\Policies;

use App\Models\AttendanceAttempt;
use App\Models\User;

class AttendanceAttemptPolicy
{
    /**
     * Berikan akses penuh kepada Super Admin Kota untuk semua aksi telemetry/attempt.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasPortalRole('admin_kota')) {
            return true;
        }

        return null;
    }

    /**
     * Tentukan apakah pengguna diizinkan melihat detail fraud/attendance attempt ini.
     */
    public function view(User $user, AttendanceAttempt $attempt): bool
    {
        if ($user->hasPortalRole('admin_instansi')) {
            return (int) $user->instansi_id === (int) $attempt->instance_id;
        }

        return false;
    }
}
