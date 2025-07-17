<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeamUpdateRequest extends FormRequest
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
        $team = $this->route('team'); // o como accedas al modelo

        return [
            'name' => [
                'sometimes',
                'string',
                'min:1',
                'max:255',
                Rule::unique('teams', 'name')->ignore($team->id),
            ],
            'team_value' => [
                'sometimes',
                'integer',
                'min:0',
                'max:1000000',
            ],
        ];
    }
}
