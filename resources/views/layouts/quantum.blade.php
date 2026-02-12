<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'QUANTUM - Gestión a la Velocidad del Pensamiento')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @yield('styles')
</head>
<body class="bg-space-500 text-gray-50 antialiased" x-data="{ sidebarOpen: true, mobileSidebarOpen: false, subscriptionModalOpen: false }" @open-subscription-modal.window="subscriptionModalOpen = true">

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

                    <!-- Right: Profile -->
                    <div class="flex items-center gap-3">
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
                                    <a href="{{ route('configuration.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-300 hover:bg-matter-light hover:text-white transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        Mi Perfil
                                    </a>
                                    <a href="{{ route('configuration.index') }}#security" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-300 hover:bg-matter-light hover:text-white transition-colors">
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
    @stack('scripts')

    <!-- Subscription Modal -->
    <div x-show="subscriptionModalOpen"
         x-cloak
         @keydown.escape.window="subscriptionModalOpen = false"
         class="fixed inset-0 z-50 bg-black/70 backdrop-blur-md overflow-y-auto"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">

        {{-- Scroll wrapper: uses flexbox with min-h to center when short, scroll when tall --}}
        <div class="min-h-full flex items-start md:items-center justify-center p-3 sm:p-4 md:p-6">
            <div @click.away="subscriptionModalOpen = false"
                 x-data="{ annual: false }"
                 class="card-quantum max-w-5xl w-full p-5 sm:p-6 lg:p-8 my-4 sm:my-6 relative"
                 x-transition:enter="transition ease-out duration-400"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0">

                {{-- Close button --}}
                <button @click="subscriptionModalOpen = false" class="absolute top-3 right-3 sm:top-4 sm:right-4 w-8 h-8 flex items-center justify-center rounded-full bg-matter-light/50 hover:bg-matter-light text-gray-400 hover:text-white transition-all duration-200 z-10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                <!-- Header -->
                <div class="text-center mb-6 sm:mb-8">
                    <div class="inline-flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-photon-500/20 to-quantum-500/20 rounded-2xl mb-3 ring-1 ring-photon-500/20">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-photon-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-photon-500 via-quantum-500 to-void-500 bg-clip-text text-transparent mb-2">
                        Potencia tu Gestion con QUANTUM
                    </h2>
                    <p class="text-gray-400 text-sm sm:text-base max-w-lg mx-auto">Herramientas especializadas en cumplimiento normativo colombiano, gestion de riesgos y vigilancia de proyectos</p>

                    {{-- Segmented Control Toggle --}}
                    <div class="inline-flex items-center mt-5 sm:mt-6 p-1 rounded-xl bg-matter-light/80 border border-matter-light relative">
                        {{-- Sliding background indicator --}}
                        <div class="absolute top-1 bottom-1 rounded-lg transition-all duration-300 ease-in-out bg-gradient-to-r from-quantum-500 to-void-500 shadow-lg shadow-quantum-500/25"
                             :class="annual ? 'left-[calc(50%)] right-1' : 'left-1 right-[calc(50%)]'"
                             style="z-index: 0;"></div>

                        <button @click="annual = false"
                                class="relative z-10 px-4 sm:px-5 py-2 text-xs sm:text-sm font-semibold rounded-lg transition-colors duration-300"
                                :class="!annual ? 'text-white' : 'text-gray-400 hover:text-gray-300'">
                            Mensual
                        </button>
                        <button @click="annual = true"
                                class="relative z-10 px-4 sm:px-5 py-2 text-xs sm:text-sm font-semibold rounded-lg transition-colors duration-300"
                                :class="annual ? 'text-white' : 'text-gray-400 hover:text-gray-300'">
                            Anual
                        </button>
                    </div>
                </div>

                <!-- Plans Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5 mb-6 sm:mb-8">

                    <!-- Plan Basico -->
                    <div class="card-quantum p-5 hover:border-quantum-500/40 transition-all duration-300 relative flex flex-col group">
                        <div class="text-center mb-5">
                            <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-gray-500/10 mb-2.5 ring-1 ring-gray-500/20">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-white mb-0.5">Basico</h3>
                            <p class="text-[11px] text-gray-500 mb-3">Ideal para pequenos equipos</p>
                            <div>
                                <div class="flex items-baseline justify-center gap-1">
                                    <span class="text-3xl sm:text-4xl font-extrabold text-white" x-text="annual ? '$6.500.000' : '$79.900'">$79.900</span>
                                    <span class="text-gray-500 text-xs">/mes</span>
                                </div>
                                <div class="h-5 mt-1">
                                    <p x-show="annual" x-transition.opacity class="text-[11px] text-green-400">
                                        $78.000.000/ano
                                    </p>
                                </div>
                            </div>
                        </div>

                        <ul class="space-y-2.5 mb-5 flex-1">
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-[13px] text-gray-300">Hasta <strong class="text-white">5 usuarios</strong></span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-[13px] text-gray-300">Hasta <strong class="text-white">10 proyectos</strong></span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-[13px] text-gray-300"><strong class="text-white">5GB</strong> almacenamiento</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-[13px] text-gray-300">Gestion de documentos</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-[13px] text-gray-300">Seguimiento basico</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-[13px] text-gray-300">Soporte por email</span>
                            </li>
                            <li class="flex items-center gap-2.5 opacity-40">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                <span class="text-[13px]">Vigilancia y riesgo</span>
                            </li>
                            <li class="flex items-center gap-2.5 opacity-40">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                <span class="text-[13px]">Compliance normativo</span>
                            </li>
                        </ul>

                        <button class="w-full px-4 py-2.5 rounded-quantum border border-matter-light text-gray-300 hover:bg-matter-light hover:text-white transition-all duration-200 font-medium text-sm">
                            Comenzar Ahora
                        </button>
                    </div>

                    <!-- Plan Profesional (Popular) -->
                    <div class="card-quantum p-5 transition-all duration-300 relative border-2 border-quantum-500/50 bg-gradient-to-b from-quantum-500/5 to-void-500/5 flex flex-col ring-1 ring-quantum-500/20 shadow-lg shadow-quantum-500/10 md:-my-2">
                        <!-- Popular Badge -->
                        <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 z-10">
                            <span class="px-4 py-1 bg-gradient-to-r from-photon-500 to-amber-400 text-space-500 text-[11px] font-bold rounded-full uppercase tracking-wider shadow-lg shadow-photon-500/30 whitespace-nowrap">
                                Mas Popular
                            </span>
                        </div>

                        <div class="text-center mb-5 mt-3">
                            <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-quantum-500/10 mb-2.5 ring-1 ring-quantum-500/30">
                                <svg class="w-5 h-5 text-quantum-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-white mb-0.5">Profesional</h3>
                            <p class="text-[11px] text-gray-500 mb-3">Para empresas en crecimiento</p>
                            <div>
                                <div class="flex items-baseline justify-center gap-1">
                                    <span class="text-3xl sm:text-4xl font-extrabold bg-gradient-to-r from-quantum-500 to-void-500 bg-clip-text text-transparent" x-text="annual ? '$8.083.300' : '$199.900'">$199.900</span>
                                    <span class="text-gray-500 text-xs">/mes</span>
                                </div>
                                <div class="h-5 mt-1">
                                    <p x-show="annual" x-transition.opacity class="text-[11px] text-green-400">
                                        $97.000.000/ano
                                    </p>
                                </div>
                            </div>
                        </div>

                        <ul class="space-y-2.5 mb-5 flex-1">
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-quantum-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-[13px] text-gray-300">Hasta <strong class="text-white">25 usuarios</strong></span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-quantum-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-[13px] text-gray-300">Proyectos <strong class="text-white">ilimitados</strong></span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-quantum-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-[13px] text-gray-300"><strong class="text-white">50GB</strong> almacenamiento</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-quantum-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-[13px] text-gray-300">Gestion avanzada de documentos</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-quantum-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-[13px] text-gray-300">Auditoria <strong class="text-white">completa</strong></span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-quantum-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-[13px] text-gray-300"><strong class="text-white">Vigilancia y riesgo</strong></span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-quantum-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-[13px] text-gray-300">Reportes y analytics</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-quantum-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-[13px] text-gray-300">Integracion con APIs</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-quantum-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-[13px] text-gray-300">Soporte <strong class="text-white">prioritario</strong></span>
                            </li>
                        </ul>

                        <button class="w-full px-4 py-3 rounded-quantum bg-gradient-to-r from-quantum-500 to-void-500 text-white hover:from-quantum-400 hover:to-void-400 transition-all duration-200 font-bold text-sm shadow-quantum">
                            Elegir Profesional
                        </button>
                    </div>

                    <!-- Plan Empresarial -->
                    <div class="card-quantum p-5 hover:border-void-500/40 transition-all duration-300 relative flex flex-col group">
                        <div class="text-center mb-5">
                            <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-void-500/10 mb-2.5 ring-1 ring-void-500/20">
                                <svg class="w-5 h-5 text-void-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-white mb-0.5">Empresarial</h3>
                            <p class="text-[11px] text-gray-500 mb-3">Para grandes organizaciones</p>
                            <div>
                                <div class="flex items-baseline justify-center gap-1">
                                    <span class="text-3xl sm:text-4xl font-extrabold bg-gradient-to-r from-void-500 to-photon-500 bg-clip-text text-transparent" x-text="annual ? '$12.083.300' : '$499.900'">$499.900</span>
                                    <span class="text-gray-500 text-xs">/mes</span>
                                </div>
                                <div class="h-5 mt-1">
                                    <p x-show="annual" x-transition.opacity class="text-[11px] text-green-400">
                                        $145.000.000/ano
                                    </p>
                                </div>
                            </div>
                        </div>

                        <ul class="space-y-2.5 mb-5 flex-1">
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-void-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-[13px] text-gray-300">Usuarios <strong class="text-white">ilimitados</strong></span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-void-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-[13px] text-gray-300">Proyectos <strong class="text-white">ilimitados</strong></span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-void-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-[13px] text-gray-300">Almacenamiento <strong class="text-white">ilimitado</strong></span>
                            </li>
                            {{-- <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-void-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-[13px] text-gray-300"><strong class="text-white">IA</strong> para analisis de documentos</span>
                            </li> --}}
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-void-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-[13px] text-gray-300">Auditoria avanzada + <strong class="text-white">Compliance</strong></span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-void-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-[13px] text-gray-300">Cumplimiento <strong class="text-white">DNP-SPI y Ley 1474</strong></span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-void-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-[13px] text-gray-300">Reportes <strong class="text-white">personalizados</strong></span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-void-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-[13px] text-gray-300">Multi-tenant y <strong class="text-white">SSO</strong></span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-void-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-[13px] text-gray-300">API completa y webhooks</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-void-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-[13px] text-gray-300">Soporte <strong class="text-white">24/7</strong> dedicado</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-void-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-[13px] text-gray-300">Gestor de cuenta <strong class="text-white">dedicado</strong></span>
                            </li>
                        </ul>

                        <button class="w-full px-4 py-2.5 rounded-quantum bg-gradient-to-r from-void-500 to-photon-500 text-white hover:from-void-400 hover:to-photon-400 transition-all duration-200 font-bold text-sm shadow-quantum">
                            Contactar Ventas
                        </button>
                    </div>
                </div>

                <!-- Footer Info -->
                <div class="text-center pt-5 border-t border-matter-light">
                    <div class="flex flex-wrap items-center justify-center gap-4 sm:gap-6 mb-3">
                        <div class="flex items-center gap-1.5 text-[11px] text-gray-500">
                            <svg class="w-3.5 h-3.5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            Datos seguros en Colombia
                        </div>
                        <div class="flex items-center gap-1.5 text-[11px] text-gray-500">
                            <svg class="w-3.5 h-3.5 text-quantum-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Actualizaciones incluidas
                        </div>
                        <div class="flex items-center gap-1.5 text-[11px] text-gray-500">
                            <svg class="w-3.5 h-3.5 text-photon-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Cancela cuando quieras
                        </div>
                    </div>
                    <p class="text-[10px] text-gray-600">
                        Precios en COP (pesos colombianos). IVA no incluido. Facturacion electronica disponible.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Liquid Glass Toast System -->
    <div id="toast-container" class="fixed top-5 right-5 z-[9999] flex flex-col items-end gap-3 pointer-events-none"></div>

    <style>
        /* ─── Liquid Glass Toast ─── Apple-inspired Liquid Glass (2026) ─── */
        .liquid-glass-toast {
            --glass-bg: rgba(255, 255, 255, 0.06);
            --glass-border: rgba(255, 255, 255, 0.12);
            --glass-highlight: rgba(255, 255, 255, 0.08);
            --glass-blur: 24px;
            position: relative;
            min-width: 340px;
            max-width: 440px;
            padding: 16px 18px;
            border-radius: 18px;
            background: var(--glass-bg);
            backdrop-filter: blur(var(--glass-blur)) saturate(180%);
            -webkit-backdrop-filter: blur(var(--glass-blur)) saturate(180%);
            border: 1px solid var(--glass-border);
            box-shadow:
                0 0 0 0.5px rgba(255, 255, 255, 0.05),
                0 8px 40px -8px rgba(0, 0, 0, 0.5),
                inset 0 1px 0 rgba(255, 255, 255, 0.07);
            pointer-events: auto;
            overflow: hidden;
            cursor: default;
            transform: translateX(calc(100% + 40px)) scale(0.92);
            opacity: 0;
            transition: transform 0.55s cubic-bezier(0.22, 1, 0.36, 1),
                        opacity 0.45s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .liquid-glass-toast.lg-visible {
            transform: translateX(0) scale(1);
            opacity: 1;
        }
        .liquid-glass-toast.lg-dismiss {
            transform: translateX(calc(100% + 40px)) scale(0.88);
            opacity: 0;
            transition: transform 0.35s cubic-bezier(0.4, 0, 1, 1),
                        opacity 0.25s cubic-bezier(0.4, 0, 1, 1);
        }
        /* Specular highlight — top edge refraction */
        .liquid-glass-toast::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 50%;
            border-radius: 18px 18px 0 0;
            background: linear-gradient(180deg,
                rgba(255, 255, 255, 0.09) 0%,
                rgba(255, 255, 255, 0.02) 50%,
                transparent 100%);
            pointer-events: none;
        }
        /* Colored accent glow — bottom inner */
        .liquid-glass-toast::after {
            content: '';
            position: absolute;
            bottom: -20px; left: 20%; right: 20%;
            height: 40px;
            border-radius: 50%;
            filter: blur(20px);
            opacity: 0.4;
            pointer-events: none;
            transition: opacity 0.5s;
        }
        /* Type-specific accent glows */
        .liquid-glass-toast[data-type="success"]::after { background: rgba(52, 211, 153, 0.5); }
        .liquid-glass-toast[data-type="error"]::after   { background: rgba(239, 68, 68, 0.5); }
        .liquid-glass-toast[data-type="warning"]::after  { background: rgba(245, 158, 11, 0.5); }
        .liquid-glass-toast[data-type="info"]::after     { background: rgba(99, 102, 241, 0.5); }
        /* Type-specific border tints */
        .liquid-glass-toast[data-type="success"] { --glass-border: rgba(52, 211, 153, 0.18); }
        .liquid-glass-toast[data-type="error"]   { --glass-border: rgba(239, 68, 68, 0.18); }
        .liquid-glass-toast[data-type="warning"] { --glass-border: rgba(245, 158, 11, 0.18); }
        .liquid-glass-toast[data-type="info"]    { --glass-border: rgba(99, 102, 241, 0.18); }
        /* Progress track */
        .lg-progress {
            position: absolute;
            bottom: 0; left: 0;
            height: 2px;
            border-radius: 0 0 18px 18px;
            opacity: 0.6;
            transition: width linear;
        }
        .lg-progress[data-type="success"] { background: linear-gradient(90deg, rgba(52,211,153,0.8), rgba(16,185,129,0.4)); }
        .lg-progress[data-type="error"]   { background: linear-gradient(90deg, rgba(239,68,68,0.8), rgba(220,38,38,0.4)); }
        .lg-progress[data-type="warning"] { background: linear-gradient(90deg, rgba(245,158,11,0.8), rgba(217,119,6,0.4)); }
        .lg-progress[data-type="info"]    { background: linear-gradient(90deg, rgba(99,102,241,0.8), rgba(79,70,229,0.4)); }
        /* Hover pause effect */
        .liquid-glass-toast:hover { --glass-bg: rgba(255, 255, 255, 0.09); }
        .liquid-glass-toast:hover .lg-progress { animation-play-state: paused !important; }
    </style>

    <script>
        // ─── Liquid Glass Toast System ─────────────────────────────────────
        // Apple-inspired Liquid Glass design (2026)
        (function() {
            const ICONS = {
                success: '<svg class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="rgba(52,211,153,0.9)" stroke-width="1.5"/><path d="M8 12.5l2.5 2.5 5-5" stroke="rgba(52,211,153,0.9)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                error: '<svg class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="rgba(239,68,68,0.9)" stroke-width="1.5"/><path d="M15 9l-6 6M9 9l6 6" stroke="rgba(239,68,68,0.9)" stroke-width="1.8" stroke-linecap="round"/></svg>',
                warning: '<svg class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none"><path d="M12 9v4m0 4h.01" stroke="rgba(245,158,11,0.9)" stroke-width="1.8" stroke-linecap="round"/><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" stroke="rgba(245,158,11,0.9)" stroke-width="1.5" fill="none"/></svg>',
                info: '<svg class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="rgba(139,140,255,0.9)" stroke-width="1.5"/><path d="M12 16v-4m0-4h.01" stroke="rgba(139,140,255,0.9)" stroke-width="1.8" stroke-linecap="round"/></svg>'
            };
            const TITLE_COLORS = {
                success: 'rgba(52,211,153,0.95)',
                error: 'rgba(248,113,113,0.95)',
                warning: 'rgba(251,191,36,0.95)',
                info: 'rgba(165,165,255,0.95)'
            };
            const TYPE_LABELS = {
                success: 'Completado',
                error: 'Error',
                warning: 'Atención',
                info: 'Información'
            };

            let toastCount = 0;

            function getContainer() {
                let c = document.getElementById('toast-container');
                if (!c) {
                    c = document.createElement('div');
                    c.id = 'toast-container';
                    c.className = 'fixed top-5 right-5 z-[9999] flex flex-col items-end gap-3 pointer-events-none';
                    document.body.appendChild(c);
                }
                return c;
            }

            function dismiss(el) {
                if (el._dismissed) return;
                el._dismissed = true;
                el.classList.remove('lg-visible');
                el.classList.add('lg-dismiss');
                el.addEventListener('transitionend', function handler() {
                    el.removeEventListener('transitionend', handler);
                    el.remove();
                }, { once: true });
                // Fallback in case transitionend doesn't fire
                setTimeout(() => { if (el.parentNode) el.remove(); }, 500);
            }

            window.showToast = function(message, type, duration) {
                type = type || 'info';
                duration = duration || 5000;
                const container = getContainer();
                const id = 'lg-toast-' + (++toastCount);

                const toast = document.createElement('div');
                toast.id = id;
                toast.className = 'liquid-glass-toast';
                toast.setAttribute('data-type', type);
                toast.setAttribute('role', 'alert');
                toast.setAttribute('aria-live', 'assertive');

                toast.innerHTML = `
                    <div class="relative z-10 flex items-start gap-3">
                        <div class="flex-shrink-0 mt-0.5">${ICONS[type] || ICONS.info}</div>
                        <div class="flex-1 min-w-0">
                            <div class="text-[11px] font-semibold uppercase tracking-wider mb-1" style="color:${TITLE_COLORS[type] || TITLE_COLORS.info}">${TYPE_LABELS[type] || 'Info'}</div>
                            <div class="text-[13px] leading-[1.45] font-medium text-white/90">${message}</div>
                        </div>
                        <button class="lg-close flex-shrink-0 -mt-0.5 -mr-1 p-1.5 rounded-full text-white/30 hover:text-white/70 hover:bg-white/10 transition-all duration-200" aria-label="Cerrar">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div class="lg-progress" data-type="${type}" style="width:100%"></div>
                `;

                container.appendChild(toast);

                // Close button handler
                toast.querySelector('.lg-close').addEventListener('click', function(e) {
                    e.stopPropagation();
                    dismiss(toast);
                });

                // Animate in (next frame for CSS transition to trigger)
                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        toast.classList.add('lg-visible');
                    });
                });

                // Progress bar countdown
                const progressBar = toast.querySelector('.lg-progress');
                let remaining = duration;
                let startTime = Date.now();
                let paused = false;

                function tick() {
                    if (toast._dismissed) return;
                    if (paused) { requestAnimationFrame(tick); return; }
                    const elapsed = Date.now() - startTime;
                    remaining = duration - elapsed;
                    if (remaining <= 0) {
                        dismiss(toast);
                        return;
                    }
                    const pct = (remaining / duration) * 100;
                    progressBar.style.width = pct + '%';
                    requestAnimationFrame(tick);
                }

                // Pause on hover
                toast.addEventListener('mouseenter', function() {
                    paused = true;
                });
                toast.addEventListener('mouseleave', function() {
                    paused = false;
                    startTime = Date.now() - (duration - remaining);
                });

                requestAnimationFrame(tick);

                // Max toasts: keep only 5 visible
                const all = container.querySelectorAll('.liquid-glass-toast');
                if (all.length > 5) {
                    dismiss(all[0]);
                }
            };
        })();

        // Display Laravel session flash messages as toasts
        @if(session('success'))
            showToast(@json(session('success')), 'success');
        @endif

        @if(session('error'))
            showToast(@json(session('error')), 'error');
        @endif

        @if(session('warning'))
            showToast(@json(session('warning')), 'warning');
        @endif

        @if(session('info'))
            showToast(@json(session('info')), 'info');
        @endif

        @if($errors->any())
            @foreach($errors->all() as $error)
                showToast(@json($error), 'error');
            @endforeach
        @endif
    </script>

    {{-- Modal: Forzar cambio de contraseña temporal --}}
    @auth
        @if(auth()->user()->is_temporary_password)
        <div x-data="{
            currentPassword: '',
            newPassword: '',
            confirmPassword: '',
            loading: false,
            error: '',
            async submit() {
                this.error = '';
                if (this.newPassword.length < 8) {
                    this.error = 'La nueva contraseña debe tener al menos 8 caracteres';
                    return;
                }
                if (this.newPassword !== this.confirmPassword) {
                    this.error = 'Las contraseñas no coinciden';
                    return;
                }
                this.loading = true;
                try {
                    const res = await fetch('{{ route("password.change.temporary") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            current_password: this.currentPassword,
                            new_password: this.newPassword,
                            new_password_confirmation: this.confirmPassword
                        })
                    });
                    const data = await res.json();
                    if (!res.ok) {
                        this.error = data.error || data.message || 'Error al cambiar la contraseña';
                        this.loading = false;
                        return;
                    }
                    window.location.reload();
                } catch (e) {
                    this.error = 'Error de conexión';
                    this.loading = false;
                }
            }
        }" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
            <div class="card-quantum max-w-md w-full p-8">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-quantum-500/20 to-void-500/20 rounded-full mb-4">
                        <svg class="w-8 h-8 text-quantum-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2">Cambiar Contraseña</h2>
                    <p class="text-gray-400 text-sm">Por seguridad, debes cambiar tu contraseña temporal antes de continuar.</p>
                </div>

                <template x-if="error">
                    <div class="mb-4 p-3 bg-red-500/10 border border-red-500/30 rounded-quantum">
                        <p class="text-red-400 text-sm" x-text="error"></p>
                    </div>
                </template>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Contraseña actual (temporal)</label>
                        <input type="password" x-model="currentPassword" required class="input-quantum w-full" placeholder="La contraseña que recibiste por correo">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Nueva contraseña</label>
                        <input type="password" x-model="newPassword" required minlength="8" class="input-quantum w-full" placeholder="Mínimo 8 caracteres">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Confirmar nueva contraseña</label>
                        <input type="password" x-model="confirmPassword" required class="input-quantum w-full" placeholder="Repite la nueva contraseña">
                    </div>
                    <button type="submit" :disabled="loading" class="btn-quantum w-full flex items-center justify-center gap-2 mt-2">
                        <template x-if="loading">
                            <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                        </template>
                        <span x-text="loading ? 'Guardando...' : 'Guardar Nueva Contraseña'"></span>
                    </button>
                </form>
            </div>
        </div>
        @endif
    @endauth
</body>
</html>
