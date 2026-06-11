<?php

namespace Database\Factories;

use App\Enums\StartupStage;
use App\Models\StartupProfile;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StartupProfileFactory extends Factory
{
    protected $model = StartupProfile::class;

    public function definition(): array
    {
        $name = fake()->company();
        return [
            'company_name' => $name,
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(1, 9999),
            'tagline' => fake()->catchPhrase(),
            'description' => fake()->paragraph(3),
            'website' => fake()->url(),
            'country' => fake()->countryCode(),
            'city' => fake()->city(),
            'sector' => fake()->randomElement(['fintech', 'agritech', 'healthtech', 'edtech', 'cleantech']),
            'stage' => fake()->randomElement(StartupStage::cases()),
            'founded_year' => fake()->year(),
            'team_size' => fake()->numberBetween(1, 50),
        ];
    }
}
