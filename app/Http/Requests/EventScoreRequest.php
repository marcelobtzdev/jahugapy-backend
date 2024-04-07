<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventScoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->route()->getName()) {
            case 'events.scores.store':
                $rules = $this->store();
                break;
            case 'events.scores.update':
                $rules = $this->update();
                break;
        }

        return $rules;
    }

    /**
     * Get the validation rules that apply to the post request.
     *
     * @return array
     */
    public function store()
    {
        return [
            'team_id' => [
                'required',
                'integer',
            ],
            'date_number' => [
                'required',
                'integer',
            ],
            'match_number' => [
                'required',
                'integer',
            ],
            'kills' => [
                'required',
                'integer',
            ],
            'kills_image' => [
                'required',
                'string'
            ],
            'position' => [
                'required',
                'integer',
            ],
            'position_image' => [
                'required',
                'string',
            ],
        ];
    }

    /**
     * Get the validation rules that apply to the put request.
     *
     * @return array
     */
    public function update()
    {
        return [
            'team_id' => [
                'required',
                'integer',
            ],
            'date_number' => [
                'required',
                'integer',
            ],
            'match_number' => [
                'required',
                'integer',
            ],
            'kills' => [
                'required',
                'integer',
            ],
            'kills_image' => [
                'required',
                'string',
            ],
            'position' => [
                'required',
                'integer',
            ],
            'position_image' => [
                'required',
                'string',
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            
        ];
    }
}
