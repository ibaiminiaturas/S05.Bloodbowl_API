<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Team;
use App\Models\PlayerType;
use App\Models\TeamPlayer;
use App\Rules\TeamHasEnoughGold;
use App\Rules\PlayerTypeBelongsToRoster;

class TeamPlayerUpdateDeleteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    protected function playerId()
    {
        $teamPlayer = $this->route('teamPlayer');
        return is_object($teamPlayer) ? $teamPlayer->id : $teamPlayer;
    }

    public function rules()
    {
        return [

            'injuries' => 'nullable|string|max:255|nullable',
            'spp' => 'nullable|integer|min:0|max:20',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $user = $this->user();

            $player = $this->route('teamPlayer');


            if (!$player) {
                $validator->errors()->add('general', 'Could not verify the player.');
                return;
            }

            $team = $player->team;


            if (!$team) {
                $validator->errors()->add('general', 'Team not found.');
                return;
            }

            if ($user->hasRole('coach') && $team->coach_id !== $user->id) {
                $validator->errors()->add('team_id', 'The team does not belong to the user.');
                return;
            }
        });
    }
}
