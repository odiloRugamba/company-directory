<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_startup_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test Startup',
            'email' => 'startup@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'startup',
        ]);

        $response->assertRedirect('/setup');
        $this->assertDatabaseHas('users', ['email' => 'startup@test.com', 'role' => 'startup']);
    }

    public function test_incubator_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test Incubator',
            'email' => 'incubator@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'incubator',
        ]);

        $response->assertRedirect('/setup');
        $this->assertDatabaseHas('users', ['email' => 'incubator@test.com', 'role' => 'incubator']);
    }

    public function test_admin_cannot_register_publicly(): void
    {
        $response = $this->post('/register', [
            'name' => 'Fake Admin',
            'email' => 'fake@admin.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'admin',
        ]);

        $response->assertSessionHasErrors('role');
        $this->assertDatabaseMissing('users', ['email' => 'fake@admin.com']);
    }

    public function test_user_without_profile_is_redirected_to_setup(): void
    {
        $user = User::factory()->create(['role' => Role::Startup]);

        $response = $this->actingAs($user)->get('/dashboard/startup');

        $response->assertRedirect('/setup');
    }

    public function test_api_register_returns_token(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'API User',
            'email' => 'api@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'startup',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['data', 'meta' => ['token'], 'message']);
    }

    public function test_api_login_returns_token(): void
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'meta' => ['token']]);
    }
}
