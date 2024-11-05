@extends(backpack_view('blank'))

@section('content')
<div class="container bg-light">
    <!-- Título de la página -->
    <h1>Agregar Criterio para el Resultado de Aprendizaje: {{ $ra->nombre }}</h1>

    <!-- Formulario para crear el criterio -->
    <form action="{{ route('criterio.store', ['id' => $ra->id]) }}" method="POST">
        @csrf

        <!-- Campo para la descripción del criterio -->
        <div class="form-group">
            <label for="descripcion">Descripción del Criterio</label>
            <textarea name="descripcion" id="descripcion" class="form-control" required></textarea>
        </div>

        <!-- Botón para guardar el criterio -->
        <button type="submit" class="btn btn-primary m-2">Guardar Criterio</button>
    </form>
</div>
@endsection
