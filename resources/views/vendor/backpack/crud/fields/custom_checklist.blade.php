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

<style>
    .resaltado {
        background-color: #e9d8fd; /* Color morado claro */
        border: 2px solid #805ad5; /* Borde morado */
        border-radius: 8px;
        padding: 5px;
    }
</style>
<!-- Campo de búsqueda -->
<label for=""> {!! $field['label'] !!}</label>
<input type="text" id="buscar-asignatura" placeholder="Buscar asignatura..."
       value="{{ $search }}" class="form-control rounded-lg border-gray-300 shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500">

<!-- Generación del checklist de asignaturas -->
<div id="asignaturas-list" class="column mt-4">
    @foreach ($asignaturas as $asignatura)
        <div class="checklist-item   {{ in_array($asignatura->id, $selectedAssignments) ? 'resaltado' : '' }}  flex items-center space-x-2">
            <input type="checkbox" data-id="{{ $asignatura->id }}" value="{{ $asignatura->id }}"
            {{ in_array($asignatura->id, $selectedAssignments) ? 'checked' : '' }}
                   class="form-checkbox text-blue-500 h-5 w-5  border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
            <label class="text-gray-700 text-lg font-medium cursor-pointer">
                {{ $asignatura->nombre }} ({{ $asignatura->codigo }})
            </label>
        </div>
    @endforeach
</div>

<!-- Controles de paginación -->
<div class="mt-4">
    {{ $asignaturas->appends(['search' => $search])->fragment('asignaturas-list')->links() }}
</div>

<!-- Script para actualizar checkboxes hidden -->
<script>


    document.getElementById('buscar-asignatura').addEventListener('input', function () {
        let searchQuery = this.value;
        let url = new URL(window.location.href);

        // Agrega el término de búsqueda a la URL
        url.searchParams.set('search', searchQuery);
        url.searchParams.set('page', 1);
        // Recarga la página con el nuevo término de búsqueda
        window.location.href = url.toString();
    });

    function getStudentIdFromUrl() {
    const url = new URL(window.location.href);
    const segments = url.pathname.split('/');
    // Buscar el ID después de "estudiantes"
    const studentId = segments.includes('estudiantes') ? segments[segments.indexOf('estudiantes') + 1] : null;
    // Si no se encuentra un ID, retornar "createestudents"
    return studentId || 'createestudents';
}
    document.addEventListener('DOMContentLoaded', function () {
        // Recuperar los elementos seleccionados desde localStorage
        const selectedAssignments = JSON.parse(localStorage.getItem(`selectedAssignments${getStudentIdFromUrl()}`)) || [];
        const checkboxes = document.querySelectorAll('.checklist-item input[type="checkbox"]');

        // Recorrer todos los checkboxes y marcar los seleccionados desde localStorage
        checkboxes.forEach(function (checkbox) {
            if (selectedAssignments.includes(Number(checkbox.value))) {
                checkbox.checked = true;
                checkbox.closest('.checklist-item').classList.add('resaltado');
            }

            // Escuchar cambios en los checkboxes
            checkbox.addEventListener('change', function () {
                const container = this.closest('.checklist-item');

                if (this.checked) {
                    container.classList.add('resaltado');
                    // Añadir el ID al array y actualizar el localStorage
                    selectedAssignments.push(Number(this.value));
                } else {
                    container.classList.remove('resaltado');
                    // Eliminar el ID del array y actualizar el localStorage
                    const index = selectedAssignments.indexOf(Number(this.value));
                    if (index > -1) {
                        selectedAssignments.splice(index, 1);
                    }
                }

                // Guardar el estado actualizado en localStorage
                localStorage.setItem(`selectedAssignments${getStudentIdFromUrl()}`, JSON.stringify(selectedAssignments));

                // Actualizar los checkboxes hidden
                updateHiddenCheckboxes(selectedAssignments);
            });
        });



function getLocalStorageKey() {
    const studentId = getStudentIdFromUrl();
    return `selectedAssignments_${studentId}`;
}


        // Función para actualizar los checkboxes hidden
        function updateHiddenCheckboxes(selectedAssignments) {
            // Eliminar todos los checkboxes hidden anteriores
            const existingHiddenInputs = document.querySelectorAll('input[type="hidden"][name="assignments[]"]');
            existingHiddenInputs.forEach(input => input.remove());

            // Crear un input hidden por cada asignatura seleccionada
            selectedAssignments.forEach(function (assignmentId) {
                const hiddenCheckbox = document.createElement('input');
                hiddenCheckbox.type = 'hidden';
                hiddenCheckbox.name = 'assignments[]';
                hiddenCheckbox.value = assignmentId;

                document.querySelector('form').appendChild(hiddenCheckbox);
            });
        }

        // Inicialmente actualizar los checkboxes hidden con el estado almacenado en localStorage
        updateHiddenCheckboxes(selectedAssignments);
    });
    const currentUrl = window.location.href;  // URL de la página actual
    const referrerUrl = document.referrer;  // URL de la página anterior

    // Expresión regular para detectar '/admin/estudiantes/[ID]/edit'
    const regexEstudiantesEdit = /admin\/estudiantes\/\d+\/edit/;
    // Verificar si la página anterior contiene '/admin/estudiantes/create'
    const isCreatePage = referrerUrl.includes('/admin/estudiantes/create');
    // Verificar si la página anterior contiene '/admin/estudiantes/[ID]/edit'
    const isEditPage = regexEstudiantesEdit.test(referrerUrl);

    // Si la página anterior no es '/admin/estudiantes/create' ni '/admin/estudiantes/[ID]/edit', eliminar el localStorage
    if (!isCreatePage && !isEditPage) {
        localStorage.clear();  // O usa localStorage.removeItem('key') para borrar un item específico
    }
</script>


