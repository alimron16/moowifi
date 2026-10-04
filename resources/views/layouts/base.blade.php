<!doctype html>
<html lang="id" data-bs-navbar-position="vertical">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        (function() {
            var theme = localStorage.getItem('mwifi_theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', theme);
        })();
    </script>
    <title>@yield('title', config('app.name', 'MooWiFi')) - Otomatisasi Jaringan, Maksimalkan Cuan</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="@yield('body-class', 'layout-fluid')">
    <div class="page @yield('page-class')">
        @yield('content')
    </div>
    @stack('scripts')
</body>
</html>
