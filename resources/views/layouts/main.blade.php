<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="apple-mobile-web-app-title" content="Sopplis">
        <meta name="theme-color" content="#436169">
        <meta name="version" content="{{ env('APP_VERSION') }}">
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <link rel="shortcut icon" href="/images/favicon.png">
        <link rel="apple-touch-icon" href="/images/favicon-touch.png">
        <link rel="manifest" href="/manifest.json">
        <title>Sopplis</title>
    </head>
<body>

    @yield('content')
    @yield('scripts')

</body>
</html>
