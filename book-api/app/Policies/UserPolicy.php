<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() && !$user->isBlocked();
    }

    public function block(User $user, User $targetUser): bool
    {
        return $user->isAdmin() &&
            !$user->isBlocked() &&
            $targetUser->isReader() &&
            $user->id !== $targetUser->id;
    }
}
