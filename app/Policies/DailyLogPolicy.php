<?php

namespace App\Policies;

use App\Models\DailyLog;
use App\Models\User;

class DailyLogPolicy
{
    /**
     * Berikan akses penuh kepada Super Admin Kota untuk semua aksi logbook.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->role === 'admin_kota') {
            return true;
        }

        return null;
    }

    /**
     * Tentukan apakah pengguna dapat melihat logbook harian ini.
     */
    public function view(User $user, DailyLog $dailyLog): bool
    {
        return $user->can('view', $dailyLog->application);
    }

    /**
     * Tentukan apakah pembimbing atau admin dinas dapat memvalidasi logbook ini.
     */
    public function validate(User $user, DailyLog $dailyLog): bool
    {
        return $user->can('validateRecords', $dailyLog->application);
    }

    /**
     * Tentukan apakah peserta dapat mengubah/menambahkan logbook ini.
     */
    public function update(User $user, DailyLog $dailyLog): bool
    {
        return $user->role === 'peserta' &&
            $dailyLog->application &&
            $user->id === $dailyLog->application->user_id &&
            $dailyLog->status_validasi === 'pending';
    }
}
