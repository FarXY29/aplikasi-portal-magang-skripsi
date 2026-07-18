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

    <!-- MOBILE BOTTOM NAVIGATION BAR (Android & iOS Phones < md) -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-xl border-t border-gray-200/80 shadow-[0_-4px_25px_rgba(0,0,0,0.06)] px-2 py-1.5 flex items-center justify-around pb-safe">
        
        <!-- 1. BERANDA (Semua Role) -->
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all {{ request()->routeIs('dashboard') ? 'text-teal-600 font-black scale-105' : 'text-gray-400 font-semibold hover:text-gray-600' }}">
            <div class="relative flex items-center justify-center w-8 h-8 {{ request()->routeIs('dashboard') ? 'bg-teal-50 rounded-xl text-teal-600 shadow-2xs' : '' }}">
                <i class="fas fa-home text-base"></i>
            </div>
            <span class="text-[10px] mt-0.5 tracking-tight font-bold">Beranda</span>
        </a>

        @if(Auth::user()->role == 'peserta')
            <!-- 2. LOGBOOK (Peserta) -->
            <a href="{{ route('peserta.logbook.index') }}" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all {{ request()->routeIs('peserta.logbook.*') ? 'text-teal-600 font-black scale-105' : 'text-gray-400 font-semibold hover:text-gray-600' }}">
                <div class="relative flex items-center justify-center w-8 h-8 {{ request()->routeIs('peserta.logbook.*') ? 'bg-teal-50 rounded-xl text-teal-600 shadow-2xs' : '' }}">
                    <i class="fas fa-book-open text-base"></i>
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight font-bold">Logbook</span>
            </a>

            <!-- 3. ABSENSI (Peserta) -->
            <a href="{{ route('peserta.absensi.index') }}" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all {{ request()->routeIs('peserta.absensi.*') ? 'text-teal-600 font-black scale-105' : 'text-gray-400 font-semibold hover:text-gray-600' }}">
                <div class="relative flex items-center justify-center w-8 h-8 {{ request()->routeIs('peserta.absensi.*') ? 'bg-teal-50 rounded-xl text-teal-600 shadow-2xs' : '' }}">
                    <i class="fas fa-user-clock text-base"></i>
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight font-bold">Absensi</span>
            </a>

            <!-- 4. SERTIFIKAT (Peserta) -->
            <a href="{{ route('peserta.sertifikat') }}" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all {{ request()->routeIs('peserta.sertifikat') ? 'text-teal-600 font-black scale-105' : 'text-gray-400 font-semibold hover:text-gray-600' }}">
                <div class="relative flex items-center justify-center w-8 h-8 {{ request()->routeIs('peserta.sertifikat') ? 'bg-teal-50 rounded-xl text-teal-600 shadow-2xs' : '' }}">
                    <i class="fas fa-award text-base"></i>
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight font-bold">Sertifikat</span>
            </a>
        @elseif(Auth::user()->role == 'admin_instansi')
            <!-- 2. LOWONGAN (Admin Instansi) -->
            <a href="{{ route('dinas.lowongan.index') }}" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all {{ request()->routeIs('dinas.lowongan.*') ? 'text-teal-600 font-black scale-105' : 'text-gray-400 font-semibold hover:text-gray-600' }}">
                <div class="relative flex items-center justify-center w-8 h-8 {{ request()->routeIs('dinas.lowongan.*') ? 'bg-teal-50 rounded-xl text-teal-600 shadow-2xs' : '' }}">
                    <i class="fas fa-briefcase text-base"></i>
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight font-bold">Lowongan</span>
            </a>

            <!-- 3. PELAMAR (Admin Instansi) -->
            <a href="{{ route('dinas.pelamar') }}" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all {{ request()->routeIs('dinas.pelamar') ? 'text-teal-600 font-black scale-105' : 'text-gray-400 font-semibold hover:text-gray-600' }}">
                <div class="relative flex items-center justify-center w-8 h-8 {{ request()->routeIs('dinas.pelamar') ? 'bg-teal-50 rounded-xl text-teal-600 shadow-2xs' : '' }}">
                    <i class="fas fa-envelope-open-text text-base"></i>
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight font-bold">Pelamar</span>
            </a>

            <!-- 4. PESERTA (Admin Instansi) -->
            <a href="{{ route('dinas.peserta.index') }}" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all {{ request()->routeIs('dinas.peserta.*') ? 'text-teal-600 font-black scale-105' : 'text-gray-400 font-semibold hover:text-gray-600' }}">
                <div class="relative flex items-center justify-center w-8 h-8 {{ request()->routeIs('dinas.peserta.*') ? 'bg-teal-50 rounded-xl text-teal-600 shadow-2xs' : '' }}">
                    <i class="fas fa-user-check text-base"></i>
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight font-bold">Peserta</span>
            </a>
        @elseif(Auth::user()->role == 'admin_kota')
            <!-- 2. INSTANSI (Admin Kota) -->
            <a href="{{ route('admin.instansi.index') }}" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all {{ request()->routeIs('admin.instansi.*') ? 'text-teal-600 font-black scale-105' : 'text-gray-400 font-semibold hover:text-gray-600' }}">
                <div class="relative flex items-center justify-center w-8 h-8 {{ request()->routeIs('admin.instansi.*') ? 'bg-teal-50 rounded-xl text-teal-600 shadow-2xs' : '' }}">
                    <i class="fas fa-building text-base"></i>
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight font-bold">Instansi</span>
            </a>

            <!-- 3. PENGGUNA (Admin Kota) -->
            <a href="{{ route('admin.users.index') }}" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all {{ request()->routeIs('admin.users.*') ? 'text-teal-600 font-black scale-105' : 'text-gray-400 font-semibold hover:text-gray-600' }}">
                <div class="relative flex items-center justify-center w-8 h-8 {{ request()->routeIs('admin.users.*') ? 'bg-teal-50 rounded-xl text-teal-600 shadow-2xs' : '' }}">
                    <i class="fas fa-users-cog text-base"></i>
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight font-bold">Pengguna</span>
            </a>

            <!-- 4. LAPORAN (Admin Kota) -->
            <a href="{{ route('admin.laporan.hub') }}" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all {{ request()->routeIs('admin.laporan.*') ? 'text-teal-600 font-black scale-105' : 'text-gray-400 font-semibold hover:text-gray-600' }}">
                <div class="relative flex items-center justify-center w-8 h-8 {{ request()->routeIs('admin.laporan.*') ? 'bg-teal-50 rounded-xl text-teal-600 shadow-2xs' : '' }}">
                    <i class="fas fa-chart-pie text-base"></i>
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight font-bold">Laporan</span>
            </a>
        @elseif(Auth::user()->role == 'pembimbing_lapangan')
            <!-- 2. ABSENSI (Pembimbing Lapangan) -->
            <a href="{{ route('pembimbing_lapangan.attendance.index') }}" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all {{ request()->routeIs('pembimbing_lapangan.attendance.*') ? 'text-teal-600 font-black scale-105' : 'text-gray-400 font-semibold hover:text-gray-600' }}">
                <div class="relative flex items-center justify-center w-8 h-8 {{ request()->routeIs('pembimbing_lapangan.attendance.*') ? 'bg-teal-50 rounded-xl text-teal-600 shadow-2xs' : '' }}">
                    <i class="fas fa-clipboard-list text-base"></i>
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight font-bold">Absensi</span>
            </a>

            <!-- 3. PROFIL -->
            <a href="{{ route('profile.edit') }}" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all {{ request()->routeIs('profile.*') ? 'text-teal-600 font-black scale-105' : 'text-gray-400 font-semibold hover:text-gray-600' }}">
                <div class="relative flex items-center justify-center w-8 h-8 {{ request()->routeIs('profile.*') ? 'bg-teal-50 rounded-xl text-teal-600 shadow-2xs' : '' }}">
                    <i class="fas fa-user-circle text-base"></i>
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight font-bold">Profil</span>
            </a>
        @elseif(Auth::user()->role == 'pembimbing')
            <!-- 2. PROFIL -->
            <a href="{{ route('profile.edit') }}" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all {{ request()->routeIs('profile.*') ? 'text-teal-600 font-black scale-105' : 'text-gray-400 font-semibold hover:text-gray-600' }}">
                <div class="relative flex items-center justify-center w-8 h-8 {{ request()->routeIs('profile.*') ? 'bg-teal-50 rounded-xl text-teal-600 shadow-2xs' : '' }}">
                    <i class="fas fa-user-circle text-base"></i>
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight font-bold">Profil</span>
            </a>
        @endif

        <!-- MENU LAINNYA / PROFIL & QUICK SHEETS (Semua Role) -->
        <button @click="$dispatch('open-mobile-menu')" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all text-gray-400 font-semibold hover:text-gray-600 focus:outline-none">
            <div class="relative flex items-center justify-center w-8 h-8">
                <i class="fas fa-grid-horizontal text-base"></i>
            </div>
            <span class="text-[10px] mt-0.5 tracking-tight font-bold">Menu</span>
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
            <div class="p-5 bg-gradient-to-r from-teal-700 via-teal-600 to-teal-700 text-white flex items-center justify-between">
                <div class="flex items-center gap-3.5 min-w-0">
                    <div class="w-12 h-12 rounded-xl bg-white text-teal-700 font-black text-lg flex items-center justify-center shadow-md flex-shrink-0">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <h4 class="font-black text-base leading-tight truncate">{{ Auth::user()->name }}</h4>
                        <p class="text-xs text-teal-100 font-medium truncate mt-0.5">{{ Auth::user()->email }}</p>
                        <span class="inline-block mt-1.5 px-2.5 py-0.5 bg-teal-900/60 text-teal-100 text-[9px] font-black rounded-md uppercase tracking-wider border border-teal-500/40">
                            {{ str_replace('_', ' ', Auth::user()->role) }}
                        </span>
                    </div>
                </div>
                <button @click="mobileMenuOpen = false" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition flex-shrink-0">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- List Menu Navigasi Lengkap -->
            <div class="p-4 overflow-y-auto custom-scrollbar flex-1 space-y-2">
                <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest px-2 mb-1">Navigasi Aplikasi</p>
                
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('dashboard') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-th-large w-5 text-center {{ request()->routeIs('dashboard') ? 'text-teal-600' : 'text-gray-400' }}"></i> Beranda / Dashboard
                </a>

                @if(Auth::user()->role == 'peserta')
                    <a href="{{ route('peserta.logbook.index') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('peserta.logbook.*') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-book-open w-5 text-center {{ request()->routeIs('peserta.logbook.*') ? 'text-teal-600' : 'text-gray-400' }}"></i> Logbook Harian
                    </a>
                    <a href="{{ route('peserta.absensi.index') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('peserta.absensi.*') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-user-clock w-5 text-center {{ request()->routeIs('peserta.absensi.*') ? 'text-teal-600' : 'text-gray-400' }}"></i> Absensi Kehadiran
                    </a>
                    <a href="{{ route('peserta.sertifikat') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('peserta.sertifikat') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-award w-5 text-center {{ request()->routeIs('peserta.sertifikat') ? 'text-teal-600' : 'text-gray-400' }}"></i> Sertifikat Magang
                    </a>
                @elseif(Auth::user()->role == 'admin_kota')
                    <a href="{{ route('admin.instansi.index') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('admin.instansi.*') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-building w-5 text-center {{ request()->routeIs('admin.instansi.*') ? 'text-teal-600' : 'text-gray-400' }}"></i> Kelola Data Instansi
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('admin.users.index') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-users-cog w-5 text-center {{ request()->routeIs('admin.users.index') ? 'text-teal-600' : 'text-gray-400' }}"></i> Manajemen Pengguna
                    </a>
                    <a href="{{ route('admin.laporan.hub') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('admin.laporan.*') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-chart-pie w-5 text-center {{ request()->routeIs('admin.laporan.*') ? 'text-teal-600' : 'text-gray-400' }}"></i> Pusat Laporan Hub
                    </a>
                    <a href="{{ route('admin.users.logbooks') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('admin.users.logbooks*') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-book w-5 text-center {{ request()->routeIs('admin.users.logbooks*') ? 'text-teal-600' : 'text-gray-400' }}"></i> Monitoring Logbook
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('admin.settings.*') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-cogs w-5 text-center {{ request()->routeIs('admin.settings.*') ? 'text-teal-600' : 'text-gray-400' }}"></i> Pengaturan Sistem
                    </a>
                @elseif(Auth::user()->role == 'admin_instansi')
                    <a href="{{ route('dinas.lowongan.index') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('dinas.lowongan.*') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-briefcase w-5 text-center {{ request()->routeIs('dinas.lowongan.*') ? 'text-teal-600' : 'text-gray-400' }}"></i> Kelola Lowongan
                    </a>
                    <a href="{{ route('dinas.pelamar') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('dinas.pelamar') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-envelope-open-text w-5 text-center {{ request()->routeIs('dinas.pelamar') ? 'text-teal-600' : 'text-gray-400' }}"></i> Pelamar Masuk
                    </a>
                    <a href="{{ route('dinas.peserta.index') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('dinas.peserta.index') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-user-check w-5 text-center {{ request()->routeIs('dinas.peserta.index') ? 'text-teal-600' : 'text-gray-400' }}"></i> Monitoring Peserta
                    </a>
                    <a href="{{ route('dinas.pembimbing_lapangan.index') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('dinas.pembimbing_lapangan.*') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-chalkboard-teacher w-5 text-center {{ request()->routeIs('dinas.pembimbing_lapangan.*') ? 'text-teal-600' : 'text-gray-400' }}"></i> Data Pembimbing
                    </a>
                    <a href="{{ route('dinas.laporan.hub') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('dinas.laporan.hub') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-chart-pie w-5 text-center {{ request()->routeIs('dinas.laporan.hub') ? 'text-teal-600' : 'text-gray-400' }}"></i> Pusat Laporan Hub
                    </a>
                    <a href="{{ route('dinas.settings') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('dinas.settings') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-sliders-h w-5 text-center {{ request()->routeIs('dinas.settings') ? 'text-teal-600' : 'text-gray-400' }}"></i> Pengaturan Geofencing
                    </a>
                @elseif(Auth::user()->role == 'pembimbing_lapangan')
                    <a href="{{ route('pembimbing_lapangan.attendance.index') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('pembimbing_lapangan.attendance.*') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-clipboard-list w-5 text-center {{ request()->routeIs('pembimbing_lapangan.attendance.*') ? 'text-teal-600' : 'text-gray-400' }}"></i> Absensi Peserta
                    </a>
                @elseif(Auth::user()->role == 'pembimbing')
                    <a href="{{ route('pembimbing.dashboard') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('pembimbing.dashboard') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-user-graduate w-5 text-center {{ request()->routeIs('pembimbing.dashboard') ? 'text-teal-600' : 'text-gray-400' }}"></i> Daftar Mahasiswa
                    </a>
                @endif

                <div class="border-t border-gray-100 my-3 pt-3">
                    <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest px-2 mb-1.5">Akun & Sistem</p>
                    
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('profile.*') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-user-circle w-5 text-center {{ request()->routeIs('profile.*') ? 'text-teal-600' : 'text-gray-400' }}"></i> Pengaturan Akun (Profile)
                    </a>

                    <a href="{{ route('home') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-globe w-5 text-center text-gray-400"></i> Beranda Publik
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="w-full mt-2">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-sm font-black text-red-600 bg-red-50 hover:bg-red-600 hover:text-white transition-all duration-200 text-left shadow-xs border border-red-100">
                            <i class="fas fa-sign-out-alt w-5 text-center"></i> Keluar dari Aplikasi (Logout)
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="p-3 bg-gray-50 border-t border-gray-100 text-center">
                <p class="text-[10px] text-gray-400 font-extrabold tracking-wider">Portal Magang v2.0 &bull; Pemkot Banjarmasin</p>
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