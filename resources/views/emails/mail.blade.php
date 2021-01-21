@component('mail::message')
# Hola <strong>{{ $name }}</strong>,

Ingresa a este link para realizar tu prueba.

@component('mail::button', ['url' => $link])
Dar la prueba
@endcomponent

Gracias,<br>
FullTimeForce
@endcomponent
