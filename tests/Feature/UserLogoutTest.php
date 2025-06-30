<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserLogoutTest extends TestCase
{
    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->accessToken;

        // Logout
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        $response->assertStatus(200);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/test');

        $response->assertStatus(200);
    }
}
