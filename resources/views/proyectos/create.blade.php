@extends('layouts.quantum')

@section('title', isset($proyecto) ? 'Editar Proyecto' : 'Nuevo Proyecto')

@section('content')
<div x-data="{
    uploading: false,
    uploadProgress: 0,
    previewImages: []
}" class="space-y-6 animate-fadeIn">

    <!-- Header Zidane -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-quantum-500 via-void-500 to-photon-500 bg-clip-text text-transparent">
                <svg class="inline-block w-8 h-8 sm:w-10 sm:h-10 mr-2 text-quantum-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M{{ isset($proyecto) ? '11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z' : '12 4v16m8-8H4' }}"/>
                </svg>
                {{ isset($proyecto) ? 'Editar Proyecto' : 'Nuevo Proyecto' }}
            </h1>
            <p class="text-gray-400 mt-2">{{ isset($proyecto) ? 'Actualiza la información del proyecto' : 'Crea un proyecto con elegancia' }}</p>
        </div>

        <a href="{{ route('proyectos.index') }}" class="btn-secondary flex items-center gap-2 group">
            <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Volver</span>
        </a>
    </div>

    <!-- Form Card -->
    <form action="{{ isset($proyecto) ? route('proyectos.update', $proyecto) : route('proyectos.store') }}"
          method="POST"
          enctype="multipart/form-data"
          @submit="uploading = true"
          class="card-quantum p-6 sm:p-8 space-y-8">
        @csrf
        @if(isset($proyecto))
            @method('PUT')
        @endif

        <!-- Información Básica -->
        <div class="space-y-6">
            <div class="flex items-center gap-3 pb-4 border-b border-matter-light">
                <div class="w-10 h-10 bg-gradient-to-br from-quantum-500/20 to-void-500/20 rounded-quantum flex items-center justify-center">
                    <svg class="w-5 h-5 text-quantum-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-white">Información Básica</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nombre del Proyecto -->
                <div class="space-y-2">
                    <label for="nombre_del_proyecto" class="block text-sm font-medium text-gray-300">
                        Nombre del Proyecto <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="nombre_del_proyecto"
                           name="nombre_del_proyecto"
                           value="{{ old('nombre_del_proyecto', $proyecto->nombre_del_proyecto ?? '') }}"
                           class="input-quantum @error('nombre_del_proyecto') border-red-500 @enderror"
                           required>
                    @error('nombre_del_proyecto')
                        <p class="text-red-400 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Objeto Contractual -->
                <div class="space-y-2">
                    <label for="objeto_contractual" class="block text-sm font-medium text-gray-300">
                        Objeto Contractual
                    </label>
                    <input type="text"
                           id="objeto_contractual"
                           name="objeto_contractual"
                           value="{{ old('objeto_contractual', $proyecto->objeto_contractual ?? '') }}"
                           class="input-quantum @error('objeto_contractual') border-red-500 @enderror">
                    @error('objeto_contractual')
                        <p class="text-red-400 text-sm">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Líneas de Acción -->
            <div class="space-y-2">
                <label for="lineas_de_accion" class="block text-sm font-medium text-gray-300">
                    Líneas de Acción
                </label>
                <textarea id="lineas_de_accion"
                          name="lineas_de_accion"
                          rows="4"
                          class="input-quantum @error('lineas_de_accion') border-red-500 @enderror"
                          placeholder="Describe las líneas de acción del proyecto...">{{ old('lineas_de_accion', $proyecto->lineas_de_accion ?? '') }}</textarea>
                @error('lineas_de_accion')
                    <p class="text-red-400 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Cobertura -->
                <div class="space-y-2">
                    <label for="cobertura" class="block text-sm font-medium text-gray-300">
                        Cobertura
                    </label>
                    <input type="text"
                           id="cobertura"
                           name="cobertura"
                           value="{{ old('cobertura', $proyecto->cobertura ?? '') }}"
                           class="input-quantum @error('cobertura') border-red-500 @enderror"
                           placeholder="Ej: Nacional">
                    @error('cobertura')
                        <p class="text-red-400 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Entidad Contratante -->
                <div class="space-y-2">
                    <label for="entidad_contratante" class="block text-sm font-medium text-gray-300">
                        Entidad Contratante
                    </label>
                    <input type="text"
                           id="entidad_contratante"
                           name="entidad_contratante"
                           value="{{ old('entidad_contratante', $proyecto->entidad_contratante ?? '') }}"
                           class="input-quantum @error('entidad_contratante') border-red-500 @enderror">
                    @error('entidad_contratante')
                        <p class="text-red-400 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fecha de Ejecución -->
                <div class="space-y-2">
                    <label for="fecha_de_ejecucion" class="block text-sm font-medium text-gray-300">
                        Fecha de Ejecución
                    </label>
                    <input type="date"
                           id="fecha_de_ejecucion"
                           name="fecha_de_ejecucion"
                           value="{{ old('fecha_de_ejecucion', isset($proyecto) && $proyecto->fecha_de_ejecucion ? $proyecto->fecha_de_ejecucion->format('Y-m-d') : '') }}"
                           class="input-quantum @error('fecha_de_ejecucion') border-red-500 @enderror">
                    @error('fecha_de_ejecucion')
                        <p class="text-red-400 text-sm">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Plazo -->
                <div class="space-y-2">
                    <label for="plazo" class="block text-sm font-medium text-gray-300">
                        Plazo (meses)
                    </label>
                    <input type="number"
                           step="0.01"
                           id="plazo"
                           name="plazo"
                           value="{{ old('plazo', $proyecto->plazo ?? '') }}"
                           class="input-quantum @error('plazo') border-red-500 @enderror"
                           placeholder="0.00">
                    @error('plazo')
                        <p class="text-red-400 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Valor Total -->
                <div class="space-y-2">
                    <label for="valor_total" class="block text-sm font-medium text-gray-300">
                        Valor Total (COP)
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">$</span>
                        <input type="number"
                               step="0.01"
                               id="valor_total"
                               name="valor_total"
                               value="{{ old('valor_total', $proyecto->valor_total ?? '') }}"
                               class="input-quantum pl-8 @error('valor_total') border-red-500 @enderror"
                               placeholder="0.00">
                    </div>
                    @error('valor_total')
                        <p class="text-red-400 text-sm">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Gestión de Archivos -->
        <div class="space-y-6">
            <div class="flex items-center gap-3 pb-4 border-b border-matter-light">
                <div class="w-10 h-10 bg-gradient-to-br from-photon-500/20 to-quantum-500/20 rounded-quantum flex items-center justify-center">
                    <svg class="w-5 h-5 text-photon-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-white">Gestión de Archivos</h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Archivo Proyecto -->
                <div class="space-y-2">
                    <label for="archivo_proyecto" class="block text-sm font-medium text-gray-300">
                        Archivo del Proyecto
                    </label>
                    <div class="relative">
                        <input type="file"
                               id="archivo_proyecto"
                               name="archivo_proyecto"
                               accept=".pdf,.doc,.docx,.xlsx,.xls"
                               class="input-quantum file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-quantum-500/20 file:text-quantum-500 hover:file:bg-quantum-500/30 @error('archivo_proyecto') border-red-500 @enderror">
                    </div>
                    <p class="text-xs text-gray-500">PDF, DOC, DOCX, XLS, XLSX (Max: 10MB)</p>
                    @if(isset($proyecto) && $proyecto->cargar_archivo_proyecto)
                        <a href="{{ $proyecto->cargar_archivo_proyecto }}" target="_blank" class="inline-flex items-center gap-2 text-sm text-quantum-500 hover:text-quantum-400 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Ver archivo actual
                        </a>
                    @endif
                    @error('archivo_proyecto')
                        <p class="text-red-400 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contrato o Convenio -->
                <div class="space-y-2">
                    <label for="archivo_contrato" class="block text-sm font-medium text-gray-300">
                        Contrato o Convenio
                    </label>
                    <input type="file"
                           id="archivo_contrato"
                           name="archivo_contrato"
                           accept=".pdf,.doc,.docx"
                           class="input-quantum file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-void-500/20 file:text-void-500 hover:file:bg-void-500/30 @error('archivo_contrato') border-red-500 @enderror">
                    <p class="text-xs text-gray-500">PDF, DOC, DOCX (Max: 10MB)</p>
                    @if(isset($proyecto) && $proyecto->cargar_contrato_o_convenio)
                        <a href="{{ $proyecto->cargar_contrato_o_convenio }}" target="_blank" class="inline-flex items-center gap-2 text-sm text-void-500 hover:text-void-400 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Ver contrato actual
                        </a>
                    @endif
                    @error('archivo_contrato')
                        <p class="text-red-400 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Evidencias -->
                <div class="space-y-2">
                    <label for="evidencias" class="block text-sm font-medium text-gray-300">
                        Evidencias (Imágenes)
                    </label>
                    <input type="file"
                           id="evidencias"
                           name="evidencias[]"
                           multiple
                           accept="image/jpeg,image/jpg,image/png,image/webp"
                           class="input-quantum file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-photon-500/20 file:text-photon-500 hover:file:bg-photon-500/30 @error('evidencias.*') border-red-500 @enderror">
                    <p class="text-xs text-gray-500">JPG, PNG, WEBP (Max: 10MB c/u)</p>
                    @if(isset($proyecto) && $proyecto->cargar_evidencias && count($proyecto->cargar_evidencias) > 0)
                        <div class="grid grid-cols-2 gap-2 max-h-40 overflow-y-auto scrollbar-quantum">
                            @foreach($proyecto->cargar_evidencias as $index => $evidencia)
                                <a href="{{ $evidencia }}" target="_blank" class="text-xs text-center px-3 py-2 bg-matter-light rounded-quantum text-gray-400 hover:text-photon-500 hover:bg-photon-500/10 transition-all truncate">
                                    📷 Evidencia {{ $index + 1 }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                    @error('evidencias.*')
                        <p class="text-red-400 text-sm">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-6 border-t border-matter-light">
            <a href="{{ route('proyectos.index') }}" class="btn-secondary w-full sm:w-auto">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span>Cancelar</span>
            </a>

            <button type="submit"
                    class="btn-quantum w-full sm:w-auto"
                    :disabled="uploading"
                    :class="{ 'opacity-50 cursor-not-allowed': uploading }">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!uploading">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24" x-show="uploading" x-cloak>
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span x-text="uploading ? 'Guardando...' : '{{ isset($proyecto) ? 'Actualizar' : 'Guardar' }} Proyecto'"></span>
            </button>
        </div>
    </form>
</div>

<style>
.scrollbar-quantum::-webkit-scrollbar {
    width: 6px;
}
.scrollbar-quantum::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 3px;
}
.scrollbar-quantum::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, #00BFFF, #9D5CFF);
    border-radius: 3px;
}
</style>
@endsection
