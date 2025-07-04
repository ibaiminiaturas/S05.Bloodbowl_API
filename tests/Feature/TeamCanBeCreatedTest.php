<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;

class TeamCanBeCreatedTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_admin_useer_can_create_team(): void
    {
        $this->withoutExceptionHandling();
        $user = User::where('email', 'ibaiminiaturas@gmail.com')->first();

        Passport::actingAs($user);

        $response = $this->postJson('/api/teams');

        $response->assertStatus(200);
    }
}
