<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" dir="{{ config('app.rtl') ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- Styles & Scripts -->
    <script type="text/javascript" src="{{ route('configs.js') }}"></script>
    <script type="text/javascript" src="{{ route('translations.js') }}"></script>
    <script type="text/javascript" src="{{ route('ziggy.js') }}"></script>
    @vite('resources/js/app.js')
    @inertiaHead
</head>
<body class="antialiased relative overflow-x-hidden">
    @inertia
</body>
</html>
