

<div class="row" bp-section="crud-operation-list">
    <div class="col-md-12">
        {{-- Contenedor principal con fondo blanco --}}
        <div class="card p-4 shadow-sm bg-light">
            <h3 class="mb-4">Lista de Estudiantes</h3>

            {{-- Tabla simple para mostrar los estudiantes --}}
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nombre del Estudiante</th>
                        <th>Codigo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($estudiantes as $estudiante)
                        <tr>
                            <td>{{ $estudiante->nombre }}</td>
                            <td>{{ $estudiante->codigo_estudiantil }}</td>
                            <td>
                                {{-- Botón de acción para evaluar al estudiante --}}

                                @if (isset($isAsignatura))
                                <a href="{{route('evaluar.estudiante_asignatura', ['id'=> $estudiante->id, 'actividad_id'=> $asignatura->id])}}" class="btn btn-sm btn-link">
                                    Evaluar estudiante
                                </a>

                                <a href="{{route('actividades_estudiante.show', ['id'=> $estudiante->id, 'actividad_id'=> $asignatura->id])}}" class="btn btn-sm btn-link">
                                    Reporte
                                </a>
                                @else

                                <a href="{{route('Evaluar_actividad.evaluar', ['id'=> $estudiante->id, 'actividad_id'=> $actividad->id])}}" class="btn btn-sm btn-link">
                                    Evaluar estudiante
                                </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No hay estudiantes matriculados en esta materia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
