<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StartupProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'company_name' => $this->company_name,
            'slug' => $this->slug,
            'tagline' => $this->tagline,
            'description' => $this->description,
            'website' => $this->website,
            'country' => $this->country,
            'city' => $this->city,
            'sector' => $this->sector,
            'stage' => $this->stage->value,
            'stage_label' => $this->stage->label(),
            'founded_year' => $this->founded_year,
            'team_size' => $this->team_size,
            'logo_url' => $this->logo_url,
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
