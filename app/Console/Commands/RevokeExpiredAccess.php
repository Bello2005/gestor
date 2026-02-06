<?php

namespace App\Console\Commands;

use App\Models\ResourceAccessRequest;
use App\Models\RiskAuditLog;
use App\Models\UserPermission;
use Illuminate\Console\Command;

class RevokeExpiredAccess extends Command
{
    protected $signature = 'access:revoke-expired';
    protected $description = 'Revoca permisos de acceso temporal que han expirado';

    public function handle(): int
    {
        $expired = UserPermission::expired()->with(['user', 'permission'])->get();

        if ($expired->isEmpty()) {
            $this->info('No hay permisos expirados para revocar.');
            return self::SUCCESS;
        }

        foreach ($expired as $up) {
            $up->update(['is_active' => false, 'revoked_at' => now()]);

            if ($up->resource_access_request_id) {
                ResourceAccessRequest::where('id', $up->resource_access_request_id)
                    ->where('status', 'aprobada')
                    ->update(['status' => 'expirada']);

                RiskAuditLog::create([
                    'resource_access_request_id' => $up->resource_access_request_id,
                    'action' => 'expired',
                    'details' => [
                        'user_id' => $up->user_id,
                        'user_name' => $up->user->name ?? 'N/A',
                        'permission' => $up->permission->slug ?? 'N/A',
                        'expired_at' => $up->expires_at?->toIso8601String(),
                    ],
                    'created_at' => now(),
                ]);
            }

            $this->info("Revocado: {$up->user->name} — {$up->permission->name}");
        }

        $this->info("Total revocados: {$expired->count()}");
        return self::SUCCESS;
    }
}
