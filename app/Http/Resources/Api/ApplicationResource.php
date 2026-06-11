<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status->value,
            'status_label' => $this->status->label(),
            'motivation' => $this->motivation,
            'pitch_deck_url' => $this->pitch_deck_url,
            'reviewer_notes' => $this->reviewer_notes,
            'reviewed_at' => $this->reviewed_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
            'startup' => $this->whenLoaded('startup', fn() => new UserResource($this->startup)),
            'incubator' => $this->whenLoaded('incubator', fn() => new UserResource($this->incubator)),
        ];
    }
}
