@extends(backpack_view('blank'))


@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h1>Estudiantes de la Asignatura: {{ $asignatura->nombre }}</h1>

            <!-- Tabla de estudiantes -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($asignatura->students as $estudiante)
                        <tr>
                            <td>{{ $estudiante->id }}</td>
                            <td>{{ $estudiante->nombre }}</td>
                            <td>{{ $estudiante->correo }}</td>
                            <td class="">
                                 <a class="nav-link btn btn-link p-1" href="{{ route('graph', ['id' => $asignatura->id, 'student' => $estudiante->id]) }}">
                                <i class="la la-chart-bar p-1"></i><small> Reporte grafico </small>
                            </a>
                                 <a class="nav-link btn btn-link p-1" href="{{ route('estudianteReport', ['id' => $asignatura->id]) }}">
                                <i class="la la-chart-bar p-1"></i> <small>Reporte documento</small>
                            </a>

                        </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

