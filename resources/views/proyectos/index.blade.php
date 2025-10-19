@extends('layouts.quantum')

@section('page-title', 'Proyectos')

@section('content')
<!-- Header Section - Versace Style -->
<div class="mb-8">
    <div class="flex items-center justify-between flex-wrap gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2 flex items-center gap-3">
                <div class="w-1 h-8 bg-gradient-to-b from-photon-500 to-quantum-500 rounded-full"></div>
                Gestión de Proyectos
            </h1>
            <p class="text-gray-400">Elegancia en cada detalle, excelencia en cada proyecto</p>
        </div>
        <a href="{{ route('proyectos.create') }}" class="btn-quantum">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo Proyecto
        </a>
    </div>

    <!-- Stats Cards - Versace Luxury -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Proyectos -->
        <div class="group relative overflow-hidden rounded-quantum-lg border border-matter-light bg-gradient-to-br from-matter via-matter-light to-matter p-6 transition-all duration-300 hover:scale-105 hover:shadow-quantum-lg">
            <div class="absolute top-0 right-0 w-32 h-32 bg-quantum-500/10 rounded-full blur-3xl"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-quantum bg-quantum-500/20 border border-quantum-500/30 flex items-center justify-center">
                        <svg class="w-6 h-6 text-quantum-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-quantum-400 bg-quantum-500/10 px-3 py-1 rounded-full">Total</span>
                </div>
                <h3 class="text-4xl font-bold text-white mb-2">{{ $proyectos->count() }}</h3>
                <p class="text-sm text-gray-400">Proyectos Totales</p>
            </div>
        </div>

        <!-- Proyectos Activos -->
        <div class="group relative overflow-hidden rounded-quantum-lg border border-matter-light bg-gradient-to-br from-matter via-matter-light to-matter p-6 transition-all duration-300 hover:scale-105 hover:shadow-quantum-lg">
            <div class="absolute top-0 right-0 w-32 h-32 bg-green-500/10 rounded-full blur-3xl"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-quantum bg-green-500/20 border border-green-500/30 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-green-400 bg-green-500/10 px-3 py-1 rounded-full">Activos</span>
                </div>
                <h3 class="text-4xl font-bold text-white mb-2">{{ $proyectos->where('estado', 'activo')->count() }}</h3>
                <p class="text-sm text-gray-400">En Ejecución</p>
            </div>
        </div>

        <!-- Valor Total -->
        <div class="group relative overflow-hidden rounded-quantum-lg border border-matter-light bg-gradient-to-br from-matter via-matter-light to-matter p-6 transition-all duration-300 hover:scale-105 hover:shadow-quantum-lg">
            <div class="absolute top-0 right-0 w-32 h-32 bg-photon-500/10 rounded-full blur-3xl"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-quantum bg-photon-500/20 border border-photon-500/30 flex items-center justify-center">
                        <svg class="w-6 h-6 text-photon-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-photon-400 bg-photon-500/10 px-3 py-1 rounded-full">Portfolio</span>
                </div>
                <h3 class="text-3xl font-bold text-white mb-2">${{ number_format($proyectos->sum('valor_total'), 0, ',', '.') }}</h3>
                <p class="text-sm text-gray-400">Valor Total</p>
            </div>
        </div>

        <!-- Entidades -->
        <div class="group relative overflow-hidden rounded-quantum-lg border border-matter-light bg-gradient-to-br from-matter via-matter-light to-matter p-6 transition-all duration-300 hover:scale-105 hover:shadow-quantum-lg">
            <div class="absolute top-0 right-0 w-32 h-32 bg-void-500/10 rounded-full blur-3xl"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-quantum bg-void-500/20 border border-void-500/30 flex items-center justify-center">
                        <svg class="w-6 h-6 text-void-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-void-400 bg-void-500/10 px-3 py-1 rounded-full">Partners</span>
                </div>
                <h3 class="text-4xl font-bold text-white mb-2">{{ $proyectos->unique('entidad_contratante')->count() }}</h3>
                <p class="text-sm text-gray-400">Entidades</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters & Search - Versace Elegance -->
<div class="card-quantum p-6 mb-8">
    <div class="flex items-center justify-between flex-wrap gap-4 mb-6">
        <div class="flex items-center gap-3">
            <div class="w-1 h-6 bg-gradient-to-b from-quantum-500 to-void-500 rounded-full"></div>
            <h2 class="text-xl font-semibold text-white">Filtros</h2>
        </div>
    </div>

    <!-- Status Filters -->
    <div class="flex flex-wrap gap-3 mb-6" x-data="{ estadoActual: 'todos' }">
        <button @click="estadoActual = 'todos'"
                :class="estadoActual === 'todos' ? 'bg-gradient-to-r from-quantum-500 to-void-500 text-white shadow-quantum' : 'bg-matter-light text-gray-400 hover:text-white hover:bg-matter'"
                class="px-5 py-2.5 rounded-quantum font-medium transition-all duration-200 border border-matter-light hover:border-quantum-500/30"
                data-estado="todos">
            <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
            </svg>
            Todos
            <span class="ml-2 px-2 py-0.5 rounded-full text-xs bg-white/20">({{ $proyectos->count() }})</span>
        </button>

        <button @click="estadoActual = 'activo'"
                :class="estadoActual === 'activo' ? 'bg-green-500 text-white shadow-glow' : 'bg-green-500/10 text-green-300 hover:bg-green-500/20'"
                class="px-5 py-2.5 rounded-quantum font-medium transition-all duration-200 border border-green-500/30"
                data-estado="activo">
            <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Activos
            <span class="ml-2 px-2 py-0.5 rounded-full text-xs bg-white/20">({{ $proyectos->where('estado', 'activo')->count() }})</span>
        </button>

        <button @click="estadoActual = 'inactivo'"
                :class="estadoActual === 'inactivo' ? 'bg-yellow-500 text-white shadow-glow' : 'bg-yellow-500/10 text-yellow-300 hover:bg-yellow-500/20'"
                class="px-5 py-2.5 rounded-quantum font-medium transition-all duration-200 border border-yellow-500/30"
                data-estado="inactivo">
            <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Inactivos
            <span class="ml-2 px-2 py-0.5 rounded-full text-xs bg-white/20">({{ $proyectos->where('estado', 'inactivo')->count() }})</span>
        </button>

        <button @click="estadoActual = 'cerrado'"
                :class="estadoActual === 'cerrado' ? 'bg-red-500 text-white shadow-glow' : 'bg-red-500/10 text-red-300 hover:bg-red-500/20'"
                class="px-5 py-2.5 rounded-quantum font-medium transition-all duration-200 border border-red-500/30"
                data-estado="cerrado">
            <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Cerrados
            <span class="ml-2 px-2 py-0.5 rounded-full text-xs bg-white/20">({{ $proyectos->where('estado', 'cerrado')->count() }})</span>
        </button>
    </div>

    <!-- Search Bar -->
    <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        <input type="text"
               id="searchProjects"
               placeholder="Buscar por nombre, entidad, objeto contractual..."
               class="w-full pl-12 pr-4 py-3 bg-matter border border-matter-light rounded-quantum text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-quantum-500 focus:border-transparent transition-all duration-200">
    </div>
</div>

<!-- Projects Grid - Versace Patterns -->
<div id="proyectosGrid" class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
    @forelse($proyectos as $proyecto)
    <div class="project-card group relative overflow-hidden rounded-quantum-xl border border-matter-light bg-gradient-to-br from-matter via-matter-light to-matter transition-all duration-300 hover:scale-105 hover:shadow-quantum-lg hover:border-quantum-500/30"
         data-estado="{{ $proyecto->estado }}">

        <!-- Decorative Pattern - Versace Style -->
        <div class="absolute top-0 right-0 w-40 h-40 opacity-10">
            <svg viewBox="0 0 100 100" class="w-full h-full">
                <pattern id="versace-{{ $proyecto->id }}" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                    <circle cx="10" cy="10" r="1.5" fill="url(#gradient-{{ $proyecto->id }})"/>
                    <line x1="0" y1="10" x2="20" y2="10" stroke="url(#gradient-{{ $proyecto->id }})" stroke-width="0.5"/>
                    <line x1="10" y1="0" x2="10" y2="20" stroke="url(#gradient-{{ $proyecto->id }})" stroke-width="0.5"/>
                </pattern>
                <defs>
                    <linearGradient id="gradient-{{ $proyecto->id }}" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:hsl(195, 100%, 50%)"/>
                        <stop offset="100%" style="stop-color:hsl(270, 80%, 60%)"/>
                    </linearGradient>
                </defs>
                <rect width="100" height="100" fill="url(#versace-{{ $proyecto->id }})"/>
            </svg>
        </div>

        <!-- Card Content -->
        <div class="relative p-6">
            <!-- Header -->
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-white mb-2 group-hover:text-quantum-400 transition-colors line-clamp-2">
                        {{ $proyecto->nombre_del_proyecto }}
                    </h3>
                    @php
                        $statusColors = [
                            'activo' => 'bg-green-500/20 text-green-300 border-green-500/50',
                            'inactivo' => 'bg-yellow-500/20 text-yellow-300 border-yellow-500/50',
                            'cerrado' => 'bg-red-500/20 text-red-300 border-red-500/50',
                        ];
                        $statusIcons = [
                            'activo' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>',
                            'inactivo' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                            'cerrado' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>',
                        ];
                        $statusClass = $statusColors[$proyecto->estado] ?? 'bg-gray-500/20 text-gray-300 border-gray-500/50';
                        $statusIcon = $statusIcons[$proyecto->estado] ?? '';
                    @endphp
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium border {{ $statusClass }}">
                        {!! $statusIcon !!}
                        {{ ucfirst($proyecto->estado) }}
                    </span>
                </div>
            </div>

            <!-- Description -->
            <p class="text-sm text-gray-400 mb-4 line-clamp-3">
                {{ $proyecto->objeto_contractual ?? 'Sin descripción disponible' }}
            </p>

            <!-- Info Grid -->
            <div class="space-y-3 mb-6">
                <!-- Entidad -->
                <div class="flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4 text-void-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="text-gray-300 truncate">{{ $proyecto->entidad_contratante ?? 'Sin entidad' }}</span>
                </div>

                <!-- Fecha -->
                <div class="flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4 text-quantum-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-gray-300">{{ $proyecto->fecha_de_ejecucion ? $proyecto->fecha_de_ejecucion->format('d M, Y') : 'Sin fecha' }}</span>
                </div>

                <!-- Valor -->
                <div class="flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4 text-photon-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-photon-300 font-semibold">${{ number_format($proyecto->valor_total ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Actions -->
            @if(auth()->check() && auth()->user()->roles->pluck('id')->intersect([1,2])->isNotEmpty())
            <div class="flex items-center gap-2 pt-4 border-t border-matter-light">
                <a href="{{ route('proyectos.show', $proyecto->id) }}"
                   class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-quantum-500/20 hover:bg-quantum-500/30 border border-quantum-500/30 hover:border-quantum-500 text-quantum-300 hover:text-quantum-200 rounded-quantum text-sm font-medium transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Ver
                </a>
                <a href="{{ route('proyectos.edit', $proyecto->id) }}"
                   class="flex items-center justify-center p-2.5 bg-void-500/20 hover:bg-void-500/30 border border-void-500/30 hover:border-void-500 text-void-300 hover:text-void-200 rounded-quantum transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </a>
                <button type="button"
                        class="btn-delete-project flex items-center justify-center p-2.5 bg-red-500/20 hover:bg-red-500/30 border border-red-500/30 hover:border-red-500 text-red-300 hover:text-red-200 rounded-quantum transition-all duration-200"
                        data-proyecto-id="{{ $proyecto->id }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>
            @else
            <div class="pt-4 border-t border-matter-light">
                <a href="{{ route('proyectos.show', $proyecto->id) }}"
                   class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-quantum-500/20 hover:bg-quantum-500/30 border border-quantum-500/30 hover:border-quantum-500 text-quantum-300 hover:text-quantum-200 rounded-quantum text-sm font-medium transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Ver Detalles
                </a>
            </div>
            @endif
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-16">
        <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-matter-light flex items-center justify-center">
            <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
            </svg>
        </div>
        <p class="text-gray-400 text-lg mb-4">No hay proyectos disponibles</p>
        <a href="{{ route('proyectos.create') }}" class="inline-block text-quantum-400 hover:text-quantum-300 transition-colors">
            Crear tu primer proyecto →
        </a>
    </div>
    @endforelse
</div>

<!-- No Results Message -->
<div id="noResults" class="hidden text-center py-16">
    <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-matter-light flex items-center justify-center">
        <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
    </div>
    <p class="text-gray-400 text-lg">No se encontraron proyectos</p>
    <p class="text-gray-500 text-sm mt-2">Intenta ajustar los filtros o la búsqueda</p>
</div>

<!-- Delete Confirmation Modal -->
<div x-data="{ open: false, proyectoId: null }"
     @delete-project.window="open = true; proyectoId = $event.detail.id"
     x-show="open"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">

    <div class="flex items-center justify-center min-h-screen px-4">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="open = false"></div>

        <!-- Modal -->
        <div class="relative bg-matter border border-matter-light rounded-quantum-xl shadow-quantum-lg max-w-md w-full p-6"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">

            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-red-500/20 border border-red-500/30 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-white">Confirmar Eliminación</h3>
                    <p class="text-sm text-gray-400">Esta acción no se puede deshacer</p>
                </div>
            </div>

            <p class="text-gray-300 mb-6">
                ¿Estás seguro de que deseas eliminar este proyecto? Todos los datos asociados se perderán permanentemente.
            </p>

            <div class="flex gap-3">
                <button @click="open = false"
                        class="flex-1 px-4 py-2.5 bg-matter-light hover:bg-matter text-gray-300 hover:text-white rounded-quantum font-medium transition-all duration-200">
                    Cancelar
                </button>
                <form :action="'/proyectos/' + proyectoId" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-quantum font-medium transition-all duration-200 shadow-glow">
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchProjects');
    const proyectosGrid = document.getElementById('proyectosGrid');
    const noResults = document.getElementById('noResults');
    const statusButtons = document.querySelectorAll('[data-estado]');
    const projectCards = document.querySelectorAll('.project-card');

    let estadoActual = 'todos';
    let busquedaActual = '';

    // Filter function
    function aplicarFiltros() {
        let visibleCount = 0;

        projectCards.forEach(card => {
            const estado = card.getAttribute('data-estado');
            const texto = card.textContent.toLowerCase();

            const coincideEstado = (estadoActual === 'todos') || (estado === estadoActual);
            const coincideBusqueda = !busquedaActual || texto.includes(busquedaActual.toLowerCase());

            if (coincideEstado && coincideBusqueda) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Show/hide no results message
        if (visibleCount === 0) {
            proyectosGrid.classList.add('hidden');
            noResults.classList.remove('hidden');
        } else {
            proyectosGrid.classList.remove('hidden');
            noResults.classList.add('hidden');
        }
    }

    // Status filter click
    statusButtons.forEach(button => {
        button.addEventListener('click', function() {
            estadoActual = this.getAttribute('data-estado');
            aplicarFiltros();
        });
    });

    // Search input
    searchInput.addEventListener('input', function() {
        busquedaActual = this.value.trim();
        aplicarFiltros();
    });

    // Delete project buttons
    const deleteButtons = document.querySelectorAll('.btn-delete-project');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const proyectoId = this.getAttribute('data-proyecto-id');
            window.dispatchEvent(new CustomEvent('delete-project', {
                detail: { id: proyectoId }
            }));
        });
    });

    // Initial filter
    aplicarFiltros();
});
</script>
@endsection
