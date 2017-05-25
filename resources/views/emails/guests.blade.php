<p>Parece que {{ $owner->name }} ha compartido la lista "{{ $cart->name }}" contigo.</p>
<p>Abre el siguiente enlace en tu navegador para entrar:</p>
<p>{{ config('app.url') }}/login?token={{ $guest->remember_token }}</p>
<p><small>Has recibido este correo porque alguien a introducido tu email en sopplis.com. Si no conoces a "{{ $owner->name }}" puedes ignorar este correo.</small></p>
