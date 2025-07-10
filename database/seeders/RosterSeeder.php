<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RosterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rosters = [
            ['id' => 1, 'name' => 'Humans'],
            ['id' => 2, 'name' => 'Orcs'],
            ['id' => 3, 'name' => 'Dwarfs'],
            ['id' => 4, 'name' => 'Skaven'],
            ['id' => 5, 'name' => 'Elven Union'],
            ['id' => 6, 'name' => 'Lizardmen'],
            ['id' => 7, 'name' => 'Chaos Chosen'],
            ['id' => 8, 'name' => 'Khemri'],
            ['id' => 9, 'name' => 'Necromantic'],
            ['id' => 10, 'name' => 'Norse'],
        ];

        DB::table('rosters')->insert($rosters);
    }
}
