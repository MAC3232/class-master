@extends(backpack_view('blank'))



@php

$breadcrumbs = [
trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
'Asignaturas' => route('courses.index'),
'Panel asignatura' => route('asignaturas.show', ['id'=> $asignatura['id']]),
'Estudiantes' => false, // El último elemento no lleva URL
];
@endphp



@section('header')
<section class="content-header">
  <div class="container-fluid mb-3">
    <div class="row">
      <!-- Columna izquierda -->
      <div class="col-sm-6 d-flex align-items-center">
        <!-- Botón de retroceso -->
        <a href="javascript:window.history.back();"
           class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center mr-3"
           style="width: 50px; height: 50px;">
          <i class="la la-arrow-left" style="font-size: 1.5rem;"></i>
        </a>

        <!-- Títulos -->
        <div class=" m-5">
          <h1 class="mb-0">Asignatura: {{ $asignatura['nombre']['nombre'] }}</h1>
          <small class="text-muted">Estudiantes</small>
        </div>
      </div>

      <!-- Columna derecha (breadcrumb u otros enlaces) -->
      <div class="col-sm-6 text-right">
<!-- Botón para imprimir -->
<div class="d-flex m-2 justify-content-end ">
  <button onclick="window.print()" class="btn btn-secundary h-100 fs-1 d-print-none"> <i class="la la-print"></i></button>
</div>
      </div>
    </div>
  </div>
</section>


@endsection




@section('content')

<div class="row" bp-section="crud-operation-list">
    <div class="col-md-12">

        {{-- Contenedor principal con fondo blanco --}}
        <div class="card p-4 shadow-sm bg-light">
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
            @if (session('import_success'))
            <div class="alert alert-success">
                <h4>Estudiante</h4>
                <ul>
                    @foreach (session('import_success') as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(session('successImport'))
            <div class="alert alert-success" role="alert">
                {{session('successImport')}}
            </div>

            @endif
            @if (session('error'))
            <div class="alert alert-danger">
                <h4>⚠️ Errores en la Importación</h4>
                <ul>

                    @if(session('error') && is_array(session('error')))
                    @foreach (session('error') as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                    @else
                    <p>{{session('error')}}.</p>
                    @endif

                </ul>
            </div>
            @endif
            <div class="float-end float-right d-flex justify-content-end mb-3">
                <!-- Puedes colocar esto arriba de tu tabla de estudiantes -->
                <form method="GET" action="{{ route('admin.asignaturas.index', ['id' => $asignatura['id']]) }}">
                    <input type="text" name="search" placeholder="Buscar estudiante..." value="{{ request('search') }}">
                    <button type="submit">Buscar</button>
                  </form>




                <!-- Botón Añadir con Modal -->
                <button class="m-1 btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAdd">
        <i class="la la-plus-circle"></i> Añadir
    </button>
                <!-- Botón Seleccionar/Borrar -->
                <button id="btnSeleccionar" class="m-1 btn btn-warning">
                    <i class="la la-check-square"></i> Seleccionar
                </button>
                <!-- Botón Cancelar (Oculto por defecto) -->
                <button id="btnCancelar" class="m-1 btn btn-danger d-none">
                    <i class="la la-times-circle"></i> Cancelar
                </button>
                <!-- Botón Importar con Modal -->
                <button class="m-1 btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalImport">
                    <i class="la la-upload"></i> Importar Estudiantes
                </button>
            </div>

            <div class="container">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th id="headerCheckAll" class="d-none">
                                    <div id="checkAllContainer" class="m-1">
                                        <input type="checkbox" id="checkAll">
                                        <label for="checkAll">Seleccionar Todo</label>
                                    </div>
                                </th>
                                <th>N°</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Código</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $index => $student)
                            <tr>
                                <td class="check-col d-none">
                                    <input type="checkbox" class="check-student" value="{{ $student->id }}">
                                </td>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $student->user->name }}</td>
                                <td>{{ $student->user->email }}</td>
                                <td>{{ $student->codigo_estudiantil }}</td>
                                <td>

                                    <a href="{{route('estudiantes.show', ['id' =>  $student->id ])}}" class=" m-1 "
                                        data-estudiante-id="{{ $student->id }}">
                                        <i class="la la-eye fs-2"></i>
                                    </a>
                                    <a href="#" class="btn m-1 btn-sm btn-danger btn-borrar"
                                        data-estudiante-id="{{ $student->id }}"
                                        data-asignatura-id="{{ $asignatura['id'] }}">
                                        <i class="la la-trash fs-2"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No hay estudiantes matriculados en esta asignatura.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>


            <!-- Paginación -->
            <div class="d-flex justify-content-center">
                <!-- Aquí iría la paginación -->
            </div>
        </div>
    </div>
</div>
</div>

<!-- Modal Añadir -->
<div class="modal fade " id="modalAdd" tabindex="-1" aria-labelledby="modalAddLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddLabel">Añadir estudiantes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container mt-4">

                    <!-- Filtros -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="text" id="buscar" class="form-control" placeholder="Buscar estudiante...">
                        </div>
                        <div class="col-md-4">
                            <select id="filtro-carrera" class="form-select">
                                <option value="">Todas las carreras</option>
                                @foreach ( $carrers as $carrer )
                                <option value="{{ $carrer->id }}"> {{ $carrer->nombre }} </option>

                                @endforeach
                            </select>
                        </div>

                    </div>

                    <!-- Tabla de estudiantes -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tabla-estudiantes">
                            <thead class="table-dark">
                                <tr>
                                    <th>Seleccionar</th>
                                    <th>Nombre</th>
                                    <th>Código</th>
                                    <th>Carrera</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <nav>

                        <div class="pagination-container">
                            <ul class="pagination justify-content-center" id="paginacion"></ul>
                        </div>

                    </nav>

                    <!-- Campo oculto para la materia actual -->
                    <input type="hidden" id="materia-id" value="{{$asignatura['id']}}">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button id="guardar-seleccion" class="btn btn-success ">Guardar</button>


            </div>
        </div>
    </div>
</div>





<!-- Modal Importar -->
<div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="modalImportLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalImportLabel">Importar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="container mt-4">
                    <p>
                        <span class="fs-1">📌</span> El archivo debe estar en formato 📂 <strong>CSV</strong> o 📊 <strong>Excel</strong> para garantizar una importación exitosa ✅.
                    </p>


                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th>📌 Código</th>
                                    <th>📌 Nombre</th>
                                    <th>📌 Identificacion</th>
                                    <th>📌 Correo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Juan Pérez</td>
                                    <td>25</td>
                                    <td>juan@example.com</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Ana López</td>
                                    <td>30</td>
                                    <td>ana@example.com</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Opciones de Subida de Archivos -->
                <form action="{{route('assignment.students.import', ['id' =>$asignatura['id'] ]  )}}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="asignatura" value="{{$asignatura['id']}}">
                    <div class="form-group">
                        <label for="file">Seleccionar archivo (Excel o CSV)</label>
                        <input type="file" class="form-control m-2" id="file" name="file" required>
                    </div>



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Importar</button>
            </div>
            </form>
        </div>
    </div>
</div>

@endsection




@push('after_styles')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .pagination-container {
        overflow-x: auto;
        /* Permite desplazamiento horizontal si es necesario */
        white-space: nowrap;
        /* Evita que los elementos se envuelvan */
        max-width: 100%;
        /* Evita que la paginación sobrepase el ancho */
        padding: 5px;
        /* Añade un pequeño espacio para evitar cortes feos */
    }

    .pagination {
        display: flex;
        flex-wrap: nowrap;
        /* Evita que los elementos se rompan en varias líneas */
    }

    .modal-backdrop,
    .fade,
    .show {
        pointer-events: none !important;
    }

    .modal-backdrop {
        z-index: 0 !important;
    }

    .modal {
        z-index: 1051 !important;
    }

    .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.3) !important;
        /* Ajusta la opacidad */
        z-index: 0 !important;
        /* Cambia el nivel z-index para asegurarte de que no quede encima del modal */
    }

    .modal {
        z-index: 1051 !important;
        /* Asegúrate de que el modal tenga un z-index superior al del backdrop */
    }


    .modal {

        z-index: 1050 !important;
        position: fixed !important;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .modal-backdrop {
        z-index: 20000 !important;
    }

    .carrer {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 300px;
        font-size: 16px;
        font-family: Arial, sans-serif;
        color: #333;
    }

    /* Centrar los checkboxes en la tabla */
    .check-col,
    #headerCheckAll {
        text-align: center;
        vertical-align: middle;
        width: 50px;
        /* Ajusta el ancho si es necesario */
    }

    /* Estilo para el checkbox de "Seleccionar Todo" */
    #checkAllContainer {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    #checkAll {
        transform: scale(1.3);
        /* Aumenta el tamaño */
        margin-right: 5px;
    }
</style>
@endpush


@push('after_scripts')
<script>
    // Variable global para almacenar los IDs de estudiantes seleccionados
    let asignatura = `{{$asignatura['id']}}`;
    let studentsSelect = [];

    // Función para actualizar la selección de un checkbox
    function cambiarEstado(checkbox, id) {
        if (checkbox.checked) {
            // Agrega el ID solo si no está ya incluido
            if (!studentsSelect.includes(id)) {
                studentsSelect.push(id);
            }
        } else {
            let index = studentsSelect.indexOf(id);
            if (index !== -1) {
                studentsSelect.splice(index, 1);
            }
        }
    }

    $(document).ready(function() {

        // Función para cargar estudiantes vía AJAX
        function cargarEstudiantes(page = 1, search = '', carrera_id = '', asignatura_id = asignatura) {
            $.ajax({
                url: `/admin/studentsassigment?page=${page}&search=${encodeURIComponent(search)}&carrera_id=${encodeURIComponent(carrera_id)}&asignatura_id=${asignatura_id}`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (!response || !response.data) {
                        console.error("Respuesta inválida del servidor.");
                        return;
                    }

                    // Obtiene los estudiantes asignados desde el backend
                    let asignados = response.asignados || [];
                    // Si hay asignados, asegúrate de que también queden en studentsSelect
                    asignados.forEach(id => {
                        if (!studentsSelect.includes(id)) {
                            studentsSelect.push(id);
                        }
                    });

                    let html = '';
                    // Recorre la lista de estudiantes y marca el checkbox si su ID está en studentsSelect
                    response.data.data.forEach(estudiante => {
                        let checked = studentsSelect.includes(estudiante.id) ? 'checked' : '';
                        html += `<tr>
                                    <td class="text-center">
                                        <input type="checkbox" class="form-check-input checkbox-materia" data-id="${estudiante.id}" ${checked}
                                            onchange="cambiarEstado(this, ${estudiante.id})">
                                    </td>
                                    <td>${estudiante.user.name}</td>
                                    <td>${estudiante.codigo_estudiantil}</td>
                                    <td class="carrer">${estudiante.carrera ? estudiante.carrera.nombre : 'Sin carrera'}</td>
                                </tr>`;
                    });

                    $('#tabla-estudiantes tbody').html(html);
                    actualizarPaginacion(response.data);
                },
                error: function(xhr, status, error) {
                    console.error("Error al cargar estudiantes:", error);
                    Swal.fire('Error', 'No se pudieron cargar los estudiantes.', 'error');
                }
            });
        }

        // Función para actualizar la paginación (puedes ajustar según tus necesidades)
        function actualizarPaginacion(response) {
            if (!response || !response.last_page) {
                console.error("Error: datos de paginación no encontrados.");
                return;
            }
            let currentPage = response.current_page;
            let lastPage = response.last_page;
            let paginacionHtml = '';

            if (currentPage > 1) {
                paginacionHtml += `<li class="page-item">
                                       <a href="#" class="page-link" data-page="${currentPage - 1}">&laquo;</a>
                                   </li>`;
            }

            for (let i = 1; i <= Math.min(4, lastPage); i++) {
                paginacionHtml += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                                       <a href="#" class="page-link" data-page="${i}">${i}</a>
                                   </li>`;
            }

            if (currentPage > 6) {
                paginacionHtml += `<li class="page-item disabled">
                                       <span class="page-link">...</span>
                                   </li>`;
            }

            let start = Math.max(5, currentPage - 2);
            let end = Math.min(lastPage - 4, currentPage + 2);
            for (let i = start; i <= end; i++) {
                paginacionHtml += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                                       <a href="#" class="page-link" data-page="${i}">${i}</a>
                                   </li>`;
            }

            if (currentPage < lastPage - 5) {
                paginacionHtml += `<li class="page-item disabled">
                                       <span class="page-link">...</span>
                                   </li>`;
            }

            for (let i = Math.max(lastPage - 3, 5); i <= lastPage; i++) {
                paginacionHtml += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                                       <a href="#" class="page-link" data-page="${i}">${i}</a>
                                   </li>`;
            }

            if (currentPage < lastPage) {
                paginacionHtml += `<li class="page-item">
                                       <a href="#" class="page-link" data-page="${currentPage + 1}">&raquo;</a>
                                   </li>`;
            }

            $('#paginacion').html(paginacionHtml);
        }

        // Búsqueda: al escribir en el input con id="buscar"
        $('#buscar').on('keyup', function() {
            let search = $(this).val().trim();
            cargarEstudiantes(1, search);
        });

        // Filtro por carrera: al cambiar el select
        $('#filtro-carrera').on('change', function() {
            let carrera_id = $(this).val();
            cargarEstudiantes(1, '', carrera_id);
        });

        // Paginación: al hacer clic en algún enlace de paginación
        $(document).on('click', '.page-link', function(e) {
            e.preventDefault();
            let page = $(this).data('page');
            if (page) {
                cargarEstudiantes(page);
            }
        });

        // Guardar la selección de estudiantes: se usa la variable global studentsSelect
        $('#guardar-seleccion').on('click', function() {
            if (studentsSelect.length === 0) {
                Swal.fire('Atención', 'Debe seleccionar al menos un estudiante.', 'warning');
                return;
            }

            $.ajax({
                url: '/admin/estudiantes/materia',
                type: 'POST',
                data: {
                    estudiantes: studentsSelect,
                    materia_id: $('#materia-id').val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire('Guardado', 'Asignación guardada correctamente', 'success');
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error("Error al guardar:", error);
                    Swal.fire('Error', 'No se pudo guardar la asignación.', 'error');
                }
            });
        });

        // Botón para borrar estudiante (mantén tu lógica actual)
        $(document).on('click', '.btn-borrar', function(e) {
            e.preventDefault();
            let estudianteId = $(this).data('estudiante-id');
            let asignaturaId = $(this).data('asignatura-id');

            Swal.fire({
                title: "¿Estás seguro?",
                text: "Eliminarás al estudiante solo de esta asignatura.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/asignaturas/${asignaturaId}/estudiantes/${estudianteId}`,
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire("Eliminado", response.message, "success");
                            location.reload();
                        },
                        error: function(xhr) {
                            Swal.fire("Error", "No se pudo eliminar al estudiante de la asignatura.", "error");
                        }
                    });
                }
            });
        });

        // Cargar estudiantes al inicio
        cargarEstudiantes();
    });
</script>
@endpush

