<?php

namespace App\Console\Commands;

use App\Models\UserPermission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class NotifyExpiringAccess extends Command
{
    protected $signature = 'access:notify-expiring {--days=3 : Días antes de la expiración}';
    protected $description = 'Notifica sobre permisos de acceso que están por vencer';

    public function handle(): int
    {
        $days = (int) $this->option('days');

        $expiring = UserPermission::active()
            ->where('is_temporary', true)
            ->whereBetween('expires_at', [now(), now()->addDays($days)])
            ->with(['user', 'permission', 'proyecto'])
            ->get();

        if ($expiring->isEmpty()) {
            $this->info("No hay permisos por vencer en los próximos {$days} días.");
            return self::SUCCESS;
        }

        foreach ($expiring as $up) {
            $proyecto = $up->proyecto ? " ({$up->proyecto->nombre_del_proyecto})" : '';
            $mensaje = "Acceso por vencer: {$up->user->name} — {$up->permission->name}{$proyecto} — expira {$up->expires_at->diffForHumans()}";

            Log::warning($mensaje);
            $this->warn($mensaje);
        }

        $this->info("Total por vencer en {$days} días: {$expiring->count()}");
        return self::SUCCESS;
    }
}
