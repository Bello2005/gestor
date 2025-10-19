# Configuración de Resend para envío de correos

## ¿Por qué Resend?

Actualmente tienes `MAIL_MAILER=log` que solo guarda los correos en logs, **no los envía realmente**.

**Resend** es el servicio de email recomendado para Laravel 12+ porque:
- ✅ **GRATIS** hasta 3,000 emails/mes (100 emails/día)
- ✅ Soportado **nativamente** por Laravel 12
- ✅ Configuración **muy simple** (solo 2 variables de entorno)
- ✅ No requiere verificar dominio para pruebas (`onboarding@resend.dev`)
- ✅ Excelente entregabilidad (casi 0% spam)

## Pasos para configurar Resend

### 1. Crear cuenta en Resend

1. Ve a: https://resend.com/signup
2. Regístrate con tu email (Gmail, GitHub, etc.)
3. Verifica tu email

### 2. Obtener API Key

1. Una vez en el dashboard, ve a: https://resend.com/api-keys
2. Click en "Create API Key"
3. Dale un nombre (ej: "Gestor Production")
4. Selecciona permisos: **"Sending access"** (solo enviar)
5. Click "Add"
6. **COPIA la API Key** (solo se muestra una vez)
   - Ejemplo: `re_123abc...xyz789`

### 3. Configurar variables en Render

En tu proyecto de Render (https://dashboard.render.com):

1. Ve a tu servicio web "gestor"
2. Click en "Environment"
3. **Agrega/actualiza estas variables**:

```bash
MAIL_MAILER=resend
RESEND_KEY=re_TU_API_KEY_AQUI
MAIL_FROM_ADDRESS=onboarding@resend.dev
MAIL_FROM_NAME="Sistema de Gestión"
```

4. Click "Save Changes"
5. Render automáticamente hará un re-deploy

### 4. Verificar que funciona

Después del deploy:

1. Ve a: https://gestor-0o3w.onrender.com/users
2. Click en el botón de restablecer contraseña de algún usuario
3. Selecciona "Enviar enlace por correo"
4. Click "Restablecer contraseña"

**Importante**: El correo se enviará desde `onboarding@resend.dev` (email de prueba de Resend) al email del usuario.

**Verifica**:
- Ve al dashboard de Resend: https://resend.com/emails
- Deberías ver el email enviado con estado "Delivered" (entregado)
- El usuario debe recibir el correo en su bandeja (revisa spam también)

## Opciones avanzadas (después de probar)

### Opción A: Usar tu propio dominio

Si quieres que los correos salgan desde `@tudominio.com`:

1. En Resend, ve a "Domains" → "Add Domain"
2. Agrega tu dominio (ej: `gestor-app.com`)
3. Configura los DNS records que te indica Resend
4. Espera la verificación (~5-10 minutos)
5. Actualiza en Render:
   ```bash
   MAIL_FROM_ADDRESS=noreply@tudominio.com
   ```

### Opción B: Usar Gmail SMTP (no recomendado)

Si prefieres Gmail (más complejo, menos confiable):

```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu_app_password_aqui  # No tu contraseña normal
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu-email@gmail.com
```

**Requiere**: Habilitar "App Passwords" en tu cuenta de Google.

## Troubleshooting

### "You can only send testing emails to your own email address"

**Problema**: Resend en modo FREE sin dominio verificado solo permite enviar emails a tu propio email (el que usaste para registrarte).

**Soluciones**:

1. **Verificar un dominio** (RECOMENDADA - gratis y permanente):
   - Ve a https://resend.com/domains
   - Agrega tu dominio (ej: `tudominio.com`)
   - Configura los DNS records
   - Cambia `MAIL_FROM_ADDRESS=noreply@tudominio.com`

2. **Usar Gmail SMTP** (temporal, si no tienes dominio):
   - Ve a la sección "Alternativa: Gmail SMTP" más abajo

### "No se envía el correo"

1. Verifica que las variables estén correctas en Render
2. Checa los logs de Render para ver errores
3. Verifica que el paquete `resend/resend-php` esté instalado:
   ```bash
   composer show | grep resend
   ```

### "El correo llega a spam"

- Esto es normal con `onboarding@resend.dev`
- Solución: Verifica tu propio dominio en Resend

### "Error: API Key inválida"

- Verifica que copiaste la API Key completa (empieza con `re_`)
- Asegúrate de no tener espacios al inicio/final
- Crea una nueva API Key si la perdiste

## Límites del plan FREE

- **3,000 emails/mes** (suficiente para ~100 usuarios activos)
- **100 emails/día**
- Si necesitas más, el plan Pro cuesta $20/mes (50,000 emails)

## Archivo modificado

Este paquete ya está instalado en el proyecto:
- `composer.json` incluye `resend/resend-php`
- Laravel 12 detecta automáticamente el driver cuando `MAIL_MAILER=resend`

## Alternativa: Gmail SMTP (Si no tienes dominio)

Si no tienes un dominio propio y necesitas enviar correos YA, usa Gmail SMTP:

### Paso 1: Habilitar App Password en Google

1. Ve a tu cuenta de Google: https://myaccount.google.com/security
2. Activa **"Verificación en 2 pasos"** (si no la tienes)
3. Ve a **"Contraseñas de aplicaciones"**: https://myaccount.google.com/apppasswords
4. Selecciona:
   - App: "Correo"
   - Dispositivo: "Otro (nombre personalizado)" → escribe "Laravel Gestor"
5. Click "Generar"
6. **Copia la contraseña de 16 caracteres** (sin espacios)

### Paso 2: Configurar variables en Render

Cambia estas variables en Render:

```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=deinerb2.0.0.5@gmail.com
MAIL_PASSWORD=tu_app_password_de_16_caracteres
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=deinerb2.0.0.5@gmail.com
MAIL_FROM_NAME="Sistema de Gestión"
```

**Importante**: Usa la App Password de 16 caracteres, NO tu contraseña normal de Gmail.

### Paso 3: Eliminar variables de Resend

Elimina o comenta (si las agregaste):
- `RESEND_KEY`

### Limitaciones de Gmail

- **Límite**: 500 emails/día
- **Problema**: Los correos pueden ir a spam más fácilmente
- **Advertencia**: Si Gmail detecta uso "masivo", puede bloquear temporalmente

### ¿Cuál usar?

| Opción | Ventajas | Desventajas | Recomendado para |
|--------|----------|-------------|------------------|
| **Resend + dominio** | ✅ Profesional<br>✅ No va a spam<br>✅ 3000 emails/mes | ⚠️ Requiere dominio | Producción |
| **Gmail SMTP** | ✅ Setup rápido<br>✅ No requiere dominio | ❌ Puede ir a spam<br>❌ Solo 500/día<br>❌ Menos profesional | Pruebas/desarrollo |

## Recursos

- Documentación Resend: https://resend.com/docs
- Dashboard: https://resend.com/emails
- API Keys: https://resend.com/api-keys
- Laravel Mail: https://laravel.com/docs/12.x/mail
- Gmail App Passwords: https://myaccount.google.com/apppasswords
