<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Proyecto;
use App\Models\ResourceAccessRequest;
use App\Models\RiskAuditLog;
use App\Models\User;
use App\Models\UserPermission;
use App\Services\RiskScoringService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ResourceAccessRequestController extends Controller
{
    public function __construct(
        private RiskScoringService $riskService
    ) {}

    /**
     * List resource access requests.
     * Admin sees all, regular users see only their own.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->isAdmin();
        $isGestor = $user->isGestor();

        $query = ResourceAccessRequest::with(['user', 'permission', 'proyecto', 'approver']);

        // Admin y Gestor ven todas las solicitudes; Colaborador solo las propias
        if (!$isAdmin && !$isGestor) {
            $query->where('user_id', $user->id);
        }

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('risk_level')) {
            $query->where('risk_level', $request->risk_level);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(15);

        // Stats (admin/gestor: global, colaborador: own)
        $baseQuery = ($isAdmin || $isGestor) ? ResourceAccessRequest::query() : ResourceAccessRequest::where('user_id', $user->id);
        $stats = [
            'total' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('status', 'pendiente')->count(),
            'approved' => (clone $baseQuery)->where('status', 'aprobada')->count(),
            'rejected' => (clone $baseQuery)->where('status', 'rechazada')->count(),
            'high_risk' => (clone $baseQuery)->pending()->highRisk()->count(),
        ];

        return view('solicitudes-acceso.index', compact('requests', 'stats', 'isAdmin', 'isGestor'));
    }

    /**
     * Show form to create a new resource access request.
     */
    public function create()
    {
        $user = Auth::user();
        $isAdmin = $user->isAdmin();
        $permissions = Permission::orderBy('category')->orderBy('risk_weight')->get()->groupBy('category');
        $proyectos = Proyecto::where('estado', 'activo')->orderBy('nombre_del_proyecto')->get();
        $users = $isAdmin ? User::orderBy('full_name')->get(['id', 'full_name', 'email']) : collect();

        return view('solicitudes-acceso.create', compact('permissions', 'proyectos', 'isAdmin', 'users'));
    }

    /**
     * Store a new resource access request and calculate risk score.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Admin bypass: direct grant without risk evaluation
        if ($user->isExemptFromAccessRequests()) {
            return $this->directGrant($request, $user);
        }

        $validated = $request->validate([
            'permission_id' => 'required|exists:permissions,id',
            'proyecto_id' => 'nullable|exists:proyectos,id',
            'requested_access_level' => 'required|in:lectura,escritura,administracion',
            'justification' => 'required|string|min:20',
            'duration_type' => 'required|in:permanente,temporal',
            'starts_at' => 'required_if:duration_type,temporal|nullable|date|after_or_equal:today',
            'expires_at' => 'required_if:duration_type,temporal|nullable|date|after:starts_at',
        ]);

        try {
            return DB::transaction(function () use ($validated) {
                $accessRequest = ResourceAccessRequest::create([
                    'user_id' => Auth::id(),
                    'permission_id' => $validated['permission_id'],
                    'proyecto_id' => $validated['proyecto_id'] ?? null,
                    'requested_access_level' => $validated['requested_access_level'],
                    'justification' => $validated['justification'],
                    'duration_type' => $validated['duration_type'],
                    'starts_at' => $validated['starts_at'] ?? null,
                    'expires_at' => $validated['expires_at'] ?? null,
                    'status' => 'pendiente',
                ]);

                // Calculate risk score
                $riskResult = $this->riskService->calculateScore($accessRequest);
                $flow = $this->riskService->determineApprovalFlow($riskResult['level']);

                // Enforce max duration for critical risk
                if ($flow['max_duration_days'] && $validated['duration_type'] === 'permanente') {
                    $accessRequest->duration_type = 'temporal';
                    $accessRequest->starts_at = now();
                    $accessRequest->expires_at = now()->addDays($flow['max_duration_days']);
                }

                $accessRequest->update([
                    'risk_score' => $riskResult['score'],
                    'risk_level' => $riskResult['level'],
                    'risk_factors' => $riskResult['factors'],
                    'requires_double_approval' => $flow['requires_double_approval'],
                ]);

                // Log risk calculation
                RiskAuditLog::create([
                    'resource_access_request_id' => $accessRequest->id,
                    'action' => 'score_calculated',
                    'actor_id' => Auth::id(),
                    'actor_name' => Auth::user()->name,
                    'risk_score_at_time' => $riskResult['score'],
                    'risk_level_at_time' => $riskResult['level'],
                    'details' => $riskResult['factors'],
                    'ip_address' => request()->ip(),
                    'created_at' => now(),
                ]);

                // Auto-approve if low risk
                if ($flow['auto_approve']) {
                    $this->autoApprove($accessRequest, $riskResult);
                }

                return redirect()->route('solicitudes-acceso.show', $accessRequest)
                    ->with('success', $flow['auto_approve']
                        ? 'Solicitud auto-aprobada por bajo riesgo (score: ' . $riskResult['score'] . ')'
                        : 'Solicitud enviada correctamente. Nivel de riesgo: ' . ucfirst($riskResult['level']) . ' (score: ' . $riskResult['score'] . ')'
                    );
            });
        } catch (\Exception $e) {
            Log::error('Error al crear solicitud de acceso: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error al procesar la solicitud. Intente nuevamente.'])->withInput();
        }
    }

    /**
     * Show details of a resource access request with risk breakdown.
     */
    public function show(ResourceAccessRequest $solicitudes_acceso)
    {
        $accessRequest = $solicitudes_acceso;
        $accessRequest->load(['user', 'permission', 'proyecto', 'approver', 'secondApprover', 'rejector', 'revoker', 'riskAuditLogs' => fn($q) => $q->orderBy('created_at')]);

        $user = Auth::user();
        $isAdmin = $user->isAdmin();
        $isGestor = $user->isGestor();

        // Only admin/gestor can see others' requests
        if (!$isAdmin && !$isGestor && $accessRequest->user_id !== $user->id) {
            abort(403);
        }

        return view('solicitudes-acceso.show', compact('accessRequest', 'isAdmin'));
    }

    /**
     * Approve a resource access request (first or second approval).
     */
    public function approve(Request $request, ResourceAccessRequest $resourceAccessRequest)
    {
        $user = Auth::user();

        if (!$resourceAccessRequest->isPending() && !$resourceAccessRequest->needsSecondApproval()) {
            return response()->json(['error' => 'Esta solicitud ya fue procesada.'], 422);
        }

        $validated = $request->validate([
            'decision_rationale' => 'required|string|min:10',
        ]);

        $isSelfApproval = $resourceAccessRequest->user_id === $user->id;

        try {
            return DB::transaction(function () use ($resourceAccessRequest, $user, $validated, $isSelfApproval) {
                $isSecondApproval = $resourceAccessRequest->needsSecondApproval();

                if ($isSecondApproval) {
                    // Cannot be same approver
                    if ($resourceAccessRequest->approved_by === $user->id) {
                        return response()->json(['error' => 'La segunda aprobación debe ser de un usuario diferente.'], 422);
                    }

                    $resourceAccessRequest->update([
                        'second_approved_by' => $user->id,
                        'second_approved_at' => now(),
                        'status' => 'aprobada',
                        'decision_rationale' => ($resourceAccessRequest->decision_rationale ? $resourceAccessRequest->decision_rationale . "\n\n--- Segunda aprobación ---\n" : '') . $validated['decision_rationale'],
                    ]);

                    $action = 'second_approval';
                } else {
                    $resourceAccessRequest->update([
                        'approved_by' => $user->id,
                        'approved_at' => now(),
                        'decision_rationale' => $validated['decision_rationale'],
                    ]);

                    if ($resourceAccessRequest->requires_double_approval) {
                        $action = 'manually_approved';
                        // Status stays pendiente until second approval
                    } else {
                        $resourceAccessRequest->update(['status' => 'aprobada']);
                        $action = 'manually_approved';
                    }
                }

                // Log
                RiskAuditLog::create([
                    'resource_access_request_id' => $resourceAccessRequest->id,
                    'action' => $action,
                    'actor_id' => $user->id,
                    'actor_name' => $user->name,
                    'risk_score_at_time' => $resourceAccessRequest->risk_score,
                    'risk_level_at_time' => $resourceAccessRequest->risk_level,
                    'details' => array_filter([
                        'rationale' => $validated['decision_rationale'],
                        'self_approval' => $isSelfApproval ?: null,
                    ]),
                    'ip_address' => request()->ip(),
                    'created_at' => now(),
                ]);

                // Grant permission if fully approved
                if ($resourceAccessRequest->fresh()->status === 'aprobada') {
                    $this->grantPermission($resourceAccessRequest);
                }

                $message = $isSecondApproval
                    ? 'Segunda aprobación completada. Permiso otorgado.'
                    : ($resourceAccessRequest->requires_double_approval
                        ? 'Primera aprobación registrada. Requiere segunda aprobación.'
                        : 'Solicitud aprobada. Permiso otorgado.');

                return response()->json(['success' => true, 'message' => $message]);
            });
        } catch (\Exception $e) {
            Log::error('Error al aprobar solicitud: ' . $e->getMessage());
            return response()->json(['error' => 'Error al procesar la aprobación.'], 500);
        }
    }

    /**
     * Reject a resource access request.
     */
    public function reject(Request $request, ResourceAccessRequest $resourceAccessRequest)
    {
        if (!$resourceAccessRequest->isPending() && !$resourceAccessRequest->needsSecondApproval()) {
            return response()->json(['error' => 'Esta solicitud ya fue procesada.'], 422);
        }

        $validated = $request->validate([
            'decision_rationale' => 'required|string|min:10',
        ]);

        $user = Auth::user();

        $resourceAccessRequest->update([
            'status' => 'rechazada',
            'rejected_by' => $user->id,
            'rejected_at' => now(),
            'decision_rationale' => $validated['decision_rationale'],
        ]);

        RiskAuditLog::create([
            'resource_access_request_id' => $resourceAccessRequest->id,
            'action' => 'rejected',
            'actor_id' => $user->id,
            'actor_name' => $user->name,
            'risk_score_at_time' => $resourceAccessRequest->risk_score,
            'risk_level_at_time' => $resourceAccessRequest->risk_level,
            'details' => ['rationale' => $validated['decision_rationale']],
            'ip_address' => request()->ip(),
            'created_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Solicitud rechazada.']);
    }

    /**
     * Revoke a previously approved access.
     */
    public function revoke(Request $request, ResourceAccessRequest $resourceAccessRequest)
    {
        if ($resourceAccessRequest->status !== 'aprobada') {
            return response()->json(['error' => 'Solo se pueden revocar solicitudes aprobadas.'], 422);
        }

        $validated = $request->validate([
            'revocation_reason' => 'required|string|min:10',
        ]);

        $user = Auth::user();

        DB::transaction(function () use ($resourceAccessRequest, $user, $validated) {
            $resourceAccessRequest->update([
                'status' => 'revocada',
                'revoked_by' => $user->id,
                'revoked_at' => now(),
                'revocation_reason' => $validated['revocation_reason'],
            ]);

            // Deactivate the granted permission
            UserPermission::where('resource_access_request_id', $resourceAccessRequest->id)
                ->where('is_active', true)
                ->update([
                    'is_active' => false,
                    'revoked_at' => now(),
                    'revoked_by' => $user->id,
                ]);

            RiskAuditLog::create([
                'resource_access_request_id' => $resourceAccessRequest->id,
                'action' => 'revoked',
                'actor_id' => $user->id,
                'actor_name' => $user->name,
                'risk_score_at_time' => $resourceAccessRequest->risk_score,
                'risk_level_at_time' => $resourceAccessRequest->risk_level,
                'details' => ['reason' => $validated['revocation_reason']],
                'ip_address' => request()->ip(),
                'created_at' => now(),
            ]);
        });

        return response()->json(['success' => true, 'message' => 'Acceso revocado exitosamente.']);
    }

    /**
     * Preview risk score (AJAX endpoint for form).
     */
    public function previewRisk(Request $request)
    {
        $validated = $request->validate([
            'permission_id' => 'required|exists:permissions,id',
            'proyecto_id' => 'nullable|exists:proyectos,id',
            'duration_type' => 'required|in:permanente,temporal',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date',
        ]);

        $tempRequest = new ResourceAccessRequest($validated);
        $tempRequest->user_id = Auth::id();

        $result = $this->riskService->calculateScore($tempRequest);

        return response()->json($result);
    }

    // --- Private helpers ---

    private function autoApprove(ResourceAccessRequest $accessRequest, array $riskResult): void
    {
        $accessRequest->update([
            'status' => 'aprobada',
            'approved_at' => now(),
            'decision_rationale' => "Auto-aprobado por el motor de riesgo. Score: {$riskResult['score']} (nivel: {$riskResult['level']}). Todas las evaluaciones de riesgo están dentro de parámetros aceptables.",
        ]);

        RiskAuditLog::create([
            'resource_access_request_id' => $accessRequest->id,
            'action' => 'auto_approved',
            'risk_score_at_time' => $riskResult['score'],
            'risk_level_at_time' => $riskResult['level'],
            'details' => ['auto_approve_reason' => 'Risk score below threshold (0-25)'],
            'ip_address' => request()->ip(),
            'created_at' => now(),
        ]);

        $this->grantPermission($accessRequest);
    }

    private function directGrant(Request $request, User $admin): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'permission_id' => 'required|exists:permissions,id',
            'proyecto_id' => 'nullable|exists:proyectos,id',
            'requested_access_level' => 'required|in:lectura,escritura,administracion',
            'justification' => 'required|string|min:5',
            'duration_type' => 'required|in:permanente,temporal',
            'starts_at' => 'required_if:duration_type,temporal|nullable|date',
            'expires_at' => 'required_if:duration_type,temporal|nullable|date|after:starts_at',
            'target_user_id' => 'nullable|exists:users,id',
        ]);

        $targetUserId = $validated['target_user_id'] ?? $admin->id;

        try {
            return DB::transaction(function () use ($validated, $admin, $targetUserId) {
                $accessRequest = ResourceAccessRequest::create([
                    'user_id' => $targetUserId,
                    'permission_id' => $validated['permission_id'],
                    'proyecto_id' => $validated['proyecto_id'] ?? null,
                    'requested_access_level' => $validated['requested_access_level'],
                    'justification' => $validated['justification'],
                    'duration_type' => $validated['duration_type'],
                    'starts_at' => $validated['starts_at'] ?? now(),
                    'expires_at' => $validated['expires_at'] ?? null,
                    'status' => 'aprobada',
                    'risk_score' => 0,
                    'risk_level' => 'bajo',
                    'risk_factors' => ['admin_bypass' => ['label' => 'Bypass de Administrador', 'score' => 0, 'weight' => 1, 'weighted' => 0, 'detail' => 'Otorgamiento directo por administrador']],
                    'requires_double_approval' => false,
                    'approved_by' => $admin->id,
                    'approved_at' => now(),
                    'decision_rationale' => 'Otorgamiento directo por administrador (exento de evaluación de riesgo).',
                ]);

                RiskAuditLog::create([
                    'resource_access_request_id' => $accessRequest->id,
                    'action' => 'admin_direct_grant',
                    'actor_id' => $admin->id,
                    'actor_name' => $admin->name,
                    'risk_score_at_time' => 0,
                    'risk_level_at_time' => 'bajo',
                    'details' => ['rationale' => 'Admin bypass — exento de evaluación de riesgo'],
                    'ip_address' => request()->ip(),
                    'created_at' => now(),
                ]);

                $this->grantPermission($accessRequest);

                return redirect()->route('solicitudes-acceso.show', $accessRequest)
                    ->with('success', 'Permiso otorgado directamente (bypass de administrador).');
            });
        } catch (\Exception $e) {
            Log::error('Error en otorgamiento directo: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error al procesar el otorgamiento.'])->withInput();
        }
    }

    private function grantPermission(ResourceAccessRequest $accessRequest): void
    {
        UserPermission::updateOrCreate(
            [
                'user_id' => $accessRequest->user_id,
                'permission_id' => $accessRequest->permission_id,
                'proyecto_id' => $accessRequest->proyecto_id,
            ],
            [
                'granted_by' => $accessRequest->approved_by ?? Auth::id(),
                'resource_access_request_id' => $accessRequest->id,
                'is_temporary' => $accessRequest->duration_type === 'temporal',
                'starts_at' => $accessRequest->starts_at ?? now(),
                'expires_at' => $accessRequest->expires_at,
                'is_active' => true,
                'revoked_at' => null,
                'revoked_by' => null,
            ]
        );
    }
}
