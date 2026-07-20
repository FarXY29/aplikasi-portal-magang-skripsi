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
                border-bottom: 1px solid rgba(55, 65, 81, 0.5);
            }
        </style>
    @endpush

    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-black text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <i class="fas fa-book-open text-teal-600"></i>
                {{ __('Logbook Harian') }}
            </h2>
        </div>
    </x-slot>

    <div x-data="logbookData()" class="py-8 bg-gray-50 dark:bg-gray-900/50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="mb-6 print:hidden">
                <a href="{{ route('peserta.dashboard') }}" class="group inline-flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i>
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

            @if($errors->any())
    <x-ui.alert type="error" class="mb-4">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </x-ui.alert>
@endif

            {{-- Premium Statistics Grid --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8 print:hidden">
                <div class="stat-summary-card stagger-1 flex items-center gap-4 hover:border-teal-500 transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-teal-50 dark:bg-teal-950/30 text-teal-600 dark:text-teal-400 flex items-center justify-center flex-shrink-0 shadow-inner">
                        <i class="fas fa-book text-xl"></i>
                    </div>
                    <div>
                        <div class="stat-number text-gray-800 dark:text-gray-200">{{ $activeApp->logs()->count() }}</div>
                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Jurnal</div>
                    </div>
                </div>

                <div class="stat-summary-card stagger-2 flex items-center gap-4 hover:border-green-500 transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-green-50 dark:bg-green-950/30 text-green-600 dark:text-green-400 flex items-center justify-center flex-shrink-0 shadow-inner">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                    <div>
                        <div class="stat-number text-green-600 dark:text-green-400">{{ $activeApp->logs()->where('status_validasi', 'disetujui')->count() }}</div>
                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Disetujui</div>
                    </div>
                </div>

                <div class="stat-summary-card stagger-3 flex items-center gap-4 hover:border-yellow-500 transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-yellow-50 dark:bg-yellow-950/30 text-yellow-600 dark:text-yellow-400 flex items-center justify-center flex-shrink-0 shadow-inner">
                        <i class="fas fa-clock text-xl"></i>
                    </div>
                    <div>
                        <div class="stat-number text-yellow-600 dark:text-yellow-400">{{ $activeApp->logs()->where('status_validasi', 'pending')->count() }}</div>
                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Pending</div>
                    </div>
                </div>

                <div class="stat-summary-card stagger-4 flex items-center gap-4 hover:border-red-500 transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 flex items-center justify-center flex-shrink-0 shadow-inner">
                        <i class="fas fa-exclamation-circle text-xl"></i>
                    </div>
                    <div>
                        <div class="stat-number text-red-600 dark:text-red-400">{{ $activeApp->logs()->where('status_validasi', 'revisi')->count() }}</div>
                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Perlu Revisi</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
                
                <!-- Left Column: Form -->
                <div class="xl:col-span-4">
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden sticky top-8">
                        <div class="p-6 border-b border-gray-50 dark:border-gray-700 bg-gradient-to-r from-teal-50 to-emerald-50 dark:from-teal-950/20 dark:to-emerald-950/20">
                            <h3 class="font-extrabold text-gray-800 dark:text-gray-200 flex items-center gap-2 text-lg">
                                <i class="fas fa-pen-nib text-teal-600"></i> Tulis Jurnal Baru
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-medium">Catat aktivitas magang Anda hari ini.</p>
                        </div>
                        
                        <div class="p-6">
                            <form action="{{ route('peserta.logbook.store') }}" method="POST" enctype="multipart/form-data" id="logbookForm">
                                @csrf
                                <input type="hidden" name="latitude" id="lat">
                                <input type="hidden" name="longitude" id="lng">

                                <div class="mb-5">
                                    <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-2">Deskripsi Kegiatan</label>
                                    <textarea name="kegiatan" rows="5" class="w-full rounded-2xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 text-sm shadow-sm transition-shadow hover:shadow-md resize-none" placeholder="Apa yang Anda kerjakan hari ini?" required maxlength="2000"></textarea>
                                    <div class="flex justify-between items-center mt-1.5 px-1">
                                        <span class="text-[10px] text-gray-400 dark:text-gray-500 font-bold">Maksimal 2000 karakter</span>
                                        <span id="char-counter" class="text-[10px] text-gray-400 dark:text-gray-500 font-bold">0 / 2000</span>
                                    </div>
                                </div>

                                <div class="mb-5">
                                    <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-2">Dokumentasi (Foto)</label>
                                    <div class="upload-zone relative w-full h-40 rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 hover:bg-teal-50 dark:hover:bg-teal-950/20 hover:border-teal-400 transition-all group overflow-hidden flex flex-col items-center justify-center cursor-pointer">
                                        <!-- Image Preview Container -->
                                        <div id="image-preview" class="absolute inset-0 z-10 hidden bg-black">
                                            <img id="preview-img" src="" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition-opacity" />
                                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                <span class="text-white text-xs font-bold bg-black/50 px-3 py-1 rounded-full"><i class="fas fa-camera mr-1"></i> Ganti Foto</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Default Empty State -->
                                        <div id="empty-state" class="flex flex-col items-center justify-center z-0">
                                            <div class="upload-icon w-12 h-12 bg-white dark:bg-gray-800 rounded-full shadow-sm flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                                <i class="fas fa-cloud-upload-alt text-teal-500 text-xl"></i>
                                            </div>
                                            <p class="text-xs font-bold text-gray-600 dark:text-gray-400">Klik untuk upload foto</p>
                                            <p class="text-[10px] text-gray-400 mt-1 font-bold">PNG, JPG, JPEG up to 5MB</p>
                                        </div>
                                        
                                        <input id="foto" name="foto" type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" accept="image/*" onchange="previewImage(this)" />
                                    </div>
                                    <button type="button" id="remove-btn" onclick="removeImage()" class="hidden mt-2 text-xs text-red-500 hover:text-red-700 font-bold items-center gap-1">
                                        <i class="fas fa-trash"></i> Hapus Foto
                                    </button>
                                </div>

                                <div class="mb-6 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800/40 dark:to-gray-900/40 p-4 rounded-2xl border border-gray-200 dark:border-gray-700 relative overflow-hidden">
                                    <div class="absolute top-0 right-0 -mt-2 -mr-2 text-gray-200 opacity-50 dark:opacity-10">
                                        <i class="fas fa-map-marked-alt text-6xl"></i>
                                    </div>
                                    <p class="text-[10px] font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2 relative z-10">Verifikasi Lokasi GPS</p>
                                    <div id="loc-status" class="flex items-center gap-2 text-sm font-bold text-orange-500 relative z-10">
                                        <i class="fas fa-circle-notch fa-spin"></i> Mendapatkan koordinat...
                                    </div>
                                    <div id="coords-display" class="text-[10px] text-gray-500 dark:text-gray-400 mt-1 hidden font-mono relative z-10 bg-white dark:bg-gray-800/60 inline-block px-2 py-1 rounded-md">
                                        Lat: <span id="show-lat"></span>, Lng: <span id="show-lng"></span>
                                    </div>
                                    
                                    <div class="mt-3 pt-3 border-t border-gray-200/60 dark:border-gray-700/60 text-xs text-gray-500 dark:text-gray-400 font-bold relative z-10 flex flex-col gap-1.5">
                                        <span class="flex items-center gap-1.5"><i class="fas fa-building text-teal-500 text-xs"></i> Kantor: {{ $activeApp->position->instansi->nama_dinas }}</span>
                                        <span class="flex items-center gap-1.5"><i class="fas fa-compress-arrows-alt text-teal-500 text-xs"></i> Radius Maksimal: {{ $activeApp->position->instansi->radius_absen ?? 100 }} meter</span>
                                    </div>
                                </div>

                                <button type="submit" id="btn-submit" disabled 
                                    class="w-full bg-teal-600 text-white py-3.5 rounded-2xl font-bold shadow-lg shadow-teal-600/30 hover:bg-teal-700 transition transform hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-50 disabled:transform-none disabled:cursor-not-allowed flex items-center justify-center gap-2">
                                    <i class="fas fa-paper-plane"></i> Simpan Laporan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Timeline/Cards -->
                <div class="xl:col-span-8">
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden min-h-[500px] flex flex-col xl:h-[120vh]">
                        
                        <!-- Header & Filters -->
                        <div class="p-6 flex flex-col justify-between gap-4 sticky top-0 z-10 glass-effect">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <h3 class="font-extrabold text-gray-800 dark:text-gray-200 text-lg">Riwayat Jurnal</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 font-medium">Catatan harian aktivitas magang Anda.</p>
                                </div>
                                
                                <!-- Status Filters -->
                                <div class="flex flex-wrap gap-2">
                                    <button @click="filter = 'semua'" :class="filter === 'semua' ? 'bg-gray-800 text-white shadow-md dark:bg-gray-700' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700/50'" class="filter-pill px-4 py-1.5 rounded-full text-xs font-bold transition-all duration-200 active:scale-95 flex items-center gap-2">
                                        Semua <span class="bg-white/20 dark:bg-gray-700/50 px-1.5 py-0.5 rounded-full text-[10px]">{{ $logs->count() }}</span>
                                    </button>
                                    <button @click="filter = 'pending'" :class="filter === 'pending' ? 'bg-yellow-500 text-white shadow-md' : 'bg-yellow-50 dark:bg-yellow-950/20 text-yellow-700 dark:text-yellow-400 hover:bg-yellow-100 dark:hover:bg-yellow-900/30'" class="filter-pill px-4 py-1.5 rounded-full text-xs font-bold transition-all duration-200 active:scale-95">
                                        Pending
                                    </button>
                                    <button @click="filter = 'disetujui'" :class="filter === 'disetujui' ? 'bg-green-500 text-white shadow-md' : 'bg-green-50 dark:bg-green-950/20 text-green-700 dark:text-green-400 hover:bg-green-100 dark:hover:bg-green-900/30'" class="filter-pill px-4 py-1.5 rounded-full text-xs font-bold transition-all duration-200 active:scale-95">
                                        Disetujui
                                    </button>
                                    <button @click="filter = 'revisi'" :class="filter === 'revisi' ? 'bg-red-500 text-white shadow-md' : 'bg-red-50 dark:bg-red-950/20 text-red-700 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/30'" class="filter-pill px-4 py-1.5 rounded-full text-xs font-bold transition-all duration-200 active:scale-95">
                                        Revisi
                                    </button>
                                </div>
                                
                            </div>
                            
                            <!-- Advanced Filters -->
                            <div class="flex flex-wrap gap-3 items-end bg-gray-50 dark:bg-gray-900/50 p-3 rounded-2xl border border-gray-100 dark:border-gray-700 mt-2">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5"><i class="far fa-calendar-alt mr-1"></i> Spesifik Tanggal</label>
                                    <input type="date" x-model="filterTanggal" class="text-xs font-bold text-gray-600 dark:text-gray-400 rounded-xl border-gray-200 dark:border-gray-700 shadow-sm focus:ring-teal-500 focus:border-teal-500 py-2 px-3 bg-white dark:bg-gray-800 w-full sm:w-auto cursor-pointer">
                                </div>
                                <div class="hidden sm:block text-gray-300 font-light mb-2">|</div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5"><i class="far fa-calendar-check mr-1"></i> Filter Bulan</label>
                                    <input type="month" x-model="filterBulan" class="text-xs font-bold text-gray-600 dark:text-gray-400 rounded-xl border-gray-200 dark:border-gray-700 shadow-sm focus:ring-teal-500 focus:border-teal-500 py-2 px-3 bg-white dark:bg-gray-800 w-full sm:w-auto cursor-pointer">
                                </div>
                                <div class="ml-auto flex-1 sm:flex-none flex justify-end gap-2">
                                    <button @click="resetFilters()" x-show="filter !== 'semua' || filterTanggal !== '' || filterBulan !== ''" x-transition class="text-xs font-bold text-red-500 hover:text-red-700 bg-red-50 dark:bg-red-950/20 hover:bg-red-100 dark:hover:bg-red-900/30 border border-red-100 dark:border-red-900/50 px-4 py-2 rounded-xl transition-all flex items-center justify-center gap-1.5 w-full sm:w-auto shadow-sm">
                                        <i class="fas fa-times-circle"></i> Reset Filter
                                    </button>
                                    <a href="{{ route('peserta.logbook.print') }}" target="_blank" class="px-4 py-2 bg-gray-800 hover:bg-gray-900 dark:bg-gray-700 dark:hover:bg-gray-600 text-white rounded-xl text-xs font-bold transition shadow-sm flex items-center gap-2">
                                        <i class="fas fa-print"></i> Cetak Rekap
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Card Grid -->
                        <div class="p-6 bg-gray-50 dark:bg-gray-900/30 flex-1 overflow-y-auto">
                            @if($logs->isEmpty())
                                <div class="flex flex-col items-center justify-center h-full text-gray-400 py-16">
                                    <div class="empty-state-svg mb-5">
                                        <svg width="120" height="120" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect x="20" y="15" width="80" height="90" rx="8" fill="#f1f5f9" stroke="#e2e8f0" stroke-width="2"/>
                                            <rect x="32" y="35" width="56" height="5" rx="2.5" fill="#cbd5e1"/>
                                            <rect x="32" y="48" width="40" height="5" rx="2.5" fill="#cbd5e1"/>
                                            <rect x="32" y="61" width="50" height="5" rx="2.5" fill="#cbd5e1"/>
                                            <rect x="32" y="74" width="30" height="5" rx="2.5" fill="#e2e8f0"/>
                                            <circle cx="60" cy="22" r="8" fill="#14b8a6" opacity="0.2"/>
                                            <path d="M56 22 L59 25 L64 19" stroke="#14b8a6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                    <p class="font-bold text-gray-600 dark:text-gray-400 text-xl">Belum ada jurnal</p>
                                    <p class="text-sm mt-2 text-center max-w-sm font-medium text-gray-400">Mulai tulis aktivitas pertama Anda menggunakan form di sebelah kiri. Jurnal harian adalah bukti produktivitas Anda!</p>
                                </div>
                            @else
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    @foreach($logs as $log)
                                        @php
                                            $badges = [
                                                'pending' => ['bg' => 'bg-yellow-100 dark:bg-yellow-950/30', 'text' => 'text-yellow-800 dark:text-yellow-400', 'icon' => 'fa-clock', 'border' => 'border-yellow-200 dark:border-yellow-900/50'],
                                                'disetujui' => ['bg' => 'bg-green-100 dark:bg-green-950/30', 'text' => 'text-green-800 dark:text-green-400', 'icon' => 'fa-check-circle', 'border' => 'border-green-200 dark:border-green-900/50'],
                                                'revisi' => ['bg' => 'bg-red-100 dark:bg-red-950/30', 'text' => 'text-red-800 dark:text-red-400', 'icon' => 'fa-exclamation-circle', 'border' => 'border-red-200 dark:border-red-900/50'],
                                            ];
                                            $status = $badges[$log->status_validasi] ?? $badges['pending'];
                                        @endphp
                                        
                                        <div x-show="matchFilter('{{ $log->status_validasi }}', '{{ \Carbon\Carbon::parse($log->tanggal)->format('Y-m-d') }}')" 
                                             x-transition.opacity.duration.300ms
                                             x-data="{ showRevisiModal: false }"
                                             @click="openDetail({
                                                 tanggal: '{{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('l, d M Y') }}',
                                                 waktu: '{{ \Carbon\Carbon::parse($log->created_at)->timezone('Asia/Makassar')->format('H:i') }} WITA',
                                                 kegiatan: `{{ e($log->kegiatan) }}`,
                                                 status_validasi: '{{ $log->status_validasi }}',
                                                 komentar_pembimbing_lapangan: `{{ e($log->komentar_pembimbing_lapangan) }}`,
                                                 bukti_foto_path: '{{ $log->bukti_foto_path ? route('storage.access', ['type' => 'logbook', 'filename' => basename($log->bukti_foto_path)]) : '' }}'
                                             })"
                                             class="cursor-pointer logbook-card status-{{ $log->status_validasi }} rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col relative group
                                                    @if($log->status_validasi === 'disetujui') bg-gradient-to-br from-white to-green-50/20 dark:from-gray-800 dark:to-green-950/5
                                                    @elseif($log->status_validasi === 'revisi') bg-gradient-to-br from-white to-red-50/20 dark:from-gray-800 dark:to-red-950/5
                                                    @else bg-gradient-to-br from-white to-yellow-50/20 dark:from-gray-800 dark:to-yellow-950/5
                                                    @endif">
                                            
                                            <!-- Status Ribbon -->
                                            <div class="absolute top-4 right-4 z-10">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider shadow-sm {{ $status['bg'] }} {{ $status['text'] }} {{ $status['border'] }} border">
                                                    <i class="fas {{ $status['icon'] }} mr-1.5"></i> {{ $log->status_validasi }}
                                                </span>
                                            </div>

                                            <!-- Image Header -->
                                            @if($log->bukti_foto_path)
                                                <div class="h-48 w-full bg-gray-100 dark:bg-gray-800 relative cursor-pointer overflow-hidden" @click.stop="openGallery('{{ route('storage.access', ['type' => 'logbook', 'filename' => basename($log->bukti_foto_path)]) }}')">
                                                    <img src="{{ route('storage.access', ['type' => 'logbook', 'filename' => basename($log->bukti_foto_path)]) }}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
                                                        <span class="text-white text-xs font-bold bg-white dark:bg-gray-800/20 backdrop-blur-sm px-3 py-1.5 rounded-full"><i class="fas fa-expand-alt mr-1.5"></i> Perbesar Foto</span>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="h-16 w-full bg-gradient-to-r from-gray-100 to-gray-50 dark:from-gray-800/55 dark:to-gray-900/55 flex items-center justify-center border-b border-gray-100 dark:border-gray-700">
                                                    <span class="text-gray-400 text-xs font-bold uppercase tracking-widest"><i class="fas fa-image mr-1"></i> Tanpa Foto</span>
                                                </div>
                                            @endif

                                            <!-- Content -->
                                            <div class="p-6 flex-1 flex flex-col">
                                                <div class="flex justify-between items-center gap-3 mb-4 border-b border-gray-50 dark:border-gray-700/60 pb-3">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-10 h-10 rounded-full bg-teal-50 dark:bg-teal-950/40 text-teal-600 dark:text-teal-400 flex items-center justify-center shadow-inner border border-teal-100 dark:border-teal-900/50">
                                                            <i class="fas fa-calendar-alt text-sm"></i>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs font-extrabold text-gray-800 dark:text-gray-200 uppercase tracking-wider">{{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('l') }}</p>
                                                            <p class="text-[11px] font-bold text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('d M Y') }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="text-right">
                                                        <span class="text-[9px] font-bold text-gray-400 dark:text-gray-500 block uppercase"><i class="far fa-clock mr-1"></i> Waktu Input</span>
                                                        <span class="text-xs font-black text-teal-600 dark:text-teal-400">{{ \Carbon\Carbon::parse($log->created_at)->timezone('Asia/Makassar')->format('H:i') }} WITA</span>
                                                    </div>
                                                </div>
                                                
                                                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line flex-1">{{ $log->kegiatan }}</p>
                                                
                                                @if($log->status_validasi === 'revisi' && $log->komentar_pembimbing_lapangan)
                                                    <div class="mt-5 bg-red-50/70 dark:bg-red-950/20 border border-red-200 dark:border-red-900/40 rounded-2xl p-4 relative overflow-hidden">
                                                        <div class="absolute top-0 left-0 w-1.5 h-full bg-red-500"></div>
                                                        <div class="flex gap-3 relative z-10">
                                                            <div class="w-8 h-8 rounded-xl bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 flex items-center justify-center flex-shrink-0">
                                                                <i class="fas fa-quote-left text-xs"></i>
                                                            </div>
                                                            <div class="flex-1">
                                                                <span class="block text-[10px] font-black text-red-800 dark:text-red-400 uppercase tracking-wider mb-1">Catatan Revisi Pembimbing</span>
                                                                <p class="text-xs font-semibold text-red-700 dark:text-red-300 leading-relaxed">{{ $log->komentar_pembimbing_lapangan }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- Revisi Button & Modal -->
                                                @if($log->status_validasi === 'revisi')
                                                    <div class="mt-5 pt-5 border-t border-gray-100 dark:border-gray-700">
                                                        <button @click.stop="showRevisiModal = true" type="button" class="w-full inline-flex items-center justify-center px-4 py-3 bg-red-50 dark:bg-red-950/20 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-900/50 rounded-2xl text-xs font-extrabold hover:bg-red-600 dark:hover:bg-red-600 hover:text-white dark:hover:text-white hover:border-red-600 transition-all shadow-sm group/btn hover:shadow-md hover:shadow-red-600/20">
                                                            <i class="fas fa-edit mr-2 group-hover/btn:animate-bounce"></i> PERBAIKI JURNAL INI
                                                        </button>
                                                    </div>

                                                    <!-- Modal Revisi -->
                                                    <div x-show="showRevisiModal" @click.stop class="fixed inset-0 z-[100] overflow-y-auto text-left" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                            <div x-show="showRevisiModal" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showRevisiModal = false" aria-hidden="true"></div>
                                                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                                            <div x-show="showRevisiModal" x-transition class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                                                                <form action="{{ route('peserta.logbook.update', $log->id) }}" method="POST" enctype="multipart/form-data">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="bg-white dark:bg-gray-800 px-6 pt-6 pb-6">
                                                                        <div class="flex items-center gap-4 mb-6 pb-5 border-b border-gray-100 dark:border-gray-700">
                                                                            <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-950/40 shadow-inner">
                                                                                <i class="fas fa-edit text-red-600 dark:text-red-400 text-xl"></i>
                                                                            </div>
                                                                            <div>
                                                                                <h3 class="text-xl font-black text-gray-900 dark:text-gray-100" id="modal-title">
                                                                                    Revisi Jurnal
                                                                                </h3>
                                                                                <p class="text-xs text-gray-500 dark:text-gray-400 font-bold mt-0.5">{{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('d F Y') }}</p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="space-y-5">
                                                                            <div>
                                                                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-2">Deskripsi Kegiatan <span class="text-red-500">*</span></label>
                                                                                <textarea name="kegiatan" rows="4" class="w-full rounded-2xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 text-sm shadow-sm resize-none" required>{{ $log->kegiatan }}</textarea>
                                                                            </div>
                                                                            <div>
                                                                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-2">Ganti Foto (Opsional)</label>
                                                                                <input type="file" name="foto" accept="image/*" class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-gray-100 dark:file:bg-gray-800 file:text-gray-700 dark:file:text-gray-300 hover:file:bg-gray-200 cursor-pointer border border-gray-200 dark:border-gray-700 rounded-xl transition bg-white dark:bg-gray-900">
                                                                                <p class="text-[10px] text-gray-400 mt-2 font-bold">Abaikan jika tidak ada perubahan dokumentasi.</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="bg-gray-50 dark:bg-gray-900 px-6 py-4 flex flex-row-reverse gap-3 border-t border-gray-100 dark:border-gray-700">
                                                                        <button type="submit" class="w-full inline-flex justify-center rounded-xl shadow-sm px-6 py-3 bg-teal-600 text-sm font-bold text-white hover:bg-teal-700 transition sm:w-auto hover:-translate-y-0.5 shadow-teal-600/30">
                                                                            Simpan Revisi
                                                                        </button>
                                                                        <button type="button" @click="showRevisiModal = false" class="w-full inline-flex justify-center rounded-xl shadow-sm px-6 py-3 bg-white dark:bg-gray-800 text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900 border border-gray-300 dark:border-gray-600 transition sm:w-auto hover:-translate-y-0.5">
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
                    <button @click="galleryOpen = false" class="absolute top-4 right-4 z-20 w-12 h-12 bg-black/50 hover:bg-red-600 text-white rounded-full flex items-center justify-center transition backdrop-blur-sm border border-white/20">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                    <img :src="galleryImage" class="w-full max-h-[90vh] object-contain bg-black/50" />
                </div>
            </div>
        </div>

        <!-- Detail Logbook Modal -->
        <div x-show="detailOpen" class="fixed inset-0 z-[150] overflow-y-auto" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div x-show="detailOpen" x-transition.opacity.duration.300ms class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity" @click="detailOpen = false" aria-hidden="true"></div>
                
                <div x-show="detailOpen" x-transition.scale.duration.300ms class="relative z-10 w-full max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-3xl overflow-hidden shadow-2xl text-left transform transition-all flex flex-col md:flex-row max-h-[85vh] border border-gray-100 dark:border-gray-700">
                    <!-- Modal Close Button -->
                    <button @click="detailOpen = false" class="absolute top-4 right-4 z-20 w-10 h-10 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-500 dark:text-gray-300 rounded-full flex items-center justify-center transition shadow-sm border border-gray-200/50 dark:border-gray-600/50">
                        <i class="fas fa-times"></i>
                    </button>

                    <!-- Left panel: Photo (if exists) -->
                    <div class="md:w-1/2 bg-gray-50 dark:bg-gray-900 flex items-center justify-center relative overflow-hidden border-r border-gray-100 dark:border-gray-700 max-h-[40vh] md:max-h-full">
                        <template x-if="selectedLog.bukti_foto_path">
                            <div class="w-full h-full relative group">
                                <img :src="selectedLog.bukti_foto_path" class="w-full h-full object-cover" />
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center cursor-zoom-in" @click.stop="openGallery(selectedLog.bukti_foto_path)">
                                    <span class="text-white text-xs font-bold bg-white/20 backdrop-blur-md px-4 py-2 rounded-full border border-white/20"><i class="fas fa-expand-alt mr-1.5"></i> Perbesar Foto</span>
                                </div>
                            </div>
                        </template>
                        <template x-if="!selectedLog.bukti_foto_path">
                            <div class="p-8 text-center text-gray-400 dark:text-gray-600 flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-2xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-2xl">
                                    <i class="fas fa-image"></i>
                                </div>
                                <span class="text-xs font-black uppercase tracking-wider">Tidak Ada Lampiran Foto</span>
                            </div>
                        </template>
                    </div>

                    <!-- Right panel: Details -->
                    <div class="md:w-1/2 p-8 flex flex-col justify-between overflow-y-auto max-h-[45vh] md:max-h-full">
                        <div>
                            <!-- Header metadata -->
                            <div class="flex items-center justify-between gap-3 mb-6 border-b border-gray-100 dark:border-gray-700/60 pb-4">
                                <div>
                                    <span class="text-[10px] font-black text-teal-600 dark:text-teal-400 uppercase tracking-widest block mb-1"><i class="far fa-calendar-alt mr-1"></i> Tanggal Jurnal</span>
                                    <h4 class="text-base font-extrabold text-gray-800 dark:text-gray-100" x-text="selectedLog.tanggal"></h4>
                                </div>
                                <div class="text-right">
                                    <span class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest block mb-1"><i class="far fa-clock mr-1"></i> Waktu Input</span>
                                    <span class="text-xs font-black text-gray-700 dark:text-gray-300" x-text="selectedLog.waktu"></span>
                                </div>
                            </div>

                            <!-- Validation Status Badge -->
                            <div class="mb-6">
                                <span class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest block mb-2">Status Validasi</span>
                                <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-black uppercase tracking-wider border shadow-sm"
                                    :class="{
                                        'bg-green-50 dark:bg-green-950/20 text-green-700 dark:text-green-400 border-green-200 dark:border-green-900/50': selectedLog.status_validasi === 'disetujui',
                                        'bg-yellow-50 dark:bg-yellow-950/20 text-yellow-700 dark:text-yellow-400 border-yellow-200 dark:border-yellow-900/50': selectedLog.status_validasi === 'pending',
                                        'bg-red-50 dark:bg-red-950/20 text-red-700 dark:text-red-400 border-red-200 dark:border-red-900/50': selectedLog.status_validasi === 'revisi'
                                    }">
                                    <i class="fas mr-2" :class="{
                                        'fa-check-circle': selectedLog.status_validasi === 'disetujui',
                                        'fa-clock': selectedLog.status_validasi === 'pending',
                                        'fa-exclamation-circle': selectedLog.status_validasi === 'revisi'
                                    }"></i>
                                    <span x-text="selectedLog.status_validasi"></span>
                                </span>
                            </div>

                            <!-- Description -->
                            <div class="mb-6">
                                <span class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest block mb-2">Deskripsi Kegiatan</span>
                                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line bg-gray-50/50 dark:bg-gray-900/30 border border-gray-100/80 dark:border-gray-700/60 p-4 rounded-2xl" x-text="selectedLog.kegiatan"></p>
                            </div>

                            <!-- Revisions / Comments -->
                            <template x-if="selectedLog.status_validasi === 'revisi' && selectedLog.komentar_pembimbing_lapangan">
                                <div class="bg-red-50/70 dark:bg-red-950/20 border border-red-200 dark:border-red-900/40 rounded-2xl p-4 relative overflow-hidden">
                                    <div class="absolute top-0 left-0 w-1.5 h-full bg-red-500"></div>
                                    <div class="flex gap-3 relative z-10">
                                        <div class="w-8 h-8 rounded-xl bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-quote-left text-xs"></i>
                                        </div>
                                        <div class="flex-1">
                                            <span class="block text-[10px] font-black text-red-800 dark:text-red-400 uppercase tracking-wider mb-1">Catatan Revisi Pembimbing</span>
                                            <p class="text-xs font-semibold text-red-700 dark:text-red-300 leading-relaxed" x-text="selectedLog.komentar_pembimbing_lapangan"></p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Image Preview Script
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
            // If user clicked cancel in the file dialog, it clears the input.
            // We must clear the preview so they know the file is gone.
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

    // Alpine JS Data
    document.addEventListener('alpine:init', () => {
        Alpine.data('logbookData', () => ({
            filter: 'semua',
            filterTanggal: '',
            filterBulan: '',
            galleryOpen: false,
            galleryImage: '',
            detailOpen: false,
            selectedLog: {
                tanggal: '',
                waktu: '',
                kegiatan: '',
                status_validasi: '',
                komentar_pembimbing_lapangan: '',
                bukti_foto_path: ''
            },
            openDetail(log) {
                this.selectedLog = log;
                this.detailOpen = true;
            },
            openGallery(imgUrl) {
                this.galleryImage = imgUrl;
                this.galleryOpen = true;
            },
            matchFilter(status, dateStr) {
                // Check Status
                if (this.filter !== 'semua' && this.filter !== status) return false;
                
                // Check Exact Date
                if (this.filterTanggal) {
                    if (dateStr !== this.filterTanggal) return false;
                }
                
                // Check Month/Year (starts with YYYY-MM)
                if (this.filterBulan) {
                    if (!dateStr.startsWith(this.filterBulan)) return false;
                }
                
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

        function requestLocation() {
            const isLocal = window.location.hostname === 'localhost' || 
                            window.location.hostname === '127.0.0.1' || 
                            window.location.hostname.startsWith('192.168.') || 
                            window.location.hostname.startsWith('10.');

            if (isLocal) {
                const mockLat = {{ $activeApp->position->instansi->latitude ?? -3.316694 }};
                const mockLng = {{ $activeApp->position->instansi->longitude ?? 114.590111 }};
                successLocation({
                    coords: {
                        latitude: mockLat,
                        longitude: mockLng
                    }
                });
                statusDiv.innerHTML = '<span class="text-green-600 flex items-center font-extrabold"><i class="fas fa-check-circle mr-1.5 text-lg shadow-green-500/50 drop-shadow"></i> Lokasi Terkunci (Mock Local)</span>';
                return;
            }

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

            statusDiv.innerHTML = '<span class="text-green-600 flex items-center font-extrabold"><i class="fas fa-check-circle mr-1.5 text-lg shadow-green-500/50 drop-shadow"></i> Lokasi Terkunci</span>';
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
                    retryBtn = `<button type="button" onclick="window.location.reload()" class="ml-2 underline text-blue-600 hover:text-blue-800">Coba Lagi</button>`;
                    break;
            }
            showErrorMsg(msg, retryBtn);
        }

        function showErrorMsg(msg, extraHtml = '') {
            statusDiv.innerHTML = `<div class="flex flex-col"><span class="text-red-500 flex items-center font-bold text-xs"><i class="fas fa-exclamation-triangle mr-1.5"></i> ${msg}</span>${extraHtml}</div>`;
            btnSubmit.disabled = true;
        }

        const textarea = document.getElementsByName("kegiatan")[0];
        const counter = document.getElementById("char-counter");

        if (textarea && counter) {
            textarea.addEventListener("input", function() {
                const len = textarea.value.length;
                counter.innerText = `${len} / 2000`;
                if (len >= 1800) {
                    counter.className = "text-[10px] text-red-500 font-bold animate-pulse";
                } else if (len >= 1500) {
                    counter.className = "text-[10px] text-orange-500 font-bold";
                } else {
                    counter.className = "text-[10px] text-gray-400 dark:text-gray-500 font-bold";
                }
            });
        }

        requestLocation();
    });
    </script>
</x-app-layout>
