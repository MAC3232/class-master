@extends(backpack_view('blank'))

@section('content')
<div class="container bg-light">
    <h1>Editar Resultado de aprendizaje</h1>

    <form action="{{ route('ra.update', $estrategia->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Campo para la descripción de la estrategia -->
        <div class="form-group">
            <label for="descripcion">Descripción de la Estrategia</label>
            <textarea name="descripcion" id="descripcion" class="form-control" required>{{ $estrategia->descripcion }}</textarea>
        </div>

        <!-- Botón para actualizar la estrategia -->
        <button type="submit" class="btn btn-primary">Actualizar Estrategia</button>
    </form>
</div>
@endsection
