@php
    use App\Models\Asignaturas;

    // Capturamos el término de búsqueda del request
    $search = request()->get('search');

    // Obtenemos las asignaturas con paginación y filtrado
    $asignaturas = Asignaturas::when($search, function ($query, $search) {
        $query->where('nombre', 'like', "%{$search}%")
              ->orWhere('codigo', 'like', "%{$search}%");
    })->paginate(10);

    // Obtenemos las asignaturas seleccionadas (si las hay)
    $selectedAssignments = old('assignments', isset($field['value']) && $field['value'] ? $field['value']->pluck('id')->toArray() : []);
@endphp

<!-- Campo de búsqueda -->
<label for=""> {!! $field['label'] !!}</label>
<input type="text" id="buscar-asignatura" placeholder="Buscar asignatura..."
       value="{{ $search }}" class="form-control rounded-lg border-gray-300 shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500">

<!-- Generación del checklist de asignaturas -->
<div id="asignaturas-list" class="column mt-4">
    @foreach ($asignaturas as $asignatura)
        <div class="checklist-item flex items-center space-x-2">
            <input type="checkbox" name="assignments[]" value="{{ $asignatura->id }}"
                   {{ in_array($asignatura->id, $selectedAssignments) ? 'checked' : '' }}
                   class="form-checkbox text-blue-500 h-5 w-5 border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
            <label class="text-gray-700 text-lg font-medium cursor-pointer">
                {{ $asignatura->nombre }} ({{ $asignatura->codigo }})
            </label>
        </div>
    @endforeach
</div>

<!-- Controles de paginación -->
<!-- Controles de paginación -->
<div class="mt-4">
    {{ $asignaturas->appends(['search' => $search])->fragment('asignaturas-list')->links() }}
</div>
<style>
    .resaltado {
        background-color: #e9d8fd; /* Color morado claro */
        border: 2px solid #805ad5; /* Borde morado */
        border-radius: 8px;
        padding: 5px;
    }
</style>
<!-- Script de filtrado con recarga de página -->
<script>
    document.getElementById('buscar-asignatura').addEventListener('input', function () {
        let searchQuery = this.value;
        let url = new URL(window.location.href);

        // Agrega el término de búsqueda a la URL
        url.searchParams.set('search', searchQuery);

        // Recarga la página con el nuevo término de búsqueda
        window.location.href = url.toString();
    });

    // Ejecutar cuando la página cargue o se filtre la búsqueda
    document.addEventListener('DOMContentLoaded', function () {
        // Seleccionar el primer checkbox visible en la lista
        const firstVisibleCheckbox = document.querySelector('.checklist-item input[type="checkbox"]:checked')
                                  || document.querySelector('.checklist-item input[type="checkbox"]');

        if (firstVisibleCheckbox) {
            const container = firstVisibleCheckbox.closest('.checklist-item');
            container.scrollIntoView({ behavior: 'smooth' }); // Desplaza al primer elemento visible
        }
    });

    // Seleccionar todos los checkboxes
    let checkboxes = document.querySelectorAll('input[type="checkbox"][name="assignments[]"]');

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            // Acceder al contenedor padre del checkbox
            let container = this.closest('.checklist-item');

            // Si el checkbox está marcado, añadir clase, de lo contrario, quitarla
            if (this.checked) {
                container.classList.add('resaltado');
            } else {
                container.classList.remove('resaltado');
            }
        });

        // Aplicar el resaltado al cargar la página si el checkbox está marcado
        if (checkbox.checked) {
            checkbox.closest('.checklist-item').classList.add('resaltado');
        }
    });
</script>

