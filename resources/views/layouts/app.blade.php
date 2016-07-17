<!DOCTYPE html>
<html manifest="/cache.manifest">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-title" content="Sopplis">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="_token" content="{{ csrf_token() }}" id="token">
    <meta name="_list" content="{{ $list_slug }}" id="slug">
    <link rel="stylesheet" type="text/css" href="css/app.css">
    <link rel="shortcut icon" href="favicon.png">
    <title>Sopplis</title>
</head>
<body>
    @yield('content')
    @yield('scripts')
</body>
</html>
