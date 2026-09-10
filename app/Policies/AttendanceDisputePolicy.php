<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\AttendanceDispute;
use App\Models\User;

class AttendanceDisputePolicy
{
    /**
     * Super-admin (admin_kota) has full administrative access except submitting dispute on behalf of intern.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($ability === 'submit') {
            return null;
        }

        return $user->hasPortalRole('admin_kota') ? true : null;
    }

    /**
     * Determine whether the user can submit a dispute for the given attendance.
     */
    public function submit(User $user, Attendance $attendance): bool
    {
        return $user->hasPortalRole('peserta') && (int) $attendance->application?->user_id === (int) $user->id;
    }

    /**
     * Determine whether the user can view the dispute.
     */
    public function view(User $user, AttendanceDispute $dispute): bool
    {
        if ((int) $dispute->user_id === (int) $user->id) {
            return true;
        }

        return $this->review($user, $dispute);
    }

    /**
     * Determine whether the user can review (approve/reject) the dispute.
     * Authorized: Admin Kota (global), Admin Instansi (same instansi), Pembimbing Lapangan (assigned).
     */
    public function review(User $user, AttendanceDispute $dispute): bool
    {
        if ($user->hasPortalRole('admin_kota')) {
            return true;
        }

        $app = $dispute->attendance?->application;
        if (!$app) {
            return false;
        }

        if ($user->hasPortalRole('admin_instansi')) {
            $instansiId = $app->position?->instansi_id;
            return $instansiId !== null && (int) $user->instansi_id === (int) $instansiId;
        }

        if ($user->hasPortalRole('pembimbing_lapangan')) {
            return (int) $app->pembimbing_lapangan_id === (int) $user->id;
        }

        return false;
    }
}
