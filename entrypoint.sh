#!/bin/bash



# Generar la clave de la aplicación

# Ejecutar migraciones
php artisan migrate --force

# Crear el enlace simbólico de storage


php artisan key:generate --force

php artisan storage:link

php artisan DB:seed

# Iniciar el servidor PHP-FPM
# php artisan serve --host=0.0.0.0 --port=8000
php-fpm
