<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#0d9488">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="Portal Magang">
        <link rel="apple-touch-icon" href="{{ asset('images/Banjarmasin_Logo.svg.png') }}">
        <link rel="manifest" href="{{ asset('manifest.json') }}">

        <title>{{ config('app.name', 'Portal Magang') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:300,400,500,600,700,800|outfit:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body {
                font-family: 'Plus Jakarta Sans', 'Figtree', 'Inter', sans-serif;
                -webkit-tap-highlight-color: transparent;
            }
            h1, h2, h3, h4, .font-display {
                font-family: 'Outfit', sans-serif;
            }
        </style>
        <script>
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            // Force HTTPS on public domains (e.g. Cloudflare Tunnels) for camera/location APIs
            const isLocal = ['localhost', '127.0.0.1', '::1'].includes(location.hostname) || 
                            !location.hostname.includes('.') ||
                            location.hostname.endsWith('.test') ||
                            location.hostname.endsWith('.dev') ||
                            location.hostname.endsWith('.local') ||
                            location.hostname.startsWith('192.168.') || 
                            location.hostname.startsWith('10.') || 
                            location.hostname.startsWith('172.');
            if (location.protocol !== 'https:' && !isLocal) {
                location.replace('https:' + location.href.substring(location.protocol.length));
            }
        </script>
    </head>
    <body class="auth-shell relative min-h-screen font-sans text-gray-900 dark:text-gray-100 antialiased transition-colors duration-300">
        {{-- Ambient background glowing Orbs --}}
        <div class="pointer-events-none fixed -top-24 -left-24 h-96 w-96 rounded-full bg-teal-500/10 blur-3xl dark:bg-teal-500/20"></div>
        <div class="pointer-events-none fixed -bottom-24 -right-24 h-96 w-96 rounded-full bg-emerald-500/10 blur-3xl dark:bg-emerald-500/20"></div>

        {{-- Top Floating Utilities (Theme Toggle & Quick Home) --}}
        <header class="absolute top-0 left-0 right-0 z-50 flex items-center justify-between px-4 py-4 sm:px-8">
            <a href="{{ route('home') }}" class="group flex items-center gap-2.5 rounded-full border border-gray-200/80 bg-white/70 px-3.5 py-1.5 text-xs font-bold text-gray-700 shadow-sm backdrop-blur-md transition hover:border-teal-500/40 hover:bg-white dark:border-gray-800 dark:bg-gray-900/70 dark:text-gray-300 dark:hover:border-teal-500/40 dark:hover:bg-gray-900">
                <x-application-logo class="h-4 w-4 fill-current text-teal-600 dark:text-teal-400" />
                <span>Portal Magang</span>
            </a>

            <div class="flex items-center gap-2 rounded-full border border-gray-200/80 bg-white/70 p-1 shadow-sm backdrop-blur-md dark:border-gray-800 dark:bg-gray-900/70">
                <x-theme-toggle class="!p-1.5" />
            </div>
        </header>

        {{-- Main Content Container --}}
        <div class="min-h-screen flex flex-col justify-center items-center p-4 sm:p-6 lg:p-8 pt-20 sm:pt-24 lg:pt-28">
            <div class="w-full max-w-6xl">
                {{ $slot }}
            </div>
        </div>

        <script src="//instant.page/5.2.0" type="module" integrity="sha384-jnZyxPjiipYXnSU0ygqeac2q7CVYMbh84q0uHVRRxEtvFPiQYbXWUorga2aqZJ0z"></script>
        @stack('scripts')
    </body>
</html>