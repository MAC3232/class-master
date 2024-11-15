
<style>



.overlay-container {

    background: rgba(0, 0, 0, 0.5); /* Fondo oscuro con transparencia */
    backdrop-filter: blur(5px); /* Aplica el desenfoque */

}



</style>



<a href="#" class="{{$idField['class'] ?? 'btn btn-primary w-100'}}" id="{{ $idField['open'] }}">{{ $idField['boton'] ?? '' }}
    <i class="{{$idField['icon'] ?? ''}}" style="font-size: xx-large"></i>
</a>


<!-- Modal -->
<div id="{{ $idField['modal'] }}" class="modal fade overlay-container" tabindex="-1" role="dialog" style="display: none;">
    <div class="modal-dialog h-100" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{$idField['name']}}</h5>
                <button type="button" class="close" id="Cerrar-{{ $idField['open'] }}" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Formulario -->
                <form action="{{ $actionUrl }}" method="POST">
                    @csrf

                    @if ( isset($idField['method'] ) )
                    @method(''.$idField['method'])
                    @endif

                    <div class="modal-body">
                        @foreach ($fields as $field)
                            <div class="form-group">
                                <label for="{{ $field['name'] }}-{{ $idField['modal'] }}">{{ ucfirst($field['label']) }}</label>
                                @if($field['type'] == 'textarea')
                                <div class="form-group">

                                    <x-character-count
                                        :name="$field['name']"
                                        :value="$field['value'] ?? ''"
                                        :id="$field['name'] . '-' . $idField['modal']"
                                        :maxLength="$field['maxLength'] ?? 100"
                                        :type="$field['type']"
                                    />
                                </div>



                                @elseif($field['type'] == 'select')
                                    <select name="{{ $field['name'] }}" required id="{{ $field['name'] }}-{{ $idField['modal'] }}" class="form-control">
                                        @foreach($field['options'] as $option)
                                            <option value="{{ $option['value'] }}" {{ old($field['name']) == $option['value'] ? 'selected' : '' }}>{{ $option['label'] }}</option>
                                        @endforeach
                                    </select>
                                @elseif($field['type'] == 'text' || $field['type'] == 'number' )
                                <div class="form-group">

                                    <x-character-count
                                        :name="$field['name']"
                                        :value="$field['value'] ?? ''"
                                        :id="$field['name'] . '-' . $idField['modal']"
                                        :maxLength="$field['maxLength'] ?? 100"
                                        :type="$field['type']"

                                    />
                                </div>
                                    {{-- <input type="{{ $field['type'] }}" class="form-control" name="{{ $field['name'] }}" id="{{ $field['name'] }}-{{ $idField['modal'] }}" value="{{ $field['value'] ?? '' }}" placeholder="Enter {{ $field['name'] }}"> --}}
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="fotter-{{ $idField['open'] }}" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Asigna los identificadores únicos de PHP a variables JavaScript
        var modalId = "{{ $idField['modal'] }}";
        var openButtonId = "{{ $idField['open'] }}";
        var cerrar = "Cerrar-{{ $idField['open'] }}";
        var cerrarFotter = "fotter-{{ $idField['open'] }}";
        var errors = "{{$idField['errors'] ?? null}}"; // Verifica si hay errores
        console.log(errors);

        // Si hay errores, abre el modal automáticamente
        if (errors) {
            var modal = document.getElementById(modalId);
            modal.style.display = 'block';
            modal.classList.add('show');
        }

        // Evento para abrir el modal
        document.getElementById(openButtonId).addEventListener('click', function(event) {
            event.preventDefault();
            var modal = document.getElementById(modalId);
            modal.style.display = 'block'; // Muestra el modal
            modal.classList.add('show');   // Añade la clase Bootstrap para mostrar el modal
        });

        // Evento para cerrar el modal desde el botón de cierre
        document.getElementById(cerrar).addEventListener('click', function() {
            var modal = document.getElementById(modalId);
            modal.style.display = 'none';
            modal.classList.remove('show');
        });

        // Evento para cerrar el modal desde el botón en el pie de página
        document.getElementById(cerrarFotter).addEventListener('click', function() {
            var modal = document.getElementById(modalId);
            modal.style.display = 'none';
            modal.classList.remove('show');
        });

        // Opcional: cerrar el modal al hacer clic fuera del contenido del modal
        window.addEventListener('click', function(event) {
            var modal = document.getElementById(modalId);
            if (event.target === modal) {
                modal.style.display = 'none';
                modal.classList.remove('show');
            }
        });
    });
</script>
