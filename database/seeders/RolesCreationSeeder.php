<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;


class RolesCreationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asegurarse de que exista el rol 'admin'
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $coachRol = Role::firstOrCreate(['name' => 'coach']);
    }
}
