FROM php:8.3-fpm

ENV DEBIAN_FRONTEND=noninteractive

# NO hardcodees credenciales aquí
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV DB_CONNECTION=pgsql
# Las credenciales vendrán de Render

# instalar deps del sistema, Node.js y preparar compilación de extensiones
RUN apt-get update && apt-get install -y --no-install-recommends \
    ca-certificates git curl build-essential pkg-config \
    libpng-dev libonig-dev libxml2-dev libpq-dev libzip-dev zlib1g-dev \
    libjpeg-dev libfreetype6-dev zip unzip nginx supervisor \
    gettext-base \
  && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
  && apt-get install -y --no-install-recommends nodejs \
  && useradd -r nginx \
  \
  # configurar GD (si necesita jpeg/freetype)
  && docker-php-ext-configure gd --with-jpeg --with-freetype \
  \
  # instalar extensiones (zip se instala SIN pasar --with-libzip)
  && docker-php-ext-install -j$(nproc) pdo pdo_mysql pdo_pgsql pgsql mbstring exif pcntl bcmath gd zip \
  \
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

# Copiar configuración de PHP-FPM
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf

# ahora instalar dependencias (composer scripts pueden ejecutar artisan correctamente)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-ansi

# Copiar archivos necesarios para npm build
COPY package.json package-lock.json* ./
COPY vite.config.js ./
COPY tailwind.config.js ./
COPY postcss.config.js ./
COPY resources ./resources

# Instalar dependencias de Node y compilar assets
# Nota: Necesitamos devDependencies para Vite build
RUN npm ci --no-audit --no-fund || npm install --no-audit --no-fund
RUN npm run build

# Limpiar node_modules para reducir tamaño de imagen
RUN rm -rf node_modules

# ahora copiar el resto del proyecto
COPY . .

# Asegurar que el directorio build existe y tiene permisos correctos
RUN mkdir -p public/build && chown -R www-data:www-data public/build storage bootstrap/cache

# configs
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
COPY docker/queue-worker.sh /usr/local/bin/queue-worker.sh
RUN chmod +x /usr/local/bin/entrypoint.sh /usr/local/bin/queue-worker.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

EXPOSE 10000
CMD ["/usr/bin/supervisord"]
