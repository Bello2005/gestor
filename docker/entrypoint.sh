#!/usr/bin/env bash
set -e

# Configurar variables de entorno de PostgreSQL para evitar error de certificado
# Neon requiere SSL pero no necesita certificado del cliente
export PGSSLMODE=${DB_SSLMODE:-prefer}
# Deshabilitar variables de certificado del cliente explícitamente
export PGSSLCERT=""
export PGSSLKEY=""
export PGSSLROOTCERT=""

# Crear directorio de certificados PostgreSQL para root y dar permisos
# Esto evita el error "Permission denied" cuando PostgreSQL intenta leer el certificado
mkdir -p /root/.postgresql
chmod 755 /root/.postgresql || true

# También crear para www-data (usuario que ejecuta queue-worker)
mkdir -p /var/www/.postgresql
chown -R www-data:www-data /var/www/.postgresql || true
chmod 755 /var/www/.postgresql || true

# crear carpetas Laravel que pueden faltar
mkdir -p /var/www/storage /var/www/storage/logs /var/www/storage/framework/{cache,sessions,views} /var/www/bootstrap/cache /var/log/nginx /var/run
chown -R www-data:www-data /var/www || true
chmod -R 775 /var/www/storage /var/www/bootstrap/cache || true

# redirigir logs de nginx a stdout/stderr (útil para Render)
ln -sf /dev/stdout /var/log/nginx/access.log
ln -sf /dev/stderr /var/log/nginx/error.log

# ejecutar el CMD/servicio (supervisord)
exec "$@"