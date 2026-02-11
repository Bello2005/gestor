{{-- Tab 4: Alertas — Panel de alertas agrupadas por severidad --}}

@php
    $severityConfig = [
        'critico' => ['label' => 'Críticas', 'color' => 'red', 'pulse' => true],
        'alto' => ['label' => 'Alta Prioridad', 'color' => 'orange', 'pulse' => false],
        'medio' => ['label' => 'Media Prioridad', 'color' => 'amber', 'pulse' => false],
        'informativo' => ['label' => 'Informativas', 'color' => 'quantum', 'pulse' => false],
    ];

    $iconMap = [
        'alert-triangle' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>',
        'file-x' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 2v6h6M9.5 12.5l5 5M14.5 12.5l-5 5"/>',
        'file-warning' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 2v6h6M12 11v4M12 19h.01"/>',
        'file-clock' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 2v6h6M12 14v2l1.5 1.5"/>',
        'clock' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
        'shield-alert' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>',
        'key' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>',
        'info' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
    ];

    $totalAlerts = array_sum(array_map('count', $alerts));
@endphp

<!-- Summary Cards -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
    @foreach($severityConfig as $level => $config)
        @php $count = count($alerts[$level] ?? []); @endphp
        <div class="card-quantum p-4 border-l-4 border-{{ $config['color'] }}-500 hover:scale-105 transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-2xl font-bold text-{{ $config['color'] }}-400 {{ $config['pulse'] && $count > 0 ? 'animate-pulse' : '' }}">{{ $count }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $config['label'] }}</p>
                </div>
                <div class="w-3 h-3 rounded-full bg-{{ $config['color'] }}-500 shadow-lg shadow-{{ $config['color'] }}-500/30 {{ $config['pulse'] && $count > 0 ? 'animate-pulse' : '' }}"></div>
            </div>
        </div>
    @endforeach
</div>

@if($totalAlerts === 0)
    <!-- Empty State -->
    <div class="card-quantum p-16 text-center">
        <div class="w-20 h-20 bg-green-500/10 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-white mb-2">Todo en orden</h3>
        <p class="text-gray-400 max-w-md mx-auto">No hay alertas pendientes. Todos los proyectos cumplen con los requisitos de seguimiento y documentación.</p>
    </div>
@else
    <!-- Alert Groups -->
    @foreach($severityConfig as $level => $config)
        @if(!empty($alerts[$level]))
        <div class="space-y-3">
            <!-- Group Header -->
            <div class="flex items-center gap-3">
                <span class="w-3 h-3 rounded-full bg-{{ $config['color'] }}-500 shadow-lg shadow-{{ $config['color'] }}-500/30 {{ $config['pulse'] && count($alerts[$level]) > 0 ? 'animate-pulse' : '' }}"></span>
                <h3 class="text-lg font-semibold text-white">Alertas {{ $config['label'] }}</h3>
                <span class="px-2 py-0.5 text-xs rounded-full bg-{{ $config['color'] }}-500/20 text-{{ $config['color'] }}-400 border border-{{ $config['color'] }}-500/30">
                    {{ count($alerts[$level]) }}
                </span>
            </div>

            <!-- Alert Cards -->
            @foreach($alerts[$level] as $alert)
                <div class="card-quantum p-4 border-l-4 border-{{ $config['color'] }}-500 hover:border-{{ $config['color'] }}-400 transition-all duration-200 hover:shadow-lg hover:shadow-{{ $config['color'] }}-500/5">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-3">
                            <!-- Icon -->
                            <div class="w-10 h-10 bg-{{ $config['color'] }}-500/15 rounded-quantum flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-5 h-5 text-{{ $config['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    {!! $iconMap[$alert['icon']] ?? $iconMap['alert-triangle'] !!}
                                </svg>
                            </div>

                            <!-- Content -->
                            <div class="min-w-0">
                                <p class="text-white font-medium text-sm">{{ $alert['title'] }}</p>
                                <p class="text-gray-400 text-sm mt-1 leading-relaxed">{{ $alert['message'] }}</p>
                                @if($alert['proyecto_nombre'])
                                    <div class="flex items-center gap-2 mt-2">
                                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                        </svg>
                                        <span class="text-xs text-gray-500">{{ $alert['proyecto_nombre'] }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Action Button -->
                        @if($alert['action_url'])
                            <a href="{{ $alert['action_url'] }}"
                               class="flex-shrink-0 px-3 py-1.5 rounded-quantum text-xs font-medium transition-all duration-200 border
                                      bg-{{ $config['color'] }}-500/10 border-{{ $config['color'] }}-500/30 text-{{ $config['color'] }}-400
                                      hover:bg-{{ $config['color'] }}-500/20 hover:border-{{ $config['color'] }}-500/50">
                                {{ $alert['action_label'] }}
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        @endif
    @endforeach
@endif

<!-- Normativa Reference -->
<div class="card-quantum p-4 bg-quantum-500/5 border border-quantum-500/20">
    <div class="flex items-start gap-3">
        <svg class="w-5 h-5 text-quantum-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <p class="text-sm text-quantum-400 font-medium">Criterios de alertas basados en normativa colombiana</p>
            <p class="text-xs text-gray-500 mt-1">
                Las alertas de documentación se generan conforme a los lineamientos del DNP-SPI y la Ley 1474 de 2011,
                que establecen seguimiento mensual obligatorio con actualizaciones periódicas de evidencias y avances.
            </p>
        </div>
    </div>
</div>
