<?php


namespace Tests\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

trait UtilsForTesting {

public function DeleteUserAndCreate(): User
    {
        $user = User::firstWhere('email', 'ibai@example.com');

        if ($user != null) {
            $user->delete();
        }


        $new_user = User::factory()->create([
            'email' => 'ibai@example.com',
            'password' => Hash::make('secret123'),
        ]);

        return $new_user;

    }

}