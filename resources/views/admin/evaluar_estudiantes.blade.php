@extends(backpack_view('blank'))

@section('header')
<section class="content-header">
    <h1 class="">
        Evaluar estudiantes
    </h1>
    <ol class="breadcrumb m-2">
        <li><a href="{{ backpack_url() }}">Panel</a></li>
        <li class="active">{{ $asignatura->nombre }}</li>
        <li>
            <a href="/admin/asignaturas/{{ $asignatura->id }}/show" class="p-2 btn-link">
                < Volver a asignatura </a>
        </li>
    </ol>



</section>

<div class="d-flex justify-content-start mb-4">
    {{-- Botón de Imprimir --}}
    <button onclick="window.print()" class="btn btn-primary m-2">Imprimir Rúbrica</button>
</div>
@endsection

@section('content')
@include('components.tabla-estudiantes', [ 'asignatura' => $asignatura])
@endsection
