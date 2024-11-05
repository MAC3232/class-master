<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\niveles_desempeno_actividad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class LevelController extends Controller
{
    public function index()
    {
        return response()->json(niveles_desempeno_actividad::all());
    }

    // Almacena un nuevo nivel
    public function store(Request $request)
    {
        // Validar la solicitud
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'puntaje_inicial' => 'required|integer',
            'puntaje_final' => 'required|integer|gte:puntaje_inicial', // score_max debe ser mayor o igual a score_min
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $level = niveles_desempeno_actividad::create($request->all());
        return response()->json($level, 201);
    }

    // Muestra un nivel específico
    public function show($id)
    {
        $level = niveles_desempeno_actividad::find($id);
        if (!$level) {
            return response()->json(['message' => 'Nivel no encontrado'], 404);
        }
        return response()->json($level);
    }

    // Actualiza un nivel existente
    public function update(Request $request, $id)
    {
        $level = niveles_desempeno_actividad::find($id);
        if (!$level) {
            return response()->json(['message' => 'Nivel no encontrado'], 404);
        }

        // Validar la solicitud
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'score_min' => 'sometimes|required|integer',
            'score_max' => 'sometimes|required|integer|gte:score_min', // score_max debe ser mayor o igual a score_min
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $level->update($request->all());
        return response()->json($level);
    }

    // Elimina un nivel
    public function destroy($id)
    {
        $level = niveles_desempeno_actividad::find($id);
        if (!$level) {
            return response()->json(['message' => 'Nivel no encontrado'], 404);
        }

        $level->delete();
        return response()->json(['message' => 'Nivel eliminado con éxito']);
    }
}
