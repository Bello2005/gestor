<?php

namespace App\Services;

use App\Models\Permission;
use App\Models\Proyecto;
use App\Models\ResourceAccessRequest;
use App\Models\User;
use App\Models\UserPermission;

class RiskScoringService
{
    // Factor weights (must sum to 1.0)
    private const WEIGHT_PERMISSION   = 0.25;
    private const WEIGHT_CRITICALITY  = 0.25;
    private const WEIGHT_DURATION     = 0.20;
    private const WEIGHT_USER_TRUST   = 0.15;
    private const WEIGHT_ACCUMULATION = 0.15;

    /**
     * Check if a user should bypass risk scoring entirely (e.g., admins).
     */
    public function shouldBypassScoring(User $user): bool
    {
        return $user->isExemptFromAccessRequests();
    }

    /**
     * Calculate the risk score for a resource access request.
     *
     * @return array{score: int, level: string, factors: array}
     */
    public function calculateScore(ResourceAccessRequest $request): array
    {
        $user = $request->user ?? User::find($request->user_id);
        $permission = $request->permission ?? Permission::find($request->permission_id);
        $proyecto = $request->proyecto_id ? ($request->proyecto ?? Proyecto::find($request->proyecto_id)) : null;

        $f1 = $this->calculatePermissionRisk($permission);
        $f2 = $this->calculateResourceCriticality($proyecto);
        $f3 = $this->calculateDurationRisk($request);
        $f4 = $this->calculateUserTrustScore($user);
        $f5 = $this->calculateAccumulationRisk($user);

        $score = (int) round(
            ($f1['score'] * self::WEIGHT_PERMISSION) +
            ($f2['score'] * self::WEIGHT_CRITICALITY) +
            ($f3['score'] * self::WEIGHT_DURATION) +
            ($f4['score'] * self::WEIGHT_USER_TRUST) +
            ($f5['score'] * self::WEIGHT_ACCUMULATION)
        );

        $score = max(0, min(100, $score));
        $level = $this->classifyRiskLevel($score);

        return [
            'score' => $score,
            'level' => $level,
            'factors' => [
                'permission_risk' => [
                    'score' => $f1['score'],
                    'weight' => self::WEIGHT_PERMISSION,
                    'weighted' => round($f1['score'] * self::WEIGHT_PERMISSION, 1),
                    'label' => 'Riesgo del permiso',
                    'detail' => $f1['detail'],
                ],
                'resource_criticality' => [
                    'score' => $f2['score'],
                    'weight' => self::WEIGHT_CRITICALITY,
                    'weighted' => round($f2['score'] * self::WEIGHT_CRITICALITY, 1),
                    'label' => 'Criticidad del recurso',
                    'detail' => $f2['detail'],
                ],
                'duration_risk' => [
                    'score' => $f3['score'],
                    'weight' => self::WEIGHT_DURATION,
                    'weighted' => round($f3['score'] * self::WEIGHT_DURATION, 1),
                    'label' => 'Riesgo por duración',
                    'detail' => $f3['detail'],
                ],
                'user_trust' => [
                    'score' => $f4['score'],
                    'weight' => self::WEIGHT_USER_TRUST,
                    'weighted' => round($f4['score'] * self::WEIGHT_USER_TRUST, 1),
                    'label' => 'Confianza del usuario',
                    'detail' => $f4['detail'],
                ],
                'accumulation' => [
                    'score' => $f5['score'],
                    'weight' => self::WEIGHT_ACCUMULATION,
                    'weighted' => round($f5['score'] * self::WEIGHT_ACCUMULATION, 1),
                    'label' => 'Acumulación de permisos',
                    'detail' => $f5['detail'],
                ],
            ],
        ];
    }

    /**
     * Determine the approval flow based on risk level.
     *
     * @return array{auto_approve: bool, requires_double_approval: bool, max_duration_days: ?int}
     */
    public function determineApprovalFlow(string $riskLevel): array
    {
        return match ($riskLevel) {
            'bajo' => [
                'auto_approve' => true,
                'requires_double_approval' => false,
                'max_duration_days' => null,
            ],
            'medio' => [
                'auto_approve' => false,
                'requires_double_approval' => false,
                'max_duration_days' => null,
            ],
            'alto' => [
                'auto_approve' => false,
                'requires_double_approval' => true,
                'max_duration_days' => null,
            ],
            'critico' => [
                'auto_approve' => false,
                'requires_double_approval' => true,
                'max_duration_days' => 90,
            ],
            default => [
                'auto_approve' => false,
                'requires_double_approval' => false,
                'max_duration_days' => null,
            ],
        };
    }

    // --- Factor 1: Permission Risk Weight (25%) ---
    private function calculatePermissionRisk(?Permission $permission): array
    {
        if (!$permission) {
            return ['score' => 50, 'detail' => 'Permiso no encontrado'];
        }

        $score = match ($permission->risk_weight) {
            1 => 10,
            2 => 30,
            3 => 50,
            4 => 75,
            5 => 100,
            default => 50,
        };

        $levelLabel = match ($permission->risk_weight) {
            1 => 'Solo lectura',
            2 => 'Creación/Exportación',
            3 => 'Edición',
            4 => 'Eliminación',
            5 => 'Administración',
            default => 'Desconocido',
        };

        return [
            'score' => $score,
            'detail' => "{$permission->name} — Nivel: {$levelLabel} (peso {$permission->risk_weight}/5)",
        ];
    }

    // --- Factor 2: Resource Criticality (25%) ---
    private function calculateResourceCriticality(?Proyecto $proyecto): array
    {
        if (!$proyecto) {
            return ['score' => 40, 'detail' => 'Sin proyecto específico — Criticidad media por defecto'];
        }

        $criticality = $proyecto->nivel_criticidad ?? 'medio';

        $score = match ($criticality) {
            'bajo' => 15,
            'medio' => 40,
            'alto' => 70,
            'critico' => 100,
            default => 40,
        };

        return [
            'score' => $score,
            'detail' => "Proyecto: {$proyecto->nombre_del_proyecto} — Criticidad: {$criticality}",
        ];
    }

    // --- Factor 3: Access Duration (20%) ---
    private function calculateDurationRisk(ResourceAccessRequest $request): array
    {
        if ($request->duration_type === 'permanente') {
            return ['score' => 100, 'detail' => 'Acceso permanente — Riesgo máximo por duración'];
        }

        if (!$request->starts_at || !$request->expires_at) {
            return ['score' => 50, 'detail' => 'Duración temporal sin fechas definidas'];
        }

        $days = $request->starts_at->diffInDays($request->expires_at);

        $score = match (true) {
            $days <= 7   => 10,
            $days <= 30  => 30,
            $days <= 90  => 50,
            $days <= 180 => 70,
            default      => 85,
        };

        return [
            'score' => $score,
            'detail' => "Duración temporal: {$days} días",
        ];
    }

    // --- Factor 4: User Trust Score (15%) ---
    private function calculateUserTrustScore(?User $user): array
    {
        if (!$user) {
            return ['score' => 80, 'detail' => 'Usuario no encontrado — Alta desconfianza'];
        }

        // Account age component
        $accountDays = $user->created_at->diffInDays(now());
        $ageScore = match (true) {
            $accountDays < 30  => 80,
            $accountDays < 90  => 50,
            $accountDays < 180 => 30,
            default            => 10,
        };

        // Previous rejections penalty
        $rejections = ResourceAccessRequest::where('user_id', $user->id)
            ->where('status', 'rechazada')
            ->count();
        $rejectionPenalty = match (true) {
            $rejections === 0 => 0,
            $rejections === 1 => 15,
            $rejections === 2 => 25,
            default           => 40,
        };

        // Previous revocations penalty
        $revocations = ResourceAccessRequest::where('user_id', $user->id)
            ->where('status', 'revocada')
            ->count();
        $revocationPenalty = min($revocations * 20, 60);

        $score = min($ageScore + $rejectionPenalty + $revocationPenalty, 100);

        // Role-based trust bonus (gestores get -15)
        $roleTrustBonus = $user->getRoleTrustBonus();
        $score = max(0, min($score + $roleTrustBonus, 100));

        $details = [];
        $details[] = "Antigüedad: {$accountDays} días (score: {$ageScore})";
        if ($rejections > 0) $details[] = "Rechazos previos: {$rejections} (+{$rejectionPenalty})";
        if ($revocations > 0) $details[] = "Revocaciones previas: {$revocations} (+{$revocationPenalty})";
        if ($roleTrustBonus !== 0) $details[] = "Bonificación por rol: {$roleTrustBonus}";

        return [
            'score' => $score,
            'detail' => implode(' | ', $details),
        ];
    }

    // --- Factor 5: Permission Accumulation (15%) ---
    private function calculateAccumulationRisk(?User $user): array
    {
        if (!$user) {
            return ['score' => 50, 'detail' => 'Usuario no encontrado'];
        }

        $activeCount = UserPermission::where('user_id', $user->id)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->count();

        $score = match (true) {
            $activeCount <= 2  => 10,
            $activeCount <= 5  => 25,
            $activeCount <= 10 => 50,
            $activeCount <= 15 => 75,
            default            => 100,
        };

        return [
            'score' => $score,
            'detail' => "Permisos activos actuales: {$activeCount}",
        ];
    }

    // --- Risk Level Classification ---
    private function classifyRiskLevel(int $score): string
    {
        return match (true) {
            $score <= 25 => 'bajo',
            $score <= 50 => 'medio',
            $score <= 75 => 'alto',
            default      => 'critico',
        };
    }
}
