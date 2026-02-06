@extends('layouts.quantum')

@section('title', 'Detalle de Solicitud #' . $accessRequest->id)

@section('content')
<div x-data="{
    processing: false,
    approveRationale: '',
    rejectRationale: '',
    revokeReason: '',

    async submitAction(url, body) {
        this.processing = true;
        try {
            let res = await fetch(url, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: JSON.stringify(body)
            });
            let data = await res.json();
            if (data.success) {
                Swal.fire({ icon: 'success', title: 'Éxito', text: data.message, background: 'hsl(240, 12%, 10%)', color: '#fff', confirmButtonColor: 'hsl(195, 100%, 50%)' }).then(() => location.reload());
            } else {
                let errorMsg = data.error || data.message || 'Ocurrió un error';
                if (data.errors) { errorMsg = Object.values(data.errors).flat().join('. '); }
                Swal.fire({ icon: 'error', title: 'Error', text: errorMsg, background: 'hsl(240, 12%, 10%)', color: '#fff', confirmButtonColor: 'hsl(0, 84%, 60%)' });
            }
        } catch(e) {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Error de conexión', background: 'hsl(240, 12%, 10%)', color: '#fff' });
        }
        this.processing = false;
    }
}" class="space-y-6 animate-fadeIn">

    {{-- ============================================================ --}}
    {{-- HEADER --}}
    {{-- ============================================================ --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <a href="{{ route('solicitudes-acceso.index') }}" class="text-gray-400 hover:text-white transition-colors text-sm flex items-center gap-1 mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Volver a solicitudes
            </a>
            <h1 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-quantum-500 via-void-500 to-photon-500 bg-clip-text text-transparent">
                Solicitud #{{ $accessRequest->id }}
            </h1>
        </div>
        @php
            $statusColors = [
                'pendiente' => 'amber', 'aprobada' => 'green', 'rechazada' => 'red', 'expirada' => 'gray', 'revocada' => 'red',
            ];
            $sc = $statusColors[$accessRequest->status] ?? 'gray';
        @endphp
        <span class="px-5 py-2 rounded-quantum text-sm font-bold uppercase tracking-wider bg-{{ $sc }}-500/20 text-{{ $sc }}-400 border border-{{ $sc }}-500/30">
            {{ $accessRequest->status_label }}
        </span>
    </div>

    {{-- ============================================================ --}}
    {{-- TOP ROW: Risk Score + Request Info (side by side) --}}
    {{-- ============================================================ --}}
    @php
        $score = $accessRequest->risk_score ?? 0;
        $riskLevel = $accessRequest->risk_level ?? 'medio';
        $riskColors = ['bajo' => '#10b981', 'medio' => '#f59e0b', 'alto' => '#f97316', 'critico' => '#ef4444'];
        $riskLabels = ['bajo' => 'Bajo', 'medio' => 'Medio', 'alto' => 'Alto', 'critico' => 'Crítico'];
        $color = $riskColors[$riskLevel] ?? '#6b7280';
        $circumference = 2 * M_PI * 54;
        $dashOffset = $circumference - ($score / 100) * $circumference;
        $flowLabels = [
            'bajo' => 'Auto-aprobación inmediata',
            'medio' => 'Requiere 1 aprobación',
            'alto' => 'Requiere doble aprobación',
            'critico' => 'Doble aprobación + restricciones',
        ];
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Risk Score Card (compact left) --}}
        <div class="lg:col-span-3">
            <div class="card-quantum p-6 h-full flex flex-col items-center justify-center gap-4">
                {{-- Score Circle --}}
                <svg width="150" height="150" viewBox="0 0 140 140">
                    <circle cx="70" cy="70" r="54" fill="none" stroke="rgba(255,255,255,0.08)" stroke-width="10"/>
                    <circle cx="70" cy="70" r="54" fill="none" stroke="{{ $color }}" stroke-width="10"
                        stroke-linecap="round" stroke-dasharray="{{ $circumference }}" stroke-dashoffset="{{ $dashOffset }}"
                        transform="rotate(-90 70 70)" class="transition-all duration-1000"/>
                    <text x="70" y="62" text-anchor="middle" fill="white" font-size="36" font-weight="bold">{{ $score }}</text>
                    <text x="70" y="82" text-anchor="middle" fill="{{ $color }}" font-size="13" font-weight="600">{{ $riskLabels[$riskLevel] ?? 'N/A' }}</text>
                </svg>

                {{-- Approval Flow Badge --}}
                <div class="w-full p-3 rounded-quantum bg-matter-light/50 border border-matter-light text-center">
                    <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-1">Flujo de Aprobación</p>
                    <p class="text-sm font-semibold" style="color: {{ $color }}">{{ $flowLabels[$riskLevel] ?? 'N/A' }}</p>
                </div>

                {{-- Double Approval Status --}}
                @if($accessRequest->requires_double_approval)
                <div class="w-full space-y-1.5">
                    <div class="flex items-center justify-between p-2 rounded-quantum bg-matter-light/30 text-xs">
                        <span class="text-gray-400">1ra aprobación</span>
                        @if($accessRequest->approver)
                            <span class="text-green-400 font-semibold">{{ $accessRequest->approver->name }}</span>
                        @else
                            <span class="text-amber-400">Pendiente</span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between p-2 rounded-quantum bg-matter-light/30 text-xs">
                        <span class="text-gray-400">2da aprobación</span>
                        @if($accessRequest->secondApprover)
                            <span class="text-green-400 font-semibold">{{ $accessRequest->secondApprover->name }}</span>
                        @else
                            <span class="text-amber-400">Pendiente</span>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Request Info (wide right) --}}
        <div class="lg:col-span-9">
            <div class="card-quantum p-6 h-full">
                <h3 class="text-lg font-bold text-white mb-5 flex items-center gap-2">
                    <svg class="w-5 h-5 text-quantum-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Información de la Solicitud
                </h3>
                <div class="grid grid-cols-2 lg:grid-cols-3 gap-5">
                    {{-- Solicitante --}}
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-quantum-500 to-void-500 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                            {{ substr($accessRequest->user->name ?? '?', 0, 1) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] text-gray-500 uppercase tracking-wider">Solicitante</p>
                            <p class="text-sm font-semibold text-white truncate">{{ $accessRequest->user->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ $accessRequest->user->email ?? '' }}</p>
                        </div>
                    </div>

                    {{-- Permiso --}}
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-full bg-void-500/15 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4.5 h-4.5 text-void-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] text-gray-500 uppercase tracking-wider">Permiso</p>
                            <p class="text-sm font-semibold text-white">{{ $accessRequest->permission->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-400">{{ ucfirst($accessRequest->permission->category ?? '') }} | Peso: {{ $accessRequest->permission->risk_weight ?? 0 }}/5</p>
                        </div>
                    </div>

                    {{-- Proyecto --}}
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-full bg-photon-500/15 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4.5 h-4.5 text-photon-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] text-gray-500 uppercase tracking-wider">Proyecto</p>
                            @if($accessRequest->proyecto)
                                <p class="text-sm font-semibold text-white truncate">{{ $accessRequest->proyecto->nombre_del_proyecto }}</p>
                                @php $critColor = ['bajo'=>'green','medio'=>'amber','alto'=>'orange','critico'=>'red'][$accessRequest->proyecto->nivel_criticidad ?? 'medio'] ?? 'gray'; @endphp
                                <span class="inline-flex px-1.5 py-0.5 text-[10px] rounded-full bg-{{ $critColor }}-500/20 text-{{ $critColor }}-400 border border-{{ $critColor }}-500/30 mt-0.5">
                                    {{ ucfirst($accessRequest->proyecto->nivel_criticidad ?? 'medio') }}
                                </span>
                            @else
                                <p class="text-sm font-semibold text-white">Acceso General</p>
                            @endif
                        </div>
                    </div>

                    {{-- Nivel de Acceso --}}
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-full bg-quantum-500/15 flex items-center justify-center flex-shrink-0">
                            @if($accessRequest->requested_access_level === 'lectura')
                                <svg class="w-4.5 h-4.5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            @elseif($accessRequest->requested_access_level === 'escritura')
                                <svg class="w-4.5 h-4.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            @else
                                <svg class="w-4.5 h-4.5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-500 uppercase tracking-wider">Nivel de Acceso</p>
                            <p class="text-sm font-semibold text-white">{{ ucfirst($accessRequest->requested_access_level) }}</p>
                        </div>
                    </div>

                    {{-- Duración --}}
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-full bg-quantum-500/15 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4.5 h-4.5 text-quantum-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-500 uppercase tracking-wider">Duración</p>
                            <p class="text-sm font-semibold text-white">{{ ucfirst($accessRequest->duration_type) }}</p>
                            @if($accessRequest->duration_type === 'temporal' && $accessRequest->starts_at && $accessRequest->expires_at)
                                <p class="text-xs text-gray-400">{{ $accessRequest->starts_at->format('d/m/Y') }} — {{ $accessRequest->expires_at->format('d/m/Y') }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Fecha de Solicitud --}}
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-full bg-void-500/15 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4.5 h-4.5 text-void-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-500 uppercase tracking-wider">Fecha de Solicitud</p>
                            <p class="text-sm font-semibold text-white">{{ $accessRequest->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Justificación --}}
                <div class="mt-5 p-4 bg-matter-light/50 rounded-quantum border border-matter-light">
                    <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-1.5">Justificación</p>
                    <p class="text-sm text-gray-300 leading-relaxed">{{ $accessRequest->justification }}</p>
                </div>

                {{-- Decision Rationale --}}
                @if($accessRequest->decision_rationale)
                <div class="mt-3 p-4 bg-{{ $sc }}-500/10 rounded-quantum border border-{{ $sc }}-500/20">
                    <p class="text-[10px] text-{{ $sc }}-400 uppercase tracking-wider mb-1.5">Decisión</p>
                    <p class="text-sm text-gray-300 leading-relaxed whitespace-pre-line">{{ $accessRequest->decision_rationale }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- BOTTOM ROW: Factor Breakdown + Timeline + Admin Actions --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Factor Breakdown --}}
        <div class="card-quantum p-6">
            <h3 class="text-lg font-bold text-white mb-5 flex items-center gap-2">
                <svg class="w-5 h-5 text-quantum-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Desglose de Factores
            </h3>
            <div class="space-y-5">
                @foreach($accessRequest->risk_factors ?? [] as $key => $factor)
                    @php
                        $fScore = $factor['score'] ?? 0;
                        $fWeighted = $factor['weighted'] ?? 0;
                        $fWeight = ($factor['weight'] ?? 0) * 100;
                        $fColor = $fScore <= 25 ? '#10b981' : ($fScore <= 50 ? '#f59e0b' : ($fScore <= 75 ? '#f97316' : '#ef4444'));
                    @endphp
                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <span class="text-sm text-gray-300 font-medium">{{ $factor['label'] ?? $key }}</span>
                            <div class="flex items-center gap-3">
                                <span class="text-xs text-gray-500">{{ $fWeight }}%</span>
                                <span class="text-sm font-bold tabular-nums" style="color: {{ $fColor }}">{{ $fScore }}/100</span>
                            </div>
                        </div>
                        <div class="w-full h-2.5 bg-matter-light rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-1000" style="width: {{ $fScore }}%; background: {{ $fColor }}"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ $factor['detail'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Decision Timeline --}}
        <div class="card-quantum p-6">
            <h3 class="text-lg font-bold text-white mb-5 flex items-center gap-2">
                <svg class="w-5 h-5 text-void-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Historial de Decisiones
            </h3>

            @if($accessRequest->riskAuditLogs->isEmpty())
                <p class="text-gray-400 text-center py-4">Sin registros de decisión</p>
            @else
                <div class="relative">
                    <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gradient-to-b from-quantum-500 via-void-500 to-photon-500"></div>

                    <div class="space-y-4">
                        @foreach($accessRequest->riskAuditLogs as $log)
                            @php
                                $dotColor = match($log->action) {
                                    'score_calculated' => 'bg-blue-500',
                                    'auto_approved', 'manually_approved', 'second_approval' => 'bg-green-500',
                                    'rejected' => 'bg-red-500',
                                    'revoked' => 'bg-orange-500',
                                    'expired' => 'bg-gray-500',
                                    default => 'bg-gray-500',
                                };
                            @endphp
                            <div class="relative pl-12">
                                <div class="absolute left-2 top-1.5 w-4 h-4 rounded-full {{ $dotColor }} border-4 border-space z-10"></div>
                                <div class="bg-matter-light/30 rounded-quantum p-3 border border-matter-light">
                                    <div class="flex flex-col sm:flex-row justify-between items-start gap-1">
                                        <div>
                                            <p class="text-sm font-semibold text-white">{{ $log->action_label }}</p>
                                            <p class="text-xs text-gray-400">
                                                {{ $log->actor_name ?? 'Sistema' }} — {{ $log->created_at->format('d/m/Y H:i:s') }}
                                            </p>
                                        </div>
                                        @if($log->risk_score_at_time !== null)
                                            @php
                                                $scoreColor = $log->risk_score_at_time <= 25 ? 'green' : ($log->risk_score_at_time <= 50 ? 'amber' : ($log->risk_score_at_time <= 75 ? 'orange' : 'red'));
                                            @endphp
                                            <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-{{ $scoreColor }}-500/20 text-{{ $scoreColor }}-400">
                                                Score: {{ $log->risk_score_at_time }}
                                            </span>
                                        @endif
                                    </div>
                                    @if(is_array($log->details) && isset($log->details['rationale']))
                                        <p class="text-xs text-gray-400 mt-2 border-t border-matter-light pt-2">{{ $log->details['rationale'] }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- ADMIN ACTIONS (full width) --}}
    {{-- ============================================================ --}}
    @if($isAdmin)
        @if($accessRequest->isPending() || $accessRequest->needsSecondApproval())
        <div class="card-quantum p-6">
            <h3 class="text-lg font-bold text-white mb-5 flex items-center gap-2">
                <svg class="w-5 h-5 text-photon-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                {{ $accessRequest->needsSecondApproval() ? 'Segunda Aprobación Requerida' : 'Acciones de Administrador' }}
            </h3>

            @if($accessRequest->needsSecondApproval())
                <div class="mb-4 p-3 bg-amber-500/10 rounded-quantum border border-amber-500/20">
                    <p class="text-sm text-amber-400">Primera aprobación: {{ $accessRequest->approver->name ?? 'N/A' }} — {{ $accessRequest->approved_at?->format('d/m/Y H:i') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Approve --}}
                <div class="p-4 rounded-quantum border border-green-500/20 bg-green-500/5">
                    <h4 class="text-sm font-bold text-green-400 mb-3">Aprobar Solicitud</h4>
                    <textarea x-model="approveRationale" placeholder="Justificación de la aprobación (mín. 10 caracteres)..." rows="3"
                        class="w-full px-3 py-2 bg-matter-light border border-matter-light rounded-quantum text-white text-sm focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all resize-none mb-3"></textarea>
                    <button @click="if(approveRationale.length >= 10) submitAction('{{ route('solicitudes-acceso.approve', $accessRequest) }}', { decision_rationale: approveRationale })" :disabled="processing || approveRationale.length < 10"
                        class="w-full px-4 py-2 bg-green-500/20 hover:bg-green-500/30 text-green-400 font-semibold rounded-quantum border border-green-500/30 transition-all disabled:opacity-50 disabled:cursor-not-allowed text-sm">
                        <span x-show="!processing">Aprobar</span>
                        <span x-show="processing">Procesando...</span>
                    </button>
                </div>

                {{-- Reject --}}
                <div class="p-4 rounded-quantum border border-red-500/20 bg-red-500/5">
                    <h4 class="text-sm font-bold text-red-400 mb-3">Rechazar Solicitud</h4>
                    <textarea x-model="rejectRationale" placeholder="Motivo del rechazo (mín. 10 caracteres)..." rows="3"
                        class="w-full px-3 py-2 bg-matter-light border border-matter-light rounded-quantum text-white text-sm focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-all resize-none mb-3"></textarea>
                    <button @click="if(rejectRationale.length >= 10) submitAction('{{ route('solicitudes-acceso.reject', $accessRequest) }}', { decision_rationale: rejectRationale })" :disabled="processing || rejectRationale.length < 10"
                        class="w-full px-4 py-2 bg-red-500/20 hover:bg-red-500/30 text-red-400 font-semibold rounded-quantum border border-red-500/30 transition-all disabled:opacity-50 disabled:cursor-not-allowed text-sm">
                        <span x-show="!processing">Rechazar</span>
                        <span x-show="processing">Procesando...</span>
                    </button>
                </div>
            </div>
        </div>
        @endif

        @if($accessRequest->status === 'aprobada')
        <div class="card-quantum p-6">
            <h3 class="text-lg font-bold text-red-400 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                Revocar Acceso
            </h3>
            <div class="max-w-xl">
                <textarea x-model="revokeReason" placeholder="Motivo de la revocación (mín. 10 caracteres)..." rows="2"
                    class="w-full px-3 py-2 bg-matter-light border border-matter-light rounded-quantum text-white text-sm focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-all resize-none mb-3"></textarea>
                <button @click="if(revokeReason.length >= 10) submitAction('{{ route('solicitudes-acceso.revoke', $accessRequest) }}', { revocation_reason: revokeReason })" :disabled="processing || revokeReason.length < 10"
                    class="px-6 py-2 bg-red-500/20 hover:bg-red-500/30 text-red-400 font-semibold rounded-quantum border border-red-500/30 transition-all disabled:opacity-50 disabled:cursor-not-allowed text-sm">
                    <span x-show="!processing">Revocar Acceso</span>
                    <span x-show="processing">Procesando...</span>
                </button>
            </div>
        </div>
        @endif
    @endif
</div>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({ icon: 'success', title: 'Éxito', text: '{{ session("success") }}', background: 'hsl(240, 12%, 10%)', color: '#fff', confirmButtonColor: 'hsl(195, 100%, 50%)', timer: 5000, timerProgressBar: true });
    });
</script>
@endif
@endsection
