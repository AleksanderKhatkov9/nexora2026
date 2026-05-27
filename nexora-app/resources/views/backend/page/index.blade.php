<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Nexora — разработка и сопровождение веб-проектов под ключ. Современные сайты, порталы и автоматизация бизнес-процессов.">

    <title>Nexora — создание сайтов и веб-разработка</title>

    @include('partials.landing.favicon')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Unbounded:wght@500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/landing.css', 'resources/js/app.js'])
</head>
<body class="landing">
    <div
        id="app"
        data-home-url="{{ route('home') }}"
        data-projects-url="{{ route('projects') }}"
        data-favicon-url="{{ asset('favicon.svg') }}"
        data-login-url="{{ Route::has('login') ? route('login') : '' }}"
        data-dashboard-url="{{ url('/nova') }}"
        data-is-authenticated="@auth true @else false @endauth"
        data-csrf-token="{{ csrf_token() }}"
        data-current-year="{{ date('Y') }}"
    ></div>
</body>
</html>
