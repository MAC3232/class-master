<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AsignaturasRequest;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

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
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setValidation(AsignaturasRequest::class);
        if (backpack_user()->hasRole('docente')) {
            // Mostrar solo las asignaturas del docente
            $this->crud->addClause('where', 'user_id', backpack_user()->id);

            // Quitar botones de edición y eliminación para el rol docente
            $this->crud->removeButton('update'); // Eliminar botón de editar
            $this->crud->removeButton('delete'); // Eliminar botón de eliminar
        }

        // Filtrar asignaturas si el usuario es docente


        // Columnas a mostrar en la lista de asignaturas
        $this->crud->addColumn(['name' => 'nombre', 'label' => 'Nombre', 'type' => 'text']);
        $this->crud->addColumn(['name' => 'creditos_academicos', 'label' => 'Créditos', 'type' => 'text']);
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
        CRUD::setValidation(AsignaturasRequest::class);

        // Primera parte del formulario
        CRUD::addField([
            'name' => 'step1',
            'type' => 'custom_html',
            'value' => '<h3>Paso 1: Información básica</h3>',
        ]);

        CRUD::addField(['name' => 'nombre', 'label' => 'Nombre de la Asignatura', 'type' => 'text']);
        CRUD::addField(['name' => 'codigo', 'label' => 'Código', 'type' => 'text']);
        CRUD::addField(['name' => 'catalogo', 'label' => 'Catalogo', 'type' => 'text']);

        // Select para facultad
        $facultades = \App\Models\Facultad::count();
        if ($facultades == 0) {
            CRUD::addField([
                'name' => 'crear_facultad',
                'type' => 'custom_html',
                'value' => '<a href="' . backpack_url('facultad/create') . '" class="btn btn-primary">Crear Facultad</a>',
            ]);
        } else {
            CRUD::addField(['name' => 'facultad_id', 'label' => 'Facultad', 'type' => 'select', 'entity' => 'facultad', 'model' => 'App\Models\Facultad', 'attribute' => 'nombre']);
        }

        // Campo de selección para el docente
        CRUD::addField([
            'label' => "Docente",
            'type' => 'select',
            'name' => 'user_id', // El campo en la base de datos
            'entity' => 'docente', // La relación en el modelo Asignatura
            'model' => "App\Models\User", // Modelo de User para la relación
            'attribute' => 'name', // Campo que queremos mostrar en el select
            'options'   => (function ($query) {
                return $query->role('docente')->get(); // Filtra solo usuarios con rol "docente"
            }), // Filtro para mostrar solo los usuarios que tienen el rol "docente"
        ]);
        // Select para carrera
        $carreras = \App\Models\Carrera::count();
        if ($carreras == 0) {
            CRUD::addField([
                'name' => 'crear_carrera',
                'type' => 'custom_html',
                'value' => '<a href="' . backpack_url('carrera/create') . '" class="btn btn-primary">Crear Carrera</a>',
            ]);
        } else {
            CRUD::addField(['name' => 'carrera_id', 'label' => 'Carrera', 'type' => 'select', 'entity' => 'carrera', 'model' => 'App\Models\Carrera', 'attribute' => 'nombre']);
        }

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
            'name' => 'type_asignatura',
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
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    // Dentro de la clase AsignaturasCrudController

    protected function setupShowOperation()
    {

        CRUD::setFromDb(); // Cargar los campos de la base de datos
        
        
        // logica roles: elimiar update y delete
        if (backpack_user()->hasRole('docente')) {
            
            $this->crud->removeButton('update'); 
            $this->crud->removeButton('delete'); 
            $this->crud->removeButton('activity'); 
        }
        
        
        // borones action
        $this->crud->addButtonFromView('line', 'asistencia', 'asistencia');
        $this->crud->addButtonFromView('line', 'activity', 'actividad');

        // eliminar campos select
        $this->crud->removeColumn('facultad_id');
        $this->crud->removeColumn('carrera_id');
        $this->crud->removeColumn('user_id');



        // agregar select de docente
        $this->crud->addColumn([
            'name' => 'user_id',
            'label' => 'Docente',
            'type' => 'text',
            'value' => function ($entry) {

                return $entry->docente ? $entry->docente->name : 'N/A'; // Asegúrate de que exista la relación
            },
        ]);

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
}
