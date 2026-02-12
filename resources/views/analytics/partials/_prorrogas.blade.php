{{-- Tab 5: Prórrogas — Panel completo de gestión de prórrogas --}}

{{-- KPI Summary Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    {{-- Pendientes --}}
    <div class="card-quantum p-5 border-l-4 border-amber-500 group hover:scale-[1.02] transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Pendientes</p>
                <p class="text-3xl font-bold text-amber-400 {{ $kpis['pendientes'] > 0 ? 'animate-pulse' : '' }}">{{ $kpis['pendientes'] }}</p>
            </div>
            <div class="w-12 h-12 bg-amber-500/10 rounded-quantum flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        @if($kpis['pendientes'] > 0)
        <p class="text-xs text-amber-400/70 mt-2">Requieren revisión</p>
        @endif
    </div>

    {{-- Aprobadas --}}
    <div class="card-quantum p-5 border-l-4 border-green-500 group hover:scale-[1.02] transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Aprobadas</p>
                <p class="text-3xl font-bold text-green-400">{{ $kpis['aprobadas'] }}</p>
            </div>
            <div class="w-12 h-12 bg-green-500/10 rounded-quantum flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Rechazadas --}}
    <div class="card-quantum p-5 border-l-4 border-red-500 group hover:scale-[1.02] transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Rechazadas</p>
                <p class="text-3xl font-bold text-red-400">{{ $kpis['rechazadas'] }}</p>
            </div>
            <div class="w-12 h-12 bg-red-500/10 rounded-quantum flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Promedio Días --}}
    <div class="card-quantum p-5 border-l-4 border-quantum-500 group hover:scale-[1.02] transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Promedio Días</p>
                <p class="text-3xl font-bold text-quantum-400">{{ $kpis['avg_dias'] }}<span class="text-lg text-gray-500 ml-1">d</span></p>
            </div>
            <div class="w-12 h-12 bg-quantum-500/10 rounded-quantum flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-quantum-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
        @if($kpis['total'] > 0)
        <p class="text-xs text-gray-500 mt-2">de {{ $kpis['total'] }} solicitudes</p>
        @endif
    </div>
</div>

{{-- Prórrogas List --}}
@if($prorrogas->isEmpty())
    {{-- Empty State --}}
    <div class="card-quantum p-12 text-center">
        <div class="w-20 h-20 bg-quantum-500/10 rounded-full mx-auto mb-4 flex items-center justify-center">
            <svg class="w-10 h-10 text-quantum-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-white mb-2">Sin solicitudes de prórroga</h3>
        <p class="text-gray-400 text-sm max-w-md mx-auto">No se han registrado solicitudes de prórroga aún. Las solicitudes aparecerán aquí cuando los gestores las creen desde los proyectos.</p>
    </div>
@else
    <div class="space-y-3">
        @foreach($prorrogas as $prorroga)
        @php
            $causaColors = [
                'fuerza_mayor' => ['border' => 'border-red-500/40', 'bg' => 'bg-red-500/10', 'text' => 'text-red-400', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                'caso_fortuito' => ['border' => 'border-orange-500/40', 'bg' => 'bg-orange-500/10', 'text' => 'text-orange-400', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
                'necesidad_servicio' => ['border' => 'border-blue-500/40', 'bg' => 'bg-blue-500/10', 'text' => 'text-blue-400', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
                'mutuo_acuerdo' => ['border' => 'border-green-500/40', 'bg' => 'bg-green-500/10', 'text' => 'text-green-400', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
            ];
            $estadoStyles = [
                'pendiente' => ['bg' => 'bg-amber-500/15', 'text' => 'text-amber-400', 'border' => 'border-amber-500/30', 'dot' => 'bg-amber-500'],
                'aprobada'  => ['bg' => 'bg-green-500/15', 'text' => 'text-green-400', 'border' => 'border-green-500/30', 'dot' => 'bg-green-500'],
                'rechazada' => ['bg' => 'bg-red-500/15', 'text' => 'text-red-400', 'border' => 'border-red-500/30', 'dot' => 'bg-red-500'],
            ];
            $causa = $causaColors[$prorroga->causa_tipo] ?? $causaColors['necesidad_servicio'];
            $estado = $estadoStyles[$prorroga->estado] ?? $estadoStyles['pendiente'];
            $isPending = $prorroga->estado === 'pendiente';
        @endphp

        <div class="card-quantum overflow-hidden hover:shadow-lg hover:shadow-matter-light/5 transition-all duration-300 {{ $isPending ? 'border-l-4 border-amber-500/60' : '' }}"
             x-data="{ expanded: false }">
            {{-- Card Header --}}
            <div class="p-5 cursor-pointer" @click="expanded = !expanded">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                    {{-- Left: Project + Causa --}}
                    <div class="flex items-start gap-3 flex-1 min-w-0">
                        {{-- Causa Icon --}}
                        <div class="w-10 h-10 {{ $causa['bg'] }} rounded-quantum flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-5 h-5 {{ $causa['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $causa['icon'] }}"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h4 class="text-white font-semibold text-sm truncate">{{ $prorroga->proyecto->nombre_del_proyecto ?? 'Proyecto' }}</h4>
                                <span class="px-2 py-0.5 text-[10px] uppercase font-bold rounded-full {{ $prorroga->tipo_solicitud === 'prorroga' ? 'bg-quantum-500/20 text-quantum-400 border border-quantum-500/30' : 'bg-amber-500/20 text-amber-400 border border-amber-500/30' }}">
                                    {{ $prorroga->tipo_solicitud === 'prorroga' ? 'Prórroga' : 'Suspensión' }}
                                </span>
                            </div>
                            <div class="flex items-center gap-3 mt-1.5 text-xs text-gray-400">
                                <span class="{{ $causa['text'] }} font-medium">{{ $prorroga->causa_tipo_label }}</span>
                                <span class="text-gray-600">|</span>
                                <span>{{ $prorroga->solicitante->full_name ?? 'N/A' }}</span>
                                <span class="text-gray-600">|</span>
                                <span>{{ $prorroga->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Right: Status + Days + Chevron --}}
                    <div class="flex items-center gap-3 sm:gap-4 flex-shrink-0">
                        {{-- Days Badge --}}
                        <div class="text-center">
                            <span class="text-lg font-bold text-white">{{ $prorroga->dias_solicitados }}</span>
                            <span class="text-[10px] text-gray-500 block -mt-0.5">días</span>
                        </div>

                        {{-- Date Range --}}
                        <div class="hidden md:flex flex-col items-center gap-0.5 text-[11px]">
                            <span class="text-gray-500">{{ $prorroga->fecha_fin_original->format('d/m/Y') }}</span>
                            <svg class="w-3 h-3 text-quantum-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                            </svg>
                            <span class="text-quantum-400 font-medium">{{ $prorroga->fecha_fin_propuesta->format('d/m/Y') }}</span>
                        </div>

                        {{-- Status Badge --}}
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full {{ $estado['bg'] }} {{ $estado['text'] }} border {{ $estado['border'] }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $estado['dot'] }} {{ $isPending ? 'animate-pulse' : '' }}"></span>
                            {{ $prorroga->estado_label }}
                        </span>

                        {{-- Chevron --}}
                        <svg class="w-5 h-5 text-gray-500 transition-transform duration-200" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Expanded Detail --}}
            <div x-show="expanded" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                 class="border-t border-matter-light/30">
                <div class="p-5 space-y-4">
                    {{-- Info Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Justificación --}}
                        <div class="md:col-span-2">
                            <label class="text-[11px] uppercase tracking-wider text-gray-500 font-semibold mb-1.5 block">Justificación</label>
                            <p class="text-sm text-gray-300 leading-relaxed bg-matter-light/20 rounded-quantum p-3">{{ $prorroga->justificacion }}</p>
                        </div>

                        {{-- Fecha Range --}}
                        <div>
                            <label class="text-[11px] uppercase tracking-wider text-gray-500 font-semibold mb-1.5 block">Rango de fechas</label>
                            <div class="flex items-center gap-2 text-sm">
                                <span class="text-gray-400">{{ $prorroga->fecha_fin_original->format('d/m/Y') }}</span>
                                <svg class="w-4 h-4 text-quantum-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                                <span class="text-quantum-400 font-medium">{{ $prorroga->fecha_fin_propuesta->format('d/m/Y') }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full bg-quantum-500/15 text-quantum-400 font-medium ml-1">+{{ $prorroga->dias_solicitados }}d</span>
                            </div>
                        </div>

                        {{-- Solicitante --}}
                        <div>
                            <label class="text-[11px] uppercase tracking-wider text-gray-500 font-semibold mb-1.5 block">Solicitante</label>
                            <p class="text-sm text-gray-300">{{ $prorroga->solicitante->full_name ?? 'N/A' }} <span class="text-gray-500">· {{ $prorroga->created_at->format('d/m/Y H:i') }}</span></p>
                        </div>

                        @if($prorroga->causa_subtipo)
                        <div>
                            <label class="text-[11px] uppercase tracking-wider text-gray-500 font-semibold mb-1.5 block">Subcategoría</label>
                            <p class="text-sm text-gray-300">{{ $prorroga->causa_subtipo_label }}</p>
                        </div>
                        @endif

                        @if($prorroga->departamento_afectado)
                        <div>
                            <label class="text-[11px] uppercase tracking-wider text-gray-500 font-semibold mb-1.5 block">Departamento</label>
                            <p class="text-sm text-gray-300">{{ $prorroga->departamento_afectado }}</p>
                        </div>
                        @endif

                        @if($prorroga->impacto_descripcion)
                        <div class="md:col-span-2">
                            <label class="text-[11px] uppercase tracking-wider text-gray-500 font-semibold mb-1.5 block">Impacto</label>
                            <p class="text-sm text-gray-300 bg-matter-light/20 rounded-quantum p-3">{{ $prorroga->impacto_descripcion }}</p>
                        </div>
                        @endif

                        @if($prorroga->referencia_ideam)
                        <div>
                            <label class="text-[11px] uppercase tracking-wider text-gray-500 font-semibold mb-1.5 block">Referencia IDEAM</label>
                            <p class="text-sm text-gray-300">{{ $prorroga->referencia_ideam }}</p>
                        </div>
                        @endif

                        @if($prorroga->referencia_declaratoria)
                        <div>
                            <label class="text-[11px] uppercase tracking-wider text-gray-500 font-semibold mb-1.5 block">Referencia Declaratoria</label>
                            <p class="text-sm text-gray-300">{{ $prorroga->referencia_declaratoria }}</p>
                        </div>
                        @endif
                    </div>

                    {{-- Evidence --}}
                    @if($prorroga->evidencia_path)
                    <div class="flex items-center gap-2">
                        <a href="{{ route('prorrogas.download.evidencia', $prorroga) }}" class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-medium rounded-quantum bg-quantum-500/10 text-quantum-400 border border-quantum-500/20 hover:bg-quantum-500/20 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                            {{ $prorroga->evidencia_nombre_original ?? 'Evidencia adjunta' }}
                        </a>
                    </div>
                    @endif

                    {{-- Decision Info (if resolved) --}}
                    @if($prorroga->estado !== 'pendiente' && $prorroga->decision_comentario)
                    <div class="p-3 rounded-quantum {{ $prorroga->estado === 'aprobada' ? 'bg-green-500/5 border border-green-500/20' : 'bg-red-500/5 border border-red-500/20' }}">
                        <div class="flex items-center gap-2 mb-1.5">
                            <span class="text-[11px] uppercase tracking-wider font-semibold {{ $prorroga->estado === 'aprobada' ? 'text-green-400' : 'text-red-400' }}">
                                {{ $prorroga->estado === 'aprobada' ? 'Aprobada' : 'Rechazada' }} por
                            </span>
                            <span class="text-xs text-gray-400">
                                {{ $prorroga->aprobador->full_name ?? $prorroga->rechazador->full_name ?? 'Admin' }}
                                · {{ ($prorroga->aprobado_en ?? $prorroga->rechazado_en)?->format('d/m/Y H:i') }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-300">{{ $prorroga->decision_comentario }}</p>
                    </div>
                    @endif

                    {{-- Action Buttons (admin only, pending only) --}}
                    @if($isPending && auth()->user()->hasRole('admin'))
                    <div class="flex items-center gap-3 pt-2 border-t border-matter-light/20">
                        <button @click="approveProrroga({{ $prorroga->id }})"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-quantum bg-green-500/10 text-green-400 border border-green-500/30 hover:bg-green-500/20 hover:shadow-lg hover:shadow-green-500/10 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Aprobar
                        </button>
                        <button @click="rejectProrroga({{ $prorroga->id }})"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-quantum bg-red-500/10 text-red-400 border border-red-500/30 hover:bg-red-500/20 hover:shadow-lg hover:shadow-red-500/10 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Rechazar
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif
