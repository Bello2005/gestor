{{-- Tab 1: Panel General — KPIs de salud + Matriz de proyectos (contenido; filtros en riesgo.blade.php) --}}

<!-- KPI Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

    <!-- Proyectos Activos -->
    <div class="card-quantum p-6 group hover:scale-105 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm uppercase tracking-wider mb-1">Proyectos Activos</p>
                <p class="text-3xl font-bold text-white">
                    {{ $activeProjects }}<span class="text-lg text-gray-500 ml-1">/{{ $totalProjects }}</span>
                </p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-quantum-500/20 to-void-500/20 rounded-quantum flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7 text-quantum-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                </svg>
            </div>
        </div>
        @if($totalProjects > 0)
        <div class="mt-3 flex items-center gap-2">
            <div class="flex-1 h-1.5 bg-matter-light rounded-full overflow-hidden">
                <div class="h-full bg-quantum-500 rounded-full transition-all duration-1000" style="width: {{ round($activeProjects / $totalProjects * 100) }}%"></div>
            </div>
            <span class="text-xs text-gray-400">{{ round($activeProjects / $totalProjects * 100) }}%</span>
        </div>
        @endif
    </div>

    <!-- Documentos Vencidos -->
    <div class="card-quantum p-6 group hover:scale-105 transition-all duration-300 {{ $overdueDocumentCount > 0 ? 'border-amber-500/40' : '' }}">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm uppercase tracking-wider mb-1">Docs. Desactualizados</p>
                <p class="text-3xl font-bold {{ $overdueDocumentCount > 0 ? 'text-amber-400' : 'text-green-400' }}">
                    {{ $overdueDocumentCount }}
                </p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br {{ $overdueDocumentCount > 0 ? 'from-amber-500/20 to-orange-500/20' : 'from-green-500/20 to-emerald-500/20' }} rounded-quantum flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7 {{ $overdueDocumentCount > 0 ? 'text-amber-400' : 'text-green-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    @if($overdueDocumentCount > 0)
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"/>
                    @endif
                </svg>
            </div>
        </div>
        <p class="text-xs text-gray-500 mt-2">Proyectos sin evidencias actualizadas (+30 días)</p>
    </div>

    <!-- Plazos Próximos -->
    <div class="card-quantum p-6 group hover:scale-105 transition-all duration-300 {{ $upcomingDeadlines > 0 ? 'border-orange-500/40' : '' }}">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm uppercase tracking-wider mb-1">Plazos Próximos</p>
                <p class="text-3xl font-bold {{ $upcomingDeadlines > 0 ? 'text-orange-400' : 'text-gray-400' }} {{ $upcomingDeadlines > 0 ? 'animate-pulse' : '' }}">
                    {{ $upcomingDeadlines }}
                </p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br {{ $upcomingDeadlines > 0 ? 'from-orange-500/20 to-red-500/20' : 'from-gray-500/20 to-gray-600/20' }} rounded-quantum flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7 {{ $upcomingDeadlines > 0 ? 'text-orange-400' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-xs text-gray-500 mt-2">Proyectos que vencen en menos de 30 días</p>
    </div>

    <!-- Salud Promedio -->
    <div class="card-quantum p-6 group hover:scale-105 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm uppercase tracking-wider mb-1">Salud Promedio</p>
                @php
                    $healthColor = $avgHealthScore >= 70 ? 'green' : ($avgHealthScore >= 40 ? 'amber' : 'red');
                @endphp
                <p class="text-3xl font-bold text-{{ $healthColor }}-400">{{ $avgHealthScore }}%</p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-{{ $healthColor }}-500/20 to-{{ $healthColor }}-600/20 rounded-quantum flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7 text-{{ $healthColor }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
        </div>
        <div class="mt-3 h-2 bg-matter-light rounded-full overflow-hidden">
            <div class="h-full bg-gradient-to-r from-{{ $healthColor }}-500 to-{{ $healthColor }}-400 rounded-full transition-all duration-1000" style="width: {{ $avgHealthScore }}%"></div>
        </div>
    </div>
</div>

<!-- Matriz de Salud de Proyectos -->
<div class="card-quantum p-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
        <div>
            <h3 class="text-xl font-bold text-white">Matriz de Salud de Proyectos</h3>
            <p class="text-sm text-gray-400 mt-1">Vista general del cumplimiento por proyecto activo</p>
        </div>
        <div class="flex items-center gap-4 text-xs">
            <span class="flex items-center gap-1.5">
                <span class="w-3 h-3 rounded-full bg-green-500 shadow-sm shadow-green-500/30"></span>
                <span class="text-gray-400">Normal</span>
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-3 h-3 rounded-full bg-amber-500 shadow-sm shadow-amber-500/30"></span>
                <span class="text-gray-400">Precaución</span>
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-3 h-3 rounded-full bg-red-500 shadow-sm shadow-red-500/30"></span>
                <span class="text-gray-400">Crítico</span>
            </span>
        </div>
    </div>

    @if($healthMatrix->isEmpty())
        <div class="text-center py-12">
            <svg class="w-12 h-12 text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
            </svg>
            <p class="text-gray-400">No hay proyectos activos para mostrar</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-matter-light">
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Proyecto</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider hidden lg:table-cell">Entidad</th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Docs</th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Tiempo</th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Riesgo</th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Salud</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-matter-light/50">
                    @foreach($healthMatrix as $project)
                        @php
                            $semaforoColors = ['verde' => 'green', 'amarillo' => 'amber', 'rojo' => 'red'];
                            $docColor = $semaforoColors[$project->document_semaforo] ?? 'gray';
                            $timeColor = $semaforoColors[$project->timeline_semaforo] ?? 'gray';
                            $riskColor = $semaforoColors[$project->risk_semaforo] ?? 'gray';
                            $healthTextColor = $project->health_score >= 70 ? 'text-green-400' : ($project->health_score >= 40 ? 'text-amber-400' : 'text-red-400');
                        @endphp
                        <tr class="hover:bg-matter-light/30 transition-colors duration-150">
                            <td class="py-3 px-4">
                                <a href="{{ route('proyectos.show', $project->id) }}" class="text-sm font-medium text-white hover:text-quantum-400 transition-colors truncate block max-w-[220px]">
                                    {{ $project->nombre_del_proyecto }}
                                </a>
                                @if($project->days_remaining !== null)
                                    <span class="text-xs {{ $project->days_remaining <= 0 ? 'text-red-400' : ($project->days_remaining <= 30 ? 'text-amber-400' : 'text-gray-500') }}">
                                        {{ $project->days_remaining <= 0 ? 'Vencido' : $project->days_remaining . ' días restantes' }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-400 hidden lg:table-cell">
                                <span class="truncate block max-w-[180px]">{{ $project->entidad_contratante }}</span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center" title="{{ ucfirst($project->document_semaforo) }} — {{ $project->days_since_upload !== null ? $project->days_since_upload . ' días desde última actualización' : 'Sin evidencias' }}">
                                    <span class="w-4 h-4 rounded-full bg-{{ $docColor }}-500 shadow-md shadow-{{ $docColor }}-500/30 inline-block"></span>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center" title="{{ ucfirst($project->timeline_semaforo) }} — {{ $project->time_elapsed_pct }}% del tiempo transcurrido">
                                    <span class="w-4 h-4 rounded-full bg-{{ $timeColor }}-500 shadow-md shadow-{{ $timeColor }}-500/30 inline-block"></span>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @php
                                    $critBadge = [
                                        'bajo' => ['green', 'Bajo'],
                                        'medio' => ['amber', 'Medio'],
                                        'alto' => ['orange', 'Alto'],
                                        'critico' => ['red', 'Crítico'],
                                    ];
                                    $badge = $critBadge[$project->nivel_criticidad ?? 'medio'] ?? ['gray', 'N/A'];
                                @endphp
                                <span class="px-2 py-0.5 text-xs rounded-full bg-{{ $badge[0] }}-500/20 text-{{ $badge[0] }}-400 border border-{{ $badge[0] }}-500/30">
                                    {{ $badge[1] }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <div class="w-12 h-1.5 bg-matter-light rounded-full overflow-hidden hidden sm:block">
                                        <div class="h-full rounded-full transition-all duration-1000 {{ $project->health_score >= 70 ? 'bg-green-500' : ($project->health_score >= 40 ? 'bg-amber-500' : 'bg-red-500') }}" style="width: {{ $project->health_score }}%"></div>
                                    </div>
                                    <span class="text-sm font-bold {{ $healthTextColor }}">{{ $project->health_score }}%</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
