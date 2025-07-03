<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Roster;
use Laravel\Passport\Passport;
use Tests\Traits\UtilsForTesting;
use App\Models\Skill;


class RostersRetrieveTest extends TestCase
{
        use UtilsForTesting;
    /**
     * A basic feature test example.
     */
    public function test_admin_user_can_retireve_rosters(): void
    {

        $user = User::where('email', 'ibaiminiaturas@gmail.com')->first();

        Passport::actingAs($user);
         $this->getRostersAndCheck();
    }


    public function test_not_admin_user_can_retireve_rosters(): void
    {
        $user = $this->DeleteUserAndCreate();

        Passport::actingAs($user);

        $this->getRostersAndCheck();

        if ($user != null) {
            $user->delete();
        }
    }

    public function getRostersAndCheck()
    {
        $response = $this->getJson('/api/rosters');
        
        $response->assertStatus(200);        

        $data = $response->json();
        $this->assertNotEmpty($data);
        $this->assertEquals(count($data['data']), Roster::count());
    }

}
