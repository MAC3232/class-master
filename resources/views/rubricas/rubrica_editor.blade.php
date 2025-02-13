@extends(backpack_view('blank'))


@php

$breadcrumbs = [
        trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
        'Asignaturas' => route('asignaturas.index'),
        'panel asignatura' => route('asignaturas.show',['id' => $asignatura->id]),
        'Listado' => false, // El último elemento no lleva URL
    ];
@endphp
@section('head')
    <style>
        * {
            box-sizing: border-box !important;
        }
       F
    </style>
@endsection

@section('header')
    <section class="content-header">
        <h1 class="text-light">
            
        </h1>
        <ol class="breadcrumb m-2">
            <li><a href="{{ backpack_url() }}">Panel</a></li>

            <li class="active">{{ $asignatura->nombre }}</li>
            <li>

                <a href="{{ route('rubrica.disenador', $asignatura->id) }}" class="p-2 btn-link">
                    < Volver a la syllabus  </a>
            </li>
        </ol>
    </section>

    <div class="d-flex justify-content-start mb-4">
        {{-- Botón de Imprimir --}}
        <button onclick="window.print()" class="btn btn-primary m-2 ">Imprimir syllabus </button>
    </div>
@endsection


@section('content')


    <div class="container">
        <h1>Diseñador de syllabus  - {{ $asignatura->nombre }}</h1>
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered avoid-break">

                    <!-- Editor  -->
                    <tr>
                        <th colspan="4" class="text-center bg-secondary text-white">
                            RESULTADOS DE APRENDIZAJE DEL PROGRAMA AL CUAL TRIBUTA ESTA ASIGNATURA
                            <br>
                            <small>(Trasladar aquí los Resultados de Aprendizaje previstos en el Programa, al cual tributa
                                esta asignatura.)</small>
                        </th>
                    </tr>
                    <!-- RAS -->
                    @if ($asignatura->rubrica->ra != null)
                        @foreach ($asignatura->rubrica->ra as $ra)
                            <tr>

                                <td> <strong>{{ $ra->nombre }} </strong>( corte {{ $ra->corte }})</td>
                                <td colspan="3">

                                    <div class="d-flex justify-content-between">

                                        <div>
                                            <p>
                                                {{ $ra->descripcion }}
                                            </p>

                                        </div>

                                        <div>

                                            <div class="d-print-none p-1">

                                                <x-form-modal-component :action-url="route('ra.update', [$ra->id,$asignatura->rubrica->id]) " :fields="[
                                                    [
                                                        'name' => 'nombreEditar-' . $ra->id,
                                                        'type' => 'text',
                                                        'label' => 'Nombre del Resultado de Aprendizaje',
                                                        'value' => $ra->nombre,
                                                    ],
                                                    [
                                                        'name' => 'Ra-' . $ra->id,
                                                        'type' => 'textarea',
                                                        'label' => 'Editar descripcion',
                                                        'value' => $ra->descripcion ?? '', // Valor actual para editar
                                                          'maxLength' => '1000'
                                                    ],

                                                    [
                                                        'name' => 'corte',
                                                        'type' => 'select',
                                                        'label' => 'Corte',

                                                        'options' => [
                                                            ['value' => $ra->corte, 'label' => 'Corte '. $ra->corte],
                                                            ['value' => '1', 'label' => 'Corte 1'],
                                                            ['value' => '2', 'label' => 'Corte 2'],
                                                            ['value' => '3', 'label' => 'Corte 3'],
                                                        ],
                                                    ],



                                                ]"
                                                    :idField="[
                                                    'errors'=> $errors->first('RAEditar'.$ra->id) ?? null,
                                                        'method'=>'PUT',
                                                        'name' => 'Editar RA: '.$ra->nombre.'( Corte '. $ra->corte .')',
                                                        'icon' => 'la la-edit',
                                                        'class' =>
                                                            'btn btn-success d-flex align-items-center justify-content-center rounded-3',
                                                        'open' => 'AbrirEditarRa-' . $ra->id,
                                                        'modal' => 'modal-EditarRa-' . $ra->id,
                                                    ]" />


                                            </div>

                                            <div class="d-print-none p-1">
                                                <a href="#"
                                                    onclick="deleteItem('{{ route('ra.destroy', ['id' => $ra->id])  }}')"
                                                    class="btn btn-danger  d-flex align-items-center justify-content-center  rounded-3 ">
                                                    <i class="la la-trash" style="font-size: xx-large"></i>
                                                </a>
                                            </div>

                                        </div>

                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    @endif

                    <!-- fila para  agregar un nuevo RA -->
                    <tr>
                        <td colspan="4" class="d-print-none">

                            <x-form-modal-component :idField="[
                            'method'=>'POST',
                             'boton'=> 'Agregar nuevo RA' ,
                                'name' => 'Agregar nuevo RA',
                                'open' => 'AbrirRA',
                                'modal' => 'modal-RA',
                                 'errors'=> $errors->first('RAEditar') ?? null,
                            ]" :action-url="route('rarubrica.store', ['id' => $asignatura->id, 'id_rubrica' => $asignatura->rubrica->id])" :fields="[
                                [
                                    'name' => 'nombre',
                                    'type' => 'text',
                                    'label' => 'Nombre del Resultado de Aprendizaje',
                                ],
                                ['name' => 'descripcion', 'type' => 'textarea', 'label' => 'Descripción',  'maxLength' => '1000'],
                                [
                                    'name' => 'corte',
                                    'type' => 'select',
                                    'label' => 'Corte',
                                    'options' => [
                                        ['value' => '1', 'label' => 'Corte 1'],
                                        ['value' => '2', 'label' => 'Corte 2'],
                                        ['value' => '3', 'label' => 'Corte 3'],
                                    ],
                                ],

                                [
                                    'name' => 'rubrica_id',
                                    'type' => 'hidden',
                                    'value' => $asignatura->rubrica->id,
                                    'label' => '',
                                ],

                            ]" />
                        </td>
                    </tr>
                    <tr>






                        <!-- Resultados de aprendizajes, criterios etc... -->
                    <tr>
                        <th class="text-center">RESULTADOS DE APRENDIZAJE QUE SE EVIDENCIARON EN ESTA ASIGNATURA</th>
                        <th class="text-center">CRITERIOS DE EVALUACIÓN</th>
                        <th class="text-center">ESTRATEGIAS DE ENSEÑANZA-APRENDIZAJE</th>
                        <th class="text-center">EJE DE CONTENIDOS</th>
                    </tr>
                    @if ($asignatura->rubrica->ra != null)
                        @foreach ($asignatura->rubrica->ra as $ra)
                            <tr>
                                <td class="text-center"><strong>{{ $ra->nombre }}</strong></td>
                                <!-- Criterios de evaluacion -->

                                <td style="padding: 0">

                                    <table class="table table-bordered avoid-break">

                                        <tbody>
                                            @foreach ($ra->criterios as $criterio)
                                                <tr>
                                                    <td>

                                                        <div class="d-flex justify-content-between ">

                                                            <div>
                                                                <p>

                                                                    {{ $criterio->descripcion }}
                                                                </p>

                                                            </div>

                                                            <div>

                                                                <div class="d-print-none p-1">
                                                                    <x-form-modal-component :action-url="route('criterio.update', $criterio->id)"
                                                                        :fields="[
                                                                            [
                                                                                'name' => 'CriterioEdit-' . $criterio->id,
                                                                                'type' => 'textarea',
                                                                                'label' => 'Editar descripcion',
                                                                                'value' => $criterio->descripcion ?? '',
                                                                                  'maxLength' => '500'
                                                                            ],
                                                                        ]" :idField="[
                                                                        'method'=> 'PUT' ,

                                                                            'name' => 'Editar criterio de RA: '.$ra->nombre,
                                                                            'icon' => 'la la-edit',
                                                                            'class' =>
                                                                                'btn btn-success d-flex align-items-center justify-content-center rounded-3',
                                                                            'open' =>
                                                                                'AbrirEditarcriterio-' . $criterio->id,
                                                                            'modal' =>
                                                                                'modal-Editarcriterio-' . $criterio->id,
                                                                        ]" />


                                                                </div>
                                                                <div class="d-print-none p-1">
                                                                    <a href="#"
                                                                        onclick="deleteItem('{{ route('criterio.destroy', ['id' => $criterio->id]) }}');"
                                                                        class="btn btn-danger  d-flex align-items-center justify-content-center  rounded-3 ">
                                                                        <i class="la la-trash" style="font-size: xx-large"></i>
                                                                    </a>
                                                                </div>

                                                            </div>

                                                    </td>

                                                </tr>
                                            @endforeach



                                            <tr>


                                            <tr class="d-print-none">
                                                <!-- Boton para agregar nuevo criterio perteneciente a un RA -->
                                                <td>
                                                    <x-form-modal-component :action-url="route('criterio.store', ['id' => $ra->id])" :fields="[
                                                        [
                                                            'name' => 'descripcion-' . $ra->id,
                                                            'type' => 'textarea',
                                                            'label' => 'Descripción del Criterio',
                                                            'maxLength' => '250'
                                                        ],
                                                    ]"
                                                        :idField="[
                                                            'boton'=> 'Agregar nuevo criterio',
                                                            'name' => 'Agregar nuevo criterio para el RA:'.$ra->nombre,
                                                            'open' => 'AbrirCriterio-' . $ra->id,
                                                            'modal' => 'modal-criterio-' . $ra->id,
                                                        ]" />
                                                </td>
                                            </tr>


                            </tr>

                            </tbody>
                </table>


                </td>
                <!-- Fin Criterio -->
                <!-- Inicio Estrategias de enseñanza -->
                <td style="padding: 0;">
                    <table class="table table-bordered avoid-break">

                        <tbody>
                            @forelse($ra->estrategias as $estrategia)
                                <tr>
                                    <td>

                                        <div class="d-flex justify-content-between">

                                            <div>
                                                <p>
                                                    {{ $estrategia->descripcion }}
                                                </p>

                                            </div>
                                            <div>
                                                <div>

                                                    <div class="d-print-none p-1">


                                                    <x-form-modal-component :action-url="route('estrategia.update', $estrategia->id)" :fields="[
                                                        [
                                                            'name' => 'EestrategiaEditar-' . $estrategia->id,
                                                            'type' => 'textarea',
                                                            'label' => 'Editar descripcion',
                                                            'value' => $estrategia->descripcion ?? '',
                                                            'maxLength' => '250'
                                                        ],
                                                    ]"
                                                        :idField="[
                                                        'method'=> 'PUT',
                                                            'name' => ' Editar Estrategia de aprendizaje para el RA: '. $ra->nombre.' ( corte'.$ra->corte.')',

                                                            'icon' => 'la la-edit',
                                                            'class' =>
                                                                'btn btn-success d-flex align-items-center justify-content-center rounded-3',
                                                            'open' => 'AbrirEditarEstrategia-' . $estrategia->id,
                                                            'modal' => 'modal-EditarEstrategia-' . $estrategia->id,
                                                        ]" />
                                                    </div>
                                                </div>
                                                <div class="d-print-none p-1">
                                                    <a href="#"
                                                        onclick="deleteItem('{{ route('estrategia.destroy', ['id' => $estrategia->id]) }}');"
                                                        class="btn btn-danger  d-flex align-items-center justify-content-center  rounded-3 ">
                                                        <i class="la la-trash" style="font-size: xx-large"></i>
                                                    </a>
                                                </div>
                                            </div>


                                        </div>

                                    </td>

                                </tr>
                            @empty
                                <li>No hay estrategias registradas para este RA.</li>
                            @endforelse
                            <tr>


                            <tr class="d-print-none">
                                <!-- Boton para agregar nueva estrategia de enseñanza a un RA -->
                                <td>

                                    <x-form-modal-component :action-url="route('estrategia.store', $ra->id)" :fields="[
                                        [
                                            'name' => 'Estrategia-' . $ra->id,
                                            'type' => 'textarea',
                                            'label' => 'Descripción de la Estrategia',
                                            'maxLength' => '250'
                                        ],
                                    ]" :idField="[
                                    'boton'=> 'Agregar Estrategia',
                                        'name' => ' Agregar Estrategia de aprendizaje para el RA: '. $ra->nombre.' ( corte'.$ra->corte.')',
                                        'open' => 'AbrirEstrategia-' . $ra->id,
                                        'modal' => 'modal-Estrategia-' . $ra->id,
                                    ]" />
                                </td>
                            </tr>


                            </tr>

                        </tbody>
                    </table>






                </td>

                <!-- Fin Estrategias de aprendizaje -->

                <!-- Inicio  Eje de contenidos-->
                <td style="padding: 0;">
                    <table class="table table-bordered avoid-break">

                        <tbody>
                            @forelse($ra->ejesContenido as $eje_contenido)
                                <tr>
                                    <td>

                                        <div class="d-flex justify-content-between">

                                            <div>
                                                <p>
                                                    {{ $eje_contenido->descripcion }}
                                                </p>

                                            </div>

                                            <div>
                                                <div>

                                                    <div class="d-print-none p-1">

                                                        <x-form-modal-component :action-url="route('eje_contenido.update', $eje_contenido->id)" :fields="[
                                                            [
                                                                'name' => 'EJeContenido-' . $eje_contenido->id,
                                                                'type' => 'textarea',
                                                                'label' => 'Editar descripcion',
                                                                'value' => $eje_contenido->descripcion ?? '',
                                                                'maxLength' => '250'
                                                            ],
                                                        ]"
                                                            :idField="[
                                                            'method'=> 'PUT',

                                        'name' => ' Agregar Contenido para el RA: '. $ra->nombre.' ( corte'.$ra->corte.')',

                                                                'icon' => 'la la-edit',
                                                                'class' =>
                                                                    'btn btn-success d-flex align-items-center justify-content-center rounded-3',
                                                                'open' => 'AbrirEditarContenido-' . $eje_contenido->id,
                                                                'modal' =>
                                                                    'modal-EditarContenido-' . $eje_contenido->id,
                                                            ]" />

                                                    </div>



                                                </div>

                                                <div class="d-print-none p-1">
                                                    <a href="#"
                                                        onclick="deleteItem('{{ route('eje_contenido.destroy', ['id' => $eje_contenido->id]) }}');"
                                                        class="btn btn-danger  d-flex align-items-center justify-content-center  rounded-3 ">
                                                        <i class="la la-trash" style="font-size: xx-large"></i>
                                                    </a>
                                                </div>
                                            </div>

                                        </div>


                                    </td>

                                </tr>
                            @empty
                                <li>No hay estrategias registradas para este RA.</li>
                            @endforelse
                            <tr>


                                <!-- Boton para agregar nuevo contenido a un RA -->
                            <tr class="d-print-none">
                                <td>


                                    <x-form-modal-component :action-url="route('eje_contenido.store', $ra->id)" :fields="[
                                        [
                                            'name' => 'Contenido-' . $ra->id,
                                            'type' => 'textarea',
                                            'label' => 'Descripción del eje de contenido',
                                            'maxLength' => '250'
                                        ],
                                    ]" :idField="[
                                       'boton'=> 'Agregar contenido',
                                        'name' => 'Agregar un contenido',
                                        'open' => 'AbrirContenido-' . $ra->id,
                                        'modal' => 'modal-Contenido-' . $ra->id,
                                    ]" />

                                </td>
                            </tr>

                            </tr>

                        </tbody>
                    </table>


                </td>


                </tr>
                @endforeach
                @endif








                <th colspan="4" class="text-center bg-secondary text-white">
                    SISTEMA DE EVALUACIÓN DE LOS RESULTADOS DE APRENDIZAJE
                </th>
                </tr>
                <tr>
                    <td colspan="4" class="text-justify">
                        <strong>CRITERIOS DE EVALUACIÓN Y PAUTAS DE REFERENCIA</strong><br>
                        El QUÉ y HASTA DÓNDE, aprende el estudiante, tiene que ver con el desarrollo evaluativo desde la
                        línea base hasta los procesos metacognitivos y de dominio de las competencias. La evaluación en el
                        modelo pedagógico se asume como comprensión de la acción desplegada por el estudiante en su
                        aprendizaje. Para ello es esencial trascender la heteroevaluación como única forma de evaluar o
                        estimar el aprendizaje, se requiere incorporar, generando una nueva cultura de la evaluación, formas
                        de coevaluación o valoración de pares y el autoevaluación que realiza el mismo actor del proceso, es
                        decir el estudiante.
                    </td>
                </tr>
                <tr>
                    <td style="padding: 0;" colspan="4">

                        <div class="d-flex ">

                            <div class="col d-flex flex-column  ">

                                <div class="d-flex col">
                                    <div class="col-md-2 "><strong>Corte</strong></div>

                                    <div class="col-md-2 border-bottom border-start"><strong>Ponderacion</strong></div>
                                    <div class="col-md border-bottom  border-start p-2"><strong>MECANISMOS DE EVALUACIÓN,
                                            EVIDENCIAS Y SEGUIMIENTO CON PARTICIPACIÓN PORCENTUAL DENTRO DEL CORTE</strong>
                                    </div>
                                    <div class="col border-bottom border-start p-2"><Strong>RESULTADO DE APRENDIZAJE QUE
                                            EVALÚA</Strong></div>


                                </div>
                                @foreach (range(1, 3) as $corte)
                                    <div class="d-flex col ">
                                        <div
                                            class="col-md-2 border-top p-2 d-flex  align-items-center  justify-content-center">
                                            Corte {{ $corte }}</div>

                                        <div
                                            class="col-md-2 border-start border-top align-items-center d-flex justify-content-center">
                                            {{ $corte == 3 ? '30%' : '35%' }}</div>
                                        @if ($asignatura->rubrica->ra->where('corte', $corte)->isNotEmpty())
                                            <div class="col d-flex flex-column border-start border-top">
                                                @foreach ($asignatura->rubrica->ra->where('corte', $corte) as $ra)
                                                    <div class="col d-flex">
                                                        <div class="col ">
                                                            <div class="d-flex ">
                                                                <div class="col border-end bg-danger p-2">Actividades</div>
                                                                <div class="col bg-danger p-2">Ponderacion</div>
                                                            </div>

                                                            @if ($ra->actividades->isNotEmpty())
                                                                @foreach ($ra->actividades as $actividad)
                                                                    <div class="d-flex border-top ">
                                                                        <div class="col border-end p-2">
                                                                            {{ $actividad->nombre }}</div>
                                                                        <div class="col p-2">{{ $actividad->ponderacion }}
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                                <div class="d-flex border-top ">
                                                                    <div class="col border-end">
                                                                        <div class="">
                                                                            <div colspan="4" class="text-center">
                                                                                <a href="{{ route('actividad.create', $asignatura->id) }}"
                                                                                    class="btn btn-link">Quieres agregar
                                                                                    una actividad?</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            @else
                                                                <div class="d-flex border-top ">
                                                                    <div class="col border-end">
                                                                        <div class="bg-warning-subtle">
                                                                            <div colspan="4" class="text-center">
                                                                                No hay Actividades para mostrar en este RA.
                                                                                <a href="{{ route('actividad.create', $asignatura->id) }}"
                                                                                    class="btn btn-link">Quieres añadir una
                                                                                    actividad?</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            @endif

                                                        </div>
                                                        <div class="col border-start border-top">{{ $ra->nombre }}
                                                            (Corte {{ $ra->corte }})</div>


                                                    </div>
                                                @endforeach


                                            </div>
                                        @else
                                            <div class="d-flex border-top col bg-warning-subtle -mt-2 ">
                                                <div class="col border-end">
                                                    <div class="bg-warning-subtle">
                                                        <div colspan="4" class="text-center">
                                                            No hay RAS para mostrar en este corte.
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        @endif



                                    </div>
                                @endforeach


                            </div>


                        </div>

                    </td>
                </tr>


                </tbody>
                </table>


            </div>


        </div>

    </div>


@endsection

@section('after_scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function deleteItem(route) {



            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminarlo!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: route, // Ajusta la URL según tu ruta
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(result) {
                            Swal.fire(
                                'Eliminado!',
                                'El elemento ha sido eliminado.',
                                'success'
                            );
                            location
                        .reload(); // Recargar la página o manejar la eliminación en la vista
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                'Ocurrió un error al eliminar el elemento.',
                                'error'
                            );
                        }
                    });
                }
            });
        }
    </script>
@endsection
