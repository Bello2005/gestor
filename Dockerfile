FROM php:8.3-fpm

ENV DEBIAN_FRONTEND=noninteractive

# instalar deps del sistema, Node.js y preparar compilación de extensiones
RUN apt-get update && apt-get install -y --no-install-recommends \
    ca-certificates git curl build-essential pkg-config \
    libpng-dev libonig-dev libxml2-dev libpq-dev libzip-dev zlib1g-dev \
    libjpeg-dev libfreetype6-dev zip unzip nginx supervisor \
  && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
  && apt-get install -y --no-install-recommends nodejs \
  && useradd -r nginx \
  \
  # configurar GD (si necesita jpeg/freetype)
  && docker-php-ext-configure gd --with-jpeg --with-freetype \
  \
  # instalar extensiones (zip se instala SIN pasar --with-libzip)
  && docker-php-ext-install -j$(nproc) pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd zip \
  \
  && apt-get purge -y --auto-remove build-essential pkg-config \
  && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www


# optimización: copiar composer files primero para cache
COPY composer.lock composer.json ./

# copiar archivos mínimos necesarios para que artisan funcione durante composer scripts
COPY artisan ./
COPY bootstrap ./bootstrap
COPY app ./app
COPY config ./config
COPY routes ./routes

# crear carpetas que artisan espera y dar permisos
RUN mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views storage/app/public bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache

# ahora instalar dependencias (composer scripts pueden ejecutar artisan correctamente)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-ansi

# ahora copiar el resto del proyecto
COPY . .

RUN npm install
RUN npm run build

# configs
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# permisos
RUN chown -R www-data:www-data /var/www \
 && chmod -R 755 /var/www/storage /var/www/bootstrap/cache \
 && mkdir -p /var/log/nginx /var/run \
 && chown -R nginx:nginx /var/log/nginx /var/run

EXPOSE 80
CMD ["/usr/bin/supervisord"]
