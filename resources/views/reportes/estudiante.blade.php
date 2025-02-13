@extends(backpack_view('blank'))
@php

$breadcrumbs = [
        trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
        'Asignaturas' => route('asignaturas.index'),
        'Listado' => false, // El último elemento no lleva URL
    ];
@endphp



@section('content')
<a href="{{ url('/admin/reportes/') }}" class="btn btn-primary">
    <i class="la la-arrow-left"></i> Volver
</a>



<div class="container">
    <div class="card">
        <div class="card-body">
            <h1>Estudiantes de la Asignatura: {{ $asignatura->nombre }}</h1>

            <!-- Tabla de estudiantes -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($asignatura->students as $estudiante)
                        <tr>
                            <td>{{ $estudiante->id }}</td>
                            <td>{{ $estudiante->nombre }}</td>
                            <td>{{ $estudiante->correo }}</td>
                            <td class="">
                                 <a class="nav-link btn btn-link p-1" href="{{ route('graph', ['id' => $asignatura->id, 'student' => $estudiante->id]) }}">
                                <i class="la la-chart-bar p-1"></i><small> Reporte grafico </small>
                            </a>
                              

                        </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('header')

