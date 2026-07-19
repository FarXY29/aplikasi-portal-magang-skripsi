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
             class="fixed bottom-0 left-0 right-0 z-50 bg-white dark:bg-gray-800 rounded-t-3xl shadow-2xl max-h-[85vh] flex flex-col overflow-hidden"
             x-cloak>
             
            <!-- Grabber Bar -->
            <div class="w-full py-3 flex justify-center cursor-pointer bg-gray-50 dark:bg-gray-900/80 border-b border-gray-100 dark:border-gray-700" @click="mobileMenuOpen = false">
                <div class="w-12 h-1.5 bg-gray-300 rounded-full"></div>
            </div>

            <!-- Header Info User -->
            <div class="p-5 bg-gradient-to-r from-teal-700 via-teal-600 to-teal-700 text-white flex items-center justify-between">
                <div class="flex items-center gap-3.5 min-w-0">
                    <div class="w-12 h-12 rounded-xl bg-white dark:bg-gray-800 text-teal-700 font-black text-lg flex items-center justify-center shadow-md flex-shrink-0">
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
                <button @click="mobileMenuOpen = false" class="w-8 h-8 rounded-full bg-white dark:bg-gray-800/10 hover:bg-white dark:hover:bg-gray-800/20 flex items-center justify-center text-white transition flex-shrink-0">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- List Menu Navigasi Lengkap -->
            <div class="p-4 overflow-y-auto custom-scrollbar flex-1 space-y-2">
                <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest px-2 mb-1">Navigasi Aplikasi</p>
                
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('dashboard') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900' }}">
                    <i class="fas fa-th-large w-5 text-center {{ request()->routeIs('dashboard') ? 'text-teal-600' : 'text-gray-400' }}"></i> Beranda / Dashboard
                </a>

                @if(Auth::user()->role == 'peserta')
                    <a href="{{ route('peserta.logbook.index') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('peserta.logbook.*') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900' }}">
                        <i class="fas fa-book-open w-5 text-center {{ request()->routeIs('peserta.logbook.*') ? 'text-teal-600' : 'text-gray-400' }}"></i> Logbook Harian
                    </a>
                    <a href="{{ route('peserta.absensi.index') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('peserta.absensi.*') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900' }}">
                        <i class="fas fa-user-clock w-5 text-center {{ request()->routeIs('peserta.absensi.*') ? 'text-teal-600' : 'text-gray-400' }}"></i> Absensi Kehadiran
                    </a>
                    <a href="{{ route('peserta.sertifikat') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('peserta.sertifikat') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900' }}">
                        <i class="fas fa-award w-5 text-center {{ request()->routeIs('peserta.sertifikat') ? 'text-teal-600' : 'text-gray-400' }}"></i> Sertifikat Magang
                    </a>
                @elseif(Auth::user()->role == 'admin_kota')
                    <a href="{{ route('admin.instansi.index') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('admin.instansi.*') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900' }}">
                        <i class="fas fa-building w-5 text-center {{ request()->routeIs('admin.instansi.*') ? 'text-teal-600' : 'text-gray-400' }}"></i> Kelola Data Instansi
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('admin.users.index') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900' }}">
                        <i class="fas fa-users-cog w-5 text-center {{ request()->routeIs('admin.users.index') ? 'text-teal-600' : 'text-gray-400' }}"></i> Manajemen Pengguna
                    </a>
                    <a href="{{ route('admin.laporan.hub') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('admin.laporan.*') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900' }}">
                        <i class="fas fa-chart-pie w-5 text-center {{ request()->routeIs('admin.laporan.*') ? 'text-teal-600' : 'text-gray-400' }}"></i> Pusat Laporan Hub
                    </a>
                    <a href="{{ route('admin.users.logbooks') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('admin.users.logbooks*') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900' }}">
                        <i class="fas fa-book w-5 text-center {{ request()->routeIs('admin.users.logbooks*') ? 'text-teal-600' : 'text-gray-400' }}"></i> Monitoring Logbook
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('admin.settings.*') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900' }}">
                        <i class="fas fa-cogs w-5 text-center {{ request()->routeIs('admin.settings.*') ? 'text-teal-600' : 'text-gray-400' }}"></i> Pengaturan Sistem
                    </a>
                @elseif(Auth::user()->role == 'admin_instansi')
                    <a href="{{ route('dinas.lowongan.index') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('dinas.lowongan.*') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900' }}">
                        <i class="fas fa-briefcase w-5 text-center {{ request()->routeIs('dinas.lowongan.*') ? 'text-teal-600' : 'text-gray-400' }}"></i> Kelola Lowongan
                    </a>
                    <a href="{{ route('dinas.pelamar') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('dinas.pelamar') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900' }}">
                        <i class="fas fa-envelope-open-text w-5 text-center {{ request()->routeIs('dinas.pelamar') ? 'text-teal-600' : 'text-gray-400' }}"></i> Pelamar Masuk
                    </a>
                    <a href="{{ route('dinas.peserta.index') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('dinas.peserta.index') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900' }}">
                        <i class="fas fa-user-check w-5 text-center {{ request()->routeIs('dinas.peserta.index') ? 'text-teal-600' : 'text-gray-400' }}"></i> Monitoring Peserta
                    </a>
                    <a href="{{ route('dinas.pembimbing_lapangan.index') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('dinas.pembimbing_lapangan.*') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900' }}">
                        <i class="fas fa-chalkboard-teacher w-5 text-center {{ request()->routeIs('dinas.pembimbing_lapangan.*') ? 'text-teal-600' : 'text-gray-400' }}"></i> Data Pembimbing
                    </a>
                    <a href="{{ route('dinas.laporan.hub') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('dinas.laporan.hub') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900' }}">
                        <i class="fas fa-chart-pie w-5 text-center {{ request()->routeIs('dinas.laporan.hub') ? 'text-teal-600' : 'text-gray-400' }}"></i> Pusat Laporan Hub
                    </a>
                    <a href="{{ route('dinas.settings') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('dinas.settings') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900' }}">
                        <i class="fas fa-sliders-h w-5 text-center {{ request()->routeIs('dinas.settings') ? 'text-teal-600' : 'text-gray-400' }}"></i> Pengaturan Geofencing
                    </a>
                @elseif(Auth::user()->role == 'pembimbing_lapangan')
                    <a href="{{ route('pembimbing_lapangan.attendance.index') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('pembimbing_lapangan.attendance.*') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900' }}">
                        <i class="fas fa-clipboard-list w-5 text-center {{ request()->routeIs('pembimbing_lapangan.attendance.*') ? 'text-teal-600' : 'text-gray-400' }}"></i> Absensi Peserta
                    </a>
                @elseif(Auth::user()->role == 'pembimbing')
                    <a href="{{ route('pembimbing.dashboard') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('pembimbing.dashboard') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900' }}">
                        <i class="fas fa-user-graduate w-5 text-center {{ request()->routeIs('pembimbing.dashboard') ? 'text-teal-600' : 'text-gray-400' }}"></i> Daftar Mahasiswa
                    </a>
                @endif

                <div class="border-t border-gray-100 dark:border-gray-700 my-3 pt-3">
                    <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest px-2 mb-1.5">Akun & Sistem</p>
                    
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold {{ request()->routeIs('profile.*') ? 'bg-teal-50 text-teal-700 border border-teal-200 shadow-xs' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900' }}">
                        <i class="fas fa-user-circle w-5 text-center {{ request()->routeIs('profile.*') ? 'text-teal-600' : 'text-gray-400' }}"></i> Pengaturan Akun (Profile)
                    </a>

                    <a href="{{ route('home') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900 dark:hover:bg-gray-700">
                        <i class="fas fa-globe w-5 text-center text-gray-400"></i> Beranda Publik
                    </a>

                    <div class="flex items-center justify-between px-4 py-2 mt-1 rounded-2xl text-sm font-bold text-gray-700 dark:text-gray-300">
                        <div class="flex items-center gap-3.5">
                            <i class="fas fa-palette w-5 text-center text-gray-400"></i> Mode Gelap
                        </div>
                        <x-theme-toggle class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:text-gray-100 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white transition-all shadow-xs border border-gray-200 dark:border-gray-700 dark:border-gray-600" />
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="w-full mt-2">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-sm font-black text-red-600 bg-red-50 hover:bg-red-600 hover:text-white dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-600 dark:hover:text-white transition-all duration-200 text-left shadow-xs border border-red-100 dark:border-red-900/50">
                            <i class="fas fa-sign-out-alt w-5 text-center"></i> Keluar dari Aplikasi (Logout)
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="p-3 bg-gray-50 dark:bg-gray-900 border-t border-gray-100 dark:border-gray-700 text-center">
                <p class="text-[10px] text-gray-400 font-extrabold tracking-wider">Portal Magang v2.0 &bull; Pemkot Banjarmasin</p>
            </div>
        </div>
    </div>
