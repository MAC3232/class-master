@extends(backpack_view('blank'))
@section('content')
<div class="container bg-light">
 
    <form action="{{ route('rarubrica.store', ['id'=> $asignatura->id]) }}" method="POST">
        @csrf
        <input type="hidden" name="rubrica_id" value="{{ $asignatura->rubrica->id}}">

        <div class="form-group">
            <label for="nombre">Nombre del Resultado de Aprendizaje</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" required></textarea>
        </div>

     
        <div class="form-group">
            <label for="ra_id">Resultado de Aprendizaje</label>
            <select name="corte" id="" class="form-control" required>
                <option value="">Selecciona un corte</option>
                <option value="1">Corte 1 </option>
            <option value="2">Corte 2 </option>
            <option value="3">Corte 3 </option>
            </select>
        </div>

        <div class="form-group">
            <label for="ponderacion">Ponderación (%)</label>
            <input type="number" name="ponderacion" id="ponderacion" class="form-control" min="0" max="100" required>
        </div>

        <button type="submit" class="btn btn-primary m-2">Guardar Resultado de Aprendizaje</button>
    </form>
</div>
@endsection
