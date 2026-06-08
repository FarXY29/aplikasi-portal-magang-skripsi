<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
<body class="font-sans antialiased bg-gray-50" x-data="{ sidebarOpen: false }">
    
    <div class="flex h-screen overflow-hidden">

        <div x-show="sidebarOpen" x-cloak 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false" 
             class="fixed inset-0 z-40 bg-gray-900/50 lg:hidden backdrop-blur-sm">
        </div>

        <aside x-cloak 
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 shadow-xl lg:shadow-none lg:static lg:inset-auto lg:translate-x-0 transition-transform duration-300 transform h-full flex flex-col">
            
            <div class="flex items-center justify-center h-16 bg-gradient-to-r from-teal-600 to-teal-700">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <x-application-logo class="block h-8 w-auto fill-current text-teal-600" />
                    <span class="text-lg font-bold text-gray-800 tracking-tight">Portal<span class="text-white">Magang</span></span>
                </a>
            </div>

            <div class="flex-1 overflow-y-auto custom-scrollbar py-4 px-3 space-y-1">
                @include('layouts.navigation')
            </div>

            <div class="p-4 border-t border-gray-100 text-center">
                <p class="text-[10px] text-gray-400">&copy; {{ date('Y') }} Pemkot Banjarmasin</p>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 z-30">
                
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="text-gray-500 hover:text-gray-700 focus:outline-none lg:hidden">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="font-bold text-lg text-gray-800 leading-tight truncate">
                        {{ $header ?? 'Dashboard' }}
                    </h2>
                </div>

                <div class="flex items-center gap-3 print:hidden">
                    <!-- Hari & Tanggal -->
                    <div class="hidden md:flex items-center gap-2 text-xs font-bold text-gray-600 bg-gray-50/80 px-3 py-1.5 rounded-xl border border-gray-100 shadow-sm">
                        <i class="far fa-calendar-alt text-teal-600"></i>
                        <span>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
                    </div>
                    
                    <!-- Jam Real-time (Digital Clock) -->
                    <div id="realtime-clock" class="hidden sm:flex items-center gap-1.5 text-xs font-mono font-bold text-teal-700 bg-teal-50/50 px-3 py-1.5 rounded-xl border border-teal-100 shadow-sm">
                        <i class="far fa-clock"></i>
                        <span id="clock-span">00:00:00</span>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 custom-scrollbar p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>

        </div>
    </div>
    <script src="//instant.page/5.2.0" type="module" integrity="sha384-jnZyxPjiipYXnSU0ygqeac2q7CVYMbh84q0uHVRRxEtvFPiQYbXWUorga2aqZJ0z"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const updateClock = () => {
                const clockSpan = document.getElementById('clock-span');
                if (clockSpan) {
                    const now = new Date();
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    const seconds = String(now.getSeconds()).padStart(2, '0');
                    clockSpan.textContent = `${hours}:${minutes}:${seconds}`;
                }
            };
            setInterval(updateClock, 1000);
            updateClock();
        });
        
        // Support Turbo page transitions
        document.addEventListener('turbo:load', () => {
            const updateClock = () => {
                const clockSpan = document.getElementById('clock-span');
                if (clockSpan) {
                    const now = new Date();
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    const seconds = String(now.getSeconds()).padStart(2, '0');
                    clockSpan.textContent = `${hours}:${minutes}:${seconds}`;
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
    @stack('scripts')
</body>
</html>