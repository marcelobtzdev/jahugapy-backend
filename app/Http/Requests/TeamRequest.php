<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeamRequest extends FormRequest
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
            case 'teams.store':
                $rules = $this->store();
                break;
            case 'teams.update':
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
            'name' => [
                'required',
                'string'
            ],
            'mode_id' => [
                'required',
                'integer'
            ],
            'members' => [
                'required',
                'array',
            ],
            'members.*.activision_id' => [
                'required',
                'string',
                'unique:users,activision_id'
            ]
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
            'name' => [
                'required',
                'string'
            ],
            'mode_id' => [
                'required',
                'integer'
            ],
            'members' => [
                'required',
                'array',
            ],
            'members.*.activision_id' => [
                'required',
                'string',
                'unique:users,activision_id'
            ]
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
            'members.*.activision_id.required' => 'Debes ingresar todos los miembros de tu equipo',
            'members.*.activision_id.unique' => 'Uno de los miembros ingresados ya ha sido registrado',
        ];
    }
}
