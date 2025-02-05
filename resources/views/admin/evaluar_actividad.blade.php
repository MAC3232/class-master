@extends(backpack_view('blank'))

@push('after_styles')
<style>
    .seleccionado {
        background-color: rgba(25, 135, 84, 0.8) !important;
        /* Bootstrap Success más vibrante */
        color: white;
        font-weight: bold;
        border: 2px solid #146c43;
        /* Borde más oscuro para resaltar */
        border-radius: 12px;
        transition: all 0.3s ease-in-out;
    }
</style>

@endpush

@php
$evalueRubrica = true;
@endphp
@section('header')
<style>
    html,
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 14px;
    }

    .modal-backdrop.show {
        pointer-events: none !important;
        z-index: 0 !important;
        opacity: 0 !important;
    }
</style>
<section class="content-header">
    <h1 class="text-light">
        Evaluando a: {{$estudiante->nombre}}
    </h1>
    <ol class="breadcrumb m-2">
        <li><a href="{{ backpack_url() }}">Panel</a></li>
        <li class="active">{{ $actividad->nombre }}</li>
        <li>
            <a href="/admin/asignaturas/{{ $actividad->id }}/evaluar-estudiante" class="p-2 btn-link">
                < Volver a todos los estudiantes </a>
        </li>
    </ol>



</section>

<div class="d-flex justify-content-start mb-4">
    {{-- Botón de Imprimir --}}
    <button onclick="window.print()" class="btn btn-primary m-2">Imprimir Rúbrica</button>
</div>
@endsection

@section('content')
{{-- calificar estudiantes - rubrica --}}
@include('components.rubrica_actividad', ['rubrica_actividad' => $actividad, 'materia' => $materia->id, 'estudiante' => $estudiante->id ])






@endsection





@section('after_scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('js/modal.js')}}"></script>

<script>
    let elementoAnterior = null;

    const marcarCriterio = (criterioId, nivelDesempenoId, usuarioId, rubrica_actividad) => {
        let actividad = '{{$actividad->id}}';
        console.log(actividad);

        $.ajax({
            url: '/admin/selectcriterios', // Ruta donde se envían los datos
            type: 'POST',
            data: {
                usuario_id: usuarioId,
                criterio_id: criterioId,
                nivel_desempeno_id: nivelDesempenoId,
                rubrica_id: rubrica_actividad,
                _token: $('meta[name="csrf-token"]').attr('content') // Token CSRF para Laravel
            },
            success: function(response) {
                console.log(response.data.estudiante_id);

                $.ajax({
                    url: `/admin/actividad/${actividad}/evaluatestudent/${response.data.estudiante_id}`, // Ruta dinámica con los valores
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);

                        document.getElementById('note-view').innerHTML = response;

                        let criterio = document.getElementById(`seleccionar_criterio${criterioId}${nivelDesempenoId}`);

                        criterio.classList.add("seleccionado");
                    },
                    error: function(xhr, status, error) {
                        console.error("Error en la solicitud:", error);
                    }
                });




            },
            error: function(xhr, status, error) {
                console.error("Error al guardar:", error);
                Swal.fire('Error', 'No se pudo guardar el desempeño.', 'error');
            }
        });
    }
</script>

@endsection
