@extends(backpack_view('blank'))

@php

$breadcrumbs = [
        trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
        'Asignaturas' => route('courses.index'),
        'Listado' => false, // El último elemento no lleva URL
    ];
@endphp

@section('header')
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
          <h1 class="mb-0 ">Asignatura: {{$asignatura->nombre}}  </h1>
        </div>
      </div>

      <!-- Columna derecha (breadcrumb u otros enlaces) -->

    </div>
  </div>

</section>

<div class="d-flex justify-content-start mb-4">
    {{-- Botón de Imprimir --}}
    <button onclick="window.print()" class="btn btn-primary m-2">Imprimir Rúbrica</button>
</div>
@endsection

@section('content')
@include('components.tabla-estudiantes', [ 'asignatura' => $asignatura])
@endsection
