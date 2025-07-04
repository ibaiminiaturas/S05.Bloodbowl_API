<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;
use Tests\Traits\UtilsForTesting;
use App\Models\Skill;

class SkillsRetrieveTest extends TestCase
{
    use UtilsForTesting;
    /**
     * A basic feature test example.
     */
    public function test_any_user_can_retireve_skills(): void
    {
        $admin = $this->getAdminUser();

        Passport::actingAs($admin);
        $response = $this->getJson('/api/skills');

        $response->assertStatus(200);

        $data = $response->json();
        $this->assertNotEmpty($data);

        $this->assertEquals(count($data['data']), Skill::count());


        $user = $this->DeleteUserAndCreate();

        Passport::actingAs($user);
        $response = $this->getJson('/api/skills');

        $response->assertStatus(200);

        $data = $response->json();
        $this->assertNotEmpty($data);

        $this->assertEquals(count($data['data']), Skill::count());
    }
}
