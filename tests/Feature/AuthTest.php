<?php

namespace Tests\Feature;

use App\Http\Controllers\AuthController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(AuthController::class)]
class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_successfully(): void
    {
        // Arrange
        User::factory()->create([
            'role' => 'user',
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        // Act
        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password'
        ]);

        // Assert

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'access_token',
                'expires_in',
            ]);
    }

    public function test_login_with_invalid_credentials(): void
    {
        // Arrange
        User::factory()->create([
            'role' => 'user',
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        // Act
        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        // Assert
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_me(): void
    {
        // Arrange
        User::factory()->create([
            'role' => 'user',
            'name' => 'John Doe',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Act
        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        // Assert
        $response->assertStatus(Response::HTTP_OK);

        $token = $response->json('access_token');

        $response = $this->getJson('/api/auth/me', [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('id')
                ->whereType('id', 'integer')
                ->where('name', 'John Doe')
                ->where('email', 'test@example.com')
                ->has('created_at')
                ->has('updated_at')
                ->missing('password')
                ->etc()
            );
    }
}













