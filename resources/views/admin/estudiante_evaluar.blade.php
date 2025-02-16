@extends(backpack_view('blank'))
@php

$breadcrumbs = [
        trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
        'Asignaturas' => route('asignaturas.index'),
        'Listado' => false, // El último elemento no lleva URL
    ];
@endphp
@section('header')
<section class="content-header">
    <h1 class="text-light">
        Evaluar estudiante: {{$estudiante->nombre}}
    </h1>
    <ol class="breadcrumb m-2">
        <li><a href="{{ backpack_url() }}">Panel</a></li>

        <li>

            <a href="" class="p-2 btn-link">< Volver a asignatura </a>
        </li>
    </ol>
</section>

<div class="d-flex justify-content-start mb-4">
    {{-- Botón de Imprimir --}}
    <button onclick="window.print()" class="btn btn-primary m-2 ">Imprimir Rúbrica</button>

    {{-- Botón de Editar --}}
</div>
@endsection

@section('content')
<div class="row" bp-section="crud-operation-list">
    <div class="col-md-12">

        {{-- Contenedor principal con fondo blanco --}}
        <div class="card p-4 shadow-sm bg-light">
            <h3 class="mb-4">Lista de actividades</h3>

            {{-- Tabla simple para mostrar los estudiantes --}}
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nombre del Estudiante</th>
                        <th>Codigo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($actividades as $actividad)
                        <tr>
                            <td>{{ $actividad->nombre }}</td>
                            <td>{{ $actividad->codigo_estudiantil  }}</td>
                            <td>
                                {{-- Botón de acción para evaluar al estudiante --}}
                                <a href="{{route('Evaluar_actividad.evaluar', ['id'=> $estudiante->id, 'actividad_id'=> $actividad->id])}}" class="btn btn-sm btn-link">
                                    Evaluar esta actividad
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No hay estudiantes matriculados en esta materia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
