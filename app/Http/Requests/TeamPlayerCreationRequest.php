<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class TeamPlayerCreationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
public function rules()
{
    return [
        'player_type_id' => 'required|integer|exists:player_types,id',
        'player_number' => [
            'required',
            'integer',
            Rule::unique('team_players')->where(function ($query) {
                return $query->where('team_id', $this->team_id)
                ->where('player_number', $this->player_number);;
            }),
        ],

        'name'=> [
            'min:6', 
            'max:255',
            'integer',
            Rule::unique('team_players')->where(function ($query) {
                return $query->where('team_id', $this->team_id)
                ->where('name', $this->name);;
            }),
        ],

        'injuries' => 'min:6|max:255',
        'spp' => 'integer|min:0|max20'
    ];
}
}
