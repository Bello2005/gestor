@extends('layouts.quantum')

@section('title', 'Solicitudes de Acceso')

@section('content')
<div x-data="{
    approveModalOpen: false,
    rejectModalOpen: false,
    currentRequest: null,
    rejectComment: '',
    searchQuery: '',
    selectedStatus: '{{ request('status', 'all') }}',
    loading: false,
    notification: {
        show: false,
        type: 'success',
        message: '',
        details: ''
    },
    showNotification(type, message, details = '') {
        this.notification = { show: true, type, message, details };
        setTimeout(() => { this.notification.show = false; }, 5000);
    }
}" class="space-y-6 animate-fadeIn">

    <!-- Header Zidane Class -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-quantum-500 via-void-500 to-photon-500 bg-clip-text text-transparent">
                Solicitudes de Acceso
            </h1>
            <p class="text-gray-400 mt-2">Gestión elegante de nuevos miembros</p>
        </div>

        <!-- Public Request Link -->
        <a href="{{ route('access-requests.create') }}" target="_blank"
           class="btn-quantum flex items-center gap-2 group">
            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
            </svg>
            <span>Link Público</span>
        </a>
    </div>

    <!-- Stats Cards Maldini -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Requests -->
        <div class="card-quantum p-6 group hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm uppercase tracking-wider mb-1">Total</p>
                    <p class="text-3xl font-bold text-white">{{ $requests->total() }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-quantum-500/20 to-void-500/20 rounded-quantum flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-quantum-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending -->
        @php
            $allRequests = $requests->getCollection();
            $pending = $allRequests->where('status', 'pending')->count();
        @endphp
        <div class="card-quantum p-6 group hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm uppercase tracking-wider mb-1">Pendientes</p>
                    <p class="text-3xl font-bold bg-gradient-to-r from-yellow-400 to-amber-500 bg-clip-text text-transparent">{{ $pending }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-yellow-500/20 to-amber-500/20 rounded-quantum flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Approved -->
        @php
            $approved = $allRequests->where('status', 'approved')->count();
        @endphp
        <div class="card-quantum p-6 group hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm uppercase tracking-wider mb-1">Aprobadas</p>
                    <p class="text-3xl font-bold bg-gradient-to-r from-green-400 to-emerald-500 bg-clip-text text-transparent">{{ $approved }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-quantum flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Rejected -->
        @php
            $rejected = $allRequests->where('status', 'rejected')->count();
        @endphp
        <div class="card-quantum p-6 group hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm uppercase tracking-wider mb-1">Rechazadas</p>
                    <p class="text-3xl font-bold bg-gradient-to-r from-red-400 to-rose-500 bg-clip-text text-transparent">{{ $rejected }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-red-500/20 to-rose-500/20 rounded-quantum flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card-quantum p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search -->
            <div class="relative">
                <label class="block text-sm font-medium text-gray-300 mb-2">Buscar</label>
                <div class="relative">
                    <input type="text"
                           x-model="searchQuery"
                           placeholder="Nombre o email..."
                           class="input-quantum pl-10">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Estado</label>
                <select x-model="selectedStatus"
                        @change="window.location.href = '{{ route('access-requests.index') }}?status=' + selectedStatus"
                        class="input-quantum">
                    <option value="all">Todos</option>
                    <option value="pending">Pendientes</option>
                    <option value="approved">Aprobadas</option>
                    <option value="rejected">Rechazadas</option>
                </select>
            </div>

            <!-- Sort -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Ordenar por</label>
                <select class="input-quantum">
                    <option value="recent">Más recientes</option>
                    <option value="oldest">Más antiguas</option>
                    <option value="name">Nombre A-Z</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="card-quantum overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-space-500/50 border-b border-matter-light">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Solicitante</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Contacto</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Razón</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-300 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-matter-light">
                    @forelse($requests as $request)
                    <tr class="hover:bg-matter-light/50 transition-colors duration-200"
                        x-show="searchQuery === '' || '{{ strtolower($request->name . ' ' . $request->email) }}'.includes(searchQuery.toLowerCase())">
                        <!-- Name -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-quantum-500/20 to-void-500/20 flex items-center justify-center text-quantum-500 font-bold text-sm border border-quantum-500/30">
                                    {{ substr($request->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-white font-medium">{{ $request->name }}</p>
                                    <p class="text-gray-400 text-sm">ID: #{{ $request->id }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Contact -->
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                <p class="text-white text-sm flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $request->email }}
                                </p>
                                @if($request->phone)
                                <p class="text-gray-400 text-sm flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    {{ $request->phone }}
                                </p>
                                @endif
                            </div>
                        </td>

                        <!-- Reason -->
                        <td class="px-6 py-4">
                            <p class="text-gray-300 text-sm line-clamp-2 max-w-xs">{{ $request->reason }}</p>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4">
                            @if($request->status === 'pending')
                                <span class="badge-quantum badge-warning flex items-center gap-1 w-fit">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    Pendiente
                                </span>
                            @elseif($request->status === 'approved')
                                <span class="badge-quantum badge-success flex items-center gap-1 w-fit">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Aprobada
                                </span>
                            @else
                                <span class="badge-quantum badge-danger flex items-center gap-1 w-fit">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    Rechazada
                                </span>
                            @endif
                        </td>

                        <!-- Date -->
                        <td class="px-6 py-4">
                            <p class="text-gray-300 text-sm">{{ $request->created_at->format('d/m/Y') }}</p>
                            <p class="text-gray-500 text-xs">{{ $request->created_at->format('H:i') }}</p>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 text-right">
                            @if($request->status === 'pending')
                            <div class="flex items-center justify-end gap-2">
                                <!-- Approve -->
                                <button @click="currentRequest = {{ $request->id }}; approveModalOpen = true"
                                        class="p-2 rounded-lg bg-green-500/10 text-green-400 hover:bg-green-500/20 transition-colors duration-200 group"
                                        title="Aprobar">
                                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>

                                <!-- Reject -->
                                <button @click="currentRequest = {{ $request->id }}; rejectModalOpen = true"
                                        class="p-2 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 transition-colors duration-200 group"
                                        title="Rechazar">
                                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            @else
                            <p class="text-gray-500 text-sm">
                                @if($request->reviewed_at)
                                Revisada {{ $request->reviewed_at->diffForHumans() }}
                                @endif
                            </p>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center space-y-3">
                                <div class="w-16 h-16 bg-gradient-to-br from-quantum-500/20 to-void-500/20 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                </div>
                                <p class="text-gray-400 text-lg font-medium">No hay solicitudes</p>
                                <p class="text-gray-500 text-sm">Las nuevas solicitudes aparecerán aquí</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($requests->hasPages())
        <div class="border-t border-matter-light px-6 py-4">
            {{ $requests->links() }}
        </div>
        @endif
    </div>

    <!-- Approve Modal -->
    <div x-show="approveModalOpen"
         x-cloak
         @keydown.escape.window="approveModalOpen = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">

        <div @click.away="approveModalOpen = false"
             class="card-quantum max-w-md w-full p-6"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100">

            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-white mb-2">Aprobar Solicitud</h3>
                <p class="text-gray-400">Se creará un usuario y se enviarán credenciales por email</p>
            </div>

            <form id="approveRequestForm"
                  :action="`{{ url('access-requests') }}/${currentRequest}/approve`" 
                  method="POST" 
                  @submit.prevent="submitApprove"
                  class="space-y-4">
                @csrf
                @method('PUT')

                <div class="bg-green-500/10 border border-green-500/30 rounded-quantum p-4">
                    <p class="text-green-400 text-sm">
                        ✓ Usuario creado con rol "user"<br>
                        ✓ Contraseña temporal generada<br>
                        ✓ Email de bienvenida enviado
                    </p>
                </div>

                <div class="flex gap-3">
                    <button type="button"
                            @click="approveModalOpen = false"
                            :disabled="loading"
                            class="flex-1 px-4 py-3 rounded-quantum border border-matter-light text-gray-300 hover:bg-matter-light transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        Cancelar
                    </button>
                    <button type="submit"
                            :disabled="loading"
                            class="flex-1 btn-quantum bg-gradient-to-r from-green-500 to-emerald-600 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        <svg x-show="loading" class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span x-text="loading ? 'Procesando...' : 'Confirmar Aprobación'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reject Modal -->
    <div x-show="rejectModalOpen"
         x-cloak
         @keydown.escape.window="rejectModalOpen = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">

        <div @click.away="rejectModalOpen = false"
             class="card-quantum max-w-md w-full p-6"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100">

            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-br from-red-500/20 to-rose-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-white mb-2">Rechazar Solicitud</h3>
                <p class="text-gray-400">Indica el motivo del rechazo (opcional)</p>
            </div>

            <form id="rejectRequestForm"
                  :action="`{{ url('access-requests') }}/${currentRequest}/reject`" 
                  method="POST" 
                  @submit.prevent="submitReject"
                  class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Comentario</label>
                    <textarea name="admin_comment"
                              x-model="rejectComment"
                              rows="4"
                              placeholder="Motivo del rechazo (opcional)..."
                              :disabled="loading"
                              class="input-quantum resize-none disabled:opacity-50 disabled:cursor-not-allowed"></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button"
                            @click="rejectModalOpen = false; rejectComment = ''"
                            :disabled="loading"
                            class="flex-1 px-4 py-3 rounded-quantum border border-matter-light text-gray-300 hover:bg-matter-light transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        Cancelar
                    </button>
                    <button type="submit"
                            :disabled="loading"
                            class="flex-1 btn-quantum bg-gradient-to-r from-red-500 to-rose-600 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        <svg x-show="loading" class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span x-text="loading ? 'Procesando...' : 'Confirmar Rechazo'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Notification Toast -->
    <div x-show="notification.show"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         class="fixed top-4 right-4 z-50 max-w-md"
         @click="notification.show = false">
        <div :class="{
            'bg-green-500/10 border-green-500/30': notification.type === 'success',
            'bg-red-500/10 border-red-500/30': notification.type === 'error',
            'bg-yellow-500/10 border-yellow-500/30': notification.type === 'warning'
        }"
        class="card-quantum p-4 border cursor-pointer hover:scale-105 transition-transform">
            <div class="flex items-start gap-3">
                <svg x-show="notification.type === 'success'" class="w-6 h-6 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <svg x-show="notification.type === 'error'" class="w-6 h-6 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <svg x-show="notification.type === 'warning'" class="w-6 h-6 text-yellow-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div class="flex-1">
                    <p class="text-white font-medium text-sm mb-1" x-text="notification.message"></p>
                    <p x-show="notification.details" class="text-gray-400 text-xs" x-text="notification.details"></p>
                </div>
                <button @click="notification.show = false" class="text-gray-400 hover:text-gray-300 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    // Approve form handler
    window.submitApprove = function(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const rootElement = form.closest('[x-data]');
        
        // Get Alpine data
        let componentData = null;
        if (rootElement && rootElement._x_dataStack && rootElement._x_dataStack.length > 0) {
            componentData = rootElement._x_dataStack[0];
        }
        
        if (componentData) componentData.loading = true;
        
        fetch(form.action, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (componentData) {
                componentData.loading = false;
                componentData.approveModalOpen = false;
                
                if (data.success) {
                    componentData.showNotification('success', data.message, data.details || '');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    componentData.showNotification('error', data.error || data.message || 'Error al aprobar solicitud', data.details || '');
                }
            } else {
                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert(data.error || data.message || 'Error al aprobar solicitud');
                }
            }
        })
        .catch(error => {
            if (componentData) {
                componentData.loading = false;
                componentData.showNotification('error', 'Error al procesar la solicitud', error.message);
            } else {
                console.error('Error:', error);
                alert('Error al procesar la solicitud');
            }
        });
    };
    
    // Reject form handler
    window.submitReject = function(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const rootElement = form.closest('[x-data]');
        
        // Get Alpine data
        let componentData = null;
        if (rootElement && rootElement._x_dataStack && rootElement._x_dataStack.length > 0) {
            componentData = rootElement._x_dataStack[0];
        }
        
        if (componentData) componentData.loading = true;
        
        fetch(form.action, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (componentData) {
                componentData.loading = false;
                componentData.rejectModalOpen = false;
                componentData.rejectComment = '';
                
                if (data.success) {
                    componentData.showNotification('success', data.message || 'Solicitud rechazada exitosamente');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    componentData.showNotification('error', data.error || data.message || 'Error al rechazar solicitud', data.details || '');
                }
            } else {
                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert(data.error || data.message || 'Error al rechazar solicitud');
                }
            }
        })
        .catch(error => {
            if (componentData) {
                componentData.loading = false;
                componentData.showNotification('error', 'Error al procesar la solicitud', error.message);
            } else {
                console.error('Error:', error);
                alert('Error al procesar la solicitud');
            }
        });
    };
});
</script>
@endpush

<style>
[x-cloak] {
    display: none !important;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
