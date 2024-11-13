# Usa una imagen de PHP con las extensiones necesarias para Laravel
FROM php:8.3-fpm

# Instala dependencias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Configura el directorio de trabajo
WORKDIR /var/www

# Copia los archivos de Laravel
COPY . .

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --optimize-autoloader --no-dev

# Permisos para `storage` y `bootstrap/cache`
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Copia y establece `entrypoint.sh`
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Expone el puerto para PHP-FPM
EXPOSE 8000

# Usa el `entrypoint.sh` como comando inicial
ENTRYPOINT ["/entrypoint.sh"]
