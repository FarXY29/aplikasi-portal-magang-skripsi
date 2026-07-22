<x-app-layout>
    @push('head')
        <meta name="turbo-cache-control" content="no-cache">
        @vite('resources/css/peserta.css')
        <style>
            .glass-effect {
                background: rgba(255, 255, 255, 0.85);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border-bottom: 1px solid rgba(243, 244, 246, 1);
            }
            .dark .glass-effect {
                background: rgba(31, 41, 55, 0.85);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border-bottom: 1px solid rgba(55, 65, 81, 0.5);
            }
        </style>
    @endpush

    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-950/60 flex items-center justify-center border border-teal-200 dark:border-teal-800/60">
                    <i class="fas fa-book-open text-teal-600 dark:text-teal-400 text-lg"></i>
                </div>
                {{ __('Logbook Harian') }}
            </h2>
        </div>
    </x-slot>

    <div x-data="logbookData()" class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="mb-6 print:hidden">
                <a href="{{ route('peserta.dashboard') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-xs">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Dashboard
                </a>
            </div>

            @if(session('success'))
                <x-ui.alert type="success" class="mb-4">
                    {{ session('success') }}
                </x-ui.alert>
            @endif

            @if(session('error'))
                <x-ui.alert type="error" class="mb-4">
                    {{ session('error') }}
                </x-ui.alert>
            @endif

            <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
                
                {{-- Left Column: Form Tulis Jurnal Baru --}}
                <div class="xl:col-span-4">
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden sticky top-8">
                        <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-teal-50 to-emerald-50 dark:from-teal-950/40 dark:to-emerald-950/40">
                            <h3 class="font-extrabold text-gray-800 dark:text-gray-100 flex items-center gap-2 text-base">
                                <i class="fas fa-pen-nib text-teal-600 dark:text-teal-400"></i> Tulis Jurnal Baru
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-medium">Catat aktivitas magang Anda hari ini.</p>
                        </div>
                        
                        <div class="p-6">
                            <form action="{{ route('peserta.logbook.store') }}" method="POST" enctype="multipart/form-data" id="logbookForm">
                                @csrf
                                <input type="hidden" name="latitude" id="lat">
                                <input type="hidden" name="longitude" id="lng">

                                <div class="mb-5">
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Deskripsi Kegiatan <span class="text-rose-500">*</span></label>
                                    <textarea name="kegiatan" rows="5" class="w-full rounded-2xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 text-xs sm:text-sm shadow-xs transition hover:shadow-md resize-none font-medium" placeholder="Apa saja kegiatan yang Anda kerjakan hari ini?" required></textarea>
                                </div>

                                <div class="mb-5">
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Dokumentasi (Foto)</label>
                                    <div class="relative w-full h-40 rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 hover:bg-teal-50 dark:hover:bg-teal-950/20 hover:border-teal-400 dark:hover:border-teal-500 transition-all group overflow-hidden flex flex-col items-center justify-center cursor-pointer">
                                        <!-- Image Preview Container -->
                                        <div id="image-preview" class="absolute inset-0 z-10 hidden bg-black">
                                            <img id="preview-img" src="" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition-opacity" />
                                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                <span class="text-white text-xs font-bold bg-black/60 px-3 py-1 rounded-full"><i class="fas fa-camera mr-1"></i> Ganti Foto</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Default Empty State -->
                                        <div id="empty-state" class="flex flex-col items-center justify-center z-0">
                                            <div class="w-12 h-12 bg-white dark:bg-gray-800 rounded-2xl shadow-xs flex items-center justify-center mb-2 border border-gray-200 dark:border-gray-700 group-hover:scale-110 transition-transform">
                                                <i class="fas fa-cloud-upload-alt text-teal-600 dark:text-teal-400 text-xl"></i>
                                            </div>
                                            <p class="text-xs font-bold text-gray-600 dark:text-gray-300">Klik untuk upload foto</p>
                                            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1 font-semibold">PNG, JPG, JPEG maks 5MB</p>
                                        </div>
                                        
                                        <input id="foto" name="foto" type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" accept="image/*" onchange="previewImage(this)" />
                                    </div>
                                    <button type="button" id="remove-btn" onclick="removeImage()" class="hidden mt-2 text-xs text-rose-600 dark:text-rose-400 hover:underline font-bold items-center gap-1">
                                        <i class="fas fa-trash"></i> Hapus Foto
                                    </button>
                                </div>

                                <div class="mb-6 bg-gray-50 dark:bg-gray-900 p-4 rounded-2xl border border-gray-200 dark:border-gray-700 relative overflow-hidden">
                                    <div class="absolute top-0 right-0 -mt-2 -mr-2 text-gray-200 dark:text-gray-800 opacity-40">
                                        <i class="fas fa-map-marked-alt text-6xl"></i>
                                    </div>
                                    <p class="text-[10px] font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2 relative z-10">Verifikasi Lokasi GPS</p>
                                    <div id="loc-status" class="flex items-center gap-2 text-xs font-bold text-amber-600 dark:text-amber-400 relative z-10">
                                        <i class="fas fa-circle-notch fa-spin"></i> Mendapatkan koordinat...
                                    </div>
                                    <div id="coords-display" class="text-[10px] text-gray-500 dark:text-gray-400 mt-1 hidden font-mono relative z-10 bg-white dark:bg-gray-800 inline-block px-2.5 py-1 rounded-md border border-gray-200 dark:border-gray-700">
                                        Lat: <span id="show-lat"></span>, Lng: <span id="show-lng"></span>
                                    </div>
                                </div>

                                <button type="submit" id="btn-submit" disabled 
                                    class="w-full bg-teal-600 hover:bg-teal-700 text-white py-3.5 rounded-2xl font-bold shadow-md transition active:scale-95 disabled:opacity-50 disabled:transform-none disabled:cursor-not-allowed flex items-center justify-center gap-2 text-xs uppercase tracking-wider">
                                    <i class="fas fa-paper-plane"></i> Simpan Laporan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Timeline & Cards --}}
                <div class="xl:col-span-8">
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden min-h-[500px] flex flex-col xl:h-[120vh]">
                        
                        <!-- Header & Filters -->
                        <div class="p-6 flex flex-col justify-between gap-4 sticky top-0 z-10 glass-effect">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <h3 class="font-extrabold text-gray-800 dark:text-gray-100 text-base sm:text-lg">Riwayat Jurnal</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 font-medium">Catatan harian aktivitas magang Anda.</p>
                                </div>
                                
                                <!-- Status Filters -->
                                <div class="flex flex-wrap gap-2">
                                    <button @click="filter = 'semua'" :class="filter === 'semua' ? 'bg-gray-800 dark:bg-gray-100 text-white dark:text-gray-900 shadow-xs' : 'bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700/50'" class="filter-pill px-3.5 py-1.5 rounded-full text-xs font-bold transition flex items-center gap-1.5">
                                        Semua <span class="bg-white/20 dark:bg-gray-800/40 px-1.5 py-0.5 rounded-full text-[10px]">{{ $logs->count() }}</span>
                                    </button>
                                    <button @click="filter = 'pending'" :class="filter === 'pending' ? 'bg-amber-500 text-white shadow-xs' : 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 hover:bg-amber-100 border border-amber-200 dark:border-amber-800/60'" class="filter-pill px-3.5 py-1.5 rounded-full text-xs font-bold transition">
                                        Pending
                                    </button>
                                    <button @click="filter = 'disetujui'" :class="filter === 'disetujui' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 hover:bg-emerald-100 border border-emerald-200 dark:border-emerald-800/60'" class="filter-pill px-3.5 py-1.5 rounded-full text-xs font-bold transition">
                                        Disetujui
                                    </button>
                                    <button @click="filter = 'revisi'" :class="filter === 'revisi' ? 'bg-rose-600 text-white shadow-xs' : 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 hover:bg-rose-100 border border-rose-200 dark:border-rose-800/60'" class="filter-pill px-3.5 py-1.5 rounded-full text-xs font-bold transition">
                                        Revisi
                                    </button>
                                </div>
                                
                            </div>
                            
                            <!-- Advanced Filters -->
                            <div class="flex flex-wrap gap-3 items-end bg-gray-50 dark:bg-gray-900 p-3 rounded-2xl border border-gray-100 dark:border-gray-700 mt-2">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5"><i class="far fa-calendar-alt mr-1"></i> Spesifik Tanggal</label>
                                    <input type="date" x-model="filterTanggal" class="text-xs font-bold text-gray-800 dark:text-gray-100 rounded-xl border-gray-300 dark:border-gray-700 shadow-xs focus:ring-teal-500 focus:border-teal-500 py-2 px-3 bg-white dark:bg-gray-800 w-full sm:w-auto cursor-pointer [color-scheme:dark]">
                                </div>
                                <div class="hidden sm:block text-gray-300 dark:text-gray-700 font-light mb-2">|</div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5"><i class="far fa-calendar-check mr-1"></i> Filter Bulan</label>
                                    <input type="month" x-model="filterBulan" class="text-xs font-bold text-gray-800 dark:text-gray-100 rounded-xl border-gray-300 dark:border-gray-700 shadow-xs focus:ring-teal-500 focus:border-teal-500 py-2 px-3 bg-white dark:bg-gray-800 w-full sm:w-auto cursor-pointer [color-scheme:dark]">
                                </div>
                                <div class="ml-auto flex-1 sm:flex-none flex justify-end gap-2">
                                    <button @click="resetFilters()" x-show="filter !== 'semua' || filterTanggal !== '' || filterBulan !== ''" x-transition class="text-xs font-bold text-rose-700 dark:text-rose-300 bg-rose-50 dark:bg-rose-950/60 hover:bg-rose-100 border border-rose-200 dark:border-rose-800/60 px-4 py-2 rounded-xl transition flex items-center justify-center gap-1.5 w-full sm:w-auto shadow-xs">
                                        <i class="fas fa-times-circle"></i> Reset Filter
                                    </button>
                                    <a href="{{ route('peserta.logbook.print') }}" target="_blank" class="px-4 py-2 bg-gray-800 hover:bg-gray-900 dark:bg-gray-700 dark:hover:bg-gray-600 text-white rounded-xl text-xs font-bold transition shadow-xs flex items-center gap-2">
                                        <i class="fas fa-print"></i> Cetak Rekap
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Card Grid -->
                        <div class="p-6 bg-gray-50/50 dark:bg-gray-900/50 flex-1 overflow-y-auto">
                            @if($logs->isEmpty())
                                <div class="flex flex-col items-center justify-center h-full text-gray-400 dark:text-gray-500 py-16">
                                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-3 border border-gray-200 dark:border-gray-700">
                                        <i class="far fa-clipboard text-3xl text-gray-400 dark:text-gray-500"></i>
                                    </div>
                                    <p class="font-bold text-gray-700 dark:text-gray-300 text-lg">Belum Ada Jurnal</p>
                                    <p class="text-xs mt-1 text-center max-w-sm font-medium text-gray-500 dark:text-gray-400">Mulai tulis aktivitas pertama Anda menggunakan formulir di sebelah kiri.</p>
                                </div>
                            @else
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    @foreach($logs as $log)
                                        @php
                                            $badges = [
                                                'pending' => ['bg' => 'bg-amber-50 dark:bg-amber-950/60', 'text' => 'text-amber-700 dark:text-amber-300', 'icon' => 'fa-clock', 'border' => 'border-amber-200 dark:border-amber-800/60'],
                                                'disetujui' => ['bg' => 'bg-emerald-50 dark:bg-emerald-950/60', 'text' => 'text-emerald-700 dark:text-emerald-300', 'icon' => 'fa-check-circle', 'border' => 'border-emerald-200 dark:border-emerald-800/60'],
                                                'revisi' => ['bg' => 'bg-rose-50 dark:bg-rose-950/60', 'text' => 'text-rose-700 dark:text-rose-300', 'icon' => 'fa-exclamation-circle', 'border' => 'border-rose-200 dark:border-rose-800/60'],
                                            ];
                                            $status = $badges[$log->status_validasi] ?? $badges['pending'];
                                        @endphp
                                        
                                        <div x-show="matchFilter('{{ $log->status_validasi }}', '{{ \Carbon\Carbon::parse($log->tanggal)->format('Y-m-d') }}')" 
                                             x-transition.opacity.duration.300ms
                                             x-data="{ showRevisiModal: false }"
                                             class="logbook-card status-{{ $log->status_validasi }} bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xs hover:shadow-md transition duration-300 overflow-hidden flex flex-col relative group">
                                            
                                            <!-- Status Ribbon -->
                                            <div class="absolute top-4 right-4 z-10">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider shadow-xs {{ $status['bg'] }} {{ $status['text'] }} {{ $status['border'] }} border">
                                                    <i class="fas {{ $status['icon'] }} mr-1.5"></i> {{ $log->status_validasi }}
                                                </span>
                                            </div>

                                            <!-- Image Header -->
                                            @if($log->bukti_foto_path)
                                                <div class="h-48 w-full bg-gray-100 dark:bg-gray-900 relative cursor-pointer overflow-hidden" @click="openGallery('{{ route('storage.access', ['type' => 'logbook', 'filename' => basename($log->bukti_foto_path)]) }}')">
                                                    <img src="{{ route('storage.access', ['type' => 'logbook', 'filename' => basename($log->bukti_foto_path)]) }}" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition flex items-end p-4">
                                                        <span class="text-white text-xs font-bold bg-black/60 backdrop-blur-sm px-3 py-1.5 rounded-full"><i class="fas fa-expand-alt mr-1.5"></i> Perbesar Foto</span>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="h-16 w-full bg-gray-50 dark:bg-gray-900 flex items-center justify-center border-b border-gray-100 dark:border-gray-700">
                                                    <span class="text-gray-400 dark:text-gray-500 text-xs font-bold uppercase tracking-widest"><i class="fas fa-image mr-1"></i> Tanpa Foto</span>
                                                </div>
                                            @endif

                                            <!-- Content -->
                                            <div class="p-6 flex-1 flex flex-col">
                                                <div class="flex items-center gap-3 mb-4">
                                                    <div class="w-10 h-10 rounded-2xl bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center border border-teal-200 dark:border-teal-800/60 font-bold text-xs shrink-0">
                                                        <i class="fas fa-calendar-alt text-sm"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider">{{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('l') }}</p>
                                                        <p class="text-[11px] font-medium text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('d M Y') }}</p>
                                                    </div>
                                                </div>
                                                
                                                <p class="text-xs sm:text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line flex-1 font-medium">{{ $log->kegiatan }}</p>
                                                
                                                @if($log->komentar_pembimbing_lapangan)
                                                    <div class="mt-5 bg-rose-50/60 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 rounded-2xl p-4 relative overflow-hidden">
                                                        <div class="absolute top-0 left-0 w-1.5 h-full bg-rose-500"></div>
                                                        <div class="flex gap-3">
                                                            <div class="mt-0.5">
                                                                <i class="fas fa-comment-dots text-rose-600 dark:text-rose-400 text-lg"></i>
                                                            </div>
                                                            <div>
                                                                <span class="block text-[10px] font-bold text-rose-800 dark:text-rose-300 uppercase tracking-wider mb-1">Catatan Pembimbing Lapangan</span>
                                                                <p class="text-xs font-medium text-rose-900 dark:text-rose-200 leading-relaxed">{{ $log->komentar_pembimbing_lapangan }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- Revisi Button & Modal -->
                                                @if($log->status_validasi === 'revisi')
                                                    <div class="mt-5 pt-5 border-t border-gray-100 dark:border-gray-700">
                                                        <button @click="showRevisiModal = true" type="button" class="w-full inline-flex items-center justify-center px-4 py-3 bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60 rounded-2xl text-xs font-bold hover:bg-rose-600 hover:text-white transition shadow-xs">
                                                            <i class="fas fa-edit mr-2"></i> Perbaiki Jurnal Ini
                                                        </button>
                                                    </div>

                                                    <!-- Modal Revisi -->
                                                    <div x-show="showRevisiModal" class="fixed inset-0 z-[100] overflow-y-auto text-left" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                            <div x-show="showRevisiModal" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showRevisiModal = false" aria-hidden="true"></div>
                                                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                                            <div x-show="showRevisiModal" x-transition class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-gray-200 dark:border-gray-700">
                                                                <form action="{{ route('peserta.logbook.update', $log->id) }}" method="POST" enctype="multipart/form-data">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="bg-white dark:bg-gray-800 px-6 pt-6 pb-6">
                                                                        <div class="flex items-center gap-4 mb-6 pb-5 border-b border-gray-100 dark:border-gray-700">
                                                                            <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-2xl bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800/60">
                                                                                <i class="fas fa-edit text-lg"></i>
                                                                            </div>
                                                                            <div>
                                                                                <h3 class="text-base font-bold text-gray-900 dark:text-gray-100" id="modal-title">
                                                                                    Revisi Jurnal Harian
                                                                                </h3>
                                                                                <p class="text-xs text-gray-500 dark:text-gray-400 font-bold mt-0.5">{{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('d F Y') }}</p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="space-y-5">
                                                                            <div>
                                                                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Deskripsi Kegiatan <span class="text-rose-500">*</span></label>
                                                                                <textarea name="kegiatan" rows="4" class="w-full rounded-2xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 text-xs sm:text-sm font-medium shadow-xs resize-none" required>{{ $log->kegiatan }}</textarea>
                                                                            </div>
                                                                            <div>
                                                                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Ganti Foto (Opsional)</label>
                                                                                <input type="file" name="foto" accept="image/*" class="w-full text-xs text-gray-500 dark:text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-teal-50 dark:file:bg-teal-950/60 file:text-teal-700 dark:file:text-teal-300 hover:file:bg-teal-100 border border-gray-300 dark:border-gray-700 rounded-xl p-1 bg-white dark:bg-gray-900 cursor-pointer">
                                                                                <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-2 font-medium">Abaikan jika tidak ada perubahan bukti dokumentasi.</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="bg-gray-50 dark:bg-gray-900 px-6 py-4 flex flex-row-reverse gap-3 border-t border-gray-100 dark:border-gray-700">
                                                                        <button type="submit" class="w-full inline-flex justify-center rounded-xl shadow-xs px-6 py-2.5 bg-teal-600 text-xs font-bold uppercase tracking-wider text-white hover:bg-teal-700 transition sm:w-auto">
                                                                            Simpan Revisi
                                                                        </button>
                                                                        <button type="button" @click="showRevisiModal = false" class="w-full inline-flex justify-center rounded-xl shadow-xs px-6 py-2.5 bg-white dark:bg-gray-800 text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900 border border-gray-300 dark:border-gray-700 transition sm:w-auto">
                                                                            Batal
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-6">
                                    {{ $logs->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
        
        <!-- Image Gallery Modal -->
        <div x-show="galleryOpen" class="fixed inset-0 z-[200] overflow-y-auto" style="display: none;" aria-labelledby="gallery-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div x-show="galleryOpen" x-transition.opacity.duration.300ms class="fixed inset-0 bg-gray-900/90 backdrop-blur-md transition-opacity" @click="galleryOpen = false" aria-hidden="true"></div>
                <div x-show="galleryOpen" x-transition.scale.duration.300ms class="relative z-10 w-full max-w-5xl mx-auto rounded-3xl overflow-hidden shadow-2xl">
                    <button @click="galleryOpen = false" class="absolute top-4 right-4 z-20 w-12 h-12 bg-black/50 hover:bg-rose-600 text-white rounded-full flex items-center justify-center transition backdrop-blur-sm border border-white/20">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                    <img :src="galleryImage" class="w-full max-h-[90vh] object-contain bg-black/50" />
                </div>
            </div>
        </div>
    </div>

    <script>
    function previewImage(input) {
        const preview = document.getElementById('preview-img');
        const previewContainer = document.getElementById('image-preview');
        const emptyState = document.getElementById('empty-state');
        const removeBtn = document.getElementById('remove-btn');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.classList.remove('hidden');
                emptyState.classList.add('hidden');
                removeBtn.classList.remove('hidden');
                removeBtn.classList.add('flex');
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            removeImage();
        }
    }

    function removeImage() {
        document.getElementById('foto').value = "";
        document.getElementById('preview-img').src = "";
        document.getElementById('image-preview').classList.add('hidden');
        document.getElementById('empty-state').classList.remove('hidden');
        const removeBtn = document.getElementById('remove-btn');
        removeBtn.classList.add('hidden');
        removeBtn.classList.remove('flex');
    }

    document.addEventListener('alpine:init', () => {
        Alpine.data('logbookData', () => ({
            filter: 'semua',
            filterTanggal: '',
            filterBulan: '',
            galleryOpen: false,
            galleryImage: '',
            openGallery(imgUrl) {
                this.galleryImage = imgUrl;
                this.galleryOpen = true;
            },
            matchFilter(status, dateStr) {
                if (this.filter !== 'semua' && this.filter !== status) return false;
                if (this.filterTanggal && dateStr !== this.filterTanggal) return false;
                if (this.filterBulan && !dateStr.startsWith(this.filterBulan)) return false;
                return true;
            },
            resetFilters() {
                this.filter = 'semua';
                this.filterTanggal = '';
                this.filterBulan = '';
            }
        }))
    });

    document.addEventListener("turbo:load", function() {
        const statusDiv = document.getElementById("loc-status");
        const btnSubmit = document.getElementById("btn-submit");
        const coordsDisplay = document.getElementById("coords-display");
        const showLat = document.getElementById("show-lat");
        const showLng = document.getElementById("show-lng");

        if (!statusDiv || !btnSubmit) return;

        function requestLocation() {
            if (navigator.geolocation) {
                const options = {
                    enableHighAccuracy: false, 
                    timeout: 30000,            
                    maximumAge: 300000         
                };
                navigator.geolocation.getCurrentPosition(successLocation, errorLocation, options);
            } else {
                showErrorMsg("Browser tidak mendukung Geolocation.");
            }
        }

        function successLocation(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            document.getElementById("lat").value = lat;
            document.getElementById("lng").value = lng;

            statusDiv.innerHTML = '<span class="text-emerald-600 dark:text-emerald-400 flex items-center font-bold"><i class="fas fa-check-circle mr-1.5 text-base"></i> Lokasi Terkunci</span>';
            showLat.innerText = lat.toFixed(6);
            showLng.innerText = lng.toFixed(6);
            coordsDisplay.classList.remove("hidden");
            
            btnSubmit.disabled = false;
        }

        function errorLocation(error) {
            let msg = "Gagal mengambil lokasi.";
            let retryBtn = '';

            switch(error.code) {
                case error.PERMISSION_DENIED:
                    msg = "Izin lokasi ditolak. Buka pengaturan browser."; 
                    break;
                case error.POSITION_UNAVAILABLE:
                    msg = "Sinyal lokasi tidak ditemukan. Pastikan GPS aktif."; 
                    break;
                case error.TIMEOUT:
                    msg = "Waktu habis. Sinyal lemah.";
                    retryBtn = `<button type="button" onclick="window.location.reload()" class="ml-2 underline text-teal-600 dark:text-teal-400">Coba Lagi</button>`;
                    break;
            }
            showErrorMsg(msg, retryBtn);
        }

        function showErrorMsg(msg, extraHtml = '') {
            statusDiv.innerHTML = `<div class="flex flex-col"><span class="text-rose-600 dark:text-rose-400 flex items-center font-bold text-xs"><i class="fas fa-exclamation-triangle mr-1.5"></i> ${msg}</span>${extraHtml}</div>`;
            btnSubmit.disabled = true;
        }

        requestLocation();
    });
    </script>
</x-app-layout>
