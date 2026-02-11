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
        @include('analytics.partials._panel_general')
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
        @include('analytics.partials._analisis_riesgo')
    </div>

    <!-- Tab 4: Alertas -->
    <div x-show="activeTab === 'alertas'"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="space-y-6">
        @include('analytics.partials._alertas')
    </div>

</div>

<script>
function vigilanceDashboard() {
    return {
        activeTab: '{{ $activeTab ?? "general" }}',

        // Tab 2: Seguimiento
        seguimientoLoaded: false,
        seguimientoData: [],
        seguimientoLoading: false,
        seguimientoFilters: { estado: '', criticidad: '' },
        expandedProject: null,

        switchTab(tab) {
            this.activeTab = tab;
            if (tab === 'seguimiento' && !this.seguimientoLoaded) {
                this.loadSeguimiento();
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
