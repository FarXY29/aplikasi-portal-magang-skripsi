<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <i class="fas fa-journal-whills text-teal-600 dark:text-teal-400"></i>
                {{ __('Detail Logbook Peserta') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans" 
         x-data="{ 
            activeLogId: {{ $logs->count() > 0 ? $logs->first()->id : 'null' }},
            mobileListOpen: true 
         }">
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Top Info Header --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.users.logbooks') }}" class="w-10 h-10 bg-white dark:bg-gray-800 rounded-full border border-gray-200 dark:border-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 hover:border-teal-300 shadow-sm transition">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-xl font-extrabold text-gray-900 dark:text-gray-100 leading-tight">{{ $user->name }}</h1>
                        <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center mt-0.5">
                            <i class="fas fa-building mr-1.5 text-gray-400 dark:text-gray-500"></i> 
                            {{ isset($app->position) ? $app->position->instansi->nama_dinas : 'Lokasi tidak ditemukan' }}
                        </p>
                    </div>
                </div>
                
                <div class="flex gap-2.5 text-xs font-bold">
                    <div class="px-3.5 py-1.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xs text-gray-600 dark:text-gray-300">
                        Total Log: <span class="text-teal-600 dark:text-teal-400 font-extrabold">{{ $logs->count() }}</span>
                    </div>
                    <div class="px-3.5 py-1.5 bg-green-50 dark:bg-green-950/60 border border-green-200 dark:border-green-800 rounded-xl shadow-xs text-green-700 dark:text-green-300">
                        Disetujui: <span class="font-extrabold">{{ $logs->where('status_validasi', 'disetujui')->count() }}</span>
                    </div>
                </div>
            </div>

            @if($logs->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-12 text-center border border-dashed border-gray-300 dark:border-gray-700 shadow-sm">
                    <div class="w-20 h-20 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300 dark:text-gray-600">
                        <i class="fas fa-book-open text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Logbook Kosong</h3>
                    <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm">Peserta ini belum mengisi catatan kegiatan harian.</p>
                </div>
            @else
                <div class="flex flex-col lg:flex-row gap-6 h-[calc(100vh-200px)]">
                    
                    {{-- Left Sidebar List --}}
                    <div class="lg:w-1/3 flex flex-col bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden h-full">
                        <div class="p-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex justify-between items-center">
                            <h3 class="font-bold text-gray-800 dark:text-gray-200 text-sm uppercase tracking-wide">Riwayat Kegiatan</h3>
                            <span class="text-[10px] text-gray-400 dark:text-gray-500 uppercase font-bold">Terbaru diatas</span>
                        </div>
                        
                        <div class="overflow-y-auto flex-1 custom-scrollbar p-3 space-y-2">
                            @foreach($logs as $log)
                                <button 
                                    @click="activeLogId = {{ $log->id }}"
                                    :class="activeLogId === {{ $log->id }} ? 'bg-teal-50 dark:bg-teal-950/60 border-teal-300 dark:border-teal-800 ring-1 ring-teal-300 dark:ring-teal-800' : 'bg-white dark:bg-gray-800 border-transparent hover:bg-gray-50 dark:hover:bg-gray-900/60'"
                                    class="w-full text-left p-3.5 rounded-2xl border transition-all duration-200 group relative focus:outline-none shadow-2xs">
                                    
                                    <div class="flex justify-between items-start mb-1">
                                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition"
                                              :class="{ 'text-teal-700 dark:text-teal-300 font-extrabold': activeLogId === {{ $log->id }} }">
                                            {{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('l, d M Y') }}
                                        </span>
                                        
                                        @php
                                            $statusColor = match($log->status_validasi) {
                                                'disetujui' => 'bg-green-500',
                                                'revisi' => 'bg-red-500',
                                                default => 'bg-yellow-400',
                                            };
                                        @endphp
                                        <span class="w-2.5 h-2.5 rounded-full {{ $statusColor }}" title="{{ ucfirst($log->status_validasi) }}"></span>
                                    </div>
                                    
                                    <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 line-clamp-1">
                                        {{ Str::limit($log->kegiatan, 40) }}
                                    </p>
                                    
                                    <div x-show="activeLogId === {{ $log->id }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-teal-500 dark:text-teal-400">
                                        <i class="fas fa-chevron-right text-xs"></i>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Right Main Detail Area --}}
                    <div class="lg:w-2/3 bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden h-full flex flex-col relative">
                        
                        <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-white dark:bg-gray-800 sticky top-0 z-10">
                            <h3 class="font-bold text-lg text-gray-800 dark:text-gray-200 flex items-center gap-2">
                                <i class="far fa-file-alt text-teal-500 dark:text-teal-400"></i> Detail Kegiatan Logbook
                            </h3>
                        </div>

                        <div class="overflow-y-auto p-6 flex-1 custom-scrollbar bg-gray-50/50 dark:bg-gray-900/30 space-y-6">
                            @foreach($logs as $log)
                                <div x-show="activeLogId === {{ $log->id }}" 
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0 translate-x-4"
                                     x-transition:enter-end="opacity-100 translate-x-0"
                                     class="space-y-6">
                                    
                                    {{-- Info Bar --}}
                                    <div class="flex items-center justify-between bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
                                        <div>
                                            <span class="text-[10px] text-gray-400 dark:text-gray-500 uppercase font-bold tracking-wider">Tanggal Kegiatan</span>
                                            <p class="text-base md:text-lg font-extrabold text-gray-900 dark:text-gray-100 mt-0.5">{{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('l, d F Y') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-[10px] text-gray-400 dark:text-gray-500 uppercase font-bold tracking-wider block mb-1">Status Validasi</span>
                                            <div>
                                                @if($log->status_validasi == 'disetujui')
                                                    <span class="px-3 py-1 bg-green-100 dark:bg-green-950/60 text-green-700 dark:text-green-300 rounded-full text-xs font-black border border-green-200 dark:border-green-800 inline-flex items-center gap-1">
                                                        <i class="fas fa-check-circle"></i> Disetujui
                                                    </span>
                                                @elseif($log->status_validasi == 'revisi')
                                                    <span class="px-3 py-1 bg-red-100 dark:bg-red-950/60 text-red-700 dark:text-red-300 rounded-full text-xs font-black border border-red-200 dark:border-red-800 inline-flex items-center gap-1">
                                                        <i class="fas fa-exclamation-circle"></i> Perlu Revisi
                                                    </span>
                                                @else
                                                    <span class="px-3 py-1 bg-yellow-100 dark:bg-yellow-950/60 text-yellow-700 dark:text-yellow-300 rounded-full text-xs font-black border border-yellow-200 dark:border-yellow-800 inline-flex items-center gap-1">
                                                        <i class="fas fa-clock"></i> Menunggu
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Aktivitas --}}
                                    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm space-y-3">
                                        <h4 class="text-xs font-extrabold text-gray-400 dark:text-gray-500 uppercase tracking-wider border-b border-gray-100 dark:border-gray-700 pb-2 flex items-center gap-2">
                                            <i class="fas fa-align-left text-teal-500 dark:text-teal-400"></i> Deskripsi Aktivitas
                                        </h4>
                                        <p class="text-gray-800 dark:text-gray-200 leading-relaxed whitespace-pre-line text-sm md:text-base font-normal">
                                            {{ $log->kegiatan }}
                                        </p>
                                    </div>

                                    {{-- Dokumen Bukti Foto --}}
                                    @if($log->bukti_foto_path)
                                    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm space-y-3">
                                        <h4 class="text-xs font-extrabold text-gray-400 dark:text-gray-500 uppercase tracking-wider border-b border-gray-100 dark:border-gray-700 pb-2 flex items-center gap-2">
                                            <i class="fas fa-camera text-teal-500 dark:text-teal-400"></i> Dokumentasi Kegiatan
                                        </h4>
                                        <div class="relative group w-full md:w-1/2 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 cursor-pointer shadow-sm" onclick="window.open('{{ route('storage.access', ['type' => 'logbook', 'filename' => basename($log->bukti_foto_path)]) }}', '_blank')">
                                            <img src="{{ route('storage.access', ['type' => 'logbook', 'filename' => basename($log->bukti_foto_path)]) }}" 
                                                 alt="Bukti Kegiatan" 
                                                 class="w-full h-auto object-cover transform transition duration-500 group-hover:scale-105">
                                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300 pointer-events-none">
                                                <span class="text-white text-xs font-bold"><i class="fas fa-expand mr-1.5"></i> Lihat Ukuran Penuh</span>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    {{-- Catatan Pembimbing Lapangan --}}
                                    @if($log->komentar_pembimbing_lapangan)
                                    <div class="bg-indigo-50/70 dark:bg-indigo-950/40 p-6 rounded-2xl border border-indigo-100 dark:border-indigo-900/50 shadow-xs relative">
                                        <div class="absolute top-0 left-0 w-1.5 h-full bg-indigo-500 dark:bg-indigo-400 rounded-l-2xl"></div>
                                        <h4 class="text-xs font-extrabold text-indigo-900 dark:text-indigo-300 uppercase tracking-wider mb-2 flex items-center gap-2">
                                            <i class="fas fa-comment-dots text-indigo-600 dark:text-indigo-400"></i> Catatan Pembimbing Lapangan
                                        </h4>
                                        <p class="text-indigo-800 dark:text-indigo-200 italic text-sm font-medium leading-relaxed">
                                            "{{ $log->komentar_pembimbing_lapangan }}"
                                        </p>
                                    </div>
                                    @endif

                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #475569; }
    </style>
</x-app-layout>