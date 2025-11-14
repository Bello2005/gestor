#!/usr/bin/env bash

# Configurar variables de entorno PostgreSQL para SSL sin certificados
# Asegurar que las variables estén disponibles para el proceso PHP
export PGSSLMODE=${DB_SSLMODE:-require}
export PGSSLCERT=/dev/null
export PGSSLKEY=/dev/null
export PGSSLROOTCERT=/dev/null

# Asegurar que el directorio de certificados existe (por si acaso)
mkdir -p /root/.postgresql /var/www/.postgresql || true
chmod 755 /root/.postgresql /var/www/.postgresql || true

# Ejecutar queue worker
exec php /var/www/artisan queue:work --tries=3 --timeout=300
