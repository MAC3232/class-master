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
            if (isset( $nota) && $nota) {
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

                    @if ( isset($estudianteEnNivel) && $estudianteEnNivel )
                    <div class="alert {{ $nivelClase }}" role="alert">
                        <div id="note-view">s</div>
                        <input type="hidden" id="note-opacity">
                        Nivel: {{$nivel->nombre}} ({{$nivel->puntaje_inicial}} - {{$nivel->puntaje_final}}) (Puntaje: {{ $nota }})
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
                        <th class="text-center">Criterios</th>
                        @foreach ($rubrica_actividad->rubrica->nivelesDesempeno as $nivel)
                        <th class="text-center position-relative" data-nivel-id="{{ $nivel->id }}" style="position: relative;">

                            <div class="position-absolute top-0 end-0 m-1 buttons-container"
                                style="opacity: 0; transition: opacity .8s; display: flex; flex-direction: column; gap: 5px;">
                                <button class="btn btn-danger btn-sm btn-delete" data-id="{{ $nivel->id }}">
                                    <i class="la la-trash"></i>
                                </button>
                                <button class="btn btn-primary btn-sm btn-edit" data-id="{{ $nivel->id }}">
                                    <i class="la la-edit"></i>
                                </button>
                            </div>

                            <div class="editable-text" style="cursor: pointer;">
                                {{ $nivel->nombre }}
                            </div>
                            <div>{{ $nivel->puntos }}</div>

                            <script>
                                // Ejecutar cuando el DOM esté listo
                                document.addEventListener("DOMContentLoaded", function() {
                                    // Seleccionamos el elemento padre que contiene el id único del nivel
                                    let th = document.querySelector('[data-nivel-id="{{ $nivel->id }}"]');
                                    let nivelId = th.getAttribute("data-nivel-id");
                                    let nameNivel = '{{ $nivel->nombre }}';
                                    let puntoNivel = '{{ $nivel->puntos }}';
                                    let buttonContainer = th.querySelector(".buttons-container");
                                    let editableText = th.querySelector(".editable-text");

                                    // Mostrar los botones al pasar el mouse
                                    th.addEventListener("mouseenter", function() {
                                        buttonContainer.style.opacity = "1";
                                    });
                                    th.addEventListener("mouseleave", function() {
                                        buttonContainer.style.opacity = "0";
                                    });

                                    // Edición del nombre al hacer clic en el texto
                                    editableText.addEventListener("click", function() {
                                        let nuevoTexto = prompt("Editar nombre del nivel:", this.innerText);
                                        if (nuevoTexto !== null) {
                                            this.innerText = nuevoTexto;
                                            console.log("Nivel ID:", nivelId, "Nuevo nombre:", nuevoTexto);
                                            // Aquí puedes realizar una petición AJAX para actualizar el backend
                                        }
                                    });

                                    // Acción del botón de edición
                                    th.querySelector('.btn-edit').addEventListener('click', function() {

                                        showCustomModal('update_modal_level', null, nivelId);
                                        document.getElementById('edit_level').value = nameNivel;
                                        document.getElementById('scoreRangeFromEdit').value = puntoNivel;



                                    });

                                    // Acción del botón de eliminación
                                    th.querySelector('.btn-delete').addEventListener('click', function() {
                                        Swal.fire({
                                            title: "Are you sure?",
                                            text: "You won't be able to revert this!",
                                            icon: "warning",
                                            showCancelButton: true,
                                            confirmButtonColor: "#3085d6",
                                            cancelButtonColor: "#d33",
                                            confirmButtonText: "Yes, delete it!"
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                $.ajax({
                                                    url: `{{ route('niveles.destroy', ['id' => 'nivelId']) }}`.replace('nivelId', nivelId),
                                                    type: 'DELETE',
                                                    dataType: 'json',
                                                    success: function(response) {
                                                        Swal.fire({
                                                            title: "Deleted!",
                                                            text: "Your file has been deleted.",
                                                            icon: "success"
                                                        }).then(() => {
                                                            location.reload();
                                                        });
                                                    },
                                                    error: function(xhr, status, error) {
                                                        console.log("Todo salió mal");
                                                        // Acción de error
                                                    }
                                                });
                                            }
                                        });
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
                                    <button type="button" class="btn btn-danger btn-sm mb-2 btn-delete" data-id="{{ $criterios->id }}">
                                        <i class="la la-trash"></i>
                                    </button>
                                    <button type="button" class="btn btn-primary btn-sm btn-edit" data-id="{{ $criterios->id }}">
                                        <i class="la la-edit"></i>
                                    </button>
                                </div>
                            </div>

                            <script>
                                (function() {
                                    // Obtenemos el <td> contenedor
                                    const td = document.currentScript.parentElement;
                                    const btnContainer = td.querySelector('.buttons-criterios');
                                    let nameDescripcionCriterio = '{{ $criterios->descripcion }}';

                                    // Mostrar botones al pasar el mouse
                                    td.addEventListener('mouseenter', () => {
                                        btnContainer.style.opacity = '1';
                                    });

                                    // Ocultar botones al salir del área
                                    td.addEventListener('mouseleave', () => {
                                        btnContainer.style.opacity = '0';
                                    });

                                    // Capturar clic en el botón de edición y obtener el ID
                                    const btnEdit = td.querySelector('.btn-edit');
                                    if (btnEdit) {
                                        btnEdit.addEventListener('click', function() {
                                            const criterioId = this.getAttribute('data-id');

                                            showCustomModal('update_modal_descripcion_criterio', criterioId);
                                            document.getElementById('edit_d_criterio').value = nameDescripcionCriterio;

                                        });
                                    }

                                    // Capturar clic en el botón de eliminación y obtener el ID
                                    const btnDelete = td.querySelector('.btn-delete');
                                    if (btnDelete) {
                                        btnDelete.addEventListener('click', function() {
                                            const criterioId = this.getAttribute('data-id');
                                            Swal.fire({
                                                title: "Are you sure?",
                                                text: "You won't be able to revert this!",
                                                icon: "warning",
                                                showCancelButton: true,
                                                confirmButtonColor: "#3085d6",
                                                cancelButtonColor: "#d33",
                                                confirmButtonText: "Yes, delete it!"
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    $.ajax({
                                                        url: `{{ route('criterios.destroy', ['id' => 'criterioId']) }}`.replace('criterioId', criterioId),
                                                        type: 'DELETE',
                                                        dataType: 'json',
                                                        success: function(response) {
                                                            Swal.fire({
                                                                title: "Deleted!",
                                                                text: "Your file has been deleted.",
                                                                icon: "success"
                                                            }).then(() => {
                                                                location.reload();
                                                            });
                                                        },
                                                        error: function(xhr, status, error) {
                                                            console.log("Todo salió mal");
                                                            // Acción de error
                                                        }
                                                    });
                                                }
                                            });
                                        });
                                    }
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


                        <td data-criterio-id="{{ $criterios->id }}" id="seleccionar_criterio{{$criterios->id}}{{$nivel->id}}" class="justify-content-between {{  $evalueRubrica && $nivelSeleccionado && $nivelSeleccionado->nivel_desempeno_id == $nivel->id ? 'seleccionado' : '' }}" data-nivel-id="{{ $nivel->id }}">
                            @if ($descripcionEncontrada)

                            @if ($evalueRubrica ?? false)

                            <div class="overlay select" id="overlay" onclick="marcarCriterio('{{ $criterios->id }}', '{{ $nivel->id }}', '{{$estudiante}}', '{{$rubrica_actividad->rubrica->id}}')">
                                <div class="plus">+</div>
                                <div class="text">Marcar cumplido</div>
                            </div>
                            @endif
                            <style>
                                .criterio-container,
                                .criterio-container * {
                                    cursor: pointer !important;
                                }

                                .buttons-criterios {
                                    transition: opacity 0.3s;
                                    opacity: 0;
                                }
                            </style>
                            <div class="criterio-wrapper">
                                <div class="d-flex justify-content-between criterio-container">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            {{ $descripcionEncontrada->descripcion }}
                                        </div>
                                    </div>

                                    <div class="b-c" style="margin-left: 10px;">
                                        @if ($evalueRubrica ?? false)
                                        <div class="buttons-criterios d-flex flex-column" style="opacity: 0; transition: opacity .3s;">
                                            <button type="button" class="btn btn-danger btn-sm mb-2 btn-delete" data-id="{{ $descripcionEncontrada->id }}">
                                                <i class="la la-trash"></i>
                                            </button>
                                            <button type="button" class="btn btn-primary btn-sm btn-edit" data-id="{{ $descripcionEncontrada->id }}">
                                                <i class="la la-edit"></i>
                                            </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <script>
                                    (function() {
                                        // Usamos el contenedor padre inmediato del script
                                        const wrapper = document.currentScript.parentElement;
                                        // Buscamos el contenedor específico de este criterio
                                        const container = wrapper.querySelector('.criterio-container');
                                        let descripcionA = '{{ $descripcionEncontrada->descripcion }}';
                                        let criterio_id= '{{ $descripcionEncontrada->criterio_id }}';
                                        let nivel_desempeno_id  = '{{ $descripcionEncontrada->nivel_desempeno_id }}';


                                        // Mostrar botones al pasar el mouse sobre el contenedor
                                        container.addEventListener('mouseenter', function() {
                                            const btnContainer = container.querySelector('.buttons-criterios');
                                            if (btnContainer) {
                                                btnContainer.style.opacity = '1';
                                            }
                                        });

                                        // Ocultar botones al salir del contenedor
                                        container.addEventListener('mouseleave', function() {
                                            const btnContainer = container.querySelector('.buttons-criterios');
                                            if (btnContainer) {
                                                btnContainer.style.opacity = '0';
                                            }
                                        });

                                        // Listener para el botón de editar
                                        const btnEdit = container.querySelector('.btn-edit');
                                        if (btnEdit) {
                                            btnEdit.addEventListener('click', function(e) {
                                                e.stopPropagation();
                                                const id = this.getAttribute('data-id');
                                                showCustomModal('update_modal_descripcion',null,null,id);
                                                document.getElementById('edit_descripcion_nivel').value = descripcionA;
                                                document.getElementById('edit_criterio_id').value= criterio_id;
                                                document.getElementById('edit_nivel_id').value=nivel_desempeno_id;


                                            });
                                        }

                                        // Listener para el botón de eliminar
                                        const btnDelete = container.querySelector('.btn-delete');
                                        if (btnDelete) {
                                            btnDelete.addEventListener('click', function(e) {
                                                e.stopPropagation();
                                                const id = this.getAttribute('data-id');
                                                Swal.fire({
                                                title: "Are you sure?",
                                                text: "You won't be able to revert this!",
                                                icon: "warning",
                                                showCancelButton: true,
                                                confirmButtonColor: "#3085d6",
                                                cancelButtonColor: "#d33",
                                                confirmButtonText: "Yes, delete it!"
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    $.ajax({
                                                        url: `{{ route('descripcionacriterionivel.destroy', ['id' => 'id']) }}`.replace('id', id),
                                                        type: 'DELETE',
                                                        dataType: 'json',
                                                        success: function(response) {
                                                            Swal.fire({
                                                                title: "Deleted!",
                                                                text: "Your file has been deleted.",
                                                                icon: "success"
                                                            }).then(() => {
                                                                location.reload();
                                                            });
                                                        },
                                                        error: function(xhr, status, error) {
                                                            console.log("Todo salió mal");
                                                            // Acción de error
                                                        }
                                                    });
                                                }
                                            });
                                            });
                                        }
                                    })();
                                </script>
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



                        <!-- Añadir descripcion -->
                        <div id="descripcionInputs" style="display: none;">
                            <div class="form-group">
                                <label for="descripcionDesc">Descripción</label>
                                <input type="text" class="form-control" id="descripcion_input" required>

                            </div>
                        </div>
                        <!--  eitar descripcion-->
                        <div id="descripcionaInputUpdate" style="display: none;">
                            <div class="form-group">
                                <label for="edit_descripcion_nivel">Descripción</label>
                                <input type="text" class="form-control" id="edit_descripcion_nivel" required>
                                <input type="hidden" class="form-control" id="edit_criterio_id" required>
                                <input type="hidden" class="form-control" id="edit_nivel_id" required>


                            </div>
                        </div>

                        <!-- editar  criterio -->
                        <div id="descripcionInputsEdit" style="display: none;">
                            <div class="form-group">
                                <label for="edit_d_criterio">Descripción</label>
                                <input type="text" class="form-control" id="edit_d_criterio" required>

                            </div>
                        </div>

                        <!-- Añadir nivel -->

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
                        <!-- Editar nivel -->
                        <div id="levelInputsEdit" style="display: none;">
                            <div class="form-group">
                                <label for="edit_level">Descripción de Nivel</label>
                                <input type="text" class="form-control" id="edit_level" required>
                            </div>
                            <div class="form-group">
                                <label for="scoreRangeFromEdit">Puntos del nivel</label>
                                <input type="number" class="form-control" id="scoreRangeFromEdit" required>
                            </div>
                            <div class="form-group">

                                <input type="hidden" class="form-control" value="{{$rubrica_actividad->rubrica->id}}" id="scoreRangeFrom" required>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="cancelButton" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="submitCustomModal()">Agregar</button>
                </div>
            </div>
        </div>
    </div>
</div>
