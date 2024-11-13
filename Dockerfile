# Imagen php 3
FROM php:8.3-fpm

WORKDIR /var/www

# dependencias del sistema
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

# Copiar la configuracion de el composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# copiar los archivos de la app
COPY . /var/www

# dependencias de Laravel
RUN composer install --optimize-autoloader --no-dev

# permisos al directorio de almacenamiento y bootstrap/cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Exponer el puerto
EXPOSE 8000
