# ⚡ INICIO RÁPIDO - MIGRACIÓN A NEON (5 MINUTOS)

## 🎯 PASOS RÁPIDOS

### 1️⃣ Crear Proyecto en Neon (2 min)

1. Ve a: https://neon.tech/
2. Regístrate (gratis, sin tarjeta)
3. Crea proyecto: **"New Project"**
   - Name: `quantum-db`
   - Region: Cercana a tu ubicación
   - Plan: **Free**
4. Copia las credenciales de **Connection Details**

### 2️⃣ Ejecutar Script Automático (1 min)

```bash
./migrate_to_neon.sh
```

El script te pedirá:
- Host de Neon
- Puerto (default: 5432)
- Base de datos
- Usuario
- Contraseña

### 3️⃣ Ejecutar Migraciones (1 min)

```bash
php artisan migrate:fresh
```

### 4️⃣ Crear Usuario Admin (1 min)

```bash
php artisan tinker
```

```php
App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@quantum.com',
    'password' => bcrypt('tu_contraseña'),
    'email_verified_at' => now(),
]);
```

## ✅ ¡LISTO!

Tu aplicación ahora usa Neon PostgreSQL.

---

## 📚 Documentación Completa

Para más detalles, consulta: **MIGRACION_NEON.md**

---

## 🔧 Si algo falla

1. Verifica credenciales en `.env`
2. Asegúrate de tener `php-pgsql` instalado
3. Revisa logs: `storage/logs/laravel.log`


