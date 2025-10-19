# Configuración de Cloudinary para Evidencias de Proyectos

## ¿Qué es Cloudinary?

Cloudinary es un servicio de almacenamiento en la nube GRATUITO (hasta 25GB) que nos permite guardar archivos de forma permanente, ideal para plataformas como Render donde los archivos se borran al reiniciar.

## Plan Gratuito de Cloudinary

- ✅ 25 créditos mensuales
- ✅ 25GB de almacenamiento
- ✅ 25GB de ancho de banda
- ✅ Perfecto para evidencias (PDFs, imágenes, documentos)

## Pasos para Configurar Cloudinary

### 1. Crear cuenta en Cloudinary

1. Ve a [https://cloudinary.com/users/register_free](https://cloudinary.com/users/register_free)
2. Regístrate con tu correo electrónico
3. Verifica tu correo
4. Accede al Dashboard

### 2. Obtener Credenciales

Una vez en el Dashboard de Cloudinary:

1. En la página principal verás un cuadro que dice **"Account Details"**
2. Copia los siguientes datos:
   - **Cloud Name** (ejemplo: `dxyz123abc`)
   - **API Key** (ejemplo: `123456789012345`)
   - **API Secret** (ejemplo: `AbCdEf1234567890GhIjKl`)

### 3. Configurar Variables de Entorno en Render

1. Ve a tu proyecto en [Render.com](https://render.com)
2. Haz clic en tu servicio web
3. Ve a la pestaña **"Environment"**
4. Agrega la siguiente variable de entorno:

```
CLOUDINARY_URL=cloudinary://API_KEY:API_SECRET@CLOUD_NAME
```

**Ejemplo completo:**
```
CLOUDINARY_URL=cloudinary://123456789012345:AbCdEf1234567890GhIjKl@dxyz123abc
```

5. Guarda los cambios
6. Render reiniciará automáticamente tu aplicación

### 4. Verificar Configuración Local (Opcional)

Si quieres probar en tu máquina local:

1. Abre el archivo `.env`
2. Agrega la misma línea:
   ```
   CLOUDINARY_URL=cloudinary://API_KEY:API_SECRET@CLOUD_NAME
   ```
3. Guarda el archivo

## Cómo Funciona

### Subir Evidencias

1. Ve a **Gestión de Proyectos**
2. Crea un nuevo proyecto o edita uno existente
3. En la sección **"Gestión de Archivos"** encontrarás:
   - **Cargar Archivo (Proyecto)**: Sube el archivo principal del proyecto
   - **Cargar Contrato o Convenio**: Sube el contrato firmado
   - **Cargar Evidencias**: Sube múltiples archivos (fotos, videos, documentos, etc.)

4. Los archivos se subirán automáticamente a Cloudinary
5. Los enlaces se guardarán en la base de datos

### Ver Evidencias

- Al editar un proyecto, verás botones para **"Ver archivo actual"** y **"Evidencia 1, 2, 3..."**
- Al hacer clic, se abrirá el archivo directamente desde Cloudinary
- Los archivos **NUNCA se borran** aunque Render reinicie la aplicación

## Formatos Soportados

- **Documentos**: PDF, DOC, DOCX, XLS, XLSX
- **Imágenes**: JPG, JPEG, PNG
- **Tamaño máximo por archivo**: 10MB

## Ventajas de Cloudinary

✅ **Gratis** hasta 25GB
✅ **Permanente** - los archivos no se borran
✅ **Rápido** - CDN global
✅ **Seguro** - URLs únicas por archivo
✅ **Confiable** - 99.9% uptime

## Solución de Problemas

### Error: "Cloudinary URL not configured"

**Solución**: Verifica que la variable `CLOUDINARY_URL` esté configurada correctamente en Render.

### Los archivos no se suben

**Solución**:
1. Verifica que el formato del archivo esté soportado
2. Verifica que el tamaño no exceda 10MB
3. Revisa los logs de Render para ver errores específicos

### ¿Cómo verifico si Cloudinary está funcionando?

1. Ve a tu cuenta de Cloudinary
2. En el menú lateral, haz clic en **"Media Library"**
3. Deberías ver las carpetas:
   - `proyectos/archivos`
   - `proyectos/contratos`
   - `proyectos/evidencias`
4. Dentro de cada carpeta verás los archivos subidos

## Monitoreo de Uso

Para verificar cuánto espacio has usado:

1. Inicia sesión en Cloudinary
2. En el Dashboard verás un gráfico de uso
3. Puedes ver:
   - Almacenamiento usado
   - Ancho de banda usado
   - Créditos restantes

---

**Implementado por**: Claude Code
**Fecha**: 2025
**Paquete utilizado**: [cloudinary-labs/cloudinary-laravel](https://github.com/cloudinary-labs/cloudinary-laravel)
