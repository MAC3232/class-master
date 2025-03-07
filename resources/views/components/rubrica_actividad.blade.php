<div class="container mt-5 bg-light p-2 rounded">
    <div class="container mt-5">
        <div class="text-right mb-3">
            <button class="btn btn-primary d-print-none" onclick="showCustomModal('criterion')">Agregar Nuevo Criterio</button>
            <button class="btn btn-primary d-print-none" onclick="showCustomModal('level')">Agregar Nuevo Nivel</button>
            @if(Route::is('Evaluar_actividad.evaluar'))


            <button class="btn btn-success d-print-none" onclick="showCustomModal('calificar')"> Ver / Editar calificacion </button>
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
                <input type="hidden" id="note-opacity">
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
    @foreach ($rubrica_actividad->rubrica->nivelesDesempeno as $nivel)
    <th class="text-center position-relative" data-nivel-id="{{ $nivel->id }}" style="position: relative;">

        <div class="position-absolute top-0 end-0 m-1 buttons-container"
            style="opacity: 0; transition: opacity .8s; display: flex; flex-direction: column; gap: 5px;">
            <button class="btn btn-danger btn-sm">
                <i class="la la-trash"></i>
            </button>
            <button class="btn btn-primary btn-sm btn-edit" data-id="{{ $nivel->id }}">
                <i class="la la-edit"></i>
            </button>
        </div>

        <div class="editable-text" data-id="{{ $nivel->id }}" style="cursor: pointer;">
            {{ $nivel->nombre }}
        </div>
        <div>{{ $nivel->puntos }}</div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let th = document.querySelector('[data-nivel-id="{{ $nivel->id }}"]');
                let buttonContainer = th.querySelector(".buttons-container");
                let editableText = th.querySelector(".editable-text");

                th.addEventListener("mouseenter", function() {
                    buttonContainer.style.opacity = "1";
                });

                th.addEventListener("mouseleave", function() {
                    buttonContainer.style.opacity = "0";
                });

                // Permitir edición al hacer clic en el nombre del nivel
                editableText.addEventListener("click", function() {
                    let nivelId = this.getAttribute("data-id");
                    let nuevoTexto = prompt("Editar nombre del nivel:", this.innerText);
                    if (nuevoTexto !== null) {
                        this.innerText = nuevoTexto;
                        console.log("Nivel ID:", nivelId, "Nuevo nombre:", nuevoTexto);
                        // Aquí puedes hacer una petición AJAX para guardar el cambio en el backend
                    }
                });

                // Capturar clic en el botón de edición
                th.querySelector('.btn-edit')?.addEventListener('click', function() {
                    let nivelId = this.getAttribute("data-id");
                    console.log('Editar nivel ID:', nivelId);
                    // Aquí puedes redirigir o abrir un modal con el ID
                });

            });
        </script>

    </th>
    @endforeach





                    </tr>
                </thead>
                <tbody>
                    <!-- Table body will be dynamically filled -->
                    @foreach ($rubrica_actividad->rubrica->criterios as $criterios)
                    <tr data-criterio-id="{{ $criterios->id }}" style="position: relative;">

                    <td>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="n-criterios text-center">
                                {{ $criterios->descripcion }}
                            </div>

                            <div class="buttons-criterios d-flex flex-column" style="opacity: 0; transition: opacity .8s;">
                                <button type="button" class="btn btn-danger btn-sm mb-2">
                                    <i class="la la-trash"></i>
                                </button>
                                <button class="btn btn-primary btn-sm btn-edit" data-id="{{ $criterios->id }}">
                                    <i class="la la-edit"></i>
                                </button>
                            </div>
                        </div>

                        <script>
                            (function(){
                                // Obtenemos el <td> contenedor
                                const td = document.currentScript.parentElement;
                                const btnContainer = td.querySelector('.buttons-criterios');

                                // Mostrar botones al pasar el mouse
                                td.addEventListener('mouseenter', () => {
                                    btnContainer.style.opacity = '1';
                                });

                                // Ocultar botones al salir del área
                                td.addEventListener('mouseleave', () => {
                                    btnContainer.style.opacity = '0';
                                });

                                // Capturar clic en el botón de edición y obtener el ID
                                td.querySelector('.btn-edit')?.addEventListener('click', function() {
                                    const criterioId = this.getAttribute('data-id');
                                    console.log('Editar criterio ID:', criterioId);
                                    // Aquí puedes redirigir o abrir un modal con el ID
                                });

                            })();
                        </script>
                    </td>





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

                            <div class="overlay select" id="overlay"   onclick="marcarCriterio('{{ $criterios->id }}', '{{ $nivel->id }}', '{{$estudiante}}', '{{$rubrica_actividad->rubrica->id}}')" >
                <div class="plus">+</div>
                <div class="text">Marcar cumplido</div>
            </div>
                            @endif

                            <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center">
                                <div>
                                    {{ $descripcionEncontrada->descripcion }}
                                </div>
                            </div>

                            <div class="b-c" style="margin-left: 10px;">
                                @if ($evalueRubrica ?? false)
                                <div class="buttons-criterios d-flex flex-column" style="opacity: 0; transition: opacity .3s;">
                                    <button type="button" class="btn btn-danger btn-sm mb-2">
                                        <i class="la la-trash"></i>
                                    </button>
                                    <button class="btn btn-primary btn-sm btn-edit" data-id="{{ $descripcionEncontrada->id }}">
                                        <i class="la la-edit"></i>
                                    </button>
                                </div>
                                @endif
                            </div>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                // Delegación de eventos para hover
                                document.addEventListener('mouseover', function(e) {
                                    const td = e.target.closest('.d-flex.justify-content-between');
                                    if (td) {
                                        const btnContainer = td.querySelector('.buttons-criterios');
                                        if (btnContainer) {
                                            btnContainer.style.opacity = '1';
                                        }
                                    }
                                });

                                document.addEventListener('mouseout', function(e) {
                                    const td = e.target.closest('.d-flex.justify-content-between');
                                    if (td) {
                                        const btnContainer = td.querySelector('.buttons-criterios');
                                        if (btnContainer) {
                                            btnContainer.style.opacity = '0';
                                        }
                                    }
                                });

                                // Delegación de eventos para clic en editar
                                document.addEventListener('click', function(e) {
                                    if (e.target.closest('.btn-edit')) {
                                        const button = e.target.closest('.btn-edit');
                                        const criterioId = button.getAttribute('data-id');
                                        console.log('Editar criterio ID:', criterioId);
                                        // Aquí tu lógica para editar
                                    }
                                });
                            });
                            </script>


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
                                <label for="scoreRangeFrom">Puntos del nivel</label>
                                <input type="number" class="form-control" id="scoreRangeFrom" required>
                            </div>
                            <div class="form-group">

                                <input type="hidden" class="form-control" value="{{$rubrica_actividad->rubrica->id}}" id="scoreRangeFrom" required>
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



