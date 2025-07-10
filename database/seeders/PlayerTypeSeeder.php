<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlayerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $player_types = [
            // 1. Humans
            ['name' => 'Lineman', 'roster_id' => 1, 'max_per_team' => 16, 'movement' => 6, 'strength' => 3, 'agility' => 3, 'passing' => 2, 'armor' => 7, 'cost' => 50000],
            ['name' => 'Thrower', 'roster_id' => 1, 'max_per_team' => 2, 'movement' => 6, 'strength' => 3, 'agility' => 3, 'passing' => 4, 'armor' => 7, 'cost' => 70000],
            ['name' => 'Catcher', 'roster_id' => 1, 'max_per_team' => 4, 'movement' => 7, 'strength' => 3, 'agility' => 4, 'passing' => 2, 'armor' => 7, 'cost' => 80000],
            ['name' => 'Blitzer', 'roster_id' => 1, 'max_per_team' => 4, 'movement' => 7, 'strength' => 3, 'agility' => 3, 'passing' => 2, 'armor' => 8, 'cost' => 90000],

            // 2. Orcs
            ['name' => 'Orc', 'roster_id' => 2, 'max_per_team' => 16, 'movement' => 5, 'strength' => 3, 'agility' => 2, 'passing' => 1, 'armor' => 8, 'cost' => 60000],
            ['name' => 'Black Orc', 'roster_id' => 2, 'max_per_team' => 4, 'movement' => 5, 'strength' => 4, 'agility' => 2, 'passing' => 1, 'armor' => 9, 'cost' => 90000],
            ['name' => 'Goblin', 'roster_id' => 2, 'max_per_team' => 4, 'movement' => 6, 'strength' => 2, 'agility' => 3, 'passing' => 1, 'armor' => 7, 'cost' => 50000],
            ['name' => 'Blitzer', 'roster_id' => 2, 'max_per_team' => 4, 'movement' => 6, 'strength' => 3, 'agility' => 3, 'passing' => 2, 'armor' => 8, 'cost' => 90000],

            // 3. Dwarfs
            ['name' => 'Lineman', 'roster_id' => 3, 'max_per_team' => 16, 'movement' => 5, 'strength' => 3, 'agility' => 3, 'passing' => 1, 'armor' => 8, 'cost' => 60000],
            ['name' => 'Runner', 'roster_id' => 3, 'max_per_team' => 4, 'movement' => 6, 'strength' => 3, 'agility' => 4, 'passing' => 1, 'armor' => 8, 'cost' => 70000],
            ['name' => 'Blocker', 'roster_id' => 3, 'max_per_team' => 4, 'movement' => 4, 'strength' => 4, 'agility' => 2, 'passing' => 1, 'armor' => 9, 'cost' => 90000],
            ['name' => 'Thunderer', 'roster_id' => 3, 'max_per_team' => 2, 'movement' => 4, 'strength' => 3, 'agility' => 2, 'passing' => 1, 'armor' => 8, 'cost' => 70000],

            // 4. Skaven
            ['name' => 'Lineman', 'roster_id' => 4, 'max_per_team' => 16, 'movement' => 7, 'strength' => 2, 'agility' => 3, 'passing' => 1, 'armor' => 7, 'cost' => 50000],
            ['name' => 'Thrower', 'roster_id' => 4, 'max_per_team' => 2, 'movement' => 7, 'strength' => 2, 'agility' => 3, 'passing' => 4, 'armor' => 7, 'cost' => 70000],
            ['name' => 'Blitzer', 'roster_id' => 4, 'max_per_team' => 4, 'movement' => 7, 'strength' => 3, 'agility' => 3, 'passing' => 2, 'armor' => 7, 'cost' => 90000],
            ['name' => 'Gutter Runner', 'roster_id' => 4, 'max_per_team' => 4, 'movement' => 8, 'strength' => 2, 'agility' => 4, 'passing' => 3, 'armor' => 7, 'cost' => 110000],

            // 5. Elven Union
            ['name' => 'Lineman', 'roster_id' => 5, 'max_per_team' => 16, 'movement' => 7, 'strength' => 3, 'agility' => 3, 'passing' => 2, 'armor' => 7, 'cost' => 60000],
            ['name' => 'Thrower', 'roster_id' => 5, 'max_per_team' => 2, 'movement' => 6, 'strength' => 3, 'agility' => 3, 'passing' => 4, 'armor' => 7, 'cost' => 70000],
            ['name' => 'Catcher', 'roster_id' => 5, 'max_per_team' => 4, 'movement' => 8, 'strength' => 2, 'agility' => 4, 'passing' => 3, 'armor' => 7, 'cost' => 90000],
            ['name' => 'Blitzer', 'roster_id' => 5, 'max_per_team' => 4, 'movement' => 7, 'strength' => 3, 'agility' => 3, 'passing' => 2, 'armor' => 7, 'cost' => 90000],

            // 6. Lizardmen
            ['name' => 'Skink', 'roster_id' => 6, 'max_per_team' => 16, 'movement' => 8, 'strength' => 2, 'agility' => 4, 'passing' => 1, 'armor' => 7, 'cost' => 50000],
            ['name' => 'Saurus', 'roster_id' => 6, 'max_per_team' => 12, 'movement' => 6, 'strength' => 4, 'agility' => 2, 'passing' => 1, 'armor' => 9, 'cost' => 80000],
            ['name' => 'Chameleon Skink', 'roster_id' => 6, 'max_per_team' => 2, 'movement' => 8, 'strength' => 2, 'agility' => 4, 'passing' => 2, 'armor' => 7, 'cost' => 70000],
            ['name' => 'Slann', 'roster_id' => 6, 'max_per_team' => 1, 'movement' => 6, 'strength' => 3, 'agility' => 3, 'passing' => 5, 'armor' => 8, 'cost' => 140000],

            // 7. Chaos Chosen
            ['name' => 'Chaos Warrior', 'roster_id' => 7, 'max_per_team' => 16, 'movement' => 5, 'strength' => 4, 'agility' => 2, 'passing' => 1, 'armor' => 9, 'cost' => 70000],
            ['name' => 'Chaos Chosen', 'roster_id' => 7, 'max_per_team' => 4, 'movement' => 5, 'strength' => 5, 'agility' => 2, 'passing' => 1, 'armor' => 9, 'cost' => 120000],
            ['name' => 'Beastman', 'roster_id' => 7, 'max_per_team' => 4, 'movement' => 6, 'strength' => 3, 'agility' => 3, 'passing' => 1, 'armor' => 8, 'cost' => 60000],
            ['name' => 'Horror', 'roster_id' => 7, 'max_per_team' => 2, 'movement' => 6, 'strength' => 4, 'agility' => 2, 'passing' => 1, 'armor' => 8, 'cost' => 90000],

            // 8. Khemri
            ['name' => 'Khemri Warrior', 'roster_id' => 8, 'max_per_team' => 16, 'movement' => 5, 'strength' => 4, 'agility' => 2, 'passing' => 1, 'armor' => 9, 'cost' => 70000],
            ['name' => 'Tomb Guardian', 'roster_id' => 8, 'max_per_team' => 4, 'movement' => 5, 'strength' => 5, 'agility' => 2, 'passing' => 1, 'armor' => 10, 'cost' => 120000],
            ['name' => 'Mummy', 'roster_id' => 8, 'max_per_team' => 2, 'movement' => 5, 'strength' => 5, 'agility' => 1, 'passing' => 1, 'armor' => 10, 'cost' => 140000],
            ['name' => 'Skeleton', 'roster_id' => 8, 'max_per_team' => 12, 'movement' => 6, 'strength' => 3, 'agility' => 2, 'passing' => 1, 'armor' => 7, 'cost' => 50000],

            // 9. Necromantic
            ['name' => 'Ghoul', 'roster_id' => 9, 'max_per_team' => 16, 'movement' => 6, 'strength' => 3, 'agility' => 3, 'passing' => 1, 'armor' => 7, 'cost' => 60000],
            ['name' => 'Wight', 'roster_id' => 9, 'max_per_team' => 4, 'movement' => 5, 'strength' => 4, 'agility' => 2, 'passing' => 1, 'armor' => 8, 'cost' => 90000],
            ['name' => 'Mummy', 'roster_id' => 9, 'max_per_team' => 2, 'movement' => 5, 'strength' => 5, 'agility' => 1, 'passing' => 1, 'armor' => 10, 'cost' => 140000],
            ['name' => 'Skeleton', 'roster_id' => 9, 'max_per_team' => 12, 'movement' => 6, 'strength' => 3, 'agility' => 2, 'passing' => 1, 'armor' => 7, 'cost' => 50000],

            // 10. Norse
            ['name' => 'Norse Lineman', 'roster_id' => 10, 'max_per_team' => 16, 'movement' => 6, 'strength' => 3, 'agility' => 3, 'passing' => 2, 'armor' => 7, 'cost' => 60000],
            ['name' => 'Norse Thrower', 'roster_id' => 10, 'max_per_team' => 2, 'movement' => 6, 'strength' => 3, 'agility' => 3, 'passing' => 4, 'armor' => 7, 'cost' => 70000],
            ['name' => 'Norse Blitzer', 'roster_id' => 10, 'max_per_team' => 4, 'movement' => 7, 'strength' => 3, 'agility' => 3, 'passing' => 2, 'armor' => 8, 'cost' => 90000],
            ['name' => 'Norse Berserker', 'roster_id' => 10, 'max_per_team' => 4, 'movement' => 7, 'strength' => 4, 'agility' => 2, 'passing' => 1, 'armor' => 8, 'cost' => 90000],
        ];

        DB::table('player_types')->insert($player_types);
    }
}
