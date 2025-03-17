@extends(backpack_view('blank'))
@php

$breadcrumbs = [
        trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
        'Asignaturas' => route('courses.index'),
        'Listado' => false, // El último elemento no lleva URL
    ];
@endphp

@section('header')
<section class="content-header">

 <div class="container-fluid mb-3">
    <div class="row">
      <!-- Columna izquierda -->
      <div class="col-sm-6 w-100 d-flex align-items-center">
        <!-- Botón de retroceso -->
        <a href="javascript:window.history.back();"
           class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center "
           style="width: 50px; height: 50px;">
          <i class="la la-arrow-left" style="font-size: 1.5rem;"></i>
        </a>

        <!-- Títulos -->
        <div class=" m-5 w-100 ">
          <h1 class="mb-0 ">Evaluar estudiante:  </h1>
          Evaluar estudiante: {{$estudiante->user->name}}

        </div>
      </div>

      <!-- Columna derecha (breadcrumb u otros enlaces) -->

    </div>
  </div>
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
                        <th>Nombre de la actividad</th>

                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($actividades as $actividad)
                        <tr>
                            <td>{{ $actividad->nombre }}</td>

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
