@php
    use Illuminate\Support\Str;
@endphp
@extends('layouts.quantum')

@section('title', 'Detalle del Proyecto')

@section('content')
<div x-data="{
    exportMenuOpen: false,
    showDeleteModal: false,
    imageModal: false,
    currentImage: ''
}" class="space-y-6 animate-fadeIn">

    <!-- Header Breadcrumb & Actions -->
    <div class="flex flex-col gap-4">
        <!-- Breadcrumb -->
        <nav class="flex text-sm text-gray-400">
            <a href="{{ route('proyectos.index') }}" class="hover:text-quantum-500 transition-colors">Proyectos</a>
            <span class="mx-2">/</span>
            <span class="text-gray-300">{{ Str::limit($proyecto->nombre_del_proyecto, 40) }}</span>
        </nav>

        <!-- Header con título y acciones -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex-1">
                <h1 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-quantum-500 via-void-500 to-photon-500 bg-clip-text text-transparent break-words">
                    {{ $proyecto->nombre_del_proyecto }}
                </h1>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-2">
                <!-- Export Dropdown -->
                <div class="relative" @click.away="exportMenuOpen = false">
                    <button @click="exportMenuOpen = !exportMenuOpen"
                            class="btn-quantum flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Exportar</span>
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': exportMenuOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="exportMenuOpen"
                         x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 card-quantum p-2 z-50">
                        <a href="{{ route('proyectos.export.pdf', ['id' => $proyecto->id]) }}"
                           class="flex items-center gap-3 px-4 py-2 rounded-quantum text-gray-300 hover:text-red-400 hover:bg-red-500/10 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <span>Exportar PDF</span>
                        </a>
                        <a href="{{ route('proyectos.export.excel', ['id' => $proyecto->id]) }}"
                           class="flex items-center gap-3 px-4 py-2 rounded-quantum text-gray-300 hover:text-green-400 hover:bg-green-500/10 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Exportar Excel</span>
                        </a>
                        <a href="{{ route('proyectos.export.word', ['id' => $proyecto->id]) }}"
                           class="flex items-center gap-3 px-4 py-2 rounded-quantum text-gray-300 hover:text-blue-400 hover:bg-blue-500/10 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Exportar Word</span>
                        </a>
                    </div>
                </div>

                <!-- Edit Button -->
                <a href="{{ route('proyectos.edit', $proyecto) }}" class="btn-secondary flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span>Editar</span>
                </a>

                <!-- Back Button -->
                <a href="{{ route('proyectos.index') }}" class="btn-secondary flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Volver</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Valor Total -->
        <div class="card-quantum p-6 group hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm uppercase tracking-wider mb-1">Valor Total</p>
                    <p class="text-2xl font-bold text-white">${{ number_format($proyecto->valor_total ?: 0, 0, ',', '.') }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-quantum-500/20 to-void-500/20 rounded-quantum flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-quantum-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Estado -->
        <div class="card-quantum p-6 group hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm uppercase tracking-wider mb-1">Estado</p>
                    <p class="text-2xl font-bold bg-gradient-to-r from-green-400 to-emerald-500 bg-clip-text text-transparent">
                        {{ ucfirst($proyecto->estado ?? 'En Proceso') }}
                    </p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-quantum flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Plazo -->
        <div class="card-quantum p-6 group hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm uppercase tracking-wider mb-1">Plazo</p>
                    <p class="text-2xl font-bold text-white">{{ $proyecto->plazo ?: 'N/A' }} <span class="text-lg">meses</span></p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-yellow-500/20 to-amber-500/20 rounded-quantum flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Evidencias -->
        <div class="card-quantum p-6 group hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm uppercase tracking-wider mb-1">Evidencias</p>
                    <p class="text-2xl font-bold text-white">{{ $proyecto->cargar_evidencias ? count($proyecto->cargar_evidencias) : 0 }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-photon-500/20 to-quantum-500/20 rounded-quantum flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-photon-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Información Detallada -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información Básica -->
        <div class="lg:col-span-2 card-quantum p-6 space-y-6">
            <div class="flex items-center gap-3 pb-4 border-b border-matter-light">
                <div class="w-10 h-10 bg-gradient-to-br from-quantum-500/20 to-void-500/20 rounded-quantum flex items-center justify-center">
                    <svg class="w-5 h-5 text-quantum-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-white">Información Básica</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-400">Nombre del Proyecto</label>
                    <p class="text-white">{{ $proyecto->nombre_del_proyecto }}</p>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-400">Objeto Contractual</label>
                    <p class="text-white">{{ $proyecto->objeto_contractual ?: 'No especificado' }}</p>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-400">Cobertura</label>
                    <p class="text-white">{{ $proyecto->cobertura ?: 'No especificado' }}</p>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-400">Entidad Contratante</label>
                    <p class="text-white">{{ $proyecto->entidad_contratante ?: 'No especificado' }}</p>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-400">Fecha de Ejecución</label>
                    <p class="text-white">
                        @if($proyecto->fecha_de_ejecucion)
                            {{ $proyecto->fecha_de_ejecucion->format('d/m/Y') }}
                        @else
                            No especificado
                        @endif
                    </p>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-400">Estado</label>
                    <p class="text-white">{{ ucfirst($proyecto->estado ?? 'En proceso') }}</p>
                </div>
            </div>

            @if($proyecto->lineas_de_accion)
            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-400">Líneas de Acción</label>
                <div class="p-4 bg-matter-light rounded-quantum">
                    <p class="text-white whitespace-pre-wrap">{{ $proyecto->lineas_de_accion }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Documentos y Archivos -->
        <div class="space-y-6">
            <!-- Archivos del Proyecto -->
            <div class="card-quantum p-6 space-y-4">
                <div class="flex items-center gap-3 pb-4 border-b border-matter-light">
                    <div class="w-10 h-10 bg-gradient-to-br from-photon-500/20 to-quantum-500/20 rounded-quantum flex items-center justify-center">
                        <svg class="w-5 h-5 text-photon-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-white">Documentos</h2>
                </div>

                @if($proyecto->cargar_archivo_proyecto)
                <a href="{{ $proyecto->archivo_proyecto_url }}" target="_blank"
                   class="flex items-center gap-3 p-3 bg-matter-light rounded-quantum hover:bg-quantum-500/10 hover:border-quantum-500/30 border border-transparent transition-all group">
                    <svg class="w-6 h-6 text-quantum-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-white group-hover:text-quantum-500 transition-colors">Archivo del Proyecto</p>
                        <p class="text-xs text-gray-400">Click para descargar</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-quantum-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                </a>
                @endif

                @if($proyecto->cargar_contrato_o_convenio)
                <a href="{{ $proyecto->contrato_convenio_url }}" target="_blank"
                   class="flex items-center gap-3 p-3 bg-matter-light rounded-quantum hover:bg-void-500/10 hover:border-void-500/30 border border-transparent transition-all group">
                    <svg class="w-6 h-6 text-void-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-white group-hover:text-void-500 transition-colors">Contrato/Convenio</p>
                        <p class="text-xs text-gray-400">Click para descargar</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-void-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                </a>
                @endif

                @if(!$proyecto->cargar_archivo_proyecto && !$proyecto->cargar_contrato_o_convenio)
                <p class="text-center text-gray-500 py-4">No hay documentos adjuntos</p>
                @endif
            </div>

            <!-- Metadatos -->
            <div class="card-quantum p-6 space-y-4">
                <div class="flex items-center gap-3 pb-4 border-b border-matter-light">
                    <div class="w-10 h-10 bg-gradient-to-br from-void-500/20 to-photon-500/20 rounded-quantum flex items-center justify-center">
                        <svg class="w-5 h-5 text-void-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-white">Información del Sistema</h2>
                </div>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Creado:</span>
                        <span class="text-white">{{ $proyecto->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Actualizado:</span>
                        <span class="text-white">{{ $proyecto->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @if($proyecto->user)
                    <div class="flex justify-between">
                        <span class="text-gray-400">Creado por:</span>
                        <span class="text-white">{{ $proyecto->user->name }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Galería de Evidencias -->
    @if($proyecto->cargar_evidencias && count($proyecto->cargar_evidencias) > 0)
    <div class="card-quantum p-6 space-y-6">
        <div class="flex items-center gap-3 pb-4 border-b border-matter-light">
            <div class="w-10 h-10 bg-gradient-to-br from-photon-500/20 to-quantum-500/20 rounded-quantum flex items-center justify-center">
                <svg class="w-5 h-5 text-photon-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-white">Galería de Evidencias</h2>
            <span class="px-3 py-1 bg-photon-500/20 text-photon-500 rounded-full text-sm">{{ count($proyecto->cargar_evidencias) }}</span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @foreach($proyecto->evidencias_urls as $index => $evidenciaUrl)
            <div class="group relative aspect-square rounded-quantum overflow-hidden cursor-pointer"
                 @click="imageModal = true; currentImage = '{{ $evidenciaUrl }}'">
                <img src="{{ $evidenciaUrl }}"
                     alt="Evidencia {{ $index + 1 }}"
                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                     onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\'%3E%3Cpath fill=\'%23666\' d=\'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\'/%3E%3C/svg%3E';">
                <div class="absolute inset-0 bg-gradient-to-t from-space/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end justify-center p-2">
                    <span class="text-white text-sm font-medium">Ver imagen</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Modal de Imagen -->
    <div x-show="imageModal"
         x-cloak
         @click="imageModal = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-space/90 backdrop-blur-sm">
        <div @click.stop class="relative max-w-5xl max-h-[90vh]">
            <button @click="imageModal = false"
                    class="absolute -top-4 -right-4 w-10 h-10 bg-red-500 rounded-full flex items-center justify-center text-white hover:bg-red-600 transition-colors z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <img :src="currentImage" alt="Evidencia" class="max-w-full max-h-[90vh] rounded-quantum shadow-2xl">
        </div>
    </div>
    @endif
</div>
@endsection
