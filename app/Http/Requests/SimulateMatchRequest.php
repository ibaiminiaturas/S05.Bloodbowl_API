<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SimulateMatchRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'team_1_id' => 'required|exists:teams,id',
            'team_2_id' => 'required|exists:teams,id|different:team_1_id',
        ];
    }
}
