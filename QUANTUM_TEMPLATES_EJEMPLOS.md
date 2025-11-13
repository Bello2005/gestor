# QUANTUM Design System - Templates y Ejemplos Listos

## TEMPLATE 1: Vista Auditoría (Quantum Style)

```blade
@extends('layouts.quantum')

@section('page-title', 'Auditoría del Sistema')

@section('content')

<!-- Header Section -->
<div class="mb-8">
    <div class="flex items-center justify-between flex-wrap gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2 flex items-center gap-3">
                <div class="w-1 h-8 bg-gradient-to-b from-quantum-500 to-void-500 rounded-full"></div>
                Registros de Auditoría
            </h1>
            <p class="text-gray-400">Monitoreo de actividades y cambios del sistema</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Events -->
        <div class="card-quantum p-6 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-quantum-500/10 rounded-full blur-3xl group-hover:bg-quantum-500/20 transition-all duration-500"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-quantum bg-quantum-500/20 border border-quantum-500/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-quantum-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total</span>
                    </div>
                    <span class="text-xs font-semibold text-green-400">+5%</span>
                </div>
                <div class="mb-2">
                    <span class="text-4xl font-bold text-white">{{ $totalEvents ?? 0 }}</span>
                </div>
                <p class="text-sm text-gray-400">Eventos registrados</p>
            </div>
        </div>

        <!-- Last 24h -->
        <div class="card-quantum p-6 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-void-500/10 rounded-full blur-3xl group-hover:bg-void-500/20 transition-all duration-500"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-quantum bg-void-500/20 border border-void-500/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-void-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Últimas 24h</span>
                    </div>
                </div>
                <div class="mb-2">
                    <span class="text-4xl font-bold text-white">{{ $events24h ?? 0 }}</span>
                </div>
                <p class="text-sm text-gray-400">Eventos hoy</p>
            </div>
        </div>

        <!-- Creates -->
        <div class="card-quantum p-6 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-green-500/10 rounded-full blur-3xl group-hover:bg-green-500/20 transition-all duration-500"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-quantum bg-green-500/20 border border-green-500/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">INSERT</span>
                    </div>
                </div>
                <div class="mb-2">
                    <span class="text-4xl font-bold text-white">{{ $inserts ?? 0 }}</span>
                </div>
                <p class="text-sm text-gray-400">Registros creados</p>
            </div>
        </div>

        <!-- Updates/Deletes -->
        <div class="card-quantum p-6 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-photon-500/10 rounded-full blur-3xl group-hover:bg-photon-500/20 transition-all duration-500"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-quantum bg-photon-500/20 border border-photon-500/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-photon-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">UPDATES</span>
                    </div>
                </div>
                <div class="mb-2">
                    <span class="text-4xl font-bold text-white">{{ $updates + $deletes ?? 0 }}</span>
                </div>
                <p class="text-sm text-gray-400">Cambios registrados</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters & Search -->
<div class="card-quantum p-6 mb-8">
    <div class="flex items-center justify-between flex-wrap gap-4 mb-6">
        <div class="flex items-center gap-3">
            <div class="w-1 h-6 bg-gradient-to-b from-quantum-500 to-void-500 rounded-full"></div>
            <h2 class="text-xl font-semibold text-white">Filtros</h2>
        </div>
    </div>

    <!-- Filters Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Date Range -->
        <div x-data="{ range: 'all' }">
            <label class="block text-sm font-medium text-gray-300 mb-2">Rango de Fecha</label>
            <select class="select-quantum text-sm">
                <option value="all">Todos</option>
                <option value="24h">Últimas 24 horas</option>
                <option value="7d">Últimos 7 días</option>
                <option value="30d">Últimos 30 días</option>
            </select>
        </div>

        <!-- Operation Type -->
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Tipo de Operación</label>
            <select class="select-quantum text-sm">
                <option value="">Todas</option>
                <option value="INSERT">Crear (INSERT)</option>
                <option value="UPDATE">Actualizar (UPDATE)</option>
                <option value="DELETE">Eliminar (DELETE)</option>
            </select>
        </div>

        <!-- Table -->
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Tabla</label>
            <select class="select-quantum text-sm">
                <option value="">Todas las tablas</option>
                <option value="proyectos">Proyectos</option>
                <option value="usuarios">Usuarios</option>
                <option value="usuarios_roles">Roles</option>
            </select>
        </div>

        <!-- Search -->
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Buscar</label>
            <input type="text" placeholder="ID, usuario, IP..." class="input-quantum text-sm" />
        </div>
    </div>
</div>

<!-- Audit Table -->
<div class="card-quantum overflow-hidden">
    <div class="overflow-x-auto">
        <table class="table-quantum">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tabla</th>
                    <th>Operación</th>
                    <th>Usuario</th>
                    <th>IP Address</th>
                    <th>Timestamp</th>
                    <th>Detalles</th>
                </tr>
            </thead>
            <tbody>
                @forelse($auditLogs ?? [] as $log)
                <tr class="hover:bg-matter-light/50 transition-colors">
                    <td>
                        <span class="font-mono text-xs text-gray-400">{{ $log->id }}</span>
                    </td>
                    <td>
                        <span class="font-medium text-gray-300">{{ ucfirst($log->table_name) }}</span>
                    </td>
                    <td>
                        @if($log->operation === 'INSERT')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-300 border border-green-500/30">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                INSERT
                            </span>
                        @elseif($log->operation === 'UPDATE')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-photon-500/20 text-photon-300 border border-photon-500/30">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                UPDATE
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-red-500/20 text-red-300 border border-red-500/30">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                DELETE
                            </span>
                        @endif
                    </td>
                    <td>
                        <span class="text-gray-300">{{ $log->user_name ?? 'Sistema' }}</span>
                    </td>
                    <td>
                        <span class="font-mono text-xs text-gray-400">{{ $log->ip_address }}</span>
                    </td>
                    <td>
                        <span class="text-sm text-gray-400">{{ $log->created_at->format('d/m/Y H:i') }}</span>
                    </td>
                    <td>
                        <button type="button" 
                                class="px-3 py-1 rounded-quantum bg-quantum-500/20 hover:bg-quantum-500/30 border border-quantum-500/30 hover:border-quantum-500 text-quantum-300 hover:text-quantum-200 text-xs font-medium transition-all duration-200">
                            Ver
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-12">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 rounded-full bg-matter-light flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <p class="text-gray-400 font-medium">No hay registros de auditoría</p>
                            <p class="text-gray-500 text-sm mt-1">Aún no se han registrado cambios en el sistema</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination if needed -->
@if(isset($auditLogs) && $auditLogs->hasPages())
<div class="mt-8 flex justify-center">
    {{ $auditLogs->links('pagination::tailwind') }}
</div>
@endif

@endsection
```

---

## TEMPLATE 2: Vista Usuarios (Quantum Style)

```blade
@extends('layouts.quantum')

@section('page-title', 'Gestión de Usuarios')

@section('content')

<!-- Header Section -->
<div class="mb-8">
    <div class="flex items-center justify-between flex-wrap gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2 flex items-center gap-3">
                <div class="w-1 h-8 bg-gradient-to-b from-void-500 to-quantum-500 rounded-full"></div>
                Gestión de Usuarios
            </h1>
            <p class="text-gray-400">Control y administración del equipo</p>
        </div>
        <a href="#" class="btn-quantum">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            Nuevo Usuario
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="card-quantum p-6 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-quantum-500/10 rounded-full blur-3xl group-hover:bg-quantum-500/20 transition-all duration-500"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-quantum bg-quantum-500/20 border border-quantum-500/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-quantum-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total</span>
                    </div>
                </div>
                <div class="mb-2">
                    <span class="text-4xl font-bold text-white">{{ $totalUsers ?? 0 }}</span>
                </div>
                <p class="text-sm text-gray-400">Usuarios totales</p>
            </div>
        </div>

        <!-- Active Today -->
        <div class="card-quantum p-6 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-green-500/10 rounded-full blur-3xl group-hover:bg-green-500/20 transition-all duration-500"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-quantum bg-green-500/20 border border-green-500/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Activos Hoy</span>
                    </div>
                </div>
                <div class="mb-2">
                    <span class="text-4xl font-bold text-white">{{ $activeToday ?? 0 }}</span>
                </div>
                <p class="text-sm text-gray-400">En el sistema hoy</p>
            </div>
        </div>

        <!-- Admins -->
        <div class="card-quantum p-6 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-photon-500/10 rounded-full blur-3xl group-hover:bg-photon-500/20 transition-all duration-500"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-quantum bg-photon-500/20 border border-photon-500/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-photon-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Administradores</span>
                    </div>
                </div>
                <div class="mb-2">
                    <span class="text-4xl font-bold text-white">{{ $adminsCount ?? 0 }}</span>
                </div>
                <p class="text-sm text-gray-400">Usuarios admin</p>
            </div>
        </div>

        <!-- Pending Password -->
        <div class="card-quantum p-6 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-void-500/10 rounded-full blur-3xl group-hover:bg-void-500/20 transition-all duration-500"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-quantum bg-void-500/20 border border-void-500/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-void-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Contraseña Pendiente</span>
                    </div>
                </div>
                <div class="mb-2">
                    <span class="text-4xl font-bold text-white">{{ $pendingPassword ?? 0 }}</span>
                </div>
                <p class="text-sm text-gray-400">Cambios requeridos</p>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filters -->
<div class="card-quantum p-6 mb-8">
    <div class="flex items-center justify-between flex-wrap gap-4 mb-4">
        <div class="flex items-center gap-3">
            <div class="w-1 h-6 bg-gradient-to-b from-quantum-500 to-void-500 rounded-full"></div>
            <h2 class="text-xl font-semibold text-white">Búsqueda y Filtros</h2>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Buscar Usuario</label>
            <input type="text" id="searchUsers" placeholder="Nombre, email..." class="input-quantum" />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Rol</label>
            <select class="select-quantum">
                <option value="">Todos los roles</option>
                <option value="admin">Administrador</option>
                <option value="director">Director</option>
                <option value="coordinador">Coordinador</option>
                <option value="empleado">Empleado</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Estado</label>
            <select class="select-quantum">
                <option value="">Todos</option>
                <option value="active">Activos</option>
                <option value="inactive">Inactivos</option>
            </select>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="card-quantum overflow-hidden">
    <div class="overflow-x-auto">
        <table class="table-quantum">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Registrado</th>
                    <th>Último Acceso</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users ?? [] as $user)
                <tr class="hover:bg-matter-light/50 transition-colors">
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-quantum-500 to-void-500 flex items-center justify-center text-white text-xs font-bold">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <span class="font-medium text-white">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="text-gray-400 text-sm">{{ $user->email }}</span>
                    </td>
                    <td>
                        @foreach($user->roles as $role)
                        <span class="inline-block px-2.5 py-1 rounded-full text-xs font-medium bg-quantum-500/20 text-quantum-300 border border-quantum-500/30 mr-1 mb-1">
                            {{ $role->name }}
                        </span>
                        @endforeach
                    </td>
                    <td>
                        <span class="text-gray-400 text-sm">{{ $user->created_at->format('d/m/Y') }}</span>
                    </td>
                    <td>
                        <span class="text-gray-400 text-sm">
                            {{ $user->last_login ? $user->last_login->format('d/m H:i') : 'Nunca' }}
                        </span>
                    </td>
                    <td>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-300 border border-green-500/30">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Activo
                        </span>
                    </td>
                    <td>
                        <div class="flex items-center gap-2">
                            <a href="#" class="p-2 rounded-quantum bg-quantum-500/20 hover:bg-quantum-500/30 border border-quantum-500/30 hover:border-quantum-500 text-quantum-300 hover:text-quantum-200 transition-all duration-200" title="Editar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <a href="#" class="p-2 rounded-quantum bg-void-500/20 hover:bg-void-500/30 border border-void-500/30 hover:border-void-500 text-void-300 hover:text-void-200 transition-all duration-200" title="Resetear contraseña">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                            </a>
                            @if($user->id !== auth()->id())
                            <button type="button" class="p-2 rounded-quantum bg-red-500/20 hover:bg-red-500/30 border border-red-500/30 hover:border-red-500 text-red-300 hover:text-red-200 transition-all duration-200" title="Eliminar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-12">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 rounded-full bg-matter-light flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <p class="text-gray-400 font-medium">No hay usuarios</p>
                            <p class="text-gray-500 text-sm mt-1">Comienza creando tu primer usuario</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
```

---

## TEMPLATE 3: Modal de Confirmación (Reutilizable)

```blade
<!-- Delete Confirmation Modal -->
<div x-data="{ open: false, itemId: null, itemName: '' }"
     @delete-item.window="open = true; itemId = $event.detail.id; itemName = $event.detail.name"
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-white">Confirmar Eliminación</h3>
                    <p class="text-sm text-gray-400">Esta acción no se puede deshacer</p>
                </div>
            </div>

            <p class="text-gray-300 mb-6">
                ¿Estás seguro de que deseas eliminar "<span x-text="itemName" class="font-semibold text-red-300"></span>"? Todos los datos asociados se perderán permanentemente.
            </p>

            <div class="flex gap-3">
                <button @click="open = false"
                        class="flex-1 px-4 py-2.5 bg-matter-light hover:bg-matter text-gray-300 hover:text-white rounded-quantum font-medium transition-all duration-200">
                    Cancelar
                </button>
                <form :action="'/api/delete/' + itemId" method="POST" class="flex-1">
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

<!-- Trigger Button Example -->
<button type="button"
        @click="$dispatch('delete-item', { id: {{ $item->id }}, name: '{{ $item->name }}' })"
        class="px-3 py-2 rounded-quantum bg-red-500/20 hover:bg-red-500/30 border border-red-500/30 hover:border-red-500 text-red-300 hover:text-red-200 rounded-quantum text-sm font-medium transition-all duration-200">
    Eliminar
</button>
```

