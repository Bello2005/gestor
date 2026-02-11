{{-- Tab 3: Análisis de Riesgo — contenido (filtros en riesgo.blade.php) --}}

<!-- KPI Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <!-- Total Requests -->
    <div class="card-quantum p-6 group hover:scale-105 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm uppercase tracking-wider mb-1">Total Solicitudes</p>
                <p class="text-3xl font-bold text-white">{{ $totalRequests }}</p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-quantum-500/20 to-void-500/20 rounded-quantum flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7 text-quantum-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Avg Risk Score -->
    <div class="card-quantum p-6 group hover:scale-105 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm uppercase tracking-wider mb-1">Score Promedio</p>
                <p class="text-3xl font-bold {{ $avgRiskScore <= 25 ? 'text-green-400' : ($avgRiskScore <= 50 ? 'text-amber-400' : ($avgRiskScore <= 75 ? 'text-orange-400' : 'text-red-400')) }}">
                    {{ $avgRiskScore }}
                </p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-amber-500/20 to-orange-500/20 rounded-quantum flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Auto-approved % -->
    <div class="card-quantum p-6 group hover:scale-105 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm uppercase tracking-wider mb-1">Auto-aprobados</p>
                <p class="text-3xl font-bold text-green-400">{{ $autoApprovedPct }}%</p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-quantum flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Pending High Risk -->
    <div class="card-quantum p-6 group hover:scale-105 transition-all duration-300 {{ $pendingHighRisk > 0 ? 'border-red-500/50' : '' }}">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm uppercase tracking-wider mb-1">Alto Riesgo Pendiente</p>
                <p class="text-3xl font-bold {{ $pendingHighRisk > 0 ? 'text-red-400 animate-pulse' : 'text-gray-400' }}">{{ $pendingHighRisk }}</p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-red-500/20 to-rose-500/20 rounded-quantum flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Risk Distribution Donut -->
    <div class="card-quantum p-6">
        <h3 class="text-xl font-bold text-white mb-6">Distribución por Nivel de Riesgo</h3>
        @php
            $total = array_sum($riskDistribution);
            $colors = ['bajo' => '#10b981', 'medio' => '#f59e0b', 'alto' => '#f97316', 'critico' => '#ef4444'];
            $labels = ['bajo' => 'Bajo', 'medio' => 'Medio', 'alto' => 'Alto', 'critico' => 'Crítico'];
            $circumference = 2 * M_PI * 60;
            $offset = 0;
        @endphp
        <div class="flex flex-col items-center">
            <div class="relative">
                <svg width="200" height="200" viewBox="0 0 200 200">
                    <circle cx="100" cy="100" r="60" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="24"/>
                    @foreach(['bajo', 'medio', 'alto', 'critico'] as $level)
                        @if(isset($riskDistribution[$level]) && $riskDistribution[$level] > 0)
                            @php
                                $pct = $total > 0 ? $riskDistribution[$level] / $total : 0;
                                $dashLength = $pct * $circumference;
                            @endphp
                            <circle cx="100" cy="100" r="60" fill="none"
                                stroke="{{ $colors[$level] }}" stroke-width="24"
                                stroke-dasharray="{{ $dashLength }} {{ $circumference - $dashLength }}"
                                stroke-dashoffset="{{ -$offset }}"
                                transform="rotate(-90 100 100)"
                                class="transition-all duration-1000"/>
                            @php $offset += $dashLength; @endphp
                        @endif
                    @endforeach
                    <text x="100" y="95" text-anchor="middle" fill="white" font-size="28" font-weight="bold">{{ $total }}</text>
                    <text x="100" y="115" text-anchor="middle" fill="#9ca3af" font-size="12">solicitudes</text>
                </svg>
            </div>
            <div class="flex flex-wrap justify-center gap-4 mt-4">
                @foreach(['bajo', 'medio', 'alto', 'critico'] as $level)
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full" style="background: {{ $colors[$level] }}"></div>
                        <span class="text-sm text-gray-300">{{ $labels[$level] }}: {{ $riskDistribution[$level] ?? 0 }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Access Type Ratio + Approval Times -->
    <div class="space-y-6">
        <!-- Temporal vs Permanent -->
        <div class="card-quantum p-6">
            <h3 class="text-xl font-bold text-white mb-4">Tipo de Acceso</h3>
            @php $accessTotal = $temporalCount + $permanentCount; @endphp
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-300">Temporal</span>
                        <span class="text-quantum-400 font-semibold">{{ $temporalCount }} ({{ $accessTotal > 0 ? round($temporalCount/$accessTotal*100) : 0 }}%)</span>
                    </div>
                    <div class="w-full h-3 bg-matter-light rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-quantum-500 to-void-500 rounded-full transition-all duration-1000" style="width: {{ $accessTotal > 0 ? ($temporalCount/$accessTotal*100) : 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-300">Permanente</span>
                        <span class="text-orange-400 font-semibold">{{ $permanentCount }} ({{ $accessTotal > 0 ? round($permanentCount/$accessTotal*100) : 0 }}%)</span>
                    </div>
                    <div class="w-full h-3 bg-matter-light rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-orange-500 to-red-500 rounded-full transition-all duration-1000" style="width: {{ $accessTotal > 0 ? ($permanentCount/$accessTotal*100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approval Times -->
        <div class="card-quantum p-6">
            <h3 class="text-xl font-bold text-white mb-4">Tiempo Promedio de Aprobación</h3>
            <div class="space-y-3">
                @foreach(['bajo' => 'Bajo', 'medio' => 'Medio', 'alto' => 'Alto', 'critico' => 'Crítico'] as $level => $label)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full" style="background: {{ $colors[$level] }}"></div>
                            <span class="text-sm text-gray-300">{{ $label }}</span>
                        </div>
                        <span class="text-sm font-semibold text-white">
                            {{ isset($approvalTimes[$level]) ? $approvalTimes[$level] . 'h' : '—' }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Solicitudes Pendientes: CTA visible + tabla colapsable --}}
@php
    $riskBadgeClass = [
        'bajo' => 'bg-green-500/20 text-green-400 border-green-500/30',
        'medio' => 'bg-amber-500/20 text-amber-400 border-amber-500/30',
        'alto' => 'bg-orange-500/20 text-orange-400 border-orange-500/30',
        'critico' => 'bg-red-500/20 text-red-400 border-red-500/30',
    ];
    $pendingCount = isset($pendingRequests) ? $pendingRequests->count() : 0;
@endphp
<div class="mt-6" x-data="{
    pendientesExpandido: false,
    approveModal: { open: false, id: null, rationale: '' },
    rejectModal: { open: false, id: null, rationale: '' },
    processing: false,
    openApprove(id) { this.approveModal = { open: true, id: id, rationale: '' }; },
    openReject(id) { this.rejectModal = { open: true, id: id, rationale: '' }; },
    verPendientes() {
        this.pendientesExpandido = true;
        this.$nextTick(() => { const el = this.$refs.tablaPendientes; if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' }); });
    },
    async submitApproval() {
        if (!this.approveModal.rationale || this.approveModal.rationale.length < 10) return;
        this.processing = true;
        try {
            const r = await fetch('{{ url('solicitudes-acceso') }}/' + this.approveModal.id + '/approve', {
                method: 'PUT',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ decision_rationale: this.approveModal.rationale })
            });
            const data = await r.json();
            this.processing = false;
            if (data.success) { this.approveModal.open = false; setTimeout(() => window.location.reload(), 800); }
        } catch (e) { this.processing = false; }
    },
    async submitRejection() {
        if (!this.rejectModal.rationale || this.rejectModal.rationale.length < 10) return;
        this.processing = true;
        try {
            const r = await fetch('{{ url('solicitudes-acceso') }}/' + this.rejectModal.id + '/reject', {
                method: 'PUT',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ decision_rationale: this.rejectModal.rationale })
            });
            const data = await r.json();
            this.processing = false;
            if (data.success) { this.rejectModal.open = false; setTimeout(() => window.location.reload(), 800); }
        } catch (e) { this.processing = false; }
    }
}">
    @if($pendingCount > 0)
        <div class="card-quantum p-4 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-quantum bg-amber-500/20 border border-amber-500/30 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white font-semibold">Tienes <span class="text-amber-400">{{ $pendingCount }}</span> solicitudes pendientes de revisión.</p>
                    <p class="text-sm text-gray-400 mt-0.5">Aprueba o rechaza desde aquí o en Solicitudes de acceso.</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" @click="pendientesExpandido = false" x-show="pendientesExpandido"
                        class="px-4 py-2 rounded-quantum text-sm text-gray-400 hover:text-white hover:bg-matter-light transition-all border border-matter-light">
                    Ocultar
                </button>
                <button type="button" @click="verPendientes()"
                        class="px-5 py-2.5 rounded-quantum text-sm font-medium text-white bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-400 hover:to-orange-500 transition-all shadow-lg shadow-amber-500/20 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    Ver y gestionar
                </button>
            </div>
        </div>
        <div x-show="pendientesExpandido" x-cloak x-ref="tablaPendientes" class="mt-4" x-transition>
            <div class="card-quantum p-6">
                <h3 class="text-xl font-bold text-white mb-4">Solicitudes Pendientes</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-gray-400 uppercase border-b border-matter-light">
                            <tr>
                                <th class="py-3 px-2">Usuario</th>
                                <th class="py-3 px-2">Permiso</th>
                                <th class="py-3 px-2">Proyecto</th>
                                <th class="py-3 px-2">Nivel de riesgo</th>
                                <th class="py-3 px-2">Score</th>
                                <th class="py-3 px-2">Fecha</th>
                                <th class="py-3 px-2 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingRequests as $req)
                                <tr class="border-b border-matter-light/50 hover:bg-matter-light/30 transition-colors">
                                    <td class="py-3 px-2 text-white">{{ $req->user ? $req->user->full_name ?? $req->user->name ?? $req->user->email : '—' }}</td>
                                    <td class="py-3 px-2 text-gray-300">{{ $req->permission ? $req->permission->name ?? $req->permission->display_name ?? '—' : '—' }}</td>
                                    <td class="py-3 px-2 text-gray-300">{{ $req->proyecto ? $req->proyecto->nombre_del_proyecto : '—' }}</td>
                                    <td class="py-3 px-2">
                                        @php $level = $req->risk_level ?? 'medio'; @endphp
                                        <span class="px-2 py-0.5 text-xs rounded-full border {{ $riskBadgeClass[$level] ?? $riskBadgeClass['medio'] }}">{{ ucfirst($level) }}</span>
                                    </td>
                                    <td class="py-3 px-2 text-gray-300">{{ $req->risk_score ?? '—' }}</td>
                                    <td class="py-3 px-2 text-gray-400">{{ $req->created_at ? $req->created_at->format('d/m/Y H:i') : '—' }}</td>
                                    <td class="py-3 px-2 text-right">
                                        <a href="{{ route('solicitudes-acceso.show', $req) }}" class="text-quantum-400 hover:text-quantum-300 text-xs mr-2">Revisar</a>
                                        <button type="button" @click="openApprove({{ $req->id }})" class="text-green-400 hover:text-green-300 text-xs font-medium mr-2">Aprobar</button>
                                        <button type="button" @click="openReject({{ $req->id }})" class="text-red-400 hover:text-red-300 text-xs font-medium">Rechazar</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card-quantum p-4 flex items-center gap-3">
            <div class="w-12 h-12 rounded-quantum bg-green-500/20 border border-green-500/30 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-gray-300">No hay solicitudes pendientes.</p>
        </div>
    @endif

    {{-- Modales Aprobar / Rechazar (siempre en DOM para cuando hay pendientes) --}}
    @if($pendingCount > 0)
    {{-- Modal Aprobar --}}
    <div x-show="approveModal.open" x-cloak @keydown.escape.window="if(!processing) approveModal.open = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div @click.away="if(!processing) approveModal.open = false" class="card-quantum max-w-lg w-full p-0 overflow-hidden">
            <div class="px-6 pt-6 pb-4 border-b border-matter-light">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-quantum flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Aprobar Solicitud</h3>
                        <p class="text-sm text-gray-400 mt-0.5">Justificación (mín. 10 caracteres)</p>
                    </div>
                </div>
            </div>
            <div class="px-6 py-5">
                <textarea x-model="approveModal.rationale" rows="4" placeholder="Justificación de la aprobación..."
                          :disabled="processing" class="w-full bg-matter-light border border-matter-light rounded-quantum text-white text-sm px-3 py-2.5 focus:border-quantum-500 focus:ring-2 focus:ring-quantum-500/20 resize-none placeholder-gray-500 disabled:opacity-50"></textarea>
                <p class="text-xs text-gray-500 mt-1" x-text="(approveModal.rationale?.length || 0) + ' / mínimo 10 caracteres'"></p>
            </div>
            <div class="px-6 py-4 border-t border-matter-light bg-matter/50 flex justify-end gap-3">
                <button type="button" @click="approveModal.open = false" :disabled="processing" class="px-4 py-2.5 text-sm font-medium text-gray-300 border border-matter-light rounded-quantum hover:bg-matter-light disabled:opacity-50">Cancelar</button>
                <button type="button" @click="submitApproval()" :disabled="processing || !approveModal.rationale || approveModal.rationale.length < 10"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-green-500 to-emerald-600 rounded-quantum hover:from-green-400 hover:to-emerald-500 disabled:opacity-50 flex items-center gap-2">
                    <span x-show="processing" class="animate-spin">⏳</span>
                    <span x-text="processing ? 'Procesando...' : 'Confirmar Aprobación'"></span>
                </button>
            </div>
        </div>
    </div>
    {{-- Modal Rechazar --}}
    <div x-show="rejectModal.open" x-cloak @keydown.escape.window="if(!processing) rejectModal.open = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div @click.away="if(!processing) rejectModal.open = false" class="card-quantum max-w-lg w-full p-0 overflow-hidden">
            <div class="px-6 pt-6 pb-4 border-b border-matter-light">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500/20 to-rose-500/20 rounded-quantum flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Rechazar Solicitud</h3>
                        <p class="text-sm text-gray-400 mt-0.5">Justificación (mín. 10 caracteres)</p>
                    </div>
                </div>
            </div>
            <div class="px-6 py-5">
                <textarea x-model="rejectModal.rationale" rows="4" placeholder="Justificación del rechazo..."
                          :disabled="processing" class="w-full bg-matter-light border border-matter-light rounded-quantum text-white text-sm px-3 py-2.5 focus:border-quantum-500 focus:ring-2 focus:ring-quantum-500/20 resize-none placeholder-gray-500 disabled:opacity-50"></textarea>
                <p class="text-xs text-gray-500 mt-1" x-text="(rejectModal.rationale?.length || 0) + ' / mínimo 10 caracteres'"></p>
            </div>
            <div class="px-6 py-4 border-t border-matter-light bg-matter/50 flex justify-end gap-3">
                <button type="button" @click="rejectModal.open = false" :disabled="processing" class="px-4 py-2.5 text-sm font-medium text-gray-300 border border-matter-light rounded-quantum hover:bg-matter-light disabled:opacity-50">Cancelar</button>
                <button type="button" @click="submitRejection()" :disabled="processing || !rejectModal.rationale || rejectModal.rationale.length < 10"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-red-500 to-rose-600 rounded-quantum hover:from-red-400 hover:to-rose-500 disabled:opacity-50 flex items-center gap-2">
                    <span x-show="processing" class="animate-spin">⏳</span>
                    <span x-text="processing ? 'Procesando...' : 'Confirmar Rechazo'"></span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Tables Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">

    <!-- Most Exposed Projects -->
    <div class="card-quantum p-6">
        <h3 class="text-xl font-bold text-white mb-4">Proyectos Más Expuestos</h3>
        @if($exposedProjects->isEmpty())
            <p class="text-gray-400 text-center py-8">Sin datos aún</p>
        @else
            <div class="space-y-3">
                @foreach($exposedProjects as $i => $project)
                    @php
                        $critColors = ['bajo' => 'green', 'medio' => 'amber', 'alto' => 'orange', 'critico' => 'red'];
                        $critColor = $critColors[$project->nivel_criticidad ?? 'medio'] ?? 'gray';
                        $maxPerms = $exposedProjects->max('permission_count');
                        $barWidth = $maxPerms > 0 ? ($project->permission_count / $maxPerms * 100) : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="text-sm font-medium text-white truncate">{{ $project->nombre_del_proyecto }}</span>
                                <span class="px-2 py-0.5 text-xs rounded-full bg-{{ $critColor }}-500/20 text-{{ $critColor }}-400 border border-{{ $critColor }}-500/30 flex-shrink-0">
                                    {{ ucfirst($project->nivel_criticidad ?? 'medio') }}
                                </span>
                            </div>
                            <span class="text-sm font-bold text-quantum-400 flex-shrink-0 ml-2">{{ $project->permission_count }}</span>
                        </div>
                        <div class="w-full h-2 bg-matter-light rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-quantum-500 to-void-500 rounded-full transition-all duration-1000" style="width: {{ $barWidth }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Top Permission Accumulators -->
    <div class="card-quantum p-6">
        <h3 class="text-xl font-bold text-white mb-4">Mayor Acumulación de Permisos</h3>
        @if($topAccumulators->isEmpty())
            <p class="text-gray-400 text-center py-8">Sin datos aún</p>
        @else
            <div class="space-y-3">
                @foreach($topAccumulators as $i => $user)
                    @php
                        $maxPerms = $topAccumulators->max('permission_count');
                        $barWidth = $maxPerms > 0 ? ($user->permission_count / $maxPerms * 100) : 0;
                        $riskColor = $user->permission_count > 10 ? 'red' : ($user->permission_count > 5 ? 'orange' : 'green');
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-quantum-500 to-void-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-white truncate">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-400 truncate">{{ $user->email }}</p>
                                </div>
                            </div>
                            <span class="text-sm font-bold text-{{ $riskColor }}-400 flex-shrink-0 ml-2">{{ $user->permission_count }}</span>
                        </div>
                        <div class="w-full h-2 bg-matter-light rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-{{ $riskColor }}-500 to-{{ $riskColor }}-400 rounded-full transition-all duration-1000" style="width: {{ $barWidth }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- Monthly Trend — Chart.js Line -->
@php
    $trendLabels = $monthlyTrend->map(fn($m) => \Carbon\Carbon::parse($m->month . '-01')->translatedFormat('M Y'))->values()->toArray();
    $trendScores = $monthlyTrend->map(fn($m) => (int) round($m->avg_score))->values()->toArray();
    $trendTotals = $monthlyTrend->map(fn($m) => (int) $m->total)->values()->toArray();
    $avgOverall  = count($trendScores) > 0 ? (int) round(array_sum($trendScores) / count($trendScores)) : 0;
    $currentMonthKey = now()->format('Y-m');
    $currentMonthIdx = $monthlyTrend->search(fn($m) => $m->month === $currentMonthKey);
    $currentMonthIdx = $currentMonthIdx !== false ? $currentMonthIdx : -1;
@endphp

@if($monthlyTrend->isNotEmpty())
<div class="card-quantum p-6">
    {{-- Header row --}}
    <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
        <div>
            <h3 class="text-xl font-bold text-white">Tendencia Mensual de Riesgo</h3>
            <p class="text-sm text-gray-400 mt-1">Score promedio (0–100) y número de solicitudes por mes.</p>
        </div>
        <div class="flex items-center gap-3 bg-matter-light/50 rounded-quantum px-4 py-2.5 border border-matter-light">
            <span class="text-xs text-gray-400 uppercase tracking-wider">Promedio</span>
            <span class="text-2xl font-bold {{ $avgOverall <= 25 ? 'text-green-400' : ($avgOverall <= 50 ? 'text-amber-400' : ($avgOverall <= 75 ? 'text-orange-400' : 'text-red-400')) }}">{{ $avgOverall }}</span>
        </div>
    </div>
    {{-- Chart container --}}
    <div class="relative" style="height: 280px;">
        <canvas id="riskTrendChart"></canvas>
    </div>
    {{-- Legend --}}
    <div class="flex flex-wrap justify-center gap-6 mt-4 text-xs text-gray-400">
        <div class="flex items-center gap-2"><span class="w-3 h-0.5 bg-[#6DD3C7] rounded-full inline-block"></span> Score promedio</div>
        <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-[#6DD3C7]/30 border border-[#6DD3C7] inline-block"></span> Punto de dato</div>
        @if($currentMonthIdx >= 0)
            <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-white/80 border-2 border-[#6DD3C7] inline-block"></span> Mes actual</div>
        @endif
    </div>
</div>

<script>
(function() {
    function initRiskTrendChart() {
        var canvas = document.getElementById('riskTrendChart');
        if (!canvas) return;
        var ctx = canvas.getContext('2d');

        // Destroy previous instance if it exists (AJAX reload)
        if (canvas._chartInstance) { canvas._chartInstance.destroy(); }

        var labels = @json($trendLabels);
        var scores = @json($trendScores);
        var totals = @json($trendTotals);
        var currentIdx = {{ $currentMonthIdx }};

        // Gradient fill
        var gradient = ctx.createLinearGradient(0, 0, 0, 280);
        gradient.addColorStop(0, 'rgba(109,211,199,0.22)');
        gradient.addColorStop(0.6, 'rgba(109,211,199,0.06)');
        gradient.addColorStop(1, 'rgba(109,211,199,0.0)');

        // Point styling: highlight current month
        var pointBg = scores.map(function(_, i) { return i === currentIdx ? '#ffffff' : '#6DD3C7'; });
        var pointBorder = scores.map(function(_, i) { return i === currentIdx ? '#6DD3C7' : 'rgba(109,211,199,0.4)'; });
        var pointRadius = scores.map(function(_, i) { return i === currentIdx ? 8 : 5; });
        var pointBorderWidth = scores.map(function(_, i) { return i === currentIdx ? 3 : 2; });

        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Score',
                    data: scores,
                    borderColor: '#6DD3C7',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: pointBg,
                    pointBorderColor: pointBorder,
                    pointRadius: pointRadius,
                    pointBorderWidth: pointBorderWidth,
                    pointHoverRadius: 9,
                    pointHoverBackgroundColor: '#ffffff',
                    pointHoverBorderColor: '#6DD3C7',
                    pointHoverBorderWidth: 3,
                    tension: 0.3,
                    fill: true,
                    // stash requests for tooltip
                    _requests: totals
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15,15,25,0.95)',
                        borderColor: 'rgba(109,211,199,0.3)',
                        borderWidth: 1,
                        titleColor: '#ffffff',
                        bodyColor: '#d1d5db',
                        titleFont: { size: 13, weight: 'bold' },
                        bodyFont: { size: 12 },
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            title: function(items) { return items[0].label; },
                            label: function(ctx) {
                                var req = ctx.dataset._requests ? ctx.dataset._requests[ctx.dataIndex] : '';
                                return [
                                    'Score: ' + ctx.parsed.y + ' / 100',
                                    'Solicitudes: ' + req
                                ];
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: { stepSize: 25, color: 'rgba(156,163,175,0.6)', font: { size: 11 } },
                        grid: { color: 'rgba(255,255,255,0.04)', drawBorder: false },
                        border: { display: false }
                    },
                    x: {
                        ticks: { color: 'rgba(156,163,175,0.6)', font: { size: 11 } },
                        grid: { display: false },
                        border: { display: false }
                    }
                },
                animation: { duration: 1200, easing: 'easeOutQuart' }
            }
        });

        canvas._chartInstance = chart;
    }

    // Load Chart.js if not present, then init
    if (typeof Chart === 'undefined') {
        var s = document.createElement('script');
        s.src = 'https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js';
        s.onload = initRiskTrendChart;
        document.head.appendChild(s);
    } else {
        // Small delay for AJAX-injected content
        setTimeout(initRiskTrendChart, 50);
    }
})();
</script>
@else
<div class="card-quantum p-10 text-center">
    <div class="w-16 h-16 bg-gray-500/10 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
        </svg>
    </div>
    <h4 class="text-white font-semibold mb-1">Sin datos de tendencia</h4>
    <p class="text-gray-400 text-sm">No hay solicitudes con score de riesgo en los últimos 6 meses. Verifica los filtros.</p>
</div>
@endif
