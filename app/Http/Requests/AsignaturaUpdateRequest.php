<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AsignaturasUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Si es necesario, cambia esta lógica para verificar si el usuario está autorizado a crear o editar asignaturas
        return true;
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
            'codigo' => 'required|integer|unique:asignaturas,codigo,' . $this->route('id'),
            'competencia' => 'required|string|max:255',
            'descripcion_competencia' => 'required|string',
            'justificacion' => 'required|string',
            'facultad_id' => 'required|exists:facultades,id',
            'carrera_id' => 'required|exists:carreras,id',
            'prerequisitos' => 'required|string|max:255',
            'correquisitos' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'area_formacion' => 'required|string|max:255',
            'tipo_asignatura' => 'required|string|in:practica,teorica,teorica con laboratorio',
            'nivel_formacion' => 'required|string|max:255',
            'modalidad' => 'required|string|in:virtual,presencial,distancia,dual',
            'creditos_academicos' => 'required|integer|min:1|max:15',
            'horas_presenciales' => 'required|integer|min:0',
            'horas_independientes' => 'required|integer|min:0',
            'horas_totales' => 'required|integer|min:0',
            'catalogo' => 'required|string|max:255',

        ];
    }

    /**
     * Get the custom messages for validation errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'nombre.required' => 'El nombre de la asignatura es obligatorio.',
            'codigo.required' => 'El código es obligatorio.',
            'codigo.unique' => 'Este código de asignatura ya está registrado.',
            'catalogo.required' => 'El catálogo es obligatorio.',
            'user_id.required' => 'El docente es obligatorio.',
            'user_id.exists' => 'El docente seleccionado no es válido.',

            'facultad_id.exists' => 'La facultad seleccionada no es válida.',
            'carrera_id.exists' => 'La carrera seleccionada no es válida.',
            'creditos_academicos.integer' => 'Los créditos académicos deben ser un número entero.',
            'creditos_academicos.min' => 'Los créditos académicos deben ser al menos 1.',
            'creditos_academicos.max' => 'Los créditos académicos no pueden exceder los 15.',
            'horas_presenciales.integer' => 'Las horas presenciales deben ser un número entero.',
            'horas_independientes.integer' => 'Las horas independientes deben ser un número entero.',
            'horas_totales.integer' => 'Las horas totales deben ser un número entero.',
        ];
    }

    /**
     * Get the attributes that are used in the validation.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'nombre' => 'nombre de la asignatura',
            'codigo' => 'código',
            'catalogo' => 'catálogo',
            'facultad_id' => 'facultad',
            'user_id' => 'docente',
            'carrera_id' => 'carrera',
            'area_formacion' => 'área de formación',
            'creditos_academicos' => 'créditos académicos',
            'modalidad' => 'modalidad',
            'type_asignatura' => 'tipo de asignatura',
            'horas_presenciales' => 'horas presenciales',
            'horas_independientes' => 'horas independientes',
            'horas_totales' => 'horas totales',
        ];
    }
}
