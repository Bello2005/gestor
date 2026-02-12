@extends('layouts.quantum')

@section('title', 'Solicitudes de Acceso')

@section('content')
<div x-data="{
    approveModal: { open: false, id: null },
    rejectModal: { open: false, id: null, comment: '' },
    processing: false,
    notification: { show: false, type: 'success', message: '', details: '' },
    copied: false,

    openApproveModal(id) {
        this.approveModal = { open: true, id };
    },

    openRejectModal(id) {
        this.rejectModal = { open: true, id, comment: '' };
    },

    async submitApproval() {
        this.processing = true;
        try {
            const response = await fetch(`{{ url('access-requests') }}/${this.approveModal.id}/approve`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await response.json();
            this.processing = false;
            if (data.success) {
                this.approveModal.open = false;
                this.showNotification('success', data.message || 'Solicitud aprobada exitosamente.', data.details || '');
                setTimeout(() => window.location.reload(), 1200);
            } else {
                this.showNotification('error', data.error || 'Error al aprobar la solicitud.');
            }
        } catch (e) {
            this.processing = false;
            this.showNotification('error', 'Error de conexion al procesar la aprobacion.');
        }
    },

    async submitRejection() {
        if (!this.rejectModal.comment || this.rejectModal.comment.length < 5) {
            this.showNotification('error', 'El comentario debe tener al menos 5 caracteres.');
            return;
        }
        this.processing = true;
        try {
            const response = await fetch(`{{ url('access-requests') }}/${this.rejectModal.id}/reject`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ admin_comment: this.rejectModal.comment })
            });
            const data = await response.json();
            this.processing = false;
            if (data.success) {
                this.rejectModal.open = false;
                this.showNotification('success', data.message || 'Solicitud rechazada exitosamente.');
                setTimeout(() => window.location.reload(), 1200);
            } else {
                this.showNotification('error', data.error || 'Error al rechazar la solicitud.');
            }
        } catch (e) {
            this.processing = false;
            this.showNotification('error', 'Error de conexion al procesar el rechazo.');
        }
    },

    copyPublicLink() {
        navigator.clipboard.writeText('{{ route('access-requests.create') }}');
        this.copied = true;
        setTimeout(() => this.copied = false, 2000);
    },

    showNotification(type, message, details = '') {
        this.notification = { show: true, type, message, details };
        setTimeout(() => { this.notification.show = false; }, 5000);
    }
}" class="space-y-6">

    {{-- ============================================================ --}}
    {{-- 1. HEADER --}}
    {{-- ============================================================ --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-quantum-500 via-void-500 to-photon-500 bg-clip-text text-transparent">
                Solicitudes de Acceso
            </h1>
            <p class="text-gray-400 mt-2">Gestion de nuevos miembros del sistema</p>
        </div>

        <button @click="copyPublicLink()"
                class="btn-quantum flex items-center gap-2 group relative">
            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
            </svg>
            <span x-text="copied ? 'Copiado!' : 'Copiar Link Publico'"></span>
        </button>
    </div>

    {{-- ============================================================ --}}
    {{-- 2. STATS CARDS --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total --}}
        <div class="card-quantum p-5 group hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Total</p>
                    <p class="text-3xl font-bold text-white">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-quantum-500/20 to-void-500/20 rounded-quantum flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-quantum-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Pendientes --}}
        <div class="card-quantum p-5 group hover:scale-105 transition-all duration-300 {{ $stats['pending'] > 0 ? 'border-amber-500/30' : '' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Pendientes</p>
                    <p class="text-3xl font-bold bg-gradient-to-r from-amber-400 to-yellow-500 bg-clip-text text-transparent {{ $stats['pending'] > 0 ? 'animate-pulse' : '' }}">{{ $stats['pending'] }}</p>
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
    </div>

    {{-- ============================================================ --}}
    {{-- 3. FILTERS BAR --}}
    {{-- ============================================================ --}}
    <div class="card-quantum p-4">
        <form method="GET" action="{{ route('solicitudes-acceso.index') }}" class="flex flex-col md:flex-row items-end gap-4">
            {{-- Search --}}
            <div class="w-full md:flex-1">
                <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1.5">Buscar</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre o email..."
                           class="w-full bg-matter-light border border-matter-light rounded-quantum text-white text-sm pl-10 pr-3 py-2.5 focus:border-quantum-500 focus:ring-2 focus:ring-quantum-500/20 transition-all placeholder-gray-500">
                </div>
            </div>

            {{-- Status --}}
            <div class="w-full md:w-auto md:flex-1">
                <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1.5">Estado</label>
                <select name="status"
                        class="w-full bg-matter-light border border-matter-light rounded-quantum text-white text-sm px-3 py-2.5 focus:border-quantum-500 focus:ring-2 focus:ring-quantum-500/20 transition-all">
                    <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>Todos</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendientes</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Aprobadas</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rechazadas</option>
                </select>
            </div>

            {{-- Date From --}}
            <div class="w-full md:w-auto md:flex-1">
                <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1.5">Desde</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="w-full bg-matter-light border border-matter-light rounded-quantum text-white text-sm px-3 py-2.5 focus:border-quantum-500 focus:ring-2 focus:ring-quantum-500/20 transition-all">
            </div>

            {{-- Date To --}}
            <div class="w-full md:w-auto md:flex-1">
                <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1.5">Hasta</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
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
    {{-- 4. DATA TABLE --}}
    {{-- ============================================================ --}}
    @if($requests->isEmpty())
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
                    Comparte el link publico para que nuevos miembros puedan solicitar acceso.
                </p>
                <button @click="copyPublicLink()"
                        class="btn-quantum flex items-center gap-2 mt-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    <span x-text="copied ? 'Copiado!' : 'Copiar Link Publico'"></span>
                </button>
            </div>
        </div>
    @else
        <div class="card-quantum overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-space-500/50 border-b border-matter-light">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Solicitante</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Contacto</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Razon</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-300 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-matter-light">
                        @foreach($requests as $accessRequest)
                        <tr class="hover:bg-matter-light/50 transition-colors duration-200">
                            {{-- Solicitante --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-quantum-500/20 to-void-500/20 flex items-center justify-center text-quantum-500 font-bold text-sm border border-quantum-500/30 flex-shrink-0">
                                        {{ strtoupper(substr($accessRequest->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-white font-medium truncate">{{ $accessRequest->name }}</p>
                                        <p class="text-gray-500 text-xs">#{{ $accessRequest->id }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Contacto --}}
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <p class="text-white text-sm flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="truncate">{{ $accessRequest->email }}</span>
                                    </p>
                                    @if($accessRequest->phone)
                                    <p class="text-gray-400 text-sm flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        {{ $accessRequest->phone }}
                                    </p>
                                    @endif
                                </div>
                            </td>

                            {{-- Razon --}}
                            <td class="px-6 py-4">
                                <p class="text-gray-300 text-sm max-w-xs" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $accessRequest->reason }}</p>
                            </td>

                            {{-- Estado --}}
                            <td class="px-6 py-4">
                                @if($accessRequest->status === 'pending')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-full bg-amber-500/20 text-amber-400">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                                        Pendiente
                                    </span>
                                @elseif($accessRequest->status === 'approved')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-full bg-green-500/20 text-green-400">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Aprobada
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-full bg-red-500/20 text-red-400">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Rechazada
                                    </span>
                                @endif
                            </td>

                            {{-- Fecha --}}
                            <td class="px-6 py-4">
                                <p class="text-gray-300 text-sm">{{ $accessRequest->created_at->format('d/m/Y') }}</p>
                                <p class="text-gray-500 text-xs">{{ $accessRequest->created_at->format('H:i') }}</p>
                            </td>

                            {{-- Acciones --}}
                            <td class="px-6 py-4 text-right">
                                @if($accessRequest->status === 'pending')
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="openApproveModal({{ $accessRequest->id }})"
                                            class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-green-400 border border-green-500/30 rounded-quantum hover:bg-green-500/10 transition-all"
                                            title="Aprobar solicitud">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Aprobar
                                    </button>
                                    <button @click="openRejectModal({{ $accessRequest->id }})"
                                            class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-red-400 border border-red-500/30 rounded-quantum hover:bg-red-500/10 transition-all"
                                            title="Rechazar solicitud">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Rechazar
                                    </button>
                                </div>
                                @else
                                <div class="text-right">
                                    @if($accessRequest->reviewed_at)
                                    <p class="text-gray-500 text-xs">Revisada {{ $accessRequest->reviewed_at->diffForHumans() }}</p>
                                    @endif
                                    @if($accessRequest->admin_comment)
                                    <p class="text-gray-400 text-xs mt-1 max-w-[200px] ml-auto" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" title="{{ $accessRequest->admin_comment }}">{{ $accessRequest->admin_comment }}</p>
                                    @endif
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($requests->hasPages())
            <div class="border-t border-matter-light px-6 py-4 flex justify-center">
                {{ $requests->appends(request()->all())->links() }}
            </div>
            @endif
        </div>
    @endif

    {{-- ============================================================ --}}
    {{-- 5. APPROVE MODAL --}}
    {{-- ============================================================ --}}
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

            {{-- Header --}}
            <div class="px-6 pt-6 pb-4 border-b border-matter-light">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-quantum flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Aprobar Solicitud</h3>
                        <p class="text-sm text-gray-400 mt-0.5">Se creara una cuenta para el solicitante</p>
                    </div>
                </div>
            </div>

            {{-- Body --}}
            <div class="px-6 py-5 space-y-4">
                <div class="bg-green-500/5 border border-green-500/20 rounded-quantum p-4 space-y-2">
                    <div class="flex items-center gap-2 text-sm text-green-300">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Usuario creado con rol "colaborador"
                    </div>
                    <div class="flex items-center gap-2 text-sm text-green-300">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Contrasena temporal generada
                    </div>
                    <div class="flex items-center gap-2 text-sm text-green-300">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Email de bienvenida con credenciales enviado
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-matter-light bg-matter/50 flex items-center justify-end gap-3">
                <button type="button"
                        @click="approveModal.open = false"
                        :disabled="processing"
                        class="px-4 py-2.5 text-sm font-medium text-gray-300 border border-matter-light rounded-quantum hover:bg-matter-light transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                    Cancelar
                </button>
                <button type="button"
                        @click="submitApproval()"
                        :disabled="processing"
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

    {{-- ============================================================ --}}
    {{-- 6. REJECT MODAL --}}
    {{-- ============================================================ --}}
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

            {{-- Header --}}
            <div class="px-6 pt-6 pb-4 border-b border-matter-light">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500/20 to-rose-500/20 rounded-quantum flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Rechazar Solicitud</h3>
                        <p class="text-sm text-gray-400 mt-0.5">Indica el motivo del rechazo</p>
                    </div>
                </div>
            </div>

            {{-- Body --}}
            <div class="px-6 py-5 space-y-4">
                <div class="bg-red-500/5 border border-red-500/20 rounded-quantum p-3">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <p class="text-sm text-red-300">
                            El solicitante no sera notificado automaticamente. No se creara ninguna cuenta.
                        </p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        Motivo del Rechazo <span class="text-red-400">*</span>
                    </label>
                    <textarea x-model="rejectModal.comment"
                              rows="4"
                              placeholder="Explica brevemente el motivo del rechazo (min. 5 caracteres)..."
                              :disabled="processing"
                              class="w-full bg-matter-light border border-matter-light rounded-quantum text-white text-sm px-3 py-2.5 focus:border-quantum-500 focus:ring-2 focus:ring-quantum-500/20 transition-all resize-none placeholder-gray-500 disabled:opacity-50 disabled:cursor-not-allowed"></textarea>
                    <p class="text-xs text-gray-500 mt-1" x-text="(rejectModal.comment?.length || 0) + ' / minimo 5 caracteres'"></p>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-matter-light bg-matter/50 flex items-center justify-end gap-3">
                <button type="button"
                        @click="rejectModal.open = false"
                        :disabled="processing"
                        class="px-4 py-2.5 text-sm font-medium text-gray-300 border border-matter-light rounded-quantum hover:bg-matter-light transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                    Cancelar
                </button>
                <button type="button"
                        @click="submitRejection()"
                        :disabled="processing || !rejectModal.comment || rejectModal.comment.length < 5"
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

    {{-- ============================================================ --}}
    {{-- 7. NOTIFICATION TOAST --}}
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
                    <p x-show="notification.details" class="text-xs text-gray-400 mt-1" x-text="notification.details"></p>
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
