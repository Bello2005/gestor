{{-- Tab 3: Análisis de Riesgo — contenido existente preservado --}}

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

<!-- Tables Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

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

<!-- Monthly Trend -->
@if($monthlyTrend->isNotEmpty())
<div class="card-quantum p-6">
    <h3 class="text-xl font-bold text-white mb-4">Tendencia Mensual de Riesgo</h3>
    <div class="overflow-x-auto">
        <div class="flex items-end gap-4 h-48 min-w-[400px]">
            @foreach($monthlyTrend as $month)
                @php
                    $barHeight = $month->avg_score > 0 ? max(($month->avg_score / 100) * 100, 5) : 5;
                    $barColor = $month->avg_score <= 25 ? 'from-green-500 to-emerald-400' : ($month->avg_score <= 50 ? 'from-amber-500 to-yellow-400' : ($month->avg_score <= 75 ? 'from-orange-500 to-amber-400' : 'from-red-500 to-rose-400'));
                @endphp
                <div class="flex-1 flex flex-col items-center gap-2">
                    <span class="text-xs font-semibold {{ $month->avg_score <= 25 ? 'text-green-400' : ($month->avg_score <= 50 ? 'text-amber-400' : ($month->avg_score <= 75 ? 'text-orange-400' : 'text-red-400')) }}">
                        {{ round($month->avg_score) }}
                    </span>
                    <div class="w-full bg-gradient-to-t {{ $barColor }} rounded-t-lg transition-all duration-1000 hover:opacity-80" style="height: {{ $barHeight }}%"></div>
                    <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($month->month . '-01')->format('M') }}</span>
                    <span class="text-xs text-gray-500">{{ $month->total }} sol.</span>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif
