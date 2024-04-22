<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function testRegisterSuccessfully()
    {
        $response = $this->postJson('/auth/register', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'User Created',
            ])
            ->assertJsonStructure([
                'token',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }

    public function testRegisterWithExistingEmail()
    {
        User::create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/auth/register', [
            'email' => 'test@example.com',
            'password' => 'newpassword123',
        ]);

        $response->assertStatus(422);
    }

    public function testLoginSuccessfully()
    {
        $user = User::create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
            ]);
    }

    public function testLoginWithWrongCredentials()
    {
        $user = User::create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/auth/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401);
    }

    public function testChangePasswordSuccessfully()
    {
        $user = User::create([
            'email' => 'test@example.com',
            'password' => bcrypt('oldpassword'),
        ]);

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/auth/change-password', [
            'current_password' => 'oldpassword',
            'new_password' => 'newpassword123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Password changed successfully',
            ]);
    }

    public function testChangePasswordWithWrongCurrentPassword()
    {
        $user = User::create([
            'email' => 'test@example.com',
            'password' => bcrypt('oldpassword'),
        ]);

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/auth/change-password', [
            'current_password' => 'wrongpassword',
            'new_password' => 'newpassword123',
        ]);

        $response->assertStatus(400);
    }

    public function testMe()
    {
        $user = User::create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/auth/me');

        $response->assertStatus(200)
            ->assertJson([
                'email' => 'test@example.com',
            ]);
    }
}
