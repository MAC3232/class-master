<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro de Asistencia</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
  <div class="row">
    <div class="col-md-10 offset-md-1">
      <!-- Contenedor principal -->
      <div class="card p-4 shadow-sm bg-light">
        <h1 class="text-center mb-4">Registro de Asistencia</h1>
        <p class="text-center">
          Toma la asistencia de la clase: <strong>{{ $asignatura->nombre }}</strong>
        </p>
        <!-- Formulario de registro de asistencia -->
        <form action="{{ route('asistencia.guardar', $asignatura->id) }}" method="POST">
          @csrf
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Estudiante</th>
                <th>Fecha</th>
                <th class="text-center">Presente</th>
              </tr>
            </thead>
            <tbody>
              @foreach($estudiantes as $estudiante)
              <tr>
                <td>{{ $estudiante->user->name }}</td>
                <td>{{ date('Y-m-d') }}</td>
                <td class="text-center">
                  <!-- Usamos el código_estudiantil para identificar al estudiante -->
                  <input type="checkbox" name="asistencia[{{ $estudiante->codigo_estudiantil }}]" value="1">
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <button type="submit" class="btn btn-primary">Guardar Asistencia</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
