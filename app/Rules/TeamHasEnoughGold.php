<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Team;
use App\Models\PlayerType;

class TeamHasEnoughGold implements ValidationRule
{
    protected int $playerTypeId;

    public function __construct(int $playerTypeId)
    {
        $this->playerTypeId = $playerTypeId;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $team = Team::find($value);
        $playerType = PlayerType::find($this->playerTypeId);

        if (! $team || ! $playerType) {
            $fail('Can not verify that team.');
            return;
        }

        if ($team->gold < $playerType->cost) {
            $fail("The Team needs at least {$playerType->cost} gold.");
        }
    }
}
