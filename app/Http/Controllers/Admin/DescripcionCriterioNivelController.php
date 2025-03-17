<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\descripciones_actividad_critrio_nivel;
use App\Models\SelectCriterioEstudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DescripcionCriterioNivelController extends Controller
{
    public function index()
    {
        return response()->json(descripciones_actividad_critrio_nivel::all());
    }

    // Almacena un nuevo criterio
    public function store(Request $request)
    {

        // Validar la solicitud
        $validator = Validator::make($request->all(), [
            'descripcion' => 'required|string|max:255',


        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $descripcion = descripciones_actividad_critrio_nivel::create($request->all());
        return response()->json($descripcion, 201);
    }

    // Muestra un criterio específico
    public function show($id)
    {
        $criterion = descripciones_actividad_critrio_nivel::find($id);
        if (!$criterion) {
            return response()->json(['message' => 'Criterio no encontrado'], 404);
        }
        return response()->json($criterion);
    }

    // Actualiza un criterio existente
    public function update(Request $request, $id)
    {
        $criterion = descripciones_actividad_critrio_nivel::find($id);
        if (!$criterion) {
            return response()->json(['message' => 'Criterio no encontrado'], 404);
        }

        // Validar la solicitud
        $validator = Validator::make($request->all(), [
            'descripcion' => 'sometimes|required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $criterion->update($request->all());
        return response()->json($criterion);
    }

    // Elimina un criterio
    public function destroy($id)
    {
        $criterion = descripciones_actividad_critrio_nivel::find($id);

        if (!$criterion) {
            return response()->json(['message' => 'Criterio no encontrado'], 404);
        }

        $seleccionado = SelectCriterioEstudent::where('criterio_id', $criterion->criterio_id)
            ->where('nivel_desempeno_id', $criterion->nivel_desempeno_id)
            ->first();

        if ($seleccionado) {
            $seleccionado->delete();
        }


        $criterion->delete();
        return response()->json(['message' => 'Criterio eliminado con éxito']);
    }
}
