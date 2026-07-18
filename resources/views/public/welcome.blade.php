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
        
        /* Custom Modern Scrollbar */
        ::-webkit-scrollbar { 
            width: 8px; 
            height: 8px;
        }
        ::-webkit-scrollbar-track { 
            background: #f1f5f9; 
        }
        ::-webkit-scrollbar-thumb { 
            background: #cbd5e1; 
            border-radius: 9999px;
            border: 2px solid #f1f5f9;
        }
        ::-webkit-scrollbar-thumb:hover { 
            background: #94a3b8; 
        }

        /* Hide horizontal scrollbar but keep functionality for Quick Pills */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Ambient Floating Blobs Animation */
        @keyframes float-ambient {
            0%, 100% { transform: translateY(0px) scale(1) rotate(0deg); }
            50% { transform: translateY(-20px) scale(1.08) rotate(180deg); }
        }
        .animate-ambient-1 {
            animation: float-ambient 12s ease-in-out infinite;
        }
        .animate-ambient-2 {
            animation: float-ambient 16s ease-in-out infinite 3s;
        }

        /* Premium Sasirangan Modern Background */
        .bg-sasirangan-premium {
            background-color: #042f2e; /* Slate/teal dark slate */
            background-image: 
                radial-gradient(circle at 80% 20%, rgba(20, 184, 166, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 15% 80%, rgba(16, 185, 129, 0.12) 0%, transparent 50%),
                linear-gradient(to bottom right, rgba(4, 47, 46, 0.95), rgba(6, 78, 59, 0.98)),
                url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2314b8a6' fill-opacity='0.05'%3E%3Cpath d='M40 38v-8h-4v8h-8v4h8v8h4v-8h8v-4h-8zm0-36V0h-4v2h-8v4h8v8h4V6h8V2h-8zM8 38v-8H4v8H0v4h4v8h4v-8h8v-4H8zM8 2V0H4v2H0v4h4v8h4V6h8V2H8z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            background-size: 100% 100%, 100% 100%, cover, auto;
        }

        /* styling content CKEditor standard */
        .ck-content ul { list-style-type: disc; padding-left: 20px; margin-bottom: 0.75rem; }
        .ck-content ol { list-style-type: decimal; padding-left: 20px; margin-bottom: 0.75rem; }
        .ck-content h2 { font-size: 1.3em; font-weight: 700; margin-top: 1.25rem; color: #0f172a; }
        .ck-content h3 { font-size: 1.15em; font-weight: 600; margin-top: 1rem; color: #334155; }
        .ck-content p { margin-bottom: 0.75em; line-height: 1.7; }

        /* Safe area bottom navigation */
        .pb-safe {
            padding-bottom: env(safe-area-inset-bottom, 1rem);
        }
    </style>
</head>
<body class="bg-slate-50/50 text-slate-600 flex flex-col min-h-screen overflow-x-hidden w-full antialiased">

    <!-- Header & Navigation Section -->
    <nav x-data="{ mobileMenuOpen: false, scrolled: false }" 
         x-init="scrolled = ((window.scrollY || window.pageYOffset) > 20); $nextTick(() => { scrolled = ((window.scrollY || window.pageYOffset) > 20) });"
         @scroll.window="scrolled = ((window.scrollY || window.pageYOffset) > 20)"
         :class="scrolled ? 'bg-white/80 backdrop-blur-xl shadow-lg shadow-slate-100/50 border-b border-slate-100 py-3' : 'bg-transparent py-5 sm:py-6'"
         class="fixed w-full top-0 z-50 transition-all duration-500 ease-in-out">
         <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
             <div class="flex justify-between h-14 sm:h-16 items-center w-full">
                 <!-- Brand Logo -->
                 <a href="{{ url('/') }}" class="flex items-center gap-3 group focus:outline-none">
                     <div class="bg-white rounded-2xl p-2 shadow-md border border-slate-100/80 flex items-center justify-center shrink-0 transition-transform duration-500 group-hover:scale-105">
                         <x-application-logo class="w-8 h-8 sm:w-9 sm:h-9 fill-current text-teal-600" />
                     </div>
                     <div class="flex flex-col">
                         <span class="text-base sm:text-lg font-black leading-none tracking-tight uppercase transition-colors duration-300" 
                               :class="scrolled ? 'text-slate-900' : 'text-white group-hover:text-teal-200'">SiMagang</span>
                         <span class="text-[9px] font-extrabold tracking-widest uppercase transition-colors duration-300 mt-1" 
                               :class="scrolled ? 'text-teal-600' : 'text-teal-300/90'">Kota Banjarmasin</span>
                     </div>
                 </a>

                 <!-- Desktop Navigation Menu -->
                 <div class="hidden md:flex items-center gap-8">
                     <a href="#lowongan" class="text-sm font-bold tracking-wide transition-all relative py-1 after:absolute after:bottom-0 after:left-0 after:w-0 after:h-0.5 after:bg-teal-500 after:transition-all hover:after:w-full" :class="scrolled ? 'text-slate-600 hover:text-teal-600' : 'text-white/90 hover:text-white'">Cari Lowongan</a>
                     <a href="#langkah" class="text-sm font-bold tracking-wide transition-all relative py-1 after:absolute after:bottom-0 after:left-0 after:w-0 after:h-0.5 after:bg-teal-500 after:transition-all hover:after:w-full" :class="scrolled ? 'text-slate-600 hover:text-teal-600' : 'text-white/90 hover:text-white'">Alur Magang</a>
                     <a href="#faq" class="text-sm font-bold tracking-wide transition-all relative py-1 after:absolute after:bottom-0 after:left-0 after:w-0 after:h-0.5 after:bg-teal-500 after:transition-all hover:after:w-full" :class="scrolled ? 'text-slate-600 hover:text-teal-600' : 'text-white/90 hover:text-white'">FAQ</a>
                     
                     <div class="h-5 w-[1px] bg-slate-200/40" :class="scrolled ? 'bg-slate-200' : 'bg-white/20'"></div>

                     @if (Route::has('login'))
                         @auth
                             <a href="{{ url('/dashboard') }}" class="bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white px-5 py-2.5 rounded-2xl font-bold shadow-md shadow-teal-600/10 hover:shadow-lg hover:shadow-teal-600/25 transition-all text-xs sm:text-sm transform hover:-translate-y-0.5 active:translate-y-0">
                                 <i class="fas fa-columns mr-2"></i>Dashboard Saya
                             </a>
                         @else
                             <div class="flex items-center gap-3">
                                 <a href="{{ route('login') }}" class="px-4 py-2.5 text-xs sm:text-sm font-bold transition-all rounded-2xl hover:-translate-y-0.5" :class="scrolled ? 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' : 'text-white hover:bg-white/10'">
                                     Masuk
                                 </a>
                                 @if (Route::has('register'))
                                     <a href="{{ route('register') }}" class="bg-white text-teal-800 hover:bg-teal-50 hover:text-teal-900 px-5 py-2.5 rounded-2xl font-extrabold shadow-sm hover:shadow-md transition-all text-xs sm:text-sm border border-slate-100 transform hover:-translate-y-0.5 active:translate-y-0">
                                         Daftar Sekarang
                                     </a>
                                 @endif
                             </div>
                         @endauth
                     @endif
                 </div>

                 <!-- Mobile Menu Toggle Button -->
                 <div class="md:hidden flex items-center">
                     <button @click.stop="mobileMenuOpen = !mobileMenuOpen" type="button" class="p-2 rounded-xl transition duration-200 focus:outline-none flex items-center justify-center border border-transparent hover:bg-slate-100/10 active:scale-95" :class="scrolled ? 'text-slate-800 hover:bg-slate-100 hover:text-teal-600' : 'text-white hover:bg-white/10'" aria-label="Toggle Menu">
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
              class="md:hidden bg-white/95 backdrop-blur-2xl border-t border-slate-100 shadow-2xl absolute w-full left-0 top-full rounded-b-[2.5rem] overflow-hidden">
             <div class="px-5 py-6 space-y-3.5">
                 <a href="#lowongan" @click="mobileMenuOpen = false" class="flex items-center px-4 py-3.5 text-sm font-extrabold text-slate-800 bg-slate-50 hover:bg-teal-50 hover:text-teal-600 rounded-2xl transition duration-300">
                     <div class="w-8 h-8 rounded-xl bg-white flex items-center justify-center shadow-sm text-teal-600 mr-3.5 border border-slate-100 shrink-0">
                         <i class="fas fa-search text-xs"></i>
                     </div>
                     Cari Lowongan Magang
                 </a>
                 <a href="#langkah" @click="mobileMenuOpen = false" class="flex items-center px-4 py-3.5 text-sm font-extrabold text-slate-800 bg-slate-50 hover:bg-teal-50 hover:text-teal-600 rounded-2xl transition duration-300">
                     <div class="w-8 h-8 rounded-xl bg-white flex items-center justify-center shadow-sm text-teal-600 mr-3.5 border border-slate-100 shrink-0">
                         <i class="fas fa-tasks text-xs"></i>
                     </div>
                     Alur Pendaftaran
                 </a>
                 <a href="#faq" @click="mobileMenuOpen = false" class="flex items-center px-4 py-3.5 text-sm font-extrabold text-slate-800 bg-slate-50 hover:bg-teal-50 hover:text-teal-600 rounded-2xl transition duration-300">
                     <div class="w-8 h-8 rounded-xl bg-white flex items-center justify-center shadow-sm text-teal-600 mr-3.5 border border-slate-100 shrink-0">
                         <i class="fas fa-question-circle text-xs"></i>
                     </div>
                     FAQ & Bantuan
                 </a>

                 <div class="border-t border-slate-100 pt-5 mt-4">
                     @if (Route::has('login'))
                         @auth
                             <a href="{{ url('/dashboard') }}" class="flex items-center justify-center w-full px-4 py-3.5 bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-2xl font-extrabold shadow-lg shadow-teal-600/20 active:scale-[0.98] transition text-sm">
                                 <i class="fas fa-columns mr-2.5"></i> Ke Dashboard Saya
                             </a>
                         @else
                             <div class="grid grid-cols-2 gap-3.5">
                                 <a href="{{ route('login') }}" class="flex items-center justify-center px-4 py-3.5 text-sm font-extrabold text-slate-800 bg-slate-100 rounded-2xl hover:bg-slate-200 active:scale-[0.98] transition">
                                     Masuk
                                 </a>
                                 @if (Route::has('register'))
                                     <a href="{{ route('register') }}" class="flex items-center justify-center px-4 py-3.5 text-sm font-extrabold text-white bg-gradient-to-r from-teal-600 to-emerald-600 rounded-2xl hover:from-teal-700 hover:to-emerald-700 shadow-md shadow-teal-600/15 active:scale-[0.98] transition">
                                         Daftar
                                     </a>
                                 @endif
                             </div>
                         @endauth
                     @endif
                 </div>
             </div>
         </div>
     </nav>

     <!-- Hero Section -->
     <section class="bg-sasirangan-premium text-white pt-28 pb-20 sm:pt-36 sm:pb-28 md:pt-40 md:pb-36 relative overflow-hidden w-full">
         <!-- Ambient Blurred Background Ornaments -->
         <div class="absolute -top-12 -right-12 w-[350px] sm:w-[500px] h-[350px] sm:h-[500px] bg-teal-500 opacity-20 rounded-full blur-[90px] sm:blur-[120px] animate-ambient-1 pointer-events-none"></div>
         <div class="absolute -bottom-16 -left-16 w-[300px] sm:w-[450px] h-[300px] sm:h-[450px] bg-emerald-500 opacity-15 rounded-full blur-[90px] sm:blur-[120px] animate-ambient-2 pointer-events-none"></div>

         <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 w-full flex flex-col items-center">
             <!-- Top Tag Badge -->
             <span class="inline-flex items-center gap-2 py-2 px-4 rounded-full bg-teal-900/60 border border-teal-500/35 backdrop-blur-md text-teal-200 text-xs font-extrabold tracking-wider uppercase mb-6 sm:mb-8 shadow-sm">
                 <span class="w-2.5 h-2.5 rounded-full bg-teal-400 animate-pulse"></span>
                 Portal Resmi Magang Kota Banjarmasin
             </span>
            <h1 class="text-3xl sm:text-5xl md:text-6xl font-black mb-4 sm:mb-6 leading-[1.2] sm:leading-[1.15] drop-shadow-sm tracking-tight">
                Bangun Karir & Pengalaman Mulia <br class="hidden sm:inline"/> Bersama <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-200 via-emerald-100 to-white">Pemerintah Kota Banjarmasin</span>
             </h1>

             <!-- Subtitle Description -->
             <p class="text-sm sm:text-lg md:text-xl text-teal-100/90 mb-10 max-w-2xl font-medium leading-relaxed px-4 sm:px-0">
                 Temukan lowongan magang terbaik di berbagai Instansi Pemerintah Kota Banjarmasin secara transparan, mudah, dan terintegrasi.
             </p>
             
             <!-- Dynamic Global Search Dock -->
             <div class="w-full max-w-2xl px-2 sm:px-0">
                 <form action="{{ route('home') }}#lowongan" method="GET" class="relative w-full" id="search-form" onsubmit="event.preventDefault(); let params = new URLSearchParams(new FormData(this)); for (let [k, v] of Array.from(params.entries())) { if (!v) params.delete(k); } window.location.href = '{{ route('home') }}?' + params.toString() + '#lowongan';">
                     <div class="flex flex-col sm:flex-row items-stretch sm:items-center p-2.5 bg-white/95 backdrop-blur-xl rounded-[2rem] shadow-2xl border border-white/20 gap-2 sm:gap-0 transform transition-transform duration-300 focus-within:scale-[1.01] w-full">
                         <div class="flex items-center flex-grow pl-4 sm:pl-5 text-slate-400">
                             <i class="fas fa-search text-lg text-teal-600 shrink-0"></i>
                             <input type="text" name="search" value="{{ request('search') }}" 
                                 class="w-full py-3.5 px-4 text-slate-800 bg-transparent text-sm sm:text-base font-semibold placeholder-slate-400 focus:outline-none border-none ring-0 focus:ring-0" 
                                 placeholder="Cari posisi magang atau nama instansi...">
                         </div>
                         <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white font-extrabold py-3.5 px-8 rounded-full transition-all duration-300 shadow-md shadow-teal-700/20 hover:shadow-lg active:scale-98 text-sm flex items-center justify-center gap-2 shrink-0">
                             <span>Cari Posisi</span>
                         </button>
                     </div>
                 </form>
             </div>
         </div>
         
         <!-- Elegant Bottom Wave Divider -->
         <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none pointer-events-none">
             <svg class="relative block w-full h-[30px] sm:h-[60px] md:h-[80px]" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                 <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" class="fill-slate-50/50"></path>
             </svg>
         </div>
     </section>

     <!-- Floating Bento Statistics Cards -->
     <section class="relative z-20 -mt-10 sm:-mt-16 px-4 w-full">
         <div class="max-w-5xl mx-auto w-full">
             <div class="grid grid-cols-3 gap-3.5 sm:gap-6 w-full">
                 <!-- Instansi Card -->
                 <div class="bg-white/95 backdrop-blur-md p-4 sm:p-7 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100/80 flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-3.5 sm:gap-5 transform hover:-translate-y-1.5 transition-all duration-300 group">
                     <div class="w-11 h-11 sm:w-14 sm:h-14 bg-teal-50 rounded-2xl flex items-center justify-center text-teal-600 group-hover:bg-teal-600 group-hover:text-white transition-all duration-500 shadow-inner shrink-0">
                         <i class="fas fa-building text-base sm:text-xl"></i>
                     </div>
                     <div class="min-w-0">
                         <div class="text-xl sm:text-3xl font-black text-slate-800 tracking-tight truncate leading-none sm:leading-tight">{{ $totalInstansi }}</div>
                         <div class="text-slate-400 text-[10px] sm:text-xs font-bold uppercase tracking-wider mt-1.5 truncate">Instansi</div>
                     </div>
                 </div>
                 <!-- Lowongan Card -->
                 <div class="bg-white/95 backdrop-blur-md p-4 sm:p-7 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100/80 flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-3.5 sm:gap-5 transform hover:-translate-y-1.5 transition-all duration-300 group">
                     <div class="w-11 h-11 sm:w-14 sm:h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 shadow-inner shrink-0">
                         <i class="fas fa-briefcase text-base sm:text-xl"></i>
                     </div>
                     <div class="min-w-0">
                         <div class="text-xl sm:text-3xl font-black text-slate-800 tracking-tight truncate leading-none sm:leading-tight">{{ $totalLowongan }}</div>
                         <div class="text-slate-400 text-[10px] sm:text-xs font-bold uppercase tracking-wider mt-1.5 truncate">Posisi Aktif</div>
                     </div>
                 </div>
                 <!-- Alumni Card -->
                 <div class="bg-white/95 backdrop-blur-md p-4 sm:p-7 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100/80 flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-3.5 sm:gap-5 transform hover:-translate-y-1.5 transition-all duration-300 group">
                     <div class="w-11 h-11 sm:w-14 sm:h-14 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-500 group-hover:bg-amber-500 group-hover:text-white transition-all duration-500 shadow-inner shrink-0">
                         <i class="fas fa-user-graduate text-base sm:text-xl"></i>
                     </div>
                     <div class="min-w-0">
                         <div class="text-xl sm:text-3xl font-black text-slate-800 tracking-tight truncate leading-none sm:leading-tight">{{ $totalAlumni }}</div>
                         <div class="text-slate-400 text-[10px] sm:text-xs font-bold uppercase tracking-wider mt-1.5 truncate">Alumni Magang</div>
                     </div>
                 </div>
             </div>
         </div>
     </section>

     <!-- Alur & Langkah Pendaftaran -->
     <section id="langkah" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28 scroll-mt-20 w-full">
         <div class="text-center mb-12 sm:mb-20">
             <span class="text-xs font-bold text-teal-600 tracking-widest uppercase bg-teal-50 px-4 py-2 rounded-full border border-teal-100">Bagaimana Cara Bergabung?</span>
             <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-800 tracking-tight mt-4">Alur Pendaftaran Magang</h2>
             <p class="text-slate-500 mt-3 max-w-xl mx-auto text-sm sm:text-base px-2">Ikuti 4 langkah mudah berikut untuk memulai perjalanan karir magang Anda bersama Pemkot Banjarmasin.</p>
         </div>

         <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative w-full">
             <!-- Line Connector Desktop -->
             <div class="hidden md:block absolute top-[40px] left-[12%] right-[12%] h-[2px] bg-gradient-to-r from-teal-200 via-emerald-100 to-slate-200 -z-10"></div>
             <!-- Line Connector Mobile -->
             <div class="block md:hidden absolute left-[35px] top-[40px] bottom-[40px] w-[2px] bg-gradient-to-b from-teal-200 via-emerald-100 to-slate-200 -z-10"></div>

             <!-- Langkah 1 -->
             <div class="bg-white p-6 sm:p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 flex flex-row md:flex-col items-start md:items-center text-left md:text-center gap-4 sm:gap-5 w-full group">
                 <div class="w-12 h-12 sm:w-16 sm:h-16 bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center font-black text-lg sm:text-xl shadow-inner border border-teal-100/50 relative shrink-0 group-hover:scale-105 transition-transform duration-300">
                     <i class="fas fa-user-plus text-sm sm:text-base"></i>
                     <span class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-teal-600 text-white rounded-full flex items-center justify-center text-[10px] border-2 border-white"><i class="fas fa-check"></i></span>
                 </div>
                 <div class="flex-grow">
                     <h4 class="font-bold text-slate-800 text-base sm:text-lg mb-2">1. Buat Akun</h4>
                     <p class="text-slate-500 text-xs sm:text-sm leading-relaxed">Registrasikan data diri Anda pada portal dengan email aktif & isi profil akademis lengkap.</p>
                 </div>
             </div>

             <!-- Langkah 2 -->
             <div class="bg-white p-6 sm:p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 flex flex-row md:flex-col items-start md:items-center text-left md:text-center gap-4 sm:gap-5 w-full group">
                 <div class="w-12 h-12 sm:w-16 sm:h-16 bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center font-black text-lg sm:text-xl shadow-inner border border-teal-100/50 relative shrink-0 group-hover:scale-105 transition-transform duration-300">
                     <i class="fas fa-search-location text-sm sm:text-base"></i>
                     <span class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-slate-200 text-slate-600 rounded-full flex items-center justify-center text-[10px] font-bold border-2 border-white">2</span>
                 </div>
                 <div class="flex-grow">
                     <h4 class="font-bold text-slate-800 text-base sm:text-lg mb-2">2. Pilih Lowongan</h4>
                     <p class="text-slate-500 text-xs sm:text-sm leading-relaxed">Cari dinas instansi yang relevan dengan kualifikasi jurusan dan minat karir Anda.</p>
                 </div>
             </div>

             <!-- Langkah 3 -->
             <div class="bg-white p-6 sm:p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 flex flex-row md:flex-col items-start md:items-center text-left md:text-center gap-4 sm:gap-5 w-full group">
                 <div class="w-12 h-12 sm:w-16 sm:h-16 bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center font-black text-lg sm:text-xl shadow-inner border border-teal-100/50 relative shrink-0 group-hover:scale-105 transition-transform duration-300">
                     <i class="fas fa-calendar-alt text-sm sm:text-base"></i>
                     <span class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-slate-200 text-slate-600 rounded-full flex items-center justify-center text-[10px] font-bold border-2 border-white">3</span>
                 </div>
                 <div class="flex-grow">
                     <h4 class="font-bold text-slate-800 text-base sm:text-lg mb-2">3. Slot Periode</h4>
                     <p class="text-slate-500 text-xs sm:text-sm leading-relaxed">Tentukan tanggal masuk & selesai. Sistem akan memvalidasi sisa kuota yang tersedia.</p>
                 </div>
             </div>

             <!-- Langkah 4 -->
             <div class="bg-white p-6 sm:p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 flex flex-row md:flex-col items-start md:items-center text-left md:text-center gap-4 sm:gap-5 w-full group">
                 <div class="w-12 h-12 sm:w-16 sm:h-16 bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center font-black text-lg sm:text-xl shadow-inner border border-teal-100/50 relative shrink-0 group-hover:scale-105 transition-transform duration-300">
                     <i class="fas fa-paper-plane text-sm sm:text-base"></i>
                     <span class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-slate-200 text-slate-600 rounded-full flex items-center justify-center text-[10px] font-bold border-2 border-white">4</span>
                 </div>
                 <div class="flex-grow">
                     <h4 class="font-bold text-slate-800 text-base sm:text-lg mb-2">4. Mulai Magang</h4>
                     <p class="text-slate-500 text-xs sm:text-sm leading-relaxed">Setelah divalidasi oleh instansi tujuan, Anda siap diterjunkan & mendapatkan pembimbing.</p>
                 </div>
             </div>
         </div>
     </section>

     <!-- Lowongan Pekerjaan Section -->
     <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-grow w-full">
         
         <!-- Global Announcement Banner -->
         @php
             $globalAnnouncement = \App\Models\Setting::where('key', 'announcement')->value('value');
         @endphp
         @if(!empty($globalAnnouncement))
             <div class="bg-gradient-to-r from-amber-500/10 via-orange-500/10 to-transparent border border-amber-500/20 rounded-[2rem] p-5 sm:p-8 shadow-sm flex flex-col md:flex-row gap-5 items-start md:items-center justify-between mb-10 overflow-hidden relative w-full">
                 <div class="absolute -right-6 -top-6 opacity-5 text-amber-600 pointer-events-none">
                     <i class="fas fa-bullhorn text-9xl"></i>
                 </div>
                 <div class="flex gap-4 items-start">
                     <div class="w-12 h-12 rounded-2xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-lg shadow-amber-500/10">
                         <i class="fas fa-bullhorn text-lg"></i>
                     </div>
                     <div>
                         <span class="text-[9px] font-extrabold text-amber-700 bg-amber-500/15 px-2.5 py-1 rounded-lg border border-amber-500/20 uppercase tracking-widest">Pengumuman Penting</span>
                         <div class="text-slate-800 font-bold text-sm sm:text-base mt-2 leading-relaxed">
                             {!! nl2br(e($globalAnnouncement)) !!}
                         </div>
                     </div>
                 </div>
             </div>
         @endif

         <!-- Banner Penempatan Otomatis -->
         <div class="bg-gradient-to-r from-teal-900 via-teal-950 to-emerald-950 rounded-[2.5rem] p-6 sm:p-10 text-white shadow-xl shadow-teal-950/35 mb-12 overflow-hidden relative border border-teal-800/40 w-full">
             <div class="absolute -right-8 -top-8 opacity-10 text-white pointer-events-none">
                 <i class="fas fa-route text-[10rem]"></i>
             </div>
             <div class="flex flex-col lg:flex-row gap-6 lg:gap-8 items-stretch lg:items-center justify-between relative z-10 w-full">
                 <div class="flex gap-4 sm:gap-5 items-start">
                     <div class="w-12 h-12 rounded-2xl bg-teal-500/20 text-teal-300 flex items-center justify-center shrink-0 shadow-inner border border-teal-500/30">
                         <i class="fas fa-wand-magic-sparkles text-lg"></i>
                     </div>
                     <div>
                         <span class="text-[9px] font-extrabold text-emerald-300 uppercase tracking-widest bg-emerald-500/20 px-2.5 py-1 rounded-lg border border-emerald-500/30">Alokasi Cerdas</span>
                         <h3 class="text-white font-extrabold text-lg sm:text-2xl mt-2 leading-snug">Bingung Memilih Dinas / Instansi?</h3>
                         <p class="text-teal-100/80 text-xs sm:text-sm mt-1.5 max-w-2xl font-medium leading-relaxed">
                             Gunakan fitur **Penempatan Otomatis**! Sistem cerdas kami akan mencocokkan latar belakang jurusan Anda dengan instansi yang saat ini kuotanya masih tersedia secara berimbang.
                         </p>
                     </div>
                 </div>
                 <a href="{{ route('peserta.apply_automatic.form') }}" class="shrink-0 bg-white text-teal-950 hover:bg-teal-50 px-6 py-4 rounded-2xl font-extrabold shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5 active:scale-98 text-center text-sm flex items-center justify-center gap-2">
                     <i class="fas fa-play text-xs"></i> Daftar Penempatan Otomatis
                 </a>
             </div>
         </div>

         <!-- Vacancies Section Header -->
         <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4 w-full">
             <div>
                 <span class="text-xs font-bold text-teal-600 tracking-widest uppercase bg-teal-50 px-4 py-2 rounded-full border border-teal-100">Eksplorasi Peran</span>
                 <h2 id="lowongan" class="text-2xl sm:text-4xl font-extrabold text-slate-800 tracking-tight mt-4 scroll-mt-[95px]">Lowongan Magang Terbaru</h2>
                 <p class="text-slate-500 mt-2 text-sm sm:text-base">Dapatkan kesempatan berharga untuk mengabdi dan belajar langsung di instansi pemerintahan.</p>
             </div>
             
             @if(request()->anyFilled(['posisi', 'instansi_id', 'jurusan', 'search']))
                 <a href="{{ route('home') }}#lowongan" class="group flex items-center bg-rose-50 text-rose-600 px-5 py-3 rounded-2xl text-xs sm:text-sm font-bold hover:bg-rose-100 hover:text-rose-700 transition duration-300 self-start md:self-auto shadow-sm">
                     <i class="fas fa-undo-alt mr-2 group-hover:-rotate-180 transition-transform duration-500"></i> Bersihkan Filter
                 </a>
             @endif
         </div>

         <!-- Filter Dock Card -->
         <div class="bg-white p-5 sm:p-8 rounded-[2.5rem] shadow-lg shadow-slate-200/40 border border-slate-100 mb-8 w-full">
             <form action="{{ route('home') }}#lowongan" method="GET" id="filter-form" onsubmit="event.preventDefault(); let params = new URLSearchParams(new FormData(this)); for (let [k, v] of Array.from(params.entries())) { if (!v) params.delete(k); } window.location.href = '{{ route('home') }}?' + params.toString() + '#lowongan';" class="w-full">
                 @if(request('search'))
                     <input type="hidden" name="search" value="{{ request('search') }}">
                 @endif

                 <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 lg:gap-6 items-end w-full">
                     <!-- Select Instansi -->
                     <div class="lg:col-span-5 w-full">
                         <label class="block text-xs font-extrabold text-slate-500 uppercase mb-2 ml-1.5 tracking-wider flex items-center gap-2">
                             <i class="fas fa-building text-teal-600"></i> Pilih Instansi / Dinas
                         </label>
                         <div class="relative w-full group">
                             <select name="instansi_id" class="w-full pl-5 pr-12 py-4 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-base lg:text-sm font-semibold bg-slate-50/50 focus:bg-white transition duration-300 appearance-none cursor-pointer text-slate-800 shadow-sm">
                                 <option value="">🏢 Semua Instansi Pemerintah</option>
                                 @foreach($instansis as $instansi)
                                     <option value="{{ $instansi->id }}" {{ request('instansi_id') == $instansi->id ? 'selected' : '' }}>
                                         {{ Str::limit($instansi->nama_dinas, 45) }}
                                     </option>
                                 @endforeach
                             </select>
                             <span class="absolute inset-y-0 right-0 flex items-center pr-5 pointer-events-none text-slate-400 transition-colors group-hover:text-teal-600">
                                 <i class="fas fa-chevron-down text-xs"></i>
                             </span>
                         </div>
                     </div>

                     <!-- Input Jurusan -->
                     <div class="lg:col-span-5 w-full">
                         <label class="block text-xs font-extrabold text-slate-500 uppercase mb-2 ml-1.5 tracking-wider flex items-center gap-2">
                             <i class="fas fa-graduation-cap text-teal-600"></i> Cari Jurusan / Keahlian
                         </label>
                         <div class="relative w-full">
                             <input type="text" name="jurusan" id="jurusan-input" value="{{ request('jurusan') }}" placeholder="Contoh: Informatika, Akuntansi, SMK..." 
                                 class="w-full px-5 py-4 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-base lg:text-sm font-semibold bg-slate-50/50 focus:bg-white transition duration-300 text-slate-800 placeholder-slate-400 shadow-sm">
                         </div>
                     </div>

                     <!-- Filter Button -->
                     <div class="lg:col-span-2 w-full">
                         <button type="submit" class="w-full bg-slate-900 hover:bg-teal-600 text-white font-extrabold py-4 px-6 rounded-2xl shadow-md hover:shadow-lg active:scale-98 transition-all duration-300 flex items-center justify-center gap-2 text-sm">
                             <i class="fas fa-filter text-xs"></i> Terapkan
                         </button>
                     </div>
                 </div>
             </form>
         </div>

         <!-- Quick Filter Scrollable Pills -->
         <div class="flex items-center gap-3.5 overflow-x-auto pb-4 mb-8 no-scrollbar w-full max-w-full">
             <span class="text-xs font-extrabold text-slate-450 uppercase tracking-wider shrink-0 mr-1 flex items-center">
                 <i class="fas fa-bolt text-amber-500 mr-2"></i> Filter Cepat:
             </span>
             <a href="{{ route('home') }}#lowongan" class="shrink-0 px-4 py-2 rounded-full text-xs font-extrabold transition-all duration-300 {{ !request('jurusan') && !request('instansi_id') && !request('search') ? 'bg-gradient-to-r from-teal-600 to-emerald-600 text-white shadow-md shadow-teal-600/25' : 'bg-white text-slate-600 border border-slate-200 hover:border-teal-300 hover:text-teal-600 shadow-sm' }}">
                 ✨ Semua Posisi
             </a>
             <a href="{{ route('home') }}?jurusan=Informatika#lowongan" class="shrink-0 px-4 py-2 rounded-full text-xs font-extrabold transition-all duration-300 {{ stripos(request('jurusan'), 'Informatika') !== false ? 'bg-gradient-to-r from-teal-600 to-emerald-600 text-white shadow-md shadow-teal-600/25' : 'bg-white text-slate-600 border border-slate-200 hover:border-teal-300 hover:text-teal-600 shadow-sm' }}">
                 💻 Informatika & IT
             </a>
             <a href="{{ route('home') }}?jurusan=Akuntansi#lowongan" class="shrink-0 px-4 py-2 rounded-full text-xs font-extrabold transition-all duration-300 {{ stripos(request('jurusan'), 'Akuntansi') !== false ? 'bg-gradient-to-r from-teal-600 to-emerald-600 text-white shadow-md shadow-teal-600/25' : 'bg-white text-slate-600 border border-slate-200 hover:border-teal-300 hover:text-teal-600 shadow-sm' }}">
                 📊 Akuntansi & Keuangan
             </a>
             <a href="{{ route('home') }}?jurusan=Administrasi#lowongan" class="shrink-0 px-4 py-2 rounded-full text-xs font-extrabold transition-all duration-300 {{ stripos(request('jurusan'), 'Administrasi') !== false ? 'bg-gradient-to-r from-teal-600 to-emerald-600 text-white shadow-md shadow-teal-600/25' : 'bg-white text-slate-600 border border-slate-200 hover:border-teal-300 hover:text-teal-600 shadow-sm' }}">
                 🏛️ Administrasi
             </a>
             <a href="{{ route('home') }}?jurusan=SMK#lowongan" class="shrink-0 px-4 py-2 rounded-full text-xs font-extrabold transition-all duration-300 {{ stripos(request('jurusan'), 'SMK') !== false ? 'bg-gradient-to-r from-teal-600 to-emerald-600 text-white shadow-md shadow-teal-600/25' : 'bg-white text-slate-600 border border-slate-200 hover:border-teal-300 hover:text-teal-600 shadow-sm' }}">
                 🏫 Khusus SMK
             </a>
             <a href="{{ route('home') }}?jurusan=SMA#lowongan" class="shrink-0 px-4 py-2 rounded-full text-xs font-extrabold transition-all duration-300 {{ stripos(request('jurusan'), 'SMA') !== false ? 'bg-gradient-to-r from-teal-600 to-emerald-600 text-white shadow-md shadow-teal-600/25' : 'bg-white text-slate-600 border border-slate-200 hover:border-teal-300 hover:text-teal-600 shadow-sm' }}">
                 🎒 Khusus SMA
             </a>
             <a href="{{ route('home') }}?jurusan=S1#lowongan" class="shrink-0 px-4 py-2 rounded-full text-xs font-extrabold transition-all duration-300 {{ stripos(request('jurusan'), 'S1') !== false ? 'bg-gradient-to-r from-teal-600 to-emerald-600 text-white shadow-md shadow-teal-600/25' : 'bg-white text-slate-600 border border-slate-200 hover:border-teal-300 hover:text-teal-600 shadow-sm' }}">
                 🎓 Mahasiswa (S1/D3)
             </a>
         </div>

         <!-- Vacancies Card Grid -->
         <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 w-full">
             @forelse($lowongans as $loker)
                 @if($loker->kuota > 0)
                     <div x-data="{ showModal: false }" class="h-full flex flex-col w-full">
                         <!-- Job Card Wrapper -->
                         <div @click="showModal = true" class="cursor-pointer group bg-white rounded-[2rem] border border-slate-200/80 overflow-hidden hover:shadow-xl hover:shadow-slate-100 hover:border-teal-300 hover:-translate-y-1 active:scale-[0.99] transition-all duration-300 flex flex-col h-full relative shadow-sm w-full">
                         
                             <!-- Card Header Bar -->
                             <div class="px-6 pt-6 pb-4 flex items-center justify-between gap-3 border-b border-slate-50 shrink-0">
                                 <div class="flex flex-wrap items-center gap-2">
                                     <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200/60 text-[10px] px-2.5 py-1 rounded-lg font-extrabold uppercase tracking-wider">
                                         <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                         {{ $loker->status }}
                                     </span>
                                     @if($loker->kuota < 3)
                                         <span class="inline-flex items-center gap-1 bg-rose-50 text-rose-600 border border-rose-200/60 text-[10px] px-2.5 py-1 rounded-lg font-extrabold uppercase tracking-wider animate-pulse">
                                             🔥 Sisa {{ $loker->kuota }} Kursi
                                         </span>
                                     @else
                                         <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-600 text-[10px] px-2.5 py-1 rounded-lg font-bold">
                                             💺 Kuota: {{ $loker->kuota }}
                                         </span>
                                     @endif
                                 </div>
                                 <div class="text-slate-300 group-hover:text-teal-600 transition-colors shrink-0">
                                     <i class="fas fa-chevron-right text-xs"></i>
                                 </div>
                             </div>

                             <!-- Job Title & Icon Info -->
                             <div class="p-6 pt-5 pb-4 flex items-start gap-4 shrink-0">
                                 <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-teal-500 to-emerald-600 text-white flex items-center justify-center shadow-md shadow-teal-500/20 shrink-0 font-bold text-lg">
                                     <i class="fas fa-briefcase"></i>
                                 </div>
                                 <div class="min-w-0 flex-grow">
                                     <h3 class="text-base sm:text-lg font-black text-slate-800 group-hover:text-teal-600 transition-colors duration-300 line-clamp-1 leading-snug" title="{{ $loker->judul_posisi }}">
                                         {{ $loker->judul_posisi }}
                                     </h3>
                                     <p class="text-xs text-slate-500 flex items-center font-bold mt-1.5">
                                         <i class="fas fa-building text-teal-600 mr-2 shrink-0"></i>
                                         <span class="truncate">{{ $loker->instansi->nama_dinas }}</span>
                                     </p>
                                 </div>
                             </div>

                             <!-- Major Requirement tags -->
                             <div class="px-6 py-2 flex-grow flex flex-col justify-start">
                                 <div class="flex flex-wrap items-center gap-2 mb-4">
                                     <span class="inline-flex items-center px-3 py-1 rounded-xl text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200/80 max-w-full">
                                         <i class="fas fa-graduation-cap mr-2 text-teal-600 shrink-0"></i>
                                         <span class="truncate" title="{{ $loker->required_major }}">{{ $loker->required_major }}</span>
                                     </span>
                                     @if(preg_match('/SMA|SMK/i', $loker->required_major))
                                         <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-[10px] font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-200 shrink-0">
                                             🎒 SMA/SMK
                                         </span>
                                     @endif
                                 </div>

                                 <!-- Excerpt description -->
                                 <div class="prose prose-sm text-slate-500 text-xs sm:text-sm leading-relaxed line-clamp-2 mb-6">
                                     {!! strip_tags($loker->deskripsi) !!}
                                 </div>
                             </div>

                             <!-- Action buttons footer -->
                             <div class="p-5 pt-3 bg-slate-50 border-t border-slate-100 mt-auto shrink-0 flex items-center justify-between gap-3">
                                 @auth
                                     @if(auth()->user()->role == 'peserta')
                                         @php
                                             $userMajor = strtolower(trim(auth()->user()->major ?? ''));
                                             $reqMajor  = strtolower(trim($loker->required_major ?? ''));
                                             $isMatch = str_contains($reqMajor, 'semua jurusan') || 
                                                        str_contains($reqMajor, $userMajor) ||
                                                        $reqMajor == '' || 
                                                        $reqMajor == '-';
                                         @endphp

                                         @if($isMatch)
                                             <a @click.stop href="{{ route('peserta.daftar.form', $loker->id) }}" class="w-full bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white py-3.5 px-4 rounded-2xl font-extrabold shadow-md shadow-teal-600/10 active:scale-98 transition-all duration-300 text-center text-xs sm:text-sm flex items-center justify-center gap-2">
                                                 <span>Lamar Posisi Ini</span>
                                                 <i class="fas fa-arrow-right text-[10px]"></i>
                                             </a>
                                         @else
                                             <button disabled class="w-full bg-slate-200 text-slate-400 py-3.5 px-4 rounded-2xl font-bold cursor-not-allowed text-xs sm:text-sm flex items-center justify-center gap-2">
                                                 <i class="fas fa-lock text-xs"></i> Syarat Jurusan Tidak Sesuai
                                             </button>
                                         @endif
                                     @elseif(auth()->user()->role == 'admin_kota' || auth()->user()->role == 'admin_instansi')
                                         <button disabled class="w-full text-center text-xs font-extrabold text-slate-400 uppercase tracking-widest py-3 bg-slate-100 border border-slate-200 rounded-xl">Pratinjau Admin</button>
                                     @endif
                                 @else
                                     <a @click.stop href="{{ route('peserta.daftar.form', $loker->id) }}" class="w-full bg-slate-900 hover:bg-teal-600 text-white py-3.5 px-4 rounded-2xl font-extrabold shadow-md active:scale-98 transition-all duration-300 text-center text-xs sm:text-sm flex items-center justify-center gap-2">
                                         <span>Masuk & Lamar</span>
                                         <i class="fas fa-arrow-right text-[10px]"></i>
                                     </a>
                                 @endauth
                             </div>
                         </div>

                         <!-- Detail Loker Side Drawer / Bottom Sheet -->
                         <div x-show="showModal" style="display: none;" class="fixed inset-0 z-[100] flex justify-end items-end sm:items-stretch" role="dialog" aria-modal="true">
                             <!-- Backdrop overlay -->
                             <div x-show="showModal" 
                                  x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                                  x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
                                  class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" 
                                  @click="showModal = false"></div>

                             <!-- Drawer Panel -->
                             <div x-show="showModal" 
                                  x-transition:enter="transition ease-out duration-300 transform" 
                                  x-transition:enter-start="translate-y-full sm:translate-y-0 sm:translate-x-full" 
                                  x-transition:enter-end="translate-y-0 sm:translate-x-0" 
                                  x-transition:leave="transition ease-in duration-200 transform" 
                                  x-transition:leave-start="translate-y-0 sm:translate-x-0" 
                                  x-transition:leave-end="translate-y-full sm:translate-y-0 sm:translate-x-full"
                                  class="relative bg-white dark:bg-slate-900 rounded-t-[2.5rem] sm:rounded-t-none sm:rounded-l-[2.5rem] shadow-2xl w-full sm:max-w-md md:max-w-lg lg:max-w-xl xl:max-w-2xl h-[88vh] sm:h-full overflow-hidden flex flex-col z-10 transition-all text-left border-l border-slate-100 dark:border-slate-800/80">
                                 
                                 <!-- Mobile Drag handle bar -->
                                 <div class="sm:hidden w-12 h-1.5 bg-slate-200 dark:bg-slate-800 rounded-full mx-auto my-3.5 shrink-0"></div>

                                 <!-- Header Drawer -->
                                 <div class="px-6 sm:px-8 py-5 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20 shrink-0 flex justify-between items-center">
                                     <h3 class="text-base sm:text-lg font-extrabold text-slate-800 dark:text-white flex items-center gap-2.5">
                                         <div class="w-8 h-8 rounded-xl bg-teal-50 dark:bg-slate-800 text-teal-600 dark:text-teal-400 flex items-center justify-center shadow-inner shrink-0">
                                             <i class="fas fa-info-circle text-sm"></i>
                                         </div>
                                         Detail Lowongan Magang
                                     </h3>
                                     <button @click="showModal = false" class="text-slate-400 hover:text-slate-655 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl w-8 h-8 flex items-center justify-center transition-all border border-slate-200/50 dark:border-slate-700/50 shrink-0">
                                         <i class="fas fa-times text-xs"></i>
                                     </button>
                                 </div>
                                 
                                 <!-- Body Drawer Content -->
                                 <div class="px-6 sm:px-8 pt-6 pb-8 overflow-y-auto flex-grow space-y-6">
                                     
                                     <!-- Branding & Instansi Block -->
                                     @php
                                         $cleanDinas = trim(str_ireplace(['dinas', 'badan', 'kantor', 'bagian', 'sekretariat'], '', $loker->instansi->nama_dinas));
                                         $initials = strtoupper(substr($cleanDinas, 0, 2));
                                     @endphp
                                     <div class="flex flex-col sm:flex-row items-start gap-4 pb-2">
                                         <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-teal-500 to-emerald-600 text-white flex items-center justify-center font-black text-xl shadow-lg shadow-teal-500/20 shrink-0">
                                             {{ $initials }}
                                         </div>
                                         <div class="space-y-1">
                                             <h4 class="text-xl sm:text-2xl font-black text-slate-800 dark:text-white leading-tight">
                                                 {{ $loker->judul_posisi }}
                                             </h4>
                                             <p class="text-sm font-bold text-teal-650 dark:text-teal-400">
                                                 {{ $loker->instansi->nama_dinas }}
                                             </p>
                                             @if(!empty($loker->instansi->alamat))
                                                 <p class="text-xs text-slate-500 dark:text-slate-450 flex items-start gap-2 pt-1 leading-relaxed">
                                                     <i class="fas fa-map-marker-alt text-rose-500 shrink-0 mt-0.5 animate-bounce"></i>
                                                     <span>{{ $loker->instansi->alamat }}</span>
                                                 </p>
                                             @endif
                                         </div>
                                     </div>

                                     <!-- Quick Info Grid Cards -->
                                     <div class="grid grid-cols-2 gap-3">
                                         <!-- Status Card -->
                                         <div class="bg-slate-50 dark:bg-slate-950/40 border border-slate-100 dark:border-slate-800 rounded-2xl p-3.5 flex flex-col justify-between">
                                             <span class="text-[9px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Status Lowongan</span>
                                             <span class="inline-flex items-center gap-1.5 text-xs font-extrabold text-emerald-600 dark:text-emerald-450 mt-1 bg-emerald-50 dark:bg-emerald-950/20 px-2 py-0.5 rounded-lg border border-emerald-150/40 dark:border-emerald-900/10 w-fit">
                                                 <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400 animate-pulse"></span>
                                                 {{ $loker->status }}
                                             </span>
                                         </div>

                                         <!-- Kuota Card -->
                                         <div class="bg-slate-50 dark:bg-slate-950/40 border border-slate-100 dark:border-slate-800 rounded-2xl p-3.5 flex flex-col justify-between">
                                             <span class="text-[9px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Kapasitas Kursi</span>
                                             <span class="text-xs font-extrabold text-slate-800 dark:text-white mt-1 flex items-center gap-1.5">
                                                 <i class="fas fa-users text-teal-600 dark:text-teal-400 text-[10px]"></i>
                                                 <span>{{ $loker->kuota }} Posisi Tersedia</span>
                                             </span>
                                         </div>

                                         <!-- Deadline Card -->
                                         <div class="bg-slate-50 dark:bg-slate-950/40 border border-slate-100 dark:border-slate-800 rounded-2xl p-3.5 flex flex-col justify-between">
                                             <span class="text-[9px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Batas Pendaftaran</span>
                                             <span class="text-xs font-extrabold text-slate-800 dark:text-white mt-1 flex items-center gap-1.5">
                                                 <i class="fas fa-calendar-alt text-teal-600 dark:text-teal-400 text-[10px]"></i>
                                                 <span>{{ \Carbon\Carbon::parse($loker->batas_daftar)->translatedFormat('d F Y') }}</span>
                                             </span>
                                         </div>

                                         <!-- Kualifikasi Card -->
                                         <div class="bg-slate-50 dark:bg-slate-950/40 border border-slate-100 dark:border-slate-800 rounded-2xl p-3.5 flex flex-col justify-between">
                                             <span class="text-[9px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Kualifikasi Utama</span>
                                             <span class="text-xs font-extrabold text-slate-800 dark:text-white mt-1 truncate flex items-center gap-1.5" title="{{ $loker->required_major }}">
                                                 <i class="fas fa-graduation-cap text-teal-600 dark:text-teal-400 text-[10px]"></i>
                                                 <span class="truncate">{{ $loker->required_major }}</span>
                                             </span>
                                         </div>
                                     </div>

                                     <!-- Detail Job Description -->
                                     <div class="space-y-3">
                                         <h5 class="text-xs font-bold text-slate-800 dark:text-white uppercase tracking-wider flex items-center gap-2">
                                             <i class="fas fa-file-alt text-teal-500"></i> Deskripsi Pekerjaan & Persyaratan
                                         </h5>
                                         <div class="prose prose-sm dark:prose-invert max-w-none text-slate-650 dark:text-slate-350 bg-slate-50/50 dark:bg-slate-950/20 p-5 rounded-2xl border border-slate-100/60 dark:border-slate-800/60 text-xs sm:text-sm shadow-inner leading-relaxed">
                                             {!! $loker->deskripsi !!}
                                         </div>
                                     </div>

                                     <!-- Detailed Office & Penanggung Jawab Section -->
                                     <div class="space-y-3">
                                         <h5 class="text-xs font-bold text-slate-800 dark:text-white uppercase tracking-wider flex items-center gap-2">
                                             <i class="fas fa-building text-teal-500"></i> Informasi Kantor & Penempatan
                                         </h5>
                                         
                                         <div class="bg-slate-50 dark:bg-slate-950/40 border border-slate-150/80 dark:border-slate-800/80 rounded-2xl p-4 sm:p-5 space-y-3.5 text-xs sm:text-sm">
                                             @if(!empty($loker->instansi->nama_pejabat))
                                                 <div class="flex items-start gap-3">
                                                     <div class="w-8 h-8 rounded-lg bg-teal-500/10 text-teal-600 dark:text-teal-400 flex items-center justify-center shrink-0 mt-0.5">
                                                         <i class="fas fa-user-tie text-xs"></i>
                                                     </div>
                                                     <div>
                                                         <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Pejabat Penanggung Jawab</span>
                                                         <span class="font-extrabold text-slate-800 dark:text-slate-200">{{ $loker->instansi->nama_pejabat }}</span>
                                                         <span class="block text-[11px] text-slate-500 dark:text-slate-450 mt-0.5">{{ $loker->instansi->jabatan_pejabat }} (NIP: {{ $loker->instansi->nip_pejabat }})</span>
                                                     </div>
                                                 </div>
                                             @endif

                                             @if(!empty($loker->instansi->jam_mulai_masuk) && !empty($loker->instansi->jam_mulai_pulang))
                                                 <div class="flex items-start gap-3 border-t border-slate-100 dark:border-slate-800 pt-3">
                                                     <div class="w-8 h-8 rounded-lg bg-teal-500/10 text-teal-600 dark:text-teal-400 flex items-center justify-center shrink-0 mt-0.5">
                                                         <i class="fas fa-clock text-xs"></i>
                                                     </div>
                                                     <div>
                                                         <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Jam Absensi Kerja Dinas</span>
                                                         <span class="font-extrabold text-slate-800 dark:text-slate-200">{{ substr($loker->instansi->jam_mulai_masuk, 0, 5) }} s/d {{ substr($loker->instansi->jam_mulai_pulang, 0, 5) }} WITA</span>
                                                         <span class="block text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">Wajib absen masuk dan pulang tepat waktu sesuai radius jangkauan dinas.</span>
                                                     </div>
                                                 </div>
                                             @endif

                                             @if(!empty($loker->instansi->latitude) && !empty($loker->instansi->longitude))
                                                 <div class="flex items-start gap-3 border-t border-slate-100 dark:border-slate-800 pt-3">
                                                     <div class="w-8 h-8 rounded-lg bg-teal-500/10 text-teal-600 dark:text-teal-400 flex items-center justify-center shrink-0 mt-0.5">
                                                         <i class="fas fa-map-marked-alt text-xs"></i>
                                                     </div>
                                                     <div class="flex-grow">
                                                         <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Titik Koordinat Absensi</span>
                                                         <span class="text-slate-800 dark:text-slate-200 block font-semibold text-xs mt-0.5">Radius: {{ $loker->instansi->radius_absen ?? '100' }} meter dari kantor</span>
                                                         <a href="https://www.google.com/maps/search/?api=1&query={{ $loker->instansi->latitude }},{{ $loker->instansi->longitude }}" target="_blank" class="inline-flex items-center gap-1.5 text-teal-600 dark:text-teal-400 font-extrabold hover:underline mt-2 text-xs">
                                                             <span>Buka Google Maps</span>
                                                             <i class="fas fa-external-link-alt text-[10px]"></i>
                                                         </a>
                                                     </div>
                                                 </div>
                                             @endif
                                         </div>
                                     </div>
                                 </div>

                                 <!-- Footer Sticky Drawer -->
                                 <div class="px-6 sm:px-8 py-5 border-t border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-[0_-4px_25px_rgba(0,0,0,0.04)] dark:shadow-[0_-4px_25px_rgba(0,0,0,0.4)] flex items-center justify-end gap-4 pb-safe sm:pb-5 shrink-0 z-20">
                                     <button @click="showModal = false" class="px-5 py-3.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-750 text-slate-700 dark:text-slate-300 rounded-2xl font-bold active:scale-98 transition-all duration-300 text-xs sm:text-sm">
                                         Tutup
                                     </button>
                                     
                                     @auth
                                         @if(auth()->user()->role == 'peserta' && ($isMatch ?? true))
                                             <a href="{{ route('peserta.daftar.form', $loker->id) }}" class="bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white px-6 py-3.5 rounded-2xl font-extrabold shadow-md shadow-teal-600/10 hover:shadow-lg active:scale-98 transition-all duration-300 text-xs sm:text-sm flex items-center gap-2">
                                                 Ajukan Lamaran <i class="fas fa-arrow-right text-xs"></i>
                                             </a>
                                         @endif
                                     @else
                                         <a href="{{ route('peserta.daftar.form', $loker->id) }}" class="bg-slate-900 dark:bg-slate-800 hover:bg-teal-600 dark:hover:bg-teal-600 text-white px-6 py-3.5 rounded-2xl font-extrabold shadow-md active:scale-98 transition-all duration-300 text-xs sm:text-sm flex items-center gap-2">
                                             Masuk & Lamar <i class="fas fa-arrow-right text-xs"></i>
                                         </a>
                                     @endauth
                                 </div>
                             </div>
                         </div>
                     </div>
                 @endif
             @empty
                 <!-- Empty State Lowongan -->
                 <div class="col-span-full py-16 sm:py-24 text-center">
                     <div class="w-20 h-20 bg-slate-100 rounded-3xl flex items-center justify-center mx-auto mb-6 text-slate-350 border border-slate-200/50">
                         <i class="fas fa-folder-open text-3xl"></i>
                     </div>
                     <h3 class="text-lg sm:text-xl font-bold text-slate-800">Tidak Ada Lowongan Ditemukan</h3>
                     <p class="text-slate-500 mt-2 max-w-sm mx-auto text-xs sm:text-sm px-4">Kami tidak menemukan lowongan yang sesuai dengan kriteria filter Anda. Silakan bersihkan pencarian atau ganti pilihan instansi.</p>
                     <a href="{{ route('home') }}#lowongan" class="inline-flex items-center gap-2 mt-6 bg-slate-900 hover:bg-teal-600 text-white px-5 py-3 rounded-2xl text-xs font-bold transition-all duration-300 shadow-md">
                         <i class="fas fa-undo text-xs"></i> Reset Pencarian
                     </a>
                 </div>
             @endforelse
         </div>
         
         <!-- Laravel Pagination Links -->
         <div class="mt-12 sm:mt-16 w-full" id="lowongan-pagination">
             {{ $lowongans->links() }}
         </div>

         <!-- Script Pagination & Scroll Helper -->
         <script>
             function scrollToLowonganHeader() {
                 const targetEl = document.getElementById('lowongan');
                 if (targetEl) {
                     const absoluteTop = targetEl.getBoundingClientRect().top + window.pageYOffset;
                     const finalY = Math.max(0, absoluteTop - 95);
                     window.scrollTo({ top: finalY, behavior: 'instant' });
                 }
             }

             if (window.location.hash === '#lowongan') {
                 scrollToLowonganHeader();
             }

             document.addEventListener('DOMContentLoaded', function() {
                 const paginationLinks = document.querySelectorAll('#lowongan-pagination a');
                 paginationLinks.forEach(link => {
                     const url = new URL(link.href, window.location.origin);
                     url.hash = 'lowongan';
                     link.href = url.toString();
                 });

                 if (window.location.hash === '#lowongan') {
                     scrollToLowonganHeader();
                     setTimeout(() => {
                         scrollToLowonganHeader();
                         document.documentElement.style.scrollBehavior = 'smooth';
                     }, 30);
                 }
             });
         </script>
     </div>

     <!-- FAQ Section -->
     <section id="faq" class="bg-slate-100/40 border-t border-b border-slate-200/50 py-20 sm:py-28 scroll-mt-20 w-full">
         <div class="max-w-4xl mx-auto px-4 sm:px-6 w-full">
             <div class="text-center mb-12 sm:mb-20">
                 <span class="text-xs font-bold text-teal-600 tracking-widest uppercase bg-teal-50 px-4 py-2 rounded-full border border-teal-100">Bantuan Portal</span>
                 <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-800 tracking-tight mt-4">Pertanyaan Populer (FAQ)</h2>
                 <p class="text-slate-500 mt-3 text-sm sm:text-base px-2">Masih bingung? Berikut beberapa jawaban singkat untuk pertanyaan yang sering diajukan.</p>
             </div>

             <!-- FAQ Accordion wrapper -->
             <div x-data="{ activeFaq: null }" class="space-y-4 w-full">
                 <!-- FAQ Item 1 -->
                 <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden transition-all duration-300 shadow-sm w-full">
                     <button @click="activeFaq = (activeFaq === 1 ? null : 1)" class="w-full text-left p-5 sm:p-6 font-bold text-slate-800 flex items-center justify-between hover:bg-slate-50/50 active:bg-slate-100 transition-colors focus:outline-none text-sm sm:text-base">
                         <span class="pr-6">Siapa saja yang boleh mendaftar magang di Pemkot Banjarmasin?</span>
                         <i class="fas shrink-0 transition-transform duration-300" :class="activeFaq === 1 ? 'fa-chevron-up text-teal-600 rotate-180' : 'fa-chevron-down text-slate-400'"></i>
                     </button>
                     <div x-show="activeFaq === 1" x-cloak x-collapse class="px-5 sm:px-6 pb-6 border-t border-slate-50 pt-4 text-xs sm:text-sm text-slate-500 leading-relaxed bg-slate-50/10">
                         Siswa aktif SMA/SMK sederajat dan mahasiswa aktif program diploma (D3/D4) maupun sarjana (S1) dari lembaga pendidikan mana pun dipersilakan mendaftar, dengan ketentuan jurusan sesuai kualifikasi lowongan dinas terkait.
                     </div>
                 </div>

                 <!-- FAQ Item 2 -->
                 <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden transition-all duration-300 shadow-sm w-full">
                     <button @click="activeFaq = (activeFaq === 2 ? null : 2)" class="w-full text-left p-5 sm:p-6 font-bold text-slate-800 flex items-center justify-between hover:bg-slate-50/50 active:bg-slate-100 transition-colors focus:outline-none text-sm sm:text-base">
                         <span class="pr-6">Bagaimana sistem validasi kuota magang dilakukan?</span>
                         <i class="fas shrink-0 transition-transform duration-300" :class="activeFaq === 2 ? 'fa-chevron-up text-teal-600 rotate-180' : 'fa-chevron-down text-slate-400'"></i>
                     </button>
                     <div x-show="activeFaq === 2" x-cloak x-collapse class="px-5 sm:px-6 pb-6 border-t border-slate-50 pt-4 text-xs sm:text-sm text-slate-500 leading-relaxed bg-slate-50/10">
                         Sistem pendaftaran menghitung kuota berdasarkan jadwal masuk dan keluar peserta magang secara dinamis (seperti sistem booking kamar). Jika kuota penuh pada tanggal yang Anda pilih, Anda akan diminta mengisi slot tanggal alternatif.
                     </div>
                 </div>

                 <!-- FAQ Item 3 -->
                 <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden transition-all duration-300 shadow-sm w-full">
                     <button @click="activeFaq = (activeFaq === 3 ? null : 3)" class="w-full text-left p-5 sm:p-6 font-bold text-slate-800 flex items-center justify-between hover:bg-slate-50/50 active:bg-slate-100 transition-colors focus:outline-none text-sm sm:text-base">
                         <span class="pr-6">Apakah saya akan mendapatkan sertifikat setelah magang selesai?</span>
                         <i class="fas shrink-0 transition-transform duration-300" :class="activeFaq === 3 ? 'fa-chevron-up text-teal-600 rotate-180' : 'fa-chevron-down text-slate-400'"></i>
                     </button>
                     <div x-show="activeFaq === 3" x-cloak x-collapse class="px-5 sm:px-6 pb-6 border-t border-slate-50 pt-4 text-xs sm:text-sm text-slate-500 leading-relaxed bg-slate-50/10">
                         Ya. Peserta yang menyelesaikan program magang secara tertib, mengisi laporan kinerja harian, dan dinilai baik oleh pembimbing lapangan akan memperoleh sertifikat elektronik resmi bertanda tangan digital yang dapat diunduh di dashboard masing-masing.
                     </div>
                 </div>
             </div>
         </div>
     </section>

     <!-- Footer Section -->
     <footer class="bg-slate-950 text-white pt-16 pb-8 mt-auto border-t border-slate-900 pb-safe w-full">
         <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
             
             <!-- Footer Grid -->
             <div class="grid grid-cols-1 md:grid-cols-12 gap-8 sm:gap-10 pb-12 border-b border-slate-900 text-left w-full">
                 <!-- Kolom 1 (Branding & Logo) -->
                 <div class="md:col-span-5 flex flex-col items-start gap-4">
                     <div class="flex items-center gap-3">
                         <div class="bg-white/10 rounded-2xl p-2.5 backdrop-blur-md border border-white/10 flex items-center justify-center">
                             <x-application-logo class="w-10 h-10 fill-current text-teal-400" />
                         </div>
                         <div>
                             <h4 class="font-extrabold text-lg leading-tight tracking-tight">SiMagang Kota Banjarmasin</h4>
                             <span class="text-[10px] text-teal-400 font-extrabold uppercase tracking-widest mt-1 block">Pemerintah Kota Banjarmasin</span>
                         </div>
                     </div>
                     <p class="text-xs sm:text-sm text-slate-400 leading-relaxed mt-2 max-w-sm">
                         Portal pendaftaran dan manajemen program magang/praktik kerja industri secara online, terpadu, dan transparan di lingkup Pemerintah Kota Banjarmasin.
                     </p>
                 </div>

                 <!-- Kolom 2 (Navigasi Cepat) -->
                 <div class="md:col-span-3 flex flex-col items-start gap-4">
                     <h5 class="text-xs font-black uppercase tracking-widest text-teal-400">Navigasi</h5>
                     <ul class="space-y-2.5 text-xs sm:text-sm text-slate-450 font-medium">
                         <li><a href="#lowongan" class="hover:text-white transition duration-300">Cari Lowongan Magang</a></li>
                         <li><a href="#langkah" class="hover:text-white transition duration-300">Alur Pendaftaran</a></li>
                         <li><a href="#faq" class="hover:text-white transition duration-300">FAQ & Bantuan</a></li>
                     </ul>
                 </div>

                 <!-- Kolom 3 (Hubungi Kami) -->
                 <div class="md:col-span-4 flex flex-col items-start gap-4">
                     <h5 class="text-xs font-black uppercase tracking-widest text-teal-400">Hubungi Kami</h5>
                     <ul class="space-y-3 text-xs sm:text-sm text-slate-450">
                         <li class="flex items-start gap-3 leading-relaxed">
                             <i class="fas fa-map-marker-alt text-teal-500 mt-1 shrink-0"></i>
                             <span>Dinas Komunikasi, Informatika dan Statistik<br>Kota Banjarmasin, Kalimantan Selatan</span>
                         </li>
                         <li class="flex items-center gap-3">
                             <i class="fas fa-globe text-teal-500 shrink-0"></i>
                             <a href="https://diskominfotik.banjarmasinkota.go.id" target="_blank" class="hover:text-white transition duration-300">diskominfotik.banjarmasinkota.go.id</a>
                         </li>
                     </ul>
                 </div>
             </div>

             <!-- Footer Bottom Bar -->
             <div class="pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-center md:text-left text-[11px] sm:text-xs text-slate-500 w-full">
                 <p>&copy; {{ date('Y') }} Dinas Komunikasi, Informatika dan Statistik Kota Banjarmasin. Hak Cipta Dilindungi.</p>
                 
                 <!-- Social Links -->
                 <div class="flex gap-3.5">
                     <a href="#" aria-label="Instagram" class="w-9 h-9 rounded-xl bg-white/5 hover:bg-teal-600 hover:text-white active:scale-95 transition-all duration-300 flex items-center justify-center text-slate-400"><i class="fab fa-instagram text-sm"></i></a>
                     <a href="#" aria-label="Facebook" class="w-9 h-9 rounded-xl bg-white/5 hover:bg-teal-600 hover:text-white active:scale-95 transition-all duration-300 flex items-center justify-center text-slate-400"><i class="fab fa-facebook text-sm"></i></a>
                     <a href="#" aria-label="Website Resmi" class="w-9 h-9 rounded-xl bg-white/5 hover:bg-teal-600 hover:text-white active:scale-95 transition-all duration-300 flex items-center justify-center text-slate-400"><i class="fas fa-globe text-sm"></i></a>
                 </div>
             </div>
         </div>
     </footer>
     
     <!-- Instant.page for prefetching speed boost -->
     <script src="//instant.page/5.2.0" type="module" integrity="sha384-jnZyxPjiipYXnSU0ygqeac2q7CVYMbh84q0uHVRRxEtvFPiQYbXWUorga2aqZJ0z"></script>
     <script>
         function scrollToResults() {
             const params = new URLSearchParams(window.location.search);
             if (params.has('search') || params.has('instansi_id') || params.has('jurusan')) {
                 const element = document.getElementById('lowongan');
                 if (element) {
                     setTimeout(() => {
                         element.scrollIntoView({ behavior: 'smooth', block: 'start' });
                     }, 50);
                 }
             }
         }
         document.addEventListener('DOMContentLoaded', scrollToResults);
         document.addEventListener('turbo:load', scrollToResults);

         // Intercept GET form submissions to prevent full-page white flash and use Turbo
         document.addEventListener('submit', function(e) {
             const form = e.target;
             if (form && form.getAttribute('method')?.toLowerCase() === 'get' && (form.id === 'search-form' || form.id === 'filter-form')) {
                 e.preventDefault();
                 
                 const url = new URL(form.action || window.location.href);
                 const formData = new FormData(form);
                 const params = new URLSearchParams();
                 
                 for (const [key, value] of formData.entries()) {
                     if (value !== '') {
                         params.append(key, value);
                     }
                 }
                 
                 // Merge parameters so search & filter parameters are preserved together
                 const currentParams = new URLSearchParams(window.location.search);
                 if (form.id === 'filter-form') {
                     if (currentParams.has('search')) {
                         params.set('search', currentParams.get('search'));
                     }
                 } else if (form.id === 'search-form') {
                     if (currentParams.has('instansi_id')) {
                         params.set('instansi_id', currentParams.get('instansi_id'));
                     }
                     if (currentParams.has('jurusan')) {
                         params.set('jurusan', currentParams.get('jurusan'));
                     }
                 }
                 
                 url.search = params.toString();
                 url.hash = 'lowongan';
                 
                 if (window.Turbo) {
                     window.Turbo.visit(url.toString(), { action: 'advance' });
                 } else {
                     window.location.href = url.toString();
                 }
             }
         });
     </script>
</body>
</html>