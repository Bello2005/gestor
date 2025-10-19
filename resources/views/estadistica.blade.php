@extends('layouts.quantum')

@section('page-title', 'Estadísticas')

@section('content')
<!-- Header Section - Maldini Precision -->
<div class="mb-8">
    <div class="flex items-center justify-between flex-wrap gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2 flex items-center gap-3">
                <div class="w-1 h-8 bg-gradient-to-b from-quantum-500 via-void-500 to-photon-500 rounded-full"></div>
                Analytics & Insights
            </h1>
            <p class="text-gray-400">Precisión táctica en cada métrica, elegancia en cada dato</p>
        </div>

        <!-- Time Range Selector - Maldini Style -->
        <div x-data="{ range: '30d' }" class="flex items-center gap-2">
            <button @click="range = '7d'"
                    :class="range === '7d' ? 'bg-quantum-500 text-white shadow-quantum' : 'bg-matter-light text-gray-400 hover:text-white'"
                    class="px-4 py-2 rounded-quantum font-medium text-sm transition-all duration-200">
                7 días
            </button>
            <button @click="range = '30d'"
                    :class="range === '30d' ? 'bg-quantum-500 text-white shadow-quantum' : 'bg-matter-light text-gray-400 hover:text-white'"
                    class="px-4 py-2 rounded-quantum font-medium text-sm transition-all duration-200">
                30 días
            </button>
            <button @click="range = '90d'"
                    :class="range === '90d' ? 'bg-quantum-500 text-white shadow-quantum' : 'bg-matter-light text-gray-400 hover:text-white'"
                    class="px-4 py-2 rounded-quantum font-medium text-sm transition-all duration-200">
                90 días
            </button>
        </div>
    </div>

    <!-- KPI Cards - Maldini Precision -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Proyectos KPI -->
        <div class="card-quantum p-6 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-quantum-500/5 rounded-full blur-3xl group-hover:bg-quantum-500/10 transition-all duration-500"></div>

            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-quantum bg-quantum-500/20 border border-quantum-500/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-quantum-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total</span>
                    </div>
                    <span class="text-xs font-semibold text-green-400 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        +{{ $crecimientoProyectos }}%
                    </span>
                </div>

                <div class="mb-2">
                    <span class="text-4xl font-bold text-white">{{ $totalProyectos }}</span>
                </div>

                <p class="text-sm text-gray-400">Proyectos registrados</p>

                <!-- Mini Progress Bar -->
                <div class="mt-4 h-1 bg-matter-light rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-quantum-500 to-void-500 rounded-full animate-pulse"
                         style="width: {{ min($porcentajeActivos, 100) }}%"></div>
                </div>
            </div>
        </div>

        <!-- Valor Total KPI -->
        <div class="card-quantum p-6 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-photon-500/5 rounded-full blur-3xl group-hover:bg-photon-500/10 transition-all duration-500"></div>

            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-quantum bg-photon-500/20 border border-photon-500/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-photon-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Portfolio</span>
                    </div>
                    <span class="text-xs font-semibold text-green-400 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        +{{ $crecimiento }}%
                    </span>
                </div>

                <div class="mb-2">
                    <span class="text-4xl font-bold text-white">${{ number_format($valorTotal / 1000, 0) }}K</span>
                </div>

                <p class="text-sm text-gray-400">Valor total portfolio</p>

                <!-- Mini Sparkline -->
                <div class="mt-4">
                    <svg class="w-full h-8" viewBox="0 0 100 30">
                        <defs>
                            <linearGradient id="sparkGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" style="stop-color:hsl(45, 100%, 50%)"/>
                                <stop offset="100%" style="stop-color:hsl(195, 100%, 50%)"/>
                            </linearGradient>
                        </defs>
                        <polyline points="0,25 20,20 40,15 60,18 80,10 100,5"
                                  fill="none"
                                  stroke="url(#sparkGradient)"
                                  stroke-width="2"
                                  stroke-linecap="round"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Proyectos Activos KPI -->
        <div class="card-quantum p-6 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-green-500/5 rounded-full blur-3xl group-hover:bg-green-500/10 transition-all duration-500"></div>

            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-quantum bg-green-500/20 border border-green-500/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Activos</span>
                    </div>
                    <div class="text-xs font-semibold text-quantum-400">
                        {{ $porcentajeActivos }}%
                    </div>
                </div>

                <div class="mb-2">
                    <span class="text-4xl font-bold text-white">{{ $proyectosActivos }}</span>
                    <span class="text-lg text-gray-500">/{{ $totalProyectos }}</span>
                </div>

                <p class="text-sm text-gray-400">En ejecución actual</p>

                <!-- Circular Progress -->
                <div class="mt-4 flex items-center gap-3">
                    <svg class="w-12 h-12 transform -rotate-90">
                        <circle cx="24" cy="24" r="20" stroke="rgba(16, 185, 129, 0.1)" stroke-width="4" fill="none"/>
                        <circle cx="24" cy="24" r="20"
                                stroke="rgb(16, 185, 129)"
                                stroke-width="4"
                                fill="none"
                                stroke-dasharray="{{ 2 * 3.14159 * 20 }}"
                                stroke-dashoffset="{{ 2 * 3.14159 * 20 * (1 - $porcentajeActivos / 100) }}"
                                stroke-linecap="round"/>
                    </svg>
                    <span class="text-xs text-gray-500">Tasa de ejecución</span>
                </div>
            </div>
        </div>

        <!-- Tasa de Éxito KPI -->
        <div class="card-quantum p-6 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-void-500/5 rounded-full blur-3xl group-hover:bg-void-500/10 transition-all duration-500"></div>

            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-quantum bg-void-500/20 border border-void-500/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-void-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Éxito</span>
                    </div>
                    <span class="px-2 py-1 bg-void-500/10 text-void-300 text-xs font-semibold rounded-full">
                        Óptimo
                    </span>
                </div>

                <div class="mb-2">
                    <span class="text-4xl font-bold text-white">{{ $tasaExito }}%</span>
                </div>

                <p class="text-sm text-gray-400">Proyectos completados</p>

                <!-- Rating Stars -->
                <div class="mt-4 flex items-center gap-1">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-4 h-4 {{ $i <= round($tasaExito / 20) ? 'text-photon-400' : 'text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section - Maldini Tactics -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Distribución por Estado - Donut Chart -->
    <div class="lg:col-span-2 card-quantum p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-1 h-6 bg-gradient-to-b from-quantum-500 to-void-500 rounded-full"></div>
                <h2 class="text-xl font-semibold text-white">Distribución por Estado</h2>
            </div>
            <div class="text-sm text-gray-400">Tiempo real</div>
        </div>

        <div class="flex items-center justify-center gap-8 flex-wrap">
            <!-- Donut Chart -->
            <div class="relative">
                <svg class="w-64 h-64 transform -rotate-90">
                    @php
                        $total = $totalProyectos > 0 ? $totalProyectos : 1;
                        $activosAngle = ($proyectosActivos / $total) * 360;
                        $inactivosAngle = ($proyectosInactivos / $total) * 360;
                        $cerradosAngle = ($proyectosCerrados / $total) * 360;

                        $radius = 100;
                        $circumference = 2 * 3.14159 * $radius;

                        $activosOffset = 0;
                        $inactivosOffset = ($proyectosActivos / $total) * $circumference;
                        $cerradosOffset = (($proyectosActivos + $proyectosInactivos) / $total) * $circumference;
                    @endphp

                    <!-- Background circle -->
                    <circle cx="128" cy="128" r="{{ $radius }}"
                            stroke="rgba(255,255,255,0.05)"
                            stroke-width="40"
                            fill="none"/>

                    <!-- Activos segment -->
                    <circle cx="128" cy="128" r="{{ $radius }}"
                            stroke="rgb(16, 185, 129)"
                            stroke-width="40"
                            fill="none"
                            stroke-dasharray="{{ ($proyectosActivos / $total) * $circumference }} {{ $circumference }}"
                            stroke-dashoffset="0"
                            class="transition-all duration-500"/>

                    <!-- Inactivos segment -->
                    <circle cx="128" cy="128" r="{{ $radius }}"
                            stroke="rgb(234, 179, 8)"
                            stroke-width="40"
                            fill="none"
                            stroke-dasharray="{{ ($proyectosInactivos / $total) * $circumference }} {{ $circumference }}"
                            stroke-dashoffset="-{{ $inactivosOffset }}"
                            class="transition-all duration-500"/>

                    <!-- Cerrados segment -->
                    <circle cx="128" cy="128" r="{{ $radius }}"
                            stroke="rgb(239, 68, 68)"
                            stroke-width="40"
                            fill="none"
                            stroke-dasharray="{{ ($proyectosCerrados / $total) * $circumference }} {{ $circumference }}"
                            stroke-dashoffset="-{{ $cerradosOffset }}"
                            class="transition-all duration-500"/>
                </svg>

                <!-- Center text -->
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-4xl font-bold text-white">{{ $totalProyectos }}</span>
                    <span class="text-sm text-gray-400">Total</span>
                </div>
            </div>

            <!-- Legend -->
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-4 h-4 rounded bg-green-500"></div>
                    <div>
                        <p class="text-sm font-medium text-white">Activos</p>
                        <p class="text-xs text-gray-400">{{ $proyectosActivos }} proyectos ({{ round(($proyectosActivos / $total) * 100) }}%)</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-4 h-4 rounded bg-yellow-500"></div>
                    <div>
                        <p class="text-sm font-medium text-white">Inactivos</p>
                        <p class="text-xs text-gray-400">{{ $proyectosInactivos }} proyectos ({{ round(($proyectosInactivos / $total) * 100) }}%)</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-4 h-4 rounded bg-red-500"></div>
                    <div>
                        <p class="text-sm font-medium text-white">Cerrados</p>
                        <p class="text-xs text-gray-400">{{ $proyectosCerrados }} proyectos ({{ round(($proyectosCerrados / $total) * 100) }}%)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tendencias - Quick Stats -->
    <div class="card-quantum p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-1 h-6 bg-gradient-to-b from-photon-500 to-quantum-500 rounded-full"></div>
                <h2 class="text-lg font-semibold text-white">Tendencias</h2>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Crecimiento Proyectos -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-400">Crecimiento</span>
                    <span class="text-sm font-semibold text-green-400">+{{ $crecimientoProyectos }}%</span>
                </div>
                <div class="h-2 bg-matter-light rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-green-500 to-green-400 rounded-full"
                         style="width: {{ min($crecimientoProyectos * 5, 100) }}%"></div>
                </div>
            </div>

            <!-- Tasa Ejecución -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-400">Ejecución</span>
                    <span class="text-sm font-semibold text-quantum-400">{{ $porcentajeActivos }}%</span>
                </div>
                <div class="h-2 bg-matter-light rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-quantum-500 to-void-500 rounded-full"
                         style="width: {{ $porcentajeActivos }}%"></div>
                </div>
            </div>

            <!-- Tasa Éxito -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-400">Éxito</span>
                    <span class="text-sm font-semibold text-photon-400">{{ $tasaExito }}%</span>
                </div>
                <div class="h-2 bg-matter-light rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-photon-500 to-quantum-500 rounded-full"
                         style="width: {{ $tasaExito }}%"></div>
                </div>
            </div>

            <!-- Value Growth -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-400">Valor Portfolio</span>
                    <span class="text-sm font-semibold text-void-400">+{{ $crecimiento }}%</span>
                </div>
                <div class="h-2 bg-matter-light rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-void-500 to-photon-500 rounded-full animate-pulse"
                         style="width: {{ min($crecimiento * 10, 100) }}%"></div>
                </div>
            </div>
        </div>

        <!-- Quick Action -->
        <div class="mt-6 pt-6 border-t border-matter-light">
            <a href="{{ route('proyectos.index') }}"
               class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-quantum-500/20 hover:bg-quantum-500/30 border border-quantum-500/30 hover:border-quantum-500 text-quantum-300 hover:text-quantum-200 rounded-quantum text-sm font-medium transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Ver Proyectos
            </a>
        </div>
    </div>
</div>

<!-- Performance Metrics - Maldini Defense -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Performance Score -->
    <div class="card-quantum p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-quantum bg-quantum-500/20 border border-quantum-500/30 flex items-center justify-center">
                <svg class="w-5 h-5 text-quantum-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Performance</h3>
        </div>

        <div class="text-center">
            <div class="text-5xl font-bold text-white mb-2">{{ round(($porcentajeActivos + $tasaExito) / 2) }}</div>
            <div class="text-sm text-gray-400 mb-4">Score General</div>

            <div class="flex justify-center gap-1">
                @for($i = 0; $i < 10; $i++)
                    <div class="w-1.5 h-8 rounded-full {{ $i < round((($porcentajeActivos + $tasaExito) / 2) / 10) ? 'bg-gradient-to-t from-quantum-500 to-void-500' : 'bg-matter-light' }}"></div>
                @endfor
            </div>
        </div>
    </div>

    <!-- Efficiency -->
    <div class="card-quantum p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-quantum bg-green-500/20 border border-green-500/30 flex items-center justify-center">
                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Eficiencia</h3>
        </div>

        <div class="text-center">
            <div class="text-5xl font-bold text-white mb-2">{{ $porcentajeActivos }}%</div>
            <div class="text-sm text-gray-400 mb-4">Tasa Operativa</div>

            <div class="relative pt-1">
                <div class="flex mb-2 items-center justify-between">
                    <div class="text-xs font-semibold inline-block text-green-400">Óptimo</div>
                </div>
                <div class="overflow-hidden h-2 text-xs flex rounded-full bg-matter-light">
                    <div style="width:{{ $porcentajeActivos }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-gradient-to-r from-green-500 to-green-400"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quality -->
    <div class="card-quantum p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-quantum bg-photon-500/20 border border-photon-500/30 flex items-center justify-center">
                <svg class="w-5 h-5 text-photon-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Calidad</h3>
        </div>

        <div class="text-center">
            <div class="text-5xl font-bold text-white mb-2">{{ $tasaExito }}%</div>
            <div class="text-sm text-gray-400 mb-4">Tasa de Éxito</div>

            <div class="flex items-center justify-center gap-2">
                <div class="flex-1 h-1.5 bg-matter-light rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-photon-500 to-quantum-500 rounded-full" style="width: {{ $tasaExito }}%"></div>
                </div>
                <span class="text-xs font-semibold text-photon-400">A+</span>
            </div>
        </div>
    </div>
</div>

@endsection
