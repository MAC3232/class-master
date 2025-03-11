<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Prologue\Alerts\Facades\Alert;
use Spatie\Permission\Models\Role;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserCrudController extends CrudController
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

        if (!backpack_auth()->check() || !backpack_user()->hasRole('super-admin')) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }


        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings('user', 'users');

        $this->crud->setModel(User::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/user');
        $this->crud->setEntityNameStrings('usuario', 'usuarios');

        // Añadir columnas
        $this->crud->addColumn(['name' => 'name', 'label' => 'Nombre', 'type' => 'text']);
        $this->crud->addColumn(['name' => 'email', 'label' => 'Email', 'type' => 'email']);
        $this->crud->addColumn([
            'name' => 'roles',
            'label' => 'Roles',
            'type' => 'select_multiple', // Cambiamos a 'select_multiple' que viene con Backpack FREE
            'entity' => 'roles', // Relación en el modelo User
            'attribute' => 'name', // Atributo del rol que queremos mostrar (nombre del rol)
            'model' => "Spatie\Permission\Models\Role",
        ]);

        // Añadir campos
        $this->crud->addField(['name' => 'name', 'label' => 'Nombre', 'type' => 'text']);
        $this->crud->addField(['name' => 'email', 'label' => 'Email', 'type' => 'email']);

        $this->crud->addField([
            'label' => "Roles",
            'type' => 'select_multiple',
            'name' => 'roles', // relación en el modelo User
            'entity' => 'roles', // relación en el modelo User
            'attribute' => 'name', // atributo que se mostrará en el select
            'model' => "Spatie\Permission\Models\Role",
            'pivot' => true,
            'default' =>['admin']
        ]);

        // Proteger acceso a admin

    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::addClause('whereDoesntHave', 'estudiante');


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

        CRUD::setValidation(UserRequest::class);
        CRUD::setFromDb(); // set fields from db columns.

        CRUD::field('password')->type('password')->label('Contraseña');




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


    public function update()
    {

     $request = $this->crud->validateRequest();

    // Encuentra y actualiza al usuario de forma elegante
    $user = User::findOrFail($request->id);

    $user->update([
    'name' => $request->name,
    'email' => $request->email,
    'password' => bcrypt($request->password)
    ]);



    Alert::success('Usuario editado exitosamente')->flash();

    if($request->_save_action == 'save_and_back'){

        return Redirect::to('/admin/user');
    }

    return redirect()->back() ;

    }
}
