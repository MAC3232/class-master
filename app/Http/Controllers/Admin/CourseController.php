<?php

namespace App\Http\Controllers\admin;


use App\Models\Asignaturas;
use App\Models\Estudiantes;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

use Illuminate\Http\Request;

class CourseController extends CrudController
{
    public function setup() {
        $this->crud->setModel('App\Models\Asignaturas');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/courses');
        $this->crud->setEntityNameStrings('Asignatura', 'Asignaturas');
    }

    public function index(Request $request)
    {
        $query = Asignaturas::query();

        if (backpack_user()->hasRole('estudiante')) {
            $estudiante = Estudiantes::where('user_id', backpack_user()->id)->first();

            if ($estudiante) {
                $query->whereHas('students', function ($q) use ($estudiante) {
                    $q->where('estudiante_id', $estudiante->id);
                });
            }
        } elseif (backpack_user()->hasRole('admin')) {
            $query->where('carrera_id', backpack_user()->carrera_id);
        } elseif (backpack_user()->hasRole('docente')) {
            $query->whereHas('asignaturasDocentes', function ($q) {
                $q->where('docente_id', backpack_user()->id);
            });
        }

        $courses = $query->get();
        $crud = $this->crud;
        return view('courses.listCourses', compact('courses', 'crud'));
    }


public function searchAsignatura(Request $request)
{
    $query = Asignaturas::select('id', 'nombre', 'codigo', 'catalogo');

    // Filtrar por rol estudiante
    if (backpack_user()->hasRole('estudiante')) {
        $estudiante = Estudiantes::where('user_id', backpack_user()->id)->first();

        if ($estudiante) {
            $query->whereHas('students', function ($q) use ($estudiante) {
                $q->where('estudiante_id', $estudiante->id);
            });
        }
    }

    // Filtrar por rol admin
    if (backpack_user()->hasRole('admin')) {
        $query->where('carrera_id', backpack_user()->carrera_id);
    }

    // Filtrar por rol docente
    if (backpack_user()->hasRole('docente')) {
        $query->whereHas('asignaturasDocentes', function ($q) {
            $q->where('docente_id', backpack_user()->id);
        });
    }

    // Aplicar búsqueda si se proporciona un término de búsqueda
    if ($search = $request->input('search')) {
        $query->where(function ($q) use ($search) {
            $q->where('nombre', 'LIKE', "%{$search}%")
              ->orWhere('codigo', 'LIKE', "%{$search}%")
              ->orWhere('catalogo', 'LIKE', "%{$search}%");
        });
    }

    $courses = $query->get();

    return response()->json($courses);
}

}
