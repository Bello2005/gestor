# Sistema de Gestión

Este es un sistema de gestión desarrollado con Laravel, que utiliza PostgreSQL como base de datos y cuenta con integración de Cloudinary para el manejo de archivos.

## 📋 Requisitos Previos

-   PHP >= 8.2
-   Composer
-   PostgreSQL
-   Extensiones PHP requeridas:
    -   pdo_pgsql
    -   pgsql
    -   BCMath
    -   Ctype
    -   cURL
    -   DOM
    -   Fileinfo
    -   JSON
    -   Mbstring
    -   OpenSSL
    -   PCRE
    -   PDO
    -   Tokenizer
    -   XML

## 🚀 Instalación Local

1. **Clonar el repositorio**

```bash
git clone https://github.com/Bello2005/gestor.git
cd gestor
```

2. **Instalar dependencias de PHP**

```bash
composer install
```

3. **Instalar dependencias de Node.js**

```bash
npm install
```

4. **Configurar el entorno**

```bash
cp .env.example .env
php artisan key:generate
```

5. **Configurar la base de datos y servicios**

    Actualizar las credenciales en el archivo `.env`:

```env
# Base de datos PostgreSQL
DB_CONNECTION=pgsql
DB_HOST=tu-host-postgresql
DB_PORT=5432
DB_DATABASE=tu-base-de-datos
DB_USERNAME=tu-usuario
DB_PASSWORD=tu-contraseña

# Cloudinary
CLOUDINARY_URL=tu-url-de-cloudinary
CLOUDINARY_CLOUD_NAME=tu-cloud-name
CLOUDINARY_API_KEY=tu-api-key
CLOUDINARY_API_SECRET=tu-api-secret

# Configuración de correo
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu-email@gmail.com
MAIL_FROM_NAME="Sistema de Gestión"
```

6. **Crear enlace simbólico para el almacenamiento**

```bash
php artisan storage:link
```

7. **Optimizar la aplicación**

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

8. **Iniciar el servidor de desarrollo**

```bash
php artisan serve
```

La aplicación estará disponible en `http://localhost:8000`

## 🌐 Despliegue en Producción (Docker)

1. **Preparación**

    - Docker instalado en el servidor
    - Variables de entorno configuradas
    - Acceso a PostgreSQL en la nube (por ejemplo, en Render.com)
    - Cuenta de Cloudinary configurada

2. **Configuración del Servidor Web**

    ### Apache

    Asegúrate de que el archivo `.htaccess` esté presente en la carpeta `public/` y que el módulo `mod_rewrite` esté habilitado:

    ```apache
    <VirtualHost *:80>
        ServerName tudominio.com
        DocumentRoot /ruta/a/tu/proyecto/public

        <Directory /ruta/a/tu/proyecto/public>
            AllowOverride All
            Require all granted
        </Directory>
    </VirtualHost>
    ```

    ### Nginx

    ```nginx
    server {
        listen 80;
        server_name tudominio.com;
        root /ruta/a/tu/proyecto/public;

        add_header X-Frame-Options "SAMEORIGIN";
        add_header X-Content-Type-Options "nosniff";

        index index.php;

        charset utf-8;

        location / {
            try_files $uri $uri/ /index.php?$query_string;
        }

        location = /favicon.ico { access_log off; log_not_found off; }
        location = /robots.txt  { access_log off; log_not_found off; }

        error_page 404 /index.php;

        location ~ \.php$ {
            fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
            fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
            include fastcgi_params;
        }

        location ~ /\.(?!well-known).* {
            deny all;
        }
    }
    ```

3. **Despliegue con Docker**

```bash
# Construir la imagen
docker build -t gestor .

# Ejecutar el contenedor
docker run -d \
  --name gestor \
  -p 80:80 \
  --env-file .env \
  gestor
```

4. **Configuración del Entorno de Producción**
   Ejemplo del archivo `.env` para producción:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.com
APP_KEY=tu-app-key

# PostgreSQL en Render.com
DB_CONNECTION=pgsql
DB_HOST=tu-host.render.com
DB_PORT=5432
DB_DATABASE=tu_database
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password

# Cloudinary
CLOUDINARY_URL=cloudinary://api_key:api_secret@cloud_name
CLOUDINARY_CLOUD_NAME=tu-cloud-name
CLOUDINARY_API_KEY=tu-api-key
CLOUDINARY_API_SECRET=tu-api-secret

# Correo
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu-email@gmail.com
MAIL_FROM_NAME="Sistema de Gestión"

# Configuración adicional
SESSION_DRIVER=cookie
SESSION_LIFETIME=120
FILESYSTEM_DISK=public
QUEUE_CONNECTION=sync
```

5. **Variables de Entorno Importantes**

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

6. **Configuración de Tareas Programadas**
   Agregar al crontab:

```bash
* * * * * cd /ruta/a/tu/proyecto && php artisan schedule:run >> /dev/null 2>&1
```

7. **Supervisor para Colas (opcional)**

```bash
sudo apt-get install supervisor
```

Crear archivo de configuración:

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /ruta/a/tu/proyecto/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=8
redirect_stderr=true
stdout_logfile=/ruta/a/tu/proyecto/storage/logs/worker.log
```

8. **SSL/TLS**
   Configurar certificado SSL usando Let's Encrypt:

```bash
sudo certbot --nginx -d tudominio.com
```

## 🔒 Seguridad

-   Mantener todas las dependencias actualizadas
-   Configurar correctamente los permisos de archivos
-   Usar contraseñas fuertes
-   Mantener el modo debug desactivado en producción
-   Configurar correctamente el firewall
-   Realizar copias de seguridad regularmente

## 📝 Licencia

Este proyecto está bajo la Licencia MIT - ver el archivo [LICENSE.md](LICENSE.md) para más detalles.

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

-   **[Vehikl](https://vehikl.com)**
-   **[Tighten Co.](https://tighten.co)**
-   **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
-   **[64 Robots](https://64robots.com)**
-   **[Curotec](https://www.curotec.com/services/technologies/laravel)**
-   **[DevSquad](https://devsquad.com/hire-laravel-developers)**
-   **[Redberry](https://redberry.international/laravel-development)**
-   **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
