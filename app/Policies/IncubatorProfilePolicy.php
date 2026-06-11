<?php

namespace App\Policies;

use App\Models\IncubatorProfile;
use App\Models\User;

class IncubatorProfilePolicy
{
    public function update(User $user, IncubatorProfile $profile): bool
    {
        return $user->id === $profile->user_id;
    }
}
