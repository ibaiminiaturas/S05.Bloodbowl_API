<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserRegistrationTest extends TestCase
{
    public function test_user_can_register_successfully(): void
    {
        $user = User::Where('email', 'ibai@example.com')->first();

        if ($user != null) {
            $user->delete();
        }

        $response = $this->postJson('/api/register', [
            'name' => 'Ibaimania',
            'email' => 'ibai@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'email' => 'ibai@example.com',
        ]);
    }


    public function test_user_already_exists(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Ibaimania',
            'email' => 'ibai@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
        $errors = $response->json('errors');
        $this->assertStringContainsString('The email has already been taken.', $errors['email'][0]);

    }

    public function test_user_does_not_send_name(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => '',
            'email' => 'ibai@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
        $errors = $response->json('errors');
        $this->assertStringContainsString("The name field is required.", $errors['name'][0]);

    }

    public function test_user_does_not_send_mail(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Ibaimania',
            'email' => '',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
        $errors = $response->json('errors');
        $this->assertStringContainsString('The email field is required.', $errors['email'][0]);

    }


    public function test_user_password_too_short(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Ibaimania',
            'email' => 'ibai2@example.com',
            'password' => '123',
            'password_confirmation' => '123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('password');
        $errors = $response->json('errors');
        $this->assertStringContainsString('The password field must be at least 6 characters.', $errors['password'][0]);


    }

    public function test_user_password_confirmation_is_not_equal(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Ibaimania',
            'email' => 'ibai2@example.com',
            'password' => '12345678',
            'password_confirmation' => '12345679',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('password');
        $errors = $response->json('errors');
        $this->assertStringContainsString('The password field confirmation does not match.', $errors['password'][0]);

    }


    public function test_user_email_is_invalid_format(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Ibaimania',
            'email' => 'no-es-un-email',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');

        $errors = $response->json('errors');
        $this->assertStringContainsString('The email field must be a valid email address.', $errors['email'][0]);
    }

}
