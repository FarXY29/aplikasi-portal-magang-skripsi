{{-- Global Announcement Banner --}}
@php
    $globalAnnouncement = $globalAnnouncement ?? \App\Models\Setting::where('key', 'announcement')->value('value');
@endphp
@if(!empty($globalAnnouncement))
    <div class="reveal bg-gradient-to-r from-amber-500/10 via-orange-500/10 to-transparent border border-amber-500/30 dark:border-amber-500/40 rounded-[2rem] p-5 sm:p-8 shadow-xs flex flex-col md:flex-row gap-5 items-start md:items-center justify-between mb-10 overflow-hidden relative w-full" style="--reveal-delay: 0ms" x-intersect.once="$el.classList.add('revealed')">
        <div class="absolute -right-6 -top-6 opacity-5 text-amber-600 pointer-events-none">
            <i class="fas fa-bullhorn text-9xl"></i>
        </div>
        <div class="flex gap-4 items-start">
            <div class="w-12 h-12 rounded-2xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-md shadow-amber-500/10">
                <i class="fas fa-bullhorn text-lg"></i>
            </div>
            <div>
                <span class="text-[9px] font-extrabold text-amber-800 dark:text-amber-300 bg-amber-500/20 px-2.5 py-1 rounded-lg border border-amber-500/30 uppercase tracking-widest">Pengumuman Penting</span>
                <div class="text-slate-800 dark:text-slate-200 font-bold text-sm sm:text-base mt-2 leading-relaxed">
                    {!! nl2br(e($globalAnnouncement)) !!}
                </div>
            </div>
        </div>
    </div>
@endif

{{-- Banner Penempatan Otomatis --}}
<div class="reveal bg-gradient-to-r from-teal-700 via-teal-800 to-emerald-800 dark:from-slate-800 dark:via-slate-900 dark:to-teal-950 rounded-[2.5rem] p-6 sm:p-10 text-white shadow-xl shadow-teal-900/10 dark:shadow-slate-950/40 mb-12 overflow-hidden relative border border-teal-600/30 dark:border-slate-700/60 w-full" style="--reveal-delay: 100ms" x-intersect.once="$el.classList.add('revealed')">
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
                    Gunakan fitur <strong>Penempatan Otomatis</strong>! Sistem cerdas kami akan mencocokkan latar belakang jurusan Anda dengan instansi yang saat ini kuotanya masih tersedia secara berimbang.
                </p>
            </div>
        </div>
        <a href="{{ route('peserta.apply_automatic.form') }}" class="shrink-0 bg-white dark:bg-gray-800 text-teal-950 dark:text-teal-200 hover:bg-teal-50 dark:hover:bg-gray-700 px-6 py-4 rounded-2xl font-extrabold shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5 active:scale-98 text-center text-xs uppercase tracking-wider flex items-center justify-center gap-2">
            <i class="fas fa-play text-xs"></i> Daftar Penempatan Otomatis
        </a>
    </div>
</div>
