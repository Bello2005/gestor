# 🚀 GUÍA DE MIGRACIÓN A NEON POSTGRESQL

## 📋 INTRODUCCIÓN

Esta guía te ayudará a migrar tu base de datos de SQLite/MySQL a **Neon PostgreSQL**, un servicio de PostgreSQL serverless moderno y gratuito.

### ¿Por qué Neon?
- ✅ **Gratis para siempre** (hasta 3 GB de almacenamiento)
- ✅ **Serverless** - Escala automáticamente
- ✅ **Connection Pooling** incluido
- ✅ **Backups automáticos**
- ✅ **Sin tarjeta de crédito** requerida
- ✅ **Rápido y confiable**

---

## PASO 1: CREAR CUENTA Y PROYECTO EN NEON (5 minutos)

### 1.1 Registrarse en Neon

1. Ve a: https://neon.tech/
2. Haz clic en **"Sign Up"** o **"Get Started"**
3. Regístrate con GitHub, Google o email (gratis)

### 1.2 Crear un Nuevo Proyecto

1. Una vez dentro del dashboard, haz clic en **"New Project"**
2. Completa el formulario:
   - **Project name:** `quantum-db` (o el nombre que prefieras)
   - **Region:** Elige la región más cercana a tu ubicación
     - Para Colombia: `US East (Ohio)` o `US West (Oregon)`
   - **PostgreSQL version:** `16` (recomendado) o `15`
   - **Compute size:** `Free` (suficiente para desarrollo)

3. Haz clic en **"Create Project"**
4. Espera 1-2 minutos mientras se crea el proyecto

### 1.3 Obtener Credenciales de Conexión

Una vez creado el proyecto:

1. En el dashboard de Neon, ve a la sección **"Connection Details"**
2. Verás dos opciones de conexión:
   - **Direct connection** (para desarrollo)
   - **Pooled connection** (recomendado para producción)

3. **Copia la Connection String** que se ve así:
   ```
   postgres://[user]:[password]@[hostname]/[database]?sslmode=require
   ```

4. También anota estos valores por separado:
   - **Host:** `ep-xxxx-xxxxx.us-east-2.aws.neon.tech`
   - **Database:** `neondb` (o el nombre que hayas elegido)
   - **User:** `neondb_owner` (o similar)
   - **Password:** [La contraseña generada]
   - **Port:** `5432`

---

## PASO 2: CONFIGURAR LARAVEL PARA NEON (2 minutos)

### 2.1 Actualizar archivo .env

Abre tu archivo `.env` y actualiza estas variables:

```env
# Cambiar de sqlite a pgsql
DB_CONNECTION=pgsql

# Credenciales de Neon (usar Connection Pooling para mejor rendimiento)
DB_HOST=ep-xxxx-xxxxx-pooler.us-east-2.aws.neon.tech
DB_PORT=5432
DB_DATABASE=neondb
DB_USERNAME=neondb_owner
DB_PASSWORD=tu_contraseña_aqui

# SSL requerido para Neon
DB_SSLMODE=require

# Opcional: Usar DATABASE_URL completo (alternativa)
# DATABASE_URL=postgres://neondb_owner:[password]@ep-xxxx-xxxxx-pooler.us-east-2.aws.neon.tech:5432/neondb?sslmode=require
```

**⚠️ IMPORTANTE:**
- Usa el **pooler endpoint** (termina en `-pooler`) para mejor rendimiento
- El **direct endpoint** (sin `-pooler`) es para conexiones directas
- **Siempre** usa `sslmode=require` para Neon

### 2.2 Verificar Configuración de Base de Datos

El archivo `config/database.php` ya está configurado correctamente para PostgreSQL. Solo asegúrate de que la configuración de SSL esté activa:

```php
'pgsql' => [
    'driver' => 'pgsql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '5432'),
    'database' => env('DB_DATABASE', 'laravel'),
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => 'utf8',
    'prefix' => '',
    'prefix_indexes' => true,
    'search_path' => 'public',
    'sslmode' => env('DB_SSLMODE', 'require'), // ✅ Ya configurado
    'options' => [
        PDO::ATTR_PERSISTENT => false,
    ],
],
```

---

## PASO 3: INSTALAR DEPENDENCIAS (si es necesario)

Verifica que tengas la extensión de PostgreSQL para PHP:

```bash
# Verificar si está instalado
php -m | grep pgsql

# Si no está instalado (Ubuntu/Debian):
sudo apt-get update
sudo apt-get install php-pgsql

# Reiniciar servidor PHP si es necesario
sudo systemctl restart php8.2-fpm  # Ajusta la versión según tu PHP
```

---

## PASO 4: PROBAR CONEXIÓN (1 minuto)

```bash
# Limpiar cache de configuración
php artisan config:clear
php artisan cache:clear

# Probar conexión
php artisan db:show

# Si todo está bien, deberías ver información de la base de datos Neon
```

---

## PASO 5: EJECUTAR MIGRACIONES (2 minutos)

### 5.1 Ejecutar Migraciones desde Cero

Si es una base de datos nueva o quieres empezar limpio:

```bash
# Ejecutar todas las migraciones
php artisan migrate:fresh

# O si prefieres mantener datos existentes (si los hay):
php artisan migrate
```

### 5.2 Verificar Migraciones

```bash
# Ver estado de migraciones
php artisan migrate:status
```

---

## PASO 6: MIGRAR DATOS EXISTENTES (si aplica)

Si tienes datos en SQLite o MySQL que necesitas migrar:

### Opción A: Migración Manual (Recomendada para datos pequeños)

1. **Exportar datos de SQLite/MySQL:**
   ```bash
   # Para SQLite
   php artisan db:export database_backup.sql
   
   # O usar herramienta de línea de comandos
   sqlite3 database/database.sqlite .dump > backup.sql
   ```

2. **Convertir y adaptar el SQL** para PostgreSQL (puede requerir ajustes manuales)

3. **Importar a Neon:**
   - Usa el SQL Editor de Neon en el dashboard
   - O usa `psql` desde la terminal

### Opción B: Usar Script de Migración Automatizado

Ejecuta el script proporcionado:

```bash
chmod +x migrate_to_neon.sh
./migrate_to_neon.sh
```

---

## PASO 7: CREAR USUARIO ADMINISTRADOR

```bash
# Abrir tinker
php artisan tinker

# Dentro de tinker, ejecutar:
$user = App\Models\User::create([
    'name' => 'Administrador',
    'email' => 'admin@quantum.com',
    'password' => bcrypt('tu_contraseña_segura'),
    'email_verified_at' => now(),
]);

# Asignar rol de administrador si tienes sistema de roles
# $user->roles()->attach(1); // Ajusta según tu sistema de roles
```

---

## PASO 8: VERIFICAR QUE TODO FUNCIONA

1. **Probar conexión:**
   ```bash
   php artisan db:show
   ```

2. **Verificar tablas:**
   ```bash
   php artisan db:table
   ```

3. **Probar aplicación:**
   - Inicia el servidor: `php artisan serve`
   - Visita: http://localhost:8000
   - Intenta iniciar sesión con el usuario creado

---

## 🔧 SOLUCIÓN DE PROBLEMAS

### Error: "SSL connection required"

**Solución:** Asegúrate de que `DB_SSLMODE=require` esté en tu `.env`

### Error: "Connection timeout"

**Solución:** 
- Verifica que estés usando el endpoint correcto (pooler o direct)
- Revisa que el firewall no esté bloqueando la conexión
- Verifica que las credenciales sean correctas

### Error: "Database does not exist"

**Solución:**
- Verifica el nombre de la base de datos en Neon dashboard
- Asegúrate de que el nombre en `.env` coincida exactamente

### Error: "Extension pgsql not found"

**Solución:**
```bash
sudo apt-get install php-pgsql
sudo systemctl restart php8.2-fpm  # Ajusta según tu versión
```

### Migraciones fallan

**Solución:**
- Verifica que todas las migraciones sean compatibles con PostgreSQL
- Revisa los logs: `php artisan migrate --pretend` para ver qué se ejecutaría
- Si hay errores, revisa la migración específica y ajusta la sintaxis

---

## 📊 MONITOREO Y MANTENIMIENTO

### Ver Uso de Recursos en Neon

1. Ve al dashboard de Neon
2. Sección **"Usage"** muestra:
   - Almacenamiento usado
   - Compute time
   - Requests

### Backups Automáticos

Neon realiza backups automáticos. Puedes:
- Ver backups en la sección **"Branches"**
- Crear branches para desarrollo/testing
- Restaurar desde cualquier punto en el tiempo

### Connection Pooling

Neon incluye connection pooling automático. Para mejor rendimiento:
- Usa el endpoint `-pooler` en producción
- Limita conexiones concurrentes en Laravel si es necesario

---

## ✅ CHECKLIST FINAL

- [ ] Cuenta creada en Neon
- [ ] Proyecto creado en Neon
- [ ] Credenciales copiadas
- [ ] `.env` actualizado con credenciales de Neon
- [ ] `DB_CONNECTION=pgsql` configurado
- [ ] `DB_SSLMODE=require` configurado
- [ ] Extensión `php-pgsql` instalada
- [ ] Conexión probada con `php artisan db:show`
- [ ] Migraciones ejecutadas exitosamente
- [ ] Usuario administrador creado
- [ ] Aplicación funcionando correctamente

---

## 🎉 ¡LISTO!

Tu aplicación ahora está usando Neon PostgreSQL. Disfruta de:
- Base de datos serverless escalable
- Backups automáticos
- Connection pooling incluido
- Plan gratuito generoso

---

## 📚 RECURSOS ADICIONALES

- [Documentación de Neon](https://neon.tech/docs)
- [Guía de Connection Pooling](https://neon.tech/docs/connect/connection-pooling)
- [Laravel PostgreSQL Documentation](https://laravel.com/docs/database#postgresql)

---

**¿Necesitas ayuda?** Revisa los logs de Laravel: `storage/logs/laravel.log`


