<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeamCreationRequest extends FormRequest
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
    public function rules(): array
    {
        return [
                    'name' => 'required|min:1|max:255|unique:teams,name',
                    'coach_id' => 'required|integer|exists:users,id',
                    'roster_id' => 'required|integer|exists:rosters,id',
                    'gold_remaining' => 'required|integer|min:800000|max:30000000',
                    'team_value' => 'required|integer|min:0|max:1000000',

        ];
    }
}
