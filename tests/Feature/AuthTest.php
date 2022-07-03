<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_register()
    {
        $user = User::factory()->make();
        $response = $this->json('post', '/api/auth/register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'passwordA0!',
        ]);
        $response
            ->assertStatus(201)
            ->assertJsonStructure(['name', 'email', 'created_at']);
        $this->assertDatabaseHas('users', [
            'id' => 1,
            'email' => $user->email,
        ]);
    }

    public function test_user_cannot_register_without_passing_validation()
    {
        $user = User::factory()->make();
        $response = $this->json('post', '/api/auth/register', [
            'name' => '',
            'email' => 'wrongemail.com',
            'password' => 'password',
        ]);
        $response
            ->assertStatus(422)
            ->assertJsonStructure(['message', 'errors']);
        $this->assertDatabaseMissing('users', [
            'name' => $user->name,
        ]);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'password' => Hash::make('passwordA0!'),
        ]);
        $response = $this->json('post', '/api/auth/login', [
            'email' => $user->email,
            'password' => 'passwordA0!',
        ]);
        $response->assertStatus(200)->assertJsonStructure(['token']);
    }

    public function test_user_cannot_login_with_wrong_credentials()
    {
        $user = User::factory()->create([
            'password' => Hash::make('passwordA0!'),
        ]);
        $response = $this->json('post', '/api/auth/login', [
            'email' => $user->email,
            'password' => 'wrongpasswordA0!',
        ]);
        $response
            ->assertStatus(401)
            ->assertJson(['message' => 'Invalid credentials!']);
    }
}
