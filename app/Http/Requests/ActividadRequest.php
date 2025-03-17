<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Actividad;

class ActividadRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta solicitud.
     *
     * @return bool
     */
    public function authorize()
    {
        return backpack_auth()->check();
    }

    /**
     * Obtiene las reglas de validación que se aplicarán a la solicitud.
     *
     * @return array
     */
    public function rules()
    {
        // Obtenemos el ID de la actividad si estamos en un update
        $actividadId = $this->route('id');

        // Sumatoria de ponderaciones excluyendo la actual (en caso de update)
        $suma_ponderaciones = Actividad::where('asignatura_id', $this->asignatura_id)
            ->where('ra_id', $this->ra_id)
            ->when($actividadId, function ($query) use ($actividadId) {
                return $query->where('id', '!=', $actividadId); // Excluye la actual si estamos actualizando
            })
            ->sum('ponderacion');

        // Espacio restante antes de agregar la nueva ponderación
        $espacio_restante = 100 - $suma_ponderaciones;

        return [
            'nombre' => 'required|string|min:3|max:255',
            'fecha' => 'required|date',
            'descripcion' => 'nullable',
            'ponderacion' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
                function ($attribute, $value, $fail) use ($suma_ponderaciones, $espacio_restante) {
                    if ($suma_ponderaciones == 100) {
                        $fail('No puedes agregar más actividades, ya que la suma total de ponderaciones es 100%.');
                    } elseif ($value > $espacio_restante) {
                        $fail("La ponderación ingresada excede el 100%. Solo puedes agregar hasta $espacio_restante%.");
                    }
                },
            ],
            'ra_id' => 'required|exists:ra,id',
            'asignatura_id' => 'required|exists:asignaturas,id',
        ];
    }

    /**
     * Obtiene nombres personalizados para los atributos.
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
     * Obtiene los mensajes de validación personalizados.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'nombre.required' => 'Debes ingresar un Nombre para la actividad.',
            'nombre.min' => 'El Nombre de la actividad debe tener al menos 3 caracteres.',
            'fecha.required' => 'Debes seleccionar una Fecha para la actividad.',
            'fecha.date' => 'La Fecha ingresada no es válida.',
            'ponderacion.required' => 'Debes ingresar una Ponderación para la actividad.',
            'ponderacion.numeric' => 'La Ponderación debe ser un número válido.',
            'ponderacion.min' => 'La Ponderación no puede ser menor que 0%.',
            'ponderacion.max' => 'La Ponderación no puede superar el 100%.',
            'ra_id.required' => 'Debes seleccionar un Resultado de aprendizaje.',
            'ra_id.exists' => 'El Resultado de aprendizaje seleccionado no existe en el sistema.',
            'asignatura_id.required' => 'Debes seleccionar una Asignatura.',
            'asignatura_id.exists' => 'La Asignatura seleccionada no existe en el sistema.',
        ];
    }
}
