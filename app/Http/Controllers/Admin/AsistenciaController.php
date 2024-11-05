<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Asignaturas;
use App\Models\Asistencia;
use App\Models\Estudiantes;
use App\Models\QrAsistencias;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Prologue\Alerts\Facades\Alert;

class AsistenciaController extends Controller
{
    public function index($asignatura_id)
    {
           // Obtener la asignatura específica con asistencias
           $asignatura = Asignaturas::with('asistencias')->findOrFail($asignatura_id);

           // Cargar estudiantes usando el código estudiantil
           $asistenciasConEstudiantes = $asignatura->asistencias->map(function ($asistencia) {
               $estudiante = Estudiantes::where('codigo_estudiantil', $asistencia->codigo_estudiantil)->first();
               $asistencia->estudiante = $estudiante; // Agregar el estudiante a la asistencia
               return $asistencia;
           });
   
           return view('asistencia.index', compact('asignatura', 'asistenciasConEstudiantes'));
       
    }


    public function mostrarFormulario($token)
    {
        $qrAsistencia = QrAsistencias::where('token', $token)
                                    ->where('fecha_inicio', '<=', Carbon::now())
                                    ->where('fecha_fin', '>=', Carbon::now())
                                    ->first();

        if (!$qrAsistencia) {
            return response()->json(['message' => 'Código QR inválido o expirado.'], 400);
        }

        return view('asistencia.form', compact('qrAsistencia'));
    }

    public function registrarAsistencia(Request $request, $token)
    {
        $qrAsistencia = QrAsistencias::where('token', $token)
                                    ->where('fecha_inicio', '<=', Carbon::now())
                                    ->where('fecha_fin', '>=', Carbon::now())
                                    ->first();

        if (!$qrAsistencia) {
            Alert::success('¡Registro exitoso!')->flash();
            return redirect()->back();
        }


        // Obtener los datos de la solicitud

   
    
        // Validar que el estudiante exista
        $estudiante = Estudiantes::where('codigo_estudiantil', $request->codigo_estudiantil)->first();
        if (!$estudiante) {
             Alert::success('¡Registro exitoso!')->flash();
             return redirect()->back();
            }
    
        // Validar que la asignatura exista
        $asignatura = Asignaturas::find($qrAsistencia->asignatura_id);
        if (!$asignatura) {
            return response()->json(['error' => 'La asignatura no existe.'], 404);
        }
    
        // Validar que el estudiante esté inscrito en la asignatura
        $pertenece = $asignatura->students()->where('codigo_estudiantil', $request->codigo_estudiantil)->exists();

        if (!$pertenece) {
            return response()->json(['error' => 'El estudiante no está inscrito en esta asignatura.'], 403);
        }
        
        // Registrar la asistencia
        Asistencia::updateOrCreate(
            [
                'asignatura_id' => $qrAsistencia->asignatura_id,
                'codigo_estudiantil' => $request->codigo_estudiantil,
                'fecha' => Carbon::now()->toDateString(),
            ],
            ['asistencia' => true]
        );
        

        return response()->json(['message' => 'Asistencia registrada con éxito.'], 200);
    }
}
