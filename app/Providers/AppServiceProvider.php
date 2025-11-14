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

        // Configurar variables de entorno de PostgreSQL para evitar error de certificado
        // Esto asegura que las variables estén disponibles cuando Laravel crea la conexión
        if (config('database.default') === 'pgsql') {
            putenv('PGSSLMODE=' . env('DB_SSLMODE', 'require'));
            putenv('PGSSLCERT=/dev/null');
            putenv('PGSSLKEY=/dev/null');
            putenv('PGSSLROOTCERT=/dev/null');
        }
    }
}
