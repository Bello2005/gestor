<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'QUANTUM - Gestión a la Velocidad del Pensamiento')</title>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @yield('styles')
</head>
<body class="bg-space-500 text-gray-50 antialiased" x-data="{ sidebarOpen: true, mobileSidebarOpen: false }">

    <!-- Sidebar -->
    @include('layouts.partials.quantum-sidebar')

    <!-- Main Content Area -->
    <div
        class="transition-all duration-300 min-h-screen"
        :class="sidebarOpen ? 'md:ml-64' : 'md:ml-20'"
    >
        <!-- Top Bar -->
        <header class="sticky top-0 z-40 bg-matter/95 backdrop-blur-quantum border-b border-matter-light">
            <div class="px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <!-- Left: Mobile Menu + Page Title -->
                    <div class="flex items-center gap-4">
                        <!-- Mobile Menu Button -->
                        <button
                            @click="mobileSidebarOpen = true"
                            class="md:hidden w-10 h-10 flex items-center justify-center rounded-lg bg-matter-light hover:bg-quantum-500/20 transition-colors"
                        >
                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>

                        <!-- Desktop Sidebar Toggle -->
                        <button
                            @click="sidebarOpen = !sidebarOpen"
                            class="hidden md:flex w-10 h-10 items-center justify-center rounded-lg bg-matter-light hover:bg-quantum-500/20 transition-colors"
                        >
                            <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>

                        <!-- Page Title -->
                        <h1 class="text-xl font-semibold text-white">
                            @yield('page-title', 'Dashboard')
                        </h1>
                    </div>

                    <!-- Right: Search + Notifications + Profile -->
                    <div class="flex items-center gap-3">
                        <!-- Search (hidden on mobile) -->
                        <div class="hidden sm:block relative">
                            <input
                                type="search"
                                placeholder="Buscar..."
                                class="w-64 px-4 py-2 pl-10 bg-matter border border-matter-light rounded-quantum text-sm text-gray-300 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-quantum-500 transition-all"
                            >
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>

                        <!-- Notifications -->
                        <button class="w-10 h-10 flex items-center justify-center rounded-lg bg-matter-light hover:bg-quantum-500/20 transition-colors relative">
                            <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span class="absolute top-1 right-1 w-2 h-2 bg-photon-500 rounded-full"></span>
                        </button>

                        <!-- Profile Dropdown -->
                        <div x-data="{ open: false }" @click.away="open = false" class="relative">
                            <button
                                @click="open = !open"
                                class="flex items-center gap-2 px-3 py-2 rounded-quantum bg-matter-light hover:bg-quantum-500/20 transition-colors"
                            >
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-quantum-500 to-void-500 flex items-center justify-center text-white font-semibold text-sm">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span class="hidden sm:block text-sm font-medium text-gray-300">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div
                                x-show="open"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-56 bg-matter border border-matter-light rounded-quantum-lg shadow-quantum-lg overflow-hidden z-50"
                            >
                                <div class="px-4 py-3 border-b border-matter-light">
                                    <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <div class="py-2">
                                    <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-300 hover:bg-matter-light hover:text-white transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        Mi Perfil
                                    </a>
                                    <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-300 hover:bg-matter-light hover:text-white transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        Configuración
                                    </a>
                                </div>
                                <div class="border-t border-matter-light py-2">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center gap-3 w-full px-4 py-2 text-sm text-red-400 hover:bg-matter-light hover:text-red-300 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                            </svg>
                                            Cerrar Sesión
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="p-4 sm:p-6 lg:p-8">
            @yield('content')
        </main>
    </div>

    @yield('scripts')

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2 pointer-events-none">
        <!-- Toasts will be injected here dynamically -->
    </div>

    <!-- Toast JavaScript -->
    <script>
        // Enhanced Toast System
        window.showToast = function(message, type = 'info', duration = 4000) {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');

            const colors = {
                success: {
                    bg: 'bg-green-500/90',
                    border: 'border-green-400/50',
                    icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                },
                error: {
                    bg: 'bg-red-500/90',
                    border: 'border-red-400/50',
                    icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                },
                warning: {
                    bg: 'bg-yellow-500/90',
                    border: 'border-yellow-400/50',
                    icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>'
                },
                info: {
                    bg: 'bg-quantum-500/90',
                    border: 'border-quantum-400/50',
                    icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                }
            };

            const config = colors[type] || colors.info;

            toast.className = `flex items-start gap-3 px-5 py-4 rounded-quantum shadow-quantum-lg backdrop-blur-sm ${config.bg} border ${config.border} text-white animate-slide-down pointer-events-auto min-w-[320px] max-w-md`;
            toast.innerHTML = `
                <div class="flex-shrink-0 mt-0.5">
                    ${config.icon}
                </div>
                <div class="flex-1 text-sm font-medium leading-relaxed">
                    ${message}
                </div>
                <button onclick="this.parentElement.remove()" class="flex-shrink-0 ml-2 text-white/80 hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            `;

            container.appendChild(toast);

            // Auto remove after duration
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                toast.style.transition = 'all 0.3s ease-out';
                setTimeout(() => toast.remove(), 300);
            }, duration);
        };

        // Display Laravel session flash messages as toasts
        @if(session('success'))
            showToast("{{ session('success') }}", 'success');
        @endif

        @if(session('error'))
            showToast("{{ session('error') }}", 'error');
        @endif

        @if(session('warning'))
            showToast("{{ session('warning') }}", 'warning');
        @endif

        @if(session('info'))
            showToast("{{ session('info') }}", 'info');
        @endif

        @if($errors->any())
            @foreach($errors->all() as $error)
                showToast("{{ $error }}", 'error');
            @endforeach
        @endif
    </script>
</body>
</html>
