<?php


namespace Tests\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

trait UtilsForTesting {

public function DeleteUserAndCreate(bool $coachRole = true): User
    {
        $user = User::firstWhere('email', 'ibai@example.com');

        if ($user != null) {
            $user->delete();
        }


        $new_user = User::factory()->create([
            'email' => 'ibai@example.com',
            'password' => Hash::make('secret123'),
        ]);
        if ($coachRole){
            $new_user->assignRole('coach');    
        }
        return $new_user;

    }

}