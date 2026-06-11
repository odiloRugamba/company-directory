<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role->value,
            'country' => $this->country,
            'avatar_url' => $this->avatar_url,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at->toIso8601String(),
            'profile' => $this->when(
                $this->relationLoaded('incubatorProfile') || $this->relationLoaded('startupProfile'),
                function () {
                    if ($this->incubatorProfile) {
                        return new IncubatorProfileResource($this->incubatorProfile);
                    }
                    if ($this->startupProfile) {
                        return new StartupProfileResource($this->startupProfile);
                    }
                    return null;
                }
            ),
        ];
    }
}
