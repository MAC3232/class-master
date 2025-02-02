<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\AsignaturaEstudianteImport;
use App\Models\AsignaturaEstudiante;
use App\Models\Asignaturas;
use App\Models\Carrera;
use App\Models\Estudiantes;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AssignmentStudentController extends CrudController
{


public function index ($id){


    $this->crud->setModel('App\Models\AsignaturaEstudiante'); // Define el modelo
    $this->crud->setRoute(config('backpack.base.route_prefix') . '/asignaturas'); // Define la ruta
    $this->crud->setEntityNameStrings('Asignatura', 'Estudiantes');

    $asignatura = Asignaturas::findOrFail($id);

    $carrer = Carrera::all();


    return view('admin.asignment.students', ['crud' => $this->crud, 'students' =>  $asignatura->students,'carrers' => $carrer, 'asignatura' =>['nombre'=> $asignatura, 'id' => $id] ]);



}

public function import(Request $request){

    $request->validate([
        'file' => 'required|file|mimes:csv,xlsx',
    ]);


    try {
        Excel::import(new AsignaturaEstudianteImport($request->asignatura), $request->file('file'));


        return redirect()->back()->with('successImport', 'Estudiantes importados correctamente.');
    } catch (\Exception $e) {

        dd($e);

        return redirect()->back()->with('error', 'Error al importar el archivo: ' . $e->getMessage());
    }



    return redirect()->back()->with('success', 'Archivo importado correctamente.');
}



public function ListCheckEstudentsView(Request $request) {


    $query = Estudiantes::with('carrera');

    // Filtro por carrera si existe
    if ($request->has('carrera_id') && $request->carrera_id) {
        $query->where('carrera_id', $request->carrera_id);
    }

   // Búsqueda por nombre o código estudiantil
if ($request->has('search') && $request->search) {
    $searchTerm = $request->search;

    $query->where(function($q) use ($searchTerm) {
        $q->where('nombre', 'like', '%' . $searchTerm . '%')
          ->orWhere('codigo_estudiantil', 'like', '%' . $searchTerm . '%');
    });
}



    // Ordenar por carrera y luego por nombre del estudiante
    $query->orderBy(Carrera::select('nombre')->whereColumn('carreras.id', 'estudiantes.carrera_id'))
          ->orderBy('nombre');

    $estudiantes = $query->paginate(10);

    $asignados = [];

    if ($request->has('asignatura_id') && $request->asignatura_id) {
        $asignados = AsignaturaEstudiante::where('asignatura_id', $request->asignatura_id)
                                          ->pluck('estudiante_id')
                                          ->toArray();

    }



    return response()->json([
        'data' => $estudiantes,
        'asignados' => $asignados, // Enviar los estudiantes asignados para marcar checkboxes
    ]);



}

public function AssigmentStoreEstudents(Request $request)
{
    $asignatura = Asignaturas::findOrFail($request->materia_id);

    $asignatura->students()->sync($request->estudiantes);
    return response()->json(['message' => 'Asignación guardada correctamente']);


}

public function DeleteStudentAsigment($asignatura_id, $estudiante_id)
{
    $asignacion = AsignaturaEstudiante::where('asignatura_id', $asignatura_id)
                                      ->where('estudiante_id', $estudiante_id)
                                      ->first();

                                      if (!$asignacion) {
                                          return response()->json(['message' => 'El estudiante no está asignado a esta materia.'], 404);
                                        }

                                        AsignaturaEstudiante::where('asignatura_id', $asignatura_id)
                                        ->where('estudiante_id', $estudiante_id)
                                        ->delete();
                                        return response()->json(['message' => $asignacion]);


}


}
