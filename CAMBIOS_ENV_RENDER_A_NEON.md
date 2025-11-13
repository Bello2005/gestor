# 🔄 CAMBIOS NECESARIOS: Render → Neon

## 📋 Variables que DEBES cambiar

### Base de Datos (OBLIGATORIO)

```env
# ❌ ANTES (Render)
DB_HOST=dpg-d3fc4j3uibrs73dqt160-a.oregon-postgres.render.com
DB_DATABASE=gestor_yq8j
DB_USERNAME=bello
DB_PASSWORD=Hh9SQTJqhUi3v3vVRLPb8sY5h7j4frI9

# ✅ DESPUÉS (Neon)
DB_HOST=ep-sweet-meadow-acu8tl0p-pooler.sa-east-1.aws.neon.tech
DB_DATABASE=neondb
DB_USERNAME=neondb_owner
DB_PASSWORD=npg_3jSRL2fqIoec
```

### Variable NUEVA que DEBES AGREGAR

```env
# ✅ AGREGAR esta línea (requerida para Neon)
DB_SSLMODE=require
```

### Variables que NO cambian (mantener igual)

```env
DB_CONNECTION=pgsql  # ✅ Mantener igual
DB_PORT=5432         # ✅ Mantener igual
```

---

## 📝 Resumen de Cambios

### Variables a CAMBIAR:
1. `DB_HOST` → Cambiar a endpoint de Neon
2. `DB_DATABASE` → Cambiar a `neondb`
3. `DB_USERNAME` → Cambiar a `neondb_owner`
4. `DB_PASSWORD` → Cambiar a contraseña de Neon

### Variables a AGREGAR:
1. `DB_SSLMODE=require` → Nueva, requerida para Neon

### Variables a MANTENER (igual):
- `DB_CONNECTION=pgsql`
- `DB_PORT=5432`
- Todas las demás variables (APP_*, CLOUDINARY_*, MAIL_*, etc.)

---

## 🔧 Comando Rápido para Actualizar

Si quieres actualizar automáticamente tu `.env`:

```bash
# Cambiar variables de base de datos
sed -i 's|DB_HOST=.*|DB_HOST=ep-sweet-meadow-acu8tl0p-pooler.sa-east-1.aws.neon.tech|' .env
sed -i 's|DB_DATABASE=.*|DB_DATABASE=neondb|' .env
sed -i 's|DB_USERNAME=.*|DB_USERNAME=neondb_owner|' .env
sed -i 's|DB_PASSWORD=.*|DB_PASSWORD=npg_3jSRL2fqIoec|' .env

# Agregar DB_SSLMODE si no existe
grep -q "^DB_SSLMODE=" .env || echo "DB_SSLMODE=require" >> .env

# Limpiar cache
php artisan config:clear
```

---

## ✅ Tu .env Final Debería Tener:

```env
# ... otras variables iguales ...

DB_CONNECTION=pgsql
DB_HOST=ep-sweet-meadow-acu8tl0p-pooler.sa-east-1.aws.neon.tech
DB_PORT=5432
DB_DATABASE=neondb
DB_USERNAME=neondb_owner
DB_PASSWORD=npg_3jSRL2fqIoec
DB_SSLMODE=require

# ... resto de variables iguales ...
```

---

## ⚠️ IMPORTANTE

- **DB_SSLMODE=require** es OBLIGATORIO para Neon
- Usa el endpoint **pooler** (`-pooler`) para mejor rendimiento en producción
- Después de cambiar, ejecuta: `php artisan config:clear`


