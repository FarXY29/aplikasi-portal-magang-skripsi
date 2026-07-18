<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ApplicationPolicy
{
    /**
     * Berikan akses penuh kepada Super Admin Kota untuk semua aksi.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->role === 'admin_kota') {
            return true;
        }

        return null;
    }

    /**
     * Tentukan apakah pengguna dapat melihat detail lamaran/peserta magang ini.
     */
    public function view(User $user, Application $application): bool
    {
        if ($user->role === 'peserta') {
            return $user->id === $application->user_id;
        }

        if ($user->role === 'pembimbing_lapangan') {
            return $user->id === $application->pembimbing_lapangan_id ||
                ($application->position && $user->instansi_id === $application->position->instansi_id);
        }

        if ($user->role === 'admin_instansi') {
            return $application->position && $user->instansi_id === $application->position->instansi_id;
        }

        if ($user->role === 'pembimbing') {
            return $application->user && $user->id === $application->user->pembimbing_sekolah_id;
        }

        return false;
    }

    /**
     * Tentukan apakah pembimbing lapangan diizinkan melakukan penilaian akhir (grading) mahasiswa magang ini.
     */
    public function grade(User $user, Application $application): bool
    {
        return $user->role === 'pembimbing_lapangan' && $user->id === $application->pembimbing_lapangan_id;
    }

    /**
     * Tentukan apakah admin instansi diizinkan mengelola peserta aktif (assign mentor, keluarkan, selesaikan).
     */
    public function manageActiveIntern(User $user, Application $application): bool
    {
        return $user->role === 'admin_instansi' &&
            $application->position &&
            $user->instansi_id === $application->position->instansi_id;
    }

    /**
     * Tentukan apakah pengguna dapat memvalidasi logbook atau absensi peserta ini.
     */
    public function validateRecords(User $user, Application $application): bool
    {
        if ($user->role === 'pembimbing_lapangan') {
            return $user->id === $application->pembimbing_lapangan_id;
        }

        if ($user->role === 'admin_instansi') {
            return $application->position && $user->instansi_id === $application->position->instansi_id;
        }

        return false;
    }
}
