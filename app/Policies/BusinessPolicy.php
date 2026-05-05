<?php

namespace App\Policies;

use App\Models\Business;
use App\Models\User;

class BusinessPolicy
{
    public function create(User $user): bool
    {
        return $user->isProvider() && is_null($user->business);
    }

    public function update(User $user, Business $business): bool
    {
        return $user->id === $business->user_id || $user->isAdmin();
    }

    public function delete(User $user, Business $business): bool
    {
        return $user->id === $business->user_id || $user->isAdmin();
    }
}
