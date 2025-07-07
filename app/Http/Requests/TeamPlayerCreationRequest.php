<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Team;
use App\Models\PlayerType;
use App\Rules\TeamHasEnoughGold;
use App\Rules\PlayerTypeBelongsToRoster;

class TeamPlayerCreationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    protected function teamId()
    {
        $team = $this->route('team');
        return is_object($team) ? $team->id : $team;
    }

    public function rules()
    {
        $teamId = $this->teamId();

        return [
            'player_type_id' => [
                'required',
                'integer',
                'exists:player_types,id',
                new PlayerTypeBelongsToRoster($teamId),
            ],

           'player_number' => [
                'required',
                'integer',
                Rule::unique('team_players')->where(function ($query) use ($teamId) {
                    return $query->where('team_id', $teamId)
                        ->where('player_number', $this->player_number);
                }),
            ],

            'name' => [
                'min:6',
                'max:255',
                Rule::unique('team_players')->where(function ($query) use ($teamId) {
                    return $query->where('team_id', $teamId)
                        ->where('name', $this->name);
                }),
            ],

            'injuries' => 'string|max:255|nullable',

            'spp' => 'integer|min:0|max:20',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $user = $this->user();

            $playerTypeId = $this->input('player_type_id');
            $teamId = $this->teamId();
            $team = Team::find($teamId);

            $playerType = PlayerType::find($playerTypeId);

            if (! $team || ! $playerType) {
                $validator->errors()->add('general', 'Could not verify team or player type.');
                return;
            }

            if ($team->coach_id !== $user->id) {
                $validator->errors()->add('team_id', 'The team does not belong to the user.');
                return;
            }



            if (! $team || ! $playerType) {
                $validator->errors()->add('general', 'Could not verify team or player type');
                return;
            }

            if ($team->gold_remaining < $playerType->cost) {
                $validator->errors()->add('general', 'The Team has not enough gold. Only ' . $team->gold_remaining . ' gold remaining');
            }


        });
    }
}
