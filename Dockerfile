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

# CRÍTICO: Crear directorio de certificados PostgreSQL para evitar error de permisos
# PostgreSQL busca certificados en ~/.postgresql/ cuando usa sslmode=require
# Dar permisos de lectura a todos para que www-data pueda acceder
RUN mkdir -p /root/.postgresql /var/www/.postgresql \
 && chmod 755 /root/.postgresql /var/www/.postgresql \
 && chmod o+rx /root/.postgresql \
 && chown -R www-data:www-data /var/www/.postgresql

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

# Copiar directorio public (sin build, que está excluido por .dockerignore)
# Esto asegura que archivos como index.php, favicon, etc. estén presentes
# El .dockerignore excluye public/build, así que no se copia si existe en el repo
COPY public ./public

# Instalar dependencias de Node y compilar assets
# Nota: Necesitamos devDependencies para Vite build
RUN npm ci --no-audit --no-fund || npm install --no-audit --no-fund
RUN npm run build

# Verificar que el manifest se generó correctamente
# Vite 7 puede generar el manifest en .vite/manifest.json, moverlo si es necesario
RUN if [ -f public/build/.vite/manifest.json ]; then \
        mv public/build/.vite/manifest.json public/build/manifest.json && \
        echo "Manifest movido desde .vite/manifest.json"; \
    fi

# Verificar que el manifest existe en la ubicación esperada por Laravel
RUN test -f public/build/manifest.json || (echo "ERROR: Vite manifest not found in public/build/manifest.json!" && ls -la public/build/ && exit 1)

# Verificar que los assets se generaron
RUN test -d public/build/assets || (echo "ERROR: Vite assets not generated!" && exit 1)

# Limpiar node_modules para reducir tamaño de imagen
RUN rm -rf node_modules

# Copiar el resto del proyecto
# IMPORTANTE: .dockerignore excluye public/build, preservando el generado arriba
# Copiamos public de forma selectiva, excluyendo build si existe en el repo
COPY . .

# Asegurar que el directorio build existe y tiene permisos correctos
# Esto preserva el directorio build generado durante el build anterior
RUN mkdir -p public/build && \
    chown -R www-data:www-data public/build storage bootstrap/cache && \
    chmod -R 755 public/build

# configs
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
COPY docker/queue-worker.sh /usr/local/bin/queue-worker.sh
RUN chmod +x /usr/local/bin/entrypoint.sh /usr/local/bin/queue-worker.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

EXPOSE 10000
CMD ["/usr/bin/supervisord"]
