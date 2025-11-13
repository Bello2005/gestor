<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>QUANTUM - Gestión a la Velocidad del Pensamiento</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">

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

        /* Password toggle icon */
        .password-toggle-btn {
            transition: all 0.2s ease;
        }

        .password-toggle-btn:hover {
            transform: scale(1.1);
            color: hsl(195, 100%, 50%);
        }
    </style>
</head>
<body class="quantum-bg min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    <!-- Animated particles background -->
    <div class="particles" id="particles"></div>

    <!-- Main Login Container -->
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <span class="text-gray-300">Velocidad cuántica de procesamiento</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-void-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-void-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <span class="text-gray-300">Seguridad de nivel enterprise</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-photon-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-photon-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <span class="text-gray-300">Colaboración en tiempo real</span>
                    </div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="w-full">
                <div class="glass-card rounded-quantum-xl p-8 md:p-12 scale-in">
                    <!-- Header -->
                    <div class="mb-8">
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

                        <h2 class="text-3xl font-bold text-white mb-2">Bienvenido de nuevo</h2>
                        <p class="text-gray-400">Ingresa tus credenciales para continuar</p>
                    </div>

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-6" x-data="{ showPassword: false }">
                        @csrf

                        <!-- Email Input -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                                Correo electrónico
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
                                    class="input-quantum pl-12 w-full"
                                    placeholder="tu@email.com"
                                    required
                                    autofocus
                                    autocomplete="username"
                                >
                            </div>
                        </div>

                        <!-- Password Input -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                                Contraseña
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <input
                                    :type="showPassword ? 'text' : 'password'"
                                    name="password"
                                    id="password"
                                    class="input-quantum pl-12 pr-12 w-full"
                                    placeholder="••••••••"
                                    required
                                    autocomplete="current-password"
                                >
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center password-toggle-btn"
                                >
                                    <svg x-show="!showPassword" class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <svg x-show="showPassword" class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Forgot Password -->
                        <div class="flex items-center justify-end">
                            <a href="{{ route('password.request') }}" class="text-sm text-quantum-400 hover:text-quantum-300 transition-colors">
                                ¿Olvidaste tu contraseña?
                            </a>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn-quantum w-full">
                            Iniciar Sesión
                        </button>
                    </form>

                    <!-- Divider -->
                    <div class="relative my-8">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-matter-light"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-matter/50 text-gray-400">¿No tienes una cuenta?</span>
                        </div>
                    </div>

                    <!-- Register Link -->
                    <a href="{{ route('access-requests.create') }}" class="btn-ghost w-full text-center block">
                        Solicitar acceso
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
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

    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de autenticación',
                    text: '{{ $errors->first() }}',
                    background: 'hsl(240, 12%, 10%)',
                    color: '#fff',
                    confirmButtonColor: 'hsl(0, 84%, 60%)',
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
