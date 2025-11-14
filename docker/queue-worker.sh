#!/usr/bin/env bash

# Configurar variables de entorno PostgreSQL para SSL sin certificados
export PGSSLMODE=require
export PGSSLCERT=/dev/null
export PGSSLKEY=/dev/null
export PGSSLROOTCERT=/dev/null

# Ejecutar queue worker
exec php /var/www/artisan queue:work --tries=3 --timeout=300
