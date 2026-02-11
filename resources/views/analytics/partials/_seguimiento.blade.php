{{-- Tab 2: Seguimiento de Proyectos — Tabla detallada con AJAX --}}

<!-- Filtros -->
<div class="card-quantum p-4">
    <div class="flex flex-wrap items-center gap-4">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
            </svg>
            <span class="text-sm font-medium text-gray-300">Filtros</span>
        </div>

        <select x-model="seguimientoFilters.estado" @change="applyFilters()"
                class="bg-matter border border-matter-light rounded-quantum px-4 py-2 text-sm text-gray-300 focus:ring-quantum-500 focus:border-quantum-500 min-w-[150px]">
            <option value="">Todos los estados</option>
            <option value="activo">Activo</option>
            <option value="cerrado">Cerrado</option>
            <option value="inactivo">Inactivo</option>
        </select>

        <select x-model="seguimientoFilters.criticidad" @change="applyFilters()"
                class="bg-matter border border-matter-light rounded-quantum px-4 py-2 text-sm text-gray-300 focus:ring-quantum-500 focus:border-quantum-500 min-w-[150px]">
            <option value="">Toda criticidad</option>
            <option value="bajo">Bajo</option>
            <option value="medio">Medio</option>
            <option value="alto">Alto</option>
            <option value="critico">Crítico</option>
        </select>

        <button @click="applyFilters()"
                class="ml-auto px-4 py-2 rounded-quantum text-sm text-gray-400 hover:text-white hover:bg-matter-light transition-all duration-200 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Actualizar
        </button>
    </div>
</div>

<!-- Loading State: Quantum Spinner -->
<template x-if="seguimientoLoading">
    @include('analytics.partials._quantum_spinner', ['message' => 'Cargando datos de seguimiento...'])
</template>

<!-- Empty State -->
<template x-if="seguimientoLoaded && !seguimientoLoading && seguimientoData.length === 0">
    <div class="card-quantum p-16 text-center">
        <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        <h3 class="text-lg font-bold text-white mb-2">Sin proyectos</h3>
        <p class="text-gray-400">No se encontraron proyectos con los filtros seleccionados.</p>
    </div>
</template>

<!-- Data Table -->
<template x-if="seguimientoLoaded && !seguimientoLoading && seguimientoData.length > 0">
    <div class="card-quantum overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-matter-light bg-matter/50">
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Proyecto</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider hidden xl:table-cell">Entidad</th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Estado</th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider hidden md:table-cell">Criticidad</th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider hidden lg:table-cell">Plazo</th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Avance</th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Días</th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider hidden md:table-cell">Evidencias</th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider hidden lg:table-cell">Contrato</th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider hidden xl:table-cell">Último Doc.</th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider hidden lg:table-cell">Días s/act.</th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-matter-light/30">
                    <template x-for="project in seguimientoData" :key="project.id">
                        <tr @click="toggleProject(project.id)"
                            class="hover:bg-matter-light/20 transition-colors duration-150 cursor-pointer"
                            :class="expandedProject === project.id ? 'bg-matter-light/20' : ''">
                            <td class="py-3 px-4">
                                <span x-text="project.nombre" class="text-sm font-medium text-white truncate block max-w-[200px]"></span>
                            </td>
                            <td class="py-3 px-4 hidden xl:table-cell">
                                <span x-text="project.entidad" class="text-sm text-gray-400 truncate block max-w-[150px]"></span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2 py-0.5 text-xs rounded-full border inline-block"
                                      :class="getEstadoClasses(project.estado)"
                                      x-text="project.estado.charAt(0).toUpperCase() + project.estado.slice(1)"></span>
                            </td>
                            <td class="py-3 px-4 text-center hidden md:table-cell">
                                <span class="px-2 py-0.5 text-xs rounded-full border inline-block"
                                      :class="getCriticidadClasses(project.criticidad)"
                                      x-text="project.criticidad.charAt(0).toUpperCase() + project.criticidad.slice(1)"></span>
                            </td>
                            <td class="py-3 px-4 text-center hidden lg:table-cell">
                                <span class="text-sm text-gray-300" x-text="project.plazo_display"></span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex flex-col items-center gap-1">
                                    <div class="w-20 h-2 bg-matter-light rounded-full overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-500"
                                             :class="getTimeBarColor(project.time_elapsed_pct)"
                                             :style="`width: ${Math.min(project.time_elapsed_pct, 100)}%`"></div>
                                    </div>
                                    <span class="text-xs text-gray-400" x-text="project.time_elapsed_pct + '%'"></span>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="text-sm font-medium"
                                      :class="getDaysColor(project.days_remaining, project.is_overdue)"
                                      x-text="project.days_remaining !== null ? (project.days_remaining < 0 ? project.days_remaining : project.days_remaining) : 'N/A'"></span>
                            </td>
                            <td class="py-3 px-4 text-center hidden md:table-cell">
                                <span class="text-sm text-gray-300" x-text="project.evidence_count"></span>
                            </td>
                            <td class="py-3 px-4 text-center hidden lg:table-cell">
                                <template x-if="project.has_contract">
                                    <svg class="w-5 h-5 text-green-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </template>
                                <template x-if="!project.has_contract">
                                    <svg class="w-5 h-5 text-red-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </template>
                            </td>
                            <td class="py-3 px-4 text-center hidden xl:table-cell">
                                <span class="text-sm"
                                      :class="project.last_upload_date ? 'text-gray-300' : 'text-red-400'"
                                      x-text="project.last_upload_date || 'Nunca'"></span>
                            </td>
                            <td class="py-3 px-4 text-center hidden lg:table-cell">
                                <span class="text-sm font-medium"
                                      :class="project.days_since_upload === null ? 'text-red-400' : (project.days_since_upload > 60 ? 'text-red-400' : (project.days_since_upload > 30 ? 'text-amber-400' : 'text-green-400'))"
                                      x-text="project.days_since_upload !== null ? project.days_since_upload : '—'"></span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="w-4 h-4 rounded-full inline-block shadow-md"
                                      :class="getSemaforoColor(project.compliance) + ' ' + getSemaforoGlow(project.compliance)"></span>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Info footer -->
        <div class="px-4 py-3 border-t border-matter-light/30 flex items-center justify-between">
            <span class="text-xs text-gray-500" x-text="`${seguimientoData.length} proyecto(s) encontrado(s)`"></span>
            <span class="text-xs text-gray-500">Click en una fila para ver detalles</span>
        </div>
    </div>
</template>

<!-- Expanded Project Detail (shown below table) -->
<template x-if="expandedProject !== null && seguimientoData.length > 0">
    <div class="card-quantum p-6 border-l-4 border-quantum-500">
        <template x-for="project in seguimientoData.filter(p => p.id === expandedProject)" :key="'detail-' + project.id">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-bold text-white" x-text="project.nombre"></h4>
                    <button @click="expandedProject = null" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Timeline Visual -->
                    <div class="space-y-3">
                        <h5 class="text-sm font-semibold text-gray-300 uppercase tracking-wider">Línea de Tiempo</h5>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Plazo</span>
                                <span class="text-white" x-text="project.plazo_display"></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Avance temporal</span>
                                <span class="font-medium" :class="getTimeBarColor(project.time_elapsed_pct).replace('bg-', 'text-')" x-text="project.time_elapsed_pct + '%'"></span>
                            </div>
                            <div class="w-full h-3 bg-matter-light rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-700"
                                     :class="getTimeBarColor(project.time_elapsed_pct)"
                                     :style="`width: ${Math.min(project.time_elapsed_pct, 100)}%`"></div>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Días restantes</span>
                                <span class="font-bold" :class="getDaysColor(project.days_remaining, project.is_overdue)"
                                      x-text="project.days_remaining !== null ? (project.is_overdue ? 'Vencido (' + Math.abs(project.days_remaining) + ' días)' : project.days_remaining + ' días') : 'N/A'"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Documentación -->
                    <div class="space-y-3">
                        <h5 class="text-sm font-semibold text-gray-300 uppercase tracking-wider">Documentación</h5>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-400">Archivo de proyecto</span>
                                <template x-if="project.has_project_file">
                                    <span class="text-green-400 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Cargado
                                    </span>
                                </template>
                                <template x-if="!project.has_project_file">
                                    <span class="text-red-400">Faltante</span>
                                </template>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-400">Contrato/Convenio</span>
                                <template x-if="project.has_contract">
                                    <span class="text-green-400 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Cargado
                                    </span>
                                </template>
                                <template x-if="!project.has_contract">
                                    <span class="text-red-400">Faltante</span>
                                </template>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-400">Evidencias</span>
                                <span class="text-white font-medium" x-text="project.evidence_count + ' archivo(s)'"></span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-400">Última actualización</span>
                                <span :class="project.last_upload_date ? 'text-gray-300' : 'text-red-400'" x-text="project.last_upload_date || 'Sin registros'"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="space-y-3">
                        <h5 class="text-sm font-semibold text-gray-300 uppercase tracking-wider">Acciones</h5>
                        <div class="space-y-2">
                            <a :href="project.url"
                               class="flex items-center gap-2 px-4 py-2.5 bg-quantum-500/10 border border-quantum-500/30 rounded-quantum text-sm text-quantum-400 hover:bg-quantum-500/20 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Ver proyecto
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>
