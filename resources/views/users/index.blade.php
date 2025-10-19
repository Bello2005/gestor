@extends('layouts.quantum')

@section('title', 'Gestión de Usuarios')

@section('content')
<div x-data="{
    createModalOpen: false,
    editModalOpen: false,
    resetModalOpen: false,
    temporalModalOpen: false,
    deleteModalOpen: false,
    currentUser: null,
    temporalPassword: '',
    searchQuery: '',
    selectedRole: 'all'
}" class="space-y-6 animate-fadeIn">

    <!-- Header Torre Eiffel -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-quantum-500 via-void-500 to-photon-500 bg-clip-text text-transparent">
                Gestión de Usuarios
            </h1>
            <p class="text-gray-400 mt-2">Administración elegante y precisa del equipo</p>
        </div>

        <!-- Create User Button -->
        <button @click="createModalOpen = true"
                class="btn-quantum flex items-center gap-2 group">
            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            <span>Nuevo Usuario</span>
        </button>
    </div>

    <!-- Stats Bar -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Users -->
        <div class="card-quantum p-6 group hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm uppercase tracking-wider mb-1">Total Usuarios</p>
                    <p class="text-3xl font-bold text-white">{{ $usuarios->total() }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-quantum-500/20 to-void-500/20 rounded-quantum flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-quantum-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Admins -->
        @php
            $admins = $usuarios->filter(fn($u) => $u->roles->where('slug', 'admin')->count() > 0)->count();
        @endphp
        <div class="card-quantum p-6 group hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm uppercase tracking-wider mb-1">Administradores</p>
                    <p class="text-3xl font-bold bg-gradient-to-r from-red-400 to-rose-500 bg-clip-text text-transparent">{{ $admins }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-red-500/20 to-rose-500/20 rounded-quantum flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Today -->
        @php
            $activeToday = $usuarios->filter(fn($u) => $u->updated_at >= now()->startOfDay())->count();
        @endphp
        <div class="card-quantum p-6 group hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm uppercase tracking-wider mb-1">Activos Hoy</p>
                    <p class="text-3xl font-bold bg-gradient-to-r from-green-400 to-emerald-500 bg-clip-text text-transparent">{{ $activeToday }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-quantum flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 18.364a9 9 0 010-12.728m12.728 0a9 9 0 010 12.728m-9.9-2.829a5 5 0 010-7.07m7.072 0a5 5 0 010 7.07M13 12a1 1 0 11-2 0 1 1 0 012 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Recent Resets -->
        @php
            $recentResets = $usuarios->filter(fn($u) => $u->last_password_reset && \Carbon\Carbon::parse($u->last_password_reset) >= now()->subDays(7))->count();
        @endphp
        <div class="card-quantum p-6 group hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm uppercase tracking-wider mb-1">Resets (7 días)</p>
                    <p class="text-3xl font-bold bg-gradient-to-r from-yellow-400 to-amber-500 bg-clip-text text-transparent">{{ $recentResets }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-yellow-500/20 to-amber-500/20 rounded-quantum flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card-quantum p-6">
        <div class="flex flex-col sm:flex-row gap-4">
            <!-- Search -->
            <div class="flex-1">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" x-model="searchQuery"
                           placeholder="Buscar por nombre o email..."
                           class="w-full pl-10 pr-4 py-3 bg-matter-light border border-matter-light rounded-quantum text-white placeholder-gray-500 focus:border-quantum-500 focus:ring-2 focus:ring-quantum-500/20 transition-all">
                </div>
            </div>

            <!-- Role Filter -->
            <select x-model="selectedRole"
                    class="px-4 py-3 bg-matter-light border border-matter-light rounded-quantum text-white focus:border-quantum-500 focus:ring-2 focus:ring-quantum-500/20 transition-all">
                <option value="all">Todos los roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->slug }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Users Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($usuarios as $index => $usuario)
            <div class="card-quantum overflow-hidden group hover:scale-105 hover:border-quantum-500/50 transition-all duration-300 animate-slideUp"
                 style="animation-delay: {{ $index * 50 }}ms;"
                 x-show="(searchQuery === '' || '{{ strtolower($usuario->name . ' ' . $usuario->email) }}'.includes(searchQuery.toLowerCase())) && (selectedRole === 'all' || {{ $usuario->roles->where('slug', 'ROLE_SLUG')->count() > 0 ? 'true' : 'false' }})"
                 x-transition>

                <!-- User Header -->
                <div class="p-6 bg-gradient-to-br from-quantum-500/5 to-void-500/5 border-b border-matter-light">
                    <div class="flex items-center gap-4">
                        <!-- Avatar -->
                        <div class="w-16 h-16 bg-gradient-to-br from-quantum-500 to-void-500 rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-quantum">
                            {{ strtoupper(substr($usuario->name, 0, 1)) }}
                        </div>

                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-bold text-white truncate">{{ $usuario->name }}</h3>
                            <p class="text-sm text-gray-400 truncate">{{ $usuario->email }}</p>
                        </div>
                    </div>

                    <!-- Roles -->
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach($usuario->roles as $role)
                            @if($role->slug === 'admin')
                                <span class="px-3 py-1 bg-gradient-to-r from-red-500/20 to-rose-500/20 border border-red-500/30 text-red-400 rounded-quantum text-xs font-semibold uppercase tracking-wider">
                                    {{ $role->name }}
                                </span>
                            @elseif($role->slug === 'director')
                                <span class="px-3 py-1 bg-gradient-to-r from-blue-500/20 to-cyan-500/20 border border-blue-500/30 text-blue-400 rounded-quantum text-xs font-semibold uppercase tracking-wider">
                                    {{ $role->name }}
                                </span>
                            @elseif($role->slug === 'coordinador')
                                <span class="px-3 py-1 bg-gradient-to-r from-green-500/20 to-emerald-500/20 border border-green-500/30 text-green-400 rounded-quantum text-xs font-semibold uppercase tracking-wider">
                                    {{ $role->name }}
                                </span>
                            @else
                                <span class="px-3 py-1 bg-gradient-to-r from-gray-500/20 to-slate-500/20 border border-gray-500/30 text-gray-400 rounded-quantum text-xs font-semibold uppercase tracking-wider">
                                    {{ $role->name }}
                                </span>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- User Info -->
                <div class="p-6 space-y-4">
                    <!-- Registration Date -->
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-quantum-500/20 to-void-500/20 rounded-quantum flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-quantum-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Registro</p>
                            <p class="text-sm font-semibold text-white">{{ $usuario->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <!-- Last Password Reset -->
                    @if($usuario->last_password_reset)
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-yellow-500/20 to-amber-500/20 rounded-quantum flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wider">Último Reset</p>
                                <p class="text-sm font-semibold text-white">{{ \Carbon\Carbon::parse($usuario->last_password_reset)->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="p-6 pt-0 flex gap-2">
                    <!-- Edit -->
                    <button @click="window.editUser({{ $usuario->id }})"
                            class="flex-1 px-4 py-2.5 bg-gradient-to-r from-blue-500/20 to-cyan-500/20 hover:from-blue-500/30 hover:to-cyan-500/30 border border-blue-500/30 text-blue-400 rounded-quantum transition-all duration-300 hover:scale-105 flex items-center justify-center gap-2 group">
                        <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar
                    </button>

                    <!-- Reset Password -->
                    <button @click="window.resetPassword({{ $usuario->id }})"
                            class="flex-1 px-4 py-2.5 bg-gradient-to-r from-yellow-500/20 to-amber-500/20 hover:from-yellow-500/30 hover:to-amber-500/30 border border-yellow-500/30 text-yellow-400 rounded-quantum transition-all duration-300 hover:scale-105 flex items-center justify-center gap-2 group">
                        <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                        Reset
                    </button>

                    <!-- Delete (if not current user) -->
                    @if($usuario->id !== auth()->id())
                        <button @click="window.deleteUser({{ $usuario->id }}, '{{ $usuario->name }}')"
                                class="px-4 py-2.5 bg-gradient-to-r from-red-500/20 to-rose-500/20 hover:from-red-500/30 hover:to-rose-500/30 border border-red-500/30 text-red-400 rounded-quantum transition-all duration-300 hover:scale-105 flex items-center justify-center group">
                            <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $usuarios->links() }}
    </div>

    <!-- Modals will be added with Alpine.js + AJAX -->
    @include('users.partials.create-modal', ['roles' => $roles])
    @include('users.partials.edit-modal', ['roles' => $roles])
    @include('users.partials.reset-modal')
    @include('users.partials.temporal-modal')
    @include('users.partials.delete-modal')
</div>

@push('scripts')
<script>
// Global functions for user management
window.editUser = function(userId) {
    // We'll implement this with fetch API
    fetch(`/users/${userId}`)
        .then(response => response.json())
        .then(data => {
            // Populate edit form
            document.getElementById('editUserId').value = data.id;
            document.getElementById('editName').value = data.name;
            document.getElementById('editEmail').value = data.email;

            // Clear all role checkboxes first
            document.querySelectorAll('.edit-role').forEach(cb => cb.checked = false);

            // Check user's current roles
            data.roles.forEach(role => {
                const checkbox = document.getElementById(`editRole${role.id}`);
                if (checkbox) checkbox.checked = true;
            });

            // Open modal
            Alpine.store('editModalOpen', true);
            document.querySelector('[x-data]').__x.$data.editModalOpen = true;
        })
        .catch(error => {
            console.error('Error:', error);
            window.showToast('Error al cargar datos del usuario', 'error');
        });
};

window.resetPassword = function(userId) {
    document.getElementById('resetUserId').value = userId;
    document.querySelector('[x-data]').__x.$data.resetModalOpen = true;
};

window.deleteUser = function(userId, userName) {
    document.getElementById('deleteUserId').value = userId;
    document.getElementById('deleteUserName').textContent = userName;
    document.querySelector('[x-data]').__x.$data.deleteModalOpen = true;
};
</script>
@endpush
@endsection
