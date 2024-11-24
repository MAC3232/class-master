@extends(backpack_view('blank'))

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <div class="container">
                <div class="row">
                    <!-- Gráfico de Notas -->
                    <div class="col-md-6">
                        <canvas id="notasChart"></canvas>
                    </div>

                    <!-- Gráfico de Resultados -->
                    <div class="col-md-6">
                        <canvas id="resultadosChart"></canvas>
                    </div>
                </div>

                <!-- Promedio General -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <canvas id="promedioChart"></canvas>
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
    // Gráfico de Notas
    const notasData = {
        labels: @json(array_keys($resultados)), // Nombres de actividades
        datasets: [{
            label: 'Notas',
            data: @json($notas), // Notas del estudiante
            backgroundColor: ['#FF6F61', '#FFD56B', '#6BCB77', '#4D96FF'], // Colores
        }]
    };

    new Chart(document.getElementById('notasChart'), {
        type: 'bar',
        data: notasData,
        options: {
            plugins: { title: { display: true, text: 'Notas por Actividad' } },
            scales: { y: { beginAtZero: true, max: 5 } }
        }
    });

    // Gráfico de Resultados
    const resultadosData = {
        labels: @json(array_keys($resultados)), // Nombres de actividades
        datasets: [{
            label: 'Promedio',
            data: @json(array_values($resultados)), // Promedios
            backgroundColor: ['#9D4EDD', '#4D96FF', '#6BCB77', '#FFD56B'], // Colores
        }]
    };

    new Chart(document.getElementById('resultadosChart'), {
        type: 'doughnut',
        data: resultadosData,
        options: { plugins: { legend: { position: 'bottom' }, title: { display: true, text: 'Resultados de Aprendizaje' } } }
    });

    // Promedio General
    const promedioData = {
        labels: ['Promedio General'],
        datasets: [{
            label: 'Promedio',
            data: [@json($promedio)],
            backgroundColor: ['#6BCB77'], // Verde
        }]
    };

    new Chart(document.getElementById('promedioChart'), {
        type: 'bar',
        data: promedioData,
        options: { plugins: { title: { display: true, text: 'Promedio General' } }, scales: { y: { beginAtZero: true, max: 5 } } }
    });
</script>
@endsection
