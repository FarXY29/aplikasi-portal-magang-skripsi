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
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        /* Sembunyikan scrollbar default tapi tetap bisa scroll */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: #94a3b8; }
    </style>
    @stack('head')
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-800" x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false">
    
    <div class="flex h-screen overflow-hidden">

        <!-- Backdrop untuk Mode Drawer (Tablet/Mobile < lg) -->
        <div x-show="sidebarOpen" x-cloak 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false" 
             class="fixed inset-0 z-40 bg-gray-900/60 lg:hidden backdrop-blur-xs">
        </div>

        <!-- MAIN SIDEBAR (Desktop & Drawer Slide-Over) -->
        <aside x-cloak 
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 z-50 w-64 md:w-72 bg-white border-r border-gray-200/80 shadow-2xl lg:shadow-none lg:static lg:inset-auto lg:translate-x-0 transition-all duration-300 transform h-full flex flex-col flex-shrink-0">
            @include('layouts.navigation')
        </aside>

        <!-- MAIN CONTENT WRAPPER -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-gray-50">
            
            <!-- DESKTOP & TABLET HEADER (md dan ke atas) -->
            <header class="hidden md:flex bg-white/90 backdrop-blur-md border-b border-gray-200/80 min-h-[4rem] py-3 items-center justify-between px-6 lg:px-8 z-30 shadow-xs">
                
                <div class="flex items-center gap-4 flex-1 min-w-0">
                    <!-- Tombol Hamburger (Muncul pada tablet md ke lg untuk membuka drawer sidebar) -->
                    <button @click="sidebarOpen = true" class="p-2.5 -ml-2 text-gray-600 hover:text-teal-600 hover:bg-teal-50/80 rounded-xl focus:outline-none lg:hidden transition active:scale-95 flex-shrink-0" title="Buka Sidebar">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    <div class="flex-1 min-w-0">
                        @if(isset($header))
                            {{ $header }}
                        @else
                            <h2 class="font-black text-xl text-gray-800 leading-tight truncate">
                                Dashboard
                            </h2>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-3 print:hidden">
                    <div class="flex items-center gap-2 text-xs font-bold text-gray-600 bg-gray-100/80 px-3.5 py-2 rounded-xl border border-gray-200/60 shadow-2xs">
                        <i class="far fa-calendar-alt text-teal-600"></i>
                        <span>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
                    </div>
                    
                    <div id="realtime-clock" class="flex items-center gap-2 text-xs font-mono font-black text-teal-700 bg-teal-50 px-3.5 py-2 rounded-xl border border-teal-200/80 shadow-2xs">
                        <i class="far fa-clock"></i>
                        <span id="clock-span">00:00:00</span>
                    </div>
                </div>
            </header>

            <!-- MOBILE NATIVE TOP BAR (Android & iOS < md) -->
            <header class="md:hidden sticky top-0 z-30 bg-white/95 backdrop-blur-lg border-b border-gray-200/80 px-4 py-3 flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-3 min-w-0">
                    <!-- Tombol Hamburger di pojok kiri atas mobile -->
                    <button @click="sidebarOpen = true" class="p-2 -ml-1 text-gray-700 hover:text-teal-600 focus:outline-none active:scale-95 transition" title="Buka Sidebar">
                        <i class="fas fa-bars text-xl"></i>
                    </button>

                    <a href="{{ route('home') }}" class="flex items-center gap-2.5 min-w-0">
                        <div class="w-8 h-8 rounded-lg bg-teal-600 text-white flex items-center justify-center p-1 shadow-xs flex-shrink-0">
                            <x-application-logo class="w-full h-full fill-current text-white" />
                        </div>
                        <div class="min-w-0">
                            <h1 class="text-sm font-black text-gray-900 leading-none truncate">Portal<span class="text-teal-600">Magang</span></h1>
                            <span class="inline-block mt-0.5 px-1.5 py-0.5 text-[8px] font-black uppercase bg-teal-50 text-teal-700 rounded border border-teal-200/60 tracking-wider">
                                {{ str_replace('_', ' ', Auth::user()->role) }}
                            </span>
                        </div>
                    </a>
                </div>

                <div class="flex items-center gap-2.5">
                    <div id="mobile-clock" class="text-[11px] font-mono font-black text-teal-700 bg-teal-50 px-2.5 py-1.5 rounded-lg border border-teal-200/60 shadow-2xs">
                        <i class="far fa-clock mr-1 text-teal-600"></i><span id="mobile-clock-span">00:00:00</span>
                    </div>

                    <!-- Tombol Menu Cepat / Profil -->
                    <button @click="$dispatch('open-mobile-menu')" class="w-9 h-9 rounded-xl bg-gradient-to-tr from-teal-600 to-teal-500 text-white flex items-center justify-center font-black text-xs shadow-sm ring-2 ring-teal-100 active:scale-95 transition flex-shrink-0" title="Menu Navigasi Mobile">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </button>
                </div>
            </header>

            <!-- MAIN BODY SLOT -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 custom-scrollbar p-4 pb-24 md:p-6 lg:p-8 md:pb-8">
                {{ $slot }}
            </main>

        </div>
    </div>

    @include('layouts.partials._mobile-bottom-nav')

    @include('layouts.partials._mobile-sheet')
    <script src="//instant.page/5.2.0" type="module" integrity="sha384-jnZyxPjiipYXnSU0ygqeac2q7CVYMbh84q0uHVRRxEtvFPiQYbXWUorga2aqZJ0z"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const updateClock = () => {
                const clockSpan = document.getElementById('clock-span');
                const mobileClockSpan = document.getElementById('mobile-clock-span');
                if (clockSpan || mobileClockSpan) {
                    const now = new Date();
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    const seconds = String(now.getSeconds()).padStart(2, '0');
                    const timeStr = `${hours}:${minutes}:${seconds}`;
                    if (clockSpan) clockSpan.textContent = timeStr;
                    if (mobileClockSpan) mobileClockSpan.textContent = timeStr;
                }
            };
            setInterval(updateClock, 1000);
            updateClock();
        });
        
        // Support Turbo page transitions
        document.addEventListener('turbo:load', () => {
            if (window.Alpine) {
                const bodyEl = document.querySelector('body');
                if (bodyEl && bodyEl.__x) {
                    bodyEl.__x.$data.sidebarOpen = false;
                }
            }
            const updateClock = () => {
                const clockSpan = document.getElementById('clock-span');
                const mobileClockSpan = document.getElementById('mobile-clock-span');
                if (clockSpan || mobileClockSpan) {
                    const now = new Date();
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    const seconds = String(now.getSeconds()).padStart(2, '0');
                    const timeStr = `${hours}:${minutes}:${seconds}`;
                    if (clockSpan) clockSpan.textContent = timeStr;
                    if (mobileClockSpan) mobileClockSpan.textContent = timeStr;
                }
            };
            updateClock();
        });
    </script>
    <div id="global-image-modal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/80 p-4 opacity-0 pointer-events-none transition-opacity duration-300 backdrop-blur-sm" onclick="closeImageModal()">
        <div class="relative max-w-4xl max-h-[90vh] w-full flex justify-center items-center" onclick="event.stopPropagation()">
            <button onclick="closeImageModal()" class="absolute -top-4 -right-4 md:top-4 md:right-4 bg-white/10 hover:bg-white/20 text-white rounded-full w-10 h-10 flex items-center justify-center focus:outline-none transition backdrop-blur-md border border-white/20 z-10">
                <i class="fas fa-times text-xl"></i>
            </button>
            <img id="global-image-modal-img" src="" class="max-w-full max-h-[85vh] object-contain rounded-xl shadow-2xl" alt="Preview Image">
        </div>
    </div>

    <script>
        function openImageModal(src) {
            const modal = document.getElementById('global-image-modal');
            const img = document.getElementById('global-image-modal-img');
            img.src = src;
            modal.classList.remove('opacity-0', 'pointer-events-none');
        }
        
        function closeImageModal() {
            const modal = document.getElementById('global-image-modal');
            modal.classList.add('opacity-0', 'pointer-events-none');
            setTimeout(() => {
                document.getElementById('global-image-modal-img').src = '';
            }, 300);
        }
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>
    <x-ui.confirm-dialog />
    @stack('scripts')
</body>
</html>