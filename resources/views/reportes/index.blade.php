@extends(backpack_view('blank'))

<<<<<<< HEAD
@php
    $breadcrumbs = [
        trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
        'Asignaturas'               => route('asignaturas.index'),
        'Listado'                   => false, // El último elemento no lleva URL
    ];
@endphp


@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">

            <div class="container mt-5">
                <h2 class="text-center mb-4">Asignaturas del Docente</h2>

                <!-- Buscador -->
                <div class="mb-3">
                    <input type="text" id="search-input" class="form-control" placeholder="Buscar asignaturas...">
                </div>

                @if($asignaturas->isEmpty())
                    <div class="alert alert-info text-center">
                        No hay asignaturas disponibles para este docente.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre de Asignatura</th>
                                    <th>Código</th>
                                    <th>Catálogo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($asignaturas as $asignatura)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $asignatura->nombre }}</td>
                                        <td>{{ $asignatura->codigo }}</td>
                                        <td>{{ $asignatura->catalogo }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a class="nav-link btn btn-link p-1" href="{{ route('estudianteReport', ['id' => $asignatura->id]) }}">
                                                    <i class="la la-chart-bar p-1"></i> Gráficos
                                                </a>
                                                <a class="nav-link btn btn-link p-1" href="{{ route('graphGeneral', ['id' => $asignatura->id]) }}">
                                                    <i class="la la-file-alt p-1"></i> Reporte gráfico general
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>

<!-- Script para el buscador (filtrado en el cliente) -->
<script>
    document.getElementById('search-input').addEventListener('keyup', function() {
        var value = this.value.toLowerCase();
        var rows = document.querySelectorAll('table tbody tr');
        rows.forEach(function(row) {
            row.style.display = row.textContent.toLowerCase().includes(value) ? '' : 'none';
        });
    });
</script>
@endsection
