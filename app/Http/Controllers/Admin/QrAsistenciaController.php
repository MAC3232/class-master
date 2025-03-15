<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QrAsistencias;
use Carbon\Carbon;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;

class QrAsistenciaController extends Controller
{
    public function generarQrAsistencia($asignatura_id)
    {
        $token = Str::random(64); // Genera un token único de 64 caracteres
        $fechaInicio = Carbon::now();

        $fechaFin = $fechaInicio->copy()->addMinutes(5); // Expira en 15 minutos

        // Guardar la sesión de QR en la base de datos
        $qrAsistencia = QrAsistencias::create([
            'asignatura_id' => $asignatura_id,
            'token' => $token,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
        ]);

        // Generar el código QR con la URL única
        $qrCode = QrCode::size(300)->generate(route('asistencia.form', ['token' => $token]));

        return view('asistencia.qr', compact('qrCode', 'qrAsistencia'));
    }
}
