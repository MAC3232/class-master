
@extends(backpack_view('blank'))



@php

    $index = 0;

    $defaultBreadcrumbs = [
      trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
      $crud->entity_name_plural => url($crud->route),
      trans('backpack::crud.preview') => false,
    ];

    // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
    $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp



@section('header')
    <div class="container-fluid d-flex justify-content-between my-3">
        <section class="header-operation animated fadeIn d-flex mb-2 align-items-baseline d-print-none" bp-section="page-header">
          <div>
		  <div class="header-operation animated fadeIn d-flex mb-2 align-items-baseline d-print-none">
		  <h1 class="text-capitalize mb-0" bp-section="page-heading">Asignatura:Estudiantes</h1>
            <p class="ms-2 ml-2 mb-0" bp-section="page-subheading">{!! $crud->getSubheading() ?? mb_ucfirst(trans('backpack::crud.preview')).' '.$crud->entity_name !!}</p>

				@if ($crud->hasAccess('list'))
					<p class="ms-2 ml-2 mb-0" bp-section="page-subheading-back-button">
						<small><a href="{{ url($crud->route) }}" class="font-sm"><i class="la la-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
					</p>
				@endif

		  </div>

		  <div>



			</div>

<a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAdd">
                    <i class="la la-plus-circle"></i> Añadir estudiantes
                </a>
		  </div>


        </section>

        <div class="float-end float-right ">

<a href="javascript: window.print();" style="margin-left:10px" class=" p-2 btn h-10  float-end float-right "><i class="la la-print fs-1"></i></a>

</div>

    </div>

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
        <i class="la la-upload"></i> Importar
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
                        <td>{{ $student->nombre }}</td>
                        <td>{{ $student->correo }}</td>
                        <td>{{ $student->codigo_estudiantil }}</td>
                        <td>
                            <a href="" class="btn btn-sm btn-info">
                                <i class="la la-eye fs-2"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-danger btn-borrar"
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
        <input type="hidden" id="materia-id"  value="{{$asignatura['id']}}">

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
                                <th>📌 Nombre(opcional)</th>
                                <th>📌 Identificacion(Opcional)</th>
                                <th>📌 Correo(opcional)</th>
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
<style>

.pagination-container{
    overflow-x: auto;  /* Permite desplazamiento horizontal si es necesario */
    white-space: nowrap;  /* Evita que los elementos se envuelvan */
    max-width: 100%;  /* Evita que la paginación sobrepase el ancho */
    padding: 5px; /* Añade un pequeño espacio para evitar cortes feos */
}

.pagination {
    display: flex;
    flex-wrap: nowrap; /* Evita que los elementos se rompan en varias líneas */
}

.modal-backdrop, .fade, .show{
    pointer-events: none !important;
}
     .modal-backdrop {
            z-index: 0 !important;
        }
        .modal {
            z-index: 1051 !important;
        }
    .modal-backdrop {
    background-color: rgba(0, 0, 0, 0.3) !important; /* Ajusta la opacidad */
    z-index: 0 !important; /* Cambia el nivel z-index para asegurarte de que no quede encima del modal */
}
.modal {
    z-index: 1051 !important; /* Asegúrate de que el modal tenga un z-index superior al del backdrop */
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

    .carrer{
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
    width: 50px; /* Ajusta el ancho si es necesario */
}

/* Estilo para el checkbox de "Seleccionar Todo" */
#checkAllContainer {
    display: flex;
    justify-content: center;
    align-items: center;
}

#checkAll {
    transform: scale(1.3); /* Aumenta el tamaño */
    margin-right: 5px;
}

</style>
@endpush


<!-- JS en línea -->
@push('after_scripts')

<!-- SweetAlert2 -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="{{asset('js/deleteAll.js')}}"></script>
<script>
let asignatura = `{{$asignatura['id']}}`;
let studentsSelect = [];




function cambiarEstado(checkbox, id) {


    if (checkbox.checked) {
        studentsSelect.push(id);
    }else{
        let index = studentsSelect.indexOf(id);

        if (index !== -1) {
            studentsSelect.splice(index, 1);

}


    }




    // Aquí puedes enviar el estado a tu backend si es necesario
  }




 $(document).ready(function() {
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

            let asignados = response.asignados || []; // Estudiantes ya asignados
            let html = '';



asignados.forEach(element => {
    studentsSelect.push(element);
});

            response.data.data.forEach(estudiante => {
                let checked = asignados.includes(estudiante.id) ? 'checked' : ''; // Marcar si ya está asignado

                if(checked === 'checked' && !studentsSelect.includes(estudiante.id)){
                    studentsSelect.push(estudiante.id);

                }


                html += `
                    <tr>
                        <td class="text-center">
                            <input type="checkbox" onchange="cambiarEstado(this, ${estudiante.id})" class="form-check-input checkbox-materia" data-id="${estudiante.id}" ${checked}>
                        </td>
                        <td>${estudiante.codigo_estudiantil}</td>
                        <td>${estudiante.nombre}</td>
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


    function actualizarPaginacion(response) {
    if (!response || !response.last_page) {
        console.error("Error: datos de paginación no encontrados.");
        return;
    }

    let currentPage = response.current_page;
    let lastPage = response.last_page;
    let paginacionHtml = '';

    // Botón "Anterior"
    if (currentPage > 1) {
        paginacionHtml += `<li class="page-item">
                               <a href="#" class="page-link" data-page="${currentPage - 1}">&laquo;</a>
                           </li>`;
    }

    // Páginas iniciales (1, 2, 3, 4)
    for (let i = 1; i <= Math.min(4, lastPage); i++) {
        paginacionHtml += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                               <a href="#" class="page-link" data-page="${i}">${i}</a>
                           </li>`;
    }

    // Primera separación "..."
    if (currentPage > 6) {
        paginacionHtml += `<li class="page-item disabled">
                               <span class="page-link">...</span>
                           </li>`;
    }

    // Páginas cercanas a la actual
    let start = Math.max(5, currentPage - 2);
    let end = Math.min(lastPage - 4, currentPage + 2);

    for (let i = start; i <= end; i++) {
        paginacionHtml += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                               <a href="#" class="page-link" data-page="${i}">${i}</a>
                           </li>`;
    }

    // Segunda separación "..."
    if (currentPage < lastPage - 5) {
        paginacionHtml += `<li class="page-item disabled">
                               <span class="page-link">...</span>
                           </li>`;
    }

    // Últimas páginas (ejemplo: 20, 21, 22)
    for (let i = Math.max(lastPage - 3, 5); i <= lastPage; i++) {
        paginacionHtml += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                               <a href="#" class="page-link" data-page="${i}">${i}</a>
                           </li>`;
    }

    // Botón "Siguiente"
    if (currentPage < lastPage) {
        paginacionHtml += `<li class="page-item">
                               <a href="#" class="page-link" data-page="${currentPage + 1}">&raquo;</a>
                           </li>`;
    }

    $('#paginacion').html(paginacionHtml);
}


    // Búsqueda
    $('#buscar').on('keyup', function() {
        let search = $(this).val().trim();
        cargarEstudiantes(1, search);
    });

    // Filtro por carrera
    $('#filtro-carrera').on('change', function() {
        let carrera_id = $(this).val();
        cargarEstudiantes(1, '', carrera_id);
    });

    // Paginación
    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        let page = $(this).data('page');
        if (page) {
            cargarEstudiantes(page);
        }
    });

    // Guardar selección de estudiantes
    $('#guardar-seleccion').on('click', function() {
        let seleccionados = [];
        seleccionados = studentsSelect





        if (seleccionados.length === 0) {
            Swal.fire('Atención', 'Debe seleccionar al menos un estudiante.', 'warning');
            return;
        }

        $.ajax({
            url: '/admin/estudiantes/materia',
            type: 'POST',
            data: {
                estudiantes: seleccionados,
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
                    _token: $('meta[name="csrf-token"]').attr('content') // CSRF Token de Laravel
                },
                success: function(response) {


                    Swal.fire("Eliminado", response.message, "success");
                    location.reload();
                    // Recargar la lista de estudiantes después de borrar

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

