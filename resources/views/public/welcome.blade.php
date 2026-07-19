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

    <!-- Font Awesome Icons & Google Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

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
            background-color: #042f2e !important; /* Force dark background */
            background-image: 
                radial-gradient(circle at 80% 20%, rgba(20, 184, 166, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 15% 80%, rgba(16, 185, 129, 0.12) 0%, transparent 50%),
                linear-gradient(to bottom right, rgba(4, 47, 46, 0.95), rgba(6, 78, 59, 0.98)),
                url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2314b8a6' fill-opacity='0.05'%3E%3Cpath d='M40 38v-8h-4v8h-8v4h8v8h4v-8h8v-4h-8zm0-36V0h-4v2h-8v4h8v8h4V6h8V2h-8zM8 38v-8H4v8H0v4h4v8h4v-8h8v-4H8zM8 2V0H4v2H0v4h4v8h4V6h8V2H8z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") !important;
            background-size: 100% 100%, 100% 100%, cover, auto !important;
        }
    </style>
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-slate-50/50 dark:bg-gray-900 text-slate-600 dark:text-slate-400 flex flex-col min-h-screen overflow-x-hidden w-full antialiased transition-colors duration-300">

    <!-- Header & Navigation Section -->
    <nav x-data="{ mobileMenuOpen: false, scrolled: false }" 
         x-init="scrolled = ((window.scrollY || window.pageYOffset) > 20); $nextTick(() => { scrolled = ((window.scrollY || window.pageYOffset) > 20) });"
         @scroll.window="scrolled = ((window.scrollY || window.pageYOffset) > 20)"
         :class="scrolled ? 'bg-white dark:bg-gray-800/90 backdrop-blur-xl shadow-lg shadow-slate-100/50 dark:shadow-none border-b border-slate-100 dark:border-gray-700/50 py-3' : 'bg-transparent py-5 sm:py-6'"
         class="fixed w-full top-0 z-50 transition-all duration-500 ease-in-out">
         <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
             <div class="flex justify-between h-14 sm:h-16 items-center w-full">
                 <!-- Brand Logo -->
                 <a href="{{ url('/') }}" class="flex items-center gap-3 group focus:outline-none">
                     <div class="bg-white dark:bg-gray-800 rounded-2xl p-2 shadow-md border border-slate-100/80 dark:border-gray-700/50 flex items-center justify-center shrink-0 transition-transform duration-500 group-hover:scale-105">
                         <x-application-logo class="w-8 h-8 sm:w-9 sm:h-9 fill-current text-teal-600" />
                     </div>
                     <div class="flex flex-col">
                         <span class="text-base sm:text-lg font-black leading-none tracking-tight uppercase transition-colors duration-300" 
                               :class="scrolled ? 'text-slate-900 dark:text-white' : 'text-white group-hover:text-teal-200'">SiMagang</span>
                         <span class="text-[9px] font-extrabold tracking-widest uppercase transition-colors duration-300 mt-1" 
                               :class="scrolled ? 'text-teal-600' : 'text-teal-300/90'">Kota Banjarmasin</span>
                     </div>
                 </a>

                 <!-- Desktop Navigation Menu -->
                 <div class="hidden md:flex items-center gap-8">
                     <a href="#lowongan" class="text-sm font-bold tracking-wide transition-all relative py-1 after:absolute after:bottom-0 after:left-0 after:w-0 after:h-0.5 after:bg-teal-500 after:transition-all hover:after:w-full" :class="scrolled ? 'text-slate-600 dark:text-slate-300 hover:text-teal-600 dark:hover:text-teal-400' : 'text-white/90 hover:text-white'">Cari Lowongan</a>
                     <a href="#langkah" class="text-sm font-bold tracking-wide transition-all relative py-1 after:absolute after:bottom-0 after:left-0 after:w-0 after:h-0.5 after:bg-teal-500 after:transition-all hover:after:w-full" :class="scrolled ? 'text-slate-600 dark:text-slate-300 hover:text-teal-600 dark:hover:text-teal-400' : 'text-white/90 hover:text-white'">Alur Magang</a>
                     <a href="#faq" class="text-sm font-bold tracking-wide transition-all relative py-1 after:absolute after:bottom-0 after:left-0 after:w-0 after:h-0.5 after:bg-teal-500 after:transition-all hover:after:w-full" :class="scrolled ? 'text-slate-600 dark:text-slate-300 hover:text-teal-600 dark:hover:text-teal-400' : 'text-white/90 hover:text-white'">FAQ</a>
                     
                     <div class="h-5 w-[1px] bg-slate-200/40" :class="scrolled ? 'bg-slate-200 dark:bg-gray-700' : 'bg-white dark:bg-gray-800/20'"></div>

                     @if (Route::has('login'))
                         @auth
                             <div class="flex items-center gap-4">
                                 <a href="{{ url('/dashboard') }}" class="bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white px-5 py-2.5 rounded-2xl font-bold shadow-md shadow-teal-600/10 hover:shadow-lg hover:shadow-teal-600/25 transition-all text-xs sm:text-sm transform hover:-translate-y-0.5 active:translate-y-0">
                                     <i class="fas fa-columns mr-2"></i>Dashboard Saya
                                 </a>
                                 <x-theme-toggle class="p-2.5 text-slate-400 hover:text-teal-600 dark:text-gray-400 dark:hover:text-white rounded-xl bg-slate-50 dark:bg-gray-800 border border-slate-100 dark:border-gray-700/50" />
                             </div>
                         @else
                             <div class="flex items-center gap-3">
                                 <a href="{{ route('login') }}" class="px-4 py-2.5 text-xs sm:text-sm font-bold transition-all rounded-2xl hover:-translate-y-0.5" :class="scrolled ? 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-gray-800 hover:text-slate-900 dark:hover:text-white' : 'text-white hover:bg-white dark:hover:bg-gray-800/10'">
                                     Masuk
                                 </a>
                                 @if (Route::has('register'))
                                     <a href="{{ route('register') }}" class="bg-white dark:bg-gray-800 text-teal-800 dark:text-teal-400 hover:bg-teal-50 dark:hover:bg-gray-800 hover:text-teal-900 px-5 py-2.5 rounded-2xl font-extrabold shadow-sm hover:shadow-md transition-all text-xs sm:text-sm border border-slate-100 dark:border-gray-700/50 transform hover:-translate-y-0.5 active:translate-y-0">
                                         Daftar Sekarang
                                     </a>
                                 @endif
                                 <x-theme-toggle class="p-2.5 text-slate-400 hover:text-teal-600 dark:text-gray-400 dark:hover:text-white rounded-xl bg-slate-50 dark:bg-gray-800 border border-slate-100 dark:border-gray-700/50" />
                             </div>
                         @endauth
                     @endif
                 </div>

                 <!-- Mobile Menu Toggle Button -->
                 <div class="md:hidden flex items-center">
                     <button @click.stop="mobileMenuOpen = !mobileMenuOpen" type="button" class="p-2 rounded-xl transition duration-200 focus:outline-none flex items-center justify-center border border-transparent hover:bg-slate-100/10 active:scale-95" :class="scrolled ? 'text-slate-800 dark:text-white hover:bg-slate-100 dark:hover:bg-gray-800 hover:text-teal-600' : 'text-white hover:bg-white dark:hover:bg-gray-800/10'" aria-label="Toggle Menu">
                         <!-- Animated Menu Icon -->
                         <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 6h16M4 12h16M4 18h16"></path>
                         </svg>
                         <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"></path>
                         </svg>
                     </button>
                 </div>
             </div>
         </div>

         <!-- Mobile Side Navigation Drawer -->
         <div x-show="mobileMenuOpen" 
              x-cloak
              @click.outside="if (!$event.target.closest('button')) mobileMenuOpen = false"
              x-transition:enter="transition ease-out duration-300"
              x-transition:enter-start="opacity-0 -translate-y-10"
              x-transition:enter-end="opacity-100 translate-y-0"
              x-transition:leave="transition ease-in duration-200"
              x-transition:leave-start="opacity-100 translate-y-0"
              x-transition:leave-end="opacity-0 -translate-y-10"
              class="md:hidden bg-white dark:bg-gray-900/95 backdrop-blur-2xl border-t border-slate-100 dark:border-gray-800 shadow-2xl absolute w-full left-0 top-full rounded-b-[2.5rem] overflow-hidden">
             <div class="px-5 py-6 space-y-3.5">
                 <a href="#lowongan" @click="mobileMenuOpen = false" class="flex items-center px-4 py-3.5 text-sm font-extrabold text-slate-800 dark:text-slate-200 bg-slate-50 dark:bg-gray-800/50 hover:bg-teal-50 dark:hover:bg-teal-950/20 hover:text-teal-600 dark:hover:text-teal-400 rounded-2xl transition duration-300">
                     <div class="w-8 h-8 rounded-xl bg-white dark:bg-gray-800 flex items-center justify-center shadow-sm text-teal-600 dark:text-teal-400 mr-3.5 border border-slate-100 dark:border-gray-700/50 shrink-0">
                         <i class="fas fa-search text-xs"></i>
                     </div>
                     Cari Lowongan Magang
                 </a>
                 <a href="#langkah" @click="mobileMenuOpen = false" class="flex items-center px-4 py-3.5 text-sm font-extrabold text-slate-800 dark:text-slate-200 bg-slate-50 dark:bg-gray-800/50 hover:bg-teal-50 dark:hover:bg-teal-950/20 hover:text-teal-600 dark:hover:text-teal-400 rounded-2xl transition duration-300">
                     <div class="w-8 h-8 rounded-xl bg-white dark:bg-gray-800 flex items-center justify-center shadow-sm text-teal-600 dark:text-teal-400 mr-3.5 border border-slate-100 dark:border-gray-700/50 shrink-0">
                         <i class="fas fa-tasks text-xs"></i>
                     </div>
                     Alur Pendaftaran
                 </a>
                 <a href="#faq" @click="mobileMenuOpen = false" class="flex items-center px-4 py-3.5 text-sm font-extrabold text-slate-800 dark:text-slate-200 bg-slate-50 dark:bg-gray-800/50 hover:bg-teal-50 dark:hover:bg-teal-950/20 hover:text-teal-600 dark:hover:text-teal-400 rounded-2xl transition duration-300">
                     <div class="w-8 h-8 rounded-xl bg-white dark:bg-gray-800 flex items-center justify-center shadow-sm text-teal-600 dark:text-teal-400 mr-3.5 border border-slate-100 dark:border-gray-700/50 shrink-0">
                         <i class="fas fa-question-circle text-xs"></i>
                     </div>
                     FAQ & Bantuan
                 </a>

                 <div class="border-t border-slate-100 dark:border-gray-800 pt-5 mt-4">
                     @if (Route::has('login'))
                         @auth
                             <div class="flex items-center gap-3">
                                 <a href="{{ url('/dashboard') }}" class="flex-grow flex items-center justify-center px-4 py-3.5 bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-2xl font-extrabold shadow-lg shadow-teal-600/20 active:scale-[0.98] transition text-sm">
                                     <i class="fas fa-columns mr-2.5"></i> Ke Dashboard Saya
                                 </a>
                                 <x-theme-toggle class="p-3.5 text-slate-500 bg-slate-100 dark:bg-gray-800 dark:text-gray-300 rounded-2xl border border-transparent flex items-center justify-center" />
                             </div>
                         @else
                             <div class="flex flex-col gap-3.5">
                                 <div class="grid grid-cols-2 gap-3.5">
                                     <a href="{{ route('login') }}" class="flex items-center justify-center px-4 py-3.5 text-sm font-extrabold text-slate-800 dark:text-slate-200 bg-slate-100 dark:bg-gray-800 rounded-2xl hover:bg-slate-200 dark:hover:bg-gray-800 active:scale-[0.98] transition">
                                         Masuk
                                     </a>
                                     @if (Route::has('register'))
                                         <a href="{{ route('register') }}" class="flex items-center justify-center px-4 py-3.5 text-sm font-extrabold text-white bg-gradient-to-r from-teal-600 to-emerald-600 rounded-2xl hover:from-teal-700 hover:to-emerald-700 shadow-md shadow-teal-600/15 active:scale-[0.98] transition">
                                             Daftar
                                         </a>
                                     @endif
                                 </div>
                                 <div class="flex items-center justify-between px-4 py-3 bg-slate-50 dark:bg-gray-800/30 rounded-2xl border border-slate-100 dark:border-gray-800/80">
                                     <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Mode Gelap</span>
                                     <x-theme-toggle class="p-2 text-slate-500 bg-white dark:bg-gray-800 dark:text-gray-300 rounded-xl border border-slate-200/50 dark:border-gray-700/50 flex items-center justify-center" />
                                 </div>
                             </div>
                         @endauth
                     @endif
                 </div>
             </div>
         </div>
     </nav>

    @include('public.welcome._hero')
    @include('public.welcome._stats')
    @include('public.welcome._alur-magang')
    @include('public.welcome._lowongan-grid')
    @include('public.welcome._faq')
    @include('public.welcome._footer')

</body>
</html>