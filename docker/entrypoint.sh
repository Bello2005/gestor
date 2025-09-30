#!/usr/bin/env bash
set -e

# crear carpetas Laravel que pueden faltar
mkdir -p /var/www/storage /var/www/storage/logs /var/www/storage/framework/{cache,sessions,views} /var/www/bootstrap/cache /var/log/nginx /var/run
chown -R www-data:www-data /var/www || true
chmod -R 775 /var/www/storage /var/www/bootstrap/cache || true

# si usas plantilla nginx con ${PORT}, procesarla
if [ -f /etc/nginx/nginx.conf.template ]; then
  : "${PORT:=8080}"
  envsubst '$PORT' < /etc/nginx/nginx.conf.template > /etc/nginx/nginx.conf
fi

# redirigir logs de nginx a stdout/stderr (útil para Render)
ln -sf /dev/stdout /var/log/nginx/access.log
ln -sf /dev/stderr /var/log/nginx/error.log

# ejecutar el CMD/servicio (supervisord)
exec "$@"