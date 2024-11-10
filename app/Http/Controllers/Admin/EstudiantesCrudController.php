<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EstudiantesRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class EstudiantesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class EstudiantesCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Estudiantes::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/estudiantes');
        CRUD::setEntityNameStrings('estudiantes', 'estudiantes');
        
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setFromDb(); // set columns from db columns.
        $this->crud->removeColumn('carrera_id');

        CRUD::addColumn([
            'name' => 'carrera_id', // Nombre de la columna en la tabla de estudiantes
            'label' => 'Carrera',   // Etiqueta para mostrar en la tabla
            'entity' => 'carrera', // Nombre de la relación en el modelo de Estudiantes
            'attribute' => 'nombre', // Nombre del atributo de Carrera que quieres mostrar
            'model' => 'App\Models\Carrera' // Modelo relacionado
        ]);
        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(EstudiantesRequest::class);
        CRUD::setFromDb();
        
        $carreras = \App\Models\Carrera::all()->count();
        if ($carreras == 0) {
            CRUD::addField([
                'name' => 'crear_carrera',
                'type' => 'custom_html',
                'value' => '<a href="' . backpack_url('carrera/create') . '" class="btn btn-primary">Crear Carrera</a>',
            ]);
        } else {
            CRUD::addField(['name' => 'carrera_id', 'label' => 'Carrera', 'type' => 'select', 'entity' => 'carrera', 'model' => 'App\Models\Carrera', 'attribute' => 'nombre']);
        }


 

        CRUD::addField([
            'name'    => 'assignments', // Este campo también debe estar presente
            'label'   => 'Añade las asignaturas que cursa el estudiante',
            'type'    => 'custom_checklist',
            'entity'  => 'assignments',
            'model'   => "App\Models\Asignaturas",
            'attribute' => 'nombre',
            'pivot'   => true,
        ]);



        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
        
    }


}

