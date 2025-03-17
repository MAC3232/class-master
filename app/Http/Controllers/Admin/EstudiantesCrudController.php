<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EstudiantesRequest;
use App\Http\Requests\EstudiantesUpdateRequest;
use App\Models\AsignaturaDocente;
use App\Models\Asignaturas;
use App\Models\Estudiantes;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Request;
use Prologue\Alerts\Facades\Alert;

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

        $user = backpack_user();
        if ($user->hasRole(['docente', 'estudiante'])) {
            $this->crud->denyAccess(['delete']); // Bloquea eliminar
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
    if (!backpack_auth()->check() || !backpack_user()->hasRole(['super-admin','admin'])) {
        abort(404);
    }

    $this->crud->addButtonFromView('top', 'create', 'add_estudiante', 'beginning');

    // Columna para el nombre (relación con user)
    CRUD::addColumn([
        'name'        => 'user.name',
        'label'       => 'Nombre',
        'type'        => 'relationship',
        'entity'      => 'user', // nombre de la relación definida en el modelo
        'attribute'   => 'name', // campo que se mostrará del usuario
        'model'       => 'App\Models\User',
        'searchLogic' => function ($query, $column, $searchTerm) {
            // Se realiza la búsqueda en la relación "user"
            $query->orWhereHas('user', function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%'.$searchTerm.'%');
            });
        },
    ]);

    CRUD::setFromDb();
    $this->crud->removeColumn('carrera_id');
    $this->crud->removeColumn('user_id');
    $this->crud->removeColumn('cedula');

    // Columna para el correo (con closure)
    CRUD::addColumn([
        'name'     => 'user_email',
        'label'    => 'Correo',
        'type'     => 'closure',
        'function' => function($entry) {
            if ($entry->user) {
                return '<a href="mailto:' . $entry->user->email . '">' . $entry->user->email . '</a>';
            }
            return 'Sin correo';
        },
        'escaped'  => false,
        'searchLogic' => function ($query, $column, $searchTerm) {
            // Se realiza la búsqueda en la relación "user"
            $query->orWhereHas('user', function ($q) use ($searchTerm) {
                $q->where('email', 'like', '%'.$searchTerm.'%');
            });
        },
    ]);

    // Columna para mostrar la relación con Carrera
    CRUD::addColumn([
        'name'      => 'carrera_id',
        'label'     => 'Carrera',
        'entity'    => 'carrera',
        'attribute' => 'nombre',
        'model'     => 'App\Models\Carrera'
    ]);
}



    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        if (!backpack_auth()->check() || !backpack_user()->hasRole(['super-admin','admin'])) {
            abort(404);
        }
        CRUD::setValidation(EstudiantesRequest::class);

        CRUD::addField([
            'name'      => 'nombre',
            'label'     => 'Nombre',
            'type'      => 'text',
            'value'     => optional($this->crud->getCurrentEntry()->user)->name, // Accede al nombre del usuario relacionado

            'fake'      => true,
        ]);
        CRUD::addField([
            'name'      => 'correo',
            'label'     => 'Correo Electrónico',
            'type'      => 'text',
            'value'     => optional($this->crud->getCurrentEntry()->user)->email, // Accede al email del usuario relacionado

            'fake'      => true,
        ]);
        CRUD::setFromDb();
        CRUD::removeField('user_id');


        $carreras = \App\Models\Carrera::all()->count();
        if ($carreras == 0) {
            CRUD::addField([
                'name'  => 'crear_carrera',
                'type'  => 'custom_html',
                'value' => '<a href="' . backpack_url('carrera/create') . '" class="btn btn-primary">Crear Carrera</a>',
            ]);
        } else {
            CRUD::addField([
                'name'      => 'carrera_id',
                'label'     => 'Carrera',
                'type'      => 'select',
                'entity'    => 'carrera',
                'model'     => 'App\Models\Carrera',
                'attribute' => 'nombre'
            ]);
        }



        CRUD::addField([
            'name'      => 'assignments',
            'label'     => 'Añade las asignaturas que cursa el estudiante',
            'type'      => 'custom_checklist',
            'entity'    => 'assignments',
            'model'     => 'App\Models\Asignaturas',
            'attribute' => 'nombre',
            'pivot'     => true,
        ]);
    }


    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        if (!backpack_auth()->check() || !backpack_user()->hasRole(['super-admin','admin'])) {
            abort(404);
        }
        $this->setupCreateOperation();

    }

    protected function update( EstudiantesRequest $request, $id)
{


    $data = $request;


    $student = \App\Models\Estudiantes::findOrFail($id);
    $user = $student->user;

    $user->update([
        'name'  => $data['nombre'],
        'email' => $data['correo']
    ]);

    $student->update([
        'carrera_id' => $data['carrera_id'],
        'codigo_estudiantil' => $data['codigo_estudiantil'],
        'cedula' => $data['cedula']
    ]);



    $student->assignments()->sync($data['assignments'] ?? []);
    Alert::success('Usuario editado exitosamente')->flash();

    return redirect()->route('estudiantes.index');
}

protected function setupShowOperation()
{
    if (!backpack_auth()->check() || !backpack_user()->hasRole(['super-admin','admin', 'docente'])) {

        abort(404);
    }



    $user = backpack_user();


       // Para el docente, se valida que tenga asignada la materia
       if ($user->hasRole('docente')) {
        // Obtenemos el ID de la asignatura actual (asumiendo que está en la ruta)
        $asignaturaId = request()->id;


        // Verificamos en la tabla intermedia (modelo AsignaturaDocente)
        $tieneAcceso = AsignaturaDocente::where('docente_id', $user->id)
            ->where('asignatura_id', $asignaturaId)
            ->exists();

        if (! $tieneAcceso) {
            abort(404);

        }


    }
   // logica roles: elimiar update y delete
   if (!backpack_user()->hasRole(['super-admin', 'admin'])) {

    $this->crud->removeButton('update');
    $this->crud->removeButton('delete');
    $this->crud->removeButton('activity');
}

  // Mostrar Nombre del usuario
  $this->crud->addColumn([
    'name' => 'user.name',
    'label' => 'Nombre',
    'type' => 'text',
]);
  $this->crud->addColumn([
    'name' => 'user.email',
    'label' => 'Correo',
    'type' => 'email',
    'function' => function ($entry) {
        return '<a href="mailto:' . e(optional($entry->user)->email) . '">' . e(optional($entry->user)->email) . '</a>';
    },
    'escaped' => false // Permite que el HTML se renderice correctamente
]);

$this->crud->addColumn(['name' => 'codigo_estudiantil', 'label' => 'Código', 'type' => 'text']);
$this->crud->addColumn(['name' => 'cedula', 'label' => 'Cedula', 'type' => 'number']);
$this->crud->addColumn([
    'name' => 'carrera.nombre',
    'label' => 'Programa',
    'type' => 'text',
]);


}


    public function obtenerAsignaturas(Request $request)
    { if (!backpack_auth()->check() || !backpack_user()->hasRole(['super-admin','admin'])) {
        abort(404);
    }
        $search = $request->get('search', '');
        $asignaturas = Asignaturas::when($search, function ($query, $search) {
            $query->where('nombre', 'like', "%{$search}%")
                  ->orWhere('codigo', 'like', "%{$search}%");
        })->paginate(10);

        return response()->json([
            'data' => $asignaturas->items(), // Devuelve solo los elementos actuales
            'pagination' => [
                'current_page' => $asignaturas->currentPage(),
                'last_page' => $asignaturas->lastPage(),
                'per_page' => $asignaturas->perPage(),
                'total' => $asignaturas->total(),
                'links' => [
                    'next' => $asignaturas->nextPageUrl(),
                    'prev' => $asignaturas->previousPageUrl(),
                ]
            ]
        ]);
    }

}

