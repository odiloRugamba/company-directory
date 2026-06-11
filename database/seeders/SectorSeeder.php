<?php

namespace Database\Seeders;

use App\Models\Sector;
use Illuminate\Database\Seeder;

class SectorSeeder extends Seeder
{
    public function run(): void
    {
        $sectors = [
            ['name' => 'Fintech', 'slug' => 'fintech', 'icon' => '💳'],
            ['name' => 'Agritech', 'slug' => 'agritech', 'icon' => '🌾'],
            ['name' => 'Healthtech', 'slug' => 'healthtech', 'icon' => '🏥'],
            ['name' => 'Edtech', 'slug' => 'edtech', 'icon' => '📚'],
            ['name' => 'Cleantech', 'slug' => 'cleantech', 'icon' => '♻️'],
            ['name' => 'Logistics', 'slug' => 'logistics', 'icon' => '🚚'],
            ['name' => 'E-commerce', 'slug' => 'ecommerce', 'icon' => '🛒'],
            ['name' => 'Govtech', 'slug' => 'govtech', 'icon' => '🏛️'],
            ['name' => 'Media', 'slug' => 'media', 'icon' => '📱'],
            ['name' => 'LegalTech', 'slug' => 'legaltech', 'icon' => '⚖️'],
            ['name' => 'PropTech', 'slug' => 'proptech', 'icon' => '🏠'],
            ['name' => 'Climate', 'slug' => 'climate', 'icon' => '🌍'],
        ];

        foreach ($sectors as $sector) {
            Sector::firstOrCreate(['slug' => $sector['slug']], $sector);
        }
    }
}
