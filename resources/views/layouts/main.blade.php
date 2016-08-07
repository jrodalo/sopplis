<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="apple-mobile-web-app-title" content="Sopplis">
        <link rel="stylesheet" type="text/css" href="/css/app.css">
        <link rel="shortcut icon" href="/favicon.png">
        <link rel="apple-touch-icon" href="/favicon-touch.png">
        <title>Sopplis</title>
    </head>
<body>

    @yield('content')
    @yield('scripts')

    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js');
        }
    </script>
</body>
</html>
