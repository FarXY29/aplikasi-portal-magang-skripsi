<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes, viewport-fit=cover">
    <meta name="theme-color" content="#0f766e">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Portal Magang">
    <link rel="apple-touch-icon" href="{{ asset('images/Banjarmasin_Logo.svg.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <title>SiMagang - Pemerintah Kota Banjarmasin</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome self-hosted via Vite (app.css); font via fonts.bunny.net -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:300,400,500,600,700,800|outfit:300,400,500,600,700,800,900&display=swap" rel="stylesheet">

    <style>
        body { 
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; 
            -webkit-tap-highlight-color: transparent;
        }
        h1, h2, h3, h4, .font-display {
            font-family: 'Outfit', sans-serif;
        }
        
        /* Premium Sasirangan Modern Background */
        .bg-sasirangan-premium {
            background-color: #042f2e !important;
            background-image: 
                radial-gradient(circle at 80% 20%, rgba(20, 184, 166, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 15% 80%, rgba(16, 185, 129, 0.12) 0%, transparent 50%),
                linear-gradient(to bottom right, rgba(4, 47, 46, 0.95), rgba(6, 78, 59, 0.98)),
                url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2314b8a6' fill-opacity='0.05'%3E%3Cpath d='M40 38v-8h-4v8h-8v4h8v8h4v-8h8v-4h-8zm0-36V0h-4v2h-8v4h8v8h4V6h8V2h-8zM8 38v-8H4v8H0v4h4v8h4v-8h8v-4H8zM8 2V0H4v2H0v4h4v8h4V6h8V2H8z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") !important;
            background-size: 100% 100%, 100% 100%, cover, auto !important;
        }
    </style>
    <script>
        // Progressive enhancement flag: enables reveal/entrance animations only with JS.
        document.documentElement.classList.add('js');

        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-slate-50 dark:bg-gray-900 text-slate-600 dark:text-slate-400 flex flex-col min-h-screen overflow-x-hidden antialiased transition-colors duration-300">

    @if (session('success'))
        <div class="fixed top-24 left-1/2 transform -translate-x-1/2 z-[100] w-full max-w-xl px-4">
            <x-ui.alert type="success" :dismissible="true">
                {{ session('success') }}
            </x-ui.alert>
        </div>
    @endif

    @include('public.welcome._navbar')
    @include('public.welcome._hero')
    @include('public.welcome._stats')
    @include('public.welcome._lowongan-grid')
    @include('public.welcome._alur-magang')
    @include('public.welcome._faq')
    @include('public.welcome._footer')

    <!-- Floating Back to Top Button (With iOS Safe Area Offset) -->
    <div x-data="{ showBackToTop: false }" 
         x-init="showBackToTop = ((window.pageYOffset || window.scrollY) > 300)"
         @scroll.window.passive="showBackToTop = ((window.pageYOffset || window.scrollY) > 300)"
         class="fixed bottom-[calc(1.25rem+env(safe-area-inset-bottom,0px))] right-4 sm:right-6 z-40">
        <button x-show="showBackToTop" 
                x-cloak
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 translate-y-4 scale-90"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 scale-90"
                @click="window.scrollTo({ top: 0, behavior: 'smooth' })" 
                type="button" 
                class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-teal-600 hover:bg-teal-700 active:scale-95 text-white shadow-xl shadow-teal-600/30 flex items-center justify-center transition-all duration-300 border border-teal-400/30 focus:outline-none group" 
                title="Kembali ke Atas">
            <i class="fas fa-chevron-up text-sm group-hover:-translate-y-0.5 transition-transform duration-300"></i>
        </button>
    </div>

    <!-- Global Lightweight Toast Notification (With iOS Safe Area Offset) -->
    <div x-data="{ open: false, message: '' }" 
         @notify.window="message = $event.detail; open = true; setTimeout(() => open = false, 3500)"
         class="fixed bottom-[calc(1.25rem+env(safe-area-inset-bottom,0px))] left-1/2 transform -translate-x-1/2 z-[1000] pointer-events-none px-4 w-full max-w-sm">
        <div x-show="open" 
             x-cloak
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 scale-95"
             class="bg-slate-900/95 dark:bg-gray-800/95 text-white backdrop-blur-xl border border-slate-700/60 rounded-2xl p-4 shadow-2xl flex items-center gap-3 text-xs font-bold pointer-events-auto">
            <div class="w-7 h-7 rounded-xl bg-teal-500/20 text-teal-400 flex items-center justify-center shrink-0 border border-teal-500/30">
                <i class="fas fa-check text-xs"></i>
            </div>
            <span x-text="message" class="flex-grow"></span>
            <button @click="open = false" type="button" class="text-slate-400 hover:text-white transition shrink-0 ml-1">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
    </div>

</body>
</html>