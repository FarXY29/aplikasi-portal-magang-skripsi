<!-- Hero Section -->
<section class="bg-sasirangan-premium text-white pt-32 pb-24 sm:pt-40 sm:pb-32 relative overflow-hidden w-full animate-glow-slow">
    <!-- Ambient Blurred Background Ornaments -->
    <div class="absolute -top-12 -right-12 w-[350px] sm:w-[500px] h-[350px] sm:h-[500px] bg-teal-500 opacity-20 rounded-full blur-[100px] sm:blur-[140px] pointer-events-none animate-pulse"></div>
    <div class="absolute -bottom-16 -left-16 w-[300px] sm:w-[450px] h-[300px] sm:h-[450px] bg-emerald-500 opacity-15 rounded-full blur-[100px] sm:blur-[140px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 w-full flex flex-col items-center">
        <!-- Top Tag Badge -->
        <span class="inline-flex items-center gap-2 py-2 px-4 rounded-full bg-teal-950/60 border border-teal-500/35 backdrop-blur-md text-teal-200 text-xs font-bold tracking-wider uppercase mb-6 sm:mb-8 shadow-sm">
            <span class="w-2 h-2 rounded-full bg-teal-400 animate-ping"></span>
            Portal Resmi Magang Kota Banjarmasin
        </span>
        
        <h1 class="text-3xl sm:text-5xl md:text-6xl font-black mb-4 sm:mb-6 leading-[1.2] sm:leading-[1.1] tracking-tight">
            Mulai Karir Profesionalmu <br class="hidden sm:inline"/> Bersama <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-200 via-emerald-200 to-white">Pemkot Banjarmasin</span>
        </h1>

        <!-- Subtitle Description -->
        <p class="text-sm sm:text-base md:text-lg text-teal-100/80 mb-10 max-w-2xl font-medium leading-relaxed px-4 sm:px-0">
            Temukan lowongan magang terbaik di berbagai Instansi Pemerintah Kota Banjarmasin secara transparan, mudah, dan terintegrasi.
        </p>
        
        <!-- Dynamic Global Search Dock -->
        <div class="w-full max-w-2xl px-2 sm:px-0">
            <form action="{{ route('home') }}#lowongan" method="GET" class="relative w-full" id="search-form" onsubmit="event.preventDefault(); let params = new URLSearchParams(new FormData(this)); for (let [k, v] of Array.from(params.entries())) { if (!v) params.delete(k); } window.location.href = '{{ route('home') }}?' + params.toString() + '#lowongan';">
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center p-2 bg-white dark:bg-slate-800/95 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/10 gap-2 sm:gap-0 focus-within:ring-2 focus-within:ring-teal-500 w-full transition-all duration-300">
                    <div class="flex items-center flex-grow pl-4 text-slate-400">
                        <i class="fas fa-search text-base text-teal-600 shrink-0"></i>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            class="w-full py-3.5 px-3 text-slate-800 dark:text-slate-100 bg-transparent text-sm sm:text-base font-semibold placeholder-slate-400 focus:outline-none border-none ring-0 focus:ring-0" 
                            placeholder="Cari posisi magang atau instansi...">
                    </div>
                    <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white font-extrabold py-3.5 px-8 rounded-2xl transition-all duration-300 shadow-md active:scale-[0.98] text-sm flex items-center justify-center gap-2 shrink-0">
                        <span>Cari Posisi</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Elegant Bottom Wave Divider -->
    <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none pointer-events-none">
        <svg class="relative block w-full h-[30px] sm:h-[60px] md:h-[80px]" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" class="fill-slate-50/50 dark:fill-slate-900 transition-colors duration-300"></path>
        </svg>
    </div>
</section>
