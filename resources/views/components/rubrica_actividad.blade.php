<div class="container mt-5 bg-light p-2 rounded">
    <div class="container mt-5">
        <div class="text-right mb-3">
            <button class="btn btn-primary d-print-none" onclick="showCustomModal('criterion')">Agregar Nuevo Criterio</button>
            <button class="btn btn-primary d-print-none" onclick="showCustomModal('level')">Agregar Nuevo Nivel</button>
            @if(Route::is('Evaluar_actividad.evaluar'))


            <button class="btn btn-success d-print-none" onclick="showCustomModal('calificar')"> Calificar / Ver </button>
            @php
    $puntaje = 4.5;
@endphp
@php

$valoracionEstudiante = $rubrica_actividad->valoraciones->firstWhere('estudiante_id', $estudiante);


if (!is_null($valoracionEstudiante) && !is_null($valoracionEstudiante->nota)) {
    // $valoracionEstudiante->nota tiene un valor

    $nota = floatval($valoracionEstudiante->nota);
}

@endphp
@php
    // Variable para saber si el estudiante está en algún nivel
    $estudianteEnRango = false;
@endphp

@foreach ($rubrica_actividad->rubrica->nivelesDesempeno as $nivel)
    @php
        // Verificar si el puntaje del estudiante está dentro del rango de este nivel
        if (isset( $nota) &&  $nota) {
            # code...
            $estudianteEnNivel = $nota >= $nivel->puntaje_inicial && $nota <= $nivel->puntaje_final;
        }




        // Definir el estado y la clase según el puntaje
        $nivelEstado = '';
        $nivelClase = '';

        // Si el estudiante está dentro del rango de puntaje de este nivel
        if ( isset($estudianteEnNivel) && $estudianteEnNivel && isset($nota) && $nota) {
            $estudianteEnRango = true; // El estudiante está en el rango de un nivel
            if ($nota > 4) {
                $nivelEstado = 'Excelente';
                $nivelClase = 'alert-success';
            } elseif ($nota >= 3) {
                $nivelEstado = 'Aprobado';
                $nivelClase = 'alert-warning';
            } else {
                $nivelEstado = 'Reprobado';
                $nivelClase = 'alert-danger';
            }
        }

    @endphp


    <th class="text-center" data-nivel-id="{{ $nivel->id }}">
        <div>
           <!-- Nombre del nivel -->
        </div>

        <div>
           <!-- Rango de puntaje -->
        </div>

        @if (  isset($estudianteEnNivel) && $estudianteEnNivel )
            <div class="alert {{ $nivelClase }}" role="alert">
                <div id="note-view">s</div>
                Nivel:  {{$nivel->nombre}}  ({{$nivel->puntaje_inicial}} - {{$nivel->puntaje_final}}) (Puntaje: {{ $nota }})
            </div>
        @endif
    </th>
@endforeach


    <!-- Si el estudiante no está en ningún nivel, mostrar mensaje con color azul o morado -->
    <th class="text-center">
        <div class="alert alert-info" role="alert">

            No hay un rango estipulado para esta nota (Puntaje: <span id="note-view">{{ $nota ?? 'No Calificado' }}</span> )
        </div>
    </th>





            @endif

        </div>
        <div class="card">
            <table class="table table-bordered table-custom mb-0" id="rubricTable">
                <thead>

                    <tr id="headerRow">
                        <th class="text-center">Descripción del Criterio</th>
                        @foreach ( $rubrica_actividad->rubrica->nivelesDesempeno as $nivel )
                        <th class="text-center" data-nivel-id="{{ $nivel->id }}">
                            <div>

                                {{$nivel->nombre}}
                            </div>

                            <div>

                                {{$nivel->puntos}}

                            </div>
                        </th>

                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <!-- Table body will be dynamically filled -->
                    @foreach ($rubrica_actividad->rubrica->criterios as $criterios)
                    <tr data-criterio-id="{{ $criterios->id }}">
                        <td>{{ $criterios->descripcion }}</td>

                        @php
        // Buscar si este criterio ya tiene un nivel seleccionado por el estudiante

        if($evalueRubrica){

            $nivelSeleccionado = $seleccionados->firstWhere('criterio_id', $criterios->id);
        }
    @endphp
                        @foreach ($rubrica_actividad->rubrica->nivelesDesempeno as $nivel)
                        @php
                        $descripcionEncontrada = $nivel->descripciones->firstWhere('criterio_id', $criterios->id);
                        @endphp


                        <td data-criterio-id="{{ $criterios->id }}" id="seleccionar_criterio{{$criterios->id}}{{$nivel->id}}"  class="justify-content-between {{  $evalueRubrica && $nivelSeleccionado && $nivelSeleccionado->nivel_desempeno_id == $nivel->id ? 'seleccionado' : '' }}" data-nivel-id="{{ $nivel->id }}">
                            @if ($descripcionEncontrada)

                            @if ($evalueRubrica ?? false)

                            <div class="overlay select" id="overlay"   onclick="marcarCriterio('{{ $criterios->id }}', '{{ $nivel->id }}', '{{$estudiante}}', '{{$rubrica_actividad->id}}')" >
                <div class="plus">+</div>
                <div class="text">Marcar cumplido</div>
            </div>
                            @endif

                            <div class="d-flex justify-content-between  ">

                            <div class="">
                                {{ $descripcionEncontrada->descripcion }}

                            </div>

                            @if ($evalueRubrica ?? false)

                            <div class="btn-group flex-column">
        <button class="btn btn-link btn-sm fs-3 d-print-none" onclick="marcarCriterio('{{ $criterios->id }}', '{{ $nivel->id }}', '{{$estudiante}}', '{{$rubrica_actividad->id}}')">
            <i class="la la-check"></i>
        </button>
        <button class="btn btn-link btn-sm fs-3 d-print-none" onclick="desmarcarCriterio('{{ $criterios->id }}', '{{ $nivel->id }}')">
            <i class="la la-times"></i>
        </button>
        <button class="btn btn-link btn-sm fs-3 d-print-none"  onclick="accionPersonalizada('{{ $criterios->id }}', '{{ $nivel->id }}')">
            <i class="la la-cog"></i>
        </button>
    </div>
                            @endif

                            </div>


                            @else
                            <button onclick="showCustomModal('descripcion', '{{ $criterios->id }}', '{{ $nivel->id }}')" class="btn btn-sm btn-link">
                                <i class="la la-plus"></i> Agregar descripción aquí
                            </button>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Custom Modal using Bootstrap -->
    <div class="modal fade custom-modal" id="customModal" tabindex="-1" role="dialog" aria-labelledby="customModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customModalLabel">Agregar Nuevo Elemento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addElementForm">
                        <div id="criteriaInputs" style="display: none;">
                            <div class="form-group">
                                <label for="criterionDesc">Descripción</label>
                                <input type="text" class="form-control" id="criterionDesc" required>

                                <input type="hidden" class="form-control" value="{{$rubrica_actividad->rubrica->id}}" id="rubrica" required>
                            </div>
                        </div>



                        @if (Route::is('Evaluar_actividad.evaluar'))
                        @php
                        // Obtener la valoración del estudiante para la actividad
                        $valoracionEstudiante = $rubrica_actividad->valoraciones->firstWhere('estudiante_id', $estudiante);

                        @endphp

                        <div id="CalificarInputs" style="display: none;">
                            <div class="form-group">
                                <label for="CalificarDesc">Calificación</label>
                                <input type="number" class="form-control" id="CalificarDesc"
                                    value="{{$valoracionEstudiante->nota ?? ''}}"
                                    required>

                                <input type="hidden" class="form-control" value="{{ $estudiante}}" id="estudiante" required>
                                <input type="hidden" class="form-control" value="{{ $rubrica_actividad->id}}" id="actividad" required>
                            </div>
                        </div>
                        @endif




                        <div id="descripcionInputs" style="display: none;">
                            <div class="form-group">
                                <label for="descripcionDesc">Descripción</label>
                                <input type="text" class="form-control" id="descripcion_input" required>

                            </div>
                        </div>

                        <div id="levelInputs" style="display: none;">
                            <div class="form-group">
                                <label for="levelDesc">Descripción de Nivel</label>
                                <input type="text" class="form-control" id="levelDesc" required>
                            </div>
                            <div class="form-group">
                                <label for="scoreRangeFrom">Rango de Nota Desde</label>
                                <input type="number" class="form-control" id="scoreRangeFrom" required>
                            </div>
                            <div class="form-group">

                                <input type="hidden" class="form-control" value="{{$rubrica_actividad->rubrica->id}}" id="scoreRangeFrom" required>
                            </div>
                            <div class="form-group">
                                <label for="scoreRangeTo">Rango de Nota Hasta</label>
                                <input type="number" class="form-control" id="scoreRangeTo" required>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="submitCustomModal()">Agregar</button>
                </div>
            </div>
        </div>
    </div>
</div>



