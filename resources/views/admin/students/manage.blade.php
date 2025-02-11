@extends(backpack_view('blank'))

@php
    $defaultBreadcrumbs = [
      trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
      $crud->entity_name_plural => url($crud->route),
      trans('backpack::crud.preview') => false,
    ];

    // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
    $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp
@section('header')
    <div class="container-fluid d-flex justify-content-between my-3">
        <section class="header-operation animated fadeIn d-flex mb-2 align-items-baseline d-print-none" bp-section="page-header">
          <div>
		  <div class="header-operation animated fadeIn d-flex mb-2 align-items-baseline d-print-none">
		  <h1 class="text-capitalize mb-0" bp-section="page-heading">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</h1>
            <p class="ms-2 ml-2 mb-0" bp-section="page-subheading">{!! $crud->getSubheading() ?? mb_ucfirst(trans('backpack::crud.preview')).' '.$crud->entity_name !!}</p>

				@if ($crud->hasAccess('list'))
					<p class="ms-2 ml-2 mb-0" bp-section="page-subheading-back-button">
						<small><a href="{{ url($crud->route) }}" class="font-sm"><i class="la la-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
					</p>
				@endif
		  </div>
		  <div>


			</div>

		  </div>

        </section>
<div class="float-end float-right ">
        <a href="javascript: window.print();" style="margin-left:10px" class=" p-2 btn h-100   float-end float-right "><i class="la la-print fs-1"></i></a>
@if(Route::is('asignaturas.show'))

    @if (!backpack_auth()->check() || !backpack_user()->hasRole('admin'))
	<a href="{{ url('/admin/assignment/calendario?id='. $entry->getKey() ) }}" class=" p-2 btn h-100  btn-primary">
    <i class="la la-calendar fs-1"></i>

    @endif
</a>

		  @endif
</div>

    </div>

@endsection

@section('content')

<div class="row" bp-section="crud-operation-list">
    <div class="col-md-12">

        {{-- Contenedor principal con fondo blanco --}}
        <div class="card p-4 shadow-sm bg-light">
            <div class="container">
    <h2 class="mb-4">Añadir estudiantes</h2>

    <div class="row">
        <!-- Añadir Estudiante Individual -->
        <div class="col-md-6">
            <h4>Añade un estudiante</h4>
            @if(session('success')  )
                <div class="alert {{ session('success') ?  'alert-success' :  'alert-error' }}">{{ session('success') ?? session("errors") }}</div>
            @endif
            @if($errors->any())
    <div class="alert alert-danger">
        <strong>Por favor corrige los siguientes errores:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
            <form action="{{route('only.students')}}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Identificacion (Dni)</label>
                    <input type="number" name="cedula" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Correo</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Código</label>
                    <input type="number" name="code" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Selecciona la carrera a la que pertenecen los estudiantes</label>
                  <select name="carrera_id" class="form-select" id="">
                    <option value=""> selecciona una Carrera</option>
                    @foreach ( $carreras as $carrera )
                    <option value="{{$carrera->id}}">  {{$carrera->nombre}}</option>

                    @endforeach
                  </select>
                </div>


                <button type="submit" class="btn btn-primary">Guardar</button>
            </form>
        </div>

        <!-- Importar Estudiantes -->



        <div class="col-md-6">

            <h4>Importar Estudiantes</h4>

            @if (session('import_success'))
    <div class="alert alert-success">
        <h4>Estudiante</h4>
        <ul>
            @foreach (session('import_success') as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
            @if (session('import_errors'))
    <div class="alert alert-danger">
        <h4>⚠️ Errores en la Importación</h4>
        <ul>
            @foreach (session('import_errors') as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

            @if(session('successImport') ||  session('error') )
                <div class="alert {{ session('successImport') ?  'alert-success' :  'alert-error' }}">{{ session('successImport') ?? session("error") }}</div>
            @endif

            <form action="{{route('import.students')}}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label>Selecciona la carrera a la que pertenece el estudiante</label>
                  <select name="career_id" class="form-select" id="">
                    <option value=""> selecciona una Carrera</option>
                    @foreach ( $carreras as $carrera )
                    <option value="{{$carrera->id}}">  {{$carrera->nombre}}</option>

                    @endforeach
                  </select>
                </div>

                <div class="form-group">
                    <label>Archivo Excel/CSV</label>
                    <input type="file" name="file" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success">Subir Archivo</button>
            </form>
        </div>
    </div>
</div>

        </div>
        </div>
        </div>

@endsection
