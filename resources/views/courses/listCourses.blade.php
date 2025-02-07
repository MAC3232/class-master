<!-- plantilla backpack -->
@extends(backpack_view('blank'))

<!-- Index -->
@php

    $index = 0;

    $defaultBreadcrumbs = [
      trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
      $crud->entity_name_plural => url($crud->route),
      trans('backpack::crud.list') => false,
    ];

    // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
    $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

<!-- header -->
@section('header')

@endsection
<!-- stilos -->
@push('after_styles')
@endpush

<!-- Contenido -->
@section('content')

@endsection

<!-- scripts -->
@push('after_scripts')
<!-- puede ser una etiqueta <script> //code </script> -->
<!-- o un script por link src -->
@endpush
