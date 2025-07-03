<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\Traits\UtilsForTesting;


class UserLogoutTest extends TestCase
{
    use UtilsForTesting;

    public function test_user_can_logout()
    {
        $user = $this->DeleteUserAndCreate();
        $token = $user->createToken('TestToken')->accessToken;

        // Logout
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');


        $response->assertStatus(200);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/user');

        $response->assertStatus(401);
    }
}
