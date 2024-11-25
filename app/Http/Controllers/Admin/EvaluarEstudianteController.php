<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Actividad;
use App\Models\Asignaturas;
use App\Models\Valoracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Prologue\Alerts\Facades\Alert;

use function Laravel\Prompts\alert;

class EvaluarEstudianteController extends Controller
{
    public function __construct()
    {
        // Aplica el middleware 'role:admin' a todas las acciones de este controlador
        if (!backpack_auth()->check() || !backpack_user()->hasRole('docente')) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

    }



    public function evaluarEstudiantes($actividad, Request $request)
    {
        $isAsignatura = $request->input('is_asignatura');

        if($isAsignatura){
        $asignatura = Asignaturas::with('actividades')->findOrFail($actividad);
        $estudiantes = $asignatura->students;
        return view('admin.evaluar_estudiantes', compact('estudiantes', 'asignatura', 'isAsignatura'));

        }

        // Materia y estudiantes

        $actividad = Actividad::with('asignatura')->findOrFail($actividad);


        $asignatura = $actividad->asignatura;
        $estudiantes = $asignatura->students;



        // Retorna la vista de evaluación, pasando los estudiantes y la materia
        return view('admin.evaluar_estudiantes', compact('estudiantes', 'asignatura', 'actividad', 'isAsignatura'));
    }

    public function evaluar($asignatura, $id)
    {


        $materia = Asignaturas::with('actividades')->findOrFail($asignatura);
        $actividades = $materia->actividades;
        $estudiante = $materia->students->findOrFail($id);


        // Retorna la vista de evaluación, pasando los estudiantes y la materia
        return view('admin.estudiante_evaluar', compact('actividades', 'materia', 'estudiante'));
    }

    public function Evaluar_actividad($actividad, $id)
    {


        $actividad = Actividad::with(['asignatura', 'rubrica.criterios', 'valoraciones.estudiante'])->findOrFail($actividad);
        if ($actividad->rubrica == null) {
            $actividad->rubrica()->create([
                'nombre' => 'Rúbrica de ' . $actividad->nombre,
                'actividad_id' => $actividad->id,
                'descripcion' => 'Descripción predeterminada para la rúbrica de esta actividad.',
            ]);

            // Recargar la relación para actualizar el estado
            $actividad->load('rubrica');

        }

        $materia = $actividad->asignatura;
        $estudiante = $materia->students->findOrFail($id);


        // Retorna la vista de evaluación, pasando los estudiantes y la materia
        return view('admin.evaluar_actividad', compact('actividad', 'materia', 'estudiante'));
    }
    public function actividades($actividad, $id)
    {


        $asignatura = Asignaturas::with([ 'rubrica.ra' ])->findOrFail($actividad);


        $estudiante = $asignatura->students->findOrFail($id);
        $tieneRubrica = $asignatura->rubrica !== null;

        // Retorna la vista de evaluación, pasando los estudiantes y la materia
        return view('admin.actividad_estudiantes', compact('actividad', 'asignatura', 'estudiante', 'tieneRubrica'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nota' => 'required|numeric|max:5',
            'actividad_id' => 'required|int',
            'estudiante_id' => 'required|int'
        ]);

        if ($validator->fails()) {
        Alert::error('No se pudo evaluar, el maximo es de 5' )->flash();
            return response()->json('error');
        }

        // Actualizar o crear la valoración si ya existe una para el mismo estudiante y actividad
        $valoracion = Valoracion::updateOrCreate(
            [
                'actividad_id' => $request->input('actividad_id'),
                'estudiante_id' => $request->input('estudiante_id')
            ],
            [
                'nota' => $request->input('nota')
            ]
        );

        Alert::success('Evaluado correctamente en '.$request->input('nota') )->flash();
        return response()->json($valoracion, 201);
    }

}



