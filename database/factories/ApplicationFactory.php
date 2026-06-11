<?php

namespace Database\Factories;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationFactory extends Factory
{
    protected $model = Application::class;

    public function definition(): array
    {
        return [
            'status' => ApplicationStatus::Pending,
            'motivation' => fake()->paragraphs(2, true),
            'pitch_deck_url' => fake()->optional()->url(),
            'reviewer_notes' => null,
            'reviewed_at' => null,
            'reviewed_by' => null,
        ];
    }
}
