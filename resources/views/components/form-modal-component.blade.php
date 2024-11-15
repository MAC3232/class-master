<a href="#" class="{{$idField['class'] ?? 'btn btn-primary w-100'}}" id="{{ $idField['open'] }}">{{ $idField['boton'] ?? '' }}
    <i class="{{$idField['icon'] ?? ''}}" style="font-size: xx-large"></i>
</a>

<!-- Modal -->
<div id="{{ $idField['modal'] }}" class="modal fade" tabindex="-1" role="dialog" style="display: none;">
    <div class="modal-dialog" role="document">
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
                                    <textarea name="{{ $field['name'] }}" id="{{ $field['name'] }}-{{ $idField['modal'] }}" class="form-control" placeholder="Enter {{ $field['name'] }}">{{ $field['value'] ?? '' }}</textarea>
                                @elseif($field['type'] == 'select')
                                    <select name="{{ $field['name'] }}" id="{{ $field['name'] }}-{{ $idField['modal'] }}" class="form-control">
                                        @foreach($field['options'] as $option)
                                            <option value="{{ $option['value'] }}" {{ old($field['name']) == $option['value'] ? 'selected' : '' }}>{{ $option['label'] }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="{{ $field['type'] }}" class="form-control" name="{{ $field['name'] }}" id="{{ $field['name'] }}-{{ $idField['modal'] }}" value="{{ $field['value'] ?? '' }}" placeholder="Enter {{ $field['name'] }}">
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
