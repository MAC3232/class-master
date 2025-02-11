<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CarreraRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CarreraCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CarreraCrudController extends CrudController
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
        if (!backpack_auth()->check() || !backpack_user()->hasRole('admin')) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        CRUD::setModel(\App\Models\Carrera::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/carrera');
        CRUD::setEntityNameStrings('Programa', 'programas');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addButtonFromView('top', 'create','AddProgram',  'beginning');
        CRUD::setFromDb();
        
        
        // set columns from db columns.

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
       
        CRUD::setValidation(CarreraRequest::class);
        CRUD::setFromDb(); // set fields from db columns.
        
        $facultades = \App\Models\Facultad::count();
        if ($facultades == 0) {
            CRUD::addField([
                'name' => 'crear_facultad',
                'type' => 'custom_html',
                'value' => '<a href="' . backpack_url('facultad/create') . '" class="btn btn-primary">Crear Facultad</a>',
            ]);
        } else {


            CRUD::addField([
                'name' => 'facultad_id',
                'label' => 'Facultad',
                'type' => 'select',
                'entity' => 'facultad', // Relación con Facultad
                'attribute' => 'nombre', // Atributo que se mostrará
                'model' => 'App\Models\Facultad', // Modelo de facultad
            ]);


        }
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
