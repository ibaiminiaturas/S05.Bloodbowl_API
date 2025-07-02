<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesCreationSedder extends Seeder
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
