# Usa la imagen oficial de PHP 8.3 con FPM (FastCGI Process Manager)
FROM php:8.3-fpm

# Configura el directorio de trabajo en el contenedor
WORKDIR /var/www

# Instala las dependencias necesarias del sistema
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Instala Composer (Administrador de dependencias de PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia el archivo .env (si existe), el script de configuración, y los archivos de la aplicación al contenedor
COPY . /var/www

# Da permisos al script para que sea ejecutable
RUN chmod +x /var/www/setup.sh

# Ejecuta el script setup.sh durante la construcción del contenedor (opcional)
RUN /var/www/setup.sh

# Instala las dependencias de Laravel
RUN composer install --optimize-autoloader --no-dev

# Da permisos al directorio de almacenamiento y bootstrap/cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expone el puerto que usará el contenedor
EXPOSE 8000

# Comando para ejecutar el script y el servidor PHP
CMD ["/bin/sh", "-c", "/var/www/setup.sh && php artisan serve --host=0.0.0.0 --port=8000"]
