@extends('layouts.quantum')

@section('title', 'Nueva Solicitud de Acceso')

@section('content')
<div
    x-data="{
        form: {
            permission_id: '{{ old('permission_id', '') }}',
            proyecto_id: '{{ old('proyecto_id', '') }}',
            requested_access_level: '{{ old('requested_access_level', 'lectura') }}',
            duration_type: '{{ old('duration_type', 'temporal') }}',
            starts_at: '{{ old('starts_at', '') }}',
            expires_at: '{{ old('expires_at', '') }}',
            justification: '{{ old('justification', '') }}'
        },
        riskPreview: null,
        loadingRisk: false,
        submitting: false,
        selectedPermission: null,
        isAdmin: {{ isset($isAdmin) && $isAdmin ? 'true' : 'false' }},
        minJustification: {{ isset($isAdmin) && $isAdmin ? '5' : '20' }},
        permissions: @js($permissions->flatten()),

        init() {
            this.$watch('form.permission_id', (val) => {
                this.selectedPermission = this.permissions.find(p => p.id == val) || null;
                if (!this.showProyectoField) {
                    this.form.proyecto_id = '';
                }
                this.updateRiskPreview();
            });
            this.$watch('form.proyecto_id', () => this.updateRiskPreview());
            this.$watch('form.duration_type', () => this.updateRiskPreview());
            this.$watch('form.starts_at', () => this.updateRiskPreview());
            this.$watch('form.expires_at', () => this.updateRiskPreview());

            if (this.form.permission_id) {
                this.selectedPermission = this.permissions.find(p => p.id == this.form.permission_id) || null;
                this.updateRiskPreview();
            }
        },

        get showProyectoField() {
            if (!this.form.permission_id) return false;
            let perm = this.permissions.find(p => p.id == this.form.permission_id);
            return perm && perm.category === 'proyectos';
        },

        get justificationLength() {
            return this.form.justification.length;
        },

        get justificationRemaining() {
            return Math.max(0, this.minJustification - this.form.justification.length);
        },

        async updateRiskPreview() {
            if (!this.form.permission_id) {
                this.riskPreview = null;
                return;
            }
            this.loadingRisk = true;
            try {
                let res = await fetch('{{ route('solicitudes-acceso.preview-risk') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.form)
                });
                this.riskPreview = await res.json();
            } catch (e) {
                console.error('Error fetching risk preview:', e);
            }
            this.loadingRisk = false;
        },

        getRiskColor(level) {
            return { bajo: '#10b981', medio: '#f59e0b', alto: '#f97316', critico: '#ef4444' }[level] || '#6b7280';
        },

        getRiskBgClass(level) {
            return {
                bajo: 'bg-green-500/20 text-green-400 border-green-500/30',
                medio: 'bg-amber-500/20 text-amber-400 border-amber-500/30',
                alto: 'bg-orange-500/20 text-orange-400 border-orange-500/30',
                critico: 'bg-red-500/20 text-red-400 border-red-500/30'
            }[level] || 'bg-gray-500/20 text-gray-400 border-gray-500/30';
        },

        getRiskLabel(level) {
            return { bajo: 'Bajo', medio: 'Medio', alto: 'Alto', critico: 'Critico' }[level] || 'N/A';
        },

        getFlowLabel(level) {
            return {
                bajo: 'Auto-aprobacion inmediata',
                medio: 'Requiere 1 aprobacion de administrador',
                alto: 'Requiere doble aprobacion',
                critico: 'Requiere doble aprobacion + restricciones de duracion'
            }[level] || '';
        },

        getFlowIcon(level) {
            return {
                bajo: 'check-circle',
                medio: 'user-check',
                alto: 'shield',
                critico: 'alert-triangle'
            }[level] || 'info';
        },

        getRiskWeightColor(weight) {
            return { 1: '#10b981', 2: '#3b82f6', 3: '#f59e0b', 4: '#f97316', 5: '#ef4444' }[weight] || '#6b7280';
        },

        getStrokeDasharray(score) {
            const circumference = 2 * Math.PI * 54;
            const offset = circumference - (score / 100) * circumference;
            return circumference + ' ' + circumference;
        },

        getStrokeDashoffset(score) {
            const circumference = 2 * Math.PI * 54;
            return circumference - (score / 100) * circumference;
        }
    }"
    class="animate-fadeIn"
>

    {{-- ============================================================ --}}
    {{-- HEADER --}}
    {{-- ============================================================ --}}
    <div class="mb-8 animate-slideUp">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-quantum-500 via-void-500 to-photon-500 bg-clip-text text-transparent">
                    Nueva Solicitud de Acceso
                </h1>
                <p class="text-gray-400 mt-2">Solicita permisos de acceso a recursos del sistema. Tu solicitud sera evaluada segun el nivel de riesgo.</p>
            </div>
            <a href="{{ route('solicitudes-acceso.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-quantum border border-matter-light text-gray-300 hover:bg-matter-light hover:text-white transition-all duration-200 text-sm self-start">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- TWO COLUMN LAYOUT --}}
    {{-- ============================================================ --}}
    <div class="grid md:grid-cols-3 gap-6">

        {{-- ======================================================== --}}
        {{-- LEFT COLUMN - THE FORM --}}
        {{-- ======================================================== --}}
        <div class="md:col-span-2 space-y-6">
            <form
                method="POST"
                action="{{ route('solicitudes-acceso.store') }}"
                @submit.prevent="submitting = true; $el.submit();"
                class="space-y-6"
            >
                @csrf

                {{-- ------------------------------------------------ --}}
                {{-- ADMIN DIRECT GRANT PANEL --}}
                {{-- ------------------------------------------------ --}}
                @if(isset($isAdmin) && $isAdmin)
                <div class="card-quantum p-6 border-quantum-500/30 bg-quantum-500/5 animate-slideUp">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-quantum bg-quantum-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-quantum-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-quantum-400 uppercase tracking-wider">Otorgamiento Directo</h3>
                            <p class="text-xs text-gray-400">Como administrador, puedes otorgar permisos directamente sin evaluación de riesgo.</p>
                        </div>
                    </div>

                    <label for="target_user_id" class="block text-sm font-medium text-gray-300 mb-2">
                        Otorgar a usuario <span class="text-gray-500 text-xs">(deja vacío para ti mismo)</span>
                    </label>
                    <select name="target_user_id" id="target_user_id"
                            class="w-full bg-matter-light border border-matter-light rounded-quantum text-white px-4 py-3 focus:border-quantum-500 focus:ring-2 focus:ring-quantum-500/20 transition-all">
                        <option value="">-- Para mí mismo --</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->full_name }} ({{ $u->email }})</option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- ------------------------------------------------ --}}
                {{-- PERMISO SELECT --}}
                {{-- ------------------------------------------------ --}}
                <div class="card-quantum p-6 animate-slideUp" style="animation-delay: 0.05s;">
                    <label for="permission_id" class="block text-sm font-medium text-gray-300 mb-2">
                        Permiso <span class="text-red-400">*</span>
                    </label>
                    <p class="text-xs text-gray-500 mb-3">Selecciona el permiso que necesitas. Los puntos de color indican el nivel de riesgo del permiso.</p>

                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                        </div>
                        <select
                            name="permission_id"
                            id="permission_id"
                            x-model="form.permission_id"
                            required
                            class="pl-12 w-full bg-matter-light border border-matter-light rounded-quantum text-white focus:border-quantum-500 focus:ring-2 focus:ring-quantum-500/20 transition-all px-4 py-3 appearance-none cursor-pointer"
                        >
                            <option value="">-- Selecciona un permiso --</option>
                            @foreach($permissions as $category => $perms)
                                <optgroup label="{{ ucfirst($category) }}">
                                    @foreach($perms as $perm)
                                        <option value="{{ $perm->id }}" {{ old('permission_id') == $perm->id ? 'selected' : '' }}>
                                            {{ $perm->name }} (Riesgo: {{ $perm->risk_weight }}/5)
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Selected permission info --}}
                    <div x-show="selectedPermission" x-transition class="mt-3 flex items-center gap-3 p-3 rounded-quantum bg-space-500/50 border border-matter-light">
                        <div class="flex items-center gap-1.5">
                            <span class="text-xs text-gray-400">Riesgo:</span>
                            <template x-for="i in 5" :key="i">
                                <span
                                    class="w-2.5 h-2.5 rounded-full inline-block"
                                    :style="'background-color:' + (selectedPermission && i <= selectedPermission.risk_weight ? getRiskWeightColor(selectedPermission.risk_weight) : '#374151')"
                                ></span>
                            </template>
                        </div>
                        <span class="text-xs text-gray-400">|</span>
                        <span class="text-xs text-gray-400">Categoria: <span class="text-white capitalize" x-text="selectedPermission ? selectedPermission.category : ''"></span></span>
                        <span class="text-xs text-gray-400">|</span>
                        <span class="text-xs text-gray-400">Slug: <code class="text-quantum-400 text-xs" x-text="selectedPermission ? selectedPermission.slug : ''"></code></span>
                    </div>

                    @error('permission_id')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ------------------------------------------------ --}}
                {{-- PROYECTO SELECT (conditional) --}}
                {{-- ------------------------------------------------ --}}
                <div
                    x-show="showProyectoField"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2"
                    class="card-quantum p-6"
                >
                    <label for="proyecto_id" class="block text-sm font-medium text-gray-300 mb-2">
                        Proyecto <span class="text-gray-500 text-xs font-normal">(opcional)</span>
                    </label>
                    <p class="text-xs text-gray-500 mb-3">Selecciona el proyecto al que necesitas acceso.</p>

                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <select
                            name="proyecto_id"
                            id="proyecto_id"
                            x-model="form.proyecto_id"
                            class="pl-12 w-full bg-matter-light border border-matter-light rounded-quantum text-white focus:border-quantum-500 focus:ring-2 focus:ring-quantum-500/20 transition-all px-4 py-3 appearance-none cursor-pointer"
                        >
                            <option value="">-- Sin proyecto especifico --</option>
                            @foreach($proyectos as $proyecto)
                                <option value="{{ $proyecto->id }}" {{ old('proyecto_id') == $proyecto->id ? 'selected' : '' }}>
                                    {{ $proyecto->nombre_del_proyecto }}
                                    @if($proyecto->nivel_criticidad)
                                        [{{ ucfirst($proyecto->nivel_criticidad) }}]
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>

                    @error('proyecto_id')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ------------------------------------------------ --}}
                {{-- NIVEL DE ACCESO --}}
                {{-- ------------------------------------------------ --}}
                <div class="card-quantum p-6 animate-slideUp" style="animation-delay: 0.1s;">
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        Nivel de Acceso <span class="text-red-400">*</span>
                    </label>
                    <p class="text-xs text-gray-500 mb-4">Define que tipo de operaciones podras realizar con este permiso.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        {{-- Lectura --}}
                        <label
                            class="relative flex flex-col items-center gap-3 p-5 rounded-quantum border-2 cursor-pointer transition-all duration-200 group"
                            :class="form.requested_access_level === 'lectura'
                                ? 'border-quantum-500 bg-quantum-500/10 shadow-quantum'
                                : 'border-matter-light bg-matter-light hover:border-quantum-500/40 hover:bg-quantum-500/5'"
                        >
                            <input type="radio" name="requested_access_level" value="lectura" x-model="form.requested_access_level" class="sr-only" required>
                            <div
                                class="w-12 h-12 rounded-quantum flex items-center justify-center transition-all duration-200"
                                :class="form.requested_access_level === 'lectura'
                                    ? 'bg-quantum-500/20 border border-quantum-500/40'
                                    : 'bg-matter border border-matter-light group-hover:border-quantum-500/30'"
                            >
                                {{-- Eye icon --}}
                                <svg class="w-6 h-6 transition-colors duration-200" :class="form.requested_access_level === 'lectura' ? 'text-quantum-400' : 'text-gray-400 group-hover:text-quantum-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </div>
                            <div class="text-center">
                                <span class="block text-sm font-semibold transition-colors duration-200" :class="form.requested_access_level === 'lectura' ? 'text-quantum-400' : 'text-gray-300'">Lectura</span>
                                <span class="block text-xs text-gray-500 mt-1">Solo consultar</span>
                            </div>
                            <div
                                x-show="form.requested_access_level === 'lectura'"
                                x-transition
                                class="absolute top-2 right-2"
                            >
                                <svg class="w-5 h-5 text-quantum-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </label>

                        {{-- Escritura --}}
                        <label
                            class="relative flex flex-col items-center gap-3 p-5 rounded-quantum border-2 cursor-pointer transition-all duration-200 group"
                            :class="form.requested_access_level === 'escritura'
                                ? 'border-void-500 bg-void-500/10 shadow-quantum'
                                : 'border-matter-light bg-matter-light hover:border-void-500/40 hover:bg-void-500/5'"
                        >
                            <input type="radio" name="requested_access_level" value="escritura" x-model="form.requested_access_level" class="sr-only">
                            <div
                                class="w-12 h-12 rounded-quantum flex items-center justify-center transition-all duration-200"
                                :class="form.requested_access_level === 'escritura'
                                    ? 'bg-void-500/20 border border-void-500/40'
                                    : 'bg-matter border border-matter-light group-hover:border-void-500/30'"
                            >
                                {{-- Pencil icon --}}
                                <svg class="w-6 h-6 transition-colors duration-200" :class="form.requested_access_level === 'escritura' ? 'text-void-400' : 'text-gray-400 group-hover:text-void-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                            <div class="text-center">
                                <span class="block text-sm font-semibold transition-colors duration-200" :class="form.requested_access_level === 'escritura' ? 'text-void-400' : 'text-gray-300'">Escritura</span>
                                <span class="block text-xs text-gray-500 mt-1">Crear y editar</span>
                            </div>
                            <div
                                x-show="form.requested_access_level === 'escritura'"
                                x-transition
                                class="absolute top-2 right-2"
                            >
                                <svg class="w-5 h-5 text-void-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </label>

                        {{-- Administracion --}}
                        <label
                            class="relative flex flex-col items-center gap-3 p-5 rounded-quantum border-2 cursor-pointer transition-all duration-200 group"
                            :class="form.requested_access_level === 'administracion'
                                ? 'border-photon-500 bg-photon-500/10 shadow-quantum'
                                : 'border-matter-light bg-matter-light hover:border-photon-500/40 hover:bg-photon-500/5'"
                        >
                            <input type="radio" name="requested_access_level" value="administracion" x-model="form.requested_access_level" class="sr-only">
                            <div
                                class="w-12 h-12 rounded-quantum flex items-center justify-center transition-all duration-200"
                                :class="form.requested_access_level === 'administracion'
                                    ? 'bg-photon-500/20 border border-photon-500/40'
                                    : 'bg-matter border border-matter-light group-hover:border-photon-500/30'"
                            >
                                {{-- Shield icon --}}
                                <svg class="w-6 h-6 transition-colors duration-200" :class="form.requested_access_level === 'administracion' ? 'text-photon-400' : 'text-gray-400 group-hover:text-photon-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <div class="text-center">
                                <span class="block text-sm font-semibold transition-colors duration-200" :class="form.requested_access_level === 'administracion' ? 'text-photon-400' : 'text-gray-300'">Administracion</span>
                                <span class="block text-xs text-gray-500 mt-1">Control total</span>
                            </div>
                            <div
                                x-show="form.requested_access_level === 'administracion'"
                                x-transition
                                class="absolute top-2 right-2"
                            >
                                <svg class="w-5 h-5 text-photon-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </label>
                    </div>

                    @error('requested_access_level')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ------------------------------------------------ --}}
                {{-- TIPO DE DURACION --}}
                {{-- ------------------------------------------------ --}}
                <div class="card-quantum p-6 animate-slideUp" style="animation-delay: 0.15s;">
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        Tipo de Duracion <span class="text-red-400">*</span>
                    </label>
                    <p class="text-xs text-gray-500 mb-4">Define si el acceso sera temporal o permanente.</p>

                    <div class="flex items-center gap-6">
                        {{-- Toggle switch --}}
                        <div class="flex items-center gap-4 p-3 rounded-quantum bg-space-500/50 border border-matter-light">
                            <span
                                class="text-sm font-medium transition-colors duration-200"
                                :class="form.duration_type === 'temporal' ? 'text-quantum-400' : 'text-gray-500'"
                            >Temporal</span>

                            <button
                                type="button"
                                @click="form.duration_type = form.duration_type === 'temporal' ? 'permanente' : 'temporal'"
                                class="relative inline-flex h-7 w-14 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-quantum-500/20 focus:ring-offset-2 focus:ring-offset-matter"
                                :class="form.duration_type === 'permanente' ? 'bg-photon-500' : 'bg-quantum-500'"
                                role="switch"
                                :aria-checked="form.duration_type === 'permanente'"
                            >
                                <span
                                    class="pointer-events-none inline-block h-6 w-6 transform rounded-full bg-white shadow-lg ring-0 transition-transform duration-200 ease-in-out"
                                    :class="form.duration_type === 'permanente' ? 'translate-x-7' : 'translate-x-0'"
                                ></span>
                            </button>

                            <span
                                class="text-sm font-medium transition-colors duration-200"
                                :class="form.duration_type === 'permanente' ? 'text-photon-400' : 'text-gray-500'"
                            >Permanente</span>
                        </div>

                        {{-- Warning badge for permanente --}}
                        <div
                            x-show="form.duration_type === 'permanente'"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-photon-500/15 border border-photon-500/30 text-photon-400 text-xs font-medium"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            Mayor riesgo
                        </div>
                    </div>

                    <input type="hidden" name="duration_type" :value="form.duration_type">

                    @error('duration_type')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ------------------------------------------------ --}}
                {{-- FECHAS (conditional for temporal) --}}
                {{-- ------------------------------------------------ --}}
                <div
                    x-show="form.duration_type === 'temporal'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2"
                    class="card-quantum p-6"
                >
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        Periodo de Acceso
                    </label>
                    <p class="text-xs text-gray-500 mb-4">Define las fechas de inicio y fin del acceso temporal.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Starts at --}}
                        <div>
                            <label for="starts_at" class="block text-sm font-medium text-gray-300 mb-2">
                                Fecha de Inicio
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <input
                                    type="date"
                                    name="starts_at"
                                    id="starts_at"
                                    x-model="form.starts_at"
                                    class="pl-12 w-full bg-matter-light border border-matter-light rounded-quantum text-white focus:border-quantum-500 focus:ring-2 focus:ring-quantum-500/20 transition-all px-4 py-3"
                                >
                            </div>
                            @error('starts_at')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Expires at --}}
                        <div>
                            <label for="expires_at" class="block text-sm font-medium text-gray-300 mb-2">
                                Fecha de Expiracion
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <input
                                    type="date"
                                    name="expires_at"
                                    id="expires_at"
                                    x-model="form.expires_at"
                                    class="pl-12 w-full bg-matter-light border border-matter-light rounded-quantum text-white focus:border-quantum-500 focus:ring-2 focus:ring-quantum-500/20 transition-all px-4 py-3"
                                >
                            </div>
                            @error('expires_at')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ------------------------------------------------ --}}
                {{-- JUSTIFICACION --}}
                {{-- ------------------------------------------------ --}}
                <div class="card-quantum p-6 animate-slideUp" style="animation-delay: 0.2s;">
                    <div class="flex items-center justify-between mb-2">
                        <label for="justification" class="block text-sm font-medium text-gray-300">
                            Justificacion <span class="text-red-400">*</span>
                        </label>
                        <div class="flex items-center gap-2">
                            <span
                                class="text-xs transition-colors duration-200"
                                :class="justificationLength < minJustification ? 'text-red-400' : 'text-green-400'"
                            >
                                <span x-text="justificationLength"></span> caracteres
                            </span>
                            <span
                                x-show="justificationLength < minJustification"
                                class="text-xs text-gray-500"
                            >
                                (min. <span x-text="justificationRemaining"></span> mas)
                            </span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mb-3">Explica por que necesitas este acceso. Minimo <span x-text="minJustification"></span> caracteres.</p>

                    <div class="relative">
                        <div class="absolute top-4 left-4 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                        </div>
                        <textarea
                            name="justification"
                            id="justification"
                            rows="4"
                            x-model="form.justification"
                            required
                            :minlength="minJustification"
                            placeholder="Describe detalladamente por que necesitas este permiso, que tareas realizaras y el impacto en tu trabajo..."
                            class="pl-12 w-full bg-matter-light border border-matter-light rounded-quantum text-white focus:border-quantum-500 focus:ring-2 focus:ring-quantum-500/20 transition-all px-4 py-3 resize-none placeholder-gray-600"
                        ></textarea>
                    </div>

                    {{-- Character progress bar --}}
                    <div class="mt-2 h-1 rounded-full bg-matter-light overflow-hidden">
                        <div
                            class="h-full rounded-full transition-all duration-300 ease-out"
                            :style="'width:' + Math.min(100, (justificationLength / minJustification) * 100) + '%'"
                            :class="justificationLength >= minJustification ? 'bg-green-500' : 'bg-red-500'"
                        ></div>
                    </div>

                    @error('justification')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ------------------------------------------------ --}}
                {{-- SUBMIT BUTTON --}}
                {{-- ------------------------------------------------ --}}
                <div class="animate-slideUp" style="animation-delay: 0.25s;">
                    <button
                        type="submit"
                        class="btn-quantum w-full flex items-center justify-center gap-3 group relative overflow-hidden"
                        :disabled="submitting || justificationLength < minJustification || !form.permission_id"
                        :class="(submitting || justificationLength < minJustification || !form.permission_id) ? 'opacity-50 cursor-not-allowed' : 'hover:scale-[1.02]'"
                    >
                        {{-- Loading spinner --}}
                        <svg
                            x-show="submitting"
                            x-transition
                            class="w-5 h-5 animate-spin"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>

                        {{-- Send icon --}}
                        <svg
                            x-show="!submitting"
                            class="w-5 h-5 transition-transform group-hover:translate-x-1"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>

                        <span x-text="submitting ? 'Enviando solicitud...' : 'Enviar Solicitud'"></span>
                    </button>
                </div>
            </form>
        </div>

        {{-- ======================================================== --}}
        {{-- RIGHT COLUMN - LIVE RISK PREVIEW --}}
        {{-- ======================================================== --}}
        <div class="md:col-span-1">
            <div class="xl:sticky xl:top-24 space-y-6">
                <div class="card-quantum p-6 animate-slideUp" style="animation-delay: 0.1s;">
                    <h3 class="text-lg font-semibold text-white mb-1 flex items-center gap-3">
                        <div class="w-1 h-5 bg-gradient-to-b from-quantum-500 to-void-500 rounded-full"></div>
                        Analisis de Riesgo
                    </h3>
                    <p class="text-xs text-gray-500 mb-6">Vista previa en tiempo real</p>

                    {{-- ============================================ --}}
                    {{-- EMPTY STATE --}}
                    {{-- ============================================ --}}
                    <div x-show="!riskPreview && !loadingRisk" class="text-center py-8">
                        <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-matter-light flex items-center justify-center">
                            <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <p class="text-gray-400 text-sm font-medium mb-1">Sin datos de riesgo</p>
                        <p class="text-gray-600 text-xs">Selecciona un permiso para ver el analisis de riesgo</p>
                    </div>

                    {{-- ============================================ --}}
                    {{-- LOADING STATE --}}
                    {{-- ============================================ --}}
                    <div x-show="loadingRisk" class="text-center py-8">
                        <div class="w-20 h-20 mx-auto mb-4 relative">
                            <svg class="w-20 h-20 animate-spin text-quantum-500/30" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"></circle>
                                <path d="M12 2a10 10 0 019.95 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" class="text-quantum-500"></path>
                            </svg>
                        </div>
                        <p class="text-gray-400 text-sm">Calculando riesgo...</p>
                    </div>

                    {{-- ============================================ --}}
                    {{-- RISK PREVIEW DATA --}}
                    {{-- ============================================ --}}
                    <div x-show="riskPreview && !loadingRisk" x-transition>

                        {{-- Risk Score Circle --}}
                        <div class="flex flex-col items-center mb-6">
                            <div class="relative w-32 h-32">
                                <svg class="w-32 h-32 transform -rotate-90" viewBox="0 0 120 120">
                                    {{-- Background circle --}}
                                    <circle
                                        cx="60"
                                        cy="60"
                                        r="54"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="8"
                                        class="text-matter-light"
                                    />
                                    {{-- Progress circle --}}
                                    <circle
                                        cx="60"
                                        cy="60"
                                        r="54"
                                        fill="none"
                                        :stroke="riskPreview ? getRiskColor(riskPreview.risk_level) : '#6b7280'"
                                        stroke-width="8"
                                        stroke-linecap="round"
                                        :stroke-dasharray="getStrokeDasharray(riskPreview ? riskPreview.total_score : 0)"
                                        :stroke-dashoffset="getStrokeDashoffset(riskPreview ? riskPreview.total_score : 0)"
                                        class="transition-all duration-1000 ease-out"
                                    />
                                </svg>
                                {{-- Score number in center --}}
                                <div class="absolute inset-0 flex flex-col items-center justify-center">
                                    <span
                                        class="text-3xl font-bold transition-colors duration-300"
                                        :style="'color:' + (riskPreview ? getRiskColor(riskPreview.risk_level) : '#6b7280')"
                                        x-text="riskPreview ? riskPreview.total_score : 0"
                                    ></span>
                                    <span class="text-xs text-gray-500">/ 100</span>
                                </div>
                            </div>

                            {{-- Risk Level Badge --}}
                            <div class="mt-4">
                                <span
                                    class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-sm font-semibold border transition-all duration-300"
                                    :class="riskPreview ? getRiskBgClass(riskPreview.risk_level) : 'bg-gray-500/20 text-gray-400 border-gray-500/30'"
                                >
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    Riesgo <span x-text="riskPreview ? getRiskLabel(riskPreview.risk_level) : 'N/A'"></span>
                                </span>
                            </div>
                        </div>

                        {{-- Factor Breakdown --}}
                        <div class="space-y-4 mb-6">
                            <h4 class="text-sm font-medium text-gray-300 flex items-center gap-2">
                                <svg class="w-4 h-4 text-quantum-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                Desglose de Factores
                            </h4>

                            {{-- Factor 1: Permission Risk --}}
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-xs text-gray-400">Riesgo del permiso</span>
                                    <span class="text-xs font-semibold text-white" x-text="riskPreview && riskPreview.factors ? riskPreview.factors.permission_risk : 0"></span>
                                </div>
                                <div class="h-2 rounded-full bg-matter-light overflow-hidden">
                                    <div
                                        class="h-full rounded-full bg-quantum-500 transition-all duration-700 ease-out"
                                        :style="'width:' + (riskPreview && riskPreview.factors ? riskPreview.factors.permission_risk : 0) + '%'"
                                    ></div>
                                </div>
                            </div>

                            {{-- Factor 2: Resource Criticality --}}
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-xs text-gray-400">Criticidad del recurso</span>
                                    <span class="text-xs font-semibold text-white" x-text="riskPreview && riskPreview.factors ? riskPreview.factors.resource_criticality : 0"></span>
                                </div>
                                <div class="h-2 rounded-full bg-matter-light overflow-hidden">
                                    <div
                                        class="h-full rounded-full bg-void-500 transition-all duration-700 ease-out"
                                        :style="'width:' + (riskPreview && riskPreview.factors ? riskPreview.factors.resource_criticality : 0) + '%'"
                                    ></div>
                                </div>
                            </div>

                            {{-- Factor 3: Duration Risk --}}
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-xs text-gray-400">Riesgo por duracion</span>
                                    <span class="text-xs font-semibold text-white" x-text="riskPreview && riskPreview.factors ? riskPreview.factors.duration_risk : 0"></span>
                                </div>
                                <div class="h-2 rounded-full bg-matter-light overflow-hidden">
                                    <div
                                        class="h-full rounded-full bg-photon-500 transition-all duration-700 ease-out"
                                        :style="'width:' + (riskPreview && riskPreview.factors ? riskPreview.factors.duration_risk : 0) + '%'"
                                    ></div>
                                </div>
                            </div>

                            {{-- Factor 4: User Trust --}}
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-xs text-gray-400">Confianza del usuario</span>
                                    <span class="text-xs font-semibold text-white" x-text="riskPreview && riskPreview.factors ? riskPreview.factors.user_trust : 0"></span>
                                </div>
                                <div class="h-2 rounded-full bg-matter-light overflow-hidden">
                                    <div
                                        class="h-full rounded-full bg-green-500 transition-all duration-700 ease-out"
                                        :style="'width:' + (riskPreview && riskPreview.factors ? riskPreview.factors.user_trust : 0) + '%'"
                                    ></div>
                                </div>
                            </div>

                            {{-- Factor 5: Permission Accumulation --}}
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-xs text-gray-400">Acumulacion de permisos</span>
                                    <span class="text-xs font-semibold text-white" x-text="riskPreview && riskPreview.factors ? riskPreview.factors.permission_accumulation : 0"></span>
                                </div>
                                <div class="h-2 rounded-full bg-matter-light overflow-hidden">
                                    <div
                                        class="h-full rounded-full bg-amber-500 transition-all duration-700 ease-out"
                                        :style="'width:' + (riskPreview && riskPreview.factors ? riskPreview.factors.permission_accumulation : 0) + '%'"
                                    ></div>
                                </div>
                            </div>
                        </div>

                        {{-- Approval Flow Info --}}
                        <div
                            class="p-4 rounded-quantum border transition-all duration-300"
                            :class="riskPreview ? getRiskBgClass(riskPreview.risk_level) : 'bg-gray-500/10 border-gray-500/30'"
                        >
                            <div class="flex items-start gap-3">
                                {{-- Flow icon --}}
                                <div class="flex-shrink-0 mt-0.5">
                                    {{-- Auto-approval (bajo) --}}
                                    <template x-if="riskPreview && riskPreview.risk_level === 'bajo'">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </template>
                                    {{-- Single approval (medio) --}}
                                    <template x-if="riskPreview && riskPreview.risk_level === 'medio'">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </template>
                                    {{-- Double approval (alto) --}}
                                    <template x-if="riskPreview && riskPreview.risk_level === 'alto'">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                    </template>
                                    {{-- Committee (critico) --}}
                                    <template x-if="riskPreview && riskPreview.risk_level === 'critico'">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                    </template>
                                </div>

                                <div>
                                    <h5 class="text-sm font-semibold mb-1">Flujo de Aprobacion</h5>
                                    <p class="text-xs opacity-80" x-text="riskPreview ? getFlowLabel(riskPreview.risk_level) : ''"></p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Tips card --}}
                <div class="card-quantum p-6 animate-slideUp" style="animation-delay: 0.2s;">
                    <h4 class="text-sm font-semibold text-white mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-photon-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                        Consejos
                    </h4>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-quantum-500 mt-1.5 flex-shrink-0"></div>
                            <span class="text-xs text-gray-400">Solicita solo los permisos que realmente necesitas para reducir el riesgo.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-void-500 mt-1.5 flex-shrink-0"></div>
                            <span class="text-xs text-gray-400">Los accesos temporales tienen menor riesgo y se aprueban mas rapido.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-photon-500 mt-1.5 flex-shrink-0"></div>
                            <span class="text-xs text-gray-400">Una justificacion detallada facilita la aprobacion de tu solicitud.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Success message via SweetAlert2 --}}
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Solicitud enviada',
            text: '{{ session("success") }}',
            background: 'hsl(240, 12%, 10%)',
            color: '#fff',
            confirmButtonColor: 'hsl(195, 100%, 50%)',
            timer: 4000,
            timerProgressBar: true,
        });
    });
</script>
@endif

@endsection

@section('styles')
<style>
    [x-cloak] {
        display: none !important;
    }

    /* Custom select styling for dark theme */
    select option {
        background-color: hsl(240, 12%, 10%);
        color: #fff;
    }

    select optgroup {
        background-color: hsl(240, 12%, 8%);
        color: #9ca3af;
        font-weight: 600;
        font-style: normal;
    }

    /* Smooth stroke animation for SVG circle */
    circle {
        transition: stroke-dashoffset 1s ease-out, stroke 0.5s ease;
    }
</style>
@endsection
