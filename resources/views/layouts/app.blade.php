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
<body class="font-sans antialiased bg-gray-50" x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false">
    
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
            
            <!-- DESKTOP & TABLET HEADER (md and above only) -->
            <header class="hidden md:flex bg-white border-b border-gray-200 min-h-[4rem] py-3 sm:py-0 items-center justify-between px-4 sm:px-6 lg:px-8 z-30 shadow-sm">
                
                <div class="flex items-start sm:items-center gap-3 sm:gap-4 flex-1 min-w-0">
                    <button @click="sidebarOpen = true" class="p-2 -ml-2 text-gray-600 hover:text-teal-600 hover:bg-gray-100 rounded-xl focus:outline-none lg:hidden transition flex-shrink-0 mt-0.5 sm:mt-0" title="Buka Menu">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <div class="flex-1 min-w-0">
                        @if(isset($header))
                            {{ $header }}
                        @else
                            <h2 class="font-bold text-lg text-gray-800 leading-tight truncate">
                                Dashboard
                            </h2>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-3 print:hidden">
                    <div class="hidden md:flex items-center gap-2 text-xs font-bold text-gray-600 bg-gray-50/80 px-3 py-1.5 rounded-xl border border-gray-100 shadow-sm">
                        <i class="far fa-calendar-alt text-teal-600"></i>
                        <span>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
                    </div>
                    
                    <div id="realtime-clock" class="hidden sm:flex items-center gap-1.5 text-xs font-mono font-bold text-teal-700 bg-teal-50/50 px-3 py-1.5 rounded-xl border border-teal-100 shadow-sm">
                        <i class="far fa-clock"></i>
                        <span id="clock-span">00:00:00</span>
                    </div>
                </div>
            </header>

            <!-- MOBILE NATIVE TOP BAR (Android & iOS Phones Only, < md) -->
            <header class="md:hidden sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-gray-200/80 px-4 py-2.5 flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2.5 min-w-0">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <x-application-logo class="block h-8 w-8 fill-current text-teal-600 flex-shrink-0" />
                        <div class="min-w-0">
                            <h1 class="text-xs font-black text-gray-900 leading-none truncate">Portal<span class="text-teal-600">Magang</span></h1>
                            <p class="text-[9px] font-bold text-gray-500 uppercase tracking-wider mt-0.5 truncate">{{ str_replace('_', ' ', Auth::user()->role) }}</p>
                        </div>
                    </a>
                </div>
                <div class="flex items-center gap-2">
                    <div id="mobile-clock" class="text-[10px] font-mono font-black text-teal-700 bg-teal-50 px-2 py-1 rounded-lg border border-teal-150">
                        <i class="far fa-clock mr-0.5"></i> <span id="mobile-clock-span">00:00:00</span>
                    </div>
                    <button @click="$dispatch('open-mobile-menu')" class="w-8 h-8 rounded-full bg-gradient-to-tr from-teal-600 to-teal-500 text-white flex items-center justify-center font-black text-xs shadow-sm ring-2 ring-teal-100 active:scale-95 transition">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </button>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 custom-scrollbar p-3.5 pb-24 md:p-6 lg:p-8 md:pb-8">
                {{ $slot }}
            </main>

        </div>
    </div>

    <!-- MOBILE BOTTOM NAVIGATION BAR (Android & iOS Phones Only, < md) -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-lg border-t border-gray-200/80 shadow-[0_-4px_20px_rgba(0,0,0,0.06)] px-2 py-1.5 flex items-center justify-around">
        
        <!-- 1. DASHBOARD / BERANDA (Semua Role) -->
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all {{ request()->routeIs('dashboard') ? 'text-teal-600 font-extrabold scale-105' : 'text-gray-400 font-medium hover:text-gray-600' }}">
            <div class="relative flex items-center justify-center w-7 h-7 {{ request()->routeIs('dashboard') ? 'bg-teal-50 rounded-xl text-teal-600' : '' }}">
                <i class="fas fa-home text-base"></i>
            </div>
            <span class="text-[10px] mt-0.5 tracking-tight">Beranda</span>
        </a>

        @if(Auth::user()->role == 'peserta')
            <!-- 2. LOGBOOK (Peserta) -->
            <a href="{{ route('peserta.logbook.index') }}" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all {{ request()->routeIs('peserta.logbook.*') ? 'text-teal-600 font-extrabold scale-105' : 'text-gray-400 font-medium hover:text-gray-600' }}">
                <div class="relative flex items-center justify-center w-7 h-7 {{ request()->routeIs('peserta.logbook.*') ? 'bg-teal-50 rounded-xl text-teal-600' : '' }}">
                    <i class="fas fa-book-open text-base"></i>
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight">Logbook</span>
            </a>

            <!-- 3. SERTIFIKAT (Peserta) -->
            <a href="{{ route('peserta.sertifikat') }}" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all {{ request()->routeIs('peserta.sertifikat') ? 'text-teal-600 font-extrabold scale-105' : 'text-gray-400 font-medium hover:text-gray-600' }}">
                <div class="relative flex items-center justify-center w-7 h-7 {{ request()->routeIs('peserta.sertifikat') ? 'bg-teal-50 rounded-xl text-teal-600' : '' }}">
                    <i class="fas fa-award text-base"></i>
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight">Sertifikat</span>
            </a>
        @elseif(Auth::user()->role == 'admin_instansi')
            <!-- 2. LOWONGAN (Admin Instansi) -->
            <a href="{{ route('dinas.lowongan.index') }}" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all {{ request()->routeIs('dinas.lowongan.*') ? 'text-teal-600 font-extrabold scale-105' : 'text-gray-400 font-medium hover:text-gray-600' }}">
                <div class="relative flex items-center justify-center w-7 h-7 {{ request()->routeIs('dinas.lowongan.*') ? 'bg-teal-50 rounded-xl text-teal-600' : '' }}">
                    <i class="fas fa-briefcase text-base"></i>
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight">Lowongan</span>
            </a>

            <!-- 3. PELAMAR (Admin Instansi) -->
            <a href="{{ route('dinas.pelamar') }}" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all {{ request()->routeIs('dinas.pelamar') ? 'text-teal-600 font-extrabold scale-105' : 'text-gray-400 font-medium hover:text-gray-600' }}">
                <div class="relative flex items-center justify-center w-7 h-7 {{ request()->routeIs('dinas.pelamar') ? 'bg-teal-50 rounded-xl text-teal-600' : '' }}">
                    <i class="fas fa-envelope-open-text text-base"></i>
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight">Pelamar</span>
            </a>

            <!-- 4. PESERTA (Admin Instansi) -->
            <a href="{{ route('dinas.peserta.index') }}" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all {{ request()->routeIs('dinas.peserta.*') ? 'text-teal-600 font-extrabold scale-105' : 'text-gray-400 font-medium hover:text-gray-600' }}">
                <div class="relative flex items-center justify-center w-7 h-7 {{ request()->routeIs('dinas.peserta.*') ? 'bg-teal-50 rounded-xl text-teal-600' : '' }}">
                    <i class="fas fa-user-check text-base"></i>
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight">Peserta</span>
            </a>
        @elseif(Auth::user()->role == 'admin_kota')
            <!-- 2. INSTANSI (Admin Kota) -->
            <a href="{{ route('admin.instansi.index') }}" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all {{ request()->routeIs('admin.instansi.*') ? 'text-teal-600 font-extrabold scale-105' : 'text-gray-400 font-medium hover:text-gray-600' }}">
                <div class="relative flex items-center justify-center w-7 h-7 {{ request()->routeIs('admin.instansi.*') ? 'bg-teal-50 rounded-xl text-teal-600' : '' }}">
                    <i class="fas fa-building text-base"></i>
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight">Instansi</span>
            </a>

            <!-- 3. PENGGUNA (Admin Kota) -->
            <a href="{{ route('admin.users.index') }}" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all {{ request()->routeIs('admin.users.*') ? 'text-teal-600 font-extrabold scale-105' : 'text-gray-400 font-medium hover:text-gray-600' }}">
                <div class="relative flex items-center justify-center w-7 h-7 {{ request()->routeIs('admin.users.*') ? 'bg-teal-50 rounded-xl text-teal-600' : '' }}">
                    <i class="fas fa-users-cog text-base"></i>
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight">Pengguna</span>
            </a>

            <!-- 4. LAPORAN (Admin Kota) -->
            <a href="{{ route('admin.laporan.hub') }}" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all {{ request()->routeIs('admin.laporan.*') ? 'text-teal-600 font-extrabold scale-105' : 'text-gray-400 font-medium hover:text-gray-600' }}">
                <div class="relative flex items-center justify-center w-7 h-7 {{ request()->routeIs('admin.laporan.*') ? 'bg-teal-50 rounded-xl text-teal-600' : '' }}">
                    <i class="fas fa-chart-pie text-base"></i>
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight">Laporan</span>
            </a>
        @elseif(Auth::user()->role == 'pembimbing_lapangan')
            <!-- 2. ABSENSI (Pembimbing Lapangan) -->
            <a href="{{ route('pembimbing_lapangan.attendance.index') }}" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all {{ request()->routeIs('pembimbing_lapangan.attendance.*') ? 'text-teal-600 font-extrabold scale-105' : 'text-gray-400 font-medium hover:text-gray-600' }}">
                <div class="relative flex items-center justify-center w-7 h-7 {{ request()->routeIs('pembimbing_lapangan.attendance.*') ? 'bg-teal-50 rounded-xl text-teal-600' : '' }}">
                    <i class="fas fa-clipboard-list text-base"></i>
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight">Absensi</span>
            </a>
        @endif

        <!-- MENU LAINNYA / PROFIL (Semua Role) -->
        <button @click="$dispatch('open-mobile-menu')" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all text-gray-400 font-medium hover:text-gray-600 focus:outline-none">
            <div class="relative flex items-center justify-center w-7 h-7">
                <i class="fas fa-grid-horizontal text-base"></i>
            </div>
            <span class="text-[10px] mt-0.5 tracking-tight">Menu</span>
        </button>
    </nav>

    <!-- MOBILE iOS/Android BOTTOM SHEET MENU (`md:hidden`) -->
    <div x-data="{ mobileMenuOpen: false }" 
         @open-mobile-menu.window="mobileMenuOpen = true" 
         @keydown.escape.window="mobileMenuOpen = false"
         class="md:hidden">
        
        <!-- Backdrop -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition-opacity ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="mobileMenuOpen = false"
             class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs"
             x-cloak>
        </div>

        <!-- Sheet Panel -->
        <div x-show="mobileMenuOpen"
             x-transition:enter="transition-transform ease-out duration-300"
             x-transition:enter-start="translate-y-full"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="transition-transform ease-in duration-200"
             x-transition:leave-start="translate-y-0"
             x-transition:leave-end="translate-y-full"
             class="fixed bottom-0 left-0 right-0 z-50 bg-white rounded-t-3xl shadow-2xl max-h-[85vh] flex flex-col overflow-hidden"
             x-cloak>
             
            <!-- Grabber Bar -->
            <div class="w-full py-3 flex justify-center cursor-pointer bg-gray-50/80 border-b border-gray-100" @click="mobileMenuOpen = false">
                <div class="w-12 h-1.5 bg-gray-300 rounded-full"></div>
            </div>

            <!-- Header Info User -->
            <div class="p-5 bg-gradient-to-r from-teal-600 to-teal-700 text-white flex items-center justify-between">
                <div class="flex items-center gap-3.5 min-w-0">
                    <div class="w-12 h-12 rounded-full bg-white text-teal-700 font-black text-lg flex items-center justify-center shadow-md flex-shrink-0">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <h4 class="font-black text-base leading-tight truncate">{{ Auth::user()->name }}</h4>
                        <p class="text-xs text-teal-100 font-medium truncate mt-0.5">{{ Auth::user()->email }}</p>
                        <span class="inline-block mt-1 px-2 py-0.5 bg-teal-800/60 text-teal-100 text-[9px] font-extrabold rounded-md uppercase tracking-wider">
                            {{ str_replace('_', ' ', Auth::user()->role) }}
                        </span>
                    </div>
                </div>
                <button @click="mobileMenuOpen = false" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition flex-shrink-0">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- List Menu Penuh -->
            <div class="p-4 overflow-y-auto custom-scrollbar flex-1 space-y-2">
                <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest px-2 mb-1">Navigasi Aplikasi</p>
                
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('dashboard') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-th-large w-6 text-center text-teal-600"></i> Beranda / Dashboard
                </a>

                @if(Auth::user()->role == 'admin_kota')
                    <a href="{{ route('admin.instansi.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('admin.instansi.*') ? 'bg-teal-50 text-teal-700 border border-teal-200' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-building w-6 text-center text-teal-600"></i> Kelola Data Instansi
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('admin.users.index') ? 'bg-teal-50 text-teal-700 border border-teal-200' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-users-cog w-6 text-center text-teal-600"></i> Manajemen Pengguna
                    </a>
                    <a href="{{ route('admin.laporan.hub') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('admin.laporan.hub') ? 'bg-teal-50 text-teal-700 border border-teal-200' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-chart-pie w-6 text-center text-teal-600"></i> Pusat Laporan
                    </a>
                    <a href="{{ route('admin.users.logbooks') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('admin.users.logbooks') ? 'bg-teal-50 text-teal-700 border border-teal-200' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-book w-6 text-center text-teal-600"></i> Monitoring Logbook
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('admin.settings.*') ? 'bg-teal-50 text-teal-700 border border-teal-200' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-cogs w-6 text-center text-teal-600"></i> Pengaturan Sistem
                    </a>
                @elseif(Auth::user()->role == 'admin_instansi')
                    <a href="{{ route('dinas.lowongan.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('dinas.lowongan.*') ? 'bg-teal-50 text-teal-700 border border-teal-200' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-briefcase w-6 text-center text-teal-600"></i> Kelola Lowongan
                    </a>
                    <a href="{{ route('dinas.pelamar') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('dinas.pelamar') ? 'bg-teal-50 text-teal-700 border border-teal-200' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-envelope-open-text w-6 text-center text-teal-600"></i> Pelamar Masuk
                    </a>
                    <a href="{{ route('dinas.peserta.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('dinas.peserta.index') ? 'bg-teal-50 text-teal-700 border border-teal-200' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-user-check w-6 text-center text-teal-600"></i> Monitoring Peserta
                    </a>
                    <a href="{{ route('dinas.pembimbing_lapangan.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('dinas.pembimbing_lapangan.*') ? 'bg-teal-50 text-teal-700 border border-teal-200' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-chalkboard-teacher w-6 text-center text-teal-600"></i> Data Pembimbing
                    </a>
                    <a href="{{ route('dinas.laporan.hub') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('dinas.laporan.hub') ? 'bg-teal-50 text-teal-700 border border-teal-200' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-chart-pie w-6 text-center text-teal-600"></i> Pusat Laporan
                    </a>
                    <a href="{{ route('dinas.settings') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('dinas.settings') ? 'bg-teal-50 text-teal-700 border border-teal-200' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-sliders-h w-6 text-center text-teal-600"></i> Pengaturan Geofencing
                    </a>
                @elseif(Auth::user()->role == 'pembimbing_lapangan')
                    <a href="{{ route('pembimbing_lapangan.attendance.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('pembimbing_lapangan.attendance.*') ? 'bg-teal-50 text-teal-700 border border-teal-200' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-clipboard-list w-6 text-center text-teal-600"></i> Absensi Peserta
                    </a>
                @elseif(Auth::user()->role == 'peserta')
                    <a href="{{ route('peserta.logbook.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('peserta.logbook.*') ? 'bg-teal-50 text-teal-700 border border-teal-200' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-book-open w-6 text-center text-teal-600"></i> Logbook Harian
                    </a>
                    <a href="{{ route('peserta.sertifikat') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('peserta.sertifikat') ? 'bg-teal-50 text-teal-700 border border-teal-200' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-award w-6 text-center text-teal-600"></i> Sertifikat Magang
                    </a>
                @endif

                <div class="border-t border-gray-100 my-2 pt-2">
                    <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest px-2 mb-1">Akun & Pengaturan</p>
                    
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-user-cog w-6 text-center text-gray-500"></i> Pengaturan Akun (Profile)
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="w-full mt-1">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-black text-red-600 bg-red-50 hover:bg-red-100/80 transition text-left">
                            <i class="fas fa-sign-out-alt w-6 text-center text-red-500"></i> Keluar dari Aplikasi (Logout)
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="p-3 bg-gray-50 border-t border-gray-100 text-center">
                <p class="text-[10px] text-gray-400 font-medium">Portal Magang Mobile v2.0 &bull; Pemkot Banjarmasin</p>
            </div>
        </div>
    </div>
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
    @stack('scripts')
</body>
</html>