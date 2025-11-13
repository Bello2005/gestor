@php
    use Illuminate\Support\Str;
@endphp

@extends('layouts.quantum')

@section('title', 'Detalle de Auditoría')

@section('content')
<div class="space-y-6 animate-fadeIn">
    <!-- Header QUANTUM -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-quantum-500 via-void-500 to-photon-500 bg-clip-text text-transparent">
                Detalle de Auditoría
            </h1>
            <p class="text-gray-400 mt-2">Registro #{{ $audit->id }}</p>
        </div>

        <a href="{{ route('audit.index') }}" 
           class="btn-quantum flex items-center gap-2 group">
            <svg class="w-5 h-5 transition-transform group-hover:scale-110 rotate-0 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Volver a Auditoría</span>
        </a>
    </div>

    <!-- Información General -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Información General Card -->
        <div class="card-quantum p-6">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-matter-light">
                <div class="w-12 h-12 bg-gradient-to-br from-quantum-500/20 to-void-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-quantum-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">Información General</h3>
                    <p class="text-sm text-gray-400">Datos del registro de auditoría</p>
                </div>
            </div>

            <div class="space-y-4">
                <!-- ID -->
                <div class="flex items-center justify-between py-3 border-b border-matter-light">
                    <span class="text-sm font-medium text-gray-400 uppercase tracking-wider">ID</span>
                    <span class="text-white font-semibold">#{{ $audit->id }}</span>
                </div>

                <!-- Tabla -->
                <div class="flex items-center justify-between py-3 border-b border-matter-light">
                    <span class="text-sm font-medium text-gray-400 uppercase tracking-wider">Tabla</span>
                    <span class="text-white font-semibold">{{ ucfirst(str_replace('_', ' ', $audit->table_name)) }}</span>
                </div>

                <!-- Operación -->
                <div class="flex items-center justify-between py-3 border-b border-matter-light">
                    <span class="text-sm font-medium text-gray-400 uppercase tracking-wider">Operación</span>
                    @if($audit->operation == 'DELETE')
                        <span class="badge-quantum badge-danger">{{ $audit->operation }}</span>
                    @elseif($audit->operation == 'INSERT')
                        <span class="badge-quantum badge-success">{{ $audit->operation }}</span>
                    @else
                        <span class="badge-quantum badge-primary">{{ $audit->operation }}</span>
                    @endif
                </div>

                <!-- ID del Registro -->
                <div class="flex items-center justify-between py-3">
                    <span class="text-sm font-medium text-gray-400 uppercase tracking-wider">ID del Registro</span>
                    <span class="text-white font-semibold">#{{ $audit->record_id }}</span>
                </div>
            </div>
        </div>

        <!-- Metadata Card -->
        <div class="card-quantum p-6">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-matter-light">
                <div class="w-12 h-12 bg-gradient-to-br from-void-500/20 to-photon-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-void-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">Metadata</h3>
                    <p class="text-sm text-gray-400">Información del usuario y contexto</p>
                </div>
            </div>

            <div class="space-y-4">
                <!-- Usuario -->
                <div class="flex items-center justify-between py-3 border-b border-matter-light">
                    <span class="text-sm font-medium text-gray-400 uppercase tracking-wider">Usuario</span>
                    <span class="text-white font-semibold">{{ $audit->user_name ?? 'Sistema' }}</span>
                </div>

                <!-- IP -->
                <div class="flex items-center justify-between py-3 border-b border-matter-light">
                    <span class="text-sm font-medium text-gray-400 uppercase tracking-wider">Dirección IP</span>
                    <span class="text-white font-mono text-sm">{{ $audit->ip_address }}</span>
                </div>

                <!-- Navegador -->
                <div class="flex items-start justify-between py-3 border-b border-matter-light">
                    <span class="text-sm font-medium text-gray-400 uppercase tracking-wider pt-1">Navegador</span>
                    <span class="text-white text-sm text-right max-w-xs truncate" title="{{ $audit->user_agent }}">{{ Str::limit($audit->user_agent, 40) }}</span>
                </div>

                <!-- Fecha -->
                <div class="flex items-center justify-between py-3">
                    <span class="text-sm font-medium text-gray-400 uppercase tracking-wider">Fecha</span>
                    <div class="text-right">
                        <span class="text-white font-semibold block">{{ $audit->created_at->format('d/m/Y') }}</span>
                        <span class="text-gray-400 text-xs">{{ $audit->created_at->format('H:i:s') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cambios Realizados -->
    @if($audit->old_values || $audit->new_values)
        @php
            $oldValues = is_array($audit->old_values) ? $audit->old_values : (json_decode($audit->old_values, true) ?? []);
            $newValues = is_array($audit->new_values) ? $audit->new_values : (json_decode($audit->new_values, true) ?? []);
            
            // Asegurarse de que cargar_evidencias sea siempre un array
            if (isset($oldValues['cargar_evidencias']) && is_string($oldValues['cargar_evidencias'])) {
                $oldValues['cargar_evidencias'] = json_decode($oldValues['cargar_evidencias'], true) ?? [];
            }
            if (isset($newValues['cargar_evidencias']) && is_string($newValues['cargar_evidencias'])) {
                $newValues['cargar_evidencias'] = json_decode($newValues['cargar_evidencias'], true) ?? [];
            }
            
            $allFields = array_unique(array_merge(array_keys($oldValues), array_keys($newValues)));
            sort($allFields);
        @endphp

        <div class="card-quantum overflow-hidden">
            <div class="p-6 border-b border-matter-light">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-photon-500/20 to-yellow-500/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-photon-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Cambios Realizados</h3>
                        <p class="text-sm text-gray-400">{{ count($allFields) }} campo(s) modificado(s)</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-space-500/50 border-b border-matter-light">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Campo</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Valor Anterior</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Valor Nuevo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-matter-light">
                        @foreach($allFields as $field)
                            @php
                                $hasChanged = isset($oldValues[$field], $newValues[$field]) && $oldValues[$field] !== $newValues[$field];
                                $oldValue = null;
                                $newValue = null;
                                
                                // Procesar valor anterior
                                if (isset($oldValues[$field])) {
                                    if ($field === 'cargar_evidencias') {
                                        if (is_array($oldValues[$field])) {
                                            $oldValue = implode(', ', array_map('basename', $oldValues[$field]));
                                        } else {
                                            $oldValue = 'N/A';
                                        }
                                    } else {
                                        $oldValue = is_array($oldValues[$field]) ? json_encode($oldValues[$field], JSON_PRETTY_PRINT) : $oldValues[$field];
                                    }
                                } else {
                                    $oldValue = 'N/A';
                                }
                                
                                // Procesar valor nuevo
                                if (isset($newValues[$field])) {
                                    if ($field === 'cargar_evidencias') {
                                        if (is_array($newValues[$field])) {
                                            $newValue = implode(', ', array_map('basename', $newValues[$field]));
                                        } elseif (is_string($newValues[$field])) {
                                            $evidencias = json_decode($newValues[$field], true) ?? [];
                                            $newValue = is_array($evidencias) ? implode(', ', array_map('basename', $evidencias)) : 'N/A';
                                        } else {
                                            $newValue = 'N/A';
                                        }
                                    } else {
                                        $newValue = is_array($newValues[$field]) ? json_encode($newValues[$field], JSON_PRETTY_PRINT) : $newValues[$field];
                                    }
                                } else {
                                    $newValue = 'N/A';
                                }
                            @endphp
                            <tr class="hover:bg-matter-light/50 transition-colors duration-200">
                                <td class="px-6 py-4">
                                    <span class="text-white font-medium">{{ ucfirst(str_replace('_', ' ', $field)) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="max-w-md">
                                        <span class="inline-block px-3 py-1.5 rounded-lg text-sm font-mono {{ $hasChanged ? 'bg-yellow-500/10 text-yellow-400 border border-yellow-500/30' : 'bg-matter-light text-gray-300' }}">
                                            {{ Str::limit($oldValue, 60) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="max-w-md">
                                        <span class="inline-block px-3 py-1.5 rounded-lg text-sm font-mono {{ $hasChanged ? 'bg-green-500/10 text-green-400 border border-green-500/30' : 'bg-matter-light text-gray-300' }}">
                                            {{ Str::limit($newValue, 60) }}
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- No hay cambios -->
        <div class="card-quantum p-12 text-center">
            <div class="w-20 h-20 bg-gradient-to-br from-gray-500/20 to-slate-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <p class="text-gray-400 text-lg font-medium">No se registraron cambios en este evento</p>
            <p class="text-gray-500 text-sm mt-2">Este registro de auditoría no contiene información de valores anteriores o nuevos</p>
        </div>
    @endif

</div>
@endsection