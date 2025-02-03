@extends(backpack_view('blank'))



@php
$evalueRubrica= false;
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
        Diseñador de Rúbrica: {{ $rubrica_actividad->nombre }}
    </h1>
    <ol class="breadcrumb m-2">
        <li><a href="{{ backpack_url() }}">Panel</a></li>

        <li class="active">{{ $rubrica_actividad->nombre }}</li>
        <li>

            <a href="/admin/actividad/{{$rubrica_actividad->id}}/show" class="p-2 btn-link">
                < Volver</a>
        </li>
    </ol>
</section>

<button onclick="window.print()" class="btn btn-secondary m-2 ">Imprimir Rúbrica</button>



@endsection

@section('content')
@include('components.rubrica_actividad', ['rubrica_actividad' => $rubrica_actividad])

@endsection


@section('after_scripts')
<script src="{{asset('js/modal.js')}}"></script>
@endsection
