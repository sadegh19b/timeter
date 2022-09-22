<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <!-- Favicon -->
{{--    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">--}}

    <!-- Styles & Scripts -->
    @vite('resources/js/app.js')
    <script type="text/javascript" src="{{ route('ziggy.js') }}"></script>
    @inertiaHead
</head>
<body class="antialiased overflow-hidden">
    @inertia
</body>
</html>
