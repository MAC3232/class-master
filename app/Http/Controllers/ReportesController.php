<?php

namespace App\Http\Controllers;

use App\Models\Asignaturas;
use App\Models\Estudiantes;
use Illuminate\Http\Request;

class ReportesController extends Controller
{
    function index()
    {

        $user = backpack_user()->id;

        $asignaturas = Asignaturas::where('user_id', $user)->get();

        return view('reportes.index', compact('asignaturas'));
    }

    function estudianteReport($id)
    {

        $asignatura = Asignaturas::find($id);
        $estudiantes = $asignatura->students;
        return view('reportes.estudiante', compact('asignatura'));
    }




    function  graph($id, $student)
    {
        $corte = 1;
        $corteRequest = request('corte');
        if (isset($corteRequest)) {
            $corte = $corteRequest;
        }




        $notas = [3.5, 4.0, 2.8, 4.5]; // Notas de actividades
        $promedio = 3.75; // Promedio general



        $asignatura = Asignaturas::where('id', $id)
        ->with(['rubrica.ra' => function($query) use ($corte) {
            $query->where('corte', $corte); // Filtra los RA por corte
        }])
        ->first();

        $actividades_nota = [];
        foreach ($asignatura->rubrica->ra as $key => $value) {

            foreach ($value->actividades as $key => $actividades) {
                $valoracion= $actividades->valoraciones->where('estudiante_id', $student)->first();

                $actividades_nota   [$actividades->nombre] = $valoracion->nota;
            }

        }

        $resultados =$actividades_nota ; // Resultados de aprendizaje



        // Suponiendo que una actividad está aprobada si la nota es >= 3.0
$aprobadas = 0;
$no_aprobadas = 0;

foreach ($resultados as $actividad => $nota) {
    if ($nota >= 3.0) {
        $aprobadas++;
    } else {
        $no_aprobadas++;
    }
}

// Calcula el total de actividades


    $total_actividades = $aprobadas + $no_aprobadas;

// Calcula los porcentajes


if ($aprobadas == 0) {
    $porcentaje_aprobadas = 0;
}else{

    $porcentaje_aprobadas = ($aprobadas / $total_actividades) * 100;

}
if($no_aprobadas== 0){
    $porcentaje_no_aprobadas = 0;
}else{

    $porcentaje_no_aprobadas = ($no_aprobadas / $total_actividades) * 100;
}

$student = Estudiantes::all()->findOrFail($student);





        return view('reportes.graph', compact('notas','asignatura','student','porcentaje_aprobadas','porcentaje_no_aprobadas', 'resultados', 'promedio'));
    }
}
