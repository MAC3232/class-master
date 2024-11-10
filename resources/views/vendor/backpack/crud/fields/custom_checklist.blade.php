


@php
    // Obtener las asignaturas desde el modelo
    use App\Models\Asignaturas;
    $asignaturas = Asignaturas::all();

    // Comprobamos si el campo tiene un valor antes de acceder a él
    $selectedAssignments = old('assignments', isset($field['value']) && $field['value'] ? $field['value']->pluck('id')->toArray() : []);
@endphp

<!-- Campo de búsqueda -->
<input type="text" id="buscar-asignatura" placeholder="Buscar asignatura..." class="form-control mt-3 p-2 rounded-lg border-gray-300 shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500">

<!-- Generación del checklist de asignaturas -->
<div class="grid grid-cols-3 gap-4 mt-4">
    @foreach ($asignaturas as $asignatura)
        <div class="checklist-item flex items-center space-x-2">
            <input type="checkbox" name="assignments[]" value="{{ $asignatura->id }}" 
                   {{ in_array($asignatura->id, $selectedAssignments) ? 'checked' : '' }}
                   class="form-checkbox text-blue-500 h-5 w-5 border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
            <label class="text-gray-700 text-lg font-medium cursor-pointer">
                {{ $asignatura->nombre }}
            </label>
        </div>
    @endforeach
</div>

<!-- Script de filtrado en tiempo real -->
<script>
    document.getElementById('buscar-asignatura').addEventListener('keyup', function() {
        let searchQuery = this.value.toLowerCase();

        // Filtra los elementos de la lista basándose en el texto
        document.querySelectorAll('.checklist-item').forEach(function(item) {
            let label = item.querySelector('label');
            let text = label ? label.innerText.toLowerCase() : '';

            item.style.display = text.includes(searchQuery) ? '' : 'none';
        });
    });
</script>