@component('mail::message')
# Solicitud de Acceso Aprobada

¡Hola {{ $user->name }}!

Nos complace informarte que tu solicitud de acceso al sistema ha sido aprobada. Puedes acceder al sistema utilizando las siguientes credenciales:

@component('mail::panel')
**URL del sistema:** https://gestor-0o3w.onrender.com
**Email:** {{ $user->email }}
**Contraseña temporal:** {{ $temporaryPassword }}
@endcomponent

@component('mail::panel')
**IMPORTANTE:** Por tu seguridad, el sistema te solicitará cambiar tu contraseña temporal la primera vez que inicies sesión. Este paso es obligatorio para garantizar la seguridad de tu cuenta.
@endcomponent

@component('mail::button', ['url' => 'https://gestor-0o3w.onrender.com'])
Acceder al Sistema
@endcomponent

También puedes acceder directamente copiando esta URL en tu navegador:
https://gestor-0o3w.onrender.com

Si tienes alguna pregunta o necesitas ayuda:
- Contacta al equipo de soporte
- Escribe a {{ config('mail.from.address', 'soporte@uniclaretiana.edu.co') }}
- Consulta con tu administrador del sistema

Saludos,<br>
{{ config('app.name') }}
@endcomponent