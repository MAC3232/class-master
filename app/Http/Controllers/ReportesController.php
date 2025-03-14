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
        if (!backpack_auth()->check() || !backpack_user()->hasRole(['docente','super-admin'])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $user = backpack_user()->id;

        $asignaturas = Asignaturas::whereHas('asignaturasDocentes', function ($query) use ($user) {
            $query->where('docente_id', $user);
        })->get();

        return view('reportes.index', compact('asignaturas'));
    }


    function estudianteReport($id)
    {

        if (!backpack_auth()->check() || !backpack_user()->hasRole(['docente','super-admin'])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $asignatura = Asignaturas::with('students.user')->findOrFail($id);
        $estudiantes = $asignatura->students;

        return view('reportes.estudiante', compact('asignatura','estudiantes'));
    }




    function  graph($id, $student)
    {

        if (!backpack_auth()->check() || !backpack_user()->hasRole(['docente','super-admin'])) {

            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        $corte = 1;
        $corteRequest = request('corte');
        if (isset($corteRequest)) {
            $corte = $corteRequest;
        }

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

$student = Estudiantes::with(['carrera', 'user'])->findOrFail($student);





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
    public function graphGeneral($id)
    {
        // Verificar acceso: solo docentes
        if (!backpack_auth()->check() || !backpack_user()->hasRole(['docente','super-admin'])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        // Obtener el corte seleccionado (por defecto 1)
        $corte = request('corte') ? request('corte') : 1;

        // Cargar la asignatura con sus relaciones filtradas por corte
        $asignatura = \App\Models\Asignaturas::where('id', $id)
            ->with(['rubrica.ra' => function($query) use ($corte) {
                $query->where('corte', $corte);
            }])
            ->first();

        if (!$asignatura) {
            abort(404, "Asignatura no encontrada");
        }

        // Inicializar contadores generales y arreglo para detalle por actividad
        $aprobadas = 0;
        $no_aprobadas = 0;
        $actividades_porcentaje = [];

        if (!empty($asignatura->rubrica) && !empty($asignatura->rubrica->ra)) {
            foreach ($asignatura->rubrica->ra as $ra) {
                if (!empty($ra->actividades)) {
                    foreach ($ra->actividades as $actividad) {
                        $valoraciones = $actividad->valoraciones;
                        $total_eval = count($valoraciones);

                        // Contar evaluaciones aprobadas (nota >= 3.0)
                        $aprobadas_eval = 0;
                        if ($total_eval > 0) {
                            $aprobadas_eval = $valoraciones->filter(function($valoracion) {
                                return $valoracion->nota >= 3.0;
                            })->count();
                        }

                        // Evaluaciones no aprobadas
                        $no_aprobadas_eval = $total_eval - $aprobadas_eval;

                        // Calcular porcentajes para la actividad
                        $porcentaje_aprobacion = $total_eval > 0 ? ($aprobadas_eval / $total_eval * 100) : 0;
                        $porcentaje_no_aprobacion = $total_eval > 0 ? ($no_aprobadas_eval / $total_eval * 100) : 0;

                        // Agregar datos de la actividad al arreglo
                        $actividades_porcentaje[] = [
                            'nombre'                  => $actividad->nombre,
                            'total'                   => $total_eval,
                            'aprobadas'               => $aprobadas_eval,
                            'no_aprobadas'            => $no_aprobadas_eval,
                            'porcentaje_aprobadas'    => $porcentaje_aprobacion,
                            'porcentaje_no_aprobadas' => $porcentaje_no_aprobacion,
                        ];

                        // Sumar a los contadores generales
                        $aprobadas += $aprobadas_eval;
                        $no_aprobadas += $no_aprobadas_eval;
                    }
                }
            }
        }

        // Calcular porcentajes generales
        $total = $aprobadas + $no_aprobadas;
        $porcentaje_aprobadas = $total > 0 ? ($aprobadas / $total * 100) : 0;
        $porcentaje_no_aprobadas = $total > 0 ? ($no_aprobadas / $total * 100) : 0;

        return view('reportes.graph_general', compact(
            'asignatura',
            'porcentaje_aprobadas',
            'porcentaje_no_aprobadas',
            'corte',
            'actividades_porcentaje',
            'aprobadas',
            'no_aprobadas'
        ));
    }


}
