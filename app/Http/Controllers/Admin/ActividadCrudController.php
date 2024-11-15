<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ActividadRequest;
use App\Models\Asignaturas;
use App\Models\Rubrica;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ActividadCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ActividadCrudController extends CrudController
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

        CRUD::setModel(\App\Models\Actividad::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/actividad');
    if ( request()->has('asignatura_id')) {
        $asignatura_id = request()->input('asignatura_id');
        $rubricaName = Asignaturas::where('id', $asignatura_id)->first();

        CRUD::setEntityNameStrings('actividad', 'actividades: '. $rubricaName->nombre ?? '');
    }else{
        return back();
    }

    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
{
    if ( request()->has('asignatura_id')) {
        $asignatura_id = request()->input('asignatura_id');
        $rubricaName = Asignaturas::where('id', $asignatura_id)->first();

        CRUD::setEntityNameStrings('actividad', 'actividades: '. $rubricaName->nombre ?? '');
    }else{
        return back();
    }
    CRUD::setFromDb(); // set columns from db columns.

    // Obtener `asignatura_id` de la URL (desde el contexto de asignatura)


    if ( isset($asignatura_id)) {
        $asignatura_id = request()->query('asignatura_id');
    }else{
        return back();
    }
    if ($asignatura_id) {
        $this->crud->addClause('where', 'asignatura_id', $asignatura_id);
    }
    $this->crud->removeColumn('ra_id');
    $this->crud->removeColumn('asignatura_id');

    // Agregar columna para el nombre del resultado de aprendizaje
    $this->crud->addColumn([
        'name' => 'ra_id',
        'label' => 'Resultado de aprendizaje',
        'type' => 'text',
        'value' => function($entry) {

            return $entry->ra ? $entry->ra->nombre : 'N/A'; // Asegúrate de que exista la relación
        },
    ]);

    // Agregar columna para el nombre de la asignatura


    // Sobreescribir el botón de "Add" para incluir `asignatura_id` en la URL
    $this->crud->denyAccess(['create']); // Primero, deshabilita el acceso normal al botón de crear
    $this->crud->addButtonFromView('top', 'add_with_asignatura', 'add_with_asignatura', 'end');

    // Pasamos `asignatura_id` a la vista para que Backpack construya la URL
    view()->share('asignatura_id', $asignatura_id);
}


    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setFromDb(); // set columns from db columns.

        CRUD::setValidation(ActividadRequest::class);

        $this->crud->addField(['name' => 'nombre', 'label' => 'Nombre', 'type' => 'text']);
        $this->crud->addField(['name' => 'descripcion', 'label' => 'Descripción', 'type' => 'textarea']);

        $this->crud->addField([
            'name' => 'asignatura_id',
            'type' => 'hidden',
            'value' => request()->input('asignatura_id')
        ]);

        // Limitar el select de RA solo a los que pertenecen a la asignatura actual
       $asignatura_id = request()->input('asignatura_id');
        $rubrica = Rubrica::where('asignatura_id', $asignatura_id)->first();

        // dd($rubrica->id);
        $this->crud->addField([
            'name' => 'ra_id',
            'label' => 'Resultado de Aprendizaje',
            'type' => 'select',
            'entity' => 'ra',
            'attribute' => 'nombre',
            'options' => function ($query) use ($rubrica) {
                return $rubrica ? $query->where('rubrica_id', $rubrica->id)->get() : collect();
            }
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
