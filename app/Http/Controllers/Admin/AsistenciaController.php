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

    public function tomarAsistencia($asignatura_id)
{
    // 1. Buscar la asignatura
    $asignatura = Asignaturas::findOrFail($asignatura_id);

    // 2. Obtener la lista de estudiantes inscritos en la asignatura
    //    Asumiendo que tienes una relación 'students()' en el modelo Asignaturas
    //    Si no, ajusta según tu lógica (por ejemplo, si guardas a los estudiantes de otra manera)
    $estudiantes = $asignatura->students; // O como tengas la relación

    // 3. Pasar la fecha de hoy (o la que quieras usar)
    $fechaHoy = now()->format('Y-m-d');

    // 4. Retornar una vista con el formulario para marcar asistencia
    return view('asistencia.tomar', compact('asignatura', 'estudiantes', 'fechaHoy'));
}

public function guardarAsistencia(Request $request, $asignatura_id)
{
    // 1. Validar los datos
    $data = $request->validate([
        'fecha' => 'required|date',
        'attendance' => 'required|array',
        // Por ejemplo: attendance[codigo_estudiantil] => "true" o "false"
    ]);

    $asignatura = Asignaturas::findOrFail($asignatura_id);
    $fecha = $data['fecha'];
    $attendanceArray = $data['attendance'];

    // 2. Recorrer cada estudiante y registrar/actualizar la asistencia
    foreach ($attendanceArray as $codigoEstudiantil => $valorAsistencia) {
        // $valorAsistencia podría ser "1" (presente) o "0" (ausente),
        // o un booleano. Ajusta según tu form.

        Asistencia::updateOrCreate(
            [
                'asignatura_id' => $asignatura->id,
                'codigo_estudiantil' => $codigoEstudiantil,
                'fecha' => $fecha,
            ],
            [
                'asistencia' => $valorAsistencia, // true/false o 1/0
            ]
        );
    }

    // 3. Redirigir con un mensaje de éxito
    Session::flash('success', '¡Asistencia guardada correctamente!');
    return redirect()->route('asistencia.index', $asignatura_id);
}

public function tomar($asignatura_id)
{
    $asignatura = Asignaturas::findOrFail($asignatura_id);

    // Carga los estudiantes con su relación 'user'
    $estudiantes = $asignatura->students()->with('user')->get();

    // Haz un dd() para ver si vienen los datos correctos
   // dd($estudiantes->toArray());

    return view('asistencia.tomar', compact('asignatura', 'estudiantes'));
}
public function guardar(Request $request, $asignatura_id)
{
    // Validamos que se reciba el ID de la asignatura
    $data = $request->validate([
        'asistencia' => 'nullable|array',
    ]);

    // Obtenemos la asignatura (asegúrate de que la relación con estudiantes esté definida)
    $asignatura = \App\Models\Asignaturas::findOrFail($asignatura_id);

    // La fecha de hoy, para registrar la asistencia
    $fecha = date('Y-m-d');

    // Obtenemos la lista de estudiantes inscritos en la asignatura
    // Ajusta según tu lógica. Por ejemplo, si usas una relación:
    $estudiantes = $asignatura->students;

    // El array enviado: [ codigo_estudiantil => '1' ]
    $asistenciaEnviada = $data['asistencia'] ?? [];

    // Iteramos sobre todos los estudiantes inscritos
    foreach ($estudiantes as $estudiante) {
        // Usamos el código_estudiantil para identificar el registro
        $codigo = $estudiante->codigo_estudiantil;

        // Si existe en el array, se marcó como presente; de lo contrario, ausente (0)
        $valor = isset($asistenciaEnviada[$codigo]) ? 1 : 0;

        \App\Models\Asistencia::updateOrCreate(
            [
                'asignatura_id'     => $asignatura->id,
                'codigo_estudiantil'=> $codigo,
                'fecha'             => $fecha,
            ],
            [
                'asistencia'        => $valor,
            ]
        );
    }

    return redirect()->route('asistencias.index',['asignatura_id'=>$asignatura_id])->with('success', '¡Asistencia guardada correctamente!');
}






}
