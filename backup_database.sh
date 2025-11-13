#!/bin/bash

# 💾 Script de Backup Automático de Base de Datos
# Proyecto: QUANTUM

set -e

# Colores
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo "╔════════════════════════════════════════════════╗"
echo "║      💾 QUANTUM - Backup de Base de Datos     ║"
echo "╚════════════════════════════════════════════════╝"
echo ""

# Obtener variables del .env
source .env 2>/dev/null || { echo "Error: No se encontró .env"; exit 1; }

# Crear directorio de backups si no existe
BACKUP_DIR="database/backups"
mkdir -p "$BACKUP_DIR"

# Nombre del archivo con timestamp
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="$BACKUP_DIR/quantum_backup_$TIMESTAMP.sql"

echo -e "${YELLOW}📦 Creando backup...${NC}"

# Usar pg_dump para crear backup
PGPASSWORD="$DB_PASSWORD" pg_dump \
    -h "$DB_HOST" \
    -p "$DB_PORT" \
    -U "$DB_USERNAME" \
    -d "$DB_DATABASE" \
    --clean \
    --if-exists \
    --no-owner \
    --no-privileges \
    > "$BACKUP_FILE"

if [ $? -eq 0 ]; then
    # Comprimir backup
    gzip "$BACKUP_FILE"
    BACKUP_FILE="${BACKUP_FILE}.gz"

    # Obtener tamaño
    SIZE=$(du -h "$BACKUP_FILE" | cut -f1)

    echo -e "${GREEN}✓ Backup creado exitosamente!${NC}"
    echo ""
    echo "  📁 Archivo: $BACKUP_FILE"
    echo "  📊 Tamaño: $SIZE"
    echo "  📅 Fecha: $(date)"
    echo ""

    # Limpiar backups antiguos (mantener solo últimos 7 días)
    echo -e "${YELLOW}🧹 Limpiando backups antiguos (>7 días)...${NC}"
    find "$BACKUP_DIR" -name "*.sql.gz" -mtime +7 -delete

    REMAINING=$(ls -1 "$BACKUP_DIR"/*.sql.gz 2>/dev/null | wc -l)
    echo -e "${GREEN}✓ Backups restantes: $REMAINING${NC}"
    echo ""

    # Copiar a ubicación segura (opcional)
    read -p "¿Deseas copiar el backup a otra ubicación? (s/n): " -r
    if [[ $REPLY =~ ^[Ss]$ ]]; then
        read -p "Ingresa la ruta de destino: " DEST_PATH
        cp "$BACKUP_FILE" "$DEST_PATH"
        echo -e "${GREEN}✓ Backup copiado a: $DEST_PATH${NC}"
    fi

else
    echo -e "${RED}✗ Error al crear backup${NC}"
    exit 1
fi

echo ""
echo "╔════════════════════════════════════════════════╗"
echo "║              ✅ BACKUP COMPLETO               ║"
echo "╚════════════════════════════════════════════════╝"
echo ""
echo "Para restaurar este backup:"
echo "  gunzip -c $BACKUP_FILE | psql -h HOST -U USER -d DATABASE"
echo ""
