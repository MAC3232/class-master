<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\RA;

class RARequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'ponderacion' => 'required|numeric|min:0|max:100',
            'corte' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.max' => 'El nombre no puede superar los 255 caracteres.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'ponderacion.required' => 'La ponderación es obligatoria.',
            'ponderacion.numeric' => 'La ponderación debe ser un número.',
            'ponderacion.min' => 'La ponderación debe ser al menos 0.',
            'ponderacion.max' => 'La ponderación no puede superar 100.',
        ];
    }

    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Obtener la rúbrica a la que pertenece el RA
            $rubricaId = $this->route('id_rubrica');

            // Obtener la suma actual de las ponderaciones para los RA de esta rúbrica
            $totalPonderacionActual = RA::where('rubrica_id', $rubricaId)->sum('ponderacion');

            // Sumar la nueva ponderación ingresada
            $nuevaPonderacionTotal = $totalPonderacionActual + $this->input('ponderacion');

            // Validar que la nueva sumatoria no exceda el 100%
            if ($nuevaPonderacionTotal > 100) {
                // Generar un mensaje de error si la suma supera el 100%
                $validator->errors()->add('ponderacion', 'Error: la sumatoria de ponderaciones supera el 100%');

            } else {
                // Calcular la ponderación faltante para alcanzar el 100%
                $ponderacionSugerida = 100 - $totalPonderacionActual;
                session()->flash('warning', "Sugerencia: para alcanzar el 100%, use una ponderación de $ponderacionSugerida%");
            }
        });
    }
}
