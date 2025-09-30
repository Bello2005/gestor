#!/usr/bin/env bash
set -e

echo "🚀 Starting build process..."

# Crear archivo .env si no existe
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Generar application key
php artisan key:generate --force

# Limpiar cachés
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Optimizar
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Migraciones
php artisan migrate --force

# Storage link
php artisan storage:link

echo "✅ Build completed!"
