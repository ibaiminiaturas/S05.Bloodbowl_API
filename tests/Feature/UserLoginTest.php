<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\Traits\UtilsForTesting;


class UserLoginTest extends TestCase
{
    use UtilsForTesting;

    public function test_user_can_authenticate_with_correct_credentials()
    {

        $user = $this->DeleteUserAndCreate();

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' =>  'secret123',
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'access_token',
            'token_type',
        ]);

        if ($user != null) {
            $user->delete();
        }

    }

    public function test_user_cant_authenticate_with_incorrect_credentials()
    {

        $user = $this->DeleteUserAndCreate();

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' =>  'secret1234',
        ]);


        $response->assertStatus(422);

        $response->assertJsonValidationErrors('email');
        $errors = $response->json('errors');
        $this->assertStringContainsString('Invalid credentials.', $errors['email'][0]);

        
        if ($user != null) {
            $user->delete();
        }

    }

    public function test_user_cant_authenticate_with_no_email()
    {

        $response = $this->postJson('/api/login', [
            'email' => '',
            'password' =>  'secret123',
        ]);


        $response->assertStatus(422);

        $response->assertJsonValidationErrors('email');
        $errors = $response->json('errors');
        $this->assertStringContainsString('The email field is required.', $errors['email'][0]);
    }

    public function test_user_cant_authenticate_with_no_password()
    {

        $response = $this->postJson('/api/login', [
            'email' => "any@email.com",
            'password' =>  '',
        ]);


        $response->assertStatus(422);

        $response->assertJsonValidationErrors('password');
        $errors = $response->json('errors');
        $this->assertStringContainsString('The password field is required.', $errors['password'][0]);
    }

}
