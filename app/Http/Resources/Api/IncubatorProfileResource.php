<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IncubatorProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'organization_name' => $this->organization_name,
            'slug' => $this->slug,
            'description' => $this->description,
            'website' => $this->website,
            'country' => $this->country,
            'city' => $this->city,
            'focus_sectors' => $this->focus_sectors,
            'supported_stages' => $this->supported_stages,
            'capacity' => $this->capacity,
            'logo_url' => $this->logo_url,
            'is_verified' => $this->is_verified,
            'founded_year' => $this->founded_year,
            'applications_count' => $this->whenCounted('applicationsAsIncubator'),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
