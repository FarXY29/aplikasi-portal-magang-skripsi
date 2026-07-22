<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-950/60 flex items-center justify-center border border-teal-200 dark:border-teal-800/60">
                    <i class="fas fa-book-open text-teal-600 dark:text-teal-400 text-lg"></i>
                </div>
                {{ __('Pemantauan Logbook Mahasiswa') }}
            </h2>
            <div class="flex items-center gap-3">
                <span class="text-xs font-bold text-gray-500 dark:text-gray-400">Mahasiswa:</span>
                <span class="px-3.5 py-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full text-xs font-bold text-gray-800 dark:text-gray-200 shadow-xs">
                    {{ $app->user->name }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex justify-between items-center mb-6">
                <a href="{{ route('pembimbing.dashboard') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-xs">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Dashboard
                </a>
            </div>

            {{-- Filter Bar --}}
            <div class="bg-white dark:bg-gray-800 p-5 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex items-center gap-2">
                    <i class="fas fa-filter text-teal-600 dark:text-teal-400"></i>
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Filter Logbook</span>
                </div>
                
                <form action="{{ route('pembimbing.peserta.logbook', $app->id) }}" method="GET" class="w-full md:w-auto flex flex-wrap items-center gap-4">
                    <div class="bg-gray-100 dark:bg-gray-900 p-1 rounded-xl flex items-center border border-gray-200 dark:border-gray-700">
                        <label class="cursor-pointer">
                            <input type="radio" name="filter_type" value="semua" {{ $filterType === 'semua' ? 'checked' : '' }} class="sr-only peer" onchange="this.form.submit()">
                            <span class="px-3 py-1.5 text-xs font-bold rounded-lg text-gray-500 dark:text-gray-400 peer-checked:bg-white dark:peer-checked:bg-gray-800 peer-checked:text-teal-600 dark:peer-checked:text-teal-400 peer-checked:shadow-xs transition block">
                                Semua
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="filter_type" value="mingguan" {{ $filterType === 'mingguan' ? 'checked' : '' }} class="sr-only peer" onchange="this.form.submit()">
                            <span class="px-3 py-1.5 text-xs font-bold rounded-lg text-gray-500 dark:text-gray-400 peer-checked:bg-white dark:peer-checked:bg-gray-800 peer-checked:text-teal-600 dark:peer-checked:text-teal-400 peer-checked:shadow-xs transition block">
                                Mingguan
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="filter_type" value="bulanan" {{ $filterType === 'bulanan' ? 'checked' : '' }} class="sr-only peer" onchange="this.form.submit()">
                            <span class="px-3 py-1.5 text-xs font-bold rounded-lg text-gray-500 dark:text-gray-400 peer-checked:bg-white dark:peer-checked:bg-gray-800 peer-checked:text-teal-600 dark:peer-checked:text-teal-400 peer-checked:shadow-xs transition block">
                                Bulanan
                            </span>
                        </label>
                    </div>

                    @if($filterType !== 'semua')
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-gray-500 dark:text-gray-400">
                                {{ $filterType === 'bulanan' ? 'Bulan:' : 'Tanggal:' }}
                            </span>
                            @if($filterType === 'bulanan')
                                <input type="month" name="month" value="{{ \Carbon\Carbon::parse($selectedDate)->format('Y-m') }}" 
                                    class="border border-gray-300 dark:border-gray-700 rounded-xl text-xs font-bold shadow-xs focus:border-teal-500 focus:ring-teal-500 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 transition py-1.5 px-3 [color-scheme:dark]"
                                    onchange="this.form.date.value = this.value + '-01'; this.form.submit();">
                                <input type="hidden" name="date" value="{{ $selectedDate }}">
                            @else
                                <input type="date" name="date" value="{{ $selectedDate }}" onchange="this.form.submit()" 
                                    class="border border-gray-300 dark:border-gray-700 rounded-xl text-xs font-bold shadow-xs focus:border-teal-500 focus:ring-teal-500 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 transition py-1.5 px-3 [color-scheme:dark]">
                            @endif
                        </div>
                    @endif

                    @if(request('filter_type') && request('filter_type') != 'semua')
                        <a href="{{ route('pembimbing.peserta.logbook', $app->id) }}" class="p-2 bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 hover:bg-rose-100 border border-rose-200 dark:border-rose-800/60 rounded-xl transition text-xs font-bold flex items-center gap-1.5">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    @endif
                </form>
            </div>

            @if($logs->isEmpty())
                <div class="flex flex-col items-center justify-center py-16 bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-3 border border-gray-200 dark:border-gray-700">
                        <i class="fas fa-book-open text-3xl text-gray-400 dark:text-gray-500"></i>
                    </div>
                    <h3 class="text-base font-bold text-gray-800 dark:text-gray-200">Logbook Kosong</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Belum ada aktivitas logbook pada periode yang dipilih.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start" x-data="{ activeTab: {{ $logs->first()->id }} }">
                    
                    {{-- Sidebar Log List --}}
                    <div class="md:col-span-4 col-span-1">
                        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden sticky top-8">
                            <div class="p-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex justify-between items-center">
                                <h3 class="font-bold text-gray-800 dark:text-gray-200 text-xs uppercase tracking-wider flex items-center gap-2">
                                    <i class="fas fa-list-ul text-teal-600 dark:text-teal-400"></i> Riwayat Aktivitas
                                </h3>
                                <span class="text-[10px] font-black bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-2 py-0.5 rounded-full">{{ $logs->count() }}</span>
                            </div>
                            
                            <div class="max-h-[70vh] overflow-y-auto custom-scrollbar">
                                <ul class="divide-y divide-gray-100 dark:divide-gray-700/60">
                                    @foreach($logs as $log)
                                    <li>
                                        <button @click="activeTab = {{ $log->id }}"
                                            :class="{ 'bg-teal-50/70 dark:bg-teal-950/40 border-l-4 border-teal-500 dark:border-teal-400': activeTab === {{ $log->id }}, 'border-l-4 border-transparent hover:bg-gray-50 dark:hover:bg-gray-900/60': activeTab !== {{ $log->id }} }"
                                            class="w-full text-left px-4 py-3 transition duration-150 ease-in-out focus:outline-none group">
                                            
                                            <div class="flex justify-between items-start mb-1">
                                                <span class="text-xs font-bold text-gray-800 dark:text-gray-200" :class="{ 'text-teal-700 dark:text-teal-300': activeTab === {{ $log->id }} }">
                                                    {{ \Carbon\Carbon::parse($log->tanggal)->format('d M Y') }}
                                                </span>
                                                @if($log->status_validasi == 'disetujui')
                                                    <i class="fas fa-check-circle text-emerald-500 text-xs" title="Disetujui"></i>
                                                @elseif($log->status_validasi == 'revisi')
                                                    <i class="fas fa-exclamation-circle text-rose-500 text-xs" title="Revisi"></i>
                                                @else
                                                    <div class="w-2.5 h-2.5 rounded-full bg-amber-400 mt-1" title="Pending"></div>
                                                @endif
                                            </div>
                                            
                                            <p class="text-[11px] text-gray-500 dark:text-gray-400 truncate group-hover:text-gray-700 dark:group-hover:text-gray-300">
                                                {{ Str::limit($log->kegiatan, 40) }}
                                            </p>
                                        </button>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Detail Pane --}}
                    <div class="md:col-span-8 col-span-1">
                        @foreach($logs as $log)
                        <div x-show="activeTab === {{ $log->id }}" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             style="display: none;">
                            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
                                
                                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                    <div>
                                        <h3 class="text-xl font-black text-gray-800 dark:text-gray-100">Detail Kegiatan Jurnal</h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center font-bold">
                                            <i class="far fa-calendar-alt mr-1.5 text-teal-600 dark:text-teal-400"></i> 
                                            {{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('l, d F Y') }}
                                        </p>
                                    </div>
                                    
                                    @php
                                        $statusClass = match($log->status_validasi) {
                                            'disetujui' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800/60',
                                            'revisi' => 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800/60',
                                            default => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800/60'
                                        };
                                        $statusIcon = match($log->status_validasi) {
                                            'disetujui' => 'fa-check-circle',
                                            'revisi' => 'fa-undo',
                                            default => 'fa-clock'
                                        };
                                    @endphp
                                    <span class="px-3.5 py-1 rounded-full text-xs font-bold uppercase border {{ $statusClass }} flex items-center gap-1.5">
                                        <i class="fas {{ $statusIcon }}"></i> {{ ucfirst($log->status_validasi) }}
                                    </span>
                                </div>

                                <div class="p-6 sm:p-8 space-y-6">
                                    <div class="flex flex-col lg:flex-row gap-6">
                                        <div class="w-full lg:w-1/3 flex-shrink-0">
                                            <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Dokumentasi</h4>
                                            @if($log->bukti_foto_path)
                                                <div class="relative group rounded-2xl overflow-hidden shadow-xs border border-gray-200 dark:border-gray-700 cursor-zoom-in">
                                                    <img src="{{ route('storage.access', ['type' => 'logbook', 'filename' => basename($log->bukti_foto_path)]) }}" class="w-full h-48 object-cover transition transform group-hover:scale-105 duration-500">
                                                    <a href="{{ route('storage.access', ['type' => 'logbook', 'filename' => basename($log->bukti_foto_path)]) }}" target="_blank" class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition flex items-center justify-center">
                                                        <i class="fas fa-search-plus text-white text-xl opacity-0 group-hover:opacity-100 transition duration-200 drop-shadow"></i>
                                                    </a>
                                                </div>
                                            @else
                                                <div class="w-full h-44 bg-gray-50 dark:bg-gray-900 rounded-2xl flex flex-col items-center justify-center text-gray-400 dark:text-gray-500 text-xs border-2 border-dashed border-gray-200 dark:border-gray-700">
                                                    <i class="far fa-image text-3xl mb-2 text-gray-300 dark:text-gray-600"></i>
                                                    <span class="font-bold">Tidak ada foto bukti</span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="w-full lg:w-2/3">
                                            <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Deskripsi Pekerjaan</h4>
                                            <div class="p-5 bg-gray-50 dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-200 text-xs sm:text-sm leading-relaxed whitespace-pre-line min-h-[11rem]">
                                                {{ $log->kegiatan }}
                                            </div>
                                        </div>
                                    </div>

                                    @if($log->komentar_pembimbing_lapangan)
                                        <div class="pt-6 border-t border-gray-100 dark:border-gray-700">
                                            <div class="p-4 bg-blue-50/60 dark:bg-blue-950/40 rounded-2xl border border-blue-200 dark:border-blue-800/60 flex gap-3 items-start">
                                                <i class="fas fa-comment-dots text-blue-600 dark:text-blue-400 mt-0.5 text-base flex-shrink-0"></i>
                                                <div>
                                                    <span class="block text-xs font-bold text-blue-800 dark:text-blue-300 uppercase mb-1">Catatan Pembimbing Lapangan:</span>
                                                    <p class="text-xs sm:text-sm text-blue-900 dark:text-blue-200 italic">"{{ $log->komentar_pembimbing_lapangan }}"</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #475569; }
    </style>
</x-app-layout>
