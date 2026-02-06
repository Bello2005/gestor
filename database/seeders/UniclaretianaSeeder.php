<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UniclaretianaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
            // Roles
            DB::table('roles')->insert([
                [
                    'id' => 1,
                    'name' => 'Administrador',
                    'slug' => 'admin',
                    'description' => 'Control total del sistema. Exento del flujo de solicitudes de acceso.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 2,
                    'name' => 'Gestor',
                    'slug' => 'gestor',
                    'description' => 'Gestor de proyectos. Bonificación de confianza en evaluación de riesgo.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);

            // Usuarios
            DB::table('users')->insert([
                [
                    'id' => 1,
                    'name' => 'María Elena García',
                    'email' => 'admin@uniclaretiana.edu.co',
                    'email_verified_at' => now(),
                    'password' => bcrypt('password123'),
                    'remember_token' => null,
                    'is_temporary_password' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 2,
                    'name' => 'Carlos Rodríguez',
                    'email' => 'coordinador@uniclaretiana.edu.co',
                    'email_verified_at' => now(),
                    'password' => bcrypt('password456'),
                    'remember_token' => null,
                    'is_temporary_password' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);

            // Relación usuario-rol
            DB::table('role_user')->insert([
                [
                    'user_id' => 1,
                    'role_id' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'user_id' => 2,
                    'role_id' => 2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);

            // Proyectos
            DB::table('proyectos')->insert([
                [
                    'id' => 1,
                    'nombre_del_proyecto' => 'Fortalecimiento de Capacidades Empresariales',
                    'objeto_contractual' => 'Desarrollo de competencias empresariales',
                    'lineas_de_accion' => 'Capacitación, Acompañamiento',
                    'cobertura' => 'Chocó',
                    'entidad_contratante' => 'Gobernación del Chocó',
                    'fecha_de_ejecucion' => '2024-03-01',
                    'plazo' => 18.00,
                    'plazo_unidad' => 'meses',
                    'valor_total' => 450000000.00,
                    'cargar_archivo_proyecto' => 'proyecto_001.pdf',
                    'cargar_contrato_o_convenio' => 'convenio_001.pdf',
                    'estado' => 'activo',
                    'cargar_evidencias' => json_encode(['evidencia1.jpg', 'evidencia2.pdf']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 2,
                    'nombre_del_proyecto' => 'Investigación en Biodiversidad',
                    'objeto_contractual' => 'Estudio de especies endémicas',
                    'lineas_de_accion' => 'Investigación, Conservación',
                    'cobertura' => 'Pacífico Colombiano',
                    'entidad_contratante' => 'SINCHI',
                    'fecha_de_ejecucion' => '2024-01-15',
                    'plazo' => 24.00,
                    'plazo_unidad' => 'meses',
                    'valor_total' => 680000000.00,
                    'cargar_archivo_proyecto' => 'proyecto_002.pdf',
                    'cargar_contrato_o_convenio' => 'convenio_002.pdf',
                    'estado' => 'activo',
                    'cargar_evidencias' => json_encode(['campo1.jpg', 'laboratorio1.pdf']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
    }
}
