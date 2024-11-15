#!/bin/bash

# Generar la clave de la aplicación

# Ejecutar migraciones
php artisan migrate --force

php artisan key:generate --force

php artisan DB:seed --force
# Crear el enlace simbólico de storage
php artisan storage:link

# Iniciar el servidor PHP-FPM
php-fpm
