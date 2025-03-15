<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\criterios_actividad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CriterioActividadController extends Controller
{
     public function index()
    {
        return response()->json(criterios_actividad::all());
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

        $criterion = criterios_actividad::create($request->all());
        return response()->json($criterion, 201);
    }

    // Muestra un criterio específico
    public function show($id)
    {
        $criterion = criterios_actividad::find($id);
        if (!$criterion) {
            return response()->json(['message' => 'Criterio no encontrado'], 404);
        }
        return response()->json($criterion);
    }

    // Actualiza un criterio existente
    public function update(Request $request, $id)
    {
        $criterion = criterios_actividad::find($id);
        if (!$criterion) {
            return response()->json(['message' => 'Criterio no encontrado'], 404);
        }

        // Validar la solicitud
        $validator = Validator::make($request->all(), [
            'description' => 'sometimes|required|string|max:255',
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
        $criterion = criterios_actividad::find($id);
        if (!$criterion) {
            return response()->json(['message' => 'Criterio no encontrado'], 404);
        }

        $criterion->delete();
        return response()->json(['message' => 'Criterio eliminado con éxito']);
    }
}
