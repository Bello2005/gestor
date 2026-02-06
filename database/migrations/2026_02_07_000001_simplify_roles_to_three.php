<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Get the colaborador role id (will be the fallback for orphaned users)
        $colaboradorId = DB::table('roles')->where('slug', 'usuario')->value('id')
            ?? DB::table('roles')->where('slug', 'colaborador')->value('id');

        // 2. Reassign users from orphan roles to colaborador
        if ($colaboradorId) {
            $orphanSlugs = ['director', 'coordinador', 'coordinator', 'empleado'];
            $orphanIds = DB::table('roles')->whereIn('slug', $orphanSlugs)->pluck('id');

            if ($orphanIds->isNotEmpty()) {
                // Move users to colaborador (skip if they already have it)
                $orphanUserIds = DB::table('role_user')
                    ->whereIn('role_id', $orphanIds)
                    ->pluck('user_id')
                    ->unique();

                foreach ($orphanUserIds as $userId) {
                    $alreadyHas = DB::table('role_user')
                        ->where('user_id', $userId)
                        ->where('role_id', $colaboradorId)
                        ->exists();

                    if (!$alreadyHas) {
                        DB::table('role_user')->insert([
                            'user_id' => $userId,
                            'role_id' => $colaboradorId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }

                // Remove orphan role assignments
                DB::table('role_user')->whereIn('role_id', $orphanIds)->delete();

                // Delete orphan roles
                DB::table('roles')->whereIn('id', $orphanIds)->delete();
            }
        }

        // 3. Rename supervisor → gestor
        DB::table('roles')
            ->where('slug', 'supervisor')
            ->update([
                'name' => 'Gestor',
                'slug' => 'gestor',
                'description' => 'Gestor de proyectos. Bonificación de confianza en evaluación de riesgo.',
                'updated_at' => now(),
            ]);

        // 4. Rename usuario → colaborador
        DB::table('roles')
            ->where('slug', 'usuario')
            ->update([
                'name' => 'Colaborador',
                'slug' => 'colaborador',
                'description' => 'Miembro del equipo. Evaluación de riesgo completa.',
                'updated_at' => now(),
            ]);

        // 5. Update admin description
        DB::table('roles')
            ->where('slug', 'admin')
            ->update([
                'name' => 'Administrador',
                'description' => 'Control total del sistema. Exento del flujo de solicitudes de acceso.',
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        DB::table('roles')->where('slug', 'gestor')->update([
            'name' => 'Supervisor',
            'slug' => 'supervisor',
            'description' => 'Supervisor de proyectos',
            'updated_at' => now(),
        ]);

        DB::table('roles')->where('slug', 'colaborador')->update([
            'name' => 'Usuario',
            'slug' => 'usuario',
            'description' => 'Usuario regular',
            'updated_at' => now(),
        ]);

        DB::table('roles')->where('slug', 'admin')->update([
            'name' => 'Administrador',
            'description' => 'Administrador del sistema',
            'updated_at' => now(),
        ]);
    }
};
