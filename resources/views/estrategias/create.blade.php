@extends(backpack_view('blank'))

@section('content')
<div class="container bg-light">
    <h1>Crear Nueva Estrategia de Enseñanza-Aprendizaje</h1>

    <form action="{{ route('estrategia.store', $ra->id) }}" method="POST">
        @csrf

        <!-- Campo para la descripción de la estrategia -->
        <div class="form-group">
            <label for="descripcion">Descripción de la Estrategia</label>
            <textarea name="descripcion" id="descripcion" class="form-control" required></textarea>
        </div>

        <!-- Botón para guardar la estrategia -->
        <button type="submit" class="btn btn-primary">Guardar Estrategia</button>
    </form>
</div>
@endsection
