<!-- PLANTILLA BASE DE BACKPACK -->
@extends(backpack_view('blank'))

<!-- Migajas de pan -->
@php
    $index = 0;
    $defaultBreadcrumbs = [
        trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
        $crud->entity_name_plural => url($crud->route),
        trans('PANEL') => false,
    ];


    $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;



@endphp


<!-- Configuracion de estulilos -->
@push('after_styles')

<!-- Recomendable. el archivo se encuentra en public->css -->
<link rel="stylesheet" href="{{asset('css/panel-asignatura.css')}}">

<style>

</style>
@endpush

<!-- iNFORMACION DEL HEADER EJ: NOMBRE DE LA ASIGNATURA, ... -->
@section('header')
<section>

</section>
@endsection

<!-- Contenido o cuerpo -->
@section('content')

@endsection

<!-- Script js -->
@push('after_scripts')
<!-- Recomendable. el archivo se encuentra en: public->js -->
<script src="{{asset('js/panel-asignatura.js')}}"></script>
<script></script>
@endpush
