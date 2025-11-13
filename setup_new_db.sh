#!/bin/bash

# 🔄 Script de Configuración de Nueva Base de Datos
# Proyecto: QUANTUM

set -e

echo "╔════════════════════════════════════════════════╗"
echo "║   🔄 QUANTUM - Setup Nueva Base de Datos      ║"
echo "╚════════════════════════════════════════════════╝"
echo ""

# Colores
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Función para preguntar
ask() {
    echo -e "${YELLOW}$1${NC}"
    read -r response
    echo "$response"
}

# Función de éxito
success() {
    echo -e "${GREEN}✓ $1${NC}"
}

# Función de error
error() {
    echo -e "${RED}✗ $1${NC}"
}

echo "Este script te ayudará a configurar una nueva base de datos PostgreSQL."
echo ""

# Preguntar credenciales
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "📝 INGRESA LAS CREDENCIALES DE TU NUEVA BD"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

DB_HOST=$(ask "🌐 DB_HOST (ej: db.xxxxx.supabase.co): ")
DB_PORT=$(ask "🔌 DB_PORT (default: 5432): ")
DB_PORT=${DB_PORT:-5432}
DB_DATABASE=$(ask "🗄️  DB_DATABASE (ej: postgres): ")
DB_USERNAME=$(ask "👤 DB_USERNAME (ej: postgres): ")
DB_PASSWORD=$(ask "🔐 DB_PASSWORD: ")

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "🔧 CONFIGURANDO ARCHIVO .env"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

# Backup del .env actual
if [ -f .env ]; then
    cp .env .env.backup.$(date +%Y%m%d_%H%M%S)
    success "Backup de .env creado"
fi

# Actualizar .env
sed -i "s|^DB_HOST=.*|DB_HOST=$DB_HOST|" .env
sed -i "s|^DB_PORT=.*|DB_PORT=$DB_PORT|" .env
sed -i "s|^DB_DATABASE=.*|DB_DATABASE=$DB_DATABASE|" .env
sed -i "s|^DB_USERNAME=.*|DB_USERNAME=$DB_USERNAME|" .env
sed -i "s|^DB_PASSWORD=.*|DB_PASSWORD=$DB_PASSWORD|" .env

success "Archivo .env actualizado"
echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "🧹 LIMPIANDO CACHE"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

success "Cache limpiado"
echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "🔌 PROBANDO CONEXIÓN A LA BASE DE DATOS"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

if php artisan db:show 2>/dev/null; then
    success "Conexión exitosa a la base de datos!"
    echo ""
else
    error "No se pudo conectar a la base de datos"
    error "Verifica las credenciales y intenta nuevamente"
    exit 1
fi

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "🗃️  EJECUTANDO MIGRACIONES"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

read -p "¿Deseas ejecutar las migraciones? (s/n): " -r
if [[ $REPLY =~ ^[Ss]$ ]]; then
    php artisan migrate:fresh --force
    success "Migraciones ejecutadas exitosamente"
    echo ""
else
    echo "Saltando migraciones..."
    echo ""
fi

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "👤 CREAR USUARIO ADMINISTRADOR"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

read -p "¿Deseas crear un usuario administrador? (s/n): " -r
if [[ $REPLY =~ ^[Ss]$ ]]; then
    ADMIN_NAME=$(ask "👤 Nombre del admin: ")
    ADMIN_EMAIL=$(ask "📧 Email del admin: ")
    ADMIN_PASSWORD=$(ask "🔐 Password del admin: ")

    # Crear usuario admin con PHP
    php artisan tinker --execute="
        \$user = App\Models\User::create([
            'name' => '$ADMIN_NAME',
            'email' => '$ADMIN_EMAIL',
            'password' => bcrypt('$ADMIN_PASSWORD'),
            'is_temporary_password' => true
        ]);

        \$role = App\Models\Role::firstOrCreate(
            ['name' => 'admin'],
            ['description' => 'Administrador del sistema']
        );

        \$user->roles()->attach(\$role->id);

        echo 'Usuario admin creado exitosamente';
    "

    success "Usuario administrador creado"
    echo ""
    echo "Credenciales:"
    echo "  Email: $ADMIN_EMAIL"
    echo "  Password: $ADMIN_PASSWORD"
    echo ""
fi

echo "╔════════════════════════════════════════════════╗"
echo "║            ✅ CONFIGURACIÓN COMPLETA          ║"
echo "╚════════════════════════════════════════════════╝"
echo ""
echo "Próximos pasos:"
echo "  1. Inicia el servidor: php artisan serve"
echo "  2. Accede a: http://localhost:8000"
echo "  3. Haz login con las credenciales creadas"
echo ""
echo "Para crear backup de la BD:"
echo "  php artisan backup:run"
echo ""
success "¡Todo listo! 🚀"
