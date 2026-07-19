    <!-- MOBILE BOTTOM NAVIGATION BAR (Android & iOS Phones < md) -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white dark:bg-gray-800/95 backdrop-blur-xl border-t border-gray-200 dark:border-gray-700/80 shadow-[0_-4px_25px_rgba(0,0,0,0.06)] px-2 py-1.5 flex items-center justify-around pb-safe">
        
        <!-- 1. BERANDA (Semua Role) -->
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all {{ request()->routeIs('dashboard') ? 'text-teal-600 font-black scale-105' : 'text-gray-400 font-semibold hover:text-gray-600 dark:hover:text-gray-400' }}">
            <div class="relative flex items-center justify-center w-8 h-8 {{ request()->routeIs('dashboard') ? 'bg-teal-50 rounded-xl text-teal-600 shadow-2xs' : '' }}">
                <i class="fas fa-home text-base"></i>
            </div>
            <span class="text-[10px] mt-0.5 tracking-tight font-bold">Beranda</span>
        </a>

        @if(Auth::user()->role == 'peserta')
            <!-- 2. LOGBOOK (Peserta) -->
            <a href="{{ route('peserta.logbook.index') }}" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all {{ request()->routeIs('peserta.logbook.*') ? 'text-teal-600 font-black scale-105' : 'text-gray-400 font-semibold hover:text-gray-600 dark:hover:text-gray-400' }}">
                <div class="relative flex items-center justify-center w-8 h-8 {{ request()->routeIs('peserta.logbook.*') ? 'bg-teal-50 rounded-xl text-teal-600 shadow-2xs' : '' }}">
                    <i class="fas fa-book-open text-base"></i>
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight font-bold">Logbook</span>
            </a>

            <!-- 3. ABSENSI (Peserta) -->
            <a href="{{ route('peserta.absensi.index') }}" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all {{ request()->routeIs('peserta.absensi.*') ? 'text-teal-600 font-black scale-105' : 'text-gray-400 font-semibold hover:text-gray-600 dark:hover:text-gray-400' }}">
                <div class="relative flex items-center justify-center w-8 h-8 {{ request()->routeIs('peserta.absensi.*') ? 'bg-teal-50 rounded-xl text-teal-600 shadow-2xs' : '' }}">
                    <i class="fas fa-user-clock text-base"></i>
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight font-bold">Absensi</span>
            </a>

            <!-- 4. SERTIFIKAT (Peserta) -->
            <a href="{{ route('peserta.sertifikat') }}" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all {{ request()->routeIs('peserta.sertifikat') ? 'text-teal-600 font-black scale-105' : 'text-gray-400 font-semibold hover:text-gray-600 dark:hover:text-gray-400' }}">
                <div class="relative flex items-center justify-center w-8 h-8 {{ request()->routeIs('peserta.sertifikat') ? 'bg-teal-50 rounded-xl text-teal-600 shadow-2xs' : '' }}">
                    <i class="fas fa-award text-base"></i>
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight font-bold">Sertifikat</span>
            </a>
        @elseif(Auth::user()->role == 'admin_instansi')
            <!-- 2. LOWONGAN (Admin Instansi) -->
            <a href="{{ route('dinas.lowongan.index') }}" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all {{ request()->routeIs('dinas.lowongan.*') ? 'text-teal-600 font-black scale-105' : 'text-gray-400 font-semibold hover:text-gray-600 dark:hover:text-gray-400' }}">
                <div class="relative flex items-center justify-center w-8 h-8 {{ request()->routeIs('dinas.lowongan.*') ? 'bg-teal-50 rounded-xl text-teal-600 shadow-2xs' : '' }}">
                    <i class="fas fa-briefcase text-base"></i>
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight font-bold">Lowongan</span>
            </a>

            <!-- 3. PELAMAR (Admin Instansi) -->
            <a href="{{ route('dinas.pelamar') }}" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all {{ request()->routeIs('dinas.pelamar') ? 'text-teal-600 font-black scale-105' : 'text-gray-400 font-semibold hover:text-gray-600 dark:hover:text-gray-400' }}">
                <div class="relative flex items-center justify-center w-8 h-8 {{ request()->routeIs('dinas.pelamar') ? 'bg-teal-50 rounded-xl text-teal-600 shadow-2xs' : '' }}">
                    <i class="fas fa-envelope-open-text text-base"></i>
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight font-bold">Pelamar</span>
            </a>

            <!-- 4. PESERTA (Admin Instansi) -->
            <a href="{{ route('dinas.peserta.index') }}" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all {{ request()->routeIs('dinas.peserta.*') ? 'text-teal-600 font-black scale-105' : 'text-gray-400 font-semibold hover:text-gray-600 dark:hover:text-gray-400' }}">
                <div class="relative flex items-center justify-center w-8 h-8 {{ request()->routeIs('dinas.peserta.*') ? 'bg-teal-50 rounded-xl text-teal-600 shadow-2xs' : '' }}">
                    <i class="fas fa-user-check text-base"></i>
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight font-bold">Peserta</span>
            </a>
        @elseif(Auth::user()->role == 'admin_kota')
            <!-- 2. INSTANSI (Admin Kota) -->
            <a href="{{ route('admin.instansi.index') }}" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all {{ request()->routeIs('admin.instansi.*') ? 'text-teal-600 font-black scale-105' : 'text-gray-400 font-semibold hover:text-gray-600 dark:hover:text-gray-400' }}">
                <div class="relative flex items-center justify-center w-8 h-8 {{ request()->routeIs('admin.instansi.*') ? 'bg-teal-50 rounded-xl text-teal-600 shadow-2xs' : '' }}">
                    <i class="fas fa-building text-base"></i>
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight font-bold">Instansi</span>
            </a>

            <!-- 3. PENGGUNA (Admin Kota) -->
            <a href="{{ route('admin.users.index') }}" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all {{ request()->routeIs('admin.users.*') ? 'text-teal-600 font-black scale-105' : 'text-gray-400 font-semibold hover:text-gray-600 dark:hover:text-gray-400' }}">
                <div class="relative flex items-center justify-center w-8 h-8 {{ request()->routeIs('admin.users.*') ? 'bg-teal-50 rounded-xl text-teal-600 shadow-2xs' : '' }}">
                    <i class="fas fa-users-cog text-base"></i>
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight font-bold">Pengguna</span>
            </a>

            <!-- 4. LAPORAN (Admin Kota) -->
            <a href="{{ route('admin.laporan.hub') }}" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all {{ request()->routeIs('admin.laporan.*') ? 'text-teal-600 font-black scale-105' : 'text-gray-400 font-semibold hover:text-gray-600 dark:hover:text-gray-400' }}">
                <div class="relative flex items-center justify-center w-8 h-8 {{ request()->routeIs('admin.laporan.*') ? 'bg-teal-50 rounded-xl text-teal-600 shadow-2xs' : '' }}">
                    <i class="fas fa-chart-pie text-base"></i>
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight font-bold">Laporan</span>
            </a>
        @elseif(Auth::user()->role == 'pembimbing_lapangan')
            <!-- 2. ABSENSI (Pembimbing Lapangan) -->
            <a href="{{ route('pembimbing_lapangan.attendance.index') }}" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all {{ request()->routeIs('pembimbing_lapangan.attendance.*') ? 'text-teal-600 font-black scale-105' : 'text-gray-400 font-semibold hover:text-gray-600 dark:hover:text-gray-400' }}">
                <div class="relative flex items-center justify-center w-8 h-8 {{ request()->routeIs('pembimbing_lapangan.attendance.*') ? 'bg-teal-50 rounded-xl text-teal-600 shadow-2xs' : '' }}">
                    <i class="fas fa-clipboard-list text-base"></i>
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight font-bold">Absensi</span>
            </a>

            <!-- 3. PROFIL -->
            <a href="{{ route('profile.edit') }}" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all {{ request()->routeIs('profile.*') ? 'text-teal-600 font-black scale-105' : 'text-gray-400 font-semibold hover:text-gray-600 dark:hover:text-gray-400' }}">
                <div class="relative flex items-center justify-center w-8 h-8 {{ request()->routeIs('profile.*') ? 'bg-teal-50 rounded-xl text-teal-600 shadow-2xs' : '' }}">
                    <i class="fas fa-user-circle text-base"></i>
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight font-bold">Profil</span>
            </a>
        @elseif(Auth::user()->role == 'pembimbing')
            <!-- 2. PROFIL -->
            <a href="{{ route('profile.edit') }}" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all {{ request()->routeIs('profile.*') ? 'text-teal-600 font-black scale-105' : 'text-gray-400 font-semibold hover:text-gray-600 dark:hover:text-gray-400' }}">
                <div class="relative flex items-center justify-center w-8 h-8 {{ request()->routeIs('profile.*') ? 'bg-teal-50 rounded-xl text-teal-600 shadow-2xs' : '' }}">
                    <i class="fas fa-user-circle text-base"></i>
                </div>
                <span class="text-[10px] mt-0.5 tracking-tight font-bold">Profil</span>
            </a>
        @endif

        <!-- MENU LAINNYA / PROFIL & QUICK SHEETS (Semua Role) -->
        <button @click="$dispatch('open-mobile-menu')" class="flex flex-col items-center justify-center w-16 py-1 rounded-2xl transition-all text-gray-400 font-semibold hover:text-gray-600 dark:hover:text-gray-400 focus:outline-none">
            <div class="relative flex items-center justify-center w-8 h-8">
                <i class="fas fa-grid-horizontal text-base"></i>
            </div>
            <span class="text-[10px] mt-0.5 tracking-tight font-bold">Menu</span>
        </button>
    </nav>
