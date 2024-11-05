<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Actividad;
use App\Models\Asignaturas;
use App\Models\RA;
use Illuminate\Http\Request;

class ActividadController extends Controller
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
        $asignatura = Asignaturas::with('rubrica.ra')->findOrFail($id);

        // Obtén los RA específicos de la rúbrica de la asignatura
        $raList = $asignatura->rubrica ? $asignatura->rubrica->ra : [];
    
        // Retorna la vista de creación de actividad pasando la lista de RA
      
        

        return view('rubricas.actividad.actividad_create', compact([ 'raList', 'asignatura']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha' => 'required|date',
            'descripcion' => 'required|string',
            'ponderacion' => 'required|numeric|min:0|max:100',
            'ra_id' => 'required|exists:ra,id',  // Validación para ra_id
        ]);
    
        Actividad::create([
            'nombre' => $request->input('nombre'),
            'fecha' => $request->input('fecha'),
            'asignatura_id' => $id,
            'descripcion' => $request->input('descripcion'),
            'ponderacion' => $request->input('ponderacion'),
            'ra_id' => $request->input('ra_id'),
        ]);
    
        return redirect()->route('rubrica.disenador', ['id' => RA::findOrFail($request->input('ra_id'))->rubrica->asignatura_id])
                         ->with('success', 'Actividad creada con éxito.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
