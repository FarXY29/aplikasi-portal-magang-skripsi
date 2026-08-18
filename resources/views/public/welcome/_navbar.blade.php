<nav x-data="{ mobileMenuOpen: false, scrolled: false }" 
     x-init="scrolled = ((window.scrollY || window.pageYOffset) > 20)"
     @scroll.window="scrolled = ((window.scrollY || window.pageYOffset) > 20)"
     :class="scrolled ? 'bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl shadow-md border-b border-slate-200/50 dark:border-gray-800 py-3' : 'bg-transparent py-5 sm:py-6'"
     class="fixed w-full top-0 z-50 transition-all duration-300 ease-in-out">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="flex justify-between h-14 sm:h-16 items-center w-full">
            <!-- Brand Logo -->
            <a href="{{ url('/') }}" class="flex items-center gap-3 group focus:outline-none">
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-2 shadow-sm border border-slate-200/60 dark:border-gray-700/50 flex items-center justify-center shrink-0 transition-transform duration-300 group-hover:scale-105">
                    <x-application-logo class="w-8 h-8 sm:w-9 sm:h-9 fill-current text-teal-600 dark:text-teal-400" />
                </div>
                <div class="flex flex-col">
                    <span class="text-base sm:text-lg font-black leading-none tracking-tight uppercase transition-colors duration-300 font-display" 
                          :class="scrolled ? 'text-slate-900 dark:text-white' : 'text-white group-hover:text-teal-200'">SiMagang</span>
                    <span class="text-[9px] font-extrabold tracking-widest uppercase transition-colors duration-300 mt-1" 
                          :class="scrolled ? 'text-teal-600 dark:text-teal-400' : 'text-teal-300/90'">Kota Banjarmasin</span>
                </div>
            </a>

            <!-- Desktop Navigation Links -->
            <div class="hidden md:flex items-center gap-8">
                <a href="#lowongan" class="text-sm font-bold tracking-wide transition-colors" :class="scrolled ? 'text-slate-600 dark:text-slate-300 hover:text-teal-600 dark:hover:text-teal-400' : 'text-white/90 hover:text-white'">Cari Lowongan</a>
                <a href="#langkah" class="text-sm font-bold tracking-wide transition-colors" :class="scrolled ? 'text-slate-600 dark:text-slate-300 hover:text-teal-600 dark:hover:text-teal-400' : 'text-white/90 hover:text-white'">Alur Magang</a>
                <a href="#faq" class="text-sm font-bold tracking-wide transition-colors" :class="scrolled ? 'text-slate-600 dark:text-slate-300 hover:text-teal-600 dark:hover:text-teal-400' : 'text-white/90 hover:text-white'">FAQ</a>
                <a href="{{url('/scan-qr') }}" class="text-sm font-bold tracking-wide transition-colors" :class="scrolled ? 'text-slate-600 dark:text-slate-300 hover:text-teal-600 dark:hover:text-teal-400' : 'text-white/90 hover:text-white'">Scan QR</a>

                <div class="h-5 w-[1px]" :class="scrolled ? 'bg-slate-200 dark:bg-gray-700' : 'bg-white/20'"></div>

                @if (Route::has('login'))
                    @auth
                        <div class="flex items-center gap-3">
                            <a href="{{ url('/dashboard') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-5 py-2.5 rounded-2xl font-bold text-xs sm:text-sm shadow-md transition-all">
                                <i class="fas fa-columns mr-2"></i>Dashboard Saya
                            </a>
                            <x-theme-toggle class="p-2.5 text-slate-400 hover:text-teal-600 dark:text-gray-400 dark:hover:text-white rounded-xl bg-slate-100 dark:bg-gray-800 border border-slate-200/50 dark:border-gray-700/50" />
                        </div>
                    @else
                        <div class="flex items-center gap-3">
                            <a href="{{ route('login') }}" class="px-4 py-2.5 text-xs sm:text-sm font-bold transition-all rounded-2xl" :class="scrolled ? 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-gray-800' : 'text-white hover:bg-white/10'">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-white text-teal-800 hover:bg-teal-50 px-5 py-2.5 rounded-2xl font-extrabold text-xs sm:text-sm shadow-sm transition-all border border-slate-100">Daftar Sekarang</a>
                            @endif
                            <x-theme-toggle class="p-2.5 text-slate-400 hover:text-teal-600 dark:text-gray-400 dark:hover:text-white rounded-xl bg-slate-100 dark:bg-gray-800 border border-slate-200/50 dark:border-gray-700/50" />
                        </div>
                    @endauth
                @endif
            </div>

            <!-- Mobile Drawer Button -->
            <div class="md:hidden flex items-center gap-2">
                <x-theme-toggle class="p-2 text-slate-400 hover:text-teal-600 dark:text-gray-400 dark:hover:text-white rounded-xl bg-white/10" />
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="p-2.5 rounded-xl transition focus:outline-none" :class="scrolled ? 'text-slate-800 dark:text-white' : 'text-white'">
                    <i class="fas" :class="mobileMenuOpen ? 'fa-times text-lg' : 'fa-bars text-lg'"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Drawer Side Overlay & Panel -->
    <div x-show="mobileMenuOpen" 
         x-cloak 
         class="fixed inset-0 z-50 md:hidden overflow-hidden" 
         role="dialog" 
         aria-modal="true">
        
        <!-- Backdrop Overlay -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition-opacity ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="mobileMenuOpen = false"
             class="fixed inset-0 bg-slate-950/70 backdrop-blur-md transition-opacity"></div>

        <!-- Slide-Down / Slide-Over Sheet -->
        <div class="fixed inset-x-0 top-0 max-h-[85vh] overflow-y-auto bg-white/95 dark:bg-gray-900/95 backdrop-blur-2xl border-b border-slate-200/80 dark:border-gray-800 shadow-2xl rounded-b-[2.5rem] z-10 flex flex-col pt-safe"
             x-show="mobileMenuOpen"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 -translate-y-8"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-8">
            
            <!-- Drawer Header -->
            <div class="px-6 py-5 flex items-center justify-between border-b border-slate-100 dark:border-gray-800">
                <div class="flex items-center gap-3">
                    <div class="bg-teal-50 dark:bg-teal-950/80 border border-teal-200 dark:border-teal-800/80 rounded-2xl p-2 flex items-center justify-center shrink-0 shadow-2xs">
                        <x-application-logo class="w-7 h-7 fill-current text-teal-600 dark:text-teal-400" />
                    </div>
                    <div>
                        <span class="text-base font-black text-slate-900 dark:text-white uppercase tracking-tight block font-display">SiMagang</span>
                        <span class="text-[9px] font-extrabold text-teal-600 dark:text-teal-400 uppercase tracking-widest block">Kota Banjarmasin</span>
                    </div>
                </div>
                <button @click="mobileMenuOpen = false" type="button" class="w-10 h-10 rounded-2xl bg-slate-100 dark:bg-gray-800 text-slate-500 dark:text-gray-400 hover:text-slate-800 dark:hover:text-white flex items-center justify-center transition active:scale-95" title="Tutup Menu">
                    <i class="fas fa-times text-base"></i>
                </button>
            </div>

            <!-- Drawer Links -->
            <div class="px-5 py-6 space-y-2 flex-grow">
                <a href="#lowongan" @click="mobileMenuOpen = false" class="flex items-center justify-between px-4 py-3.5 text-sm font-extrabold text-slate-800 dark:text-slate-100 bg-slate-50 dark:bg-gray-800/60 border border-slate-100 dark:border-gray-700/60 rounded-2xl hover:border-teal-400 hover:text-teal-600 dark:hover:text-teal-400 transition active:scale-[0.99]">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-teal-50 dark:bg-teal-950/80 text-teal-600 dark:text-teal-400 flex items-center justify-center">
                            <i class="fas fa-briefcase text-xs"></i>
                        </div>
                        <span>Cari Lowongan Magang</span>
                    </div>
                    <i class="fas fa-chevron-right text-xs text-slate-400"></i>
                </a>

                <a href="#langkah" @click="mobileMenuOpen = false" class="flex items-center justify-between px-4 py-3.5 text-sm font-extrabold text-slate-800 dark:text-slate-100 bg-slate-50 dark:bg-gray-800/60 border border-slate-100 dark:border-gray-700/60 rounded-2xl hover:border-teal-400 hover:text-teal-600 dark:hover:text-teal-400 transition active:scale-[0.99]">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-950/80 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                            <i class="fas fa-route text-xs"></i>
                        </div>
                        <span>Alur Pendaftaran</span>
                    </div>
                    <i class="fas fa-chevron-right text-xs text-slate-400"></i>
                </a>

                <a href="#faq" @click="mobileMenuOpen = false" class="flex items-center justify-between px-4 py-3.5 text-sm font-extrabold text-slate-800 dark:text-slate-100 bg-slate-50 dark:bg-gray-800/60 border border-slate-100 dark:border-gray-700/60 rounded-2xl hover:border-teal-400 hover:text-teal-600 dark:hover:text-teal-400 transition active:scale-[0.99]">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-sky-50 dark:bg-sky-950/80 text-sky-600 dark:text-sky-400 flex items-center justify-center">
                            <i class="fas fa-circle-question text-xs"></i>
                        </div>
                        <span>FAQ & Bantuan</span>
                    </div>
                    <i class="fas fa-chevron-right text-xs text-slate-400"></i>
                </a>

                <a href="{{ url('/scan-qr') }}" id="scan-qr-btn" @click="mobileMenuOpen = false" class="flex items-center justify-between px-4 py-3.5 text-sm font-extrabold text-slate-800 dark:text-slate-100 bg-slate-50 dark:bg-gray-800/60 border border-slate-100 dark:border-gray-700/60 rounded-2xl hover:border-teal-400 hover:text-teal-600 dark:hover:text-teal-400 transition active:scale-[0.99]">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-950/80 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                            <i class="fas fa-qrcode text-xs"></i>
                        </div>
                        <span>Scan QR Sertifikat</span>
                    </div>
                    <i class="fas fa-chevron-right text-xs text-slate-400"></i>
                </a>
            </div>

            <!-- Drawer Auth & Action Footer -->
            <div class="p-5 pt-2 pb-8 border-t border-slate-100 dark:border-gray-800 bg-slate-50/70 dark:bg-gray-900/90 rounded-b-[2.5rem] space-y-3">
                @auth
                    <a href="{{ url('/dashboard') }}" class="w-full py-4 bg-teal-600 hover:bg-teal-700 text-white rounded-2xl font-extrabold text-sm uppercase tracking-wider shadow-lg shadow-teal-600/25 flex items-center justify-center gap-2 active:scale-98 transition">
                        <i class="fas fa-columns text-sm"></i>
                        <span>Buka Dashboard Saya</span>
                    </a>
                @else
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('login') }}" class="py-3.5 bg-white dark:bg-gray-800 text-slate-800 dark:text-white border border-slate-200 dark:border-gray-700 rounded-2xl font-bold text-xs uppercase tracking-wider text-center flex items-center justify-center shadow-2xs active:scale-98 transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="py-3.5 bg-teal-600 text-white rounded-2xl font-extrabold text-xs uppercase tracking-wider text-center flex items-center justify-center shadow-md shadow-teal-600/20 active:scale-98 transition">
                            Daftar Akun
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>
