<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Actividad;
use App\Models\Asignaturas;
use App\Models\Estudiantes;
use App\Models\niveles_desempeno_actividad;
use App\Models\SelectCriterioEstudent;
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
        $criterios = $actividad->rubrica->criterios;
        $seleccionados = SelectCriterioEstudent::where('estudiante_id', $id)
        ->whereIn('criterio_id', $criterios->pluck('id')) // Solo los criterios de la rúbrica
        ->get();


        // Retorna la vista de evaluación, pasando los estudiantes y la materia
        return view('admin.evaluar_actividad', compact('actividad', 'materia', 'estudiante', 'seleccionados'));
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




    public function storeSelectCriterioEstudent(Request $request){

        try {
            $validated = $request->validate([
                'usuario_id' => 'required|exists:estudiantes,id',
                'criterio_id' => 'required|exists:criterios_actividad,id',
                'rubrica_id' => 'required|exists:rubrica_actividad,id',
                'nivel_desempeno_id' => 'required|exists:niveles_desempeno_actividad,id',
            ]);

            $seleccionActual = SelectCriterioEstudent::where('criterio_id', $validated['criterio_id'] )
            ->where('estudiante_id', $validated['usuario_id'])
            ->first();

            if ($seleccionActual) {

                $seleccionActual->delete();
            }




            $registro = SelectCriterioEstudent::updateOrCreate(
                ['estudiante_id' => $validated['usuario_id'],
                'criterio_id' => $validated['criterio_id'],
                'rubrica_actividad_id' =>   $validated['rubrica_id'] ,
                'nivel_desempeno_id' => $validated['nivel_desempeno_id']

            ],

            );

            return response()->json(['success' => true, 'data' => $registro]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }


    public function getNoteStudent($actividad, $student){
        // Maximo de puntos posibles
        $rubricaActividad = Actividad::with('rubrica.nivelesDesempeno')->findOrFail($actividad);
        $criteriosAmount = $rubricaActividad->rubrica->criterios->count();
        $maxNivel = $rubricaActividad->rubrica->nivelesDesempeno->sortByDesc('puntos')->first();
        $pointMax = $criteriosAmount * $maxNivel->puntos;
        $rubrica = $rubricaActividad->rubrica->id;


        $students = SelectCriterioEstudent::where('estudiante_id', $student)
        ->where('rubrica_actividad_id', $rubrica)
        ->get();


        $note = 0;
        foreach ($students as $key => $value) {


            $nivel = niveles_desempeno_actividad::find($value->nivel_desempeno_id);

            if ($nivel) {

                $note+= $nivel->puntos ;
            }
        }

        $noteActivity = (  $note / $pointMax) * 5 ;

$valoracion = Valoracion::updateOrCreate(
            [
                'actividad_id' => $rubricaActividad->id,
                'estudiante_id' => $student
            ],
            [
                'nota' => $noteActivity
            ]
        );
        return response()->json($noteActivity);

    }


}



