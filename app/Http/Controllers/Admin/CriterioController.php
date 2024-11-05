<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Criterio;
use App\Models\RA;
use Illuminate\Http\Request;

class CriterioController extends Controller
{
    /**
     * Muestra el formulario de creación de un criterio para un RA específico.
     */
    public function create($ra_id)
    {
        // Obtener el Resultado de Aprendizaje (RA) con el ID dado
        $ra = RA::findOrFail($ra_id);

     

        // Retornar la vista de creación de criterio, pasando el RA
        return view('rubricas.criterio.criteriocreate', compact('ra'));
    }

    /**
     * Almacena un nuevo criterio en la base de datos.
     */
    public function store(Request $request, $ra_id)
    {
        // Validar los datos de entrada
        $request->validate([
            'descripcion' => 'required|string',
        ]);

        // Crear el criterio y asociarlo al RA específico
        Criterio::create([
            'descripcion' => $request->input('descripcion'),
            'ra_id' => $ra_id,
        ]);

        // Redirigir a la vista del diseñador de rúbrica con un mensaje de éxito
        return redirect()->route('rubrica.editor', ['id' => RA::findOrFail($ra_id)->rubrica->asignatura_id])
                         ->with('success', 'Criterio creado con éxito.');
    }


    public function edit($asignatura,$id)
{
    // Obtener el criterio por su ID
    $criterio = Criterio::findOrFail($id);

    // Retornar la vista de edición, pasando el criterio a la vista
    return view('rubricas.criterio.criterio_updat', compact('criterio'));
}
public function update(Request $request, $id)
{
    // Validar los datos de entrada
    $request->validate([
        'descripcion' => 'required|string',
    ]);

    // Buscar el criterio y actualizarlo
    $criterio = Criterio::findOrFail($id);
    $criterio->update([
        'descripcion' => $request->input('descripcion'),
    ]);

    // Redirigir a la vista anterior o a la lista de criterios con un mensaje de éxito
    return redirect()->route('rubrica.editor', ['id' => $criterio->ra->rubrica->asignatura_id])
                     ->with('success', 'Criterio actualizado con éxito.');
}


public function destroy($id)
{
    $criterio = Criterio::findOrFail($id);
    $criterio->delete();

    return response()->json(['success' => 'Elemento eliminado con éxito.']);
}

}
