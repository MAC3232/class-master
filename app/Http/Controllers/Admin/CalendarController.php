<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AsignaturaDocente;
use App\Models\Asignaturas;
use App\Models\Evento;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index( Request $request )
    {




        if (!backpack_auth()->check() || !backpack_user()->hasRole(['docente','super-admin'])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        $asignatura_id = $request->id;
        $user = backpack_user();


        // Para el docente, se valida que tenga asignada la materia
       if ($user->hasRole('docente')) {
        // Verificamos en la tabla intermedia (modelo AsignaturaDocente)
        $tieneAcceso = AsignaturaDocente::where('docente_id', $user->id)
            ->where('asignatura_id', $asignatura_id)
            ->exists();

        if (! $tieneAcceso) {
            abort(404);

        }


    }

        $nombre = Asignaturas::where('id', $asignatura_id)->value('nombre');


        return view('calendar', compact('asignatura_id', 'nombre')); // Asegúrate de que esta vista exista en `resources/views/calendario/index.blade.php`
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $datosEvento = $request->except(['_token', '_method']);
        Evento::insert(($datosEvento));
        return response()->json($datosEvento);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {




        $data['eventos'] = Evento::where('asignatura_id', $id)->get();
return response()->json($data['eventos']);
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
    $datosEvento = $request->except(['_token', '_method']);
    $respuesta = Evento::where('id', "=", $id)->update($datosEvento);
    return response()->json($respuesta);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $evento = Evento::findOrFail($id); // Encuentra el evento por ID
        $evento->delete(); // Elimina el evento
        return response()->json(['id' => $id]); // Devuelve el ID eliminado como parte de un objeto JSON
    }
}
