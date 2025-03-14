<?php

namespace App\Http\Controllers;

use App\Models\Asignaturas;
use App\Models\Rubrica;
use Illuminate\Http\Request;

class RubricaController extends Controller
{
    public function __construct()
    {
        // Aplica el middleware 'role:admin' a todas las acciones de este controlador
        if (!backpack_auth()->check() || !backpack_user()->hasRole(['docente','super-admin'])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

    }
    public function showDisenador($id)
{

    $asignatura = Asignaturas::with('rubrica')->findOrFail($id);

    // Verificar si tiene una rúbrica
    $tieneRubrica = $asignatura->rubrica !== null;

    if (!$tieneRubrica) {
        $asignatura->rubrica()->create([
            'nombre' => 'Rúbrica de ' . $asignatura->nombre,
            'asignatura_id' => 'Rúbrica de ' . $asignatura->id,
            'descripcion' => 'Descripción predeterminada para la rúbrica de esta asignatura.',
        ]);

        // Recargar la relación para actualizar el estado
        $asignatura->load('rubrica');

        // Actualizar la variable para reflejar el cambio
        $tieneRubrica = true;
    }
    // Obtenemos los datos de la asignatura
    $asignatura = Asignaturas::findOrFail($id);

    // Retornamos la vista del diseñador de rúbrica con los datos de la asignatura
    return view('rubricas.disenador', compact('asignatura', 'tieneRubrica'));
}


public function editor($id)
    {
        // Retorna la vista del editor de rúbrica
    $asignatura = Asignaturas::findOrFail($id);
    // dd($asignatura->rubrica->ra);


        return view('rubricas.rubrica_editor', compact('asignatura'));
    }
public function create($id)
    {
        // Retorna la vista del editor de rúbrica
    $asignatura = Asignaturas::findOrFail($id);

        return view('rubricas.rubrica_crear', compact('asignatura'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'asignatura_id' => 'unique:rubricas|required|exists:asignaturas,id',
        ]);

        Rubrica::create([
            'nombre' => $request->input('nombre'),
            'asignatura_id' => $request->input('asignatura_id'),
        ]);

        return redirect()->route('rubrica.disenador', $request->input('asignatura_id'))->with('success', 'Rúbrica creada con éxito.');
    }


}
