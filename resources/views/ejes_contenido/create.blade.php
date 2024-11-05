@extends(backpack_view('blank'))

@section('content')
<div class="container bg-light">
    <h1>Crear Nuevo Eje de Contenido</h1>

    <form action="{{ route('eje_contenido.store', $ra->id) }}" method="POST">
        @csrf

        <!-- Campo para la descripción del eje de contenido -->
        <div class="form-group">
            <label for="descripcion">Descripción del Eje de Contenido</label>
            <textarea name="descripcion" id="descripcion" class="form-control" required></textarea>
        </div>

        <!-- Botón para guardar el eje de contenido -->
        <button type="submit" class="btn btn-primary">Guardar Eje de Contenido</button>
    </form>
</div>
@endsection
