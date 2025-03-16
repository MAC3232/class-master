<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asignaturas;
use App\Models\RA;
use App\Models\Rubrica;
use Illuminate\Http\Request;
use Prologue\Alerts\Facades\Alert;

class RAController extends Controller
{
    public function create($id)
    {
        // Retorna la vista del editor de rúbrica
        $asignatura = Asignaturas::with('rubrica')->findOrFail($id);


        // dd($rubrica);


        return view('rubricas.RA.Racreate', compact('asignatura'));
    }

    public function store(Request $request, $id, $id_rubrica)
    {



        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:1000',
        ]);


        RA::create([
            'nombre' => $request->input('nombre'),
            'descripcion' => $request->input('descripcion'),
            'corte' => $request->input('corte'),
            'rubrica_id' => $id_rubrica,
        ]);

        $asignatura = Asignaturas::with('rubrica')->findOrFail($id);
        Alert::success('Añadido exitosamente')->flash();


        return    redirect()->route('rubrica.editor', ['id' => $asignatura->id]);
    }

    public function edit($id)
    {
        $estrategia = RA::findOrFail($id);

        return view('rubricas.RA.edit', compact('estrategia'));
    }

    public function update(Request $request, $id, $id_rubrica)
    {

        $request->validate([
            'Ra-'.$id => 'required|string|max:1000',
        ]);

        $ra = RA::findOrFail($id);
        $ra->update([
            'nombre' => $request->input('nombreEditar-'.$id),
            'descripcion' => $request->input('Ra-'.$id),
            'corte' => $request->input('corte'),
            'rubrica_id' => $id_rubrica,
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
