@extends(backpack_view('blank'))
@php
    $breadcrumbs = [
        trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
        'Asignaturas'               => route('asignaturas.index'),
        'Reporte General'           => false,
    ];
@endphp

@section('header')
<a href="{{ url('/admin/reportes/') }}" class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center" 
   style="width: 50px; height: 50px; padding: 0; position: relative; top: -10px; left: 17px;">
    <i class="la la-arrow-left" style="font-size: 1.5rem;"></i>
</a>
    <section class="content-header text-center bg-primary text-light py-4">
        <h1 class="mb-1">
            Reporte General de Asignatura: <strong>{{ $asignatura->nombre }}</strong>
        </h1>
        <ol class="breadcrumb justify-content-center">
            <li>
                <a href="{{ backpack_url() }}" class="text-white">Panel</a>
            </li>
            <li>
                <a href="{{ route('asignaturas.index') }}" class="text-white">Asignaturas</a>
            </li>
        </ol>
    </section>
@endsection

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg">
        <!-- Encabezado del reporte -->
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Gestión de Reporte General</h5>
            <button onclick="window.print()" class="btn btn-warning btn-sm">
                <i class="la la-print"></i> Imprimir
            </button>
        </div>
        <div class="card-body">
            <!-- Selector de Corte -->
            <div class="row mb-4">
                <div class="col-md-6 offset-md-3">
                    <form id="corteForm" method="GET" action="{{ route('graphGeneral', ['id' => $asignatura->id]) }}">
                        <label for="corte" class="form-label">Seleccionar Corte:</label>
                        <select name="corte" id="corte" class="form-select form-select-lg" onchange="this.form.submit()">
                            <option value="1" {{ $corte == 1 ? 'selected' : '' }}>Corte 1</option>
                            <option value="2" {{ $corte == 2 ? 'selected' : '' }}>Corte 2</option>
                            <option value="3" {{ $corte == 3 ? 'selected' : '' }}>Corte 3</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Gráficos: Diagrama de Dona y Diagrama de Barras -->
            <div class="row">
                <!-- Diagrama de Dona -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header text-center bg-success text-white">
                            <strong>Porcentaje General (Dona)</strong>
                        </div>
                        <div class="card-body">
                            <canvas id="cursoDonaChart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Diagrama de Barras -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header text-center bg-primary text-white">
                            <strong>Cantidad por Actividad (Barras)</strong>
                        </div>
                        <div class="card-body">
                            <canvas id="cursoBarChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detalle de Actividades -->
            <div class="row mt-4">
                <div class="col-md-10 offset-md-1">
                    <div class="card">
                        <div class="card-header bg-info text-white text-center">
                            <strong>Detalle de Actividades</strong>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Actividad</th>
                                        <th>Total Evaluaciones</th>
                                        <th>Aprobadas</th>
                                        <th>% Aprobadas</th>
                                        <th>No Aprobadas</th>
                                        <th>% No Aprobadas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($actividades_porcentaje as $actividad)
                                        <tr>
                                            <td>{{ $actividad['nombre'] }}</td>
                                            <td>{{ $actividad['total'] }}</td>
                                            <td>{{ $actividad['aprobadas'] }}</td>
                                            <td>{{ number_format($actividad['porcentaje_aprobadas'], 2) }}%</td>
                                            <td>{{ $actividad['no_aprobadas'] }}</td>
                                            <td>{{ number_format($actividad['porcentaje_no_aprobadas'], 2) }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fin Detalle de Actividades -->
        </div>
    </div>
</div>
@endsection

@section('after_scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Configuración del diagrama de dona (porcentajes generales)
        const cursoDonaData = {
            labels: ['Aprobadas', 'No Aprobadas'],
            datasets: [{
                data: [@json($porcentaje_aprobadas), @json($porcentaje_no_aprobadas)],
                backgroundColor: ['#6BCB77', '#FF6F61'],
            }]
        };

        new Chart(document.getElementById('cursoDonaChart'), {
            type: 'doughnut',
            data: cursoDonaData,
            options: {
                plugins: {
                    legend: { position: 'bottom' },
                    title: {
                        display: true,
                        text: 'Porcentaje de Actividades Aprobadas vs No Aprobadas'
                    }
                }
            }
        });

        // Configuración del diagrama de barras (por actividad)
        const actividades = @json($actividades_porcentaje);
        const etiquetas = actividades.map(item => item.nombre);
        const datosAprobadas = actividades.map(item => item.aprobadas);
        const datosNoAprobadas = actividades.map(item => item.no_aprobadas);

        const cursoBarData = {
            labels: etiquetas,
            datasets: [
                {
                    label: 'Aprobadas',
                    data: datosAprobadas,
                    backgroundColor: '#6BCB77'
                },
                {
                    label: 'No Aprobadas',
                    data: datosNoAprobadas,
                    backgroundColor: '#FF6F61'
                }
            ]
        };

        new Chart(document.getElementById('cursoBarChart'), {
            type: 'bar',
            data: cursoBarData,
            options: {
                plugins: {
                    legend: { position: 'bottom' },
                    title: {
                        display: true,
                        text: 'Cantidad de Evaluaciones por Actividad'
                    }
                },
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Actividades'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Cantidad de Evaluaciones'
                        },
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
@endsection
