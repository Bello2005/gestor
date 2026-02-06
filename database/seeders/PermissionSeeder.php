<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Proyectos
            ['name' => 'Ver Proyectos',      'slug' => 'proyectos.ver',      'category' => 'proyectos',   'risk_weight' => 1, 'description' => 'Permite visualizar la lista y detalles de proyectos'],
            ['name' => 'Crear Proyectos',     'slug' => 'proyectos.crear',    'category' => 'proyectos',   'risk_weight' => 2, 'description' => 'Permite crear nuevos proyectos en el sistema'],
            ['name' => 'Editar Proyectos',    'slug' => 'proyectos.editar',   'category' => 'proyectos',   'risk_weight' => 3, 'description' => 'Permite modificar información de proyectos existentes'],
            ['name' => 'Eliminar Proyectos',  'slug' => 'proyectos.eliminar', 'category' => 'proyectos',   'risk_weight' => 4, 'description' => 'Permite eliminar proyectos del sistema'],
            ['name' => 'Exportar Proyectos',  'slug' => 'proyectos.exportar', 'category' => 'proyectos',   'risk_weight' => 2, 'description' => 'Permite exportar datos de proyectos a Excel, PDF o Word'],

            // Usuarios
            ['name' => 'Ver Usuarios',        'slug' => 'usuarios.ver',       'category' => 'usuarios',    'risk_weight' => 1, 'description' => 'Permite ver la lista de usuarios del sistema'],
            ['name' => 'Gestionar Usuarios',  'slug' => 'usuarios.gestionar', 'category' => 'usuarios',    'risk_weight' => 5, 'description' => 'Permite crear, editar y eliminar usuarios'],

            // Auditoría
            ['name' => 'Ver Auditoría',       'slug' => 'auditoria.ver',      'category' => 'auditoria',   'risk_weight' => 2, 'description' => 'Permite acceder al registro de auditoría'],
            ['name' => 'Exportar Auditoría',  'slug' => 'auditoria.exportar', 'category' => 'auditoria',   'risk_weight' => 3, 'description' => 'Permite exportar registros de auditoría'],

            // Solicitudes
            ['name' => 'Gestionar Solicitudes', 'slug' => 'solicitudes.gestionar', 'category' => 'solicitudes', 'risk_weight' => 4, 'description' => 'Permite aprobar o rechazar solicitudes de acceso'],

            // Configuración
            ['name' => 'Gestionar Configuración', 'slug' => 'config.gestionar', 'category' => 'configuracion', 'risk_weight' => 5, 'description' => 'Permite modificar la configuración del sistema'],
        ];

        foreach ($permissions as $perm) {
            Permission::updateOrCreate(
                ['slug' => $perm['slug']],
                $perm
            );
        }

        // Asignar permisos a roles
        $adminRole = Role::where('slug', Role::ADMIN)->first();
        $gestorRole = Role::where('slug', Role::GESTOR)->first();
        $colaboradorRole = Role::where('slug', Role::COLABORADOR)->first();

        $allPermissions = Permission::pluck('id')->toArray();

        if ($adminRole) {
            $adminRole->permissions()->syncWithoutDetaching($allPermissions);
        }

        if ($gestorRole) {
            $gestorPerms = Permission::whereIn('slug', [
                'proyectos.ver', 'proyectos.crear', 'proyectos.editar', 'proyectos.exportar',
                'usuarios.ver', 'auditoria.ver', 'solicitudes.gestionar',
            ])->pluck('id')->toArray();
            $gestorRole->permissions()->syncWithoutDetaching($gestorPerms);
        }

        if ($colaboradorRole) {
            $colaboradorPerms = Permission::whereIn('slug', [
                'proyectos.ver', 'proyectos.exportar',
            ])->pluck('id')->toArray();
            $colaboradorRole->permissions()->syncWithoutDetaching($colaboradorPerms);
        }
    }
}
