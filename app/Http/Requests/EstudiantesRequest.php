<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EstudiantesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Allow only if the user is logged in
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
            'nombre' => 'required|string|max:255',
            'cedula' => 'required|string|max:10|unique:estudiantes,cedula',
            'correo' => 'required|email|max:255|unique:estudiantes,correo',
            'codigo_estudiantil' => 'required|string|max:20|unique:estudiantes,codigo_estudiantil',
            'carrera_id' => 'required|exists:carreras,id',
        ];
    }

    /**
     * Get custom attribute names.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'nombre' => 'Nombre del estudiante',
            'cedula' => 'Cédula',
            'correo' => 'Correo electrónico',
            'codigo_estudiantil' => 'Código estudiantil',
            'carrera_id' => 'Carrera',
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
            'nombre.required' => 'El campo Nombre del estudiante es obligatorio.',
            'cedula.required' => 'El campo Cédula es obligatorio.',
            'cedula.unique' => 'La Cédula ya está registrada.',
            'correo.required' => 'El campo Correo electrónico es obligatorio.',
            'correo.unique' => 'El Correo electrónico ya está registrado.',
            'codigo_estudiantil.required' => 'El campo Código estudiantil es obligatorio.',
            'codigo_estudiantil.unique' => 'El Código estudiantil ya está registrado.',
            'carrera_id.required' => 'El campo Carrera es obligatorio.',
            'carrera_id.exists' => 'La Carrera seleccionada no es válida.',
        ];
    }
}
