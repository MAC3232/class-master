<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Estrategia;
use App\Models\RA;
use Illuminate\Http\Request;

class EstrategiaController extends Controller
{
    public function create($ra_id)
    {
        $ra = RA::findOrFail($ra_id);
        return view('estrategias.create', compact('ra'));
    }

    // Almacena una nueva estrategia en la base de datos
    public function store(Request $request, $ra_id)
    {
        $request->validate([
            'Estrategia-'.$ra_id => 'required|string|max:250',
        ]);

        Estrategia::create([
            'descripcion' => $request->input('Estrategia-'.$ra_id),
            'ra_id' => $ra_id,
        ]);

        return redirect()->route('rubrica.editor', ['id' => RA::findOrFail($ra_id)->rubrica->asignatura_id])
                         ->with('success', 'Criterio creado con éxito.');
    }

    // Muestra el formulario de edición de una estrategia específica
    public function edit($id)
    {
        $estrategia = Estrategia::findOrFail($id);
        return view('estrategias.edit', compact('estrategia'));
    }

    // Actualiza la estrategia en la base de datos
    public function update(Request $request, $id)
    {
        $request->validate([
            'EestrategiaEditar-'. $id => 'required|string|max:250',
        ]);

        $estrategia = Estrategia::findOrFail($id);
        $estrategia->update([
            'descripcion' => $request->input('EestrategiaEditar-'.$id),
        ]);

        return redirect()->route('rubrica.editor', ['id' => $estrategia->ra->rubrica->asignatura_id])
                         ->with('success', 'Estrategia actualizada con éxito.');
    }

    // Elimina una estrategia
    public function destroy($id)
    {
        $estrategia = Estrategia::findOrFail($id);
        $estrategia->delete();

    return response()->json(['success' => 'Elemento eliminado con éxito.']);

    }
}
