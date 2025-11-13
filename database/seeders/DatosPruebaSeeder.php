<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Hash;

class DatosPruebaSeeder extends Seeder
{
    public function run(): void
    {
        echo "🌱 Creando datos de prueba...\n\n";

        // 1. CREAR ROLES
        echo "📋 Creando roles...\n";
        $adminRole = Role::firstOrCreate(
            ['slug' => 'admin'],
            [
                'name' => 'admin',
                'description' => 'Administrador del sistema'
            ]
        );

        $userRole = Role::firstOrCreate(
            ['slug' => 'user'],
            [
                'name' => 'user',
                'description' => 'Usuario estándar'
            ]
        );

        // 2. CREAR USUARIOS
        echo "👥 Creando usuarios...\n";

        // Usuario Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@quantum.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123'),
                'is_temporary_password' => false,
            ]
        );
        $admin->roles()->syncWithoutDetaching([$adminRole->id]);
        echo "   ✓ Admin: admin@quantum.com / admin123\n";

        // Usuario Normal 1
        $user1 = User::firstOrCreate(
            ['email' => 'usuario1@quantum.com'],
            [
                'name' => 'María García',
                'password' => Hash::make('user123'),
                'is_temporary_password' => false,
            ]
        );
        $user1->roles()->syncWithoutDetaching([$userRole->id]);
        echo "   ✓ Usuario 1: usuario1@quantum.com / user123\n";

        // Usuario Normal 2
        $user2 = User::firstOrCreate(
            ['email' => 'usuario2@quantum.com'],
            [
                'name' => 'Carlos Rodríguez',
                'password' => Hash::make('user123'),
                'is_temporary_password' => false,
            ]
        );
        $user2->roles()->syncWithoutDetaching([$userRole->id]);
        echo "   ✓ Usuario 2: usuario2@quantum.com / user123\n";

        // 3. CREAR PROYECTOS DE PRUEBA
        echo "\n📊 Creando proyectos de prueba...\n";

        $proyectos = [
            [
                'nombre_del_proyecto' => 'Desarrollo de Software Educativo',
                'objeto_contractual' => 'Crear plataforma e-learning para instituciones educativas',
                'lineas_de_accion' => "1. Análisis de requisitos\n2. Diseño de interfaz\n3. Desarrollo backend\n4. Testing y QA\n5. Deployment",
                'cobertura' => 'Nacional',
                'entidad_contratante' => 'Ministerio de Educación',
                'fecha_de_ejecucion' => '2025-01-15',
                'plazo' => 6.00,
                'valor_total' => 150000000.00,
                'estado' => 'activo',
            ],
            [
                'nombre_del_proyecto' => 'Sistema de Gestión Hospitalaria',
                'objeto_contractual' => 'Implementar sistema integral para gestión de pacientes, citas y historias clínicas',
                'lineas_de_accion' => "1. Levantamiento de información\n2. Diseño de arquitectura\n3. Desarrollo de módulos\n4. Integración con sistemas existentes\n5. Capacitación",
                'cobertura' => 'Regional - Bogotá',
                'entidad_contratante' => 'Hospital Universitario Nacional',
                'fecha_de_ejecucion' => '2025-02-01',
                'plazo' => 8.00,
                'valor_total' => 280000000.00,
                'estado' => 'activo',
            ],
            [
                'nombre_del_proyecto' => 'Digitalización de Trámites Municipales',
                'objeto_contractual' => 'Digitalizar y automatizar trámites ciudadanos del municipio',
                'lineas_de_accion' => "1. Diagnóstico de procesos\n2. Diseño de flujos digitales\n3. Desarrollo de portal web\n4. App móvil\n5. Implementación",
                'cobertura' => 'Municipal - Medellín',
                'entidad_contratante' => 'Alcaldía de Medellín',
                'fecha_de_ejecucion' => '2024-11-20',
                'plazo' => 10.00,
                'valor_total' => 420000000.00,
                'estado' => 'activo',
            ],
            [
                'nombre_del_proyecto' => 'Plataforma de Comercio Electrónico',
                'objeto_contractual' => 'Desarrollar marketplace para artesanos locales',
                'lineas_de_accion' => "1. Estudio de mercado\n2. Diseño UX/UI\n3. Desarrollo frontend y backend\n4. Sistema de pagos\n5. Logística",
                'cobertura' => 'Nacional',
                'entidad_contratante' => 'Cámara de Comercio',
                'fecha_de_ejecucion' => '2024-10-01',
                'plazo' => 5.00,
                'valor_total' => 95000000.00,
                'estado' => 'cerrado',
            ],
            [
                'nombre_del_proyecto' => 'Sistema de Monitoreo Ambiental',
                'objeto_contractual' => 'Implementar red de sensores IoT para monitoreo de calidad del aire',
                'lineas_de_accion' => "1. Selección de sensores\n2. Desarrollo de dashboard\n3. Instalación de hardware\n4. Pruebas de campo\n5. Documentación",
                'cobertura' => 'Regional - Antioquia',
                'entidad_contratante' => 'Corporación Autónoma Regional',
                'fecha_de_ejecucion' => '2025-03-10',
                'plazo' => 4.00,
                'valor_total' => 180000000.00,
                'estado' => 'inactivo',
            ],
            [
                'nombre_del_proyecto' => 'Aplicación Móvil de Turismo',
                'objeto_contractual' => 'App móvil con realidad aumentada para turismo cultural',
                'lineas_de_accion' => "1. Diseño de experiencia AR\n2. Desarrollo iOS y Android\n3. Contenido multimedia\n4. Testing\n5. Lanzamiento",
                'cobertura' => 'Departamental - Cundinamarca',
                'entidad_contratante' => 'Secretaría de Turismo',
                'fecha_de_ejecucion' => '2025-01-05',
                'plazo' => 7.00,
                'valor_total' => 210000000.00,
                'estado' => 'activo',
            ],
            [
                'nombre_del_proyecto' => 'Sistema de Control de Inventarios',
                'objeto_contractual' => 'Software para gestión de inventarios y almacenes',
                'lineas_de_accion' => "1. Análisis de operaciones\n2. Diseño de base de datos\n3. Desarrollo web\n4. Módulo de reportes\n5. Implementación",
                'cobertura' => 'Local - Cali',
                'entidad_contratante' => 'Empresa Pública de Logística',
                'fecha_de_ejecucion' => '2024-09-15',
                'plazo' => 3.00,
                'valor_total' => 75000000.00,
                'estado' => 'cerrado',
            ],
            [
                'nombre_del_proyecto' => 'Portal de Transparencia Gubernamental',
                'objeto_contractual' => 'Plataforma web para publicación de información pública',
                'lineas_de_accion' => "1. Definición de arquitectura\n2. Diseño responsive\n3. Desarrollo portal\n4. Integración APIs\n5. Seguridad y auditoría",
                'cobertura' => 'Nacional',
                'entidad_contratante' => 'Procuraduría General',
                'fecha_de_ejecucion' => '2025-02-20',
                'plazo' => 12.00,
                'valor_total' => 550000000.00,
                'estado' => 'activo',
            ],
        ];

        foreach ($proyectos as $index => $proyectoData) {
            Proyecto::firstOrCreate(
                ['nombre_del_proyecto' => $proyectoData['nombre_del_proyecto']],
                $proyectoData
            );
            echo "   ✓ Proyecto " . ($index + 1) . ": {$proyectoData['nombre_del_proyecto']}\n";
        }

        echo "\n";
        echo "╔═══════════════════════════════════════════════════╗\n";
        echo "║        ✅ DATOS DE PRUEBA CREADOS               ║\n";
        echo "╚═══════════════════════════════════════════════════╝\n";
        echo "\n";
        echo "📊 RESUMEN:\n";
        echo "   • Usuarios: " . User::count() . "\n";
        echo "   • Proyectos: " . Proyecto::count() . "\n";
        echo "   • Proyectos activos: " . Proyecto::where('estado', 'activo')->count() . "\n";
        echo "   • Valor total: $" . number_format(Proyecto::sum('valor_total'), 0, ',', '.') . " COP\n";
        echo "\n";
        echo "🔐 CREDENCIALES:\n";
        echo "   Admin:    admin@quantum.com / admin123\n";
        echo "   Usuario:  usuario1@quantum.com / user123\n";
        echo "   Usuario:  usuario2@quantum.com / user123\n";
        echo "\n";
        echo "🚀 INICIAR SERVIDOR:\n";
        echo "   php artisan serve\n";
        echo "   http://localhost:8000\n";
        echo "\n";
    }
}
