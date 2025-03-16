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
        .margin-l{
            margin-left: 2rem;
        }
        .margin-l-2{
            margin-left: 5rem;
        }
</style>

@endpush

@php
    $index = 0;
    $defaultBreadcrumbs = [
        'Admin' => url('admin'),
        'Actividades' => 'javascript:window.history.back();', // Regresar atrás
        trans('Rubrica') => false,
    ];

    $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
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
        <div class=" margin-l">
          <h1 class="mb-0">Actividad: {{ $actividad->nombre }}</h1>
          <small class="text-muted">Evaluando a: {{ $estudiante->user->name }}</small>
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
{{-- calificar estudiantes - rubrica --}}
@include('components.rubrica_actividad', ['rubrica_actividad' => $actividad, 'materia' => $materia->id, 'estudiante' => $estudiante->id ])
@endsection


@section('after_scripts')

<script>let actividad = '{{$actividad->id}}';</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/modal.js') }}"></script>

<script src="{{asset('js/checksCriterios.js')}}"></script>
@endsection
