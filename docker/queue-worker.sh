#!/usr/bin/env bash

# Configurar variables de entorno PostgreSQL para SSL sin certificados
# NO configurar PGSSLROOTCERT, PGSSLCERT, PGSSLKEY
# PostgreSQL usará SSL sin buscar certificados del cliente
export PGSSLMODE=${DB_SSLMODE:-require}
unset PGSSLCERT
unset PGSSLKEY
unset PGSSLROOTCERT

# Ejecutar queue worker
exec php /var/www/artisan queue:work --tries=3 --timeout=300
