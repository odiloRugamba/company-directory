<?php

namespace Database\Factories;

use App\Models\Sector;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SectorFactory extends Factory
{
    protected $model = Sector::class;

    public function definition(): array
    {
        $name = fake()->unique()->word() . 'tech';
        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'icon' => '🔧',
        ];
    }
}
