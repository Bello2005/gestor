@extends('layouts.quantum')

@section('title', 'Configuración')

@section('content')
<div x-data="{
    activeTab: ['profile', 'security'].includes(window.location.hash.replace('#', '')) ? window.location.hash.replace('#', '') : 'profile'
}" class="space-y-6 animate-fadeIn">

    <!-- Header Elegance -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-quantum-500 via-void-500 to-photon-500 bg-clip-text text-transparent">
                Configuración
            </h1>
            <p class="text-gray-400 mt-2">Ajustes personales con precisión cuántica</p>
        </div>
    </div>

    <!-- Success Messages -->
    @if(session('success'))
    <div class="card-quantum p-4 bg-green-500/10 border border-green-500/30 animate-fadeIn">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-green-400">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Tabs Navigation -->
    <div class="card-quantum p-2">
        <div class="flex gap-2">
            <button @click="activeTab = 'profile'"
                    :class="activeTab === 'profile' ? 'bg-gradient-to-r from-quantum-500/20 to-void-500/20 border border-quantum-500/30 text-white' : 'text-gray-400 hover:text-white hover:bg-matter-light'"
                    class="flex-1 px-6 py-3 rounded-quantum transition-all duration-200 flex items-center justify-center gap-2 group">
                <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="font-medium">Perfil</span>
            </button>

            <button @click="activeTab = 'security'"
                    :class="activeTab === 'security' ? 'bg-gradient-to-r from-quantum-500/20 to-void-500/20 border border-quantum-500/30 text-white' : 'text-gray-400 hover:text-white hover:bg-matter-light'"
                    class="flex-1 px-6 py-3 rounded-quantum transition-all duration-200 flex items-center justify-center gap-2 group">
                <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <span class="font-medium">Seguridad</span>
            </button>

        </div>
    </div>

    <!-- Profile Tab -->
    <div x-show="activeTab === 'profile'"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="space-y-6">

        <!-- User Info Card -->
        <div class="card-quantum p-8">
            <div class="flex items-center gap-6 mb-8">
                <div class="relative group">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-quantum-500 to-void-500 flex items-center justify-center text-white font-bold text-3xl ring-4 ring-quantum-500/30 group-hover:ring-quantum-500/50 transition-all duration-300">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div class="absolute bottom-0 right-0 w-6 h-6 bg-green-500 rounded-full border-4 border-matter"></div>
                </div>

                <div class="flex-1">
                    <h3 class="text-2xl font-bold text-white mb-1">{{ $user->name }}</h3>
                    <p class="text-gray-400 mb-2">{{ $user->email }}</p>
                    <div class="flex gap-2">
                        @foreach($user->roles as $role)
                        <span class="badge-quantum badge-primary">{{ ucfirst($role->name) }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Update Profile Form -->
            <form action="{{ route('configuration.profile.update') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            Nombre completo
                        </label>
                        <input type="text"
                               name="name"
                               value="{{ old('name', $user->name) }}"
                               required
                               class="input-quantum">
                        @error('name')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            Correo electrónico
                        </label>
                        <input type="email"
                               name="email"
                               value="{{ old('email', $user->email) }}"
                               required
                               class="input-quantum">
                        @error('email')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="btn-quantum">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>

        <!-- Account Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="card-quantum p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-quantum-500/20 to-void-500/20 rounded-quantum flex items-center justify-center">
                        <svg class="w-6 h-6 text-quantum-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Miembro desde</p>
                        <p class="text-white font-bold">{{ $user->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="card-quantum p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-quantum flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Última actividad</p>
                        <p class="text-white font-bold">{{ $user->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            <div class="card-quantum p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-photon-500/20 to-yellow-500/20 rounded-quantum flex items-center justify-center">
                        <svg class="w-6 h-6 text-photon-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Estado</p>
                        <p class="text-white font-bold">Verificado</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Tab -->
    <div x-show="activeTab === 'security'"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="space-y-6">

        <!-- Change Password -->
        <div class="card-quantum p-8">
            <div class="mb-6">
                <h3 class="text-xl font-bold text-white mb-2">Cambiar Contraseña</h3>
                <p class="text-gray-400">Mantén tu cuenta segura con una contraseña fuerte</p>
            </div>

            <form action="{{ route('configuration.password.update') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Current Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        Contraseña actual
                    </label>
                    <input type="password"
                           name="current_password"
                           required
                           class="input-quantum"
                           placeholder="••••••••">
                    @error('current_password')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- New Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            Nueva contraseña
                        </label>
                        <input type="password"
                               name="new_password"
                               required
                               minlength="8"
                               class="input-quantum"
                               placeholder="••••••••">
                        <p class="mt-1 text-xs text-gray-400">Mínimo 8 caracteres</p>
                        @error('new_password')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            Confirmar contraseña
                        </label>
                        <input type="password"
                               name="new_password_confirmation"
                               required
                               class="input-quantum"
                               placeholder="••••••••">
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="btn-quantum bg-gradient-to-r from-green-500 to-emerald-600">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Actualizar Contraseña
                    </button>
                </div>
            </form>
        </div>

        <!-- Security Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="card-quantum p-6 border-l-4 border-green-500">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-green-500/20 rounded-quantum flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold mb-1">Contraseña segura</h4>
                        <p class="text-gray-400 text-sm">Usa mayúsculas, minúsculas, números y símbolos</p>
                    </div>
                </div>
            </div>

            <div class="card-quantum p-6 border-l-4 border-blue-500">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-blue-500/20 rounded-quantum flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold mb-1">Sesión protegida</h4>
                        <p class="text-gray-400 text-sm">Tu sesión expira automáticamente por seguridad</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
