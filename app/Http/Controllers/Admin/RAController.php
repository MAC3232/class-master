<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Asignaturas;
use App\Models\RA;
use App\Models\Rubrica;
use Illuminate\Http\Request;

class RAController extends Controller
{
    public function create($id)
    {
        // Retorna la vista del editor de rúbrica
        $asignatura = Asignaturas::with('rubrica')->findOrFail($id);


        // dd($rubrica);


        return view('rubricas.RA.Racreate', compact('asignatura'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'ponderacion' => 'required|numeric|min:0|max:100',

        ]);

        RA::create([
            'nombre' => $request->input('nombre'),
            'descripcion' => $request->input('descripcion'),
            'ponderacion' => $request->input('ponderacion'),
            'corte' => $request->input('corte'),
            'rubrica_id' => $request->input('rubrica_id'),
        ]);

        $asignatura = Asignaturas::with('rubrica')->findOrFail($id);

        return    redirect()->route('rubrica.editor', ['id' => $asignatura->id]);
    }

    public function edit($id)
    {
        $estrategia = RA::findOrFail($id);

        return view('rubricas.RA.edit', compact('estrategia'));
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'Ra-'.$id => 'required|string|max:255',
        ]);

        $ra = RA::findOrFail($id);
        $ra->update([
            'nombre' => $request->input('nombreEditar-'.$id),
            'descripcion' => $request->input('Ra-'.$id),
            'ponderacion' => $request->input('ponderacion'),
            'corte' => $request->input('corte'),
            'rubrica_id' => $request->input('rubrica_id'),
        ]);

        return redirect()->route('rubrica.editor', ['id' => $ra->rubrica->asignatura_id]);

}
public function destroy($id)
{
    $ejeContenido = RA::findOrFail($id);
    $ejeContenido->delete();

    return response()->json(['success' => 'Elemento eliminado con éxito.']);
}

}
