#!/usr/bin/env bash
set -e

echo "🚀 Starting build process..."

# Asegurarse que el script es ejecutable
chmod +x ./build.sh

# Crear archivo .env si no existe
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Generar application key si no existe
php artisan key:generate --force

# Crear directorios necesarios si no existen
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Asignar permisos correctos
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Optimizar la aplicación
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones si la base de datos está disponible
if [[ -n "${DB_HOST}" ]]; then
    php artisan migrate --force
fi

# Limpiar y regenerar assets
npm ci
npm run build

# Crear enlace simbólico del storage
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Migraciones
php artisan migrate --force

# Storage link
php artisan storage:link

echo "✅ Build completed!"
