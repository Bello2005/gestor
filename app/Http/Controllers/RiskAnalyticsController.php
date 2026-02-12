<?php

namespace App\Http\Controllers;

use App\Models\Prorroga;
use App\Models\ResourceAccessRequest;
use App\Models\UserPermission;
use App\Services\ProjectVigilanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RiskAnalyticsController extends Controller
{
    public function __construct(
        private ProjectVigilanceService $vigilanceService
    ) {}

    /**
     * Dashboard principal — carga Tab 1 (Panel General) + Tab 3 (Riesgo) + alertas
     */
    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'general');
        $generalFilters = $request->only(['estado', 'criticidad']);
        $riskFilters = $request->only(['risk_level', 'status']);

        // Tab 1: Panel General
        $overviewKpis = $this->vigilanceService->getOverviewKpis($generalFilters);
        $healthMatrix = $this->vigilanceService->getProjectHealthMatrix($generalFilters);

        // Tab 3: Análisis de Riesgo (lógica existente)
        $riskData = $this->getRiskAnalyticsData($riskFilters);

        // Tab 4: Alertas (para badge + contenido)
        $alerts = $this->vigilanceService->getAlerts();
        $alertCounts = [
            'critico' => count($alerts['critico'] ?? []),
            'alto' => count($alerts['alto'] ?? []),
            'medio' => count($alerts['medio'] ?? []),
            'informativo' => count($alerts['informativo'] ?? []),
            'total' => array_sum(array_map('count', $alerts)),
        ];

        $pendingRequests = ResourceAccessRequest::with(['user', 'permission', 'proyecto'])
            ->where('status', 'pendiente')
            ->orderByDesc('risk_score')
            ->limit(20)
            ->get();

        // Tab 5: Prórrogas badge count
        $prorrogaCounts = [
            'pendiente' => Prorroga::where('estado', 'pendiente')->count(),
            'total'     => Prorroga::count(),
        ];

        return view('analytics.riesgo', array_merge(
            $overviewKpis,
            $riskData,
            compact('healthMatrix', 'activeTab', 'alerts', 'alertCounts', 'pendingRequests', 'prorrogaCounts')
        ));
    }

    /**
     * Endpoint HTML fragment para Tab 1 (Panel General) — filtros vía query
     */
    public function panelGeneral(Request $request)
    {
        $filters = $request->only(['estado', 'criticidad']);
        $overviewKpis = $this->vigilanceService->getOverviewKpis($filters);
        $healthMatrix = $this->vigilanceService->getProjectHealthMatrix($filters);

        return response(
            view('analytics.partials._panel_general', array_merge($overviewKpis, compact('healthMatrix')))->render()
        )->header('Content-Type', 'text/html; charset=UTF-8');
    }

    /**
     * Endpoint HTML fragment para Tab 3 (Análisis de Riesgo) — filtros vía query
     */
    public function riesgoData(Request $request)
    {
        $filters = $request->only(['risk_level', 'status']);
        $riskData = $this->getRiskAnalyticsData($filters);
        $pendingRequests = ResourceAccessRequest::with(['user', 'permission', 'proyecto'])
            ->where('status', 'pendiente')
            ->orderByDesc('risk_score')
            ->limit(20)
            ->get();

        return response(
            view('analytics.partials._analisis_riesgo', array_merge($riskData, compact('pendingRequests')))->render()
        )->header('Content-Type', 'text/html; charset=UTF-8');
    }

    /**
     * Endpoint JSON para Tab 2 (Seguimiento)
     */
    public function seguimiento(Request $request)
    {
        $filters = $request->only(['estado', 'criticidad']);
        $projects = $this->vigilanceService->getTrackingTable($filters);

        return response()->json([
            'projects' => $projects,
        ]);
    }

    /**
     * Endpoint HTML fragment para Tab 4 (Alertas)
     */
    public function alertas()
    {
        $alerts = $this->vigilanceService->getAlerts();

        return response(
            view('analytics.partials._alertas', compact('alerts'))->render()
        )->header('Content-Type', 'text/html; charset=UTF-8');
    }

    /**
     * Lógica existente de análisis de riesgo extraída a método privado
     */
    private function getRiskAnalyticsData(array $filters = []): array
    {
        $baseQuery = ResourceAccessRequest::query();
        if (!empty($filters['risk_level'])) {
            $baseQuery->where('risk_level', $filters['risk_level']);
        }
        if (!empty($filters['status'])) {
            $baseQuery->where('status', $filters['status']);
        }

        // KPIs
        $totalRequests = (clone $baseQuery)->count();
        $avgRiskScore = (int) (clone $baseQuery)->avg('risk_score');
        $autoApprovedCount = (clone $baseQuery)->whereHas('riskAuditLogs', fn($q) => $q->where('action', 'auto_approved'))->count();
        $autoApprovedPct = $totalRequests > 0 ? round(($autoApprovedCount / $totalRequests) * 100) : 0;
        $pendingHighRisk = (clone $baseQuery)->pending()->highRisk()->count();

        // Risk distribution (for donut chart)
        $riskDistribution = (clone $baseQuery)->select('risk_level', DB::raw('count(*) as total'))
            ->whereNotNull('risk_level')
            ->groupBy('risk_level')
            ->pluck('total', 'risk_level')
            ->toArray();

        // Most exposed projects (top 5 by active permissions)
        $exposedProjects = DB::table('user_permissions')
            ->join('proyectos', 'user_permissions.proyecto_id', '=', 'proyectos.id')
            ->where('user_permissions.is_active', true)
            ->select('proyectos.nombre_del_proyecto', 'proyectos.nivel_criticidad', DB::raw('count(*) as permission_count'))
            ->groupBy('proyectos.id', 'proyectos.nombre_del_proyecto', 'proyectos.nivel_criticidad')
            ->orderByDesc('permission_count')
            ->limit(5)
            ->get();

        // Average approval time by risk level (in hours)
        $approvalTimes = (clone $baseQuery)
            ->select(
                'risk_level',
                DB::raw('AVG(EXTRACT(EPOCH FROM (approved_at - created_at)) / 3600) as avg_hours')
            )
            ->where('status', 'aprobada')
            ->whereNotNull('approved_at')
            ->whereNotNull('risk_level')
            ->groupBy('risk_level')
            ->pluck('avg_hours', 'risk_level')
            ->map(fn($v) => round((float) $v, 1))
            ->toArray();

        // Top 5 users by permission accumulation
        $topAccumulators = DB::table('user_permissions')
            ->join('users', 'user_permissions.user_id', '=', 'users.id')
            ->where('user_permissions.is_active', true)
            ->where(function ($q) {
                $q->whereNull('user_permissions.expires_at')
                  ->orWhere('user_permissions.expires_at', '>', now());
            })
            ->select('users.full_name as name', 'users.email', DB::raw('count(*) as permission_count'))
            ->groupBy('users.id', 'users.full_name', 'users.email')
            ->orderByDesc('permission_count')
            ->limit(5)
            ->get();

        // Temporal vs permanent ratio
        $temporalCount = UserPermission::where('is_active', true)->where('is_temporary', true)->count();
        $permanentCount = UserPermission::where('is_active', true)->where('is_temporary', false)->count();

        // Monthly trend (last 6 months)
        $monthlyTrend = (clone $baseQuery)
            ->select(
                DB::raw("TO_CHAR(created_at, 'YYYY-MM') as month"),
                DB::raw('AVG(risk_score) as avg_score'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->whereNotNull('risk_score')
            ->groupBy(DB::raw("TO_CHAR(created_at, 'YYYY-MM')"))
            ->orderBy('month')
            ->get();

        return compact(
            'totalRequests', 'avgRiskScore', 'autoApprovedPct', 'pendingHighRisk',
            'riskDistribution', 'exposedProjects', 'approvalTimes', 'topAccumulators',
            'temporalCount', 'permanentCount', 'monthlyTrend'
        );
    }
}
