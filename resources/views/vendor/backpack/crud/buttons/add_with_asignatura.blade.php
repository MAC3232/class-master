{{-- resources/views/vendor/backpack/crud/buttons/add_with_asignatura.blade.php --}}
@if (isset($asignatura_id))
    <a href="{{ url($crud->route.'/create?asignatura_id='.$asignatura_id) }}" class="btn btn-primary">
        <i class="la la-plus"></i> Añadir Actividad
    </a>
@else
    {{-- Si no hay asignatura_id, muestra el botón sin el parámetro --}}
    <a href="{{ url($crud->route.'/create') }}" class="btn btn-primary">
        <i class="la la-plus"></i> Añadir Actividad
    </a>
@endif
