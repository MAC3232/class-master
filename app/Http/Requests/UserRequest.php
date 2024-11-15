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
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user,
            'password' => 'required|string|min:8', // Sin confirmación de contraseña
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => 'Nombre',
            'email' => 'Correo electrónico',
            'password' => 'Contraseña',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'El campo Nombre es obligatorio.',
            'name.min' => 'El Nombre debe tener al menos 3 caracteres.',
            'email.required' => 'El campo Correo electrónico es obligatorio.',
            'email.email' => 'El Correo electrónico debe ser una dirección válida.',
            'email.unique' => 'El Correo electrónico ya está en uso.',
            'password.required' => 'El campo Contraseña es obligatorio.',
            'password.min' => 'La Contraseña debe tener al menos 8 caracteres.',

        ];
    }
}
