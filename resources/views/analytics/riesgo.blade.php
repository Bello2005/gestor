@extends('layouts.quantum')

@section('title', 'Vigilancia & Riesgo')

@section('content')
<div x-data="vigilanceDashboard()" class="space-y-6 animate-fadeIn">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-quantum-500/20 to-void-500/20 rounded-quantum flex items-center justify-center">
                    <svg class="w-6 h-6 text-quantum-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-quantum-500 via-void-500 to-photon-500 bg-clip-text text-transparent">
                        Vigilancia & Riesgo
                    </h1>
                    <p class="text-gray-400 mt-1">Panel integral de seguimiento, cumplimiento y riesgo de proyectos</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation: móvil = una palabra por tab; PC = títulos largos -->
    <div class="card-quantum p-2">
        <div class="flex flex-nowrap gap-1.5 sm:gap-2 overflow-x-auto">
            <button @click="switchTab('general')"
                    :class="activeTab === 'general' ? 'bg-gradient-to-r from-quantum-500/20 to-void-500/20 border border-quantum-500/30 text-white shadow-lg shadow-quantum-500/10' : 'text-gray-400 hover:text-white hover:bg-matter-light'"
                    class="sm:flex-1 shrink-0 px-4 sm:px-5 py-3 sm:min-w-[140px] rounded-quantum transition-all duration-200 flex items-center justify-center gap-1.5 sm:gap-2 group whitespace-nowrap">
                <svg class="w-6 h-6 sm:w-5 sm:h-5 flex-shrink-0 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                </svg>
                <span class="font-medium sm:hidden">General</span>
                <span class="font-medium hidden sm:inline">Panel General</span>
            </button>

            <button @click="switchTab('seguimiento')"
                    :class="activeTab === 'seguimiento' ? 'bg-gradient-to-r from-quantum-500/20 to-void-500/20 border border-quantum-500/30 text-white shadow-lg shadow-quantum-500/10' : 'text-gray-400 hover:text-white hover:bg-matter-light'"
                    class="sm:flex-1 shrink-0 px-4 sm:px-5 py-3 sm:min-w-[140px] rounded-quantum transition-all duration-200 flex items-center justify-center gap-1.5 sm:gap-2 group whitespace-nowrap">
                <svg class="w-6 h-6 sm:w-5 sm:h-5 flex-shrink-0 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                <span class="font-medium">Seguimiento</span>
            </button>

            <button @click="switchTab('riesgo')"
                    :class="activeTab === 'riesgo' ? 'bg-gradient-to-r from-quantum-500/20 to-void-500/20 border border-quantum-500/30 text-white shadow-lg shadow-quantum-500/10' : 'text-gray-400 hover:text-white hover:bg-matter-light'"
                    class="sm:flex-1 shrink-0 px-4 sm:px-5 py-3 sm:min-w-[140px] rounded-quantum transition-all duration-200 flex items-center justify-center gap-1.5 sm:gap-2 group whitespace-nowrap">
                <svg class="w-6 h-6 sm:w-5 sm:h-5 flex-shrink-0 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span class="font-medium sm:hidden">Riesgo</span>
                <span class="font-medium hidden sm:inline">Análisis de Riesgo</span>
            </button>

            <button @click="switchTab('alertas')"
                    :class="activeTab === 'alertas' ? 'bg-gradient-to-r from-quantum-500/20 to-void-500/20 border border-quantum-500/30 text-white shadow-lg shadow-quantum-500/10' : 'text-gray-400 hover:text-white hover:bg-matter-light'"
                    class="sm:flex-1 shrink-0 px-4 sm:px-5 py-3 sm:min-w-[140px] rounded-quantum transition-all duration-200 flex items-center justify-center gap-1.5 sm:gap-2 group whitespace-nowrap">
                <span class="relative inline-flex shrink-0">
                    <svg class="w-6 h-6 sm:w-5 sm:h-5 flex-shrink-0 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @if(($alertCounts['total'] ?? 0) > 0)
                        <span class="absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] flex items-center justify-center text-[10px] font-semibold bg-red-500 text-white rounded-full ring-2 ring-matter {{ ($alertCounts['critico'] ?? 0) > 0 ? 'animate-pulse' : '' }} px-1" aria-label="{{ $alertCounts['total'] }} alertas pendientes">
                            {{ $alertCounts['total'] > 99 ? '99+' : $alertCounts['total'] }}
                        </span>
                    @endif
                </span>
                <span class="font-medium">Alertas</span>
            </button>
        </div>
    </div>

    <!-- Tab 1: Panel General -->
    <div x-show="activeTab === 'general'"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="space-y-6">
        <!-- Filtros (como Seguimiento) -->
        <div class="card-quantum p-4">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    <span class="text-sm font-medium text-gray-300">Filtros</span>
                </div>
                <select x-model="generalFilters.estado" @change="applyGeneralFilters()"
                        class="bg-matter border border-matter-light rounded-quantum px-4 py-2 text-sm text-gray-300 focus:ring-quantum-500 focus:border-quantum-500 min-w-[150px]">
                    <option value="">Todos los estados</option>
                    <option value="activo">Activo</option>
                    <option value="cerrado">Cerrado</option>
                    <option value="inactivo">Inactivo</option>
                </select>
                <select x-model="generalFilters.criticidad" @change="applyGeneralFilters()"
                        class="bg-matter border border-matter-light rounded-quantum px-4 py-2 text-sm text-gray-300 focus:ring-quantum-500 focus:border-quantum-500 min-w-[150px]">
                    <option value="">Toda criticidad</option>
                    <option value="bajo">Bajo</option>
                    <option value="medio">Medio</option>
                    <option value="alto">Alto</option>
                    <option value="critico">Crítico</option>
                </select>
                <button @click="applyGeneralFilters()"
                        class="ml-auto px-4 py-2 rounded-quantum text-sm text-gray-400 hover:text-white hover:bg-matter-light transition-all duration-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Actualizar
                </button>
            </div>
        </div>
        <template x-if="generalLoading">
            @include('analytics.partials._quantum_spinner', ['message' => 'Cargando panel general...'])
        </template>
        <div x-show="!generalLoading" x-ref="generalContent">
            @include('analytics.partials._panel_general')
        </div>
    </div>

    <!-- Tab 2: Seguimiento -->
    <div x-show="activeTab === 'seguimiento'"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="space-y-6">
        @include('analytics.partials._seguimiento')
    </div>

    <!-- Tab 3: Análisis de Riesgo -->
    <div x-show="activeTab === 'riesgo'"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="space-y-6">
        <!-- Filtros (como Seguimiento) -->
        <div class="card-quantum p-4">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    <span class="text-sm font-medium text-gray-300">Filtros</span>
                </div>
                <select x-model="riskFilters.risk_level" @change="applyRiskFilters()"
                        class="bg-matter border border-matter-light rounded-quantum px-4 py-2 text-sm text-gray-300 focus:ring-quantum-500 focus:border-quantum-500 min-w-[150px]">
                    <option value="">Todos los niveles</option>
                    <option value="bajo">Bajo</option>
                    <option value="medio">Medio</option>
                    <option value="alto">Alto</option>
                    <option value="critico">Crítico</option>
                </select>
                <select x-model="riskFilters.status" @change="applyRiskFilters()"
                        class="bg-matter border border-matter-light rounded-quantum px-4 py-2 text-sm text-gray-300 focus:ring-quantum-500 focus:border-quantum-500 min-w-[180px]">
                    <option value="">Todos los estados</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="aprobada">Aprobada</option>
                    <option value="rechazada">Rechazada</option>
                    <option value="expirada">Expirada</option>
                    <option value="revocada">Revocada</option>
                </select>
                <button @click="applyRiskFilters()"
                        class="ml-auto px-4 py-2 rounded-quantum text-sm text-gray-400 hover:text-white hover:bg-matter-light transition-all duration-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Actualizar
                </button>
            </div>
        </div>
        <template x-if="riskLoading">
            @include('analytics.partials._quantum_spinner', ['message' => 'Cargando análisis de riesgo...'])
        </template>
        <div x-show="!riskLoading" x-ref="riskContent">
            @include('analytics.partials._analisis_riesgo')
        </div>
    </div>

    <!-- Tab 4: Alertas -->
    <div x-show="activeTab === 'alertas'"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="space-y-6">
        <!-- Filtros -->
        <div class="card-quantum p-4">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    <span class="text-sm font-medium text-gray-300">Filtros</span>
                </div>
                <select x-model="alertaSeveridadFilter"
                        class="bg-matter border border-matter-light rounded-quantum px-4 py-2 text-sm text-gray-300 focus:ring-quantum-500 focus:border-quantum-500 min-w-[180px]">
                    <option value="">Todas las severidades</option>
                    <option value="critico">Crítico</option>
                    <option value="alto">Alto</option>
                    <option value="medio">Medio</option>
                    <option value="informativo">Informativo</option>
                </select>
                <button @click="loadAlertas()"
                        class="ml-auto px-4 py-2 rounded-quantum text-sm text-gray-400 hover:text-white hover:bg-matter-light transition-all duration-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Actualizar
                </button>
            </div>
        </div>
        <template x-if="alertasLoading">
            @include('analytics.partials._quantum_spinner', ['message' => 'Cargando alertas...'])
        </template>
        <div x-show="!alertasLoading" x-ref="alertasContent">
            @include('analytics.partials._alertas')
        </div>
    </div>

</div>

<script>
function vigilanceDashboard() {
    return {
        activeTab: '{{ $activeTab ?? "general" }}',

        // Tab 1: Panel General
        generalLoading: false,
        generalFilters: { estado: '', criticidad: '' },

        // Tab 2: Seguimiento
        seguimientoLoaded: false,
        seguimientoData: [],
        seguimientoLoading: false,
        seguimientoFilters: { estado: '', criticidad: '' },
        expandedProject: null,

        // Tab 3: Análisis de Riesgo
        riskLoading: false,
        riskFilters: { risk_level: '', status: '' },

        // Tab 4: Alertas
        alertaSeveridadFilter: '',
        alertasLoading: false,

        switchTab(tab) {
            this.activeTab = tab;
            if (tab === 'seguimiento' && !this.seguimientoLoaded) {
                this.loadSeguimiento();
            }
        },

        async loadPanelGeneral() {
            this.generalLoading = true;
            try {
                const params = new URLSearchParams();
                if (this.generalFilters.estado) params.set('estado', this.generalFilters.estado);
                if (this.generalFilters.criticidad) params.set('criticidad', this.generalFilters.criticidad);
                const response = await fetch(`/analytics/panel-general?${params.toString()}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const html = await response.text();
                if (this.$refs.generalContent) this.$refs.generalContent.innerHTML = html;
            } catch (e) {
                console.error('Error cargando panel general:', e);
                if (window.showToast) window.showToast('Error al cargar panel general', 'error');
            } finally {
                this.generalLoading = false;
            }
        },

        applyGeneralFilters() {
            this.loadPanelGeneral();
        },

        async loadRiskAnalysis() {
            this.riskLoading = true;
            try {
                const params = new URLSearchParams();
                if (this.riskFilters.risk_level) params.set('risk_level', this.riskFilters.risk_level);
                if (this.riskFilters.status) params.set('status', this.riskFilters.status);
                const response = await fetch(`/analytics/riesgo-data?${params.toString()}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const html = await response.text();
                if (this.$refs.riskContent) {
                    this.$refs.riskContent.innerHTML = html;
                    if (typeof Alpine !== 'undefined' && Alpine.initTree) Alpine.initTree(this.$refs.riskContent);
                }
            } catch (e) {
                console.error('Error cargando análisis de riesgo:', e);
                if (window.showToast) window.showToast('Error al cargar análisis de riesgo', 'error');
            } finally {
                this.riskLoading = false;
            }
        },

        applyRiskFilters() {
            this.loadRiskAnalysis();
        },

        async loadAlertas() {
            this.alertasLoading = true;
            try {
                const params = new URLSearchParams();
                if (this.alertaSeveridadFilter) params.set('severidad', this.alertaSeveridadFilter);
                const response = await fetch(`/analytics/alertas?${params.toString()}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const html = await response.text();
                if (this.$refs.alertasContent) {
                    this.$refs.alertasContent.innerHTML = html;
                    if (typeof Alpine !== 'undefined' && Alpine.initTree) Alpine.initTree(this.$refs.alertasContent);
                }
            } catch (e) {
                console.error('Error cargando alertas:', e);
                if (window.showToast) window.showToast('Error al cargar alertas', 'error');
            } finally {
                this.alertasLoading = false;
            }
        },

        async loadSeguimiento() {
            this.seguimientoLoading = true;
            try {
                const params = new URLSearchParams();
                if (this.seguimientoFilters.estado) params.set('estado', this.seguimientoFilters.estado);
                if (this.seguimientoFilters.criticidad) params.set('criticidad', this.seguimientoFilters.criticidad);

                const response = await fetch(`/analytics/seguimiento?${params.toString()}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                });
                const data = await response.json();
                this.seguimientoData = data.projects || [];
                this.seguimientoLoaded = true;
            } catch (error) {
                console.error('Error cargando seguimiento:', error);
                if (window.showToast) {
                    window.showToast('Error al cargar datos de seguimiento', 'error');
                }
            } finally {
                this.seguimientoLoading = false;
            }
        },

        applyFilters() {
            this.seguimientoLoaded = false;
            this.loadSeguimiento();
        },

        toggleProject(id) {
            this.expandedProject = this.expandedProject === id ? null : id;
        },

        getSemaforoColor(semaforo) {
            const colors = { verde: 'bg-green-500', amarillo: 'bg-amber-500', rojo: 'bg-red-500' };
            return colors[semaforo] || 'bg-gray-500';
        },

        getSemaforoGlow(semaforo) {
            const glows = { verde: 'shadow-green-500/40', amarillo: 'shadow-amber-500/40', rojo: 'shadow-red-500/40' };
            return glows[semaforo] || '';
        },

        getEstadoClasses(estado) {
            const classes = {
                activo: 'bg-green-500/20 text-green-400 border-green-500/30',
                cerrado: 'bg-red-500/20 text-red-400 border-red-500/30',
                inactivo: 'bg-gray-500/20 text-gray-400 border-gray-500/30',
            };
            return classes[estado] || 'bg-gray-500/20 text-gray-400 border-gray-500/30';
        },

        getCriticidadClasses(crit) {
            const classes = {
                bajo: 'bg-green-500/20 text-green-400 border-green-500/30',
                medio: 'bg-amber-500/20 text-amber-400 border-amber-500/30',
                alto: 'bg-orange-500/20 text-orange-400 border-orange-500/30',
                critico: 'bg-red-500/20 text-red-400 border-red-500/30',
            };
            return classes[crit] || 'bg-gray-500/20 text-gray-400 border-gray-500/30';
        },

        getTimeBarColor(pct) {
            if (pct > 90) return 'bg-red-500';
            if (pct > 70) return 'bg-amber-500';
            return 'bg-quantum-500';
        },

        getDaysColor(days, isOverdue) {
            if (isOverdue || days < 0) return 'text-red-400 font-bold';
            if (days <= 30) return 'text-amber-400';
            return 'text-gray-300';
        }
    }
}
</script>
@endsection
