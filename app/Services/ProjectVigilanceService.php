<?php

namespace App\Services;

use App\Models\Prorroga;
use App\Models\Proyecto;
use App\Models\ResourceAccessRequest;
use App\Models\UserPermission;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProjectVigilanceService
{
    /**
     * KPIs del Panel General (Tab 1)
     */
    public function getOverviewKpis(array $filters = []): array
    {
        $query = Proyecto::query();
        if (!empty($filters['estado'])) {
            $query->where('estado', $filters['estado']);
        } else {
            $query->whereIn('estado', ['activo', 'cerrado', 'inactivo']);
        }
        if (!empty($filters['criticidad'])) {
            $query->where('nivel_criticidad', $filters['criticidad']);
        }

        $totalProjects = $query->count();
        $activeProjects = (clone $query)->where('estado', 'activo')->count();

        $activeProjectsList = (clone $query)->where('estado', 'activo')->get();
        $lastUploads = $this->getLastEvidenceUploads($activeProjectsList->pluck('id'));

        // Proyectos activos sin evidencias actualizadas en >30 días
        $overdueDocumentCount = 0;
        $upcomingDeadlines = 0;
        $healthScores = [];

        foreach ($activeProjectsList as $project) {
            $daysSinceUpload = $this->computeDaysSinceUpload($project, $lastUploads);
            $docSemaforo = $this->computeDocumentSemaforo($daysSinceUpload);
            $timeMetrics = $this->calculateTimeMetrics($project);
            $timelineSemaforo = $this->computeTimelineSemaforo($timeMetrics);
            $riskSemaforo = $this->computeRiskSemaforo($project->nivel_criticidad);

            if ($docSemaforo !== 'verde') {
                $overdueDocumentCount++;
            }

            if ($timeMetrics['days_remaining'] !== null && $timeMetrics['days_remaining'] >= 0 && $timeMetrics['days_remaining'] <= 30) {
                $upcomingDeadlines++;
            }

            $healthScores[] = $this->computeHealthScore($docSemaforo, $timelineSemaforo, $riskSemaforo);
        }

        $avgHealthScore = count($healthScores) > 0 ? (int) round(array_sum($healthScores) / count($healthScores)) : 0;

        return compact('totalProjects', 'activeProjects', 'overdueDocumentCount', 'upcomingDeadlines', 'avgHealthScore');
    }

    /**
     * Matriz de salud de proyectos activos (Tab 1)
     */
    public function getProjectHealthMatrix(array $filters = []): Collection
    {
        $query = Proyecto::query();
        if (!empty($filters['estado'])) {
            $query->where('estado', $filters['estado']);
        } else {
            $query->whereIn('estado', ['activo', 'cerrado', 'inactivo']);
        }
        if (!empty($filters['criticidad'])) {
            $query->where('nivel_criticidad', $filters['criticidad']);
        }

        $projects = $query->orderBy('nombre_del_proyecto')->get();

        $lastUploads = $this->getLastEvidenceUploads($projects->pluck('id'));

        return $projects->map(function ($project) use ($lastUploads) {
            $daysSinceUpload = $this->computeDaysSinceUpload($project, $lastUploads);
            $docSemaforo = $this->computeDocumentSemaforo($daysSinceUpload);
            $timeMetrics = $this->calculateTimeMetrics($project);
            $timelineSemaforo = $this->computeTimelineSemaforo($timeMetrics);
            $riskSemaforo = $this->computeRiskSemaforo($project->nivel_criticidad);
            $healthScore = $this->computeHealthScore($docSemaforo, $timelineSemaforo, $riskSemaforo);

            $project->document_semaforo = $docSemaforo;
            $project->timeline_semaforo = $timelineSemaforo;
            $project->risk_semaforo = $riskSemaforo;
            $project->health_score = $healthScore;
            $project->days_since_upload = $daysSinceUpload;
            $project->time_elapsed_pct = $timeMetrics['time_elapsed_pct'];
            $project->days_remaining = $timeMetrics['days_remaining'];

            return $project;
        })->sortBy('health_score')->values();
    }

    /**
     * Tabla de seguimiento detallado (Tab 2) — retorna array para JSON
     */
    public function getTrackingTable(array $filters = []): array
    {
        $query = Proyecto::query();

        if (!empty($filters['estado'])) {
            $query->where('estado', $filters['estado']);
        } else {
            $query->whereIn('estado', ['activo', 'cerrado', 'inactivo']);
        }

        if (!empty($filters['criticidad'])) {
            $query->where('nivel_criticidad', $filters['criticidad']);
        }

        $projects = $query->orderBy('nombre_del_proyecto')->get();
        $lastUploads = $this->getLastEvidenceUploads($projects->pluck('id'));
        $lastUploadDates = $this->getLastEvidenceUploadDates($projects->pluck('id'));

        return $projects->map(function ($project) use ($lastUploads, $lastUploadDates) {
            $daysSinceUpload = $this->computeDaysSinceUpload($project, $lastUploads);
            $timeMetrics = $this->calculateTimeMetrics($project);
            $evidencias = $project->cargar_evidencias;
            $evidenceCount = is_array($evidencias) ? count($evidencias) : 0;
            $lastUploadDate = $lastUploadDates->get((string) $project->id);

            $docSemaforo = $this->computeDocumentSemaforo($daysSinceUpload);
            $timelineSemaforo = $this->computeTimelineSemaforo($timeMetrics);
            $riskSemaforo = $this->computeRiskSemaforo($project->nivel_criticidad);

            $worstSemaforo = $this->worstSemaforo([$docSemaforo, $timelineSemaforo, $riskSemaforo]);

            $plazoUnit = $project->plazo_unidad ?? 'meses';
            $plazoDisplay = $project->plazo
                ? ((int) $project->plazo) . ' ' . ($plazoUnit === 'dias' ? 'días' : 'meses')
                : 'N/A';

            $prorrogaDias = (int) ($project->prorroga_dias_aprobados ?? 0);

            return [
                'id' => $project->id,
                'nombre' => $project->nombre_del_proyecto,
                'entidad' => $project->entidad_contratante,
                'estado' => $project->estado,
                'criticidad' => $project->nivel_criticidad ?? 'medio',
                'plazo_display' => $plazoDisplay,
                'time_elapsed_pct' => $timeMetrics['time_elapsed_pct'],
                'days_remaining' => $timeMetrics['days_remaining'],
                'is_overdue' => $timeMetrics['is_overdue'],
                'evidence_count' => $evidenceCount,
                'has_contract' => !empty($project->cargar_contrato_o_convenio),
                'has_project_file' => !empty($project->cargar_archivo_proyecto),
                'last_upload_date' => $lastUploadDate ? Carbon::parse($lastUploadDate)->format('d/m/Y') : null,
                'days_since_upload' => $daysSinceUpload,
                'compliance' => $worstSemaforo,
                'url' => route('proyectos.show', $project->id),
                'has_prorroga' => $prorrogaDias > 0,
                'prorroga_dias_aprobados' => $prorrogaDias,
                'fecha_fin_original' => $project->fecha_fin_original?->format('d/m/Y'),
                'fecha_fin_ajustada' => $prorrogaDias > 0 ? $project->fecha_fin?->format('d/m/Y') : null,
            ];
        })->toArray();
    }

    /**
     * Alertas agrupadas por severidad (Tab 4)
     */
    public function getAlerts(): array
    {
        $alerts = [
            'critico' => [],
            'alto' => [],
            'medio' => [],
            'informativo' => [],
        ];

        $activeProjects = Proyecto::where('estado', 'activo')->get();
        $lastUploads = $this->getLastEvidenceUploads($activeProjects->pluck('id'));

        foreach ($activeProjects as $project) {
            $timeMetrics = $this->calculateTimeMetrics($project);
            $daysSinceUpload = $this->computeDaysSinceUpload($project, $lastUploads);

            // CRITICO: Proyecto vencido
            if ($timeMetrics['is_overdue']) {
                $alerts['critico'][] = [
                    'type' => 'proyecto_vencido',
                    'severity' => 'critico',
                    'title' => 'Proyecto vencido',
                    'message' => "El proyecto \"{$project->nombre_del_proyecto}\" ha superado su plazo de ejecución.",
                    'proyecto_id' => $project->id,
                    'proyecto_nombre' => $project->nombre_del_proyecto,
                    'icon' => 'alert-triangle',
                    'action_url' => route('proyectos.show', $project->id),
                    'action_label' => 'Ver proyecto',
                ];
            }

            // CRITICO: Sin contrato ni archivo de proyecto
            if (empty($project->cargar_contrato_o_convenio) && empty($project->cargar_archivo_proyecto)) {
                $alerts['critico'][] = [
                    'type' => 'sin_documentos_base',
                    'severity' => 'critico',
                    'title' => 'Sin documentos base',
                    'message' => "El proyecto \"{$project->nombre_del_proyecto}\" no tiene contrato ni archivo de proyecto cargado.",
                    'proyecto_id' => $project->id,
                    'proyecto_nombre' => $project->nombre_del_proyecto,
                    'icon' => 'file-x',
                    'action_url' => route('proyectos.edit', $project->id),
                    'action_label' => 'Subir documentos',
                ];
            }

            // ALTO: Sin evidencias >60 días
            if ($daysSinceUpload !== null && $daysSinceUpload > 60) {
                $alerts['alto'][] = [
                    'type' => 'evidencias_muy_desactualizadas',
                    'severity' => 'alto',
                    'title' => 'Evidencias muy desactualizadas',
                    'message' => "El proyecto \"{$project->nombre_del_proyecto}\" no actualiza evidencias hace {$daysSinceUpload} días.",
                    'proyecto_id' => $project->id,
                    'proyecto_nombre' => $project->nombre_del_proyecto,
                    'icon' => 'file-warning',
                    'action_url' => route('proyectos.edit', $project->id),
                    'action_label' => 'Actualizar',
                ];
            } elseif ($daysSinceUpload !== null && $daysSinceUpload > 30 && $daysSinceUpload <= 60) {
                // MEDIO: Sin evidencias 30-60 días
                $alerts['medio'][] = [
                    'type' => 'evidencias_desactualizadas',
                    'severity' => 'medio',
                    'title' => 'Evidencias desactualizadas',
                    'message' => "El proyecto \"{$project->nombre_del_proyecto}\" no actualiza evidencias hace {$daysSinceUpload} días.",
                    'proyecto_id' => $project->id,
                    'proyecto_nombre' => $project->nombre_del_proyecto,
                    'icon' => 'file-clock',
                    'action_url' => route('proyectos.edit', $project->id),
                    'action_label' => 'Actualizar',
                ];
            } elseif ($daysSinceUpload === null) {
                // Sin evidencias nunca subidas
                $evidencias = $project->cargar_evidencias;
                $hasEvidencias = is_array($evidencias) && count($evidencias) > 0;
                if (!$hasEvidencias) {
                    $daysSinceCreation = $project->created_at ? $project->created_at->diffInDays(now()) : 0;
                    if ($daysSinceCreation <= 7) {
                        $alerts['informativo'][] = [
                            'type' => 'proyecto_nuevo_sin_docs',
                            'severity' => 'informativo',
                            'title' => 'Proyecto nuevo sin evidencias',
                            'message' => "El proyecto \"{$project->nombre_del_proyecto}\" fue creado recientemente y aún no tiene evidencias.",
                            'proyecto_id' => $project->id,
                            'proyecto_nombre' => $project->nombre_del_proyecto,
                            'icon' => 'info',
                            'action_url' => route('proyectos.edit', $project->id),
                            'action_label' => 'Agregar',
                        ];
                    } else {
                        $alerts['alto'][] = [
                            'type' => 'sin_evidencias',
                            'severity' => 'alto',
                            'title' => 'Sin evidencias',
                            'message' => "El proyecto \"{$project->nombre_del_proyecto}\" no tiene evidencias cargadas.",
                            'proyecto_id' => $project->id,
                            'proyecto_nombre' => $project->nombre_del_proyecto,
                            'icon' => 'file-x',
                            'action_url' => route('proyectos.edit', $project->id),
                            'action_label' => 'Subir evidencias',
                        ];
                    }
                }
            }

            // ALTO: Plazo <15 días
            if ($timeMetrics['days_remaining'] !== null && $timeMetrics['days_remaining'] >= 0 && $timeMetrics['days_remaining'] <= 15 && !$timeMetrics['is_overdue']) {
                $alerts['alto'][] = [
                    'type' => 'plazo_muy_proximo',
                    'severity' => 'alto',
                    'title' => 'Plazo muy próximo',
                    'message' => "El proyecto \"{$project->nombre_del_proyecto}\" vence en {$timeMetrics['days_remaining']} días.",
                    'proyecto_id' => $project->id,
                    'proyecto_nombre' => $project->nombre_del_proyecto,
                    'icon' => 'clock',
                    'action_url' => route('proyectos.show', $project->id),
                    'action_label' => 'Ver proyecto',
                ];
            } elseif ($timeMetrics['days_remaining'] !== null && $timeMetrics['days_remaining'] > 15 && $timeMetrics['days_remaining'] <= 30) {
                // MEDIO: Plazo <30 días
                $alerts['medio'][] = [
                    'type' => 'plazo_proximo',
                    'severity' => 'medio',
                    'title' => 'Plazo próximo',
                    'message' => "El proyecto \"{$project->nombre_del_proyecto}\" vence en {$timeMetrics['days_remaining']} días.",
                    'proyecto_id' => $project->id,
                    'proyecto_nombre' => $project->nombre_del_proyecto,
                    'icon' => 'clock',
                    'action_url' => route('proyectos.show', $project->id),
                    'action_label' => 'Ver proyecto',
                ];
            }
        }

        // Prórrogas pendientes de aprobación
        $pendingProrrogas = Prorroga::where('estado', 'pendiente')
            ->with('proyecto:id,nombre_del_proyecto')
            ->get();

        foreach ($pendingProrrogas as $prorroga) {
            $alerts['medio'][] = [
                'type' => 'prorroga_pendiente',
                'severity' => 'medio',
                'title' => 'Solicitud de prórroga pendiente',
                'message' => "Prórroga de {$prorroga->dias_solicitados} días para \"{$prorroga->proyecto->nombre_del_proyecto}\" pendiente de aprobación.",
                'proyecto_id' => $prorroga->proyecto_id,
                'proyecto_nombre' => $prorroga->proyecto->nombre_del_proyecto,
                'icon' => 'clock',
                'action_url' => route('analytics.riesgo') . '?tab=seguimiento',
                'action_label' => 'Revisar',
            ];
        }

        // Solicitudes de riesgo pendientes
        $pendingCritical = ResourceAccessRequest::pending()
            ->where('risk_level', 'critico')
            ->with('user', 'proyecto')
            ->get();

        foreach ($pendingCritical as $request) {
            $alerts['critico'][] = [
                'type' => 'solicitud_critica_pendiente',
                'severity' => 'critico',
                'title' => 'Solicitud de riesgo crítico pendiente',
                'message' => "Solicitud de {$request->user->name} con score de riesgo {$request->risk_score} requiere aprobación.",
                'proyecto_id' => $request->proyecto_id,
                'proyecto_nombre' => $request->proyecto?->nombre_del_proyecto ?? 'Global',
                'icon' => 'shield-alert',
                'action_url' => route('solicitudes-acceso.show', $request->id),
                'action_label' => 'Revisar',
            ];
        }

        $pendingHigh = ResourceAccessRequest::pending()
            ->where('risk_level', 'alto')
            ->with('user', 'proyecto')
            ->get();

        foreach ($pendingHigh as $request) {
            $alerts['alto'][] = [
                'type' => 'solicitud_alta_pendiente',
                'severity' => 'alto',
                'title' => 'Solicitud de riesgo alto pendiente',
                'message' => "Solicitud de {$request->user->name} con score de riesgo {$request->risk_score} requiere aprobación.",
                'proyecto_id' => $request->proyecto_id,
                'proyecto_nombre' => $request->proyecto?->nombre_del_proyecto ?? 'Global',
                'icon' => 'shield-alert',
                'action_url' => route('solicitudes-acceso.show', $request->id),
                'action_label' => 'Revisar',
            ];
        }

        // Permisos expirando pronto (<7 días)
        $expiringPermissions = UserPermission::where('is_active', true)
            ->where('is_temporary', true)
            ->whereNotNull('expires_at')
            ->where('expires_at', '>', now())
            ->where('expires_at', '<=', now()->addDays(7))
            ->with('user', 'permission')
            ->get();

        foreach ($expiringPermissions as $perm) {
            $daysLeft = (int) now()->diffInDays($perm->expires_at);
            $alerts['medio'][] = [
                'type' => 'permiso_por_expirar',
                'severity' => 'medio',
                'title' => 'Permiso por expirar',
                'message' => "El permiso \"{$perm->permission->name}\" de {$perm->user->name} expira en {$daysLeft} días.",
                'proyecto_id' => $perm->proyecto_id,
                'proyecto_nombre' => null,
                'icon' => 'key',
                'action_url' => route('solicitudes-acceso.index'),
                'action_label' => 'Ver permisos',
            ];
        }

        return $alerts;
    }

    // ─── Helpers internos ───────────────────────────────────────

    /**
     * Obtener fechas de última subida de evidencias en batch desde audit_log
     */
    private function getLastEvidenceUploads(Collection $projectIds): Collection
    {
        if ($projectIds->isEmpty()) {
            return collect();
        }

        return DB::table('audit_log')
            ->select('record_id', DB::raw('MAX(created_at) as last_upload'))
            ->where('table_name', 'proyectos')
            ->where('operation', 'UPDATE')
            ->whereIn('record_id', $projectIds->map(fn($id) => (string) $id)->toArray())
            ->whereRaw("new_values::text LIKE '%cargar_evidencias%'")
            ->groupBy('record_id')
            ->pluck('last_upload', 'record_id');
    }

    /**
     * Obtener fechas formateadas de última subida (para mostrar en tabla)
     */
    private function getLastEvidenceUploadDates(Collection $projectIds): Collection
    {
        return $this->getLastEvidenceUploads($projectIds);
    }

    /**
     * Calcular días desde última subida de evidencias para un proyecto
     */
    private function computeDaysSinceUpload(Proyecto $project, Collection $lastUploads): ?int
    {
        $lastUpload = $lastUploads->get((string) $project->id);

        if ($lastUpload) {
            return (int) Carbon::parse($lastUpload)->diffInDays(now());
        }

        // Fallback: si tiene evidencias, usar updated_at del proyecto
        $evidencias = $project->cargar_evidencias;
        if (is_array($evidencias) && count($evidencias) > 0) {
            return (int) $project->updated_at->diffInDays(now());
        }

        return null; // Nunca ha subido evidencias
    }

    /**
     * Semáforo de cumplimiento documental
     */
    private function computeDocumentSemaforo(?int $daysSinceUpload): string
    {
        if ($daysSinceUpload === null) {
            return 'rojo';
        }
        if ($daysSinceUpload <= 30) {
            return 'verde';
        }
        if ($daysSinceUpload <= 60) {
            return 'amarillo';
        }
        return 'rojo';
    }

    /**
     * Calcular métricas de tiempo del proyecto (usa accessor que incluye prórrogas aprobadas)
     */
    private function calculateTimeMetrics(Proyecto $project): array
    {
        $startDate = $project->fecha_de_ejecucion;
        $endDate = $project->fecha_fin; // Accessor: plazo base + prorroga_dias_aprobados

        if (!$startDate || !$endDate) {
            return [
                'time_elapsed_pct' => 0,
                'days_remaining' => null,
                'end_date' => null,
                'is_overdue' => false,
            ];
        }

        $totalDays = $startDate->diffInDays($endDate);
        $elapsedDays = $startDate->diffInDays(now());
        $isOverdue = now()->gt($endDate);
        $daysRemaining = $isOverdue ? -(int) $endDate->diffInDays(now()) : (int) now()->diffInDays($endDate);
        $timeElapsedPct = $totalDays > 0 ? round(min(($elapsedDays / $totalDays) * 100, 100), 1) : 0;

        return [
            'time_elapsed_pct' => $timeElapsedPct,
            'days_remaining' => $daysRemaining,
            'end_date' => $endDate,
            'is_overdue' => $isOverdue,
        ];
    }

    /**
     * Semáforo de cumplimiento de timeline
     */
    private function computeTimelineSemaforo(array $timeMetrics): string
    {
        if ($timeMetrics['is_overdue']) {
            return 'rojo';
        }
        if ($timeMetrics['days_remaining'] === null) {
            return 'verde'; // Sin datos de plazo, no penalizar
        }
        $pct = $timeMetrics['time_elapsed_pct'];
        if ($pct < 70) {
            return 'verde';
        }
        if ($pct <= 90) {
            return 'amarillo';
        }
        return 'rojo';
    }

    /**
     * Semáforo basado en nivel de criticidad/riesgo del proyecto
     */
    private function computeRiskSemaforo(?string $criticidad): string
    {
        return match ($criticidad) {
            'bajo' => 'verde',
            'medio' => 'amarillo',
            'alto', 'critico' => 'rojo',
            default => 'amarillo',
        };
    }

    /**
     * Calcular health score ponderado
     * Timeline 40%, Documentos 35%, Riesgo 25%
     */
    private function computeHealthScore(string $docSemaforo, string $timelineSemaforo, string $riskSemaforo): int
    {
        $scores = ['verde' => 100, 'amarillo' => 60, 'rojo' => 20];

        $docScore = $scores[$docSemaforo] ?? 50;
        $timeScore = $scores[$timelineSemaforo] ?? 50;
        $riskScore = $scores[$riskSemaforo] ?? 50;

        return (int) round(
            $timeScore * 0.40 +
            $docScore * 0.35 +
            $riskScore * 0.25
        );
    }

    /**
     * Retorna el peor semáforo de un array
     */
    private function worstSemaforo(array $semaforos): string
    {
        $order = ['rojo' => 0, 'amarillo' => 1, 'verde' => 2];
        $worst = 'verde';
        foreach ($semaforos as $s) {
            if (($order[$s] ?? 2) < ($order[$worst] ?? 2)) {
                $worst = $s;
            }
        }
        return $worst;
    }
}
