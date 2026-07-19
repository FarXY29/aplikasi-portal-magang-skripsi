<?php

namespace App\Policies;

use App\Models\InternshipPosition;
use App\Models\User;

class InternshipPositionPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->hasPortalRole('admin_kota') ? true : null;
    }

    public function manage(User $user, InternshipPosition $position): bool
    {
        return $user->hasPortalRole('admin_instansi')
            && $user->hasPortalPermission('edit-lowongan')
            && $user->instansi_id === $position->instansi_id;
    }
}
