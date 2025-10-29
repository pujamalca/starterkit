<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        $response = $this->postJson(route('api.v1.auth.register'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'email'],
                'meta' => ['token', 'token_type'],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }

    public function test_register_validation_fails_with_invalid_data(): void
    {
        $response = $this->postJson(route('api.v1.auth.register'), [
            'name' => '',
            'email' => 'invalid-email',
            'password' => 'short',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson(route('api.v1.auth.login'), [
            'login' => 'test@example.com',
            'password' => 'password123',
            'device_name' => 'test-device',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'email'],
                'meta' => ['token', 'token_type'],
            ]);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        $response = $this->postJson(route('api.v1.auth.login'), [
            'login' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['login']);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withToken($token)
            ->postJson(route('api.v1.auth.logout'));

        $response->assertStatus(200)
            ->assertJson(['message' => 'Anda telah keluar.']);
    }

    public function test_user_can_get_profile(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withToken($token)
            ->getJson(route('api.v1.auth.profile'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'email'],
            ])
            ->assertJson([
                'data' => [
                    'name' => 'Test User',
                    'email' => 'test@example.com',
                ],
            ]);
    }

    public function test_profile_requires_authentication(): void
    {
        $response = $this->getJson(route('api.v1.auth.profile'));

        $response->assertStatus(401);
    }
}
