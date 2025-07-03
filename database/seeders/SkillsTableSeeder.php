<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            ['name' => 'Block', 'description' => 'Allows a player to safely block an opponent.'],
            ['name' => 'Dodge', 'description' => 'Helps a player avoid tackles more easily.'],
            ['name' => 'Mighty Blow', 'description' => 'Increases damage on blocks.'],
            ['name' => 'Kick', 'description' => 'Improves kicking distance.'],
            ['name' => 'Catch', 'description' => 'Increases chance of catching the ball.'],
            ['name' => 'Leap', 'description' => 'Allows jumping over opposing players.'],
            ['name' => 'Guard', 'description' => 'Protects teammates from opponent blocks.'],
            ['name' => 'Pass', 'description' => 'Improves passing ability.'],
            ['name' => 'Stunty', 'description' => 'Small players are harder to hit.'],
            ['name' => 'Sure Hands', 'description' => 'Reduces chances of fumbling the ball.'],
            ['name' => 'Dirty Player', 'description' => 'Allows committing fouls without immediate penalty.'],
            ['name' => 'Fend', 'description' => 'Allows defending when blocked by an opponent.'],
            ['name' => 'Mighty Block', 'description' => 'Advanced block that stuns the opponent.'],
            ['name' => 'Horns', 'description' => 'Increases strength in blocks (typical of Chaos Chosen).'],
            ['name' => 'Sprint', 'description' => 'Allows extra movement when running.'],
            ['name' => 'Break Tackle', 'description' => 'Helps escaping tackles more easily.'],
            ['name' => 'Claw', 'description' => 'Increases chances to cause damage and knock down opponents.'],
            ['name' => 'Multiple Block', 'description' => 'Allows blocking multiple opponents in one turn.'],
            ['name' => 'Hypnotic Gaze', 'description' => 'Typical of Lizardmen players, reduces opponent mobility.'],
            ['name' => 'Regeneration', 'description' => 'Allows recovering injuries after the match.'],
        ];

        DB::table('skills')->insert($skills);
    }
}
