#!/bin/bash

# 🚀 Script de Migración a Neon PostgreSQL
# Este script automatiza la configuración de Laravel para usar Neon

set -e  # Salir si hay algún error

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}╔════════════════════════════════════════════════════════╗${NC}"
echo -e "${BLUE}║     MIGRACIÓN A NEON POSTGRESQL - QUANTUM            ║${NC}"
echo -e "${BLUE}╚════════════════════════════════════════════════════════╝${NC}"
echo ""

# Verificar que estamos en el directorio correcto
if [ ! -f "artisan" ]; then
    echo -e "${RED}❌ Error: No se encontró el archivo artisan${NC}"
    echo -e "${YELLOW}   Asegúrate de ejecutar este script desde la raíz del proyecto Laravel${NC}"
    exit 1
fi

# Verificar que existe .env
if [ ! -f ".env" ]; then
    echo -e "${YELLOW}⚠️  No se encontró .env, creando desde .env.example...${NC}"
    if [ -f ".env.example" ]; then
        cp .env.example .env
        echo -e "${GREEN}✅ Archivo .env creado${NC}"
    else
        echo -e "${RED}❌ Error: No se encontró .env.example${NC}"
        exit 1
    fi
fi

echo -e "${BLUE}📋 Paso 1: Configuración de credenciales de Neon${NC}"
echo ""

# Solicitar credenciales
read -p "Host de Neon (ej: ep-xxxx-pooler.us-east-2.aws.neon.tech): " DB_HOST
read -p "Puerto (default: 5432): " DB_PORT
DB_PORT=${DB_PORT:-5432}
read -p "Nombre de la base de datos: " DB_DATABASE
read -p "Usuario: " DB_USERNAME
read -sp "Contraseña: " DB_PASSWORD
echo ""

# Validar que se ingresaron valores
if [ -z "$DB_HOST" ] || [ -z "$DB_DATABASE" ] || [ -z "$DB_USERNAME" ] || [ -z "$DB_PASSWORD" ]; then
    echo -e "${RED}❌ Error: Todos los campos son requeridos${NC}"
    exit 1
fi

echo ""
echo -e "${BLUE}📝 Paso 2: Actualizando archivo .env${NC}"

# Backup del .env actual
if [ -f ".env" ]; then
    cp .env .env.backup.$(date +%Y%m%d_%H%M%S)
    echo -e "${GREEN}✅ Backup de .env creado${NC}"
fi

# Actualizar .env usando sed
# Nota: En macOS, usar sed -i '' en lugar de sed -i

if [[ "$OSTYPE" == "darwin"* ]]; then
    # macOS
    sed -i '' "s/^DB_CONNECTION=.*/DB_CONNECTION=pgsql/" .env
    sed -i '' "s/^DB_HOST=.*/DB_HOST=$DB_HOST/" .env
    sed -i '' "s/^DB_PORT=.*/DB_PORT=$DB_PORT/" .env
    sed -i '' "s/^DB_DATABASE=.*/DB_DATABASE=$DB_DATABASE/" .env
    sed -i '' "s/^DB_USERNAME=.*/DB_USERNAME=$DB_USERNAME/" .env
    sed -i '' "s/^DB_PASSWORD=.*/DB_PASSWORD=$DB_PASSWORD/" .env
    # Agregar DB_SSLMODE si no existe
    if ! grep -q "^DB_SSLMODE=" .env; then
        echo "DB_SSLMODE=require" >> .env
    else
        sed -i '' "s/^DB_SSLMODE=.*/DB_SSLMODE=require/" .env
    fi
else
    # Linux
    sed -i "s/^DB_CONNECTION=.*/DB_CONNECTION=pgsql/" .env
    sed -i "s/^DB_HOST=.*/DB_HOST=$DB_HOST/" .env
    sed -i "s/^DB_PORT=.*/DB_PORT=$DB_PORT/" .env
    sed -i "s/^DB_DATABASE=.*/DB_DATABASE=$DB_DATABASE/" .env
    sed -i "s/^DB_USERNAME=.*/DB_USERNAME=$DB_USERNAME/" .env
    sed -i "s/^DB_PASSWORD=.*/DB_PASSWORD=$DB_PASSWORD/" .env
    # Agregar DB_SSLMODE si no existe
    if ! grep -q "^DB_SSLMODE=" .env; then
        echo "DB_SSLMODE=require" >> .env
    else
        sed -i "s/^DB_SSLMODE=.*/DB_SSLMODE=require/" .env
    fi
fi

echo -e "${GREEN}✅ Archivo .env actualizado${NC}"

echo ""
echo -e "${BLUE}🔍 Paso 3: Verificando extensión PostgreSQL de PHP${NC}"

# Verificar extensión pgsql
if php -m | grep -q pgsql; then
    echo -e "${GREEN}✅ Extensión pgsql está instalada${NC}"
else
    echo -e "${YELLOW}⚠️  Extensión pgsql no encontrada${NC}"
    echo -e "${YELLOW}   Por favor instala php-pgsql:${NC}"
    echo -e "${YELLOW}   sudo apt-get install php-pgsql${NC}"
    echo ""
    read -p "¿Continuar de todos modos? (y/n): " CONTINUE
    if [ "$CONTINUE" != "y" ] && [ "$CONTINUE" != "Y" ]; then
        exit 1
    fi
fi

echo ""
echo -e "${BLUE}🧹 Paso 4: Limpiando cache de Laravel${NC}"

php artisan config:clear
php artisan cache:clear
echo -e "${GREEN}✅ Cache limpiado${NC}"

echo ""
echo -e "${BLUE}🔌 Paso 5: Probando conexión a Neon${NC}"

# Probar conexión
if php artisan db:show > /dev/null 2>&1; then
    echo -e "${GREEN}✅ Conexión exitosa a Neon!${NC}"
    php artisan db:show
else
    echo -e "${RED}❌ Error al conectar a Neon${NC}"
    echo -e "${YELLOW}   Por favor verifica las credenciales en .env${NC}"
    exit 1
fi

echo ""
echo -e "${BLUE}📊 Paso 6: Estado de migraciones${NC}"

# Mostrar estado de migraciones
php artisan migrate:status

echo ""
read -p "¿Deseas ejecutar las migraciones ahora? (y/n): " RUN_MIGRATIONS

if [ "$RUN_MIGRATIONS" = "y" ] || [ "$RUN_MIGRATIONS" = "Y" ]; then
    echo ""
    echo -e "${BLUE}🚀 Ejecutando migraciones...${NC}"
    
    read -p "¿Ejecutar migrate:fresh? Esto eliminará todas las tablas existentes (y/n): " FRESH
    
    if [ "$FRESH" = "y" ] || [ "$FRESH" = "Y" ]; then
        php artisan migrate:fresh
        echo -e "${GREEN}✅ Migraciones ejecutadas desde cero${NC}"
    else
        php artisan migrate
        echo -e "${GREEN}✅ Migraciones ejecutadas${NC}"
    fi
else
    echo -e "${YELLOW}⚠️  Migraciones no ejecutadas. Ejecuta manualmente:${NC}"
    echo -e "${YELLOW}   php artisan migrate${NC}"
fi

echo ""
echo -e "${GREEN}╔════════════════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║              ✅ MIGRACIÓN COMPLETADA                  ║${NC}"
echo -e "${GREEN}╚════════════════════════════════════════════════════════╝${NC}"
echo ""
echo -e "${BLUE}📝 Próximos pasos:${NC}"
echo -e "   1. Crea un usuario administrador: ${YELLOW}php artisan tinker${NC}"
echo -e "   2. Prueba tu aplicación: ${YELLOW}php artisan serve${NC}"
echo -e "   3. Revisa la guía completa: ${YELLOW}MIGRACION_NEON.md${NC}"
echo ""
echo -e "${GREEN}¡Tu aplicación ahora está usando Neon PostgreSQL! 🎉${NC}"


