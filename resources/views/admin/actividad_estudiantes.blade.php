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

                <a href="/admin/asignaturas/{{ $asignatura->id }}/show" class="p-2 btn-link">
                    < Volver</a>
            </li>
        </ol>
    </section>

    @if ($tieneRubrica)
        <div class="d-flex justify-content-start mb-4">
            {{-- Botón de Imprimir --}}
            <button onclick="window.print()" class="btn btn-secondary m-2 ">Imprimir Rúbrica</button>

            {{-- Botón de Editar --}}
            <a href="{{ route('rubrica.editor', $asignatura->id) }}" class="btn btn-primary m-2">Editar Rúbrica</a>
        </div>
    @endif
@endsection


@section('content')

    @if ($tieneRubrica)
        <div class="container">
            <h1>Diseñador de Rúbrica - {{ $asignatura->nombre }}</h1>
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered avoid-break">

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
                                                    <th class="col-md-1" ><strong>RESULTADO DE APRENDIZAJE QUE
                                                            EVALÚA</strong></th>
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
                                                        <td class="text-center align-middle">{{ $ponderacionCorte * 100 }}%
                                                        </td>
                                                        <td>
                                                            @if ($asignatura->rubrica->ra->where('corte', $corte)->isNotEmpty())
                                                                <table class="table table-bordered mb-0">
                                                                    @foreach ($asignatura->rubrica->ra->where('corte', $corte) as $ra)
                                                                        <tr class="bg-danger text-white">
                                                                            <th class="col-3">Actividades</th>
                                                                            <th class="col-2">Nota</th>
                                                                            <th class="col-2">Ponderación</th>

                                                                            <th class="col-2">Total</th>
                                                                            <th class="col-2">Nivel alcanzado</th>
                                                                        </tr>
                                                                        @if ($ra->actividades->isNotEmpty())
                                                                            @php
                                                                                $totalPonderacionRA = 0;
                                                                                $totalSumatoriaRA = 0;
                                                                            @endphp
                                                                            @foreach ($ra->actividades as $actividad)
                                                                                <tr>
                                                                                    <td class="col-3">
                                                                                        {{ $actividad->nombre }}</td>
                                                                                    <td class="col-2">
                                                                                        @php
                                                                                            $notaEstudiante =
                                                                                                $actividad->valoraciones->firstWhere(
                                                                                                    'estudiante_id',
                                                                                                    $estudiante->id,
                                                                                                )->nota ?? 0;
                                                                                        @endphp
                                                                                        {{ $notaEstudiante ?? 'N/A' }}
                                                                                    </td>
                                                                                    <td class="col-2">
                                                                                        {{ $actividad->ponderacion }}
                                                                                        @php
                                                                                            $totalPonderacionRA +=
                                                                                                $actividad->ponderacion;
                                                                                        @endphp
                                                                                    </td>
                                                                                    <td class="col-1">
                                                                                        @php
                                                                                            $totalActividad = $notaEstudiante
                                                                                                ? ($actividad->ponderacion *
                                                                                                        $notaEstudiante) /
                                                                                                    100
                                                                                                : 0;
                                                                                            $totalSumatoriaRA += $totalActividad;
                                                                                        @endphp
                                                                                        {{ $notaEstudiante ? $totalActividad : 'N/A' }}
                                                                                    </td>

                                                                                    <td class="col-1">
                                                                                        @php
                                                                                            // Obtener la valoración del estudiante para la actividad
                                                                                            $valoracionEstudiante = $actividad->valoraciones->firstWhere(
                                                                                                'estudiante_id',
                                                                                                $estudiante->id,
                                                                                            );

                                                                                            // Convertir a float
                                                                                            $valoracionEstudianteFloat = floatval(
                                                                                                $valoracionEstudiante->nota ?? 0,
                                                                                            );

                                                                                        @endphp
                                                                                        @php
                                                                                            // Variable para saber si el estudiante está en algún nivel
                                                                                            $estudianteEnRango = false;
                                                                                        @endphp

                                                                                        @foreach ($actividad->rubrica->nivelesDesempeno as $nivel)
                                                                                            @php
                                                                                                // Verificar si el puntaje del estudiante está dentro del rango de este nivel
                                                                                                $estudianteEnNivel =
                                                                                                    $valoracionEstudianteFloat >=
                                                                                                        $nivel->puntaje_inicial &&
                                                                                                    $valoracionEstudianteFloat <=
                                                                                                        $nivel->puntaje_final;

                                                                                                // Definir el estado y la clase según el puntaje
                                                                                                $nivelEstado = '';
                                                                                                $nivelClase = '';

                                                                                                // Si el estudiante está dentro del rango de puntaje de este nivel
                                                                                                if (
                                                                                                    $estudianteEnNivel
                                                                                                ) {
                                                                                                    $estudianteEnRango = true; // El estudiante está en el rango de un nivel
                                                                                                    if (
                                                                                                        $valoracionEstudianteFloat >
                                                                                                        4
                                                                                                    ) {
                                                                                                        $nivelEstado =
                                                                                                            'Excelente';
                                                                                                        $nivelClase =
                                                                                                            'alert-success';
                                                                                                    } elseif (
                                                                                                        $valoracionEstudianteFloat >=
                                                                                                        3
                                                                                                    ) {
                                                                                                        $nivelEstado =
                                                                                                            'Aprobado';
                                                                                                        $nivelClase =
                                                                                                            'alert-warning';
                                                                                                    } else {
                                                                                                        $nivelEstado =
                                                                                                            'Reprobado';
                                                                                                        $nivelClase =
                                                                                                            'alert-danger';
                                                                                                    }
                                                                                                }
                                                                                            @endphp

                                                                                            <div class="text-center"
                                                                                                data-nivel-id="{{ $nivel->id }}">
                                                                                                <div>
                                                                                                    <!-- Nombre del nivel -->
                                                                                                </div>

                                                                                                <div>
                                                                                                    <!-- Rango de puntaje -->
                                                                                                </div>

                                                                                                @if ($estudianteEnNivel)
                                                                                                    <div class="alert {{ $nivelClase }}"
                                                                                                        role="alert">
                                                                                                        Nivel:
                                                                                                        {{ $nivel->nombre }}
                                                                                                        ({{ $nivel->puntaje_inicial }}
                                                                                                        -
                                                                                                        {{ $nivel->puntaje_final }})
                                                                                                        (Puntaje:
                                                                                                        {{ $valoracionEstudianteFloat }})
                                                                                                    </div>
                                                                                                @endif
                                                                                            </div>
                                                                                        @endforeach

                                                                                        @if (!$estudianteEnRango)
                                                                                            <!-- Si el estudiante no está en ningún nivel, mostrar mensaje con color azul o morado -->
                                                                                    <th class="text-center">
                                                                                        <div class="alert alert-info"
                                                                                            role="alert">
                                                                                            No hay un rango estipulado para
                                                                                            esta nota (Puntaje:
                                                                                            {{ $valoracionEstudianteFloat }})
                                                                                        </div>
                                                                                    </th>
                                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr class="bg-warning-subtle font-weight-bold">
                                                    <td>Total</td>
                                                    <td></td>
                                                    <td>{{ $totalPonderacionRA }}</td>
                                                    <td>{{ $totalSumatoriaRA }}</td>
                                                    <td> </td>
                                                </tr>
                                                @php
                                                    $totalRA = $totalSumatoriaRA; // Total de RA ajustado por su ponderación
                                                    $totalCorte += $totalRA; // Acumular en el total del corte
                                                @endphp
                                            @else
                                                <tr class="bg-warning-subtle text-center">
                                                    <td colspan="4">
                                                        No hay Actividades para mostrar en este RA.
                                                        <a href="{{ route('actividad.create', $asignatura->id) }}"
                                                            class="btn btn-link">¿Quieres añadir una actividad?</a>
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
