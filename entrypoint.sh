#!/bin/bash

# Generar la clave de la aplicación
php artisan key:generate --force

# Ejecutar migraciones
php artisan migrate --force

# Crear el enlace simbólico de storage
php artisan storage:link

# Iniciar el servidor PHP-FPM
php-fpm
