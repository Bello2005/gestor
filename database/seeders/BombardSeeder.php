<?php

namespace Database\Seeders;

/**
 * Llena la base de datos con gran cantidad de datos de prueba.
 * Rutas de archivos (cargar_archivo_proyecto, cargar_contrato_o_convenio, cargar_evidencias) se dejan vacías.
 *
 * Uso:
 *   php artisan db:seed --class=BombardSeeder
 *
 * Con Neon: puede tardar varios minutos (red). Si fallan migraciones/seeds, usa conexión directa
 * (host sin "-pooler") en .env para migrate/seed. Desde Tinker: app()->call(\Database\Seeders\BombardSeeder::class . '@run');
 */
use App\Models\Permission;
use App\Models\Proyecto;
use App\Models\ResourceAccessRequest;
use App\Models\RiskAuditLog;
use App\Models\Role;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BombardSeeder extends Seeder
{
    private const NUM_USUARIOS = 80;
    private const NUM_PROYECTOS = 120;
    private const NUM_ACCESS_REQUESTS = 150;
    private const NUM_RESOURCE_ACCESS_REQUESTS = 200;
    private const NUM_AUDIT_LOGS = 300;
    private const NUM_USER_PERMISSIONS = 180;

    private array $userIds = [];
    private array $proyectoIds = [];
    private array $permissionIds = [];

    public function run(): void
    {
        $this->command->info('💣 Bombardeo de datos iniciado...');

        $this->ensurePermissions();
        $this->bombardUsers();
        $this->bombardProyectos();
        $this->bombardAccessRequests();
        $this->bombardResourceAccessRequests();
        $this->bombardRiskAuditLog();
        $this->bombardUserPermissions();
        $this->bombardAuditLog();

        $this->printSummary();
    }

    private function ensurePermissions(): void
    {
        $this->command->info('  🔐 Roles y permisos...');
        $this->call(RolesSeeder::class); // roles deben existir para PermissionSeeder
        $this->call(PermissionSeeder::class);
        $this->permissionIds = Permission::pluck('id')->all();
    }

    private function bombardUsers(): void
    {
        $this->command->info('  👥 Usuarios...');
        $existing = User::count();
        $nombres = $this->nombresColombianos();
        $dominios = ['uniclaretiana.edu.co', 'quantum.com', 'empresa.co', 'gobierno.gov.co', 'universidad.edu.co'];

        for ($i = 0; $i < self::NUM_USUARIOS; $i++) {
            $nombre = $nombres[array_rand($nombres)];
            $slug = \Illuminate\Support\Str::slug($nombre);
            $email = $slug . '.' . ($existing + $i + 1) . '@' . $dominios[array_rand($dominios)];

            if (User::where('email', $email)->exists()) {
                $email = $slug . '.b' . ($existing + $i + 1) . '@' . $dominios[array_rand($dominios)];
            }

            // Neon/DB real usa columna full_name (model User)
            $id = DB::table('users')->insertGetId([
                'full_name' => $nombre,
                'email' => $email,
                'password' => Hash::make('password'),
                'is_temporary_password' => (bool) random_int(0, 4),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->userIds[] = $id;
        }
    }

    private function bombardProyectos(): void
    {
        $this->command->info('  📊 Proyectos (rutas de archivo vacías)...');
        $entidades = [
            'Ministerio de Educación', 'Gobernación del Valle', 'Alcaldía de Cali', 'ICETEX',
            'SENA', 'Colciencias', 'Cámara de Comercio', 'Hospital Universitario del Valle',
            'Secretaría de Educación Municipal', 'Corporación Autónoma Regional',
            'Procuraduría General', 'Contraloría', 'Universidad del Valle', 'Uniclaretiana',
            'Empresa de Acueducto', 'ETM', 'Metrocali', 'Fondo de Desarrollo Local',
        ];
        $objetos = [
            'Desarrollo de software educativo', 'Plataforma e-learning', 'Sistema de información',
            'Capacitación docente', 'Investigación aplicada', 'Infraestructura tecnológica',
            'Digitalización de trámites', 'App móvil de servicios', 'Portal de transparencia',
            'Sistema de gestión documental', 'Red de sensores IoT', 'Comercio electrónico',
        ];
        $coberturas = ['Nacional', 'Regional', 'Departamental', 'Municipal', 'Local'];
        $estados = ['activo', 'activo', 'activo', 'inactivo', 'cerrado'];
        $criticidades = ['bajo', 'medio', 'medio', 'alto', 'critico'];
        $unidades = ['meses', 'meses', 'dias'];

        for ($i = 0; $i < self::NUM_PROYECTOS; $i++) {
            $plazo = (float) random_int(1, 24);
            $unidad = $unidades[array_rand($unidades)];
            $proyecto = Proyecto::create([
                'nombre_del_proyecto' => 'Proyecto ' . ($i + 1) . ' - ' . $objetos[array_rand($objetos)] . ' ' . substr(md5((string)$i), 0, 6),
                'objeto_contractual' => $objetos[array_rand($objetos)] . ' para ' . $entidades[array_rand($entidades)],
                'lineas_de_accion' => "Línea 1: Desarrollo\nLínea 2: Capacitación\nLínea 3: Seguimiento",
                'cobertura' => $coberturas[array_rand($coberturas)],
                'entidad_contratante' => $entidades[array_rand($entidades)],
                'fecha_de_ejecucion' => now()->subMonths(random_int(0, 12))->addDays(random_int(0, 30)),
                'plazo' => $plazo,
                'plazo_unidad' => $unidad,
                'valor_total' => (float) random_int(50, 800) * 1000000,
                'estado' => $estados[array_rand($estados)],
                'nivel_criticidad' => $criticidades[array_rand($criticidades)],
                'cargar_archivo_proyecto' => null,
                'cargar_contrato_o_convenio' => null,
                'cargar_evidencias' => [],
            ]);
            $this->proyectoIds[] = $proyecto->id;
        }
    }

    private function bombardAccessRequests(): void
    {
        $this->command->info('  📨 Solicitudes de acceso (access_requests)...');
        $statuses = ['pending', 'pending', 'approved', 'approved', 'rejected'];
        $nombres = $this->nombresColombianos();
        $reasons = [
            'Necesito acceso para elaborar informes del proyecto.',
            'Solicitud de acceso como responsable del seguimiento.',
            'Requiero consultar documentación contractual.',
            'Acceso temporal para auditoría interna.',
        ];

        for ($i = 0; $i < self::NUM_ACCESS_REQUESTS; $i++) {
            $nombre = $nombres[array_rand($nombres)];
            $slug = \Illuminate\Support\Str::slug($nombre);
            $email = $slug . '.req' . $i . '@solicitud-' . substr(md5((string)$i), 0, 8) . '.com';
            $status = $statuses[array_rand($statuses)];
            $row = [
                'name' => $nombre,
                'email' => $email,
                'phone' => '3' . random_int(1000000, 3999999),
                'reason' => $reasons[array_rand($reasons)],
                'status' => $status,
                'admin_comment' => in_array($status, ['approved', 'rejected']) ? 'Revisado por administración.' : null,
                'reviewed_at' => in_array($status, ['approved', 'rejected']) ? now()->subDays(random_int(1, 90)) : null,
                'created_at' => now()->subDays(random_int(0, 180)),
                'updated_at' => now(),
            ];
            DB::table('access_requests')->insert($row);
        }
    }

    private function bombardResourceAccessRequests(): void
    {
        $this->command->info('  🔑 Solicitudes de acceso a recursos...');
        $userIds = User::pluck('id')->all();
        $proyectoIds = Proyecto::pluck('id')->all();
        $permissionIds = $this->permissionIds;
        $statuses = ['pendiente', 'pendiente', 'aprobada', 'aprobada', 'rechazada', 'expirada', 'revocada'];
        $riskLevels = ['bajo', 'medio', 'medio', 'alto', 'critico'];
        $levels = ['lectura', 'edicion', 'crear', 'eliminar'];

        for ($i = 0; $i < self::NUM_RESOURCE_ACCESS_REQUESTS; $i++) {
            $userId = $userIds[array_rand($userIds)];
            $proyectoId = (random_int(0, 10) > 2) ? $proyectoIds[array_rand($proyectoIds)] : null;
            $status = $statuses[array_rand($statuses)];
            $startsAt = now()->subDays(random_int(0, 60));
            $expiresAt = $status === 'aprobada' ? $startsAt->copy()->addDays(random_int(30, 365)) : null;

            $request = ResourceAccessRequest::create([
                'user_id' => $userId,
                'permission_id' => $permissionIds[array_rand($permissionIds)],
                'proyecto_id' => $proyectoId,
                'requested_access_level' => $levels[array_rand($levels)],
                'justification' => 'Justificación de prueba para solicitud #' . ($i + 1) . '. Requerido para labores del proyecto.',
                'duration_type' => random_int(0, 1) ? 'temporal' : 'permanente',
                'starts_at' => $startsAt,
                'expires_at' => $expiresAt,
                'risk_score' => random_int(10, 95),
                'risk_level' => $riskLevels[array_rand($riskLevels)],
                'risk_factors' => ['antiguedad' => random_int(1, 5), 'rol' => 'colaborador'],
                'status' => $status,
                'requires_double_approval' => (bool) random_int(0, 3),
                'approved_by' => in_array($status, ['aprobada']) ? ($userIds[array_rand($userIds)] ?? null) : null,
                'approved_at' => in_array($status, ['aprobada']) ? now()->subDays(random_int(1, 30)) : null,
                'rejected_by' => $status === 'rechazada' ? ($userIds[array_rand($userIds)] ?? null) : null,
                'rejected_at' => $status === 'rechazada' ? now()->subDays(random_int(1, 20)) : null,
                'decision_rationale' => in_array($status, ['aprobada', 'rechazada']) ? 'Decisión registrada.' : null,
            ]);
        }
    }

    private function bombardRiskAuditLog(): void
    {
        $this->command->info('  📜 Risk audit log...');
        $requests = ResourceAccessRequest::limit(250)->get();
        $userIds = User::pluck('id')->all();
        $actions = ['score_calculated', 'manually_approved', 'rejected', 'second_approval', 'revoked', 'expired', 'auto_approved'];
        $riskLevels = ['bajo', 'medio', 'alto', 'critico'];

        foreach ($requests as $req) {
            if (random_int(0, 2) === 0) {
                continue;
            }
            $actorId = $userIds[array_rand($userIds)] ?? null;
            RiskAuditLog::create([
                'resource_access_request_id' => $req->id,
                'action' => $actions[array_rand($actions)],
                'actor_id' => $actorId,
                'actor_name' => $actorId ? User::find($actorId)?->name : 'Sistema',
                'risk_score_at_time' => random_int(15, 90),
                'risk_level_at_time' => $riskLevels[array_rand($riskLevels)],
                'details' => ['ip' => '127.0.0.' . random_int(1, 255), 'timestamp' => now()->toIso8601String()],
                'ip_address' => '192.168.1.' . random_int(1, 254),
                'created_at' => now()->subDays(random_int(0, 60)),
            ]);
        }
    }

    private function bombardUserPermissions(): void
    {
        $this->command->info('  ✅ User permissions...');
        $users = User::pluck('id')->all();
        $proyectos = Proyecto::pluck('id')->all();
        $permissionIds = $this->permissionIds;
        $requests = ResourceAccessRequest::where('status', 'aprobada')->pluck('id')->all();

        $created = 0;
        $max = self::NUM_USER_PERMISSIONS;
        while ($created < $max) {
            $userId = $users[array_rand($users)];
            $permissionId = $permissionIds[array_rand($permissionIds)];
            $proyectoId = (random_int(0, 5) > 1) ? $proyectos[array_rand($proyectos)] : null;

            $q = UserPermission::where('user_id', $userId)->where('permission_id', $permissionId);
            $q = $proyectoId === null ? $q->whereNull('proyecto_id') : $q->where('proyecto_id', $proyectoId);
            $exists = $q->exists();

            if (!$exists) {
                UserPermission::create([
                    'user_id' => $userId,
                    'permission_id' => $permissionId,
                    'proyecto_id' => $proyectoId,
                    'granted_by' => $users[array_rand($users)],
                    'resource_access_request_id' => !empty($requests) ? $requests[array_rand($requests)] : null,
                    'is_temporary' => (bool) random_int(0, 2),
                    'starts_at' => now()->subDays(random_int(0, 30)),
                    'expires_at' => random_int(0, 2) ? now()->addDays(random_int(30, 365)) : null,
                    'is_active' => true,
                ]);
                $created++;
            }
        }
    }

    private function bombardAuditLog(): void
    {
        $this->command->info('  📋 Audit log...');
        $operations = ['INSERT', 'UPDATE', 'UPDATE', 'DELETE'];
        $tables = ['proyectos', 'users', 'proyectos', 'resource_access_requests', 'user_permissions'];
        $userIds = User::pluck('id')->all();

        for ($i = 0; $i < self::NUM_AUDIT_LOGS; $i++) {
            $userId = $userIds[array_rand($userIds)] ?? null;
            $table = $tables[array_rand($tables)];
            DB::table('audit_log')->insert([
                'table_name' => $table,
                'operation' => $operations[array_rand($operations)],
                'record_id' => (string) random_int(1, 500),
                'old_values' => json_encode(['field' => 'old']),
                'new_values' => json_encode(['field' => 'new']),
                'changed_by' => $userId,
                'user_name' => $userId ? User::find($userId)?->name : null,
                'ip_address' => '10.0.0.' . random_int(1, 254),
                'created_at' => now()->subDays(random_int(0, 120)),
            ]);
        }
    }

    private function printSummary(): void
    {
        $this->command->newLine();
        $this->command->info('╔══════════════════════════════════════════════════════════╗');
        $this->command->info('║           💣 BOMBARDEO COMPLETADO                         ║');
        $this->command->info('╚══════════════════════════════════════════════════════════╝');
        $this->command->table(
            ['Tabla', 'Total'],
            [
                ['users', User::count()],
                ['proyectos', Proyecto::count()],
                ['roles', Role::count()],
                ['access_requests', DB::table('access_requests')->count()],
                ['resource_access_requests', ResourceAccessRequest::count()],
                ['risk_audit_log', RiskAuditLog::count()],
                ['user_permissions', UserPermission::count()],
                ['audit_log', DB::table('audit_log')->count()],
            ]
        );
        $this->command->info('Rutas de archivos en proyectos: vacías (null / []).');
        $this->command->info('Contraseña por defecto para usuarios: password');
    }

    private function nombresColombianos(): array
    {
        return [
            'Ana García', 'Carlos Rodríguez', 'María López', 'José Martínez', 'Laura Pérez',
            'Miguel Sánchez', 'Sofia Ramírez', 'Diego Torres', 'Valentina Flores', 'Santiago Gómez',
            'Isabella Díaz', 'Mateo Hernández', 'Lucía Moreno', 'Sebastián Jiménez', 'Emma Ruiz',
            'Nicolás Castro', 'Mariana Ortiz', 'Daniel Vargas', 'Camila Silva', 'Alejandro Rojas',
            'Victoria Mendoza', 'Andrés Guerrero', 'Sara Romero', 'Juan Pablo Núñez', 'Elena Medina',
            'Felipe Herrera', 'Adriana Suárez', 'Luis Fernando Cabrera', 'Carolina Reyes', 'Javier Soto',
            'Paula Sandoval', 'Camilo Lopera', 'Natalia Restrepo', 'Oscar Giraldo', 'Diana Zapata',
            'Ricardo Ospina', 'Claudia Velásquez', 'Gustavo Mejía', 'Andrea Cardona', 'Fernando Ríos',
            'Lina Henao', 'Pablo Arango', 'Catalina Montoya', 'Esteban Quintero', 'Daniela Escobar',
        ];
    }
}
