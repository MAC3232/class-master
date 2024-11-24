<?php

namespace App\Http\Controllers;

use App\Models\Asignaturas;
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

        $notas = [3.5, 4.0, 2.8, 4.5]; // Notas de actividades
        $resultados = ['Excelente' => 3, 'Aprobado' => 5, 'Reprobado' => 2]; // Resultados de aprendizaje
        $promedio = 3.75; // Promedio general



        $asignatura = Asignaturas::with('actividades.valoraciones')->findOrFail($id);


        foreach ($asignatura->actividades as  $value) {
            dd($value->valoraciones->findOrFail($student));
        }
        return view('reportes.graph', compact('notas', 'resultados', 'promedio'));
    }
}
