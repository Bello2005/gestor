<?php

namespace App\Http\Controllers;

use App\Models\ResourceAccessRequest;
use App\Models\UserPermission;
use Illuminate\Support\Facades\DB;

class RiskAnalyticsController extends Controller
{
    public function index()
    {
        // KPIs
        $totalRequests = ResourceAccessRequest::count();
        $avgRiskScore = (int) ResourceAccessRequest::avg('risk_score');
        $autoApprovedCount = ResourceAccessRequest::whereHas('riskAuditLogs', fn($q) => $q->where('action', 'auto_approved'))->count();
        $autoApprovedPct = $totalRequests > 0 ? round(($autoApprovedCount / $totalRequests) * 100) : 0;
        $pendingHighRisk = ResourceAccessRequest::pending()->highRisk()->count();

        // Risk distribution (for donut chart)
        $riskDistribution = ResourceAccessRequest::select('risk_level', DB::raw('count(*) as total'))
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
        $approvalTimes = ResourceAccessRequest::select(
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
        $monthlyTrend = ResourceAccessRequest::select(
                DB::raw("TO_CHAR(created_at, 'YYYY-MM') as month"),
                DB::raw('AVG(risk_score) as avg_score'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->whereNotNull('risk_score')
            ->groupBy(DB::raw("TO_CHAR(created_at, 'YYYY-MM')"))
            ->orderBy('month')
            ->get();

        return view('analytics.riesgo', compact(
            'totalRequests', 'avgRiskScore', 'autoApprovedPct', 'pendingHighRisk',
            'riskDistribution', 'exposedProjects', 'approvalTimes', 'topAccumulators',
            'temporalCount', 'permanentCount', 'monthlyTrend'
        ));
    }
}
