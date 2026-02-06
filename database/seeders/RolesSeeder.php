<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    public function run()
    {
        // Insertar roles (upsert by slug)
        $roles = [
            [
                'name' => 'Administrador',
                'slug' => 'admin',
                'description' => 'Control total del sistema. Exento del flujo de solicitudes de acceso.',
            ],
            [
                'name' => 'Gestor',
                'slug' => 'gestor',
                'description' => 'Gestor de proyectos. Bonificación de confianza en evaluación de riesgo.',
            ],
            [
                'name' => 'Colaborador',
                'slug' => 'colaborador',
                'description' => 'Miembro del equipo. Evaluación de riesgo completa.',
            ],
        ];

        foreach ($roles as $role) {
            $existing = DB::table('roles')->where('slug', $role['slug'])->first();
            if ($existing) {
                DB::table('roles')->where('id', $existing->id)->update(array_merge($role, ['updated_at' => now()]));
            } else {
                DB::table('roles')->insert(array_merge($role, ['created_at' => now(), 'updated_at' => now()]));
            }
        }

        // Asignar roles a usuarios de prueba (si existen)
        $assignments = [
            'test1@uniclaretiana.edu.co' => 'admin',
            'test2@uniclaretiana.edu.co' => 'gestor',
            'test3@uniclaretiana.edu.co' => 'colaborador',
        ];

        foreach ($assignments as $email => $slug) {
            $userId = DB::table('users')->where('email', $email)->value('id');
            $roleId = DB::table('roles')->where('slug', $slug)->value('id');

            if ($userId && $roleId) {
                $exists = DB::table('role_user')->where('user_id', $userId)->where('role_id', $roleId)->exists();
                if (!$exists) {
                    DB::table('role_user')->insert([
                        'user_id' => $userId,
                        'role_id' => $roleId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
