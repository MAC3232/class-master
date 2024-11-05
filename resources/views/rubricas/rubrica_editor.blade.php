@extends(backpack_view('blank'))

@section('head')
<style>
    * {
        box-sizing: border-box !important;
    }

    @media print {
        .d-print-none {
            display: none !important;
            /* Utiliza !important para asegurar que se aplique */
        }


    }
</style>
@endsection

@section('header')
<section class="content-header">
    <h1 class="text-light">
        Diseñador de Rúbrica
    </h1>
    <ol class="breadcrumb m-2">
        <li><a href="{{ backpack_url() }}">Panel</a></li>

        <li class="active">{{ $asignatura->nombre }}</li>
        <li>

            <a href="{{ route('rubrica.disenador', $asignatura->id) }}" class="p-2 btn-link">
                < Volver a la rubrica </a>
        </li>
    </ol>
</section>

<div class="d-flex justify-content-start mb-4">
    {{-- Botón de Imprimir --}}
    <button onclick="window.print()" class="btn btn-secondary m-2 ">Imprimir Rúbrica</button>

    {{-- Botón de Editar --}}
    <a href="{{ route('rubrica.editor', $asignatura->id) }}" class="btn btn-primary m-2">Editar Rúbrica</a>
</div>
@endsection


@section('content')


<div class="container">
    <h1>Diseñador de Rúbrica - {{ $asignatura->nombre }}</h1>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered avoid-break">

                <!-- Editor  -->
                <tr>
                    <th colspan="4" class="text-center bg-secondary text-white">
                        RESULTADOS DE APRENDIZAJE DEL PROGRAMA AL CUAL TRIBUTA ESTA ASIGNATURA
                        <br>
                        <small>(Trasladar aquí los Resultados de Aprendizaje previstos en el Programa, al cual tributa esta asignatura.)</small>
                    </th>
                </tr>
                <!-- RAS -->
                @if ($asignatura->rubrica->ra !=null)

                @foreach($asignatura->rubrica->ra as $ra)
                <tr>

                    <td> <strong>{{$ra->nombre}} </strong>( corte {{$ra->corte}})</td>
                    <td colspan="3">

                        <div class="d-flex justify-content-between">

                            <div>
                                <p>
                                    {{$ra->descripcion}}
                                </p>

                            </div>

                            <div>

                                <div class="d-print-none p-1"> <a href="{{route('ra.edit' , ['id'=> $ra->id])}}" class="btn btn-success d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 10px;">
                                        <i class="la la-edit" style="font-size: xx-large"></i>
                                    </a>
                                </div>

                                <div class="d-print-none p-1">
                                    <a href="#" onclick="deleteItem('{{ route('ra.destroy', ['id'=> $ra->id]) }}');" class="btn btn-danger d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 10px;">
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

                        <a href="{{route('rarubrica.create', ['id' => $asignatura->id])}}" class="btn btn-primary w-100  ">Agregar nuevo Resultado de aprendizaje</a>

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
                @if ($asignatura->rubrica->ra !=null)

                @foreach($asignatura->rubrica->ra as $ra)
                <tr>
                    <td class="text-center"><strong>{{$ra->nombre}}</strong></td>
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

                                                    {{$criterio->descripcion}}
                                                </p>

                                            </div>

                                            <div>

                                                <div class="d-print-none p-1"> <a href="{{route('criterio.edit', ['id' => $asignatura->id, 'criterio_id'=> $criterio->id])}}" class="btn btn-success d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 10px;">
                                                        <i class="la la-edit" style="font-size: xx-large"></i>
                                                    </a></div>
                                                <div class="d-print-none p-1">
                                                    <a href="#" onclick="deleteItem( '{{route('criterio.destroy', ['id'=> $criterio->id])}}');" class="btn btn-danger d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 10px;">
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
                                        <a href="{{ route('criterio.create', ['id' => $ra->id]) }}" class="btn btn-primary w-100">
                                            Agregar criterio
                                        </a>
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


                                            <div class="d-print-none p-1"> <a href="{{route('estrategia.edit', ['id' => $estrategia->id])}}" class="btn btn-success d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 10px;">
                                                    <i class="la la-edit" style="font-size: xx-large"></i>
                                                </a></div>
                                        </div>
                                        <div class="d-print-none p-1">
                                            <a href="#" onclick="deleteItem('{{ route('estrategia.destroy', ['id'=> $estrategia->id]) }}');" class="btn btn-danger d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 10px;">
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
                                <a href="{{ route('estrategia.create', parameters: $ra->id) }}" class="btn btn-primary w-100">Agregar Estrategia</a>

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

                                            <div class="d-print-none p-1"> <a href="{{route('eje_contenido.edit', ['id' => $eje_contenido->id])}}" class="btn btn-success d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 10px;">
                                                    <i class="la la-edit" style="font-size: xx-large"></i>
                                                </a></div>

                                        </div>

                                        <div class="d-print-none p-1">
                                            <a href="#" onclick="deleteItem('{{ route('eje_contenido.destroy', ['id'=> $eje_contenido->id]) }}');" class="btn btn-danger d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 10px;">
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

                                <a href="{{ route('eje_contenido.create', parameters: $ra->id) }}" class="btn btn-primary w-100">Agregar Estrategia</a>


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
                    El QUÉ y HASTA DÓNDE, aprende el estudiante, tiene que ver con el desarrollo evaluativo desde la línea base hasta los procesos metacognitivos y de dominio de las competencias. La evaluación en el modelo pedagógico se asume como comprensión de la acción desplegada por el estudiante en su aprendizaje. Para ello es esencial trascender la heteroevaluación como única forma de evaluar o estimar el aprendizaje, se requiere incorporar, generando una nueva cultura de la evaluación, formas de coevaluación o valoración de pares y el autoevaluación que realiza el mismo actor del proceso, es decir el estudiante.
                </td>
            </tr>
            <tr>
                <td style="padding: 0;" colspan="4">

                    <div class="d-flex ">

                        <div class="col d-flex flex-column  ">

                            <div class="d-flex col">
                                <div class="col-md-2 "><strong>Corte</strong></div>

                                <div class="col-md-2 border-bottom border-start"><strong>Ponderacion</strong></div>
                                <div class="col-md border-bottom  border-start p-2"><strong>MECANISMOS DE EVALUACIÓN, EVIDENCIAS Y SEGUIMIENTO CON PARTICIPACIÓN PORCENTUAL DENTRO DEL CORTE</strong></div>
                                <div class="col border-bottom border-start p-2"><Strong>RESULTADO DE APRENDIZAJE QUE EVALÚA</Strong></div>


                            </div>
                            @foreach (range(1, 3) as $corte)

                            <div class="d-flex col ">
                                <div class="col-md-2 border-top p-2 d-flex  align-items-center  justify-content-center">Corte {{$corte}}</div>

                                <div class="col-md-2 border-start border-top align-items-center d-flex justify-content-center">{{ $corte == 3 ? '30%' : '35%' }}</div>
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
                                                <div class="col border-end p-2">{{$actividad->nombre}}</div>
                                                <div class="col p-2">{{$actividad->ponderacion}}</div>
                                            </div>

                                            @endforeach
                                            <div class="d-flex border-top ">
                                                <div class="col border-end">
                                                    <div class="">
                                                        <div colspan="4" class="text-center">
                                                            <a href="{{ route('actividad.create', $asignatura->id) }}" class="btn btn-link">Quieres agregar una actividad?</a>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            @else
                                            <div class="d-flex border-top ">
                                                <div class="col border-end">
                                                    <div class="bg-warning-subtle">
                                                        <div colspan="4" class="text-center">
                                                            No hay Actividades para mostrar en este RA. <a href="{{ route('actividad.create', $asignatura->id) }}" class="btn btn-link">Quieres añadir una actividad?</a>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            @endif

                                        </div>
                                        <div class="col border-start border-top">{{$ra->nombre}} (Corte {{$ra->corte}})</div>


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
                        location.reload(); // Recargar la página o manejar la eliminación en la vista
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