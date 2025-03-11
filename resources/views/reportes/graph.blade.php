@extends(backpack_view('blank'))
@php

$breadcrumbs = [
        trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
        'Asignaturas' => route('asignaturas.index'),
        'Listado' => false, // El último elemento no lleva URL
    ];
@endphp
<style>
    @media print {
        /* Ocultar elementos no relevantes para impresión */
        .btn, .breadcrumb,  , #corteForm, .card-header.bg-dark {
            display: none;
        }

        /* Ajustar márgenes para impresión */
        body {
            margin: 0;
            padding: 1cm;
            background-color: white !important;
        }

        /* Cambiar diseño de tarjetas */
        .card {
            border: none !important;
            box-shadow: none !important;
        }

        .card-header {
            font-size: 1.2em;
            text-align: center;
            color: black;
            background-color: transparent !important;
        }

        /* Asegurar que el contenido ocupe toda la página */
        .container {
            width: 100%;
        }

        /* Forzar gráficos a un tamaño específico */
        canvas {
            width: 100% !important;
            height: auto !important;
        }

        /* Personalizar fuentes */
        h1, h2, h5 {
            font-family: 'Arial', sans-serif;
            font-weight: bold;
        }
    }
</style>

@section('header')
    <section class="content-header text-center bg-primary text-light py-4">
        <h1 class="mb-1">Reporte Materia: <strong>{{ $asignatura->nombre }}</strong></h1>
        <h2>Estudiante: <strong>{{ $student->user->name }}</strong></h2>
        <ol class="breadcrumb justify-content-center">
            <li><a href="{{ backpack_url() }}" class="text-white">Panel</a></li>
            <li><a href="#" onclick="window.history.back()" class="text-white">Volver</a></li>
        </ol>
    </section>
@endsection

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Gestión de Reporte</h5>
            <button onclick="window.print()" class="btn btn-warning btn-sm">
                <i class="la la-print"></i> Imprimir
            </button>
        </div>
        <div class="card-body">
            <!-- Selector de Corte -->
            <div class="row mb-4">
                <div class="col-md-6 offset-md-3">
                    <form id="corteForm" method="GET" action="{{ route('graph', ['id' => $asignatura->id, 'student' => $student->id]) }}">
                        <label for="corte" class="form-label">Seleccionar Corte:</label>
                        <select name="corte" id="corte" class="form-select form-select-lg" onchange="this.form.submit()">
                            <option value="1" {{ request('corte') == '1' ? 'selected' : '' }}>Corte 1</option>
                            <option value="2" {{ request('corte') == '2' ? 'selected' : '' }}>Corte 2</option>
                            <option value="3" {{ request('corte') == '3' ? 'selected' : '' }}>Corte 3</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Gráficos -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header text-center bg-info text-white">
                            <strong>Notas por Actividad</strong>
                        </div>
                        <div class="card-body">
                            <canvas id="notasChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header text-center bg-success text-white">
                            <strong>Resultados de Aprendizaje</strong>
                        </div>
                        <div class="card-body">
                            <canvas id="resultadosChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
@endsection

@section('after_scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Configuración de gráficos
    const notasData = {
        labels: @json(array_keys($resultados)),
        datasets: [{
            label: 'Notas',
            data: @json($resultados),
            backgroundColor: ['#FF6F61', '#FFD56B', '#6BCB77', '#4D96FF'],
        }]
    };

    new Chart(document.getElementById('notasChart'), {
        type: 'bar',
        data: notasData,
        options: {
            plugins: {
                title: { display: true, text: 'Notas por Actividad' }
            },
            scales: {
                x: { title: { display: true, text: 'Actividades' } },
                y: { beginAtZero: true, max: 5, title: { display: true, text: 'Notas (Escala 1-5)' } }
            }
        }
    });

    const resultadosData = {
    labels: ['Aprobadas', 'No aprobadas'],
    datasets: [{
        data: [@json($porcentaje_aprobadas), @json($porcentaje_no_aprobadas)], // Usa los porcentajes calculados
        backgroundColor: ['#6BCB77', '#FF6F61'], // Colores para aprobadas y no aprobadas
    }]
};

new Chart(document.getElementById('resultadosChart'), {
    type: 'doughnut',
    data: resultadosData,
    options: {
        plugins: {
            legend: { position: 'bottom' },
            title: { display: true, text: 'Estado de Actividades' }
        }
    }
});


</script>
@endsection
