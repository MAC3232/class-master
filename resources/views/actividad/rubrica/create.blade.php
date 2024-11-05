@extends(backpack_view('blank'))

@section('header')
<h1 style="color: white;">Crear Nueva Rúbrica para la actividad: {{ $actividad->nombre }}</h1>

@endsection

@section('content')
<div class="container bg-light mt-6 p-5">

    <form action="{{ route('rubrica_actividad.store') }}" method="POST">
        @csrf
        <input type="hidden" name="actividad_id" value="{{ $actividad->id }}">

        <div class="form-group">
            <label for="nombre">Nombre de la Rúbrica</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="nombre">Descripcion</label>
            <input type="text" name="descripcion" id="descripcion" class="form-control" required>
        </div>


        <button type="submit" class="btn btn-primary mt-2">Guardar Rúbrica</button>
    </form>
</div>
@endsection