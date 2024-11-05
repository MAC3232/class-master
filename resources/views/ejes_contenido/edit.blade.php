@extends(backpack_view('blank'))

@section('content')
<div class="container bg-light">
    <h1>Editar Eje de Contenido</h1>

    <form action="{{ route('eje_contenido.update', $ejeContenido->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Campo para la descripción del eje de contenido -->
        <div class="form-group">
            <label for="descripcion">Descripción del Eje de Contenido</label>
            <textarea name="descripcion" id="descripcion" class="form-control" required>{{ $ejeContenido->descripcion }}</textarea>
        </div>

        <!-- Botón para actualizar el eje de contenido -->
        <button type="submit" class="btn btn-primary">Actualizar Eje de Contenido</button>
    </form>
</div>
@endsection
