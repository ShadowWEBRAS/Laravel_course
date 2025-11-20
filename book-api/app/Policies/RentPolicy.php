<?php

namespace App\Policies;

use App\Models\Rent;
use App\Models\User;

class RentPolicy
{
    public function viewAny(User $user): bool
    {
        return !$user->isBlocked();
    }

    public function view(User $user, Rent $rent): bool
    {
        return ($user->isAdmin() || $user->id === $rent->user_id) && !$user->isBlocked();
    }

    public function create(User $user): bool
    {
        return $user->isReader() && !$user->isBlocked();
    }

    public function update(User $user, Rent $rent): bool
    {
        return $user->isAdmin() && !$user->isBlocked();
    }

    public function delete(User $user, Rent $rent): bool
    {
        return ($user->isAdmin() || $user->id === $rent->user_id) && !$user->isBlocked();
    }
}
