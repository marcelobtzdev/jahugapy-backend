<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            case 'users.register':
                $rules = $this->store();
                break;
            case 'users.update':
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
            'username' => [
                'required',
                'string',
                'unique:users,username'
            ],
            'activision_id' => [
                'required',
                'string',
                'unique:users,activision_id'
            ],
            'phone' => [
                'required',
                'string',
                'unique:users,phone'
            ],
            'password' => [
                'required',
                'string',
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
            'username' => [
                'required',
                'string',
                'unique:users,username,'.$this->route()->parameter('user')->id
            ],
            'activision_id' => [
                'required',
                'string',
                'unique:users,activision_id,'.$this->route()->parameter('user')->id
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
            'username.unique' => 'El usuario ingresado ya existe',
            'activision_id.unique' => 'El ID de Activision ingresado ya existe',
            'phone.unique' => 'El Teléfono ingresado ya existe'
        ];
    }
}
