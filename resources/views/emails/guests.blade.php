<p>Parece que {{ $user->name }} ha compartido la lista "{{ $cart->name }}" contigo. Utiliza el siguiente enlace para entrar:</p>

<p><b><a href="{{ config('app.url') }}/login?token={{ $guest->remember_token }}">Ver la lista compartida en sopplis.com</a></b></p>

<p><small>Has recibido este correo porque alguien a introducido tu email en sopplis.com. Si no conoces a "{{ $user->name }}" puedes ignorar este correo.</small></p>
