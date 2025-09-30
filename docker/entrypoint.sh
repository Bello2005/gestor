#!/usr/bin/env bash
set -e

# asegurarse de que las carpetas necesarias existan y tengan permisos correctos
mkdir -p /var/www/storage /var/www/storage/logs /var/www/storage/framework/{cache,sessions,views} /var/www/bootstrap/cache /var/log/nginx /var/run
chown -R www-data:www-data /var/www || true
chmod -R 775 /var/www/storage /var/www/bootstrap/cache || true

# link logs de supervisor/nginx/php-fpm a stdout/stderr (opcional y útil en Render)
ln -sf /dev/stdout /var/log/nginx/access.log
ln -sf /dev/stderr /var/log/nginx/error.log

# ejecutar el CMD original
exec "$@"