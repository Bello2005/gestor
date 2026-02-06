@component('mail::message')
# Restablecer Contraseña

Hola **{{ $user->name }}**,

Recibimos una solicitud para restablecer tu contraseña en QUANTUM.

@component('mail::panel')
**Ten en cuenta:**
- Este enlace es válido por **1 hora**
- Solo puede ser usado **una vez**
- Si no solicitaste este cambio, ignora este correo
@endcomponent

@component('mail::button', ['url' => $resetUrl])
Restablecer Mi Contraseña
@endcomponent

Si el botón no funciona, copia y pega esta URL en tu navegador:
{{ $resetUrl }}

¿Problemas? Escribe a {{ config('mail.from.address') }}

Saludos,<br>
**QUANTUM** — Control de Acceso Basado en Riesgo

<small>Si no solicitaste este restablecimiento, puedes ignorar este correo con seguridad. Tu contraseña actual no será modificada.</small>
@endcomponent
