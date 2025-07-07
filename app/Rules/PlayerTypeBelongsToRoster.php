<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Team;
use App\Models\Roster;
use App\Models\PlayerType;
use Illuminate\Validation\ValidationException;

class PlayerTypeBelongsToRoster implements ValidationRule
{
    protected int $teamId;

    public function __construct(int $teamId)
    {
        $this->teamId = $teamId;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
       $team = Team::find($this->teamId);
        $playerType = PlayerType::find($value);

        if (!$team || !$playerType) {
            $fail('Team or Player Type does not exist.');
            return;
        }

        $teamRoster = Roster::find($team->roster_id);
        if (!$teamRoster) {
            $fail('Team roster not found.');
            return;
        }

        if ($teamRoster->id != $playerType->roster_id) {
$fail('The type of player does not belong to the team roster.');
        }
    }
}
