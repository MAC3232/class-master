@extends(backpack_view('blank'))

@section('content')
<div class="container bg-light">
    <h1>Crear Nueva Actividad para el Resultado de Aprendizaje: </h1>

    <!-- Formulario para crear la actividad -->
    <form action="{{ route('actividad.store', ['id' => $asignatura->id]) }}" method="POST">
        @csrf

        <!-- Campo para el nombre de la actividad -->
        <div class="form-group">
            <label for="nombre">Nombre de la Actividad</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="ra_id">Resultado de Aprendizaje</label>
            <select name="ra_id" id="ra_id" class="form-control" required>
                <option value="">Selecciona un Resultado de Aprendizaje</option>
                @foreach($raList as $ra)
                    <option value="{{ $ra->id }}">{{ $ra->nombre }}</option>
                @endforeach
            </select>
        </div>

        <!-- Campo para la fecha de la actividad -->
        <div class="form-group">
            <label for="fecha">Fecha de la Actividad</label>
            <input type="date" name="fecha" id="fecha" class="form-control" required>
        </div>

        <!-- Campo para la descripción de la actividad -->
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" required></textarea>
        </div>

        <!-- Campo para la ponderación de la actividad -->
        <div class="form-group">
            <label for="ponderacion">Ponderación (%)</label>
            <input type="number" name="ponderacion" id="ponderacion" class="form-control" min="0" max="100" required>
        </div>

        <!-- Botón para guardar la actividad -->
        <button type="submit" class="btn btn-primary">Guardar Actividad</button>
    </form>
</div>
@endsection
