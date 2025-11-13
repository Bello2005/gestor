# 🔄 GUÍA DE RECUPERACIÓN DE BASE DE DATOS QUANTUM

## SITUACIÓN ACTUAL

- ✅ Tienes backup: `database/uniclaretiana_proyectos1.sql` (MySQL)
- ❌ Base de datos Render.com expiró (periodo gratuito)
- 🎯 Objetivo: Migrar a PostgreSQL gratuito permanente

---

## SOLUCIÓN RÁPIDA: Supabase (GRATIS PERMANENTE)

### PASO 1: Crear Cuenta y Proyecto en Supabase

1. Ve a: https://supabase.com/dashboard/sign-in
2. Registrate con GitHub/Google (gratuito)
3. Crea un nuevo proyecto:
   - **Name:** quantum-db
   - **Database Password:** [Genera una contraseña fuerte]
   - **Region:** South America (sao-paulo) - Más cercano a Colombia
   - **Pricing Plan:** FREE

4. Espera 2-3 minutos mientras se crea el proyecto

### PASO 2: Obtener Credenciales

Una vez creado el proyecto:

1. Ve a: **Settings** → **Database**
2. Copia las credenciales en la sección "Connection string":

```
Host: db.xxxxxxxxxxxxx.supabase.co
Database: postgres
Port: 5432
User: postgres
Password: [tu contraseña del paso 1]
```

3. También copia la **Connection Pooling** URI (importante para Laravel):
```
postgresql://postgres.xxxxx:[PASSWORD]@aws-0-sa-east-1.pooler.supabase.com:6543/postgres
```

### PASO 3: Actualizar .env

Abre tu archivo `.env` y actualiza estas líneas:

```env
DB_CONNECTION=pgsql
DB_HOST=db.xxxxxxxxxxxxx.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=tu_contraseña_aqui

# Para Connection Pooling (recomendado para producción)
DATABASE_URL=postgresql://postgres.xxxxx:[PASSWORD]@aws-0-sa-east-1.pooler.supabase.com:6543/postgres
```

### PASO 4: Ejecutar Migraciones

```bash
# 1. Limpiar cache
php artisan config:clear
php artisan cache:clear

# 2. Probar conexión
php artisan db:show

# 3. Ejecutar migraciones (crea todas las tablas)
php artisan migrate:fresh

# Si hay error, intenta:
php artisan migrate:fresh --force
```

### PASO 5: Crear Usuario Administrador

```bash
# Ejecutar en terminal PHP
php artisan tinker

# Dentro de tinker, ejecutar:
$user = new App\Models\User();
$user->name = 'Admin';
$user->email = 'admin@quantum.com';
$user->password = bcrypt('password123');
$user->is_temporary_password = true;
$user->save();

# Asignar rol admin
$adminRole = App\Models\Role::where('name', 'admin')->first();
if (!$adminRole) {
    $adminRole = App\Models\Role::create(['name' => 'admin', 'description' => 'Administrador']);
}
$user->roles()->attach($adminRole->id);

exit
```

### PASO 6: Importar Datos Antiguos (Opcional)

Si necesitas los datos del backup antiguo, necesitarás convertirlos manualmente.

**Opción A: Importar datos manualmente**
- El backup es pequeño (14KB), probablemente no tenías muchos datos
- Puedes recrear los proyectos manualmente desde el dashboard

**Opción B: Conversión MySQL → PostgreSQL**
```bash
# Instalar herramienta de conversión
npm install -g sql-translator

# Convertir archivo (requiere edición manual)
sql-translator database/uniclaretiana_proyectos1.sql > database/postgres_backup.sql

# Luego ejecutar en PostgreSQL
psql -h db.xxxxx.supabase.co -U postgres -d postgres -f database/postgres_backup.sql
```

---

## ALTERNATIVA: Neon.tech (También Gratis)

Si prefieres otra opción:

1. Ve a: https://neon.tech
2. Sign up (GitHub/Google)
3. Create new project
4. Copia credenciales similares a Supabase

**Ventaja:** Serverless, escala a 0 cuando no se usa
**Desventaja:** Menos features que Supabase

---

## PREVENCIÓN: Backups Automáticos

Para evitar perder datos en el futuro, configura backups:

### 1. Backup Manual Rápido

```bash
# Crear script de backup
cat > backup_db.sh << 'EOF'
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
php artisan db:backup --filename="backup_$DATE.sql"
echo "Backup creado: backup_$DATE.sql"
EOF

chmod +x backup_db.sh
```

### 2. Backup Automático con Cron (Linux)

```bash
# Editar crontab
crontab -e

# Agregar línea (backup diario a las 2 AM)
0 2 * * * cd /home/deiner-bello/Documents/Projects/quantum && php artisan backup:run
```

### 3. Backup a GitHub

```bash
# Agregar backup al repo (NO subir credenciales)
git add database/*.sql
git commit -m "chore: Backup de base de datos"
git push
```

---

## VERIFICACIÓN FINAL

Después de configurar todo:

```bash
# 1. Verificar conexión
php artisan db:show

# 2. Ver tablas creadas
php artisan db:table users

# 3. Iniciar servidor
php artisan serve

# 4. Hacer login
# Usuario: admin@quantum.com
# Password: password123
```

---

## SOPORTE

Si tienes problemas:
1. Revisa los logs: `storage/logs/laravel.log`
2. Verifica credenciales en Supabase Dashboard
3. Asegúrate que tu IP no esté bloqueada (Supabase acepta todas por defecto)

---

## RESUMEN DE COSTOS

| Servicio | Plan | Costo | Límite Tiempo |
|----------|------|-------|---------------|
| **Supabase** | Free | $0 | ♾️ Ilimitado |
| **Neon.tech** | Free | $0 | ♾️ Ilimitado |
| Render.com | Free | $0 | ❌ 90 días |
| Railway | Free | $0 | ❌ Límite de $5 crédito |

**RECOMENDACIÓN:** Usa Supabase, es el más estable y generoso.

---

¡Tu base de datos estará lista en menos de 10 minutos! 🚀
