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
        <div class="col-md-8 offset-md-2">
            <!-- Contenedor principal con fondo blanco y sombra -->
            <div class="card p-4 shadow-sm bg-light">
                <h1 class="text-center mb-4">Registro de Asistencia</h1>
                <p class="text-center">Ingrese su código estudiantil para registrar la asistencia.</p>
                
                <!-- Formulario de registro de asistencia -->
                <form action="{{ route('asistencia.registrar', ['token' => $qrAsistencia->token]) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="codigo_estudiantil">Código Estudiantil</label>
                        <input type="text" name="codigo_estudiantil" id="codigo_estudiantil" class="form-control" placeholder="Ingrese su código estudiantil" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary mt-3">Marcar Asistencia</button>
                    </div>
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
    