<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserLoginTest extends TestCase
{
    public function test_user_can_login()
    {
        $user = User::Where('email', 'ibai@example.com')->first();

        if ($user != null) {
            $user->delete();
        }

        $user = User::factory()->create([
            'email' => 'ibai@example.com',
            'password' => Hash::make('secret123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'ibai@example.com',
            'password' => 'secret123',
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'access_token',
            'token_type',
        ]);
    }

}
