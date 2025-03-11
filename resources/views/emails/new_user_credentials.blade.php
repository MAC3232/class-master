<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Credenciales de acceso</title>
</head>
<body>
    <p>Hola, {{ $user->name }}</p>
    <p>Se ha creado una cuenta para ti en Class Master.</p>
    <p><strong>Correo:</strong> {{ $user->email }}</p>
    <p><strong>Contraseña:</strong> {{ $plainPassword }}</p>
    <p>Por favor, inicia sesión y cambia tu contraseña lo antes posible.</p>
    <br>
    <p>Saludos,</p>
    <p>El equipo de Class Master</p>
</body>
</html>
