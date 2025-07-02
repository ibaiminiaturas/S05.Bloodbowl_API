<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Solo en entornos locales o de desarrollo
        if (!app()->environment('local', 'development')) {
            return;
        }

        // Asegurarse de que exista el rol 'admin'
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Crear el usuario admin si no existe
        $user = User::firstOrCreate(
            ['email' => 'ibaiminiaturas@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'), // Contraseña temporal
            ]
        );

        // Asignar el rol
        $user->assignRole($adminRole);
    }
}
