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

    public function index() {
        $courses = Asignaturas::where('user_id', backpack_user()->id)->get();
        $crud = $this->crud;
        return view('courses.listCourses', compact('courses', 'crud'));
    }
}
