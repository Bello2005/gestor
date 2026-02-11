{{-- Spinner Quantum: reutilizable en todos los tabs de Vigilancia & Riesgo --}}
<div class="card-quantum p-16 border border-quantum-500/20">
    <div class="flex flex-col items-center justify-center gap-8">
        <div class="relative flex items-center justify-center">
            {{-- Glow fondo --}}
            <div class="absolute w-24 h-24 rounded-full bg-gradient-to-br from-quantum-500/20 to-void-500/20 blur-2xl animate-pulse"></div>
            {{-- Anillo exterior --}}
            <div class="relative w-20 h-20 rounded-full border-2 border-transparent border-t-quantum-500 border-r-void-500/80 animate-spin" style="animation-duration: 1.2s;"></div>
            {{-- Anillo interior (sentido contrario) --}}
            <div class="absolute w-14 h-14 rounded-full border-2 border-transparent border-b-photon-500/70 border-l-quantum-500/80 animate-spin" style="animation-duration: 0.9s; animation-direction: reverse;"></div>
            {{-- Núcleo --}}
            <div class="absolute w-4 h-4 rounded-full bg-gradient-to-br from-quantum-500 to-void-500 shadow-lg shadow-quantum-500/40 animate-pulse"></div>
        </div>
        <p class="text-gray-400 text-sm font-medium tracking-wide">{{ $message ?? 'Cargando...' }}</p>
    </div>
</div>
