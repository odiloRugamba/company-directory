<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Application;
use App\Models\User;

class ApplicationPolicy
{
    public function create(User $user): bool
    {
        return $user->role === Role::Startup;
    }

    public function updateStatus(User $user, Application $application): bool
    {
        return $user->role === Role::Incubator && $application->incubator_id === $user->id;
    }

    public function view(User $user, Application $application): bool
    {
        return $user->role === Role::Admin
            || $application->startup_id === $user->id
            || $application->incubator_id === $user->id;
    }
}
