<?php

namespace App\Http\Controllers\admin;

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

        $rubrica = Rubrica::with('ra')->findOrFail($id_rubrica);


        $sumaPonderacion = $rubrica->ra->where('corte', $request->corte)->sum('ponderacion');


        $nuevaPonderacion = ($sumaPonderacion) + $request->ponderacion;

        if ($nuevaPonderacion > 100) {

            $diferenteError = "ERROR: La ponderación total supera el 100% (". $request->corte.")";
            Alert::error($diferenteError)->flash();

            // Comprobamos si se ha alcanzado el límite de RA
            if ($sumaPonderacion < 0) {
                Alert::error('Se ha alcanzado el límite de RAS. Prueba cambiar las ponderaciones de los RAS existentes')->flash();
            } else {
                $ponderacionRestante = 100 - $sumaPonderacion;
                Alert::error("En este RA la ponderación puede ser de $ponderacionRestante%")->flash();
            }
            return back()->withErrors(['RAEditar' => $diferenteError]); // Pasamos todos los errores como un array
        }

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

        $rubrica = Rubrica::with('ra')->findOrFail($id_rubrica);

        // Sumar las ponderaciones actuales de los RA de esta rúbrica
        $sumaPonderacion = $rubrica->ra->where('corte', $request->corte)->sum('ponderacion');

        // Calcular la nueva ponderación total incluyendo la actual

        if ( ($sumaPonderacion - $rubrica->ra->findOrFail($id)->ponderacion) +  $request->ponderacion > 100) {
            // Mostrar diferentes mensajes de error dependiendo del caso
            $diferenteError = "ERROR: La ponderación total supera el 100% ( Corte". $request->corte.")";
            Alert::error($diferenteError)->flash();

            // Comprobamos si se ha alcanzado el límite de RA
            if ($sumaPonderacion < 0) {
                Alert::error('Se ha alcanzado el límite de RAS. Prueba cambiar las ponderaciones de los RAS existentes')->flash();
            } else {
                $ponderacionRestante = 100 - ($sumaPonderacion - ($rubrica->ra->findOrFail($id)->ponderacion)) ;
                Alert::error("En este RA la ponderación puede ser de $ponderacionRestante%")->flash();
            }
            return back()->withErrors(['RAEditar'.$id => $diferenteError]); // Pasamos todos los errores como un array
        }
        $request->validate([
            'Ra-'.$id => 'required|string|max:255',
        ]);

        $ra = RA::findOrFail($id);
        $ra->update([
            'nombre' => $request->input('nombreEditar-'.$id),
            'descripcion' => $request->input('Ra-'.$id),
            'ponderacion' => $request->input('ponderacion'),
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
