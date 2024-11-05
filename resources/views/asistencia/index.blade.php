@extends(backpack_view('blank'))

@section('header')


<section class="content-header">
    <h1 class="text-light">
        Diseñador de Rúbrica: 
    </h1>
    <ol class="breadcrumb m-2">
        <li><a href="{{ backpack_url() }}">Panel</a></li>

        <li class="active">  </li>
        <li>

            <a href="{{url('/admin/asignaturas/'.$asignatura->id.'/show')}}" class="p-2 btn-link">
                < Volver</a>
        </li>



    </ol>
</section>
<a href="{{ route('asistencia.generar', ['asignatura_id' => $asignatura->id]) }}" class="btn btn-primary">
<i class="la la-list"></i> Tomar asistencia
</a>





@endsection



@section('content')
<div class="container">
    <h1>Asistencias de Estudiantes para la Asignatura: {{ $asignatura->nombre }}</h1>

    <table class="table bg-light">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>ID Estudiante</th>
                <th>Nombre Estudiante</th>
                <th>Asistencia</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($asistenciasConEstudiantes as $asistencia)
                <tr>
                    <td>{{ $asistencia->fecha }}</td>
                    <td>{{ $asistencia->codigo_estudiantil }}</td>
                    <td>{{ $asistencia->estudiante ? $asistencia->estudiante->nombre : 'Estudiante no encontrado' }}</td>
                    <td>Presente</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No hay asistencias registradas para esta asignatura.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection



