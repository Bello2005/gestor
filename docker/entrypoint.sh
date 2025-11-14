#!/usr/bin/env bash
set -e

# Configurar PostgreSQL para SSL sin certificados de cliente
# Neon.tech requiere SSL pero NO requiere certificados del cliente
# IMPORTANTE: NO configurar PGSSLROOTCERT, PGSSLCERT, PGSSLKEY
# Si se configuran, PostgreSQL intentará leerlos como archivos reales
# Al no configurarlos, PostgreSQL usará SSL sin buscar certificados del cliente
export PGSSLMODE=${DB_SSLMODE:-require}
# NO configurar estas variables - dejar que PostgreSQL use valores por defecto
unset PGSSLCERT
unset PGSSLKEY
unset PGSSLROOTCERT

# Los directorios ya están creados en Dockerfile, solo verificar /var/www/.postgresql
mkdir -p /var/www/.postgresql || true
chmod 755 /var/www/.postgresql || true
chown -R www-data:www-data /var/www/.postgresql || true

# crear carpetas Laravel que pueden faltar
mkdir -p /var/www/storage /var/www/storage/logs /var/www/storage/framework/{cache,sessions,views} /var/www/bootstrap/cache /var/log/nginx /var/run
chown -R www-data:www-data /var/www || true
chmod -R 775 /var/www/storage /var/www/bootstrap/cache || true

# redirigir logs de nginx a stdout/stderr (útil para Render)
ln -sf /dev/stdout /var/log/nginx/access.log
ln -sf /dev/stderr /var/log/nginx/error.log

# ejecutar el CMD/servicio (supervisord)
exec "$@"