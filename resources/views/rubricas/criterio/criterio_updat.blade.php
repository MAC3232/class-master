@extends(backpack_view('blank'))

@section('content')
<div class="container bg-light">
    <h1>Editar Criterio</h1>

    <form action="{{ route('criterio.update', $criterio->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Campo para la descripción del criterio -->
        <div class="form-group">
            <label for="descripcion">Descripción del Criterio</label>
            <textarea name="descripcion" id="descripcion" class="form-control" required>{{ $criterio->descripcion }}</textarea>
        </div>

        <!-- Botón para actualizar el criterio -->
        <button type="submit" class="btn btn-primary">Actualizar Criterio</button>
    </form>
</div>
@endsection
