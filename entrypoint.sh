#!/bin/bash

# Asegurarse de que el archivo .env exista
if [ ! -f "/var/www/.env" ]; then
  echo ".env file not found!"
  exit 1
fi

# Esperar a que el servicio de base de datos esté disponible
echo "Esperando a que MySQL esté disponible..."
until nc -z -v -w30 $DB_HOST $DB_PORT
do
  echo "Esperando a MySQL en el host $DB_HOST:$DB_PORT..."
  sleep 5
done

# Generar la clave de la aplicación

# Ejecutar migraciones
php artisan migrate --force

# Crear el enlace simbólico de storage


php artisan key:generate --force

php artisan DB:seed

php artisan storage:link

# Iniciar el servidor PHP-FPM
php artisan serve --host=0.0.0.0 --port=8000
