<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AsignaturasRequest extends FormRequest
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
        return [
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|regex:/^\d+$/|unique:asignaturas,codigo,' . $this->route('id'),
            'competencia' => 'required|string',
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
            'competencia.required' => 'La competencia es obligatoria.',
            'descripcion_competencia.required' => 'La descripción de la competencia es obligatoria.',
            'justificacion.required' => 'La justificación es obligatoria.',
            'facultad_id.required' => 'La facultad es obligatoria.',
            'facultad_id.exists' => 'La facultad seleccionada no es válida.',
            'carrera_id.required' => 'La carrera es obligatoria.',
            'carrera_id.exists' => 'La carrera seleccionada no es válida.',
            'prerequisitos.required' => 'Los prerrequisitos son obligatorios.',
            'correquisitos.required' => 'Los correquisitos son obligatorios.',
            'area_formacion.required' => 'El área de formación es obligatoria.',
            'tipo_asignatura.required' => 'El tipo de asignatura es obligatorio.',
            'nivel_formacion.required' => 'El nivel de formación es obligatorio.',
            'modalidad.required' => 'La modalidad es obligatoria.',
            'creditos_academicos.required' => 'Los créditos académicos son obligatorios.',
            'creditos_academicos.integer' => 'Los créditos académicos deben ser un número entero.',
            'creditos_academicos.min' => 'Los créditos académicos deben ser al menos 1.',
            'creditos_academicos.max' => 'Los créditos académicos no pueden exceder los 15.',
            'horas_presenciales.required' => 'Las horas presenciales son obligatorias.',
            'horas_presenciales.integer' => 'Las horas presenciales deben ser un número entero.',
            'horas_independientes.required' => 'Las horas independientes son obligatorias.',
            'horas_independientes.integer' => 'Las horas independientes deben ser un número entero.',
            'horas_totales.required' => 'Las horas totales son obligatorias.',
            'horas_totales.integer' => 'Las horas totales deben ser un número entero.',
            'periodo_academico.required' => 'El periodo académico es obligatorio.',
            'periodo_academico.integer' => 'El periodo académico debe ser un número entero.',
            'periodo_academico.min' => 'El periodo académico debe ser al menos 1.',
            'periodo_academico.max' => 'El periodo académico no puede ser mayor a 10.',
            'numero_estudiantes.required' => 'El número de estudiantes es obligatorio.',
            'numero_estudiantes.integer' => 'El número de estudiantes debe ser un número entero.',
            'numero_estudiantes.min' => 'Debe haber al menos un estudiante inscrito.'
        ];
    }
}
