<?php

namespace App\Http\Controllers;

use App\Exports\DynamicReporteExport;
use App\Exports\ReporteNotasExport;
use App\Models\Asignaturas;
use App\Models\Estudiantes;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportesController extends Controller
{
    function index()
    {
        if (!backpack_auth()->check() || !backpack_user()->hasRole('docente')) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }


        $user = backpack_user()->id;

        $asignaturas = Asignaturas::where('user_id', $user)->get();

        return view('reportes.index', compact('asignaturas'));
    }

    function estudianteReport($id)
    {

        if (!backpack_auth()->check() || !backpack_user()->hasRole('docente')) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        $asignatura = Asignaturas::find($id);
        $estudiantes = $asignatura->students;
        return view('reportes.estudiante', compact('asignatura'));
    }




    function  graph($id, $student)
    {
        if (!backpack_auth()->check() || !backpack_user()->hasRole('docente')) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
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

                $actividades_nota   [$actividades->nombre] = $valoracion->nota ?? 0;
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


    public function exportReporte($asignaturaId)
    {
        $asignatura = Asignaturas::with([
            'actividades.valoraciones.estudiante'
        ])->findOrFail($asignaturaId);

        $datos = [];

        // Recorremos cada actividad de la asignatura
        foreach ($asignatura->actividades as $actividad) {
            // Recorremos cada valoración (evaluación) de la actividad
            foreach ($actividad->valoraciones as $valoracion) {
                // Obtenemos el estudiante relacionado
                $estudiante = $valoracion->estudiante;

                $datos[] = [
                    $estudiante->nombre,   // Nombre del estudiante
                    $estudiante->codigo_estudiantil,   // Código del estudiante
                    $actividad->nombre,    // Nombre de la actividad
                    $valoracion->nota      // Nota obtenida en la actividad
                ];
            }
        }




        // Opcional: Agregar una fila de resumen (por ejemplo, un promedio general)
        if (count($datos) > 0) {
            $sum = array_sum(array_column($datos, 3));
            $promedio = round($sum / count($datos), 2);
            $datos[] = ['Promedio general', '', '', $promedio];
        }

        // Retornamos el Excel usando la clase DynamicReporteExport
        return Excel::download(
            new ReporteNotasExport($asignatura, $datos),
            'reporte_asignatura_' . $asignaturaId . '.xlsx'
        );
    }
}
