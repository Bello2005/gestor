<!-- Quantum Sidebar -->
<div
    x-cloak
    x-show="mobileSidebarOpen"
    @click="mobileSidebarOpen = false"
    class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm md:hidden"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
></div>

<aside
    class="fixed top-0 left-0 z-50 h-full transition-all duration-300 ease-in-out bg-matter border-r border-matter-light"
    :class="{
        'w-64': sidebarOpen,
        'w-20': !sidebarOpen,
        'translate-x-0': mobileSidebarOpen,
        '-translate-x-full md:translate-x-0': !mobileSidebarOpen
    }"
>
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between h-20 px-6 border-b border-matter-light">
        <div class="flex items-center gap-3 transition-all duration-300 flex-1 min-w-0" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">
            <!-- Quantum Logo -->
            <div class="relative flex-shrink-0">
                <svg width="40" height="40" viewBox="0 0 120 120" class="animate-float">
                    <defs>
                        <linearGradient id="quantumGradientSidebar" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:hsl(195, 100%, 50%);stop-opacity:1" />
                            <stop offset="100%" style="stop-color:hsl(270, 80%, 60%);stop-opacity:1" />
                        </linearGradient>
                    </defs>

                    <!-- Quantum Particle Structure -->
                    <circle cx="60" cy="30" r="8" fill="url(#quantumGradientSidebar)" opacity="0.9"/>
                    <circle cx="30" cy="70" r="8" fill="url(#quantumGradientSidebar)" opacity="0.9"/>
                    <circle cx="90" cy="70" r="8" fill="url(#quantumGradientSidebar)" opacity="0.9"/>
                    <circle cx="60" cy="90" r="6" fill="hsl(45, 100%, 50%)" opacity="0.8"/>

                    <!-- Quantum Connections -->
                    <line x1="60" y1="30" x2="30" y2="70" stroke="url(#quantumGradientSidebar)" stroke-width="2" opacity="0.5"/>
                    <line x1="60" y1="30" x2="90" y2="70" stroke="url(#quantumGradientSidebar)" stroke-width="2" opacity="0.5"/>
                    <line x1="30" y1="70" x2="90" y2="70" stroke="url(#quantumGradientSidebar)" stroke-width="2" opacity="0.5"/>
                    <line x1="60" y1="90" x2="30" y2="70" stroke="hsl(45, 100%, 50%)" stroke-width="2" opacity="0.4"/>
                    <line x1="60" y1="90" x2="90" y2="70" stroke="hsl(45, 100%, 50%)" stroke-width="2" opacity="0.4"/>

                    <!-- Central Energy Core -->
                    <circle cx="60" cy="60" r="12" fill="none" stroke="url(#quantumGradientSidebar)" stroke-width="2" opacity="0.6"/>
                    <circle cx="60" cy="60" r="4" fill="url(#quantumGradientSidebar)" opacity="0.9">
                        <animate attributeName="r" values="3;5;3" dur="2s" repeatCount="indefinite"/>
                        <animate attributeName="opacity" values="0.8;1;0.8" dur="2s" repeatCount="indefinite"/>
                    </circle>
                </svg>
            </div>

            <div class="flex flex-col min-w-0">
                <span class="text-xl font-bold tracking-tight bg-gradient-to-r from-quantum-500 to-void-500 bg-clip-text text-transparent">
                    QUANTUM
                </span>
                <span class="text-[10px] text-gray-400 tracking-wider uppercase">
                    Gestión Empresarial
                </span>
            </div>
        </div>

        <!-- Collapsed Logo (Icon Only) -->
        <div class="flex items-center justify-center transition-all duration-300 flex-1" :class="!sidebarOpen ? 'opacity-100' : 'opacity-0 absolute pointer-events-none'">
            <svg width="32" height="32" viewBox="0 0 120 120">
                <defs>
                    <linearGradient id="quantumGradientCollapsed" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:hsl(195, 100%, 50%);stop-opacity:1" />
                        <stop offset="100%" style="stop-color:hsl(270, 80%, 60%);stop-opacity:1" />
                    </linearGradient>
                </defs>
                <circle cx="60" cy="60" r="20" fill="url(#quantumGradientCollapsed)" opacity="0.9"/>
                <circle cx="60" cy="60" r="8" fill="hsl(45, 100%, 50%)" opacity="0.8">
                    <animate attributeName="r" values="6;10;6" dur="2s" repeatCount="indefinite"/>
                </circle>
            </svg>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-3 py-6 space-y-2 overflow-y-auto">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-4 px-4 py-3 rounded-quantum transition-all duration-200 group {{ Request::routeIs('dashboard') ? 'bg-gradient-to-r from-quantum-500/20 to-void-500/20 border border-quantum-500/30 text-white shadow-quantum' : 'text-gray-400 hover:text-white hover:bg-matter-light' }}">
            <svg class="w-5 h-5 flex-shrink-0 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="font-medium transition-all duration-300" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">
                Dashboard
            </span>
        </a>

        <!-- Proyectos -->
        <a href="{{ route('proyectos.index') }}"
           class="flex items-center gap-4 px-4 py-3 rounded-quantum transition-all duration-200 group {{ Request::routeIs('proyectos.*') ? 'bg-gradient-to-r from-quantum-500/20 to-void-500/20 border border-quantum-500/30 text-white shadow-quantum' : 'text-gray-400 hover:text-white hover:bg-matter-light' }}">
            <svg class="w-5 h-5 flex-shrink-0 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
            </svg>
            <span class="font-medium transition-all duration-300" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">
                Proyectos
            </span>
        </a>

        <!-- Estadísticas -->
        <a href="{{ route('estadistica') }}"
           class="flex items-center gap-4 px-4 py-3 rounded-quantum transition-all duration-200 group {{ Request::routeIs('estadistica') ? 'bg-gradient-to-r from-quantum-500/20 to-void-500/20 border border-quantum-500/30 text-white shadow-quantum' : 'text-gray-400 hover:text-white hover:bg-matter-light' }}">
            <svg class="w-5 h-5 flex-shrink-0 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span class="font-medium transition-all duration-300" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">
                Estadísticas
            </span>
        </a>

        <!-- Auditoría (Admin only) -->
        @if(Auth::user()->isAdmin())
        <a href="{{ route('audit.index') }}"
           class="flex items-center gap-4 px-4 py-3 rounded-quantum transition-all duration-200 group {{ Request::routeIs('audit.*') ? 'bg-gradient-to-r from-quantum-500/20 to-void-500/20 border border-quantum-500/30 text-white shadow-quantum' : 'text-gray-400 hover:text-white hover:bg-matter-light' }}">
            <svg class="w-5 h-5 flex-shrink-0 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span class="font-medium transition-all duration-300" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">
                Auditoría
            </span>
        </a>
        @endif

        <!-- Usuarios (Admin only) -->
        @if(Auth::user()->isAdmin())
        <a href="{{ route('users.index') }}"
           class="flex items-center gap-4 px-4 py-3 rounded-quantum transition-all duration-200 group {{ Request::routeIs('users.*') ? 'bg-gradient-to-r from-quantum-500/20 to-void-500/20 border border-quantum-500/30 text-white shadow-quantum' : 'text-gray-400 hover:text-white hover:bg-matter-light' }}">
            <svg class="w-5 h-5 flex-shrink-0 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <span class="font-medium transition-all duration-300" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">
                Usuarios
            </span>
        </a>
        @endif

        <!-- Solicitudes de Acceso (Admin only) -->
        @if(Auth::user()->isAdmin())
        <a href="{{ route('access-requests.index') }}"
           class="flex items-center gap-4 px-4 py-3 rounded-quantum transition-all duration-200 group relative {{ Request::routeIs('access-requests.*') ? 'bg-gradient-to-r from-quantum-500/20 to-void-500/20 border border-quantum-500/30 text-white shadow-quantum' : 'text-gray-400 hover:text-white hover:bg-matter-light' }}">
            <svg class="w-5 h-5 flex-shrink-0 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            <span class="font-medium transition-all duration-300" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">
                Solicitudes
            </span>
            <!-- Pending Badge (if any) -->
            @if(isset($pendingRequestsCount) && $pendingRequestsCount > 0)
            <span class="absolute top-2 right-2 w-5 h-5 flex items-center justify-center text-[10px] font-bold bg-photon-500 text-space-500 rounded-full animate-pulse">
                {{ $pendingRequestsCount }}
            </span>
            @endif
        </a>
        @endif

        <!-- Solicitudes de Acceso a Recursos (todos los usuarios) -->
        <a href="{{ route('solicitudes-acceso.index') }}"
           class="flex items-center gap-4 px-4 py-3 rounded-quantum transition-all duration-200 group relative {{ Request::routeIs('solicitudes-acceso.*') ? 'bg-gradient-to-r from-quantum-500/20 to-void-500/20 border border-quantum-500/30 text-white shadow-quantum' : 'text-gray-400 hover:text-white hover:bg-matter-light' }}">
            <svg class="w-5 h-5 flex-shrink-0 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
            </svg>
            <span class="font-medium transition-all duration-300" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">
                Control de Acceso
            </span>
        </a>

        <!-- Análisis de Riesgo (Admin only) -->
        @if(Auth::user()->isAdmin())
        <a href="{{ route('analytics.riesgo') }}"
           class="flex items-center gap-4 px-4 py-3 rounded-quantum transition-all duration-200 group {{ Request::routeIs('analytics.riesgo') ? 'bg-gradient-to-r from-quantum-500/20 to-void-500/20 border border-quantum-500/30 text-white shadow-quantum' : 'text-gray-400 hover:text-white hover:bg-matter-light' }}">
            <svg class="w-5 h-5 flex-shrink-0 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <span class="font-medium transition-all duration-300" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">
                Análisis de Riesgo
            </span>
        </a>
        @endif

        <!-- Divider -->
        <div class="my-4 border-t border-matter-light"></div>

        <!-- Configuración -->
        <a href="{{ route('configuration.index') }}"
           class="flex items-center gap-4 px-4 py-3 rounded-quantum transition-all duration-200 group {{ Request::routeIs('configuration.*') ? 'bg-gradient-to-r from-quantum-500/20 to-void-500/20 border border-quantum-500/30 text-white shadow-quantum' : 'text-gray-400 hover:text-white hover:bg-matter-light' }}">
            <svg class="w-5 h-5 flex-shrink-0 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="font-medium transition-all duration-300" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">
                Configuración
            </span>
        </a>

        <!-- Divider -->
        <div class="my-4 border-t border-matter-light"></div>

        <!-- Suscripción -->
        <button @click="$dispatch('open-subscription-modal')"
           class="w-full flex items-center gap-4 px-4 py-3 rounded-quantum transition-all duration-200 group bg-gradient-to-r from-quantum-500/20 via-void-500/20 to-quantum-500/20 border border-quantum-500/30 hover:border-quantum-500/50 text-white hover:shadow-quantum hover:scale-105">
            <svg class="w-5 h-5 flex-shrink-0 transition-transform group-hover:scale-110 text-quantum-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="font-bold transition-all duration-300 bg-gradient-to-r from-quantum-500 to-void-500 bg-clip-text text-transparent" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">
                Adquiere Suscripción
            </span>
        </button>
    </nav>

    <!-- User Profile Section -->
    <div class="border-t border-matter-light p-4">
        <div class="flex items-center gap-3" :class="sidebarOpen ? 'flex-row' : 'flex-col'">
            <div class="relative flex-shrink-0">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-quantum-500 to-void-500 flex items-center justify-center text-white font-bold text-sm ring-2 ring-quantum-500/30">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-matter"></div>
            </div>

            <div class="flex-1 min-w-0 transition-all duration-300" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">
                <p class="text-sm font-medium text-white truncate">
                    {{ Auth::user()->name }}
                </p>
                <p class="text-xs text-gray-400 truncate">
                    @php
                        $userRole = 'Colaborador';
                        if (Auth::user()->isAdmin()) {
                            $userRole = 'Administrador';
                        } elseif (Auth::user()->isGestor()) {
                            $userRole = 'Gestor';
                        }
                    @endphp
                    {{ $userRole }}
                </p>
            </div>
        </div>

        <!-- Logout Button -->
        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 rounded-quantum text-sm text-red-400 hover:text-red-300 hover:bg-red-500/10 transition-all duration-200 group">
                <svg class="w-4 h-4 flex-shrink-0 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span class="font-medium transition-all duration-300" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">
                    Cerrar Sesión
                </span>
            </button>
        </form>
    </div>
</aside>

<style>
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-5px); }
}

.animate-float {
    animation: float 3s ease-in-out infinite;
}

[x-cloak] {
    display: none !important;
}
</style>
