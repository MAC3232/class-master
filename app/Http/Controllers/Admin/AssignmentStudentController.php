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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class AssignmentStudentController extends CrudController
{


public function index ($id){


    $this->crud->setModel('App\Models\AsignaturaEstudiante'); // Define el modelo
    $this->crud->setRoute(config('backpack.base.route_prefix') . '/asignaturas'); // Define la ruta
    $this->crud->setEntityNameStrings('Asignatura', 'Estudiantes');

    $asignatura = Asignaturas::findOrFail($id);

    $carrer = Carrera::all();

    $students = $asignatura->students()->get();


    return view('admin.asignment.students', ['crud' => $this->crud, 'students' =>  $students,'carrers' => $carrer, 'asignatura' =>['nombre'=> $asignatura, 'id' => $id] ]);



}

public function import(Request $request){

    $request->validate([
        'file' => 'required|file|mimes:csv,xlsx',
    ]);

    Session::forget('error');
    try {

        Excel::import(new AsignaturaEstudianteImport($request->asignatura), $request->file('file'));

        $errors = Session::get('error', []);
        $success = Session::get('import_success', []);

        return redirect()->back()->with([
            'successImport' => 'Proceso de importación completado.',
            'error' => $errors,
            'import_success' => $success
        ]);

        return redirect()->back()->with('successImport', 'Estudiantes importados correctamente.');
    } catch (\Exception $e) {


        return redirect()->back()->with('error', '❌ Error al importar el archivo: ' );

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

public function deleteStudents($asignatura_id, $studentsList)
{


    try {
        $studentsArray = explode(',', $studentsList);
        // Convertir la lista de IDs a un array

        // Eliminar la relación en la tabla intermedia

        foreach ($studentsArray as $key => $value) {


            $asignacion = AsignaturaEstudiante::where('asignatura_id', $asignatura_id)
            ->where('estudiante_id', $value)
            ->first();

            if (!$asignacion) {
                return response()->json(['message' => 'El estudiante no está asignado a esta materia.'], 404);
              }

              AsignaturaEstudiante::where('asignatura_id', $asignatura_id)
              ->where('estudiante_id', $value)
              ->delete();

        }




        return response()->json([
            'success' => true,
            'message' => 'Estudiantes eliminados correctamente',
            'deleted_students' => $studentsArray
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al eliminar los estudiantes',
            'error' => $e->getMessage()
        ], 500);
    }
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
