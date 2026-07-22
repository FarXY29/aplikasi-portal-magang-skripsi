<!-- Hero Section -->
<section class="bg-sasirangan-premium text-white pt-28 pb-20 sm:pt-36 sm:pb-28 md:pt-40 md:pb-32 relative overflow-hidden w-full">
    <!-- Ambient Blurred Background Ornaments -->
    <div class="absolute -top-12 -right-12 w-[350px] sm:w-[500px] h-[350px] sm:h-[500px] bg-teal-500 opacity-20 rounded-full blur-[90px] sm:blur-[120px] animate-ambient-1 pointer-events-none"></div>
    <div class="absolute -bottom-16 -left-16 w-[300px] sm:w-[450px] h-[300px] sm:h-[450px] bg-emerald-500 opacity-15 rounded-full blur-[90px] sm:blur-[120px] animate-ambient-2 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 w-full flex flex-col items-center">
        <!-- Top Tag Badge -->
        <span class="inline-flex items-center gap-2 py-2 px-4 rounded-full bg-teal-900/60 border border-teal-500/35 backdrop-blur-md text-teal-200 text-xs font-extrabold tracking-wider uppercase mb-6 sm:mb-8 shadow-xs">
            <span class="w-2.5 h-2.5 rounded-full bg-teal-400 animate-pulse"></span>
            Portal Resmi Magang Kota Banjarmasin
        </span>

        <!-- Main Heading -->
        <h1 class="text-3xl sm:text-5xl md:text-6xl font-black mb-4 sm:mb-6 leading-[1.2] sm:leading-[1.15] drop-shadow-xs tracking-tight font-display">
            Bangun Karir & Pengalaman Mulia <br class="hidden sm:inline"/> Bersama <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-200 via-emerald-100 to-white">Pemerintah Kota Banjarmasin</span>
        </h1>

        <!-- Subtitle Description -->
        <p class="text-sm sm:text-lg md:text-xl text-teal-100/90 mb-10 max-w-2xl font-medium leading-relaxed px-4 sm:px-0">
            Temukan lowongan magang terbaik di berbagai Instansi Pemerintah Kota Banjarmasin secara transparan, mudah, dan terintegrasi.
        </p>

        <!-- Dynamic Global Search Dock -->
        <div class="w-full max-w-2xl px-2 sm:px-0">
            <form action="{{ route('home') }}#lowongan" method="GET" class="relative w-full" id="search-form">
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center p-2.5 bg-white/95 dark:bg-gray-800/95 backdrop-blur-xl rounded-[2rem] shadow-2xl border border-white/20 dark:border-gray-700/60 gap-2 sm:gap-0 transform transition-transform duration-300 focus-within:scale-[1.01] w-full">
                    <div class="flex items-center flex-grow pl-4 sm:pl-5 text-slate-400 dark:text-gray-400">
                        <i class="fas fa-search text-lg text-teal-600 dark:text-teal-400 shrink-0"></i>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            class="w-full py-3.5 px-4 text-slate-800 dark:text-gray-100 bg-transparent text-sm sm:text-base font-semibold placeholder-slate-400 dark:placeholder-gray-400 focus:outline-none border-none ring-0 focus:ring-0" 
                            placeholder="Cari posisi magang atau nama instansi...">
                    </div>
                    <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white font-extrabold py-3.5 px-8 rounded-full transition-all duration-300 shadow-md shadow-teal-700/20 hover:shadow-lg active:scale-98 text-xs uppercase tracking-wider flex items-center justify-center gap-2 shrink-0">
                        <span>Cari Posisi</span>
                        <i class="fas fa-arrow-right text-xs"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bottom Divider -->
    <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none pointer-events-none">
        <svg class="relative block w-full h-[30px] sm:h-[60px] md:h-[80px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" class="fill-slate-50 dark:fill-gray-900 transition-colors duration-300"></path>
        </svg>
    </div>
</section>
