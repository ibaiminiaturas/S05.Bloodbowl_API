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

    public function rules()
    {
        return [
            'player_type_id' => [
                'required',
                'integer',
                'exists:player_types,id',
                new PlayerTypeBelongsToRoster($this->input('team_id')),
            ],

            'team_id' => 'required|integer|exists:teams,id',

            'player_number' => [
                'required',
                'integer',
                Rule::unique('team_players')->where(function ($query) {
                    return $query->where('team_id', $this->team_id)
                        ->where('player_number', $this->player_number);
                }),
            ],

            'name' => [
                'min:6',
                'max:255',
                Rule::unique('team_players')->where(function ($query) {
                    return $query->where('team_id', $this->team_id)
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
            $playerTypeId = $this->input('player_type_id');
            $teamId = $this->input('team_id');

            $team = Team::find($teamId);
            $playerType = PlayerType::find($playerTypeId);

            if (! $team || ! $playerType) {
                $validator->errors()->add('general', 'No se pudo verificar el equipo o tipo de jugador.');
                return;
            }

            if ($team->gold < $playerType->cost) {
                $validator->errors()->add('general', 'El equipo no tiene suficiente oro.');
            }
        });
    }
}
