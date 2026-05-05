<?php

namespace App\Policies;

use App\Models\ServiceRequest;
use App\Models\User;

class ServiceRequestPolicy
{
    public function view(User $user, ServiceRequest $request): bool
    {
        return $user->id === $request->user_id || $user->isAdmin();
    }

    public function delete(User $user, ServiceRequest $request): bool
    {
        return $user->id === $request->user_id || $user->isAdmin();
    }
}