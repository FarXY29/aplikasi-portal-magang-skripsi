<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiMagang - Pemerintah Kota Banjarmasin</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { 
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; 
        }
        h1, h2, h3, .font-display {
            font-family: 'Outfit', sans-serif;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f8fafc; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; border: 2px solid #f8fafc; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Floating Blobs Animation */
        @keyframes float-blob {
            0%, 100% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-15px) scale(1.05); }
        }
        .animate-float-blob-1 {
            animation: float-blob 8s ease-in-out infinite;
        }
        .animate-float-blob-2 {
            animation: float-blob 12s ease-in-out infinite 2s;
        }

        /* Pattern Sasirangan Modern Background */
        .bg-sasirangan-modern {
            background-color: #0d5c56;
            background-image: 
                linear-gradient(to bottom right, rgba(13, 92, 86, 0.95), rgba(11, 74, 69, 0.98)),
                url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%232dd4bf' fill-opacity='0.08'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            background-size: cover, auto;
        }

        /* Styling Konten CKEditor */
        .ck-content ul { list-style-type: disc; padding-left: 20px; margin-bottom: 0.5rem; }
        .ck-content ol { list-style-type: decimal; padding-left: 20px; margin-bottom: 0.5rem; }
        .ck-content h2 { font-size: 1.25em; font-weight: 700; margin-top: 1rem; color: #111827; }
        .ck-content h3 { font-size: 1.1em; font-weight: 600; margin-top: 0.75rem; color: #374151; }
        .ck-content p { margin-bottom: 0.5em; line-height: 1.6; }
    </style>
</head>
<body class="bg-slate-50 text-slate-600 flex flex-col min-h-screen">

    <!-- Navbar Section -->
    <nav x-data="{ mobileMenuOpen: false, scrolled: false }" 
         x-init="scrolled = ((window.scrollY || window.pageYOffset) > 20); $nextTick(() => { scrolled = ((window.scrollY || window.pageYOffset) > 20) }); setTimeout(() => { scrolled = ((window.scrollY || window.pageYOffset) > 20) }, 100); setTimeout(() => { scrolled = ((window.scrollY || window.pageYOffset) > 20) }, 300);"
         @scroll.window="scrolled = ((window.scrollY || window.pageYOffset) > 20)"
         :class="scrolled ? 'bg-white/80 backdrop-blur-lg shadow-md border-b border-slate-100 py-3' : 'bg-transparent py-5'"
         class="fixed w-full top-0 z-50 transition-all duration-300">
         <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
             <div class="flex justify-between h-16 items-center">
                 <div class="flex items-center gap-3.5">
                     <div class="bg-white rounded-2xl p-1.5 shadow-sm border border-slate-100/50 flex items-center justify-center">
                         <x-application-logo class="w-10 h-10 fill-current text-teal-600" />
                     </div>
                     <div class="flex flex-col">
                         <span class="text-xl font-black leading-none tracking-tight uppercase transition-colors duration-300" 
                               :class="scrolled ? 'text-slate-800' : 'text-white'">Portal Magang</span>
                         <span class="text-[10px] font-bold tracking-widest uppercase transition-colors duration-300 mt-0.5" 
                               :class="scrolled ? 'text-teal-600' : 'text-teal-200/90'">Pemkot Banjarmasin</span>
                     </div>
                 </div>

                 <!-- Desktop Menu -->
                 <div class="hidden md:flex items-center gap-8">
                     <a href="#lowongan" class="text-sm font-bold transition hover:text-teal-400" :class="scrolled ? 'text-slate-600 hover:text-teal-600' : 'text-white/90 hover:text-white'">Cari Lowongan</a>
                     <a href="#langkah" class="text-sm font-bold transition hover:text-teal-400" :class="scrolled ? 'text-slate-600 hover:text-teal-600' : 'text-white/90 hover:text-white'">Alur Magang</a>
                     <a href="#faq" class="text-sm font-bold transition hover:text-teal-400" :class="scrolled ? 'text-slate-600 hover:text-teal-600' : 'text-white/90 hover:text-white'">FAQ</a>
                     
                     @if (Route::has('login'))
                         @auth
                             <a href="{{ url('/dashboard') }}" class="bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-teal-600/25 transition-all text-sm transform hover:-translate-y-0.5 active:scale-95">
                                 <i class="fas fa-columns mr-2"></i>Dashboard Saya
                             </a>
                         @else
                             <div class="flex items-center gap-4">
                                 <a href="{{ route('login') }}" class="px-5 py-2.5 text-sm font-bold transition rounded-xl hover:-translate-y-0.5" :class="scrolled ? 'text-slate-600 hover:bg-slate-100 hover:text-slate-800' : 'text-white hover:bg-white/10'">
                                     Masuk
                                 </a>
                                 @if (Route::has('register'))
                                     <a href="{{ route('register') }}" class="bg-white text-teal-700 hover:bg-teal-50 hover:text-teal-800 px-6 py-2.5 rounded-xl font-extrabold shadow-md transition-all text-sm transform hover:-translate-y-0.5 active:scale-95 border border-slate-100">
                                         Daftar Sekarang
                                     </a>
                                 @endif
                             </div>
                         @endauth
                     @endif
                 </div>

                 <!-- Mobile Menu Toggle -->
                 <div class="md:hidden flex items-center">
                     <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-xl transition focus:outline-none" :class="scrolled ? 'text-slate-600' : 'text-white'">
                         <i class="fas" :class="mobileMenuOpen ? 'fa-times text-2xl' : 'fa-bars text-2xl'"></i>
                     </button>
                 </div>
             </div>
         </div>

         <!-- Mobile Menu Drawer -->
         <div x-show="mobileMenuOpen" 
              x-cloak
              @click.away="mobileMenuOpen = false"
              x-transition:enter="transition ease-out duration-200"
              x-transition:enter-start="opacity-0 -translate-y-4"
              x-transition:enter-end="opacity-100 translate-y-0"
              x-transition:leave="transition ease-in duration-150"
              x-transition:leave-start="opacity-100 translate-y-0"
              x-transition:leave-end="opacity-0 -translate-y-4"
              class="md:hidden bg-white border-t border-slate-100 shadow-xl absolute w-full left-0">
             <div class="px-4 py-6 space-y-3 bg-white">
                 <a href="#lowongan" @click="mobileMenuOpen = false" class="flex items-center px-4 py-3 text-base font-bold text-slate-700 bg-slate-50 rounded-xl hover:bg-teal-50 hover:text-teal-600 transition">
                     <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center shadow-sm text-teal-500 mr-3">
                         <i class="fas fa-search text-xs"></i>
                     </div>
                     Cari Lowongan
                 </a>
                 <a href="#langkah" @click="mobileMenuOpen = false" class="flex items-center px-4 py-3 text-base font-bold text-slate-700 bg-slate-50 rounded-xl hover:bg-teal-50 hover:text-teal-600 transition">
                     <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center shadow-sm text-teal-500 mr-3">
                         <i class="fas fa-tasks text-xs"></i>
                     </div>
                     Alur Pendaftaran
                 </a>
                 <a href="#faq" @click="mobileMenuOpen = false" class="flex items-center px-4 py-3 text-base font-bold text-slate-700 bg-slate-50 rounded-xl hover:bg-teal-50 hover:text-teal-600 transition">
                     <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center shadow-sm text-teal-500 mr-3">
                         <i class="fas fa-question-circle text-xs"></i>
                     </div>
                     FAQ
                 </a>

                 <div class="border-t border-slate-100 pt-4 mt-2">
                     @if (Route::has('login'))
                         @auth
                             <a href="{{ url('/dashboard') }}" class="flex items-center justify-center w-full px-4 py-3 bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-xl font-bold shadow-lg shadow-teal-200 transition">
                                 <i class="fas fa-columns mr-2"></i> Ke Dashboard Saya
                             </a>
                         @else
                             <div class="grid grid-cols-2 gap-3">
                                 <a href="{{ route('login') }}" class="flex items-center justify-center px-4 py-3 text-sm font-bold text-slate-700 border border-slate-200 rounded-xl hover:bg-slate-50 transition">
                                     Masuk
                                 </a>
                                 @if (Route::has('register'))
                                     <a href="{{ route('register') }}" class="flex items-center justify-center px-4 py-3 text-sm font-bold text-white bg-teal-600 rounded-xl hover:bg-teal-700 shadow-md transition">
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
    <div class="bg-sasirangan-modern text-white pt-44 pb-36 md:pb-44 relative overflow-hidden">
        <!-- Glowing background blobs -->
        <div class="absolute top-0 right-0 -mt-24 -mr-24 w-[400px] h-[400px] bg-teal-300 opacity-20 rounded-full blur-[100px] animate-float-blob-1"></div>
        <div class="absolute bottom-0 left-0 -mb-24 -ml-24 w-[350px] h-[350px] bg-emerald-400 opacity-20 rounded-full blur-[100px] animate-float-blob-2"></div>

        <div class="max-w-7xl mx-auto px-4 text-center relative z-10">
            <span class="inline-flex items-center gap-1.5 py-1.5 px-3.5 rounded-full bg-teal-800/40 border border-teal-400/20 backdrop-blur-md text-teal-200 text-xs font-bold tracking-wider uppercase mb-8 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-teal-400 animate-pulse"></span>
                Portal Resmi Magang Kota Banjarmasin
            </span>
            <h1 class="text-4xl md:text-6xl font-black mb-6 leading-[1.15] drop-shadow-sm tracking-tight">
                Bangun Karir & Pengalaman Mulia <br/> Bersama <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-200 via-emerald-100 to-white">Pemkot Banjarmasin</span>
            </h1>
            <p class="text-lg md:text-xl text-teal-100/90 mb-12 max-w-2xl mx-auto font-medium leading-relaxed">
                Temukan lowongan magang terbaik di berbagai Instansi Pemerintah Kota Banjarmasin secara transparan dan terintegrasi.
            </p>
            
            <!-- Global Search Dock -->
            <div class="max-w-2xl mx-auto">
                <form action="{{ route('home') }}#lowongan" method="GET" class="relative" id="search-form">
                    <div class="flex items-center p-2.5 bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl border border-white/20 transform transition duration-300 focus-within:scale-[1.01] focus-within:bg-white focus-within:shadow-teal-950/20">
                        <div class="pl-5 text-slate-400">
                            <i class="fas fa-search text-xl"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            class="w-full py-3.5 px-4 text-slate-800 bg-transparent text-base font-bold placeholder-slate-400 focus:outline-none border-none ring-0 focus:ring-0" 
                            placeholder="Cari posisi magang atau dinas instansi...">
                        <button type="submit" class="bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white font-extrabold py-3 px-9 rounded-xl transition duration-300 shadow-lg shadow-teal-700/25 active:scale-95">
                            Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Wave Transition SVG -->
        <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none">
            <svg class="relative block w-full h-[80px] md:h-[120px]" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" class="fill-slate-50"></path>
            </svg>
        </div>
    </div>

    <!-- Floating Statistics Cards -->
    <div class="relative z-20 -mt-24 px-4">
        <div class="max-w-5xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Instansi -->
                <div class="bg-white p-7 rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 flex items-center gap-5 transform hover:-translate-y-1 transition duration-300 group">
                    <div class="w-14 h-14 bg-teal-50 rounded-2xl flex items-center justify-center text-teal-600 group-hover:bg-teal-600 group-hover:text-white transition duration-500 shadow-inner">
                        <i class="fas fa-building text-2xl"></i>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-slate-800 tracking-tight">{{ $totalInstansi }}</div>
                        <div class="text-slate-400 text-xs font-bold uppercase tracking-wider mt-0.5">Instansi</div>
                    </div>
                </div>
                <!-- Lowongan -->
                <div class="bg-white p-7 rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 flex items-center gap-5 transform hover:-translate-y-1 transition duration-300 group">
                    <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition duration-500 shadow-inner">
                        <i class="fas fa-briefcase text-2xl"></i>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-slate-800 tracking-tight">{{ $totalLowongan }}</div>
                        <div class="text-slate-400 text-xs font-bold uppercase tracking-wider mt-0.5">Posisi Buka</div>
                    </div>
                </div>
                <!-- Alumni -->
                <div class="bg-white p-7 rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 flex items-center gap-5 transform hover:-translate-y-1 transition duration-300 group">
                    <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-500 group-hover:bg-amber-500 group-hover:text-white transition duration-500 shadow-inner">
                        <i class="fas fa-user-graduate text-2xl"></i>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-slate-800 tracking-tight">{{ $totalAlumni }}</div>
                        <div class="text-slate-400 text-xs font-bold uppercase tracking-wider mt-0.5">Alumni Magang</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alur/Langkah Pendaftaran Section -->
    <div id="langkah" class="max-w-7xl mx-auto px-4 py-24 scroll-mt-24">
        <div class="text-center mb-16">
            <span class="text-xs font-bold text-teal-600 tracking-widest uppercase bg-teal-50 px-3.5 py-1.5 rounded-full border border-teal-100">Bagaimana Cara Bergabung?</span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800 tracking-tight mt-4">Alur Pendaftaran Program Magang</h2>
            <p class="text-slate-500 mt-3 max-w-xl mx-auto text-base">Ikuti langkah mudah berikut ini untuk memulai perjalanan magang Anda bersama Pemkot Banjarmasin.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative">
            <!-- Connecting Line for Desktop -->
            <div class="hidden md:block absolute top-1/3 left-[12%] right-[12%] h-0.5 bg-gradient-to-r from-teal-200 via-emerald-100 to-slate-200 -z-10"></div>

            <!-- Step 1 -->
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition text-center relative flex flex-col items-center">
                <div class="w-14 h-14 bg-teal-50 text-teal-600 rounded-full flex items-center justify-center font-bold text-xl mb-5 shadow-inner border border-teal-100 relative">
                    1
                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-teal-500 rounded-full flex items-center justify-center text-[9px] text-white"><i class="fas fa-check"></i></span>
                </div>
                <h4 class="font-bold text-slate-800 text-lg mb-2">Buat Akun</h4>
                <p class="text-slate-500 text-sm leading-relaxed">Registrasikan data diri Anda pada portal dengan email aktif & isi profil akademis lengkap.</p>
            </div>

            <!-- Step 2 -->
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition text-center relative flex flex-col items-center">
                <div class="w-14 h-14 bg-teal-50 text-teal-600 rounded-full flex items-center justify-center font-bold text-xl mb-5 shadow-inner border border-teal-100">
                    2
                </div>
                <h4 class="font-bold text-slate-800 text-lg mb-2">Pilih Lowongan</h4>
                <p class="text-slate-500 text-sm leading-relaxed">Cari dinas instansi yang relevan dengan kualifikasi jurusan dan minat karir Anda.</p>
            </div>

            <!-- Step 3 -->
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition text-center relative flex flex-col items-center">
                <div class="w-14 h-14 bg-teal-50 text-teal-600 rounded-full flex items-center justify-center font-bold text-xl mb-5 shadow-inner border border-teal-100">
                    3
                </div>
                <h4 class="font-bold text-slate-800 text-lg mb-2">Pilih Slot Periode</h4>
                <p class="text-slate-500 text-sm leading-relaxed">Tentukan tanggal masuk & selesai. Sistem akan memvalidasi sisa kuota yang tersedia.</p>
            </div>

            <!-- Step 4 -->
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition text-center relative flex flex-col items-center">
                <div class="w-14 h-14 bg-teal-50 text-teal-600 rounded-full flex items-center justify-center font-bold text-xl mb-5 shadow-inner border border-teal-100">
                    4
                </div>
                <h4 class="font-bold text-slate-800 text-lg mb-2">Mulai Magang</h4>
                <p class="text-slate-500 text-sm leading-relaxed">Setelah divalidasi oleh instansi tujuan, Anda siap diterjunkan & mendapatkan pembimbing.</p>
            </div>
        </div>
    </div>

    <!-- Vacancies List Section -->
    <div id="lowongan" class="max-w-7xl mx-auto px-4 py-16 flex-grow scroll-mt-24">
        
        @php
            $globalAnnouncement = \App\Models\Setting::where('key', 'announcement')->value('value');
        @endphp
        @if(!empty($globalAnnouncement))
            <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-250/70 rounded-3xl p-6 md:p-8 shadow-sm flex flex-col md:flex-row gap-5 items-start md:items-center justify-between mb-12 overflow-hidden relative">
                <div class="absolute right-0 top-0 translate-x-6 -translate-y-6 opacity-5 text-amber-500 pointer-events-none">
                    <i class="fas fa-bullhorn text-9xl"></i>
                </div>
                <div class="flex gap-4 items-start">
                    <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0 shadow-inner">
                        <i class="fas fa-bullhorn text-xl"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold text-amber-600 uppercase tracking-widest bg-amber-100/50 px-2 py-0.5 rounded-md border border-amber-200">Pengumuman Terbaru</span>
                        <div class="text-slate-800 font-bold text-base md:text-lg mt-1.5 leading-relaxed">
                            {!! nl2br(e($globalAnnouncement)) !!}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Banner Penempatan Otomatis -->
        <div class="bg-gradient-to-r from-teal-800 via-teal-900 to-emerald-900 rounded-3xl p-6 md:p-8 text-white shadow-xl shadow-teal-950/20 mb-12 overflow-hidden relative border border-teal-700/50">
            <div class="absolute right-0 top-0 translate-x-6 -translate-y-6 opacity-10 text-white pointer-events-none">
                <i class="fas fa-magic text-9xl"></i>
            </div>
            <div class="flex flex-col md:flex-row gap-6 items-center justify-between relative z-10">
                <div class="flex gap-4 items-start">
                    <div class="w-12 h-12 rounded-2xl bg-teal-500/20 text-teal-300 flex items-center justify-center flex-shrink-0 shadow-inner border border-teal-500/30">
                        <i class="fas fa-magic text-xl"></i>
                    </div>
                    <div>
                        <span class="text-[9px] font-extrabold text-emerald-300 uppercase tracking-widest bg-emerald-500/20 px-2 py-0.5 rounded-md border border-emerald-500/30">Fitur Baru</span>
                        <h3 class="text-white font-extrabold text-lg md:text-xl mt-1.5 leading-snug">Bingung Memilih Dinas / Instansi?</h3>
                        <p class="text-teal-100/90 text-sm mt-1 max-w-2xl font-medium leading-relaxed">
                            Gunakan **Penempatan Otomatis**! Sistem akan mencocokkan jurusan Anda dengan instansi/dinas yang saat ini masih memiliki kuota longgar dan kekurangan peserta magang secara berimbang.
                        </p>
                    </div>
                </div>
                <a href="{{ route('peserta.apply_automatic.form') }}" class="shrink-0 bg-white text-teal-800 hover:bg-teal-50 px-6 py-3 rounded-xl font-extrabold shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5 active:scale-95 text-sm">
                    Daftar Penempatan Otomatis
                </a>
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
            <div>
                <span class="text-xs font-bold text-teal-600 tracking-widest uppercase bg-teal-50 px-3 py-1 rounded-full border border-teal-100">Eksplorasi Peran</span>
                <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight mt-3">Lowongan Magang Terbaru</h2>
                <p class="text-slate-500 mt-1.5 text-base">Dapatkan kesempatan mengabdi dan belajar langsung di kantor dinas.</p>
            </div>
            
            @if(request()->anyFilled(['posisi', 'instansi_id', 'jurusan', 'search']))
                <a href="{{ route('home') }}#lowongan" class="group flex items-center bg-rose-50 text-rose-600 px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-rose-100 hover:text-rose-700 transition">
                    <i class="fas fa-undo-alt mr-2 group-hover:-rotate-180 transition duration-500"></i> Bersihkan Filter
                </a>
            @endif
        </div>

        <!-- Filter Dock Card -->
        <div class="bg-white p-7 rounded-3xl shadow-sm border border-slate-200/80 mb-10">
            <form action="{{ route('home') }}#lowongan" method="GET" id="filter-form">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
                    <!-- Instansi Select -->
                    <div class="md:col-span-5">
                        <label class="block text-xs font-extrabold text-slate-400 uppercase mb-2 ml-1 tracking-wider">Pilih Instansi </label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-teal-500 transition">
                                <i class="fas fa-building text-sm"></i>
                            </span>
                            <select name="instansi_id" class="w-full pl-11 pr-10 py-3.5 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm font-semibold bg-slate-50/50 focus:bg-white transition appearance-none cursor-pointer text-slate-800">
                                <option value="">Semua Instansi</option>
                                @foreach($instansis as $instansi)
                                    <option value="{{ $instansi->id }}" {{ request('instansi_id') == $instansi->id ? 'selected' : '' }}>
                                        {{ Str::limit($instansi->nama_dinas, 40) }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Jurusan Input -->
                    <div class="md:col-span-5">
                        <div class="flex justify-between items-end mb-2 ml-1">
                            <label class="block text-xs font-extrabold text-slate-400 uppercase tracking-wider">Cari Jurusan / Keahlian</label>
                            <div class="flex gap-1.5 mr-1">
                                <button type="button" onclick="document.getElementById('jurusan-input').value='SMA'; document.getElementById('filter-form').submit()" class="text-[9px] font-bold px-2 py-0.5 rounded bg-slate-100 hover:bg-teal-50 text-slate-500 hover:text-teal-700 border border-slate-200 transition">SMA</button>
                                <button type="button" onclick="document.getElementById('jurusan-input').value='SMK'; document.getElementById('filter-form').submit()" class="text-[9px] font-bold px-2 py-0.5 rounded bg-slate-100 hover:bg-teal-50 text-slate-500 hover:text-teal-700 border border-slate-200 transition">SMK</button>
                                <button type="button" onclick="document.getElementById('jurusan-input').value='S1'; document.getElementById('filter-form').submit()" class="text-[9px] font-bold px-2 py-0.5 rounded bg-slate-100 hover:bg-teal-50 text-slate-500 hover:text-teal-700 border border-slate-200 transition">S1</button>
                            </div>
                        </div>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-teal-500 transition">
                                <i class="fas fa-graduation-cap text-sm"></i>
                            </span>
                            <input type="text" name="jurusan" id="jurusan-input" value="{{ request('jurusan') }}" placeholder="Contoh: SMA, SMK, Informatika..." 
                                class="w-full pl-11 pr-4 py-3.5 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm font-semibold bg-slate-50/50 focus:bg-white transition text-slate-800 placeholder-slate-400">
                        </div>
                    </div>

                    <!-- Filter Button -->
                    <div class="md:col-span-2">
                        <button type="submit" class="w-full bg-slate-900 hover:bg-slate-850 text-white font-extrabold py-3.5 px-4 rounded-2xl shadow-lg transition transform active:scale-95 flex items-center justify-center gap-2">
                            <i class="fas fa-filter text-xs"></i> Terapkan
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Vacancy Card Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($lowongans as $loker)
                @if($loker->kuota > 0)
                    <div x-data="{ showModal: false }" class="h-full flex flex-col">
                        <div @click="showModal = true" class="cursor-pointer group bg-white rounded-3xl border border-slate-200/90 overflow-hidden hover:shadow-xl hover:border-teal-200 hover:-translate-y-1 transition-all duration-300 flex flex-col h-full relative">
                        <!-- Card Header -->
                        <div class="p-6 pb-4 border-b border-slate-100 bg-gradient-to-br from-white to-slate-50/30">
                            <div class="flex justify-between items-start mb-3.5">
                                <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center shadow-inner">
                                    <i class="fas fa-laptop-code text-xl"></i>
                                </div>
                                <span class="bg-teal-100 text-teal-800 text-[10px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wider">
                                    {{ $loker->status }}
                                </span>
                            </div>
                            
                            <!-- Position Title & Instansi Name -->
                            <h3 class="text-xl font-bold text-slate-800 group-hover:text-teal-600 transition line-clamp-1 mb-1" title="{{ $loker->judul_posisi }}">
                                {{ $loker->judul_posisi }}
                            </h3>
                            <p class="text-xs text-slate-500 flex items-center font-medium">
                                <i class="fas fa-building text-slate-350 mr-1.5"></i>
                                {{ Str::limit($loker->instansi->nama_dinas, 42) }}
                            </p>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 pt-5 flex-grow flex flex-col">
                            <div class="mb-4">
                                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest block mb-2">Jurusan Syarat:</span>
                                <div class="flex flex-wrap gap-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200/60 max-w-full">
                                        <i class="fas fa-graduation-cap mr-2 text-slate-400"></i>
                                        <span class="truncate" title="{{ $loker->required_major }}">{{ $loker->required_major }}</span>
                                    </span>
                                    @if(preg_match('/SMA|SMK/i', $loker->required_major))
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 flex-shrink-0">
                                            <i class="fas fa-school mr-1.5 text-indigo-500"></i> SMA/SMK
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Description Preview -->
                            <div class="prose prose-sm prose-slate max-w-none mb-6 line-clamp-3 text-slate-500 text-sm leading-relaxed">
                                {!! strip_tags($loker->deskripsi) !!}
                            </div>

                            <!-- Quota details -->
                            <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between text-sm">
                                <div class="flex items-center text-slate-500 gap-2">
                                    <div class="relative flex">
                                        <i class="fas fa-chart-pie text-teal-600 text-lg"></i>
                                        <span class="absolute -top-0.5 -right-0.5 flex h-2 w-2">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                        </span>
                                    </div>
                                    <span class="text-xs font-bold text-slate-500">
                                        Kapasitas: <b class="text-slate-800 text-sm ml-0.5">{{ $loker->kuota }} kursi</b>
                                    </span>
                                </div>

                                @if($loker->kuota < 3)
                                    <span class="text-[9px] text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full font-bold border border-rose-100 animate-pulse">
                                        Slot Terbatas!
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Card CTA Block -->
                        <div class="p-4 bg-slate-50/50 border-t border-slate-100">
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
                                        <a @click.stop href="{{ route('peserta.daftar.form', $loker->id) }}" class="flex items-center justify-center w-full bg-teal-600 hover:bg-teal-700 text-white py-3 rounded-2xl font-bold shadow-md hover:shadow-lg transition-all transform active:scale-95 gap-1.5 text-sm">
                                            Ajukan Lamaran Magang <i class="fas fa-arrow-right text-xs"></i>
                                        </a>
                                    @else
                                        <button disabled class="flex items-center justify-center w-full bg-slate-200 text-slate-400 py-3 rounded-2xl font-bold cursor-not-allowed text-sm">
                                            <i class="fas fa-lock mr-2"></i> Syarat Jurusan Tidak Sesuai
                                        </button>
                                    @endif
                                @elseif(auth()->user()->role == 'admin_kota' || auth()->user()->role == 'admin_instansi')
                                    <button disabled class="w-full text-center text-xs font-extrabold text-slate-400 uppercase tracking-widest py-2">Pratinjau Admin</button>
                                @endif
                            @else
                                <a @click.stop href="{{ route('peserta.daftar.form', $loker->id) }}" class="flex items-center justify-center w-full bg-slate-900 hover:bg-teal-600 text-white hover:text-white py-3 rounded-2xl font-bold shadow-md hover:shadow-lg transition-all transform active:scale-95 text-sm">
                                    Ajukan Lamaran
                                </a>
                            @endauth
                        </div>
                    </div>

                    <!-- Modal Detail Lowongan -->
                    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6">
                        <!-- Backdrop -->
                        <div x-show="showModal" 
                             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
                             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
                             @click="showModal = false"></div>

                        <!-- Modal Panel -->
                        <div x-show="showModal" 
                             x-transition:enter="ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                             x-transition:leave="ease-in duration-200 transform" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                             class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col z-10 transition-all text-left">
                            
                            <!-- Modal Header -->
                            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                                <h3 class="text-lg font-extrabold text-slate-800 flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shadow-inner">
                                        <i class="fas fa-info-circle text-sm"></i>
                                    </div>
                                    Detail Lowongan Magang
                                </h3>
                                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 bg-white hover:bg-slate-100 rounded-xl w-8 h-8 flex items-center justify-center transition border border-transparent hover:border-slate-200">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            
                            <!-- Modal Body -->
                            <div class="px-6 py-6 overflow-y-auto flex-grow">
                                <span class="bg-teal-100 text-teal-800 text-[10px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wider mb-3 inline-block">
                                    {{ $loker->status }}
                                </span>
                                <h4 class="text-2xl font-black text-slate-800 mb-2 leading-tight">{{ $loker->judul_posisi }}</h4>
                                <p class="text-sm font-bold text-teal-600 mb-6 flex items-center gap-2">
                                    <i class="fas fa-building"></i> {{ $loker->instansi->nama_dinas }}
                                </p>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 flex items-center gap-3">
                                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-teal-600 shadow-sm border border-slate-100">
                                            <i class="fas fa-graduation-cap"></i>
                                        </div>
                                        <div class="min-w-0 flex flex-col items-start">
                                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kualifikasi Jurusan</span>
                                            <div class="flex items-center gap-2 mt-0.5">
                                                <span class="text-sm font-bold text-slate-800 truncate" title="{{ $loker->required_major }}">{{ $loker->required_major }}</span>
                                                @if(preg_match('/SMA|SMK/i', $loker->required_major))
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[9px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 flex-shrink-0">
                                                        <i class="fas fa-school mr-1 text-indigo-500"></i> SMA/SMK
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 flex items-center gap-3">
                                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-teal-600 shadow-sm border border-slate-100">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <div>
                                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kapasitas Maksimal</span>
                                            <span class="block text-sm font-bold text-slate-800 mt-0.5">{{ $loker->kuota }} Kursi</span>
                                        </div>
                                    </div>
                                </div>

                                <h5 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                                    <i class="fas fa-list-ul text-teal-500"></i> Deskripsi & Persyaratan
                                </h5>
                                <div class="prose prose-sm prose-slate max-w-none text-slate-600 bg-slate-50/50 p-5 rounded-2xl border border-slate-100/50">
                                    {!! $loker->deskripsi !!}
                                </div>
                            </div>

                            <!-- Modal Footer -->
                            <div class="px-6 py-5 border-t border-slate-100 bg-slate-50 flex items-center justify-end gap-3">
                                <button @click="showModal = false" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold hover:bg-slate-50 transition text-sm">
                                    Tutup
                                </button>
                                
                                @auth
                                    @if(auth()->user()->role == 'peserta' && ($isMatch ?? true))
                                        <a href="{{ route('peserta.daftar.form', $loker->id) }}" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2.5 rounded-xl font-bold shadow-md hover:shadow-lg transition transform active:scale-95 text-sm flex items-center gap-2">
                                            Ajukan Lamaran <i class="fas fa-arrow-right text-xs"></i>
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('peserta.daftar.form', $loker->id) }}" class="bg-slate-900 hover:bg-teal-600 text-white px-6 py-2.5 rounded-xl font-bold shadow-md hover:shadow-lg transition transform active:scale-95 text-sm flex items-center gap-2">
                                        Masuk & Lamar <i class="fas fa-arrow-right text-xs"></i>
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @empty
                <div class="col-span-full py-20 text-center">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-350 border border-slate-200/50">
                        <i class="fas fa-folder-open text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800">Tidak Ada Lowongan Ditemukan</h3>
                    <p class="text-slate-500 mt-2 max-w-sm mx-auto text-sm">Cobalah untuk mengubah parameter pencarian atau dinas yang dipilih untuk menampilkan data.</p>
                    <a href="{{ route('home') }}#lowongan" class="inline-block mt-6 text-teal-600 font-extrabold hover:text-teal-700 hover:underline text-sm">
                        Reset Pencarian
                    </a>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination Links -->
        <div class="mt-12">
            {{ $lowongans->links() }}
        </div>
    </div>

    <!-- FAQ Accordion Section -->
    <div id="faq" class="bg-slate-100/50 border-t border-b border-slate-200/40 py-24 scroll-mt-24">
        <div class="max-w-4xl mx-auto px-4">
            <div class="text-center mb-16">
                <span class="text-xs font-bold text-teal-600 tracking-widest uppercase bg-teal-50 px-3.5 py-1.5 rounded-full border border-teal-100">Bantuan Portal</span>
                <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight mt-4">Pertanyaan yang Sering Diajukan (FAQ)</h2>
                <p class="text-slate-500 mt-3 text-base">Butuh penjelasan singkat? Temukan jawabannya di bawah ini.</p>
            </div>

            <!-- FAQ Accordion container -->
            <div x-data="{ activeFaq: null }" class="space-y-4">
                <!-- Item 1 -->
                <div class="bg-white rounded-2xl border border-slate-200/70 overflow-hidden transition-all duration-300">
                    <button @click="activeFaq = (activeFaq === 1 ? null : 1)" class="w-full text-left p-6 font-bold text-slate-800 flex items-center justify-between hover:bg-slate-50 transition focus:outline-none">
                        <span>Siapa saja yang boleh mendaftar magang di Pemkot Banjarmasin?</span>
                        <i class="fas" :class="activeFaq === 1 ? 'fa-chevron-up text-teal-600' : 'fa-chevron-down text-slate-400'"></i>
                    </button>
                    <div x-show="activeFaq === 1" x-cloak x-collapse class="px-6 pb-6 border-t border-slate-50 pt-4 text-sm text-slate-500 leading-relaxed">
                        Seluruh siswa SMA/SMK sederajat dan mahasiswa aktif D3/D4/S1 dari universitas manapun diperbolehkan melamar, dengan syarat kualifikasi jurusan sesuai dengan yang diminta oleh lowongan instansi terkait.
                    </div>
                </div>

                <!-- Item 2 -->
                <div class="bg-white rounded-2xl border border-slate-200/70 overflow-hidden transition-all duration-300">
                    <button @click="activeFaq = (activeFaq === 2 ? null : 2)" class="w-full text-left p-6 font-bold text-slate-800 flex items-center justify-between hover:bg-slate-50 transition focus:outline-none">
                        <span>Bagaimana sistem validasi kuota magang dilakukan?</span>
                        <i class="fas" :class="activeFaq === 2 ? 'fa-chevron-up text-teal-600' : 'fa-chevron-down text-slate-400'"></i>
                    </button>
                    <div x-show="activeFaq === 2" x-cloak x-collapse class="px-6 pb-6 border-t border-slate-50 pt-4 text-sm text-slate-500 leading-relaxed">
                        Sistem kami menggunakan algoritma alokasi hotel booking. Kuota dihitung berdasarkan irisan tanggal mulai dan selesai. Jika pada rentang tanggal pilihan Anda kuota penuh oleh pendaftar lain, Anda akan disarankan memilih tanggal alternatif.
                    </div>
                </div>

                <!-- Item 3 -->
                <div class="bg-white rounded-2xl border border-slate-200/70 overflow-hidden transition-all duration-300">
                    <button @click="activeFaq = (activeFaq === 3 ? null : 3)" class="w-full text-left p-6 font-bold text-slate-800 flex items-center justify-between hover:bg-slate-50 transition focus:outline-none">
                        <span>Apakah saya akan mendapatkan sertifikat setelah masa magang berakhir?</span>
                        <i class="fas" :class="activeFaq === 3 ? 'fa-chevron-up text-teal-600' : 'fa-chevron-down text-slate-400'"></i>
                    </button>
                    <div x-show="activeFaq === 3" x-cloak x-collapse class="px-6 pb-6 border-t border-slate-50 pt-4 text-sm text-slate-500 leading-relaxed">
                        Ya. Peserta yang masa magangnya habis atau ditandai selesai oleh admin Instansi, dan telah dinilai oleh pembimbing_lapangan, akan dapat mengunduh Sertifikat Magang resmi bertanda tangan digital serta transkrip nilai langsung dari halaman dashboard peserta.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer class="bg-slate-900 text-white pt-16 pb-8 mt-auto border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center mb-10 pb-8 border-b border-slate-800/80">
                <div class="flex items-center gap-3.5 mb-6 md:mb-0">
                    <div class="bg-white/10 rounded-2xl p-2.5 backdrop-blur-sm border border-white/5 flex items-center justify-center">
                        <x-application-logo class="w-10 h-10 fill-current text-teal-400" />
                    </div>
                    <div>
                        <h4 class="font-extrabold text-lg leading-none tracking-tight">Portal Magang Banjarmasin</h4>
                        <span class="text-xs text-slate-450 uppercase tracking-widest mt-1 block">Pemerintah Kota Banjarmasin</span>
                    </div>
                </div>
                <div class="flex gap-4">
                    <a href="#" class="w-10 h-10 rounded-xl bg-white/5 hover:bg-teal-600 hover:text-white transition duration-300 flex items-center justify-center text-slate-400"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="w-10 h-10 rounded-xl bg-white/5 hover:bg-teal-600 hover:text-white transition duration-300 flex items-center justify-center text-slate-400"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="w-10 h-10 rounded-xl bg-white/5 hover:bg-teal-600 hover:text-white transition duration-300 flex items-center justify-center text-slate-400"><i class="fas fa-globe"></i></a>
                </div>
            </div>
            <div class="text-center md:text-left flex flex-col md:flex-row justify-between text-xs text-slate-550 gap-4">
                <p>&copy; {{ date('Y') }} Dinas Komunikasi, Informatika dan Statistik Kota Banjarmasin. Hak Cipta Dilindungi.</p>
                <div class="flex justify-center md:justify-start gap-5">
                    <a href="#" class="hover:text-white transition">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-white transition">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>
    
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
                
                // Merge parameters so search & filter don't overwrite each other
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