<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

        <title>{{ config('app.name', 'Toko Rajua') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="guest-wallpaper relative isolate min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="guest-wallpaper__scene" aria-hidden="true">
                <video
                    class="guest-wallpaper__video"
                    data-wallpaper-video
                    autoplay
                    loop
                    muted
                    playsinline
                    preload="auto"
                    aria-hidden="true"
                >
                    <source src="{{ asset('videos/login-wallpaper.mp4') }}" type="video/mp4">
                </video>
            </div>

            <div class="relative z-10">
                <a href="/">
                    <img src="{{ asset('images/logo.png') }}" alt="Rajua" class="h-28 w-28 object-contain" width="112" height="112">
                </a>
            </div>

            <div class="relative z-10 w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>

            <button
                type="button"
                class="guest-wallpaper__motion-toggle"
                data-wallpaper-motion-toggle
                aria-pressed="false"
            >
                Atur gerakan latar
            </button>
        </div>
    </body>
</html>
