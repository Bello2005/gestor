<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Get or create the colaborador role (from 'user' slug)
        $userRole = DB::table('roles')->where('slug', 'user')->first();

        if ($userRole) {
            // Rename 'user' → 'colaborador'
            DB::table('roles')->where('id', $userRole->id)->update([
                'name' => 'Colaborador',
                'slug' => 'colaborador',
                'description' => 'Miembro del equipo. Evaluación de riesgo completa.',
                'updated_at' => now(),
            ]);
            $colaboradorId = $userRole->id;
        } else {
            // Already renamed or doesn't exist
            $colaboradorId = DB::table('roles')->where('slug', 'colaborador')->value('id');
        }

        // Check if gestor already exists (from previous migration renaming supervisor)
        $gestorExists = DB::table('roles')->where('slug', 'gestor')->exists();

        if (!$gestorExists) {
            // Create the gestor role
            DB::table('roles')->insert([
                'name' => 'Gestor',
                'slug' => 'gestor',
                'description' => 'Gestor de proyectos. Bonificación de confianza en evaluación de riesgo.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $gestorId = DB::table('roles')->where('slug', 'gestor')->value('id');

        // Reassign orphan roles to colaborador/gestor then delete
        $orphanSlugs = ['coordinador', 'coordinator', 'revisor', 'supervisor', 'director', 'empleado'];
        $orphanIds = DB::table('roles')->whereIn('slug', $orphanSlugs)->pluck('id');

        if ($orphanIds->isNotEmpty() && $colaboradorId) {
            // Move orphan users to colaborador
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

            // Remove orphan assignments and roles
            DB::table('role_user')->whereIn('role_id', $orphanIds)->delete();
            DB::table('roles')->whereIn('id', $orphanIds)->delete();
        }
    }

    public function down(): void
    {
        // Reverse: rename colaborador back to user
        DB::table('roles')->where('slug', 'colaborador')->update([
            'name' => 'Usuario',
            'slug' => 'user',
            'description' => 'Usuario regular',
            'updated_at' => now(),
        ]);

        // Delete gestor if it was created by this migration
        DB::table('roles')->where('slug', 'gestor')->delete();
    }
};
