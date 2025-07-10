<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use App\Models\Roster;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
{
    protected $model = Team::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'gold_remaining' => 1000000,
            'roster_id' => Roster::inRandomOrder()->value('id'), // Asegúrate de tener esta factory también
            'coach_id' => User::factory()->coach()->create(),
            'team_value' => 0,
        ];
    }
}
