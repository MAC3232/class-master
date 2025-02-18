<?php

namespace App\Http\Controllers\admin;


use App\Models\Asignaturas;
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
    $query = Asignaturas::where('user_id', backpack_user()->id);

    if ($search = $request->input('search')) {
        $query->where(function($q) use ($search) {
            $q->where('nombre', 'LIKE', "%{$search}%")
              ->orWhere('codigo', 'LIKE', "%{$search}%")
              ->orWhere('catalogo', 'LIKE', "%{$search}%");
        });
    }

    $courses = $query->get();
    $crud = $this->crud;
    // cambia a la vista para mostrar
    return response()->json($courses);
}
}
