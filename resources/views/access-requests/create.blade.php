<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Solicitar Acceso - QUANTUM</title>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- SweetAlert2 para notificaciones -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Animated gradient background */
        @keyframes gradientFlow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .quantum-bg {
            background: linear-gradient(135deg,
                hsl(240, 15%, 6%) 0%,
                hsl(270, 80%, 10%) 25%,
                hsl(195, 100%, 15%) 50%,
                hsl(270, 80%, 10%) 75%,
                hsl(240, 15%, 6%) 100%
            );
            background-size: 400% 400%;
            animation: gradientFlow 15s ease infinite;
        }

        /* Particles effect */
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .particle {
            position: absolute;
            width: 2px;
            height: 2px;
            background: rgba(0, 191, 255, 0.5);
            border-radius: 50%;
            animation: float 10s infinite ease-in-out;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) translateX(0); opacity: 0; }
            50% { opacity: 1; }
            100% { transform: translateY(-100vh) translateX(50px); opacity: 0; }
        }

        /* Glassmorphism card */
        .glass-card {
            background: rgba(21, 21, 31, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3),
                        0 0 80px rgba(0, 191, 255, 0.15);
        }

        /* Glow effect on focus */
        .input-quantum:focus {
            box-shadow: 0 0 0 3px rgba(0, 191, 255, 0.3),
                        0 0 20px rgba(0, 191, 255, 0.2);
        }

        /* Logo animation */
        @keyframes logoFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(2deg); }
        }

        .logo-quantum {
            animation: logoFloat 6s ease-in-out infinite;
        }

        /* Button hover effect */
        .btn-quantum:hover {
            box-shadow: 0 0 40px rgba(0, 191, 255, 0.5),
                        0 8px 25px rgba(0, 0, 0, 0.3);
        }

        /* Scale in animation */
        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .scale-in {
            animation: scaleIn 0.5s ease-out;
        }

        .text-gradient {
            background: linear-gradient(135deg, hsl(195, 100%, 50%), hsl(270, 80%, 60%));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="quantum-bg min-h-screen flex items-start sm:items-center justify-center px-4 py-6 sm:p-4 relative overflow-y-auto overflow-x-hidden">

    <!-- Animated particles background -->
    <div class="particles" id="particles"></div>

    <!-- Main Container -->
    <div class="w-full max-w-6xl flex items-center justify-center relative z-10">
        <div class="w-full grid md:grid-cols-2 gap-8 items-center">

            <!-- Left Side - Branding -->
            <div class="hidden md:flex flex-col items-center justify-center space-y-8 text-center">
                <!-- Logo -->
                <div class="logo-quantum">
                    <svg width="120" height="120" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="quantumGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:hsl(195, 100%, 50%);stop-opacity:1" />
                                <stop offset="100%" style="stop-color:hsl(270, 80%, 60%);stop-opacity:1" />
                            </linearGradient>
                        </defs>
                        <!-- Quantum Particles Structure -->
                        <circle cx="60" cy="30" r="8" fill="url(#quantumGradient)"/>
                        <circle cx="30" cy="60" r="8" fill="url(#quantumGradient)"/>
                        <circle cx="90" cy="60" r="8" fill="url(#quantumGradient)"/>
                        <circle cx="60" cy="90" r="8" fill="url(#quantumGradient)"/>
                        <!-- Connection Lines -->
                        <line x1="60" y1="30" x2="30" y2="60" stroke="url(#quantumGradient)" stroke-width="2"/>
                        <line x1="60" y1="30" x2="90" y2="60" stroke="url(#quantumGradient)" stroke-width="2"/>
                        <line x1="30" y1="60" x2="60" y2="90" stroke="url(#quantumGradient)" stroke-width="2"/>
                        <line x1="90" y1="60" x2="60" y2="90" stroke="url(#quantumGradient)" stroke-width="2"/>
                        <line x1="30" y1="60" x2="90" y2="60" stroke="url(#quantumGradient)" stroke-width="2"/>
                        <!-- Central Core -->
                        <circle cx="60" cy="60" r="15" fill="none" stroke="url(#quantumGradient)" stroke-width="3"/>
                        <circle cx="60" cy="60" r="5" fill="url(#quantumGradient)"/>
                    </svg>
                </div>

                <!-- Brand Name -->
                <div>
                    <h1 class="text-6xl font-bold text-gradient mb-2">QUANTUM</h1>
                    <p class="text-gray-400 text-lg">Gestión a la Velocidad del Pensamiento</p>
                </div>

                <!-- Features -->
                <div class="space-y-4 text-left">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-quantum-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-quantum-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <span class="text-gray-300">Acceso seguro y rápido</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-void-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-void-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                        </div>
                        <span class="text-gray-300">Revisión manual por el equipo</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-photon-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-photon-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span class="text-gray-300">Notificación por correo</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-quantum-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-quantum-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                        </div>
                        <span class="text-gray-300">Soporte personalizado</span>
                    </div>
                </div>
            </div>

            <!-- Right Side - Request Form -->
            <div class="w-full">
                <div class="glass-card rounded-quantum-xl p-5 sm:p-8 md:p-12 scale-in">
                    <!-- Header -->
                    <div class="mb-5 sm:mb-8">
                        <!-- Mobile Logo -->
                        <div class="md:hidden flex justify-center mb-6">
                            <svg width="60" height="60" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <linearGradient id="mobileLogo" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" style="stop-color:hsl(195, 100%, 50%);stop-opacity:1" />
                                        <stop offset="100%" style="stop-color:hsl(270, 80%, 60%);stop-opacity:1" />
                                    </linearGradient>
                                </defs>
                                <circle cx="60" cy="30" r="8" fill="url(#mobileLogo)"/>
                                <circle cx="30" cy="60" r="8" fill="url(#mobileLogo)"/>
                                <circle cx="90" cy="60" r="8" fill="url(#mobileLogo)"/>
                                <circle cx="60" cy="90" r="8" fill="url(#mobileLogo)"/>
                                <line x1="60" y1="30" x2="30" y2="60" stroke="url(#mobileLogo)" stroke-width="2"/>
                                <line x1="60" y1="30" x2="90" y2="60" stroke="url(#mobileLogo)" stroke-width="2"/>
                                <line x1="30" y1="60" x2="60" y2="90" stroke="url(#mobileLogo)" stroke-width="2"/>
                                <line x1="90" y1="60" x2="60" y2="90" stroke="url(#mobileLogo)" stroke-width="2"/>
                                <circle cx="60" cy="60" r="15" fill="none" stroke="url(#mobileLogo)" stroke-width="3"/>
                                <circle cx="60" cy="60" r="5" fill="url(#mobileLogo)"/>
                            </svg>
                        </div>

                        <h2 class="text-2xl sm:text-3xl font-bold text-white mb-2">Solicitar Acceso</h2>
                        <p class="text-gray-400">Completa el formulario para solicitar acceso al sistema</p>
                    </div>

                    <!-- Request Form -->
                    <form method="POST" action="{{ route('access-requests.store') }}" class="space-y-4 sm:space-y-6">
                        @csrf

                        <!-- Nombre Completo -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                                Nombre Completo <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <input
                                    type="text"
                                    name="name"
                                    id="name"
                                    class="input-quantum pl-12 w-full @error('name') border-red-500 @enderror"
                                    placeholder="Juan Pérez"
                                    value="{{ old('name') }}"
                                    required
                                    autofocus
                                >
                            </div>
                            @error('name')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Correo Electrónico -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                                Correo Electrónico <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    class="input-quantum pl-12 w-full @error('email') border-red-500 @enderror"
                                    placeholder="tu@email.com"
                                    value="{{ old('email') }}"
                                    required
                                >
                            </div>
                            @error('email')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            @if($errors->any())
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        Swal.fire({
                                            icon: 'error',
                                            title: '¡Error!',
                                            text: '{{ $errors->first() }}',
                                            background: 'hsl(240, 12%, 10%)',
                                            color: '#fff',
                                            confirmButtonColor: 'hsl(0, 84%, 60%)',
                                        });
                                    });
                                </script>
                            @endif
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-300 mb-2">
                                Teléfono <span class="text-gray-500 text-xs font-normal">(opcional)</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                                <input
                                    type="tel"
                                    name="phone"
                                    id="phone"
                                    class="input-quantum pl-12 w-full @error('phone') border-red-500 @enderror"
                                    placeholder="+57 300 000 0000"
                                    value="{{ old('phone') }}"
                                >
                            </div>
                            @error('phone')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Razón -->
                        <div>
                            <label for="reason" class="block text-sm font-medium text-gray-300 mb-2">
                                ¿Por qué necesitas acceso al sistema? <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute top-4 left-4 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                    </svg>
                                </div>
                                <textarea
                                    name="reason"
                                    id="reason"
                                    rows="4"
                                    class="input-quantum pl-12 pt-3 w-full @error('reason') border-red-500 @enderror"
                                    placeholder="Describe el motivo por el cual necesitas acceso al sistema..."
                                    required
                                >{{ old('reason') }}</textarea>
                            </div>
                            @error('reason')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn-quantum w-full flex items-center justify-center gap-2 group">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            <span>Enviar Solicitud</span>
                        </button>
                    </form>

                    <!-- Divider -->
                    <div class="relative my-5 sm:my-8">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-matter-light"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-matter/50 text-gray-400">¿Ya tienes una cuenta?</span>
                        </div>
                    </div>

                    <!-- Login Link -->
                    <a href="{{ route('login') }}" class="btn-ghost w-full text-center block">
                        Volver al Login
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Messages -->
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session("success") }}',
                    background: 'hsl(240, 12%, 10%)',
                    color: '#fff',
                    confirmButtonColor: 'hsl(195, 100%, 50%)',
                    timer: 3000,
                    timerProgressBar: true,
                });
            });
        </script>
    @endif

    <!-- Particles Script -->
    <script>
        // Create floating particles
        const particlesContainer = document.getElementById('particles');
        const particleCount = 50;

        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 10 + 's';
            particle.style.animationDuration = (Math.random() * 10 + 10) + 's';
            particlesContainer.appendChild(particle);
        }
    </script>
</body>
</html>
