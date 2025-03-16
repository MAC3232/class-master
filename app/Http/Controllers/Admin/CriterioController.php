<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Criterio;
use App\Models\RA;
use Illuminate\Http\Request;
use Prologue\Alerts\Facades\Alert;

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
        try {
            // Validar los datos de entrada
            $validatedData = $request->validate([
                'descripcion-' . $ra_id => 'required|string|max:250',
            ]);

            // Crear el criterio y asociarlo al RA específico
            Criterio::create([
                'descripcion' => $request->input('descripcion-'.$ra_id),
                'ra_id' => $ra_id,
            ]);
            Alert::success('Criterio añadido con exito')->flush();

            // Redirigir con mensaje de éxito
            return redirect()->route('rubrica.editor', ['id' => RA::findOrFail($ra_id)->rubrica->asignatura_id]);
        }  catch (\Exception $e) {
            Alert::error('No se pudo añadir el Criterio ')->flush();

            // Capturar cualquier otro error inesperado

            return redirect()->back();
        }
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
    try {
        $request->validate([
            'CriterioEdit-'. $id => 'required|string|max:250',
        ]);

        // Buscar el criterio y actualizarlo
        $criterio = Criterio::findOrFail($id);
        $criterio->update([
            'descripcion' => $request->input('CriterioEdit-'.$id),
        ]);

        Alert::success('Criterio editado con exito')->flush();

        // Redirigir a la vista anterior o a la lista de criterios con un mensaje de éxito
        return redirect()->route('rubrica.editor', ['id' => $criterio->ra->rubrica->asignatura_id]);

    } catch (\Throwable $th) {

        Alert::error('No se pudo editar el criterio')->flush();

        // Capturar cualquier otro error inesperado

        return redirect()->back();

    }
    // Validar los datos de entrada
}


public function destroy($id)
{
    $criterio = Criterio::findOrFail($id);
    $criterio->delete();

    return response()->json(['success' => 'Elemento eliminado con éxito.']);
}

}
