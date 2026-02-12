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
                        <span x-show="!alertasBadgeSeen" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-0"
                              class="absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] flex items-center justify-center text-[10px] font-semibold bg-red-500 text-white rounded-full ring-2 ring-matter {{ ($alertCounts['critico'] ?? 0) > 0 ? 'animate-pulse' : '' }} px-1" aria-label="{{ $alertCounts['total'] }} alertas pendientes">
                            {{ $alertCounts['total'] > 99 ? '99+' : $alertCounts['total'] }}
                        </span>
                    @endif
                </span>
                <span class="font-medium">Alertas</span>
            </button>

            <button @click="switchTab('prorrogas')"
                    :class="activeTab === 'prorrogas' ? 'bg-gradient-to-r from-quantum-500/20 to-void-500/20 border border-quantum-500/30 text-white shadow-lg shadow-quantum-500/10' : 'text-gray-400 hover:text-white hover:bg-matter-light'"
                    class="sm:flex-1 shrink-0 px-4 sm:px-5 py-3 sm:min-w-[140px] rounded-quantum transition-all duration-200 flex items-center justify-center gap-1.5 sm:gap-2 group whitespace-nowrap">
                <span class="relative inline-flex shrink-0">
                    <svg class="w-6 h-6 sm:w-5 sm:h-5 flex-shrink-0 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    @if(($prorrogaCounts['pendiente'] ?? 0) > 0)
                        <span x-show="!prorrogasBadgeSeen" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-0"
                              class="absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] flex items-center justify-center text-[10px] font-semibold bg-amber-500 text-white rounded-full ring-2 ring-matter animate-pulse px-1">
                            {{ ($prorrogaCounts['pendiente'] ?? 0) > 99 ? '99+' : $prorrogaCounts['pendiente'] }}
                        </span>
                    @endif
                </span>
                <span class="font-medium sm:hidden">Prórrogas</span>
                <span class="font-medium hidden sm:inline">Prórrogas</span>
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

    <!-- Tab 5: Prórrogas -->
    <div x-show="activeTab === 'prorrogas'"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0">

        {{-- Filter bar --}}
        <div class="card-quantum p-3 mb-4">
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2 text-xs text-gray-500 uppercase tracking-wider font-semibold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filtros
                </div>
                <select x-model="prorrogasFilters.estado" @change="loadProrrogasTab()"
                        class="bg-matter-light border border-matter-light rounded-quantum px-3 py-1.5 text-sm text-gray-300 focus:border-quantum-500 focus:ring-1 focus:ring-quantum-500/30 outline-none transition-all">
                    <option value="">Todos los estados</option>
                    <option value="pendiente">Pendientes</option>
                    <option value="aprobada">Aprobadas</option>
                    <option value="rechazada">Rechazadas</option>
                </select>
                <input type="text" x-model="prorrogasFilters.search" @input.debounce.400ms="loadProrrogasTab()"
                       placeholder="Buscar proyecto..."
                       class="bg-matter-light border border-matter-light rounded-quantum px-3 py-1.5 text-sm text-gray-300 placeholder-gray-500 focus:border-quantum-500 focus:ring-1 focus:ring-quantum-500/30 outline-none transition-all w-full sm:w-48">
                <button @click="loadProrrogasTab()"
                        class="ml-auto px-4 py-2 rounded-quantum text-sm text-gray-400 hover:text-white hover:bg-matter-light transition-all duration-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Actualizar
                </button>
            </div>
        </div>

        <template x-if="prorrogasTabLoading">
            @include('analytics.partials._quantum_spinner', ['message' => 'Cargando prórrogas...'])
        </template>
        <div x-show="!prorrogasTabLoading" x-ref="prorrogasContent">
            {{-- Contenido se carga vía AJAX --}}
        </div>
    </div>

    {{-- Modal de Prórroga --}}
    @include('analytics.partials._modal_prorroga')

    {{-- ═══ Quantum Decision Modal ═══ --}}
    <div x-show="decisionModalOpen" x-cloak
         class="fixed inset-0 z-[9998] flex items-center justify-center p-4"
         @keydown.escape.window="decisionModalOpen && closeDecisionModal()">

        {{-- Backdrop --}}
        <div x-show="decisionModalOpen"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="absolute inset-0 bg-black/70 backdrop-blur-md"
             @click="closeDecisionModal()"></div>

        {{-- Panel --}}
        <div x-show="decisionModalOpen"
             x-transition:enter="transition ease-[cubic-bezier(.21,1.02,.73,1)] duration-400" x-transition:enter-start="opacity-0 scale-90 translate-y-8" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="relative w-full max-w-lg card-quantum overflow-hidden shadow-2xl"
             :class="decisionModalType === 'approve' ? 'shadow-green-500/5' : 'shadow-red-500/5'"
             @click.stop>

            {{-- Top accent bar --}}
            <div class="absolute inset-x-0 top-0 h-1 rounded-t-quantum"
                 :class="decisionModalType === 'approve'
                    ? 'bg-gradient-to-r from-green-500 via-emerald-400 to-green-500'
                    : 'bg-gradient-to-r from-red-500 via-rose-400 to-red-500'"></div>

            {{-- Ambient glow --}}
            <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-64 h-64 rounded-full blur-3xl opacity-[0.07] pointer-events-none"
                 :class="decisionModalType === 'approve' ? 'bg-green-400' : 'bg-red-400'"></div>

            {{-- Close button --}}
            <button @click="closeDecisionModal()"
                    class="absolute top-4 right-4 w-8 h-8 rounded-quantum flex items-center justify-center text-gray-500 hover:text-white hover:bg-matter-light transition-all duration-200 z-20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            {{-- Header --}}
            <div class="relative px-6 pt-7 pb-5">
                <div class="flex items-start gap-4">
                    {{-- Animated Icon --}}
                    <div class="w-12 h-12 rounded-quantum flex items-center justify-center flex-shrink-0 transition-all duration-300"
                         :class="decisionModalType === 'approve'
                            ? 'bg-gradient-to-br from-green-500/20 to-emerald-500/10 ring-1 ring-green-500/25 shadow-lg shadow-green-500/10'
                            : 'bg-gradient-to-br from-red-500/20 to-rose-500/10 ring-1 ring-red-500/25 shadow-lg shadow-red-500/10'">
                        <template x-if="decisionModalType === 'approve'">
                            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </template>
                        <template x-if="decisionModalType === 'reject'">
                            <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </template>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white" x-text="decisionModalType === 'approve' ? 'Aprobar Prórroga' : 'Rechazar Prórroga'"></h3>
                        <p class="text-sm text-gray-400 mt-0.5" x-text="decisionModalType === 'approve' ? 'Se agregarán los días adicionales al plazo del proyecto' : 'La solicitud de extensión será denegada'"></p>
                    </div>
                </div>
            </div>

            {{-- Body --}}
            <div class="relative px-6 pb-5">
                <label class="text-[11px] uppercase tracking-wider text-gray-500 font-semibold mb-2 block"
                       x-text="decisionModalType === 'approve' ? 'Justificación de aprobación' : 'Motivo del rechazo'"></label>
                <div class="relative">
                    <textarea id="decision-comentario-input"
                              x-model="decisionModalComentario"
                              rows="4"
                              class="w-full px-4 py-3 rounded-quantum bg-matter-light/50 border text-white text-sm placeholder-gray-600
                                     focus:ring-2 focus:outline-none transition-all duration-200 resize-none"
                              :class="decisionModalType === 'approve'
                                ? 'border-green-500/20 focus:border-green-500/40 focus:ring-green-500/10'
                                : 'border-red-500/20 focus:border-red-500/40 focus:ring-red-500/10'"
                              :placeholder="decisionModalType === 'approve' ? 'Describa por qué se aprueba esta extensión de plazo...' : 'Explique el motivo por el cual se rechaza esta solicitud...'"
                              @keydown.meta.enter="submitDecision()"
                              @keydown.ctrl.enter="submitDecision()"></textarea>
                    {{-- Character counter --}}
                    <div class="absolute bottom-3 right-3 text-[10px] px-1.5 py-0.5 rounded-full transition-all duration-200"
                         :class="decisionModalComentario.length >= 10
                            ? (decisionModalType === 'approve' ? 'bg-green-500/15 text-green-400' : 'bg-red-500/15 text-red-400')
                            : 'bg-matter-light/50 text-gray-500'"
                         x-text="decisionModalComentario.length + '/10'"></div>
                </div>
                <p class="text-[11px] text-gray-600 mt-1.5 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Esta decisión será registrada y notificada al solicitante
                </p>
            </div>

            {{-- Footer --}}
            <div class="relative px-6 py-4 border-t border-matter-light/50 flex items-center justify-between">
                <div class="text-[10px] text-gray-600 hidden sm:flex items-center gap-1.5">
                    <kbd class="px-1.5 py-0.5 rounded-md bg-matter-light border border-matter-light text-[9px] font-mono text-gray-500">⌘</kbd>
                    <span>+</span>
                    <kbd class="px-1.5 py-0.5 rounded-md bg-matter-light border border-matter-light text-[9px] font-mono text-gray-500">Enter</kbd>
                    <span class="ml-0.5">para enviar</span>
                </div>
                <div class="flex items-center gap-2.5 ml-auto">
                    <button @click="closeDecisionModal()"
                            class="px-4 py-2 text-sm text-gray-400 hover:text-white rounded-quantum hover:bg-matter-light transition-all duration-200">
                        Cancelar
                    </button>
                    <button @click="submitDecision()"
                            :disabled="decisionModalSubmitting || decisionModalComentario.trim().length < 10"
                            class="px-5 py-2.5 text-sm font-semibold text-white rounded-quantum transition-all duration-300
                                   disabled:opacity-30 disabled:cursor-not-allowed disabled:shadow-none flex items-center gap-2"
                            :class="decisionModalType === 'approve'
                                ? 'bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-400 hover:to-emerald-500 shadow-lg shadow-green-500/25 hover:shadow-green-500/40 hover:scale-[1.02]'
                                : 'bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-400 hover:to-rose-500 shadow-lg shadow-red-500/25 hover:shadow-red-500/40 hover:scale-[1.02]'">
                        <template x-if="decisionModalSubmitting">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                        </template>
                        <template x-if="!decisionModalSubmitting">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 x-show="decisionModalType === 'approve'">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </template>
                        <template x-if="!decisionModalSubmitting">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 x-show="decisionModalType === 'reject'">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </template>
                        <span x-text="decisionModalSubmitting
                            ? 'Procesando...'
                            : (decisionModalType === 'approve' ? 'Confirmar Aprobación' : 'Confirmar Rechazo')"></span>
                    </button>
                </div>
            </div>
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
        alertasBadgeSeen: false,

        // Tab 5: Prórrogas
        prorrogasTabLoading: false,
        prorrogasTabLoaded: false,
        prorrogasFilters: { estado: '', search: '' },
        prorrogasBadgeSeen: false,

        // Decision Modal (Liquid Glass)
        decisionModalOpen: false,
        decisionModalType: '', // 'approve' or 'reject'
        decisionModalProrrogaId: null,
        decisionModalComentario: '',
        decisionModalSubmitting: false,

        // Prórrogas
        prorrogaModalOpen: false,
        prorrogaProjectId: null,
        prorrogaProjectName: '',
        prorrogaProjectFechaFin: '',
        prorrogaForm: {
            tipo_solicitud: 'prorroga',
            causa_tipo: '',
            causa_subtipo: '',
            dias_solicitados: '',
            justificacion: '',
            impacto_descripcion: '',
            departamento_afectado: '',
            referencia_ideam: '',
            referencia_declaratoria: '',
        },
        prorrogaSubmitting: false,
        prorrogaFile: null,
        prorrogaErrors: {},
        prorrogasCache: {},
        prorrogasLoading: false,

        switchTab(tab) {
            this.activeTab = tab;
            if (tab === 'alertas') this.alertasBadgeSeen = true;
            if (tab === 'prorrogas') this.prorrogasBadgeSeen = true;
            if (tab === 'seguimiento' && !this.seguimientoLoaded) {
                this.loadSeguimiento();
            }
            if (tab === 'prorrogas' && !this.prorrogasTabLoaded) {
                this.loadProrrogasTab();
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

        async loadProrrogasTab() {
            this.prorrogasTabLoading = true;
            try {
                const params = new URLSearchParams();
                if (this.prorrogasFilters.estado) params.set('estado', this.prorrogasFilters.estado);
                if (this.prorrogasFilters.search) params.set('search', this.prorrogasFilters.search);
                const response = await fetch(`/analytics/prorrogas?${params.toString()}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const html = await response.text();
                if (this.$refs.prorrogasContent) {
                    this.$refs.prorrogasContent.innerHTML = html;
                    if (typeof Alpine !== 'undefined' && Alpine.initTree) Alpine.initTree(this.$refs.prorrogasContent);
                }
                this.prorrogasTabLoaded = true;
            } catch (e) {
                console.error('Error cargando prórrogas:', e);
                if (window.showToast) window.showToast('Error al cargar prórrogas', 'error');
            } finally {
                this.prorrogasTabLoading = false;
            }
        },

        approveProrroga(id) {
            this.decisionModalType = 'approve';
            this.decisionModalProrrogaId = id;
            this.decisionModalComentario = '';
            this.decisionModalSubmitting = false;
            this.decisionModalOpen = true;
            this.$nextTick(() => {
                const ta = document.getElementById('decision-comentario-input');
                if (ta) ta.focus();
            });
        },

        rejectProrroga(id) {
            this.decisionModalType = 'reject';
            this.decisionModalProrrogaId = id;
            this.decisionModalComentario = '';
            this.decisionModalSubmitting = false;
            this.decisionModalOpen = true;
            this.$nextTick(() => {
                const ta = document.getElementById('decision-comentario-input');
                if (ta) ta.focus();
            });
        },

        closeDecisionModal() {
            this.decisionModalOpen = false;
            this.decisionModalComentario = '';
            this.decisionModalProrrogaId = null;
        },

        async submitDecision() {
            const comentario = this.decisionModalComentario.trim();
            if (comentario.length < 10) {
                if (window.showToast) window.showToast('El comentario debe tener al menos 10 caracteres.', 'warning');
                return;
            }
            this.decisionModalSubmitting = true;
            const action = this.decisionModalType === 'approve' ? 'approve' : 'reject';
            try {
                const response = await fetch(`/prorrogas/${this.decisionModalProrrogaId}/${action}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ decision_comentario: comentario }),
                });
                const data = await response.json();
                if (response.ok && data.success) {
                    if (window.showToast) window.showToast(data.message || (action === 'approve' ? 'Prórroga aprobada.' : 'Prórroga rechazada.'), 'success');
                    this.closeDecisionModal();
                    this.loadProrrogasTab();
                } else {
                    if (window.showToast) window.showToast(data.error || 'Error al procesar la solicitud.', 'error');
                }
            } catch (e) {
                console.error('Error al procesar prórroga:', e);
                if (window.showToast) window.showToast('Error de conexión. Intente de nuevo.', 'error');
            } finally {
                this.decisionModalSubmitting = false;
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
        },

        // ─── Prórrogas ─────────────────────────────

        get causaSubtipoOptions() {
            const map = {
                'fuerza_mayor': [
                    { value: 'climatica', label: 'Evento Climático (lluvias, sequía, heladas)' },
                    { value: 'sismica', label: 'Evento Sísmico' },
                    { value: 'inundacion', label: 'Inundación' },
                    { value: 'deslizamiento', label: 'Deslizamiento de tierra' },
                    { value: 'otro_natural', label: 'Otro evento natural' },
                ],
                'caso_fortuito': [
                    { value: 'orden_publico', label: 'Alteración de orden público' },
                    { value: 'paro', label: 'Paro / Protesta social' },
                    { value: 'pandemia', label: 'Pandemia / Emergencia sanitaria' },
                    { value: 'otro_humano', label: 'Otro evento humano' },
                ],
                'necesidad_servicio': [
                    { value: 'cambio_alcance', label: 'Cambio de alcance' },
                    { value: 'disponibilidad_presupuestal', label: 'Disponibilidad presupuestal' },
                    { value: 'ajuste_diseno', label: 'Ajuste de diseño técnico' },
                ],
                'mutuo_acuerdo': [
                    { value: 'conveniencia_partes', label: 'Conveniencia de las partes' },
                ],
            };
            return map[this.prorrogaForm.causa_tipo] || [];
        },

        get prorrogaFechaFinPropuesta() {
            if (!this.prorrogaProjectFechaFin || !this.prorrogaForm.dias_solicitados) return '';
            const parts = this.prorrogaProjectFechaFin.split('/');
            if (parts.length !== 3) return '';
            const date = new Date(parts[2], parts[1] - 1, parts[0]);
            date.setDate(date.getDate() + parseInt(this.prorrogaForm.dias_solicitados));
            return String(date.getDate()).padStart(2, '0') + '/' + String(date.getMonth() + 1).padStart(2, '0') + '/' + date.getFullYear();
        },

        get isProrrogaFormValid() {
            const dias = Number(this.prorrogaForm.dias_solicitados);
            return this.prorrogaForm.tipo_solicitud &&
                   this.prorrogaForm.causa_tipo &&
                   dias >= 1 && dias <= 365 && !isNaN(dias) &&
                   (this.prorrogaForm.justificacion?.length || 0) >= 30;
        },

        openProrrogaModal(project) {
            this.prorrogaProjectId = project.id;
            this.prorrogaProjectName = project.nombre;
            this.prorrogaProjectFechaFin = project.has_prorroga && project.fecha_fin_ajustada
                ? project.fecha_fin_ajustada
                : (project.fecha_fin_original || '');
            this.prorrogaForm = {
                tipo_solicitud: 'prorroga',
                causa_tipo: '',
                causa_subtipo: '',
                dias_solicitados: '',
                justificacion: '',
                impacto_descripcion: '',
                departamento_afectado: '',
                referencia_ideam: '',
                referencia_declaratoria: '',
            };
            this.prorrogaFile = null;
            this.prorrogaErrors = {};
            this.prorrogaModalOpen = true;
        },

        closeProrrogaModal() {
            this.prorrogaModalOpen = false;
        },

        async submitProrroga() {
            this.prorrogaSubmitting = true;
            this.prorrogaErrors = {};

            // ─── Client-side validation ───────────────────────────
            const errors = {};
            if (!this.prorrogaForm.tipo_solicitud) {
                errors.tipo_solicitud = ['Seleccione el tipo de solicitud.'];
            }
            if (!this.prorrogaForm.causa_tipo) {
                errors.causa_tipo = ['Seleccione la causa principal.'];
            }
            const dias = Number(this.prorrogaForm.dias_solicitados);
            if (!this.prorrogaForm.dias_solicitados || isNaN(dias) || dias < 1) {
                errors.dias_solicitados = ['Ingrese los días solicitados (mínimo 1).'];
            } else if (dias > 365) {
                errors.dias_solicitados = ['Los días solicitados no pueden exceder 365.'];
            }
            const justLen = (this.prorrogaForm.justificacion || '').length;
            if (justLen < 30) {
                errors.justificacion = ['La justificación debe tener al menos 30 caracteres (' + justLen + '/30).'];
            }

            if (Object.keys(errors).length > 0) {
                this.prorrogaErrors = errors;
                const errorList = Object.values(errors).flat();
                errorList.forEach(function(msg) {
                    window.showToast(msg, 'warning', 6000);
                });
                this.prorrogaSubmitting = false;
                return;
            }

            // ─── Build FormData ───────────────────────────────────
            const formData = new FormData();
            formData.append('proyecto_id', String(this.prorrogaProjectId || ''));
            formData.append('tipo_solicitud', String(this.prorrogaForm.tipo_solicitud || ''));
            formData.append('causa_tipo', String(this.prorrogaForm.causa_tipo || ''));
            if (this.prorrogaForm.causa_subtipo) formData.append('causa_subtipo', String(this.prorrogaForm.causa_subtipo));
            formData.append('dias_solicitados', String(dias));
            formData.append('justificacion', String(this.prorrogaForm.justificacion || ''));
            if (this.prorrogaForm.impacto_descripcion) formData.append('impacto_descripcion', String(this.prorrogaForm.impacto_descripcion));
            if (this.prorrogaForm.departamento_afectado) formData.append('departamento_afectado', String(this.prorrogaForm.departamento_afectado));
            if (this.prorrogaForm.referencia_ideam) formData.append('referencia_ideam', String(this.prorrogaForm.referencia_ideam));
            if (this.prorrogaForm.referencia_declaratoria) formData.append('referencia_declaratoria', String(this.prorrogaForm.referencia_declaratoria));
            if (this.prorrogaFile) formData.append('evidencia', this.prorrogaFile);

            try {
                const response = await fetch('/prorrogas', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                let data;
                try {
                    data = await response.json();
                } catch (jsonError) {
                    window.showToast('Error al procesar la respuesta del servidor.', 'error');
                    return;
                }

                if (!response.ok) {
                    if (response.status === 422 && data.errors) {
                        this.prorrogaErrors = data.errors;
                        const errorList = Object.values(data.errors).flat();
                        // Show each validation error as its own toast
                        errorList.forEach(function(msg) {
                            window.showToast(msg, 'error', 7000);
                        });
                        return;
                    } else {
                        window.showToast(data.error || data.message || 'Error al enviar la solicitud.', 'error');
                        return;
                    }
                }

                // Success
                this.closeProrrogaModal();
                delete this.prorrogasCache[this.prorrogaProjectId];
                window.showToast(data.message || 'Solicitud de prórroga enviada exitosamente.', 'success');
                this.loadSeguimiento();
            } catch (e) {
                console.error('Error al enviar prórroga:', e);
                window.showToast('Error de conexión. Verifique su red e intente de nuevo.', 'error');
            } finally {
                this.prorrogaSubmitting = false;
            }
        },

        async loadProrrogasForProject(projectId) {
            if (this.prorrogasCache[projectId]) return;
            this.prorrogasLoading = true;
            try {
                const response = await fetch(`/prorrogas/proyecto/${projectId}`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await response.json();
                this.prorrogasCache[projectId] = data.prorrogas || [];
            } catch (e) {
                console.error('Error cargando prórrogas:', e);
            } finally {
                this.prorrogasLoading = false;
            }
        },

    }
}
</script>
@endsection
