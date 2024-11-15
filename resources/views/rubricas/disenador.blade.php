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
                                    @foreach ([0 => 'tecnico', 2 => 'Tecnologia'] as $nivel)
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
                            <small>(Trasladar aquí los Resultados de Aprendizaje previstos en el Programa, al cual tributa
                                esta asignatura.)</small>
                        </th>
                    </tr>


                    @if ($asignatura->rubrica->ra->isNotEmpty())
                        @foreach ($asignatura->rubrica->ra as $ra)
                            <tr>

                                <td> <strong> {{ $ra->nombre }} (Corte {{ $ra->corte }}) </strong></td>
                                <td colspan="3">

                                    {{ $ra->descripcion }}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="table-warning">
                            <td colspan="4" class="text-center">
                                No hay Resultados de aprendizaje (RA) para mostrar. <a
                                    href="{{ route('rubrica.editor', $asignatura->id) }}" class="btn btn-link"> Quieres
                                    editar la Rúbrica?</a>

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
                        @foreach ($asignatura->rubrica->ra as $ra)
                            <tr>
                                <td class="text-center"><strong>{{ $ra->nombre }} (Corte {{ $ra->corte }})</strong>
                                </td>

                                <td style="padding: 0;">


                                    <table class="table table-bordered avoid-break">

                                        <tbody>
                                            @if ($ra->criterios->isNotEmpty())
                                                @foreach ($ra->criterios as $criterio)
                                                    <tr>
                                                        <td>


                                                            <div>
                                                                <p>
                                                                    {{ $criterio->descripcion }}

                                                                </p>

                                                            </div>


                                                        </td>

                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr class="table-warning">
                                                    <td colspan="4" class="text-center">
                                                        No hay Criterios de evaluacion para mostrar. <a
                                                            href="{{ route('rubrica.editor', $asignatura->id) }}"
                                                            class="btn btn-link"> Quieres editar la Rúbrica?</a>
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
                                                                    {{ $estrategia->descripcion }}

                                                                </p>

                                                            </div>


                                                        </td>

                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr class="table-warning">
                                                    <td colspan="4" class="text-center">
                                                        No hay Criterios de evaluacion para mostrar. <a
                                                            href="{{ route('rubrica.editor', $asignatura->id) }}"
                                                            class="btn btn-link"> Quieres editar la Rúbrica?</a>
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
                                                                    {{ $contenido->descripcion }}

                                                                </p>

                                                            </div>


                                                        </td>

                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr class="table-warning">
                                                    <td colspan="4" class="text-center">
                                                        No hay Criterios de evaluacion para mostrar. <a
                                                            href="{{ route('rubrica.editor', $asignatura->id) }}"
                                                            class="btn btn-link"> Quieres editar la Rúbrica?</a>
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
                                No hay Resultados de aprendizaje (RA) para mostrar. <a
                                    href="{{ route('rubrica.editor', $asignatura->id) }}" class="btn btn-link"> Quieres
                                    editar la Rúbrica?</a>
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
                            El QUÉ y HASTA DÓNDE, aprende el estudiante, tiene que ver con el desarrollo evaluativo desde la
                            línea base hasta los procesos metacognitivos y de dominio de las competencias. La evaluación en
                            el modelo pedagógico se asume como comprensión de la acción desplegada por el estudiante en su
                            aprendizaje. Para ello es esencial trascender la heteroevaluación como única forma de evaluar o
                            estimar el aprendizaje, se requiere incorporar, generando una nueva cultura de la evaluación,
                            formas de coevaluación o valoración de pares y el autoevaluación que realiza el mismo actor del
                            proceso, es decir el estudiante.
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0;" colspan="4">

                            <div class="d-flex ">

                                <div class="col d-flex flex-column  ">

                                    <div class="d-flex col">
                                        <div class="col-md-2 "><strong>Corte</strong></div>

                                        <div class="col-md-2 border-bottom border-start"><strong>Ponderacion</strong></div>
                                        <div class="col-md border-bottom  border-start p-2"><strong>MECANISMOS DE
                                                EVALUACIÓN, EVIDENCIAS Y SEGUIMIENTO CON PARTICIPACIÓN PORCENTUAL DENTRO DEL
                                                CORTE</strong></div>
                                        <div class="col border-bottom border-start p-2"><Strong>RESULTADO DE APRENDIZAJE QUE
                                                EVALÚA</Strong></div>


                                    </div>
                                    @foreach (range(1, 3) as $corte)
                                        <div class="d-flex col ">
                                            <div class="col-md-2 border-top p-2 d-flex justify-content-center">Corte
                                                {{ $corte }}</div>

                                            <div
                                                class="col-md-2 border-start border-top align-items-center d-flex justify-content-center">
                                                {{ $corte == 3 ? '30%' : '35%' }}</div>
                                            @if ($asignatura->rubrica->ra->where('corte', $corte)->isNotEmpty())
                                                <div class="col d-flex flex-column border-start border-top">
                                                    @foreach ($asignatura->rubrica->ra->where('corte', $corte) as $ra)
                                                        <div class="col d-flex">
                                                            <div class="col ">
                                                                <div class="d-flex ">
                                                                    <div class="col border-end bg-danger p-2">Actividades
                                                                    </div>
                                                                    <div class="col bg-danger p-2">Ponderacion</div>
                                                                </div>

                                                                @if ($ra->actividades->isNotEmpty())
                                                                    @foreach ($ra->actividades as $actividad)
                                                                        <div class="d-flex border-top ">
                                                                            <div class="col border-end p-2">
                                                                                {{ $actividad->nombre }}</div>
                                                                            <div class="col p-2">
                                                                                {{ $actividad->ponderacion }}</div>
                                                                        </div>
                                                                    @endforeach
                                                                @else
                                                                    <div class="d-flex border-top ">
                                                                        <div class="col border-end">
                                                                            <div class="bg-warning-subtle">
                                                                                <div colspan="4" class="text-center">
                                                                                    No hay Actividades para mostrar en este
                                                                                    RA. <a
                                                                                        href="{{ route('actividad.create', $asignatura->id) }}"
                                                                                        class="btn btn-link">Quieres añadir
                                                                                        una actividad?</a>
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
                                                <div class="d-flex border-top col ">
                                                    <div class="col border-end">
                                                        <div class="bg-warning-subtle">
                                                            <div colspan="4" class="text-center">
                                                                No hay RAS para mostrar en este corte. <a
                                                                    href="{{ route('rubrica.editor', $asignatura->id) }}"
                                                                    class="btn btn-link">Quieres editar la rubrica?</a>
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
    @else

    <div class="container mt-5">
        <p>No existe una rúbrica para esta asignatura.</p>
        <a href="#" class="btn btn-primary" id="openModal">Crear Rúbrica</a>
        <div id="rubricaModal" class="modal fade" tabindex="-1" role="dialog" style="display: none;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Nueva Rúbrica</h5>
                        <button type="button" class="close" id="closeModal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Contenido del modal -->
                        <form action="{{ route('rubrica.store') }}" method="POST" id="rubricaForm">
                            @csrf
                            <input type="hidden" name="asignatura_id" value="{{ $asignatura->id }}">
                            <div class="form-group">
                                <label for="nombre">Nombre de la Rúbrica</label>
                                <input type="text" name="nombre" id="nombre" class="form-control" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="closeModalFooter">Cancelar</button>
                        <button type="submit" class="btn btn-primary" form="rubricaForm">Guardar Rúbrica</button>
                    </div>
                </div>
            </div>
        </div>

    @endif



@endsection

@section('after_scripts')
<script>
document.getElementById('openModal').addEventListener('click', function(event) {
    event.preventDefault();
    document.getElementById('rubricaModal').style.display = 'block'; // Muestra el modal
    document.getElementById('rubricaModal').classList.add('show'); // Añade clase Bootstrap para estilos
});

document.getElementById('closeModal').addEventListener('click', function() {
    document.getElementById('rubricaModal').style.display = 'none'; // Oculta el modal
    document.getElementById('rubricaModal').classList.remove('show');
});

document.getElementById('closeModalFooter').addEventListener('click', function() {
    document.getElementById('rubricaModal').style.display = 'none';
    document.getElementById('rubricaModal').classList.remove('show');
});

// Opcional: cerrar el modal al hacer clic fuera del contenido modal
window.addEventListener('click', function(event) {
    const modal = document.getElementById('rubricaModal');
    if (event.target === modal) {
        modal.style.display = 'none';
        modal.classList.remove('show');
    }
});

</script>

@endsection
