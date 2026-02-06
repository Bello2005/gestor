@extends('layouts.quantum')

@section('title', 'Registro de Auditoría')

@section('content')
<div x-data="{
    init() {
        this.filterOpen = window.innerWidth >= 1280;
    },
    filterOpen: true,
    showDetails: null,
    selectedOperation: '{{ request('operation', 'all') }}',
    selectedTable: '{{ request('table', 'all') }}'
}" class="space-y-6 animate-fadeIn">

    <!-- Header Eiffel -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-quantum-500 via-void-500 to-photon-500 bg-clip-text text-transparent">
                Registro de Auditoría
            </h1>
            <p class="text-gray-400 mt-2">Estructura impecable del historial de cambios</p>
        </div>

        <!-- Export Button -->
        <a href="{{ route('audit.export', request()->all()) }}"
           class="btn-quantum flex items-center gap-2 group">
            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span>Exportar CSV</span>
        </a>
    </div>

    <!-- Stats Bar - Torre Eiffel Niveles -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Audits -->
        <div class="card-quantum p-6 group hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm uppercase tracking-wider mb-1">Total Registros</p>
                    <p class="text-3xl font-bold text-white">{{ $audits->total() }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-quantum-500/20 to-void-500/20 rounded-quantum flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-quantum-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Inserts -->
        <div class="card-quantum p-6 group hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm uppercase tracking-wider mb-1">Inserciones</p>
                    <p class="text-3xl font-bold bg-gradient-to-r from-green-400 to-emerald-500 bg-clip-text text-transparent">
                        {{ $audits->where('operation', 'INSERT')->count() }}
                    </p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-quantum flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Updates -->
        <div class="card-quantum p-6 group hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm uppercase tracking-wider mb-1">Actualizaciones</p>
                    <p class="text-3xl font-bold bg-gradient-to-r from-blue-400 to-cyan-500 bg-clip-text text-transparent">
                        {{ $audits->where('operation', 'UPDATE')->count() }}
                    </p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500/20 to-cyan-500/20 rounded-quantum flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Deletes -->
        <div class="card-quantum p-6 group hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm uppercase tracking-wider mb-1">Eliminaciones</p>
                    <p class="text-3xl font-bold bg-gradient-to-r from-red-400 to-rose-500 bg-clip-text text-transparent">
                        {{ $audits->where('operation', 'DELETE')->count() }}
                    </p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-red-500/20 to-rose-500/20 rounded-quantum flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content: Filters + Timeline -->
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">

        <!-- Filters Sidebar - Torre Eiffel Base -->
        <div class="xl:col-span-1">
            <div class="card-quantum p-6 xl:sticky xl:top-24">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-white">Filtros</h3>
                    <button @click="filterOpen = !filterOpen" class="xl:hidden text-gray-400 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div x-show="filterOpen" x-cloak x-transition class="xl:!block">
                    <form action="{{ route('audit.index') }}" method="GET" class="space-y-6">

                    <!-- Table Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                Tabla
                            </span>
                        </label>
                        <select name="table" x-model="selectedTable"
                                class="w-full px-4 py-2.5 bg-matter-light border border-matter-light rounded-quantum text-white focus:border-quantum-500 focus:ring-2 focus:ring-quantum-500/20 transition-all">
                            <option value="">Todas las tablas</option>
                            @foreach($tables as $table)
                                <option value="{{ $table }}" {{ request('table') == $table ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $table)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Operation Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Operación
                            </span>
                        </label>
                        <select name="operation" x-model="selectedOperation"
                                class="w-full px-4 py-2.5 bg-matter-light border border-matter-light rounded-quantum text-white focus:border-quantum-500 focus:ring-2 focus:ring-quantum-500/20 transition-all">
                            <option value="">Todas las operaciones</option>
                            @foreach($operations as $operation)
                                <option value="{{ $operation }}" {{ request('operation') == $operation ? 'selected' : '' }}>
                                    {{ ucfirst($operation) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date Range -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Fecha Desde
                            </span>
                        </label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                               class="w-full px-4 py-2.5 bg-matter-light border border-matter-light rounded-quantum text-white focus:border-quantum-500 focus:ring-2 focus:ring-quantum-500/20 transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Fecha Hasta
                            </span>
                        </label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                               class="w-full px-4 py-2.5 bg-matter-light border border-matter-light rounded-quantum text-white focus:border-quantum-500 focus:ring-2 focus:ring-quantum-500/20 transition-all">
                    </div>

                    <!-- Filter Actions -->
                    <div class="flex gap-3 pt-4 border-t border-matter-light">
                        <button type="submit" class="flex-1 btn-quantum flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Aplicar
                        </button>
                        <a href="{{ route('audit.index') }}" class="px-4 py-2.5 bg-matter-light hover:bg-matter-light/80 text-gray-300 rounded-quantum transition-all">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>
        </div>
        </div>

        <!-- Timeline - Torre Eiffel Structure -->
        <div class="xl:col-span-3">
            @if($audits->isEmpty())
                <!-- Empty State -->
                <div class="card-quantum p-12 text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-quantum-500/20 to-void-500/20 rounded-full mx-auto mb-6 flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">No hay registros de auditoría</h3>
                    <p class="text-gray-400">Intenta ajustar los filtros para ver más resultados</p>
                </div>
            @else
                <!-- Timeline Container -->
                <div class="relative space-y-6">
                    <!-- Vertical Line (Torre Eiffel) -->
                    <div class="hidden xl:block absolute left-6 top-0 bottom-0 w-0.5 bg-gradient-to-b from-quantum-500 via-void-500 to-photon-500"></div>

                    @foreach($audits as $index => $audit)
                        <div class="relative pl-0 xl:pl-16 group animate-slideUp" style="animation-delay: {{ $index * 50 }}ms;">
                            <!-- Timeline Dot -->
                            <div class="hidden xl:flex absolute left-3.5 top-6 w-5 h-5 rounded-full border-4 border-space
                                        {{ $audit->operation === 'INSERT' ? 'bg-green-500' : ($audit->operation === 'UPDATE' ? 'bg-blue-500' : 'bg-red-500') }}
                                        group-hover:scale-150 transition-transform duration-300 shadow-lg z-10">
                            </div>

                            <!-- Audit Card -->
                            <div class="card-quantum overflow-hidden group-hover:border-quantum-500/50 transition-all duration-300">
                                <!-- Card Header -->
                                <div class="p-6 border-b border-matter-light">
                                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                        <div class="flex items-center gap-4">
                                            <!-- Operation Badge -->
                                            @if($audit->operation === 'INSERT')
                                                <span class="px-4 py-2 bg-gradient-to-r from-green-500/20 to-emerald-500/20 border border-green-500/30 text-green-400 rounded-quantum text-sm font-semibold uppercase tracking-wider">
                                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                    </svg>
                                                    {{ $audit->operation }}
                                                </span>
                                            @elseif($audit->operation === 'UPDATE')
                                                <span class="px-4 py-2 bg-gradient-to-r from-blue-500/20 to-cyan-500/20 border border-blue-500/30 text-blue-400 rounded-quantum text-sm font-semibold uppercase tracking-wider">
                                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                    </svg>
                                                    {{ $audit->operation }}
                                                </span>
                                            @else
                                                <span class="px-4 py-2 bg-gradient-to-r from-red-500/20 to-rose-500/20 border border-red-500/30 text-red-400 rounded-quantum text-sm font-semibold uppercase tracking-wider">
                                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    {{ $audit->operation }}
                                                </span>
                                            @endif

                                            <div>
                                                <h3 class="text-lg font-bold text-white">
                                                    {{ ucfirst(str_replace('_', ' ', $audit->table_name)) }}
                                                </h3>
                                                <p class="text-sm text-gray-400">ID del registro: #{{ $audit->record_id }}</p>
                                            </div>
                                        </div>

                                        <!-- Timestamp -->
                                        <div class="text-right">
                                            <p class="text-sm text-gray-400">
                                                {{ $audit->created_at->format('d/m/Y') }}
                                            </p>
                                            <p class="text-lg font-semibold text-white">
                                                {{ $audit->created_at->format('H:i:s') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card Body -->
                                <div class="p-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 md:gap-6">
                                        <!-- User Info -->
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 bg-gradient-to-br from-quantum-500/20 to-void-500/20 rounded-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-quantum-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-400 uppercase tracking-wider">Usuario</p>
                                                <p class="text-sm font-semibold text-white">{{ $audit->user_name ?? 'Sistema' }}</p>
                                            </div>
                                        </div>

                                        <!-- IP Address -->
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 bg-gradient-to-br from-void-500/20 to-photon-500/20 rounded-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-void-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-400 uppercase tracking-wider">Dirección IP</p>
                                                <p class="text-sm font-semibold text-white font-mono">{{ $audit->ip_address ?? 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <!-- View Details -->
                                        <div class="flex items-center justify-end">
                                            <a href="{{ route('audit.show', $audit) }}"
                                               class="px-6 py-2.5 bg-gradient-to-r from-quantum-500/20 to-void-500/20 hover:from-quantum-500/30 hover:to-void-500/30 border border-quantum-500/30 text-quantum-400 rounded-quantum transition-all duration-300 hover:scale-105 flex items-center gap-2 group">
                                                <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Ver Detalles
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $audits->appends(request()->all())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
