<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AsignaturasRequest;
use App\Models\AsignaturaDocente;
use App\Models\Asignaturas;
use App\Models\Carrera;
use App\Models\Estudiantes;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Exception;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Prologue\Alerts\Facades\Alert;


/**
 * Class AsignaturasCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AsignaturasCrudController extends CrudController
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

        CRUD::setModel(\App\Models\Asignaturas::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/asignaturas');
        CRUD::setEntityNameStrings('asignaturas', 'asignaturas');


        $this->crud->setOperationSetting('destroy', [
            'message' => '¿Estás seguro de eliminar este registro? Esta acción no puede deshacerse.',
            'icon' => 'warning', // Puedes personalizar el icono también
            'buttons' => ['cancel' => 'Cancelar', 'confirm' => 'Eliminar'],

        ]);
    }


    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addButtonFromView('top', 'create', 'Addcourses',  'beginning');

        CRUD::setValidation(AsignaturasRequest::class);

        if (backpack_user()->hasRole('estudiante')) {
            // Obtener el estudiante autenticado
            $estudiante = Estudiantes::where('user_id', backpack_user()->id)->first();

            if ($estudiante) {
                // Filtrar solo las asignaturas del estudiante usando la tabla intermedia
                $this->crud->addClause('whereHas', 'students', function ($query) use ($estudiante) {
                    $query->where('estudiante_id', $estudiante->id);
                });

                // Quitar botones de edición y eliminación para el rol estudiante
                $this->crud->removeButton('update');
                $this->crud->removeButton('delete');
            }
        }


        if (backpack_user()->hasRole('admin')) {
            $this->crud->addClause('where', 'carrera_id', backpack_user()->carrera_id);
        } elseif (backpack_user()->hasRole('docente')) {
            $this->crud->addClause('whereHas', 'asignaturasDocentes', function ($query) {
                $query->where('docente_id', backpack_user()->id);
            });

            $this->crud->removeButton('update');
            $this->crud->removeButton('delete');
        }



        // Filtrar asignaturas si el usuario es docente


        // Columnas a mostrar en la lista de asignaturas
        $this->crud->addColumn(['name' => 'nombre', 'label' => 'Nombre', 'type' => 'text']);
        $this->crud->addColumn(['name' => 'codigo', 'label' => 'N° clase', 'type' => 'text']);
        $this->crud->addColumn(['name' => 'catalogo', 'label' => 'Catálogo', 'type' => 'text']);
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {

        if (!backpack_auth()->check() || !backpack_user()->hasRole('admin')) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        CRUD::setValidation(AsignaturasRequest::class);


        // Primera parte del formulario
        CRUD::addField([
            'name' => 'step1',
            'type' => 'custom_html',
            'value' => '<h3>Paso 1: Información básica</h3>',
        ]);

        CRUD::addField(['name' => 'nombre', 'label' => 'Nombre de la Asignatura', 'type' => 'text']);
        CRUD::addField(['name' => 'codigo', 'label' => 'N° clase', 'type' => 'text']);
        CRUD::addField(['name' => 'catalogo', 'label' => 'Catalogo', 'type' => 'text']);
        CRUD::addField(['name' => 'competencia', 'label' => 'competencia', 'type' => 'text']);
        CRUD::addField(['name' => 'descripcion_competencia', 'label' => 'descripcion de la competencia', 'type' => 'text']);
        CRUD::addField(['name' => 'justificacion', 'label' => 'Justificacion', 'type' => 'text']);


        // Select para facultad
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
        CRUD::addField([
            'name' => 'carrera_id',
            'label' => 'Carrera',
            'type' => 'select',
            'entity' => 'carrera', // Relación con Facultad
            'attribute' => 'nombre', // Atributo que se mostrará
            'model' => 'App\Models\Carrera', // Modelo de facultad
        ]);

        CRUD::addField(['name' => 'correquisitos', 'label' => 'corequisitos', 'type' => 'text']);
        CRUD::addField(['name' => 'prerequisitos', 'label' => 'prerequisitos', 'type' => 'text']);





        CRUD::addField([
            'name' => 'nivel_formacion',
            'label' => 'Nivel de formacion',
            'type' => 'select_from_array',
            'options' => [
                'doctorado'           => 'Doctorado (postgrado)',
                'maestria'            => 'Maestría (postgrado)',
                'especializacion'     => 'Especialización (postgrado)',
                'tecnologia'          => 'Tecnología (pregrado)',
                'tecnico profesional' => 'Técnico Profesional (pregrado)',
                'universitario'       => 'Universitario (pregrado)',
            ],
            'allows_null' => false,
            'default' => 'universitario',

        ]);

        // Campo de selección para el docente





        // Campo de selección para el docente
    CRUD::addField([
        'label' => 'Docente',
        'type' => 'select',
        'name' => 'user_id', // No está en asignaturas directamente, se usará en la relación
        'entity' => 'asignaturasDocentes',
        'model' => 'App\Models\User',
        'attribute' => 'name',
        'options' => (function ($query) {
            return $query->role('docente')->get();
        })
    ]);


        // Botón para avanzar al paso 2
        CRUD::addField([
            'name' => 'toStep2',
            'type' => 'custom_html',
            'value' => '<button class="btn btn-primary" id="goToStep2" type="button">Siguiente paso</button>',
        ]);



        // Segunda parte del formulario (oculta por defecto)
        CRUD::addField([
            'name' => 'step2',
            'type' => 'custom_html',
            'value' => '<h3>Paso 2: Información adicional</h3>',
            'wrapperAttributes' => ['style' => 'display:none', 'id' => 'step2Fields'],
        ]);

        CRUD::addField(['name' => 'area_formacion', 'label' => 'Área de Formación', 'type' => 'text', 'wrapperAttributes' => ['style' => 'display:none', 'id' => 'area_formacion']]);
        CRUD::addField(['name' => 'creditos_academicos', 'label' => 'Créditos Académicos', 'type' => 'number', 'wrapperAttributes' => ['style' => 'display:none', 'id' => 'creditos_academicos']]);
        CRUD::addField([
            'name' => 'modalidad',
            'label' => 'Modalidad',
            'type' => 'select_from_array',
            'options' => [
                'virtual' => 'Virtual',
                'presencial' => 'Presencial',
                'distancia' => 'Distancia',
                'dual' => 'Dual',
            ],
            'allows_null' => false,
            'default' => 'presencial',
            'wrapperAttributes' => ['style' => 'display:none', 'id' => 'modalidad_field'],
        ]);




        CRUD::addField([
            'name' => 'tipo_asignatura',
            'label' => 'Tipo de asignatura',
            'type' => 'select_from_array',
            'options' => [
                'practica' => 'Practica',
                'teorica' => 'Teorica',
                'teorica con laboratorio' => 'Teorica con laboratorio',
            ],
            'allows_null' => false,
            'default' => 'presencial',
            'wrapperAttributes' => ['style' => 'display:none', 'id' => 'type_asignatura'],
        ]);


        // Horas presenciales
        CRUD::addField([
            'name' => 'horas_presenciales',
            'label' => 'Horas Presenciales',
            'type' => 'number',
            'attributes' => ['min' => 0, 'onchange' => 'calcularHorasTotales()', 'oninput' => 'calcularHorasTotales()'],
            'wrapperAttributes' => ['style' => 'display:none', 'id' => 'horas_presenciales_field'],
        ]);

        // Horas independientes
        CRUD::addField([
            'name' => 'horas_independientes',
            'label' => 'Horas Independientes',
            'type' => 'number',
            'attributes' => ['min' => 0, 'onchange' => 'calcularHorasTotales()', 'oninput' => 'calcularHorasTotales()'],
            'wrapperAttributes' => ['style' => 'display:none', 'id' => 'horas_independientes_field'],
        ]);

        // Campo para horas totales (no editable)
        CRUD::addField([
            'name' => 'horas_totales',
            'label' => 'Horas Totales',
            'type' => 'number',
            'attributes' => ['readonly' => 'readonly'], // Campo no editable
            'wrapperAttributes' => ['style' => 'display:none', 'id' => 'horas_totales_field'],
        ]);

        // Agregar el script para mostrar el segundo paso al hacer clic en el botón
        CRUD::addField([
            'name' => 'custom_script',
            'type' => 'custom_html',
            'value' => '
                <script>
                    document.getElementById("goToStep2").addEventListener("click", function(e) {
                        e.preventDefault();
                        document.getElementById("step2Fields").style.display = "block";
                        document.getElementById("area_formacion").style.display = "block";
                        document.getElementById("creditos_academicos").style.display = "block";
                        document.getElementById("modalidad_field").style.display = "block";

                        document.getElementById("horas_totales_field").style.display = "block";
                        document.getElementById("horas_independientes_field").style.display = "block";
                        document.getElementById("horas_presenciales_field").style.display = "block";
                        document.getElementById("type_asignatura").style.display = "block";
                        this.style.display = "none";
                    });


                    function calcularHorasTotales() {
    var horasPresenciales = document.getElementById("horas_presenciales_field").querySelector("input");
    var horasIndependientes = document.getElementById("horas_independientes_field").querySelector("input");
    var horasTotales = document.getElementById("horas_totales_field").querySelector("input");

    if (horasPresenciales && horasIndependientes && horasTotales) {
        var presencialesValue = parseInt(horasPresenciales.value) || 0;
        var independientesValue = parseInt(horasIndependientes.value) || 0;
        horasTotales.value = presencialesValue + independientesValue;
    }
}
                </script>',
        ]);
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */

    function getPrerequisitosOptions()
    {
        // Puedes agregar lógica para cargar las opciones dinámicamente, por ejemplo desde una base de datos

        if (!empty(old('facultad_id'))) {
            # code...
            dd(old('facultad_id'));
        }

        return [
            'Ninguna' => 'Ninguna',
            'universitario' => 'Universitario',
            'secundario' => 'Secundario',
            'tecnico' => 'Técnico',
        ];
    }
    protected function setupUpdateOperation()
    {
        if (!backpack_auth()->check() || !backpack_user()->hasRole('admin')) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        $this->setupCreateOperation();
    }

    // Dentro de la clase AsignaturasCrudController

    public function getAsignaturas(Request $request)
    {
        $facultadId = $request->input('facultad_id');

        if (!$facultadId) {
            return response()->json([]);
        }

        $asignaturas = Asignaturas::where('facultad_id', $facultadId)
            ->select('id', 'nombre as text') // Formato requerido para select2
            ->get();

        if ($asignaturas->isEmpty()) {
            // Retorna una opción indicando que no hay asignaturas
            return response()->json([['id' => '', 'text' => 'Ninguna asignatura encontrada']]);
        } else {
            // Retorna las asignaturas encontradas
            return response()->json($asignaturas);
        }
    }



    protected function setupShowOperation()
    {




        // logica roles: elimiar update y delete
        if (backpack_user()->hasRole('docente')) {

            $this->crud->removeButton('update');
            $this->crud->removeButton('delete');
            $this->crud->removeButton('activity');
        }


        // borones action
        $this->crud->addButtonFromView('line', 'asistencia', 'asistencia');
        $this->crud->addButtonFromView('line', 'activity', 'actividad');
        $this->crud->addButtonFromView('line', 'assigment_students', 'assigment_students', 'beginning');

        // eliminar campos select
        $this->crud->removeColumn('facultad_id');
        $this->crud->removeColumn('carrera_id');
        $this->crud->removeColumn('user_id');



        // agregar select de docente
        // $this->crud->addColumn([
        //     'name' => 'user_id',
        //     'label' => 'Docente',
        //     'type' => 'text',
        //     'value' => function ($entry) {

        //         return $entry->docente ? $entry->docente->name : 'N/A'; // Asegúrate de que exista la relación
        //     },
        // ]);

        // Campos básicos
        $this->crud->addColumn(['name' => 'nombre', 'label' => 'Nombre de la Asignatura', 'type' => 'text']);
        $this->crud->addColumn(['name' => 'codigo', 'label' => 'Código', 'type' => 'text']);
        $this->crud->addColumn(['name' => 'creditos_academicos', 'label' => 'Créditos Académicos', 'type' => 'number']);
        $this->crud->addColumn(['name' => 'catalogo', 'label' => 'Catálogo', 'type' => 'text']);



        // Mostrar Facultad
        $this->crud->addColumn([
            'name' => 'facultad_id',
            'label' => 'Facultad',
            'type' => 'text',
            'value' => function ($entry) {


                return $entry->facultad ? $entry->facultad->nombre : 'N/A';
            },
        ]);



        // Mostrar Programa Académico
        $this->crud->addColumn([
            'name' => 'carrera_id',
            'label' => 'Carrera',
            'type' => 'text',
            'value' => function ($entry) {

                return $entry->carrera ? $entry->carrera->nombre : 'N/A';
            },
        ]);
    }


    public function getCarrerasByFacultad($facultadId)
    {
        $carreras = Carrera::where('facultad_id', $facultadId)
            ->with('asignaturas') // Cargar las asignaturas relacionadas
            ->get();

        return response()->json($carreras);
    }

    public function getAsignaturasByCarrera($carreraId)
    {
        $asignaturas = Asignaturas::where('carrera_id', $carreraId)->get(['id', 'nombre', 'codigo']);
        return response()->json($asignaturas);
    }

    protected function store()
    {

        try {
            DB::beginTransaction();

            $asignatura = Asignaturas::create([
                'nombre' => request()->nombre,
                'codigo' => request()->codigo,
                'competencia' => request()->competencia,
                'descripcion_competencia' => request()->descripcion_competencia,
                'justificacion' => request()->justificacion,
                'facultad_id' => request()->facultad_id,
                'carrera_id' => request()->carrera_id,
                'prerequisitos' => request()->prerequisitos,
                'correquisitos' => request()->correquisitos,
                'area_formacion' => request()->area_formacion,
                'tipo_asignatura' => request()->tipo_asignatura,
                'nivel_formacion' => request()->nivel_formacion,
                'modalidad' => request()->modalidad,
                'creditos_academicos' => request()->creditos_academicos,
                'horas_presenciales' => request()->horas_presenciales,
                'horas_independientes' => request()->horas_independientes,
                'horas_totales' => request()->horas_totales,
                'catalogo' => request()->catalogo
            ]);

            if (!$asignatura) {

            }

            AsignaturaDocente::create([
                'asignatura_id' => $asignatura->id,
                'docente_id' => request()->asignaturasDocentes
            ]);

            DB::commit();

        Alert::success('Asignatura agregada exitosamente')->flash();

            return redirect()->back();

        } catch (Exception $e) {
            DB::rollBack();
            Alert::error('No se pudo agregar la asignatura')->flash();
            return redirect()->back();

        }


        // if (request()->has('docente_id')) {
        //     \App\Models\AsignaturaDocente::create([
        //         'asignatura_id' => $entry->id,
        //         'user_id'      => request('docente_id'),
        //     ]);
        // }
    }

}
