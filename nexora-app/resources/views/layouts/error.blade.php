<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="@yield('meta_description', 'Nexora — разработка и сопровождение веб-проектов под ключ.')">

    <title>@yield('title', 'Nexora')</title>

    @include('partials.landing.favicon')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Unbounded:wght@500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/landing.css'])
</head>
<body class="landing">
    @include('partials.landing.header')

    <main class="landing-main">
        @yield('content')
    </main>

    @include('partials.landing.footer')
    @include('partials.landing.scripts')
</body>
</html>
