@extends('layouts.quantum')

@section('title', 'Gestión de Usuarios')

@section('content')
<div x-data="{
    createModalOpen: false,
    editModalOpen: false,
    resetModalOpen: false,
    temporalModalOpen: false,
    deleteModalOpen: false,
    passwordDisplayModalOpen: false,
    currentUser: null,
    temporalPassword: '',
    searchQuery: '',
    selectedRole: 'all',
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
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 md:gap-6">
        @foreach($usuarios as $index => $usuario)
            @php
                $userRoles = $usuario->roles->pluck('slug')->toArray();
                $hasRole = json_encode($userRoles);
            @endphp
            <div class="card-quantum overflow-hidden group hover:scale-105 hover:border-quantum-500/50 transition-all duration-300 animate-slideUp"
                 style="animation-delay: {{ $index * 50 }}ms;"
                 x-show="(searchQuery === '' || '{{ strtolower($usuario->name . ' ' . $usuario->email) }}'.includes(searchQuery.toLowerCase())) && (selectedRole === 'all' || {{ json_encode($userRoles) }}.includes(selectedRole))"
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
                    <button @click="currentUser = {{ $usuario->id }}; editModalOpen = true"
                            class="flex-1 px-4 py-2.5 bg-gradient-to-r from-blue-500/20 to-cyan-500/20 hover:from-blue-500/30 hover:to-cyan-500/30 border border-blue-500/30 text-blue-400 rounded-quantum transition-all duration-300 hover:scale-105 flex items-center justify-center gap-2 group">
                        <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar
                    </button>

                    <!-- Reset Password -->
                    <button @click="currentUser = {{ $usuario->id }}; resetModalOpen = true"
                            class="flex-1 px-4 py-2.5 bg-gradient-to-r from-yellow-500/20 to-amber-500/20 hover:from-yellow-500/30 hover:to-amber-500/30 border border-yellow-500/30 text-yellow-400 rounded-quantum transition-all duration-300 hover:scale-105 flex items-center justify-center gap-2 group">
                        <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                        Reset
                    </button>

                    <!-- Delete (if not current user) -->
                    @if($usuario->id !== auth()->id())
                        <button @click="currentUser = {{ $usuario->id }}; deleteModalOpen = true"
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

    <!-- Create User Modal -->
    <div x-show="createModalOpen"
         x-cloak
         @keydown.escape.window="createModalOpen = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">

        <div @click.away="createModalOpen = false"
             class="card-quantum max-w-md w-full p-6"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">

            <!-- Header -->
            <div class="flex items-center gap-3 mb-5 pb-4 border-b border-matter-light">
                <div class="w-11 h-11 bg-gradient-to-br from-quantum-500/20 to-void-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-quantum-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-bold text-white mb-0.5">Nuevo Usuario</h3>
                    <p class="text-xs text-gray-400">Crea un nuevo usuario en el sistema</p>
                </div>
            </div>

            <form id="createUserForm" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Nombre Completo</label>
                    <input type="text" name="name" required
                           class="input-quantum w-full py-3 text-sm"
                           placeholder="Juan Pérez">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Email</label>
                    <input type="email" name="email" required
                           class="input-quantum w-full py-3 text-sm"
                           placeholder="juan@example.com">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Contraseña</label>
                    <input type="password" name="password" required minlength="8"
                           class="input-quantum w-full py-3 text-sm"
                           placeholder="Mínimo 8 caracteres">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-2.5 uppercase tracking-wider">Roles</label>
                    <div class="space-y-2">
                        @foreach($roles as $role)
                        <label class="flex items-center gap-3 p-2.5 rounded-lg bg-matter-light hover:bg-matter-light/80 cursor-pointer transition-colors group">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                   class="w-4 h-4 rounded border-matter-light bg-matter text-quantum-500 focus:ring-quantum-500 focus:ring-2">
                            <span class="text-sm text-white flex-1 font-medium group-hover:text-quantum-400 transition-colors">{{ $role->name }}</span>
                            <span class="text-[10px] text-gray-500 uppercase font-mono px-2 py-0.5 bg-space-500 rounded">{{ $role->slug }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex gap-3 pt-4 border-t border-matter-light">
                    <button type="button"
                            @click="createModalOpen = false"
                            class="flex-1 px-4 py-3 rounded-quantum border border-matter-light text-gray-300 hover:bg-matter-light transition-colors duration-200 text-sm font-medium">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="flex-1 btn-quantum text-sm font-medium py-3">
                        Crear Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div x-show="editModalOpen"
         x-cloak
         @keydown.escape.window="editModalOpen = false"
         x-init="$watch('currentUser', () => { if (currentUser) { setTimeout(() => loadUserData(currentUser), 100); } })"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">

        <div @click.away="editModalOpen = false"
             class="card-quantum max-w-md w-full p-6"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">

            <!-- Header -->
            <div class="flex items-center gap-3 mb-5 pb-4 border-b border-matter-light">
                <div class="w-11 h-11 bg-gradient-to-br from-blue-500/20 to-cyan-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-bold text-white mb-0.5">Editar Usuario</h3>
                    <p class="text-xs text-gray-400">Actualiza la información del usuario</p>
                </div>
            </div>

            <form :id="`editUserForm${currentUser}`" :action="`/users/${currentUser}`" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" :id="`editUserId${currentUser}`" name="user_id" :value="currentUser">

                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Nombre Completo</label>
                    <input type="text" :id="`editName${currentUser}`" name="name" required
                           class="input-quantum w-full py-3 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Email</label>
                    <input type="email" :id="`editEmail${currentUser}`" name="email" required
                           class="input-quantum w-full py-3 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">
                        Contraseña 
                        <span class="text-gray-500 font-normal normal-case">(opcional)</span>
                    </label>
                    <input type="password" name="password" minlength="8"
                           class="input-quantum w-full py-3 text-sm"
                           placeholder="Dejar vacío para no cambiar">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-2.5 uppercase tracking-wider">Roles</label>
                    <div class="space-y-2">
                        @foreach($roles as $role)
                        <label class="flex items-center gap-3 p-2.5 rounded-lg bg-matter-light hover:bg-matter-light/80 cursor-pointer transition-colors group">
                            <input type="checkbox" :id="`editRole${currentUser}_${role->id}`" name="roles[]" value="{{ $role->id }}"
                                   class="edit-role w-4 h-4 rounded border-matter-light bg-matter text-quantum-500 focus:ring-quantum-500 focus:ring-2">
                            <span class="text-sm text-white flex-1 font-medium group-hover:text-quantum-400 transition-colors">{{ $role->name }}</span>
                            <span class="text-[10px] text-gray-500 uppercase font-mono px-2 py-0.5 bg-space-500 rounded">{{ $role->slug }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex gap-3 pt-4 border-t border-matter-light">
                    <button type="button"
                            @click="editModalOpen = false"
                            class="flex-1 px-4 py-3 rounded-quantum border border-matter-light text-gray-300 hover:bg-matter-light transition-colors duration-200 text-sm font-medium">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="flex-1 btn-quantum bg-gradient-to-r from-blue-500 to-cyan-600 text-sm font-medium py-3">
                        Actualizar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reset Password Modal -->
    <div x-show="resetModalOpen"
         x-cloak
         @keydown.escape.window="resetModalOpen = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">

        <div @click.away="resetModalOpen = false"
             class="card-quantum max-w-md w-full p-6"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">

            <!-- Header -->
            <div class="flex items-center gap-4 mb-6 pb-5 border-b border-matter-light">
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500/20 to-amber-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-xl font-bold text-white mb-1">Resetear Contraseña</h3>
                    <p class="text-sm text-gray-400">Selecciona el método de restablecimiento</p>
                </div>
            </div>

            <form :id="`resetPasswordForm${currentUser}`" :action="`/users/${currentUser}/reset-password`" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="reset_type" value="email" id="resetType">
                <input type="hidden" name="force_change" value="1">
                <input type="hidden" name="invalidate_sessions" value="1">

                <div class="space-y-3">
                    <label class="flex items-center gap-3 p-4 rounded-lg bg-matter-light cursor-pointer hover:bg-matter-light/80 transition-colors border border-transparent hover:border-yellow-500/30" 
                           @click="document.getElementById('resetType').value = 'email'">
                        <input type="radio" name="reset_method" value="email" checked class="w-4 h-4 text-quantum-500">
                        <div class="flex-1">
                            <p class="text-sm text-white font-medium">Enviar por Email</p>
                            <p class="text-xs text-gray-400">Se enviará un enlace al correo del usuario</p>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-4 rounded-lg bg-matter-light cursor-pointer hover:bg-matter-light/80 transition-colors border border-transparent hover:border-yellow-500/30"
                           @click="document.getElementById('resetType').value = 'temporal'; temporalModalOpen = true; resetModalOpen = false">
                        <input type="radio" name="reset_method" value="temporal" class="w-4 h-4 text-quantum-500">
                        <div class="flex-1">
                            <p class="text-sm text-white font-medium">Contraseña Temporal</p>
                            <p class="text-xs text-gray-400">Generar una contraseña temporal que se mostrará aquí</p>
                        </div>
                    </label>
                </div>

                <div class="flex gap-3 pt-4 border-t border-matter-light">
                    <button type="button"
                            @click="resetModalOpen = false"
                            class="flex-1 px-4 py-3 rounded-quantum border border-matter-light text-gray-300 hover:bg-matter-light transition-colors duration-200 font-medium">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="flex-1 btn-quantum bg-gradient-to-r from-yellow-500 to-amber-600 font-medium">
                        Enviar Reset
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Temporal Password Modal -->
    <div x-show="temporalModalOpen"
         x-cloak
         @keydown.escape.window="temporalModalOpen = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">

        <div @click.away="temporalModalOpen = false"
             class="card-quantum max-w-md w-full p-6"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">

            <!-- Header -->
            <div class="flex items-center gap-4 mb-6 pb-5 border-b border-matter-light">
                <div class="w-12 h-12 bg-gradient-to-br from-amber-500/20 to-yellow-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-xl font-bold text-white mb-1">Contraseña Temporal</h3>
                    <p class="text-sm text-gray-400">Generar contraseña temporal</p>
                </div>
            </div>

            <form :id="`temporalPasswordForm${currentUser}`" :action="`/users/${currentUser}/reset-password`" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="reset_type" value="temporal">
                <input type="hidden" name="force_change" value="1">
                <input type="hidden" name="invalidate_sessions" value="1">

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Motivo <span class="text-gray-500 font-normal">(opcional)</span></label>
                    <textarea name="motivo" rows="3"
                              class="input-quantum w-full resize-none"
                              placeholder="Motivo del reset de contraseña..."></textarea>
                </div>

                <div class="bg-amber-500/10 border border-amber-500/30 rounded-lg p-4">
                    <p class="text-amber-400 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        La contraseña temporal se mostrará después de crear. Asegúrate de copiarla antes de cerrar.
                    </p>
                </div>

                <div class="flex gap-3 pt-4 border-t border-matter-light">
                    <button type="button"
                            @click="temporalModalOpen = false"
                            class="flex-1 px-4 py-3 rounded-quantum border border-matter-light text-gray-300 hover:bg-matter-light transition-colors duration-200 font-medium">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="flex-1 btn-quantum bg-gradient-to-r from-amber-500 to-yellow-600 font-medium">
                        Generar Contraseña
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete User Modal -->
    <div x-show="deleteModalOpen"
         x-cloak
         @keydown.escape.window="deleteModalOpen = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">

        <div @click.away="deleteModalOpen = false"
             class="card-quantum max-w-md w-full p-6"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">

            <!-- Header -->
            <div class="flex items-center gap-4 mb-6 pb-5 border-b border-matter-light">
                <div class="w-12 h-12 bg-gradient-to-br from-red-500/20 to-rose-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-xl font-bold text-white mb-1">Eliminar Usuario</h3>
                    <p class="text-sm text-gray-400">Esta acción no se puede deshacer</p>
                </div>
            </div>

            <form :id="`deleteUserForm${currentUser}`" :action="`/users/${currentUser}`" method="POST" class="space-y-5">
                @csrf
                @method('DELETE')

                <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-4">
                    <p class="text-red-400 text-sm flex items-start gap-2">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span>El usuario será eliminado permanentemente junto con todos sus datos asociados.</span>
                    </p>
                </div>

                <div class="flex gap-3 pt-4 border-t border-matter-light">
                    <button type="button"
                            @click="deleteModalOpen = false"
                            class="flex-1 px-4 py-3 rounded-quantum border border-matter-light text-gray-300 hover:bg-matter-light transition-colors duration-200 font-medium">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="flex-1 btn-quantum bg-gradient-to-r from-red-500 to-rose-600 font-medium">
                        Eliminar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Password Display Modal -->
    <div x-show="passwordDisplayModalOpen"
         x-cloak
         @keydown.escape.window="passwordDisplayModalOpen = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">

        <div @click.away="passwordDisplayModalOpen = false"
             class="card-quantum max-w-md w-full p-8"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">

            <div class="text-center mb-6">
                <div class="w-20 h-20 bg-gradient-to-br from-amber-500/20 to-yellow-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-white mb-2">Contraseña Temporal Generada</h3>
                <p class="text-gray-400 text-sm">Copia esta contraseña y compártela de forma segura con el usuario</p>
            </div>

            <div class="space-y-4 mb-6">
                <div class="bg-amber-500/10 border-2 border-amber-500/30 rounded-quantum p-4">
                    <label class="block text-xs font-medium text-amber-400 mb-2 uppercase tracking-wider">Contraseña Temporal</label>
                    <div class="flex items-center gap-3">
                        <input type="text"
                               :value="temporalPassword"
                               readonly
                               id="temporalPasswordDisplay"
                               class="flex-1 input-quantum bg-matter-light text-white text-lg font-mono text-center tracking-widest font-bold"
                               onclick="this.select(); document.execCommand('copy');">
                        <button @click="copyPassword"
                                class="px-4 py-3 bg-amber-500/20 hover:bg-amber-500/30 border border-amber-500/30 text-amber-400 rounded-quantum transition-all duration-200 hover:scale-105">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-quantum p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-yellow-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div>
                            <p class="text-yellow-400 text-sm font-medium mb-1">¡Importante!</p>
                            <p class="text-yellow-300 text-xs">Esta contraseña solo se mostrará una vez. Asegúrate de copiarla y compartirla de forma segura con el usuario.</p>
                        </div>
                    </div>
                </div>
            </div>

            <button @click="passwordDisplayModalOpen = false; temporalPassword = ''"
                    class="w-full btn-quantum bg-gradient-to-r from-amber-500 to-yellow-600 font-medium">
                Entendido
            </button>
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
// Copy password function
window.copyPassword = function() {
    const input = document.getElementById('temporalPasswordDisplay');
    if (input) {
        input.select();
        input.setSelectionRange(0, 99999); // For mobile devices
        document.execCommand('copy');
        
        // Show feedback
        const rootElement = document.querySelector('[x-data]');
        if (rootElement && rootElement._x_dataStack && rootElement._x_dataStack.length > 0) {
            const componentData = rootElement._x_dataStack[0];
            if (componentData) {
                componentData.showNotification('success', 'Contraseña copiada al portapapeles');
            }
        }
    }
};

// Make loadUserData available globally for Alpine.js
window.loadUserData = function(userId) {
    return fetch(`/users/${userId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al cargar usuario');
            }
            return response.json();
        })
        .then(data => {
            // Wait for modal to be fully rendered
            setTimeout(() => {
            // Populate edit form
                const nameInput = document.getElementById(`editName${userId}`);
                const emailInput = document.getElementById(`editEmail${userId}`);
                
                if (nameInput && data.name) nameInput.value = data.name;
                if (emailInput && data.email) emailInput.value = data.email;

            // Clear all role checkboxes first
                document.querySelectorAll(`.edit-role`).forEach(cb => cb.checked = false);

            // Check user's current roles
                if (data.roles && Array.isArray(data.roles)) {
            data.roles.forEach(role => {
                        const checkbox = document.getElementById(`editRole${userId}_${role.id}`);
                if (checkbox) checkbox.checked = true;
            });
                }
            }, 150);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar datos del usuario');
        });
};

// Handle form submissions with success/error messages
document.addEventListener('DOMContentLoaded', function() {
    // Create user form
    const createForm = document.getElementById('createUserForm');
    if (createForm) {
        createForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('/users', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert(data.message);
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al crear usuario');
            });
        });
    }

    // Edit user forms (dynamically created)
    document.addEventListener('submit', function(e) {
        if (e.target.id && e.target.id.startsWith('editUserForm')) {
            e.preventDefault();
            const formData = new FormData(e.target);
            fetch(e.target.action, {
                method: 'PUT',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert(data.message);
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al actualizar usuario');
            });
        }

        // Reset password forms
        if (e.target.id && e.target.id.startsWith('resetPasswordForm')) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const rootElement = form.closest('[x-data]');
            let componentData = null;
            if (rootElement && rootElement._x_dataStack && rootElement._x_dataStack.length > 0) {
                componentData = rootElement._x_dataStack[0];
            }
            
            if (componentData) componentData.loading = true;
            
            fetch(form.action, {
                method: 'POST',
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
                    componentData.resetModalOpen = false;
                    
                    if (data.success) {
                        componentData.showNotification('success', data.message);
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        componentData.showNotification('error', data.message || 'Error al resetear contraseña', data.details || '');
                    }
                } else {
                    if (data.success) {
                        alert(data.message);
                        window.location.reload();
                    } else {
                        alert(data.message || 'Error al resetear contraseña');
                    }
                }
            })
            .catch(error => {
                if (componentData) {
                    componentData.loading = false;
                    componentData.showNotification('error', 'Error al resetear contraseña', error.message);
                } else {
                    console.error('Error:', error);
                    alert('Error al resetear contraseña');
                }
            });
        }

        // Temporal password forms
        if (e.target.id && e.target.id.startsWith('temporalPasswordForm')) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const rootElement = form.closest('[x-data]');
            let componentData = null;
            if (rootElement && rootElement._x_dataStack && rootElement._x_dataStack.length > 0) {
                componentData = rootElement._x_dataStack[0];
            }
            
            if (componentData) componentData.loading = true;
            
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (componentData) componentData.loading = false;
                
                if (data.success) {
                    // Extraer contraseña temporal del mensaje
                    const passwordMatch = data.message.match(/temporal: ([^\s.]+)/i) || 
                                       data.message.match(/Contraseña temporal: ([^\s.]+)/i) ||
                                       data.message.match(/password: ([^\s.]+)/i);
                    
                    if (passwordMatch && componentData) {
                        componentData.temporalPassword = passwordMatch[1];
                        componentData.temporalModalOpen = false;
                        componentData.passwordDisplayModalOpen = true;
                    } else {
                        if (componentData) {
                            componentData.showNotification('success', data.message);
                            setTimeout(() => window.location.reload(), 1500);
                        } else {
                            alert(data.message);
                            window.location.reload();
                        }
                    }
                } else {
                    if (componentData) {
                        componentData.showNotification('error', data.message || 'Error al generar contraseña', data.details || '');
                    } else {
                        alert(data.message || 'Error al generar contraseña');
                    }
                }
            })
            .catch(error => {
                if (componentData) {
                    componentData.loading = false;
                    componentData.showNotification('error', 'Error al generar contraseña temporal', error.message);
                } else {
                    console.error('Error:', error);
                    alert('Error al generar contraseña temporal');
                }
            });
        }

        // Delete user forms
        if (e.target.id && e.target.id.startsWith('deleteUserForm')) {
            e.preventDefault();
            if (!confirm('¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.')) {
                return;
            }
            const formData = new FormData(e.target);
            fetch(e.target.action, {
                method: 'DELETE',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert(data.message);
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al eliminar usuario');
            });
        }
    });
});
</script>
@endpush

<style>
[x-cloak] {
    display: none !important;
}
</style>
@endsection
