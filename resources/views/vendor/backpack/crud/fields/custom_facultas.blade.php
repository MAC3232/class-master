@php
    use App\Models\Facultad;

    // Obtenemos todas las facultades
    $facultades = Facultad::all();
@endphp

<div>
    <!-- Select de facultades -->
    <label for="facultad_id">Facultades</label>
    <select
        name="{{$field['name']}}"
        id="facultad_select"
        @include('crud::fields.inc.attributes')
    >
        <option value="">Seleccione una facultad</option>
        @foreach ($facultades as $facultad)
            <option value="{{ $facultad->id }}" {{ old('facultad_id') == $facultad->id ? 'selected' : '' }}>
                {{ $facultad->nombre }}
            </option>
        @endforeach
    </select>
</div>


<script>
    document.getElementById('facultad_select').addEventListener('change', function () {
        const facultadId = this.value;
        const carreraSelect = document.getElementById('carrera_select');
        const asignaturaSelect = document.getElementById('asignatura_select');

        // Resetear los selects dependientes
        carreraSelect.innerHTML = '<option value="">Seleccione una carrera</option>';
        carreraSelect.disabled = true;
        asignaturaSelect.innerHTML = '<option value="">Seleccione una asignatura</option>';
        asignaturaSelect.disabled = true;

        if (facultadId) {
            fetch(`/admin/facultad/${facultadId}/carreras`)
                .then(response => response.json())
                .then(carreras => {
                    if (carreras.length > 0) {
                        carreras.forEach(carrera => {
                            const option = document.createElement('option');
                            option.value = carrera.id;


                            option.textContent = carrera.nombre;
                            carreraSelect.appendChild(option);
                        });
                        carreraSelect.disabled = false;
                    } else {
                        carreraSelect.innerHTML = '<option value="">No hay carreras disponibles</option>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    carreraSelect.innerHTML = '<option value="">Error al cargar carreras</option>';
                });
        }
    });

    document.getElementById('carrera_select').addEventListener('change', function () {
        const carreraId = this.value;
        const asignaturaSelect = document.getElementById('asignatura_select');

        // Resetear el select dependiente
        asignaturaSelect.innerHTML = '<option value="">Seleccione una asignatura</option>';
        asignaturaSelect.disabled = true;

        if (carreraId) {
            fetch(`/admin/carrera/${carreraId}/asignaturas`)
                .then(response => response.json())
                .then(asignaturas => {
                    if (asignaturas.length > 0) {

                        console.log(asignaturas);

                        asignaturas.forEach(asignatura => {
                            const option = document.createElement('option');
                            option.value = asignatura.id;
                            option.textContent = asignatura.nombre + " ("+ asignatura.codigo +")";
                            asignaturaSelect.appendChild(option);
                        });
                        asignaturaSelect.disabled = false;
                    } else {
                        asignaturaSelect.innerHTML = '<option value="">No hay asignaturas disponibles</option>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    asignaturaSelect.innerHTML = '<option value="">Error al cargar asignaturas</option>';
                });
        }
    });
</script>





