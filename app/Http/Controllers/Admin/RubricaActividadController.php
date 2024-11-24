<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Actividad;
use App\Models\rubrica_actividad;
use Illuminate\Http\Request;

class RubricaActividadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
 public function create($id)
{
    if (!backpack_auth()->check() || !backpack_user()->hasRole('docente')) {
        abort(403, 'No tienes permiso para acceder a esta sección.');
    }
    // Buscar la actividad con su rúbrica y criterios
    $rubrica_actividad = Actividad::with('rubrica.criterios')->findOrFail($id);

    if ($rubrica_actividad->rubrica) {
        return view('actividad.rubrica.show', compact('rubrica_actividad'));
    }

    $actividad = Actividad::findOrFail($id);



        return view('actividad.rubrica.create', compact('actividad'));
    // Si no existe la rúbrica, mostrar la vista de creación

}
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!backpack_auth()->check() || !backpack_user()->hasRole('docente')) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

// dd($request);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'actividad_id' => 'unique:rubrica_actividad|required|exists:actividades,id',
        ]);

        rubrica_actividad::create([
            'nombre' => $request->input('nombre'),
            'actividad_id' => $request->input('actividad_id'),
            'descripcion' => $request->input('descripcion'),
        ]);

        return redirect()->route('rubrica_actividad.index', $request->input('actividad_id'))->with('success', 'Rúbrica creada con éxito.');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {


        $rubrica_actividad = Actividad::with('rubrica.criterios')->findOrFail($id);




        return view('actividad.rubrica.show', compact('rubrica_actividad'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
