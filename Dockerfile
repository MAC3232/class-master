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

# Copia el archivo .env (si existe) y los archivos de la aplicación al contenedor
COPY . /var/www

# Instala las dependencias de Laravel
RUN composer install --optimize-autoloader --no-dev

# Da permisos al directorio de almacenamiento y bootstrap/cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache


# Copia y establece `entrypoint.sh`
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Expone el puerto que usará el contenedor
EXPOSE 8000

# Comando para ejecutar el servidor PHP
ENTRYPOINT ["/entrypoint.sh"]
