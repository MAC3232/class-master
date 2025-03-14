@extends(backpack_view('blank'))

@php
  $defaultBreadcrumbs = [
    trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
    $crud->entity_name_plural => url($crud->route),
    trans('backpack::crud.list') => false,
  ];

  // Si los breadcrumbs no están definidos en el CrudController, se usa el valor por defecto
  $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
    <section class="header-operation container-fluid animated fadeIn d-flex mb-2 align-items-baseline d-print-none" bp-section="page-header">
        <h1 class="text-capitalize mb-0" bp-section="page-heading">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</h1>
    </section>
@endsection

@section('content')
  {{-- Caja principal --}}
  <div class="row" bp-section="crud-operation-list">
    {{-- CONTENIDO PRINCIPAL --}}
    <div class="{{ $crud->getListContentClass() }}">
        <div class="row mb-2 align-items-center">
          <div class="col-sm-9">

            @if ( $crud->buttons()->where('stack', 'top')->count() ||  $crud->exportButtons())
              <div class="d-print-none {{ $crud->hasAccess('create')?'with-border':'' }}">
@if ( backpack_user()->hasRole('admin') || backpack_user()->hasRole('super-admin')|| Route::is('actividad.index') )

@include('crud::inc.button_stack', ['stack' => 'top'])

@endif




              </div>
            @endif
          </div>
          @if($crud->getOperationSetting('searchableTable'))
          <div class="col-sm-3">
            <div id="datatable_search_stack" class="mt-sm-0 mt-2 d-print-none">
              <div class="input-icon">
                <span class="input-icon-addon">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                    <path d="M21 21l-6 -6"></path>
                  </svg>
                </span>
                <input type="search" class="form-control" placeholder="{{ trans('backpack::crud.search') }}..."/>
              </div>
            </div>
          </div>
          @endif
        </div>

        {{-- Filtros de Backpack --}}
        @if ($crud->filtersEnabled())
          @include('crud::inc.filters_navbar')
        @endif

        <div class="{{ backpack_theme_config('classes.tableWrapper') }}">
            <table
              id="crudTable"
              class="{{ backpack_theme_config('classes.table') ?? 'table table-striped table-hover nowrap rounded card-table table-vcenter card d-table shadow-xs border-xs' }}"
              data-responsive-table="{{ (int) $crud->getOperationSetting('responsiveTable') }}"
              data-has-details-row="{{ (int) $crud->getOperationSetting('detailsRow') }}"
              data-has-bulk-actions="{{ (int) $crud->getOperationSetting('bulkActions') }}"
              data-has-line-buttons-as-dropdown="{{ (int) $crud->getOperationSetting('lineButtonsAsDropdown') }}"
              data-line-buttons-as-dropdown-minimum="{{ (int) $crud->getOperationSetting('lineButtonsAsDropdownMinimum') }}"
              data-line-buttons-as-dropdown-show-before-dropdown="{{ (int) $crud->getOperationSetting('lineButtonsAsDropdownShowBefore') }}"
              cellspacing="0">
            <thead>
              <tr>
                {{-- Columnas de la tabla --}}
                @foreach ($crud->columns() as $column)
                  @php
                    $exportOnlyColumn = $column['exportOnlyColumn'] ?? false;
                    $visibleInTable = $column['visibleInTable'] ?? ($exportOnlyColumn ? false : true);
                    $visibleInModal = $column['visibleInModal'] ?? ($exportOnlyColumn ? false : true);
                    $visibleInExport = $column['visibleInExport'] ?? true;
                    $forceExport = $column['forceExport'] ?? (isset($column['exportOnlyColumn']) ? true : false);
                  @endphp
                  <th
                    data-orderable="{{ var_export($column['orderable'], true) }}"
                    data-priority="{{ $column['priority'] }}"
                    data-column-name="{{ $column['name'] }}"
                    data-visible="{{ $exportOnlyColumn ? 'false' : var_export($visibleInTable) }}"
                    data-visible-in-table="{{ var_export($visibleInTable) }}"
                    data-can-be-visible-in-table="{{ $exportOnlyColumn ? 'false' : 'true' }}"
                    data-visible-in-modal="{{ var_export($visibleInModal) }}"
                    data-visible-in-export="{{ $exportOnlyColumn ? 'true' : ($visibleInExport ? 'true' : 'false') }}"
                    data-force-export="{{ var_export($forceExport) }}"
                  >
                    {{-- Casilla para acciones masivas --}}
                    @if($loop->first && $crud->getOperationSetting('bulkActions'))
                      {!! View::make('crud::columns.inc.bulk_actions_checkbox')->render() !!}
                    @endif
                    {!! $column['label'] !!}
                  </th>
                @endforeach

                @if ($crud->buttons()->where('stack', 'line')->count())
                  <th data-orderable="false"
                      data-priority="{{ $crud->getActionsColumnPriority() }}"
                      data-visible-in-export="false"
                      data-action-column="true">
                      {{ trans('backpack::crud.actions') }}
                  </th>
                @endif
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <tr>
                {{-- Columnas del pie de tabla --}}
                @foreach ($crud->columns() as $column)
                  <th>
                    {{-- Casilla para acciones masivas --}}
                    @if($loop->first && $crud->getOperationSetting('bulkActions'))
                      {!! View::make('crud::columns.inc.bulk_actions_checkbox')->render() !!}
                    @endif
                    {!! $column['label'] !!}
                  </th>
                @endforeach

                @if ($crud->buttons()->where('stack', 'line')->count())
                  <th>{{ trans('backpack::crud.actions') }}</th>
                @endif
              </tr>
            </tfoot>
          </table>
        </div>
        @if ($crud->buttons()->where('stack', 'bottom')->count())
          <div id="bottom_buttons" class="d-print-none text-sm-left">
              @include('crud::inc.button_stack', ['stack' => 'bottom'])
              <div id="datatable_button_stack" class="float-right float-end text-right hidden-xs"></div>
          </div>
        @endif
    </div>
  </div>
@endsection

@section('after_styles')
  {{-- Estilos de DATA TABLES --}}
  @basset('https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css')
  @basset('https://cdn.datatables.net/fixedheader/3.3.1/css/fixedHeader.dataTables.min.css')
  @basset('https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css')

  {{-- CSS para ocultar el mensaje de paginación y el botón Reset --}}
  <style>
    .dataTables_info {
        display: none !important; /* Oculta "Showing 1 to 10 of 70 entries." */
    }
    button[type="reset"] {
        display: none !important; /* Oculta el botón "Reset" */
    }
  </style>

  {{-- CRUD LIST CONTENT - crud_list_styles stack --}}
  @stack('crud_list_styles')
@endsection

@section('after_scripts')
  {{-- Se incluye la lógica de DataTables para cargar los datos --}}
  @include('crud::inc.datatables_logic')

  {{-- CRUD LIST CONTENT - crud_list_scripts stack --}}
  @stack('crud_list_scripts')
@endsection

