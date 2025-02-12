@extends(backpack_view('blank'))

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">

<div class="container mt-5">
    <h2 class="text-center mb-4">Asignaturas del Docente</h2>

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
                        <th>Codigo</th>
                        <th>Catalogo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($asignaturas as $asignatura)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $asignatura->nombre }}</td>

                            <td>
                                {{ $asignatura->codigo }}

                            </td>
                            <td>
                                {{ $asignatura->catalogo }}

                            </td>
                            <td>

                               <a class="nav-link btn btn-link p-1" href="{{ route('estudianteReport', ['id' => $asignatura->id]) }}">
                                <i class="la la-eye p-1"></i> Reportes
                            </a>

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
@endsection
