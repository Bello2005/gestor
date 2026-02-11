<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsersSeeder::class,
            RolesSeeder::class,
            // BombardSeeder::class,  // Descomentar para llenar la DB con muchos datos de prueba (usuarios, proyectos, audit, etc.). Rutas de archivos vacías.
        ]);
    }
}
