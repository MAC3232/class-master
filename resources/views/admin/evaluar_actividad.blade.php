@extends(backpack_view('blank'))


@php $evalueRubrica = true; @endphp

@push('after_styles')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Dosis:wght@200..800&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Dosis:wght@200..800&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Dosis:wght@200..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">


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

    @media print {
        .d-print-none {
        display: none;
    }
    html, body{
        width: 100% !important;
    }
    .container, .mt-5, .bg-light, .p-2, .rounded{
        min-width: 100%    !important;
    }
    }

    .seleccionado {
        background-color: #565656 !important;
        color: white;
        transition: all 0.3s ease-in-out;
    }

    td {
            width: 150px;
            height: 100px;
            text-align: center;
            position: relative;
            font-family: "Poppins",Helvetica Neue,sans-serif;
            border: 1px solid #ddd;
            transition: background 0.3s ease;
        }
        .overlay {
            cursor: pointer;
            position: absolute;
            top: 0;
            border-radius: 10px;
            left: 0;
            font-weight: bold;
            font-size: 2em;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            color: rgba(255, 255, 255, 0.72);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        td:hover {
    opacity: 1;
}



td.seleccionado .overlay:hover {
    opacity: 0;
}
        td .overlay:hover {
            opacity: 1;
        }
        td .overlay:active {
            transition: 1s;
            scale: 0.95;
        }


        .overlay .plus {
            font-size: 3em;
            font-weight: bold;
            font-family: "Dosis", serif;
        }
        .overlay .text {
            font-size: 14px;
            margin-top: 5px;
        }
</style>

@endpush


@section('header')

<section class="content-header">
    <h1 class="">
        Evaluando a: {{$estudiante->user->name}}
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
    <button onclick="window.print()" class="btn btn-primary m-2 d-print-none">Imprimir Rúbrica</button>
</div>
@endsection

@section('content')
{{-- calificar estudiantes - rubrica --}}
@include('components.rubrica_actividad', ['rubrica_actividad' => $actividad, 'materia' => $materia->id, 'estudiante' => $estudiante->id ])
@endsection


@section('after_scripts')

<script>let actividad = '{{$actividad->id}}';</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('js/modal.js')}}"></script>
<script src="{{asset('js/checksCriterios.js')}}"></script>
@endsection
