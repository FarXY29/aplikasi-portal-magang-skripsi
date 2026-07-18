<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;

class AttendancePolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->role === 'admin_kota' ? true : null;
    }

    public function view(User $user, Attendance $attendance): bool
    {
        $application = $attendance->application;

        if (! $application) {
            return false;
        }

        if ($user->role === 'peserta') {
            return $user->id === $application->user_id;
        }

        if ($user->role === 'admin_instansi') {
            return $application->position && $user->instansi_id === $application->position->instansi_id;
        }

        if ($user->role === 'pembimbing_lapangan') {
            return $user->id === $application->pembimbing_lapangan_id;
        }

        if ($user->role === 'pembimbing') {
            return $application->user && $user->id === $application->user->pembimbing_sekolah_id;
        }

        return false;
    }

    public function validate(User $user, Attendance $attendance): bool
    {
        return $this->view($user, $attendance)
            && in_array($user->role, ['admin_instansi', 'pembimbing_lapangan'], true);
    }
}
