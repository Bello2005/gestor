#!/usr/bin/env bash
set -e

# Configurar PostgreSQL para SSL sin certificados de cliente
# Neon.tech requiere SSL pero NO requiere certificados del cliente
export PGSSLMODE=require
# Deshabilitar archivos de certificado del cliente
export PGSSLCERT=/dev/null
export PGSSLKEY=/dev/null
export PGSSLROOTCERT=/dev/null

# crear carpetas Laravel que pueden faltar
mkdir -p /var/www/storage /var/www/storage/logs /var/www/storage/framework/{cache,sessions,views} /var/www/bootstrap/cache /var/log/nginx /var/run
chown -R www-data:www-data /var/www || true
chmod -R 775 /var/www/storage /var/www/bootstrap/cache || true

# redirigir logs de nginx a stdout/stderr (útil para Render)
ln -sf /dev/stdout /var/log/nginx/access.log
ln -sf /dev/stderr /var/log/nginx/error.log

# ejecutar el CMD/servicio (supervisord)
exec "$@"