<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\IncubatorProfile;
use App\Models\Sector;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IncubatorApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_incubator_list_is_paginated(): void
    {
        $user = User::factory()->create(['role' => Role::Incubator]);
        IncubatorProfile::factory()->count(5)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/v1/incubators');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'meta' => ['current_page', 'last_page', 'total'],
            ]);
    }

    public function test_incubator_list_filters_by_country(): void
    {
        $rwUser = User::factory()->create(['role' => Role::Incubator]);
        IncubatorProfile::factory()->create(['user_id' => $rwUser->id, 'country' => 'RW']);

        $ngUser = User::factory()->create(['role' => Role::Incubator]);
        IncubatorProfile::factory()->create(['user_id' => $ngUser->id, 'country' => 'NG']);

        $response = $this->getJson('/api/v1/incubators?country=RW');

        $response->assertStatus(200);
        $this->assertEquals(1, $response->json('meta.total'));
    }

    public function test_incubator_list_filters_by_sector(): void
    {
        $user1 = User::factory()->create(['role' => Role::Incubator]);
        IncubatorProfile::factory()->create(['user_id' => $user1->id, 'focus_sectors' => ['fintech', 'agritech']]);

        $user2 = User::factory()->create(['role' => Role::Incubator]);
        IncubatorProfile::factory()->create(['user_id' => $user2->id, 'focus_sectors' => ['healthtech']]);

        $response = $this->getJson('/api/v1/incubators?sector=fintech');

        $response->assertStatus(200);
        $this->assertEquals(1, $response->json('meta.total'));
    }

    public function test_incubator_detail_returns_by_slug(): void
    {
        $user = User::factory()->create(['role' => Role::Incubator]);
        $profile = IncubatorProfile::factory()->create(['user_id' => $user->id, 'slug' => 'test-hub']);

        $response = $this->getJson('/api/v1/incubators/test-hub');

        $response->assertStatus(200)
            ->assertJsonPath('data.slug', 'test-hub');
    }

    public function test_sectors_list_returns_all_sectors(): void
    {
        Sector::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/sectors');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }
}
