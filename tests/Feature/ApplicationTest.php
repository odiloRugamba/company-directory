<?php

namespace Tests\Feature;

use App\Enums\ApplicationStatus;
use App\Enums\Role;
use App\Models\Application;
use App\Models\IncubatorProfile;
use App\Models\StartupProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationTest extends TestCase
{
    use RefreshDatabase;

    private function makeStartup(): User
    {
        $user = User::factory()->create(['role' => Role::Startup]);
        StartupProfile::factory()->create(['user_id' => $user->id]);
        return $user;
    }

    private function makeIncubator(): User
    {
        $user = User::factory()->create(['role' => Role::Incubator]);
        IncubatorProfile::factory()->create(['user_id' => $user->id]);
        return $user;
    }

    public function test_startup_can_submit_application(): void
    {
        $startup = $this->makeStartup();
        $incubator = $this->makeIncubator();

        $response = $this->actingAs($startup, 'sanctum')
            ->postJson('/api/v1/applications', [
                'incubator_id' => $incubator->id,
                'motivation' => str_repeat('This is a test motivation. ', 10),
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.status', 'pending');

        $this->assertDatabaseHas('applications', [
            'startup_id' => $startup->id,
            'incubator_id' => $incubator->id,
        ]);
    }

    public function test_startup_cannot_apply_twice_to_same_incubator(): void
    {
        $startup = $this->makeStartup();
        $incubator = $this->makeIncubator();

        Application::factory()->create([
            'startup_id' => $startup->id,
            'incubator_id' => $incubator->id,
        ]);

        $response = $this->actingAs($startup, 'sanctum')
            ->postJson('/api/v1/applications', [
                'incubator_id' => $incubator->id,
                'motivation' => str_repeat('This is a test motivation. ', 10),
            ]);

        $response->assertStatus(422);
    }

    public function test_incubator_can_update_application_status(): void
    {
        $startup = $this->makeStartup();
        $incubator = $this->makeIncubator();

        $application = Application::factory()->create([
            'startup_id' => $startup->id,
            'incubator_id' => $incubator->id,
            'status' => ApplicationStatus::Pending,
        ]);

        $response = $this->actingAs($incubator, 'sanctum')
            ->patchJson("/api/v1/applications/{$application->id}/status", [
                'status' => 'accepted',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.status', 'accepted');

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $startup->id,
        ]);
    }

    public function test_startup_cannot_update_application_status(): void
    {
        $startup = $this->makeStartup();
        $incubator = $this->makeIncubator();

        $application = Application::factory()->create([
            'startup_id' => $startup->id,
            'incubator_id' => $incubator->id,
        ]);

        $response = $this->actingAs($startup, 'sanctum')
            ->patchJson("/api/v1/applications/{$application->id}/status", [
                'status' => 'accepted',
            ]);

        $response->assertStatus(403);
    }

    public function test_admin_routes_return_403_for_non_admin(): void
    {
        $startup = $this->makeStartup();

        $response = $this->actingAs($startup)->get('/admin/dashboard');

        $response->assertStatus(403);
    }
}
