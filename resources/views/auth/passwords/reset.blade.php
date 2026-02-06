<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Restablecer Contraseña - QUANTUM</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
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

        .glass-card {
            background: rgba(21, 21, 31, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3),
                        0 0 80px rgba(0, 191, 255, 0.15);
        }

        .input-quantum:focus {
            box-shadow: 0 0 0 3px rgba(0, 191, 255, 0.3),
                        0 0 20px rgba(0, 191, 255, 0.2);
        }

        @keyframes logoFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(2deg); }
        }

        .logo-quantum { animation: logoFloat 6s ease-in-out infinite; }

        .btn-quantum:hover {
            box-shadow: 0 0 40px rgba(0, 191, 255, 0.5),
                        0 8px 25px rgba(0, 0, 0, 0.3);
        }

        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        .scale-in { animation: scaleIn 0.5s ease-out; }
    </style>
</head>
<body class="quantum-bg min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    <div class="particles" id="particles"></div>

    <div class="w-full max-w-6xl flex items-center justify-center relative z-10">
        <div class="w-full grid md:grid-cols-2 gap-8 items-center">

            <!-- Left Side - Branding -->
            <div class="hidden md:flex flex-col items-center justify-center space-y-8 text-center">
                <div class="logo-quantum">
                    <svg width="120" height="120" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="quantumGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:hsl(195, 100%, 50%);stop-opacity:1" />
                                <stop offset="100%" style="stop-color:hsl(270, 80%, 60%);stop-opacity:1" />
                            </linearGradient>
                        </defs>
                        <circle cx="60" cy="30" r="8" fill="url(#quantumGradient)"/>
                        <circle cx="30" cy="60" r="8" fill="url(#quantumGradient)"/>
                        <circle cx="90" cy="60" r="8" fill="url(#quantumGradient)"/>
                        <circle cx="60" cy="90" r="8" fill="url(#quantumGradient)"/>
                        <line x1="60" y1="30" x2="30" y2="60" stroke="url(#quantumGradient)" stroke-width="2"/>
                        <line x1="60" y1="30" x2="90" y2="60" stroke="url(#quantumGradient)" stroke-width="2"/>
                        <line x1="30" y1="60" x2="60" y2="90" stroke="url(#quantumGradient)" stroke-width="2"/>
                        <line x1="90" y1="60" x2="60" y2="90" stroke="url(#quantumGradient)" stroke-width="2"/>
                        <line x1="30" y1="60" x2="90" y2="60" stroke="url(#quantumGradient)" stroke-width="2"/>
                        <circle cx="60" cy="60" r="15" fill="none" stroke="url(#quantumGradient)" stroke-width="3"/>
                        <circle cx="60" cy="60" r="5" fill="url(#quantumGradient)"/>
                    </svg>
                </div>

                <div>
                    <h1 class="text-6xl font-bold text-gradient mb-2">QUANTUM</h1>
                    <p class="text-gray-400 text-lg">Gestión a la Velocidad del Pensamiento</p>
                </div>

                <div class="space-y-4 text-left">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-quantum-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-quantum-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <span class="text-gray-300">Restablecimiento seguro</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-void-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-void-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <span class="text-gray-300">Enlace protegido y único</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-photon-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-photon-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <span class="text-gray-300">Proceso rápido y confiable</span>
                    </div>
                </div>
            </div>

            <!-- Right Side - Reset Form -->
            <div class="w-full">
                <div class="glass-card rounded-quantum-xl p-8 md:p-12 scale-in">
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

                        <h2 class="text-3xl font-bold text-white mb-2">Nueva Contraseña</h2>
                        <p class="text-gray-400">Ingresa tu nueva contraseña para restablecer el acceso a tu cuenta.</p>
                    </div>

                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-500/10 border border-red-500/30 rounded-quantum">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <ul class="list-disc list-inside text-red-300 text-sm space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="email" value="{{ $email }}">

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Nueva contraseña</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <input type="password" name="password" id="password"
                                       class="input-quantum pl-12 w-full" placeholder="Mínimo 8 caracteres"
                                       required autofocus autocomplete="new-password" minlength="8">
                            </div>
                            @error('password')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">Confirmar contraseña</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                       class="input-quantum pl-12 w-full" placeholder="Repite la nueva contraseña"
                                       required autocomplete="new-password">
                            </div>
                        </div>

                        <button type="submit" class="btn-quantum w-full">
                            <svg class="w-5 h-5 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Guardar Nueva Contraseña
                        </button>

                        <div class="text-center pt-4 border-t border-matter-light">
                            <a href="{{ route('login') }}" class="text-sm text-quantum-400 hover:text-quantum-300 transition-colors inline-flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Volver al Login
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const particlesContainer = document.getElementById('particles');
            for (let i = 0; i < 50; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 10 + 's';
                particle.style.animationDuration = (Math.random() * 10 + 10) + 's';
                particlesContainer.appendChild(particle);
            }
        });
    </script>
</body>
</html>
