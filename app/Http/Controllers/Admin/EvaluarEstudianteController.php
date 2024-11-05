<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Actividad;
use App\Models\Asignaturas;
use App\Models\Valoracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EvaluarEstudianteController extends Controller
{



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
            'nota' => 'required|int|max:5',
            'actividad_id' => 'required|int',
            'estudiante_id' => 'required|int'
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
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
    
        return response()->json($valoracion, 201);
    }
    
}



