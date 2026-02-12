@php
    use Illuminate\Support\Str;
@endphp
@extends('layouts.quantum')

@section('page-title', 'Editar Proyecto')

@section('content')
<div x-data="{
    activeTab: 'info',
    uploading: false,
    uploadProgress: 0,
    previewImages: []
}" class="space-y-6 animate-fadeIn">

    <!-- Breadcrumb -->
    <nav class="flex text-sm text-gray-400 mb-4">
        <a href="{{ route('proyectos.index') }}" class="hover:text-quantum-500 transition-colors">Proyectos</a>
        <span class="mx-2">/</span>
        <a href="{{ route('proyectos.show', $proyecto) }}" class="hover:text-quantum-500 transition-colors">{{ Str::limit($proyecto->nombre_del_proyecto, 40) }}</a>
        <span class="mx-2">/</span>
        <span class="text-gray-300">Editar</span>
    </nav>

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-quantum-500 via-void-500 to-photon-500 bg-clip-text text-transparent flex items-center gap-3">
                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-quantum-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                {{ $proyecto->nombre_del_proyecto }}
            </h1>
            <div class="flex flex-wrap gap-4 mt-2 text-sm text-gray-400">
                <span><i class="fas fa-calendar-plus mr-1"></i>Creado: {{ $proyecto->created_at->format('d/m/Y') }}</span>
                <span><i class="fas fa-clock mr-1"></i>Actualizado: {{ $proyecto->updated_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('proyectos.show', $proyecto) }}" class="btn-quantum flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Ver Proyecto
            </a>
            <a href="{{ route('proyectos.index') }}" class="btn-secondary flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </div>

    <!-- Alertas -->
    @if ($errors->any())
        <div class="card-quantum p-4 bg-red-500/10 border border-red-500/30">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-red-400 mb-2">Por favor, corrija los siguientes errores:</h3>
                    <ul class="list-disc list-inside text-sm text-red-300 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="card-quantum p-4 bg-green-500/10 border border-green-500/30">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-green-300">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Formulario -->
    <form id="editForm" 
          action="{{ route('proyectos.update', ['proyecto' => $proyecto->id]) }}" 
          method="POST" 
          enctype="multipart/form-data"
          onsubmit="return confirmarEnvio(event)"
          @submit="uploading = true"
          class="card-quantum p-6 sm:p-8">
        @csrf
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="is_edit" value="1">
        <input type="hidden" name="proyecto_id" value="{{ $proyecto->id }}">
        <input type="hidden" name="form_action" value="update">
        <input type="hidden" name="original_url" value="{{ url()->current() }}">

        <!-- Tabs QUANTUM -->
        <div class="mb-8">
            <div class="flex border-b border-matter-light">
                <button type="button"
                        @click="activeTab = 'info'"
                        :class="activeTab === 'info' ? 'border-b-2 border-quantum-500 text-quantum-400' : 'text-gray-400 hover:text-gray-300'"
                        class="px-6 py-4 font-medium text-sm transition-colors duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Información General
                </button>
                <button type="button"
                        @click="activeTab = 'docs'"
                        :class="activeTab === 'docs' ? 'border-b-2 border-quantum-500 text-quantum-400' : 'text-gray-400 hover:text-gray-300'"
                        class="px-6 py-4 font-medium text-sm transition-colors duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Documentos
                </button>
                <button type="button"
                        @click="activeTab = 'evidencias'"
                        :class="activeTab === 'evidencias' ? 'border-b-2 border-quantum-500 text-quantum-400' : 'text-gray-400 hover:text-gray-300'"
                        class="px-6 py-4 font-medium text-sm transition-colors duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Evidencias
                </button>
            </div>
        </div>

        <!-- Contenido de las pestañas -->
        <div class="space-y-6">
            <!-- Información General -->
            <div x-show="activeTab === 'info'" x-transition class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nombre del Proyecto -->
                    <div class="md:col-span-2 space-y-2">
                        <label for="nombre_del_proyecto" class="block text-sm font-medium text-gray-300">
                            Nombre del Proyecto <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="nombre_del_proyecto"
                               name="nombre_del_proyecto"
                               value="{{ old('nombre_del_proyecto', $proyecto->nombre_del_proyecto) }}"
                               class="input-quantum w-full @error('nombre_del_proyecto') border-red-500 @enderror"
                               required
                               maxlength="255">
                        @error('nombre_del_proyecto')
                            <p class="text-red-400 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Estado -->
                    <div class="space-y-2">
                        <label for="estado" class="block text-sm font-medium text-gray-300">Estado del Proyecto</label>
                        <select id="estado" name="estado" class="input-quantum w-full @error('estado') border-red-500 @enderror">
                            <option value="activo" {{ old('estado', $proyecto->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="cerrado" {{ old('estado', $proyecto->estado) == 'cerrado' ? 'selected' : '' }}>Cerrado</option>
                            <option value="inactivo" {{ old('estado', $proyecto->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        @error('estado')
                            <p class="text-red-400 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nivel de Criticidad -->
                    <div class="space-y-2">
                        <label for="nivel_criticidad" class="block text-sm font-medium text-gray-300">
                            Nivel de Criticidad
                            <span class="text-xs text-gray-500 ml-1">(evaluación de riesgo)</span>
                        </label>
                        <select id="nivel_criticidad" name="nivel_criticidad" class="input-quantum w-full">
                            <option value="bajo" {{ old('nivel_criticidad', $proyecto->nivel_criticidad) == 'bajo' ? 'selected' : '' }}>Bajo</option>
                            <option value="medio" {{ old('nivel_criticidad', $proyecto->nivel_criticidad ?? 'medio') == 'medio' ? 'selected' : '' }}>Medio</option>
                            <option value="alto" {{ old('nivel_criticidad', $proyecto->nivel_criticidad) == 'alto' ? 'selected' : '' }}>Alto</option>
                            <option value="critico" {{ old('nivel_criticidad', $proyecto->nivel_criticidad) == 'critico' ? 'selected' : '' }}>Crítico</option>
                        </select>
                    </div>

                    <!-- Valor Total -->
                    <div class="space-y-2">
                        <label for="valor_total" class="block text-sm font-medium text-gray-300">Valor Total ($)</label>
                        <div class="relative overflow-hidden">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none z-10">$</span>
                            <input type="number"
                                   id="valor_total"
                                   name="valor_total"
                                   value="{{ old('valor_total', $proyecto->valor_total) }}"
                                   step="0.01"
                                   min="0"
                                   class="input-quantum w-full pl-8 @error('valor_total') border-red-500 @enderror">
                        </div>
                        @error('valor_total')
                            <p class="text-red-400 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Plazo -->
                    <div class="space-y-2">
                        <label for="plazo" class="block text-sm font-medium text-gray-300">Plazo (meses)</label>
                        <div class="relative overflow-hidden">
                            <input type="number"
                                   id="plazo"
                                   name="plazo"
                                   value="{{ old('plazo', $proyecto->plazo) }}"
                                   step="0.01"
                                   min="0"
                                   class="input-quantum w-full pr-16 @error('plazo') border-red-500 @enderror">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none z-10">meses</span>
                        </div>
                        @error('plazo')
                            <p class="text-red-400 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha de Ejecución -->
                    <div class="space-y-2">
                        <label for="fecha_de_ejecucion" class="block text-sm font-medium text-gray-300">Fecha de Ejecución</label>
                        <input type="date"
                               id="fecha_de_ejecucion"
                               name="fecha_de_ejecucion"
                               value="{{ old('fecha_de_ejecucion', $proyecto->fecha_de_ejecucion ? date('Y-m-d', strtotime($proyecto->fecha_de_ejecucion)) : '') }}"
                               class="input-quantum w-full @error('fecha_de_ejecucion') border-red-500 @enderror">
                        @error('fecha_de_ejecucion')
                            <p class="text-red-400 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Entidad Contratante -->
                    <div class="space-y-2">
                        <label for="entidad_contratante" class="block text-sm font-medium text-gray-300">Entidad Contratante</label>
                        <input type="text"
                               id="entidad_contratante"
                               name="entidad_contratante"
                               value="{{ old('entidad_contratante', $proyecto->entidad_contratante) }}"
                               maxlength="255"
                               class="input-quantum w-full @error('entidad_contratante') border-red-500 @enderror">
                        @error('entidad_contratante')
                            <p class="text-red-400 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cobertura -->
                    <div class="space-y-2">
                        <label for="cobertura" class="block text-sm font-medium text-gray-300">Cobertura</label>
                        <input type="text"
                               id="cobertura"
                               name="cobertura"
                               value="{{ old('cobertura', $proyecto->cobertura) }}"
                               maxlength="255"
                               class="input-quantum w-full @error('cobertura') border-red-500 @enderror">
                        @error('cobertura')
                            <p class="text-red-400 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Objeto Contractual -->
                <div class="space-y-2">
                    <label for="objeto_contractual" class="block text-sm font-medium text-gray-300">Objeto Contractual</label>
                    <textarea id="objeto_contractual"
                              name="objeto_contractual"
                              rows="3"
                              class="input-quantum w-full @error('objeto_contractual') border-red-500 @enderror">{{ old('objeto_contractual', $proyecto->objeto_contractual) }}</textarea>
                    @error('objeto_contractual')
                        <p class="text-red-400 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Líneas de Acción -->
                <div class="space-y-2">
                    <label for="lineas_de_accion" class="block text-sm font-medium text-gray-300">Líneas de Acción</label>
                    <textarea id="lineas_de_accion"
                              name="lineas_de_accion"
                              rows="3"
                              class="input-quantum w-full @error('lineas_de_accion') border-red-500 @enderror">{{ old('lineas_de_accion', $proyecto->lineas_de_accion) }}</textarea>
                    @error('lineas_de_accion')
                        <p class="text-red-400 text-sm">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Documentos -->
            <div x-show="activeTab === 'docs'" x-transition class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Archivo del Proyecto -->
                    <div class="card-quantum p-6 space-y-4">
                        <div class="flex items-center gap-3 pb-3 border-b border-matter-light">
                            <div class="w-10 h-10 bg-gradient-to-br from-quantum-500/20 to-void-500/20 rounded-quantum flex items-center justify-center">
                                <svg class="w-5 h-5 text-quantum-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-white">Archivo del Proyecto</h3>
                        </div>
                        <div class="space-y-3">
                            <input type="file"
                                   id="archivo_proyecto"
                                   name="archivo_proyecto"
                                   accept=".pdf,.doc,.docx,.xlsx,.xls"
                                   class="input-quantum w-full @error('archivo_proyecto') border-red-500 @enderror">
                            @error('archivo_proyecto')
                                <p class="text-red-400 text-sm">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-400">Formatos permitidos: PDF, DOC, DOCX, XLSX, XLS. Tamaño máximo: 10MB.</p>
                            @if($proyecto->cargar_archivo_proyecto)
                                <div class="mt-3 p-3 bg-matter-light rounded-lg overflow-hidden">
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-2 min-w-0 flex-1">
                                            <svg class="w-5 h-5 text-quantum-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <a href="{{ route('proyectos.download.archivo', $proyecto) }}" target="_blank" class="text-quantum-400 hover:text-quantum-300 text-sm truncate">
                                                {{ basename($proyecto->cargar_archivo_proyecto) }}
                                            </a>
                                        </div>
                                        <button type="button" class="text-red-400 hover:text-red-300 delete-file-btn flex-shrink-0 p-1" data-file-type="archivo" data-file-url="{{ route('proyectos.delete.archivo', $proyecto->id) }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Contrato o Convenio -->
                    <div class="card-quantum p-6 space-y-4">
                        <div class="flex items-center gap-3 pb-3 border-b border-matter-light">
                            <div class="w-10 h-10 bg-gradient-to-br from-void-500/20 to-photon-500/20 rounded-quantum flex items-center justify-center">
                                <svg class="w-5 h-5 text-void-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-white">Contrato o Convenio</h3>
                        </div>
                        <div class="space-y-3">
                            <input type="file"
                                   id="archivo_contrato"
                                   name="archivo_contrato"
                                   accept=".pdf,.doc,.docx"
                                   class="input-quantum w-full @error('archivo_contrato') border-red-500 @enderror">
                            @error('archivo_contrato')
                                <p class="text-red-400 text-sm">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-400">Formatos permitidos: PDF, DOC, DOCX. Tamaño máximo: 10MB.</p>
                            @if($proyecto->cargar_contrato_o_convenio)
                                <div class="mt-3 p-3 bg-matter-light rounded-lg overflow-hidden">
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-2 min-w-0 flex-1">
                                            <svg class="w-5 h-5 text-void-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <a href="{{ route('proyectos.download.contrato', $proyecto) }}" target="_blank" class="text-void-400 hover:text-void-300 text-sm truncate">
                                                {{ basename($proyecto->cargar_contrato_o_convenio) }}
                                            </a>
                                        </div>
                                        <button type="button" class="text-red-400 hover:text-red-300 delete-file-btn flex-shrink-0 p-1" data-file-type="contrato" data-file-url="{{ route('proyectos.delete.contrato', $proyecto->id) }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Evidencias -->
            <div x-show="activeTab === 'evidencias'" x-transition class="space-y-6">
                <div class="card-quantum p-6 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-matter-light">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-photon-500/20 to-quantum-500/20 rounded-quantum flex items-center justify-center">
                                <svg class="w-5 h-5 text-photon-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-white">Evidencias del Proyecto</h3>
                        </div>
                        <span class="px-3 py-1 bg-quantum-500/20 text-quantum-400 rounded-full text-sm font-medium">
                            {{ $proyecto->cargar_evidencias ? count($proyecto->cargar_evidencias) : 0 }} archivos
                        </span>
                    </div>
                    <div class="space-y-3">
                        <input type="file"
                               id="evidencias"
                               name="evidencias[]"
                               multiple
                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                               class="input-quantum w-full @error('evidencias') border-red-500 @enderror">
                        @error('evidencias')
                            <p class="text-red-400 text-sm">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-400">Formatos permitidos: PDF, DOC, DOCX, JPG, JPEG, PNG. Tamaño máximo por archivo: 10MB.</p>
                    </div>

                    @if($proyecto->cargar_evidencias && count($proyecto->cargar_evidencias) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-6">
                            @foreach($proyecto->cargar_evidencias as $index => $evidencia)
                                <div class="card-quantum p-4 evidencia-item overflow-hidden">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex items-center gap-3 flex-1 min-w-0">
                                            @php
                                                $extension = pathinfo($evidencia, PATHINFO_EXTENSION);
                                                $iconClass = match(strtolower($extension)) {
                                                    'pdf' => 'text-red-400',
                                                    'doc', 'docx' => 'text-blue-400',
                                                    'jpg', 'jpeg', 'png' => 'text-green-400',
                                                    default => 'text-gray-400'
                                                };
                                            @endphp
                                            <svg class="w-8 h-8 {{ $iconClass }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <div class="min-w-0 flex-1 overflow-hidden">
                                                @php
                                                    // Si es URL externa (Cloudinary), usar directamente; si no, usar ruta de descarga
                                                    $evidenciaUrl = (strpos($evidencia, 'http://') === 0 || strpos($evidencia, 'https://') === 0) 
                                                        ? $evidencia 
                                                        : route('proyectos.download.evidencia', ['proyecto' => $proyecto, 'indice' => $index]);
                                                @endphp
                                                <a href="{{ $evidenciaUrl }}" target="_blank" class="text-quantum-400 hover:text-quantum-300 text-sm font-medium block truncate">
                                                    {{ basename($evidencia) }}
                                                </a>
                                                <span class="text-xs text-gray-500 uppercase truncate block">{{ $extension }}</span>
                                            </div>
                                        </div>
                                        <button type="button"
                                                class="text-red-400 hover:text-red-300 flex-shrink-0 p-1 delete-evidencia-btn"
                                                data-proyecto-id="{{ $proyecto->id }}"
                                                data-index="{{ $index }}"
                                                data-url="{{ route('proyectos.delete.evidencia', ['proyecto' => $proyecto->id, 'index' => $index]) }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 p-3 bg-photon-500/10 border border-photon-500/30 rounded-lg">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-photon-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <p class="text-sm text-photon-300">
                                    <strong>Importante:</strong> Al subir nuevas evidencias, se reemplazarán todas las anteriores.
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12 text-gray-400">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                            </svg>
                            <h4 class="text-lg font-medium mb-2">No hay evidencias cargadas</h4>
                            <p class="text-sm">Seleccione los archivos que desea cargar como evidencia del proyecto.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="flex items-center justify-between pt-6 mt-6 border-t border-matter-light">
            <p class="text-sm text-gray-400">
                <i class="fas fa-clock mr-1"></i>
                Última actualización: {{ $proyecto->updated_at->format('d/m/Y H:i:s') }}
            </p>
            <div class="flex gap-3">
                <a href="{{ route('proyectos.index') }}" class="btn-secondary">
                    Cancelar
                </a>
                <button type="submit" class="btn-quantum" id="submitButton">
                    Guardar Cambios
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script src="{{ asset('js/delete-file.js') }}"></script>
<script>
function confirmarEnvio(event) {
    const form = event.target;
    
    if (!form.querySelector('input[name="is_edit"]')) {
        console.error('Error: Falta campo is_edit');
        event.preventDefault();
        return false;
    }

    if (!form.querySelector('input[name="_method"][value="PUT"]')) {
        console.error('Error: Método HTTP incorrecto');
        event.preventDefault();
        return false;
    }

    const proyectoId = form.querySelector('input[name="proyecto_id"]').value;
    const actionUrl = form.getAttribute('action');
    if (!actionUrl.includes(proyectoId)) {
        console.error('Error: ID de proyecto no coincide con la URL');
        event.preventDefault();
        return false;
    }

    return true;
}

document.addEventListener('DOMContentLoaded', function() {
    // Manejar eliminación de evidencias
    document.querySelectorAll('.delete-evidencia-btn').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('¿Está seguro de que desea eliminar esta evidencia?')) {
                const url = this.dataset.url;
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                const formData = new FormData();
                formData.append('_method', 'DELETE');
                formData.append('_token', csrfToken);

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.closest('.evidencia-item').remove();
                        // Actualizar contador
                        const badgeCount = document.querySelector('.bg-quantum-500\\/20 .text-quantum-400');
                        if (badgeCount) {
                            const currentCount = parseInt(badgeCount.textContent);
                            badgeCount.textContent = `${currentCount - 1} archivos`;
                        }
                    } else {
                        alert(data.message || 'Error al eliminar la evidencia');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al eliminar la evidencia. Por favor, intente nuevamente.');
                });
            }
        });
    });
});
</script>
@endpush
@endsection