{{-- Feed: Aktivitas Pendaftaran --}}
<div class="bg-white dark:bg-gray-800 rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="p-4 md:p-5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-white dark:bg-gray-800">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-indigo-600 dark:bg-indigo-500 text-white flex items-center justify-center shadow-sm">
                <i class="fas fa-broadcast-tower text-sm"></i>
            </div>
            <div>
                <h3 class="text-sm md:text-base font-black text-gray-800 dark:text-gray-200">Aktivitas Pendaftaran</h3>
                <p class="text-[10px] md:text-xs text-gray-500 dark:text-gray-400 font-medium mt-0.5">Feed pelamar magang terbaru</p>
            </div>
        </div>
        <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-green-50 dark:bg-green-950/60 border border-green-100 dark:border-green-800">
            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
            <span class="text-[10px] font-black text-green-700 dark:text-green-300">Live</span>
        </div>
    </div>
    
    <div class="divide-y divide-gray-100 dark:divide-gray-700/60">
        @forelse($recentApplications as $app)
        <div class="p-4 md:p-5 flex items-center justify-between feed-item hover:bg-gray-50 dark:hover:bg-gray-900/60 gap-3 md:gap-4 transition">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-300 flex items-center justify-center font-black text-sm shrink-0 border border-indigo-100 dark:border-indigo-900/40">
                    {{ strtoupper(substr($app->user->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <p class="text-sm font-bold text-gray-900 dark:text-gray-100 truncate">{{ $app->user->name }}</p>
                        <span class="text-[10px] text-gray-400 dark:text-gray-500 font-medium truncate hidden sm:inline">({{ $app->user->asal_instansi }})</span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">
                        <i class="fas fa-arrow-right text-[10px] text-gray-300 dark:text-gray-600 mr-1"></i> 
                        <span class="font-bold text-gray-700 dark:text-gray-300">{{ $app->position->instansi->nama_dinas }}</span> 
                        <span class="hidden sm:inline">&bull; <span class="italic text-gray-500 dark:text-gray-400">{{ $app->position->judul_posisi }}</span></span>
                    </p>
                </div>
            </div>
            <div class="text-right shrink-0">
                <x-ui.badge :status="$app->status" />
                <span class="block text-[10px] text-gray-400 dark:text-gray-500 mt-1 font-medium">
                    <i class="far fa-clock text-[9px]"></i> {{ $app->created_at->diffForHumans() }}
                </span>
            </div>
        </div>
        @empty
        <div class="p-10 text-center">
            <div class="w-14 h-14 rounded-2xl bg-gray-100 dark:bg-gray-900 flex items-center justify-center mx-auto mb-3 border border-gray-200 dark:border-gray-700">
                <i class="fas fa-inbox text-2xl text-gray-400 dark:text-gray-500"></i>
            </div>
            <p class="text-sm font-bold text-gray-800 dark:text-gray-200">Belum ada aktivitas lamaran</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Data akan muncul saat ada pendaftaran baru.</p>
        </div>
        @endforelse
    </div>
</div>
