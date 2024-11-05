
{{-- Ejemplo en Blade para generar el QR --}}
<!-- <a href="{{ route('asistencia.generar', ['asignatura_id' => $entry->getKey()]) }}" class="btn btn-link">
    <i class="la la-list"></i> Tomar asistencia
</a> -->
<a href="{{ route('asistencias.index', ['asignatura_id' => $entry->getKey()]) }}" class="btn btn-link">
    <i class="la la-list"></i> Ver asistencias
</a>

