<?php

namespace App\Policies;

use App\Models\Instansi;
use App\Models\User;

class InstansiPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->role === 'admin_kota' ? true : null;
    }

    public function view(User $user, Instansi $instansi): bool
    {
        return $user->instansi_id === $instansi->id;
    }
}
