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

            <a href="/admin/asignaturas/{{$asignatura->id}}/show" class="p-2 btn-link">
                < Volver</a>
        </li>
    </ol>
</section>

@if($tieneRubrica)

<div class="d-flex justify-content-start mb-4">
    {{-- Botón de Imprimir --}}
    <button onclick="window.print()" class="btn btn-secondary m-2 ">Imprimir Rúbrica</button>

    {{-- Botón de Editar --}}
    <a href="{{ route('rubrica.editor', $asignatura->id) }}" class="btn btn-primary m-2">Editar Rúbrica</a>
</div>

@endif
@endsection


@section('content')

@if($tieneRubrica)



<div class="container">
    <h1>Diseñador de Rúbrica - {{ $asignatura->nombre }}</h1>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered avoid-break">
                <thead>
                    <tr class="bg-light">
                        <th colspan="4" class="text-center">CONTENIDO PROGRAMÁTICO DE ASIGNATURA</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>

                        <td><strong>NOMBRE DE LA ASIGNATURA</strong></td>
                        <td>{{ $asignatura->nombre }}</td>
                        <td><strong>CÓDIGO</strong></td>
                        <td>{{ $asignatura->codigo }}</td>
                    </tr>
                    <tr>
                        <td><strong>FACULTAD</strong></td>
                        <td>{{ $asignatura->facultad->nombre }}</td>
                        <td><strong>PROGRAMA</strong></td>
                        <td>{{ $asignatura->carrera->nombre }}</td>
                    </tr>
                    <tr>
                        <td><strong>ÁREA DE FORMACIÓN</strong></td>
                        <td>{{ $asignatura->area_formacion }}</td>
                        <td><strong>NIVEL DE FORMACIÓN</strong></td>
                        <td>
                            @foreach( [0 => "tecnico", 2 => "Tecnologia"] as $nivel)
                            {{ $nivel }},
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td><strong>NÚMERO DE CRÉDITOS ACADÉMICOS</strong></td>
                        <td colspan="3">{{ $asignatura->creditos_academicos }}</td>

                    </tr>
                    <tr>
                        <td><strong>TRABAJO ACADÉMICO</strong></td>
                        <td colspan="3">
                            <table class="table table-bordered avoid-break">
                                <tr>

                                <tr><strong>No. DE HORAS PRESENCIALES E INDEPENDIENTES</strong></tr>
                                <tr>
                                    <td>Horas precenciales</td>
                                    <td>Horas independientes</td>
                                    <td>Horas totales</td>
                                </tr>
                                <td>{{ $asignatura->horas_presenciales }}</td>
                                <td>{{ $asignatura->horas_independientes }}</td>
                                <td>{{ $asignatura->horas_totales }} (Horas Totales)</td>
                    </tr>
            </table>
            </td>
            </tr>
            <tr>
                <td class=".btn-imprimir"><strong>MODALIDAD</strong></td>
                <td>{{ $asignatura->modalidad }}</td>
                <td><strong>TIPO DE ASIGNATURA</strong></td>
                <td>{{ $asignatura->type_asignatura }}</td>
            </tr>
            <tr>
                <td><strong>PRERREQUISITOS</strong></td>
                <td>{{ $asignatura->prerrequisitos }}</td>
                <td><strong>CORREQUISITOS</strong></td>
                <td>{{ $asignatura->correquisitos }}</td>
            </tr>
            <tr>
                <td><strong>FECHA DE ACTUALIZACIÓN</strong></td>
                <td colspan="3">{{ $asignatura->fecha_actualizacion }}</td>
            </tr>

            <tr>
                <th colspan="4" class="text-center bg-secondary text-white">
                    RESULTADOS DE APRENDIZAJE DEL PROGRAMA AL CUAL TRIBUTA ESTA ASIGNATURA
                    <br>
                    <small>(Trasladar aquí los Resultados de Aprendizaje previstos en el Programa, al cual tributa esta asignatura.)</small>
                </th>
            </tr>


            @if ($asignatura->rubrica->ra->isNotEmpty())

            @foreach($asignatura->rubrica->ra as $ra)
            <tr>

                <td> <strong> {{$ra->nombre}} (Corte {{$ra->corte}}) </strong></td>
                <td colspan="3">

                    {{$ra->descripcion}}
                </td>
            </tr>
            @endforeach
            @else

            <tr class="table-warning">
                <td colspan="4" class="text-center">
                    No hay Resultados de aprendizaje (RA) para mostrar. <a href="{{ route('rubrica.editor', $asignatura->id) }}" class="btn btn-link"> Quieres editar la Rúbrica?</a>

                </td>
            </tr>

            @endif

            <tr>
                <th class="text-center">RESULTADOS DE APRENDIZAJE QUE SE EVIDENCIARON EN ESTA ASIGNATURA</th>
                <th class="text-center">CRITERIOS DE EVALUACIÓN</th>
                <th class="text-center">ESTRATEGIAS DE ENSEÑANZA-APRENDIZAJE</th>
                <th class="text-center">EJE DE CONTENIDOS</th>
            </tr>
            @if ($asignatura->rubrica->ra->isNotEmpty())

            @foreach($asignatura->rubrica->ra as $ra)

            <tr>
                <td class="text-center"><strong>{{$ra->nombre}} (Corte {{$ra->corte}})</strong></td>

                <td style="padding: 0;">


                    <table class="table table-bordered avoid-break">

                        <tbody>
                            @if ($ra->criterios->isNotEmpty())
                            @foreach ($ra->criterios as $criterio)
                            <tr>
                                <td>


                                    <div>
                                        <p>
                                            {{$criterio->descripcion}}

                                        </p>

                                    </div>


                                </td>

                            </tr>
                            @endforeach
                            @else
                            <tr class="table-warning">
                                <td colspan="4" class="text-center">
                                    No hay Criterios de evaluacion para mostrar. <a href="{{ route('rubrica.editor', $asignatura->id) }}" class="btn btn-link"> Quieres editar la Rúbrica?</a>
                                </td>
                            </tr>
                            @endif


                        </tbody>
                    </table>
                </td>
                <td style="padding: 0;">

                    <table class="table table-bordered avoid-break">

                        <tbody>
                            @if ($ra->estrategias->isNotEmpty())
                            @foreach ($ra->estrategias as $estrategia)
                            <tr>
                                <td>


                                    <div>
                                        <p>
                                            {{$estrategia->descripcion}}

                                        </p>

                                    </div>


                                </td>

                            </tr>
                            @endforeach
                            @else
                            <tr class="table-warning">
                                <td colspan="4" class="text-center">
                                    No hay Criterios de evaluacion para mostrar. <a href="{{ route('rubrica.editor', $asignatura->id) }}" class="btn btn-link"> Quieres editar la Rúbrica?</a>
                                </td>
                            </tr>
                            @endif


                        </tbody>
                    </table>
                </td>
                <td style="padding: 0;">

                    <table class="table table-bordered avoid-break">

                        <tbody>
                            @if ($ra->ejesContenido->isNotEmpty())
                            @foreach ($ra->ejesContenido as $contenido)
                            <tr>
                                <td>


                                    <div>
                                        <p>
                                            {{$contenido->descripcion}}

                                        </p>

                                    </div>


                                </td>

                            </tr>
                            @endforeach
                            @else
                            <tr class="table-warning">
                                <td colspan="4" class="text-center">
                                    No hay Criterios de evaluacion para mostrar. <a href="{{ route('rubrica.editor', $asignatura->id) }}" class="btn btn-link"> Quieres editar la Rúbrica?</a>
                                </td>
                            </tr>
                            @endif


                        </tbody>
                    </table>
                </td>
            </tr>
            @endforeach

            @else
            <tr class="table-warning">
                <td colspan="4" class="text-center">
                    No hay Resultados de aprendizaje (RA) para mostrar. <a href="{{ route('rubrica.editor', $asignatura->id) }}" class="btn btn-link"> Quieres editar la Rúbrica?</a>
                </td>
            </tr>


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
                <div class="d-flex">
    <div class="col d-flex flex-column">
        <table class="table table-bordered">
            <!-- Encabezado de la tabla -->
            <thead>
                <tr>
                    <th class="col-md-1"><strong>Corte</strong></th>
                    <th class="col-md-1"><strong>Ponderación</strong></th>
                    <th class="col-md-1"><strong>MECANISMOS DE EVALUACIÓN</strong></th>
                    <th class="col-md-2"><strong>RESULTADO DE APRENDIZAJE QUE EVALÚA</strong></th>
                    <th class="col-md-1"><strong>Ponderación del resultado de aprendizaje</strong></th>
                    <th class="col-md-1"><strong>Total del Corte</strong></th>
                </tr>
            </thead>
            <tbody>
                @foreach (range(1, 3) as $corte)
                    @php
                        $ponderacionCorte = $corte == 3 ? 0.3 : 0.35; // 30% o 35%
                        $totalCorte = 0; // Total acumulado del corte
                    @endphp
                    <tr>
                        <td class="text-center align-middle">Corte {{ $corte }}</td>
                        <td class="text-center align-middle">{{ $ponderacionCorte * 100 }}%</td>
                        <td>
                            @if ($asignatura->rubrica->ra->where('corte', $corte)->isNotEmpty())
                                <table class="table table-bordered mb-0">
                                    @foreach ($asignatura->rubrica->ra->where('corte', $corte) as $ra)
                                        <tr class="bg-danger text-white">
                                            <th class="col-3">Actividades</th>
                                            <th class="col-2">Nota</th>
                                            <th class="col-2">Ponderación</th>
                                            <th class="col-2">Total</th>
                                        </tr>
                                        @if ($ra->actividades->isNotEmpty())
                                            @php
                                                $totalPonderacionRA = 0;
                                                $totalSumatoriaRA = 0;
                                            @endphp
                                            @foreach ($ra->actividades as $actividad)
                                                <tr>
                                                    <td class="col-3">{{ $actividad->nombre }}</td>
                                                    <td class="col-2">
                                                        @php
                                                            $notaEstudiante = $actividad->valoraciones->firstWhere('estudiante_id', $estudiante->id)->nota ?? null;
                                                        @endphp
                                                        {{ $notaEstudiante ?? 'N/A' }}
                                                    </td>
                                                    <td class="col-2">
                                                        {{ $actividad->ponderacion }}
                                                        @php
                                                            $totalPonderacionRA += $actividad->ponderacion;
                                                        @endphp
                                                    </td>
                                                    <td class="col-1">
                                                        @php
                                                            $totalActividad = $notaEstudiante ? ($actividad->ponderacion * $notaEstudiante) / 100 : 0;
                                                            $totalSumatoriaRA += $totalActividad;
                                                        @endphp
                                                        {{ $notaEstudiante ? $totalActividad : 'N/A' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr class="bg-warning-subtle font-weight-bold">
                                                <td>Total</td>
                                                <td></td>
                                                <td>{{ $totalPonderacionRA }}</td>
                                                <td>{{ $totalSumatoriaRA }}</td>
                                            </tr>
                                            @php
                                                $totalRA = ($totalSumatoriaRA * $ra->ponderacion) / 100; // Total de RA ajustado por su ponderación
                                                $totalCorte += $totalRA; // Acumular en el total del corte
                                            @endphp
                                        @else
                                            <tr class="bg-warning-subtle text-center">
                                                <td colspan="4">
                                                    No hay Actividades para mostrar en este RA.
                                                    <a href="{{ route('actividad.create', $asignatura->id) }}" class="btn btn-link">¿Quieres añadir una actividad?</a>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </table>
                            @else
                                <div class="bg-warning-subtle text-center">
                                    No hay RAS para mostrar en este corte.
                                    <a href="{{ route('rubrica.editor', $asignatura->id) }}" class="btn btn-link">¿Quieres editar la rúbrica?</a>
                                </div>
                            @endif
                        </td>
                        <td>
                            @foreach ($asignatura->rubrica->ra->where('corte', $corte) as $ra)
                                {{ $ra->nombre }} (Corte {{ $ra->corte }})
                            @endforeach
                        </td>
                        <td>
                            @foreach ($asignatura->rubrica->ra->where('corte', $corte) as $ra)
                                {{ $ra->ponderacion }}
                            @endforeach
                        </td>
                        <td class="font-weight-bold">
                            @php
                                $totalCorteFinal = $totalCorte * $ponderacionCorte;
                            @endphp
                            {{ $totalCorteFinal }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

                                   </td>
            </tr>


            </tbody>
            </table>


        </div>


    </div>

</div>



@else
<p>No existe una rúbrica para esta asignatura.</p>
<a href="{{ route('rubrica.create', ['id' => $asignatura->id]) }}" class="btn btn-primary">Crear Rúbrica</a>
@endif



@endsection