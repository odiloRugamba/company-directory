<?php

namespace App\Policies;

use App\Models\StartupProfile;
use App\Models\User;

class StartupProfilePolicy
{
    public function update(User $user, StartupProfile $profile): bool
    {
        return $user->id === $profile->user_id;
    }
}
