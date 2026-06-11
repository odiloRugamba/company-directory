<?php

namespace Database\Factories;

use App\Models\IncubatorProfile;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class IncubatorProfileFactory extends Factory
{
    protected $model = IncubatorProfile::class;

    public function definition(): array
    {
        $name = fake()->company() . ' Hub';
        return [
            'organization_name' => $name,
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(1, 9999),
            'description' => fake()->paragraph(3),
            'website' => fake()->url(),
            'country' => fake()->countryCode(),
            'city' => fake()->city(),
            'focus_sectors' => fake()->randomElements(['fintech', 'agritech', 'healthtech', 'edtech', 'cleantech'], 2),
            'supported_stages' => fake()->randomElements(['idea', 'mvp', 'early_traction', 'growth'], 2),
            'capacity' => fake()->numberBetween(10, 50),
            'is_verified' => fake()->boolean(),
            'founded_year' => fake()->year(),
        ];
    }
}
