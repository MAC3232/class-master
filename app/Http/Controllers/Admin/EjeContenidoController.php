<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\EjeContenido;
use App\Models\RA;
use Illuminate\Http\Request;

class EjeContenidoController extends Controller
{
    public function create($ra_id)
    {
        $ra = RA::findOrFail($ra_id);
        return view('ejes_contenido.create', compact('ra'));
    }

    // Almacena un nuevo eje de contenido en la base de datos
    public function store(Request $request, $ra_id)
    {
        $request->validate([
            'Contenido-'.$ra_id => 'required|string|max:255',
        ]);

        EjeContenido::create([
            'descripcion' => $request->input('Contenido-'.$ra_id),
            'ra_id' => $ra_id,
        ]);

        return redirect()->route('rubrica.editor', ['id' => RA::findOrFail($ra_id)->rubrica->asignatura_id])
        ->with('success', 'Criterio creado con éxito.');
    }

    // Muestra el formulario de edición de un eje de contenido específico
    public function edit($id)
    {
        $ejeContenido = EjeContenido::findOrFail($id);
        return view('ejes_contenido.edit', compact('ejeContenido'));
    }

    // Actualiza el eje de contenido en la base de datos
    public function update(Request $request, $id)
    {
        $request->validate([
            'EJeContenido-'.$id => 'required|string|max:255',
        ]);

        $ejeContenido = EjeContenido::findOrFail($id);
        $ejeContenido->update([
            'descripcion' => $request->input('EJeContenido-'.$id),
        ]);

        return redirect()->route('rubrica.editor', ['id' => $ejeContenido->ra->rubrica->asignatura_id])
                         ->with('success', 'Eje de Contenido actualizado con éxito.');
    }

    // Elimina un eje de contenido
    public function destroy($id)
    {
        $ejeContenido = EjeContenido::findOrFail($id);
        $ejeContenido->delete();

    return response()->json(['success' => 'Elemento eliminado con éxito.']);

    }
}
