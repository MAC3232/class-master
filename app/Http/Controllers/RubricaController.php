<?php

namespace App\Http\Controllers;

use App\Models\Asignaturas;
use App\Models\Rubrica;
use Illuminate\Http\Request;

class RubricaController extends Controller
{
    public function showDisenador($id)
{

    $asignatura = Asignaturas::with('rubrica')->findOrFail($id);
    
    // Verificar si tiene una rúbrica
    $tieneRubrica = $asignatura->rubrica !== null;
    
    
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
