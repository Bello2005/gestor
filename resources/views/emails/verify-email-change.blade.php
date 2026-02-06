@component('mail::message')
# Verificar Cambio de Correo

Hola **{{ $name }}**,

Has solicitado cambiar tu correo electr&oacute;nico en QUANTUM.

@component('mail::panel')
**Correo actual:** {{ $currentEmail }}
**Nuevo correo:** {{ $newEmail }}
@endcomponent

@component('mail::button', ['url' => $verificationUrl])
Verificar Cambio de Correo
@endcomponent

Este enlace expirar&aacute; en **24 horas**. Si no solicitaste este cambio, ignora este mensaje.

Si el bot&oacute;n no funciona, copia y pega esta URL en tu navegador:
{{ $verificationUrl }}

Saludos,<br>
**QUANTUM** &mdash; Control de Acceso Basado en Riesgo
@endcomponent
