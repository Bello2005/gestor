<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Forzar HTTPS en production y development (no en local)
        if ($this->app->environment('production', 'development')) {
            URL::forceScheme('https');
        }

        // Configurar variables de entorno de PostgreSQL para SSL sin certificados del cliente
        // NO configurar PGSSLROOTCERT, PGSSLCERT, PGSSLKEY
        // Al no configurarlos, PostgreSQL usará SSL sin buscar certificados del cliente
        if (config('database.default') === 'pgsql') {
            putenv('PGSSLMODE=' . env('DB_SSLMODE', 'require'));
            // NO configurar certificados - si están configuradas, eliminarlas
            // Esto asegura que PostgreSQL no intente leer archivos de certificado
            if (getenv('PGSSLCERT')) {
                putenv('PGSSLCERT');
            }
            if (getenv('PGSSLKEY')) {
                putenv('PGSSLKEY');
            }
            if (getenv('PGSSLROOTCERT')) {
                putenv('PGSSLROOTCERT');
            }
        }
    }
}
