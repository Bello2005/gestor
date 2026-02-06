@component('mail::message')
# Bienvenido a QUANTUM

Hola **{{ $user->name }}**,

Tu solicitud de acceso ha sido aprobada. Estas son tus credenciales:

@component('mail::panel')
**Email:** {{ $user->email }}
**Contraseña temporal:** {{ $temporaryPassword }}
@endcomponent

@component('mail::panel')
**Importante:** Por seguridad, deberás cambiar tu contraseña temporal la primera vez que inicies sesión.
@endcomponent

@component('mail::button', ['url' => config('app.url')])
Iniciar Sesión en QUANTUM
@endcomponent

Si el botón no funciona, copia y pega esta URL en tu navegador:
{{ config('app.url') }}

¿Necesitas ayuda? Escribe a {{ config('mail.from.address') }}

Saludos,<br>
**QUANTUM** — Control de Acceso Basado en Riesgo
@endcomponent
