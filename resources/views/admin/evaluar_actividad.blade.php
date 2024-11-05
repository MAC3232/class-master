@extends(backpack_view('blank'))
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
    {{--  calificar estudiantes - rubrica --}}
@include('components.rubrica_actividad', ['rubrica_actividad' => $actividad, 'materia' => $materia->id, 'estudiante' => $estudiante->id ])

@endsection

@section('after_scripts')
<script src="{{asset('js/modal.js')}}"></script>

@endsection