# 🚀 INICIO RÁPIDO - RECUPERACIÓN DE BASE DE DATOS

## ⚡ SOLUCIÓN EN 3 PASOS (10 MINUTOS)

### 📋 ANTES DE EMPEZAR

Tu situación:
- ✅ Tienes un backup pequeño (14KB) de MySQL
- ❌ Render.com expiró (periodo gratuito terminó)
- 🎯 Necesitas una nueva BD PostgreSQL GRATIS

---

## PASO 1: CREAR NUEVA BASE DE DATOS (5 min)

### Opción A: Supabase (RECOMENDADA) ⭐

```
1. Abre: https://supabase.com/dashboard/sign-in
2. Sign up con GitHub o Google (gratis)
3. Click en "New Project"

   📝 Configuración:
   - Name: quantum-db
   - Password: [genera una contraseña fuerte]
   - Region: South America (sao-paulo)
   - Plan: FREE ✅

4. Espera 2-3 minutos

5. Ve a Settings → Database → Connection String

   📋 Copia estas credenciales:
   - Host: db.xxxxxxxxxxxxx.supabase.co
   - Database: postgres
   - Port: 5432
   - User: postgres
   - Password: [tu contraseña del paso 3]
```

**¿Por qué Supabase?**
- ✅ Gratis PARA SIEMPRE (sin período de prueba)
- ✅ 500 MB almacenamiento (más que suficiente)
- ✅ No requiere tarjeta de crédito
- ✅ Backups automáticos diarios

---

## PASO 2: CONFIGURAR PROYECTO (2 min)

### Opción Automática (Recomendada):

```bash
cd /home/deiner-bello/Documents/Projects/quantum

# Ejecutar script automático
./setup_new_db.sh
```

El script te pedirá las credenciales de Supabase y configurará todo.

### Opción Manual:

```bash
# 1. Editar .env manualmente
nano .env

# 2. Actualizar estas líneas con tus credenciales de Supabase:
DB_HOST=db.xxxxxxxxxxxxx.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=tu_contraseña_aqui

# 3. Limpiar cache
php artisan config:clear
php artisan cache:clear

# 4. Probar conexión
php artisan db:show
```

---

## PASO 3: CREAR ESTRUCTURA Y USUARIO (3 min)

```bash
# 1. Ejecutar migraciones (crea todas las tablas)
php artisan migrate:fresh

# 2. Crear usuario admin
php artisan tinker

# 3. Dentro de tinker, copiar y pegar:
$user = App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@quantum.com',
    'password' => bcrypt('admin123'),
    'is_temporary_password' => true
]);

$role = App\Models\Role::firstOrCreate(
    ['name' => 'admin'],
    ['description' => 'Administrador']
);

$user->roles()->attach($role->id);
echo "✓ Usuario admin creado\n";
exit

# 4. Iniciar servidor
php artisan serve

# 5. Abrir navegador
# http://localhost:8000
# Email: admin@quantum.com
# Password: admin123
```

---

## 🎉 ¡LISTO!

Tu base de datos está funcionando. Ahora puedes:

1. ✅ Crear nuevos proyectos
2. ✅ Gestionar usuarios
3. ✅ Subir archivos a Cloudinary
4. ✅ Ver auditoría completa

---

## 💾 BACKUPS FUTUROS

### Backup Manual Rápido:

```bash
./backup_database.sh
```

Esto creará un archivo comprimido en `database/backups/`

### Backup Automático (Opcional):

```bash
# Agregar a cron (backup diario a las 2 AM)
crontab -e

# Agregar esta línea:
0 2 * * * cd /home/deiner-bello/Documents/Projects/quantum && ./backup_database.sh
```

---

## ⚠️ SOBRE TU BACKUP ANTIGUO

Tu archivo `database/uniclaretiana_proyectos1.sql`:
- 📦 Tamaño: 14KB (muy pequeño)
- 📅 Fecha: 18 septiembre 2025
- 🔄 Formato: MySQL (no compatible directamente)

**Recomendación:**
- Si tenías pocos datos, mejor empezar de cero
- Si necesitas los datos, puedo ayudarte a convertirlos

---

## 🆘 PROBLEMAS COMUNES

### Error: "Connection refused"
```bash
# Verifica credenciales en .env
cat .env | grep DB_

# Prueba conexión con psql
psql -h tu_host -U postgres -d postgres
```

### Error: "No such table"
```bash
# Ejecuta migraciones
php artisan migrate:fresh
```

### Error: "Class not found"
```bash
# Regenera autoload
composer dump-autoload
php artisan clear-compiled
```

---

## 📊 COMPARACIÓN DE SERVICIOS GRATUITOS

| Servicio | Límite Tiempo | Storage | Pros |
|----------|---------------|---------|------|
| **Supabase** | ♾️ Ilimitado | 500 MB | ⭐ Mejor opción |
| **Neon.tech** | ♾️ Ilimitado | 500 MB | ⚡ Serverless |
| Render.com | ❌ 90 días | 1 GB | Expira |
| Railway | ⚠️ $5 crédito | 512 MB | Se agota |
| ElephantSQL | ♾️ Ilimitado | 20 MB | Muy limitado |

---

## 🔗 RECURSOS

- 📚 Guía completa: `RECUPERACION_DB.md`
- 🤖 Script automático: `./setup_new_db.sh`
- 💾 Script backup: `./backup_database.sh`
- 📖 Docs Supabase: https://supabase.com/docs

---

## 💡 PRÓXIMOS PASOS

Después de recuperar tu BD:

1. **Cambiar contraseña del admin** (primera vez que entres)
2. **Configurar backups automáticos** (cron job)
3. **Agregar usuarios** del equipo
4. **Configurar Cloudinary** (si no está funcionando)
5. **Probar envío de emails** (RESEND o SMTP)

---

## 🎯 RESUMEN EJECUTIVO

```
┌─────────────────────────────────────────┐
│  TIEMPO TOTAL: ~10 minutos              │
│  COSTO: $0 (GRATIS permanente)          │
│  DIFICULTAD: ⭐⭐☆☆☆ (Fácil)            │
│  RESULTADO: BD funcional en la nube     │
└─────────────────────────────────────────┘
```

**¿Necesitas ayuda?** Revisa los logs en `storage/logs/laravel.log`

---

✅ **Tu sistema estará funcionando en menos de 10 minutos** 🚀
