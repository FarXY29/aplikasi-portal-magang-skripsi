<?php

namespace App\Policies;

use App\Models\InternshipPosition;
use App\Models\User;

class InternshipPositionPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->role === 'admin_kota' ? true : null;
    }

    public function manage(User $user, InternshipPosition $position): bool
    {
        return $user->role === 'admin_instansi'
            && $user->instansi_id === $position->instansi_id;
    }
}
