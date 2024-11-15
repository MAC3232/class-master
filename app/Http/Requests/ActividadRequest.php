<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActividadRequest extends FormRequest
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
            'nombre' => 'required|string|min:3|max:255',
            'fecha' => 'required|date',
            'ponderacion' => 'required|numeric|min:0|max:100',
            'ra_id' => 'required|exists:ra,id',
            'asignatura_id' => 'required|exists:asignaturas,id',
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
            'nombre' => 'Nombre de la actividad',
            'fecha' => 'Fecha de la actividad',
            'ponderacion' => 'Ponderación',
            'ra_id' => 'Resultado de aprendizaje',
            'asignatura_id' => 'Asignatura',
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
            'nombre.required' => 'El campo Nombre de la actividad es obligatorio.',
            'nombre.min' => 'El Nombre de la actividad debe tener al menos 3 caracteres.',
            'fecha.required' => 'El campo Fecha de la actividad es obligatorio.',
            'fecha.date' => 'La Fecha de la actividad debe ser una fecha válida.',
            'ponderacion.required' => 'El campo Ponderación es obligatorio.',
            'ponderacion.numeric' => 'La Ponderación debe ser un número.',
            'ponderacion.min' => 'La Ponderación no puede ser menor que 0.',
            'ponderacion.max' => 'La Ponderación no puede exceder 100.',
            'ra_id.required' => 'El campo Resultado de aprendizaje es obligatorio.',
            'ra_id.exists' => 'El Resultado de aprendizaje seleccionado no existe.',
            'asignatura_id.required' => 'El campo Asignatura es obligatorio.',
            'asignatura_id.exists' => 'La Asignatura seleccionada no existe.',
        ];
    }
}
