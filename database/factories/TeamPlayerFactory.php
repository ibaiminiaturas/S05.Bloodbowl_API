<?php

namespace Database\Factories;

use App\Models\PlayerType;
use App\Models\TeamPlayer;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TeamPlayer>
 */
class TeamPlayerFactory extends Factory
{
    protected $model = TeamPlayer::class;

    public function definition()
    {
        return [
            'player_type_id' => null,
            'player_number' => $this->faker->unique()->numberBetween(1, 20),
            'name' => $this->faker->name(),
            'injuries' => $this->faker->optional()->sentence(),
            'spp' => $this->faker->numberBetween(0, 20),
        ];


    }

    public function forTeamRoster($teamId)
    {
        return $this->state(function () use ($teamId) {
            $team = Team::find($teamId);
            $playerType = PlayerType::where('roster_id', $team->roster_id)->inRandomOrder()->first();

            return [
                'player_type_id' => $playerType ? $playerType->id : null,
                'team_id' => $teamId,
            ];
        });
    }
}
