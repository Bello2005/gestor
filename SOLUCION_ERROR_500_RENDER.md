# 🔧 SOLUCIÓN: Error 500 Internal Server Error en Render

## 🚨 Problemas Identificados

### 1. Queue Worker - Ruta Incorrecta ✅ CORREGIDO
```
Could not open input file: /var/www/html/artisan
```

**Causa:** Supervisor busca artisan en `/var/www/html/` pero el WORKDIR es `/var/www/`

**Solución:** Actualizado `docker/supervisor/supervisord.conf` para usar `/var/www/`

### 2. Error 500 - Necesita Diagnóstico

El error 500 puede ser causado por:
- ❌ Vite manifest aún no generado
- ❌ Error de PHP/Laravel
- ❌ Problema de permisos
- ❌ Error de conexión a base de datos

---

## ✅ SOLUCIÓN: Pasos Inmediatos

### Paso 1: Verificar Logs de Laravel

Para ver el error específico, necesitas acceder a los logs de Laravel. En Render:

1. Ve a **"Logs"** en tu servicio
2. Busca líneas que contengan:
   - `ERROR`
   - `Exception`
   - `SQLSTATE`
   - Stack trace

### Paso 2: Verificar que Build Fue Exitoso

En Render Dashboard:
1. Ve a **"Events"** o **"Deployments"**
2. Revisa el último deployment
3. Busca en los logs:
   ```
   npm run build
   ✓ built in Xs
   ```

Si no ves esto, el build de Vite falló.

### Paso 3: Verificar Archivos Generados

Si tienes acceso a shell (o en el contenedor), verifica:

```bash
ls -la /var/www/public/build/
```

Deberías ver:
- `manifest.json`
- `assets/` (directorio)

---

## 🔍 Diagnóstico del Error 500

### Opción A: Habilitar Logs Detallados Temporalmente

En Render, agrega temporalmente estas variables:

```env
APP_DEBUG=true
LOG_LEVEL=debug
```

**⚠️ IMPORTANTE:** Solo para diagnóstico. Desactívalo después.

### Opción B: Verificar Logs de Laravel

Los logs de Laravel deberían estar en:
- `/var/www/storage/logs/laravel.log`

En Render, busca en los logs mensajes que contengan el stack trace del error.

---

## 🔧 Soluciones Comunes

### Si es Error de Vite Manifest:

1. Verifica que `npm run build` se ejecutó en el build
2. Verifica que `public/build/manifest.json` existe
3. Si no existe, el build falló silenciosamente

**Solución temporal:** Crear manifest.json manualmente (ver SOLUCION_VITE_MANIFEST.md)

### Si es Error de Base de Datos:

1. Verifica variables de entorno en Render
2. Prueba conexión manualmente:
   ```bash
   psql "postgresql://neondb_owner:npg_3jSRL2fqIoec@ep-sweet-meadow-acu8tl0p-pooler.sa-east-1.aws.neon.tech/neondb?sslmode=require" -c "SELECT 1;"
   ```

### Si es Error de Permisos:

```bash
chmod -R 775 /var/www/storage /var/www/bootstrap/cache
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
```

---

## 📋 Checklist de Verificación

- [ ] Queue worker corregido (hecho ✅)
- [ ] Build de Vite exitoso
- [ ] `public/build/manifest.json` existe
- [ ] Variables de entorno correctas
- [ ] Conexión a base de datos funciona
- [ ] Permisos correctos en storage
- [ ] Logs de Laravel revisados

---

## 🚀 Próximos Pasos

1. **Hacer commit y push** de la corrección del queue worker:
   ```bash
   git add docker/supervisor/supervisord.conf
   git commit -m "Fix: Corregir ruta de artisan en queue worker"
   git push
   ```

2. **Esperar nuevo build** (3-5 minutos)

3. **Revisar logs** para ver el error específico del 500

4. **Compartir logs** si necesitas más ayuda

---

## 📞 Información Necesaria para Debug

Si el error persiste, comparte:

1. **Últimas 50-100 líneas de logs** de Render
2. **Build logs** del último deployment
3. **Mensaje de error específico** (si aparece en los logs)
4. **Stack trace** completo (si está disponible)

---

**Última actualización:** $(date)

