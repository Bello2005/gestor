@extends('layouts.quantum')

@section('page-title', 'Dashboard')

@section('content')
<!-- Welcome Banner -->
<div class="card-quantum p-8 mb-8 relative overflow-hidden">
    <!-- Background Gradient Overlay -->
    <div class="absolute inset-0 bg-gradient-to-r from-quantum-500/10 via-void-500/10 to-transparent opacity-50"></div>

    <div class="relative z-10">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">
                    Bienvenido, <span class="bg-gradient-to-r from-quantum-500 to-void-500 bg-clip-text text-transparent">{{ Auth::user()->name }}</span>
                </h1>
                <p class="text-gray-400 text-lg">
                    Gestión a la Velocidad del Pensamiento
                </p>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('proyectos.create') }}" class="btn-quantum">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nuevo Proyecto
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Projects Card -->
    <div class="card-quantum p-6 group hover:scale-105 transition-all duration-300 cursor-pointer">
        <div class="flex items-start justify-between mb-4">
            <div class="p-3 rounded-quantum bg-quantum-500/20 border border-quantum-500/30 group-hover:shadow-quantum transition-all duration-300">
                <svg class="w-6 h-6 text-quantum-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <span class="text-xs font-medium text-gray-400 bg-quantum-500/10 px-2 py-1 rounded">Total</span>
        </div>
        <h3 class="text-3xl font-bold text-white mb-1">{{ $totalProjects ?? 0 }}</h3>
        <p class="text-sm text-gray-400">Proyectos Totales</p>
        <div class="mt-4 pt-4 border-t border-matter-light">
            <span class="text-xs text-green-400">
                <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
                +12% este mes
            </span>
        </div>
    </div>

    <!-- Active Projects Card -->
    <div class="card-quantum p-6 group hover:scale-105 transition-all duration-300 cursor-pointer">
        <div class="flex items-start justify-between mb-4">
            <div class="p-3 rounded-quantum bg-green-500/20 border border-green-500/30 group-hover:shadow-glow transition-all duration-300">
                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-xs font-medium text-gray-400 bg-green-500/10 px-2 py-1 rounded">Activos</span>
        </div>
        <h3 class="text-3xl font-bold text-white mb-1">{{ $activeProjects ?? 0 }}</h3>
        <p class="text-sm text-gray-400">Proyectos Activos</p>
        <div class="mt-4 pt-4 border-t border-matter-light">
            <span class="text-xs text-gray-400">
                {{ round(($activeProjects ?? 0) / max($totalProjects ?? 1, 1) * 100) }}% del total
            </span>
        </div>
    </div>

    <!-- Pending Tasks Card -->
    <div class="card-quantum p-6 group hover:scale-105 transition-all duration-300 cursor-pointer">
        <div class="flex items-start justify-between mb-4">
            <div class="p-3 rounded-quantum bg-void-500/20 border border-void-500/30 group-hover:shadow-glow-purple transition-all duration-300">
                <svg class="w-6 h-6 text-void-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-xs font-medium text-gray-400 bg-void-500/10 px-2 py-1 rounded">Pendientes</span>
        </div>
        <h3 class="text-3xl font-bold text-white mb-1">{{ $pendingTasks ?? 0 }}</h3>
        <p class="text-sm text-gray-400">Tareas Pendientes</p>
        <div class="mt-4 pt-4 border-t border-matter-light">
            <span class="text-xs text-void-400">
                Requieren atención
            </span>
        </div>
    </div>

    <!-- Team Members Card -->
    <div class="card-quantum p-6 group hover:scale-105 transition-all duration-300 cursor-pointer">
        <div class="flex items-start justify-between mb-4">
            <div class="p-3 rounded-quantum bg-photon-500/20 border border-photon-500/30 group-hover:shadow-glow transition-all duration-300">
                <svg class="w-6 h-6 text-photon-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <span class="text-xs font-medium text-gray-400 bg-photon-500/10 px-2 py-1 rounded">Equipo</span>
        </div>
        <h3 class="text-3xl font-bold text-white mb-1">{{ $totalUsers ?? 0 }}</h3>
        <p class="text-sm text-gray-400">Miembros del Equipo</p>
        <div class="mt-4 pt-4 border-t border-matter-light">
            <span class="text-xs text-gray-400">
                {{ $activeUsers ?? 0 }} activos esta semana
            </span>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Recent Projects (2/3 width) -->
    <div class="lg:col-span-2 card-quantum p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-white flex items-center gap-3">
                <div class="w-1 h-6 bg-gradient-to-b from-quantum-500 to-void-500 rounded-full"></div>
                Proyectos Recientes
            </h2>
            <a href="{{ route('proyectos.index') }}" class="text-sm text-quantum-400 hover:text-quantum-300 transition-colors">
                Ver todos
                <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div class="space-y-3">
            @forelse($recentProjects ?? [] as $project)
            <a href="{{ route('proyectos.show', $project->id) }}" class="block p-4 rounded-quantum bg-matter-light hover:bg-matter-light/70 border border-transparent hover:border-quantum-500/30 transition-all duration-200 group">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <h3 class="font-medium text-white mb-1 group-hover:text-quantum-400 transition-colors">
                            {{ $project->nombre_del_proyecto }}
                        </h3>
                        <p class="text-sm text-gray-400 line-clamp-1">
                            {{ $project->objeto_contractual ?? 'Sin descripción' }}
                        </p>
                        <div class="flex items-center gap-4 mt-3 text-xs text-gray-500">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $project->created_at->format('d/m/Y') }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                {{ $project->entidad_contratante ?? 'Sin entidad' }}
                            </span>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        @php
                            $statusColors = [
                                'activo' => 'bg-green-500/20 text-green-300 border-green-500/30',
                                'completado' => 'bg-blue-500/20 text-blue-300 border-blue-500/30',
                                'cancelado' => 'bg-red-500/20 text-red-300 border-red-500/30',
                                'pausado' => 'bg-yellow-500/20 text-yellow-300 border-yellow-500/30',
                            ];
                            $statusClass = $statusColors[$project->estado] ?? 'bg-gray-500/20 text-gray-300 border-gray-500/30';
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-medium border {{ $statusClass }}">
                            {{ ucfirst($project->estado) }}
                        </span>
                    </div>
                </div>
            </a>
            @empty
            <div class="text-center py-12">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-matter-light flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <p class="text-gray-400">No hay proyectos recientes</p>
                <a href="{{ route('proyectos.create') }}" class="inline-block mt-4 text-sm text-quantum-400 hover:text-quantum-300">
                    Crear tu primer proyecto →
                </a>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Quick Actions & Activity (1/3 width) -->
    <div class="space-y-6">
        <!-- Quick Actions -->
        <div class="card-quantum p-6">
            <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-3">
                <div class="w-1 h-5 bg-gradient-to-b from-void-500 to-photon-500 rounded-full"></div>
                Acciones Rápidas
            </h2>
            <div class="space-y-2">
                <a href="{{ route('proyectos.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-quantum bg-matter-light hover:bg-quantum-500/20 hover:border hover:border-quantum-500/30 transition-all duration-200 group">
                    <div class="p-2 rounded-lg bg-quantum-500/20 group-hover:bg-quantum-500/30 transition-colors">
                        <svg class="w-4 h-4 text-quantum-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-300 group-hover:text-white transition-colors">Nuevo Proyecto</span>
                </a>

                @if(Auth::user()->hasRole('admin'))
                <a href="{{ route('users.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-quantum bg-matter-light hover:bg-void-500/20 hover:border hover:border-void-500/30 transition-all duration-200 group">
                    <div class="p-2 rounded-lg bg-void-500/20 group-hover:bg-void-500/30 transition-colors">
                        <svg class="w-4 h-4 text-void-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-300 group-hover:text-white transition-colors">Crear Usuario</span>
                </a>

                <a href="{{ route('access-requests.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-quantum bg-matter-light hover:bg-photon-500/20 hover:border hover:border-photon-500/30 transition-all duration-200 group">
                    <div class="p-2 rounded-lg bg-photon-500/20 group-hover:bg-photon-500/30 transition-colors">
                        <svg class="w-4 h-4 text-photon-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-300 group-hover:text-white transition-colors">Ver Solicitudes</span>
                </a>
                @endif
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="card-quantum p-6">
            <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-3">
                <div class="w-1 h-5 bg-gradient-to-b from-photon-500 to-quantum-500 rounded-full"></div>
                Actividad Reciente
            </h2>
            <div class="space-y-4">
                @forelse($recentActivity ?? [] as $activity)
                <div class="flex gap-3">
                    <div class="w-2 h-2 mt-2 rounded-full bg-quantum-500 flex-shrink-0"></div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-300">{{ $activity->description }}</p>
                        <span class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-matter-light flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-400">Sin actividad reciente</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
