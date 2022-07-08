<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_register_as_company()
    {
        $user = User::factory()->make();
        $company = Company::factory()->make();
        Storage::fake('profile-images');
        $profileImage = UploadedFile::fake()->image('profile-image.jpg');
        $response = $this->post('/api/auth/register/company', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'passwordA0!',
            'password_confirmation' => 'passwordA0!',
            'about' => $company->about,
            'location' => $company->location,
            'profile_image' => $profileImage,
        ]);
        $response
            ->assertStatus(201)
            ->assertJsonStructure(['name', 'email', 'created_at', 'userable']);
        $this->assertDatabaseHas('users', [
            'id' => 1,
            'email' => $user->email,
            'userable_type' => 'App\\Models\\Company',
            'userable_id' => 1,
        ])->assertDatabaseHas('companies', [
            'id' => 1,
            'name' => $user->name,
        ]);
        // TODO: fix this assertion
        // Storage::disk('profile-images')->assertExists(
        //     $profileImage->hashName()
        // );
    }

    public function test_user_cannot_register_without_passing_validation()
    {
        $user = User::factory()->make();
        $response = $this->json('post', '/api/auth/register/company', [
            'name' => '',
            'email' => 'wrong_email.com',
            'password' => 'password',
            'profile_image' => 'profile image as text',
            'location' => '1',
        ]);
        $response->assertInvalid([
            'name',
            'email',
            'password',
            'about',
            'profile_image',
        ]);
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
