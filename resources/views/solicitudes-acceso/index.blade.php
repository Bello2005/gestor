@extends('layouts.quantum')

@section('title', 'Solicitudes de Acceso')

@section('content')
<div x-data="{
    approveModal: { open: false, id: null, rationale: '' },
    rejectModal: { open: false, id: null, rationale: '' },
    processing: false,
    notification: { show: false, type: 'success', message: '' },

    openApproveModal(id) {
        this.approveModal = { open: true, id: id, rationale: '' };
    },

    openRejectModal(id) {
        this.rejectModal = { open: true, id: id, rationale: '' };
    },

    async submitApproval() {
        if (!this.approveModal.rationale || this.approveModal.rationale.length < 10) {
            this.showNotification('error', 'La justificacion debe tener al menos 10 caracteres.');
            return;
        }
        this.processing = true;
        try {
            const response = await fetch(`{{ url('solicitudes-acceso') }}/${this.approveModal.id}/approve`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ decision_rationale: this.approveModal.rationale })
            });
            const data = await response.json();
            this.processing = false;
            if (data.success) {
                this.approveModal.open = false;
                this.showNotification('success', data.message || 'Solicitud aprobada exitosamente.');
                setTimeout(() => window.location.reload(), 1200);
            } else {
                this.showNotification('error', data.error || data.message || 'Error al aprobar la solicitud.');
            }
        } catch (e) {
            this.processing = false;
            this.showNotification('error', 'Error de conexion al procesar la aprobacion.');
        }
    },

    async submitRejection() {
        if (!this.rejectModal.rationale || this.rejectModal.rationale.length < 10) {
            this.showNotification('error', 'La justificacion debe tener al menos 10 caracteres.');
            return;
        }
        this.processing = true;
        try {
            const response = await fetch(`{{ url('solicitudes-acceso') }}/${this.rejectModal.id}/reject`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ decision_rationale: this.rejectModal.rationale })
            });
            const data = await response.json();
            this.processing = false;
            if (data.success) {
                this.rejectModal.open = false;
                this.showNotification('success', data.message || 'Solicitud rechazada exitosamente.');
                setTimeout(() => window.location.reload(), 1200);
            } else {
                this.showNotification('error', data.error || data.message || 'Error al rechazar la solicitud.');
            }
        } catch (e) {
            this.processing = false;
            this.showNotification('error', 'Error de conexion al procesar el rechazo.');
        }
    },

    showNotification(type, message) {
        this.notification = { show: true, type, message };
        setTimeout(() => { this.notification.show = false; }, 5000);
    }
}" class="space-y-6">

    {{-- ============================================================ --}}
    {{-- 1. HEADER ROW --}}
    {{-- ============================================================ --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-quantum-500 via-void-500 to-photon-500 bg-clip-text text-transparent">
                Solicitudes de Acceso
            </h1>
            <p class="text-gray-400 mt-2">Control adaptativo de accesos basado en riesgo</p>
        </div>

        <a href="{{ route('solicitudes-acceso.create') }}"
           class="btn-quantum flex items-center gap-2 group">
            <svg class="w-5 h-5 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Nueva Solicitud</span>
        </a>
    </div>

    {{-- Gestor info banner --}}
    @if(isset($isGestor) && $isGestor && !(isset($isAdmin) && $isAdmin))
    <div class="card-quantum p-4 border-blue-500/30 bg-blue-500/5">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-quantum bg-blue-500/20 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-sm text-blue-300">Vista de Gestor: puedes ver todas las solicitudes para supervisar el acceso a tus proyectos, pero solo puedes gestionar las tuyas.</span>
        </div>
    </div>
    @endif

    {{-- ============================================================ --}}
    {{-- 2. STATS CARDS --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        {{-- Total --}}
        <div class="card-quantum p-5 group hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Total</p>
                    <p class="text-3xl font-bold text-white">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-quantum-500/20 to-void-500/20 rounded-quantum flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-quantum-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Pendientes --}}
        <div class="card-quantum p-5 group hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Pendientes</p>
                    <p class="text-3xl font-bold bg-gradient-to-r from-amber-400 to-yellow-500 bg-clip-text text-transparent">{{ $stats['pending'] }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-amber-500/20 to-yellow-500/20 rounded-quantum flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Aprobadas --}}
        <div class="card-quantum p-5 group hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Aprobadas</p>
                    <p class="text-3xl font-bold bg-gradient-to-r from-green-400 to-emerald-500 bg-clip-text text-transparent">{{ $stats['approved'] }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-quantum flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Rechazadas --}}
        <div class="card-quantum p-5 group hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Rechazadas</p>
                    <p class="text-3xl font-bold bg-gradient-to-r from-red-400 to-rose-500 bg-clip-text text-transparent">{{ $stats['rejected'] }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-red-500/20 to-rose-500/20 rounded-quantum flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Alto Riesgo --}}
        <div class="card-quantum p-5 group hover:scale-105 transition-all duration-300 {{ $stats['high_risk'] > 0 ? 'border-red-500/40' : '' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Alto Riesgo</p>
                    <p class="text-3xl font-bold text-red-400 {{ $stats['high_risk'] > 0 ? 'animate-pulse' : '' }}">{{ $stats['high_risk'] }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-red-500/20 to-orange-500/20 rounded-quantum flex items-center justify-center group-hover:scale-110 transition-transform {{ $stats['high_risk'] > 0 ? 'animate-pulse' : '' }}">
                    <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- 3. FILTERS BAR --}}
    {{-- ============================================================ --}}
    <div class="card-quantum p-4"
         x-data="{
            status: '{{ request('status', '') }}',
            risk_level: '{{ request('risk_level', '') }}',
            date_from: '{{ request('date_from', '') }}',
            date_to: '{{ request('date_to', '') }}'
         }">
        <form method="GET" action="{{ route('solicitudes-acceso.index') }}" class="flex flex-col md:flex-row items-end gap-4">
            {{-- Status --}}
            <div class="w-full md:w-auto md:flex-1">
                <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1.5">Estado</label>
                <select name="status" x-model="status"
                        class="w-full bg-matter-light border border-matter-light rounded-quantum text-white text-sm px-3 py-2.5 focus:border-quantum-500 focus:ring-2 focus:ring-quantum-500/20 transition-all">
                    <option value="">Todos los estados</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="aprobada">Aprobada</option>
                    <option value="rechazada">Rechazada</option>
                    <option value="expirada">Expirada</option>
                    <option value="revocada">Revocada</option>
                </select>
            </div>

            {{-- Risk Level --}}
            <div class="w-full md:w-auto md:flex-1">
                <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1.5">Nivel de Riesgo</label>
                <select name="risk_level" x-model="risk_level"
                        class="w-full bg-matter-light border border-matter-light rounded-quantum text-white text-sm px-3 py-2.5 focus:border-quantum-500 focus:ring-2 focus:ring-quantum-500/20 transition-all">
                    <option value="">Todos los niveles</option>
                    <option value="bajo">Bajo</option>
                    <option value="medio">Medio</option>
                    <option value="alto">Alto</option>
                    <option value="critico">Critico</option>
                </select>
            </div>

            {{-- Date From --}}
            <div class="w-full md:w-auto md:flex-1">
                <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1.5">Desde</label>
                <input type="date" name="date_from" x-model="date_from"
                       class="w-full bg-matter-light border border-matter-light rounded-quantum text-white text-sm px-3 py-2.5 focus:border-quantum-500 focus:ring-2 focus:ring-quantum-500/20 transition-all">
            </div>

            {{-- Date To --}}
            <div class="w-full md:w-auto md:flex-1">
                <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1.5">Hasta</label>
                <input type="date" name="date_to" x-model="date_to"
                       class="w-full bg-matter-light border border-matter-light rounded-quantum text-white text-sm px-3 py-2.5 focus:border-quantum-500 focus:ring-2 focus:ring-quantum-500/20 transition-all">
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-2 w-full md:w-auto">
                <button type="submit"
                        class="btn-quantum px-4 py-2.5 text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filtrar
                </button>
                <a href="{{ route('solicitudes-acceso.index') }}"
                   class="px-4 py-2.5 text-sm text-gray-400 hover:text-white border border-matter-light rounded-quantum hover:bg-matter-light transition-all">
                    Limpiar
                </a>
            </div>
        </form>
    </div>

    {{-- ============================================================ --}}
    {{-- 4. REQUEST CARDS LIST --}}
    {{-- ============================================================ --}}
    @if($requests->isEmpty())
        {{-- Empty State --}}
        <div class="card-quantum p-12">
            <div class="flex flex-col items-center justify-center space-y-4">
                <div class="w-20 h-20 bg-gradient-to-br from-quantum-500/10 to-void-500/10 rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white">No hay solicitudes</h3>
                <p class="text-gray-400 text-center max-w-md">
                    No se encontraron solicitudes de acceso que coincidan con los filtros seleccionados.
                    Puedes crear una nueva solicitud o ajustar los criterios de busqueda.
                </p>
                <a href="{{ route('solicitudes-acceso.create') }}"
                   class="btn-quantum flex items-center gap-2 mt-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Crear Solicitud
                </a>
            </div>
        </div>
    @else
        <div class="space-y-4">
            @foreach($requests as $accessRequest)
                <div class="card-quantum hover:border-quantum-500/50 transition-all duration-300">
                    {{-- Card Header --}}
                    <div class="p-5 border-b border-matter-light flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                        {{-- Left: Risk badge + Permission --}}
                        <div class="flex items-center flex-wrap gap-2">
                            {{-- Risk Level Badge --}}
                            @switch($accessRequest->risk_level)
                                @case('bajo')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full border bg-green-500/20 border-green-500/30 text-green-400">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                                        Bajo
                                    </span>
                                    @break
                                @case('medio')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full border bg-amber-500/20 border-amber-500/30 text-amber-400">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                                        Medio
                                    </span>
                                    @break
                                @case('alto')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full border bg-orange-500/20 border-orange-500/30 text-orange-400">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                                        Alto
                                    </span>
                                    @break
                                @case('critico')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full border bg-red-500/20 border-red-500/30 text-red-400 animate-pulse">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                                        Critico
                                    </span>
                                    @break
                                @default
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full border bg-gray-500/20 border-gray-500/30 text-gray-400">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                                        {{ ucfirst($accessRequest->risk_level ?? 'N/A') }}
                                    </span>
                            @endswitch

                            {{-- Risk Score --}}
                            <span class="text-xs text-gray-400 font-mono">
                                Score: {{ $accessRequest->risk_score ?? 0 }}
                            </span>

                            {{-- Permission Name --}}
                            <span class="text-sm font-semibold text-white">
                                {{ $accessRequest->permission->name ?? 'Permiso no disponible' }}
                            </span>
                        </div>

                        {{-- Right: Status + Date --}}
                        <div class="flex items-center gap-3">
                            {{-- Status Badge --}}
                            @switch($accessRequest->status)
                                @case('pendiente')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-full bg-amber-500/20 text-amber-400">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Pendiente
                                    </span>
                                    @break
                                @case('aprobada')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-full bg-green-500/20 text-green-400">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Aprobada
                                    </span>
                                    @break
                                @case('rechazada')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-full bg-red-500/20 text-red-400">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Rechazada
                                    </span>
                                    @break
                                @case('expirada')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-full bg-gray-500/20 text-gray-400">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Expirada
                                    </span>
                                    @break
                                @case('revocada')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-full bg-red-500/20 text-red-400">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                        </svg>
                                        Revocada
                                    </span>
                                    @break
                            @endswitch

                            {{-- Date --}}
                            <span class="text-xs text-gray-500">
                                {{ $accessRequest->created_at->format('d/m/Y H:i') }}
                            </span>
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="p-5 grid grid-cols-1 md:grid-cols-4 gap-4">
                        {{-- Solicitante --}}
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-quantum-500/20 to-void-500/20 flex items-center justify-center flex-shrink-0 border border-quantum-500/20">
                                <svg class="w-4 h-4 text-quantum-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs text-gray-500 uppercase tracking-wider mb-0.5">Solicitante</p>
                                <p class="text-sm font-medium text-white truncate">{{ $accessRequest->user->name ?? 'Usuario desconocido' }}</p>
                            </div>
                        </div>

                        {{-- Proyecto --}}
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-void-500/20 to-photon-500/20 flex items-center justify-center flex-shrink-0 border border-void-500/20">
                                <svg class="w-4 h-4 text-void-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs text-gray-500 uppercase tracking-wider mb-0.5">Proyecto</p>
                                <p class="text-sm font-medium text-white truncate">{{ $accessRequest->proyecto->nombre_del_proyecto ?? 'General' }}</p>
                            </div>
                        </div>

                        {{-- Duracion --}}
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-photon-500/20 to-amber-500/20 flex items-center justify-center flex-shrink-0 border border-photon-500/20">
                                <svg class="w-4 h-4 text-photon-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs text-gray-500 uppercase tracking-wider mb-0.5">Duracion</p>
                                <p class="text-sm font-medium text-white">
                                    {{ $accessRequest->duration_type === 'permanente' ? 'Permanente' : 'Temporal' }}
                                </p>
                                @if($accessRequest->duration_type === 'temporal')
                                    <p class="text-xs text-gray-400">
                                        {{ $accessRequest->starts_at?->format('d/m/Y') }} - {{ $accessRequest->expires_at?->format('d/m/Y') }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- Acciones --}}
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('solicitudes-acceso.show', $accessRequest) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-quantum-400 border border-quantum-500/30 rounded-quantum hover:bg-quantum-500/10 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Ver Detalles
                            </a>

                            @if($isAdmin && $accessRequest->status === 'pendiente')
                                <button @click="openApproveModal({{ $accessRequest->id }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-green-400 border border-green-500/30 rounded-quantum hover:bg-green-500/10 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Aprobar
                                </button>
                                <button @click="openRejectModal({{ $accessRequest->id }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-red-400 border border-red-500/30 rounded-quantum hover:bg-red-500/10 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Rechazar
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ============================================================ --}}
        {{-- 5. PAGINATION --}}
        {{-- ============================================================ --}}
        @if($requests->hasPages())
            <div class="flex justify-center">
                {{ $requests->appends(request()->all())->links() }}
            </div>
        @endif
    @endif

    {{-- ============================================================ --}}
    {{-- 7. ADMIN MODALS --}}
    {{-- ============================================================ --}}
    @if($isAdmin)
        {{-- APPROVE MODAL --}}
        <div x-show="approveModal.open"
             x-cloak
             @keydown.escape.window="if(!processing) approveModal.open = false"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

            <div @click.away="if(!processing) approveModal.open = false"
                 class="card-quantum max-w-lg w-full p-0 overflow-hidden"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-90">

                {{-- Modal Header --}}
                <div class="px-6 pt-6 pb-4 border-b border-matter-light">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-quantum flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Aprobar Solicitud</h3>
                            <p class="text-sm text-gray-400 mt-0.5">Proporciona una justificacion para la aprobacion</p>
                        </div>
                    </div>
                </div>

                {{-- Modal Body --}}
                <div class="px-6 py-5 space-y-4">
                    <div class="bg-green-500/5 border border-green-500/20 rounded-quantum p-3">
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm text-green-300">
                                Al aprobar esta solicitud, el usuario recibira los permisos solicitados de acuerdo al nivel de acceso y duracion especificados.
                            </p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            Justificacion de la Decision <span class="text-red-400">*</span>
                        </label>
                        <textarea x-model="approveModal.rationale"
                                  rows="4"
                                  placeholder="Explica por que se aprueba esta solicitud (min. 10 caracteres)..."
                                  :disabled="processing"
                                  class="w-full bg-matter-light border border-matter-light rounded-quantum text-white text-sm px-3 py-2.5 focus:border-quantum-500 focus:ring-2 focus:ring-quantum-500/20 transition-all resize-none placeholder-gray-500 disabled:opacity-50 disabled:cursor-not-allowed"></textarea>
                        <p class="text-xs text-gray-500 mt-1" x-text="(approveModal.rationale?.length || 0) + ' / minimo 10 caracteres'"></p>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="px-6 py-4 border-t border-matter-light bg-matter/50 flex items-center justify-end gap-3">
                    <button type="button"
                            @click="approveModal.open = false"
                            :disabled="processing"
                            class="px-4 py-2.5 text-sm font-medium text-gray-300 border border-matter-light rounded-quantum hover:bg-matter-light transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        Cancelar
                    </button>
                    <button type="button"
                            @click="submitApproval()"
                            :disabled="processing || !approveModal.rationale || approveModal.rationale.length < 10"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-green-500 to-emerald-600 rounded-quantum hover:from-green-400 hover:to-emerald-500 transition-all shadow-lg shadow-green-500/20 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                        <template x-if="processing">
                            <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </template>
                        <template x-if="!processing">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </template>
                        <span x-text="processing ? 'Procesando...' : 'Confirmar Aprobacion'"></span>
                    </button>
                </div>
            </div>
        </div>

        {{-- REJECT MODAL --}}
        <div x-show="rejectModal.open"
             x-cloak
             @keydown.escape.window="if(!processing) rejectModal.open = false"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

            <div @click.away="if(!processing) rejectModal.open = false"
                 class="card-quantum max-w-lg w-full p-0 overflow-hidden"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-90">

                {{-- Modal Header --}}
                <div class="px-6 pt-6 pb-4 border-b border-matter-light">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-red-500/20 to-rose-500/20 rounded-quantum flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Rechazar Solicitud</h3>
                            <p class="text-sm text-gray-400 mt-0.5">Proporciona una justificacion para el rechazo</p>
                        </div>
                    </div>
                </div>

                {{-- Modal Body --}}
                <div class="px-6 py-5 space-y-4">
                    <div class="bg-red-500/5 border border-red-500/20 rounded-quantum p-3">
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <p class="text-sm text-red-300">
                                Esta accion rechazara la solicitud de acceso. El solicitante sera notificado y no recibira los permisos solicitados.
                            </p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            Justificacion del Rechazo <span class="text-red-400">*</span>
                        </label>
                        <textarea x-model="rejectModal.rationale"
                                  rows="4"
                                  placeholder="Explica por que se rechaza esta solicitud (min. 10 caracteres)..."
                                  :disabled="processing"
                                  class="w-full bg-matter-light border border-matter-light rounded-quantum text-white text-sm px-3 py-2.5 focus:border-quantum-500 focus:ring-2 focus:ring-quantum-500/20 transition-all resize-none placeholder-gray-500 disabled:opacity-50 disabled:cursor-not-allowed"></textarea>
                        <p class="text-xs text-gray-500 mt-1" x-text="(rejectModal.rationale?.length || 0) + ' / minimo 10 caracteres'"></p>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="px-6 py-4 border-t border-matter-light bg-matter/50 flex items-center justify-end gap-3">
                    <button type="button"
                            @click="rejectModal.open = false"
                            :disabled="processing"
                            class="px-4 py-2.5 text-sm font-medium text-gray-300 border border-matter-light rounded-quantum hover:bg-matter-light transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        Cancelar
                    </button>
                    <button type="button"
                            @click="submitRejection()"
                            :disabled="processing || !rejectModal.rationale || rejectModal.rationale.length < 10"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-red-500 to-rose-600 rounded-quantum hover:from-red-400 hover:to-rose-500 transition-all shadow-lg shadow-red-500/20 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                        <template x-if="processing">
                            <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </template>
                        <template x-if="!processing">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </template>
                        <span x-text="processing ? 'Procesando...' : 'Confirmar Rechazo'"></span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- ============================================================ --}}
    {{-- NOTIFICATION TOAST --}}
    {{-- ============================================================ --}}
    <div x-show="notification.show"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2 translate-x-2"
         x-transition:enter-end="opacity-100 translate-y-0 translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         class="fixed top-6 right-6 z-[60] max-w-sm"
         @click="notification.show = false">
        <div :class="{
            'border-green-500/30 bg-green-500/10': notification.type === 'success',
            'border-red-500/30 bg-red-500/10': notification.type === 'error'
        }"
        class="card-quantum border p-4 cursor-pointer hover:scale-105 transition-transform shadow-quantum-lg backdrop-blur-sm">
            <div class="flex items-start gap-3">
                <template x-if="notification.type === 'success'">
                    <svg class="w-5 h-5 text-green-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </template>
                <template x-if="notification.type === 'error'">
                    <svg class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </template>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white" x-text="notification.message"></p>
                </div>
                <button @click.stop="notification.show = false" class="text-gray-400 hover:text-gray-300 transition-colors flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

</div>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>
@endsection
