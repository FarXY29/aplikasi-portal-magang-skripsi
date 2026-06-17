<x-app-layout>
    @push('head')
        <meta name="turbo-cache-control" content="no-cache">
        <style>
            .glass-effect {
                background: rgba(255, 255, 255, 0.85);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border-bottom: 1px solid rgba(243, 244, 246, 1);
            }
        </style>
    @endpush

    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-black text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-book-open text-teal-600"></i>
                {{ __('Logbook Harian') }}
            </h2>
        </div>
    </x-slot>

    <div x-data="logbookData()" class="py-8 bg-gray-50/50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="mb-6 print:hidden">
                <a href="{{ route('peserta.dashboard') }}" class="group inline-flex items-center text-sm font-bold text-gray-500 hover:text-teal-600 transition">
                    <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                    Kembali ke Dashboard
                </a>
            </div>

            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition class="flex items-center p-4 mb-4 text-green-800 rounded-xl bg-green-50 border border-green-100 shadow-sm relative">
                    <i class="fas fa-check-circle flex-shrink-0 w-5 h-5 mr-3 text-green-600"></i>
                    <div class="text-sm font-bold">{{ session('success') }}</div>
                    <button @click="show = false" class="ml-auto text-green-500 hover:text-green-700"><i class="fas fa-times"></i></button>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-transition class="flex items-center p-4 mb-4 text-red-800 rounded-xl bg-red-50 border border-red-100 shadow-sm relative">
                    <i class="fas fa-map-marker-slash flex-shrink-0 w-5 h-5 mr-3 text-red-600"></i>
                    <div class="text-sm font-bold">{{ session('error') }}</div>
                    <button @click="show = false" class="ml-auto text-red-500 hover:text-red-700"><i class="fas fa-times"></i></button>
                </div>
            @endif

            <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
                
                <!-- Left Column: Form -->
                <div class="xl:col-span-4">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden sticky top-8">
                        <div class="p-6 border-b border-gray-50 bg-gradient-to-r from-teal-50 to-emerald-50">
                            <h3 class="font-extrabold text-gray-800 flex items-center gap-2 text-lg">
                                <i class="fas fa-pen-nib text-teal-600"></i> Tulis Jurnal Baru
                            </h3>
                            <p class="text-xs text-gray-500 mt-1 font-medium">Catat aktivitas magang Anda hari ini.</p>
                        </div>
                        
                        <div class="p-6">
                            <form action="{{ route('peserta.logbook.store') }}" method="POST" enctype="multipart/form-data" id="logbookForm">
                                @csrf
                                <input type="hidden" name="latitude" id="lat">
                                <input type="hidden" name="longitude" id="lng">

                                <div class="mb-5">
                                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Deskripsi Kegiatan</label>
                                    <textarea name="kegiatan" rows="5" class="w-full rounded-2xl border-gray-200 focus:border-teal-500 focus:ring-teal-500 text-sm shadow-sm transition-shadow hover:shadow-md resize-none" placeholder="Apa yang Anda kerjakan hari ini?" required></textarea>
                                </div>

                                <div class="mb-5">
                                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Dokumentasi (Foto)</label>
                                    <div class="relative w-full h-40 rounded-2xl border-2 border-dashed border-gray-300 bg-gray-50 hover:bg-teal-50 hover:border-teal-400 transition-all group overflow-hidden flex flex-col items-center justify-center cursor-pointer">
                                        <!-- Image Preview Container -->
                                        <div id="image-preview" class="absolute inset-0 z-10 hidden bg-black">
                                            <img id="preview-img" src="" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition-opacity" />
                                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                <span class="text-white text-xs font-bold bg-black/50 px-3 py-1 rounded-full"><i class="fas fa-camera"></i> Ganti Foto</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Default Empty State -->
                                        <div id="empty-state" class="flex flex-col items-center justify-center z-0">
                                            <div class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                                <i class="fas fa-cloud-upload-alt text-teal-500 text-xl"></i>
                                            </div>
                                            <p class="text-xs font-bold text-gray-600">Klik untuk upload foto</p>
                                            <p class="text-[10px] text-gray-400 mt-1 font-bold">PNG, JPG, JPEG up to 5MB</p>
                                        </div>
                                        
                                        <input id="foto" name="foto" type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" accept="image/*" onchange="previewImage(this)" />
                                    </div>
                                    <button type="button" id="remove-btn" onclick="removeImage()" class="hidden mt-2 text-xs text-red-500 hover:text-red-700 font-bold items-center gap-1">
                                        <i class="fas fa-trash"></i> Hapus Foto
                                    </button>
                                </div>

                                <div class="mb-6 bg-gradient-to-br from-gray-50 to-gray-100 p-4 rounded-2xl border border-gray-200 relative overflow-hidden">
                                    <div class="absolute top-0 right-0 -mt-2 -mr-2 text-gray-200 opacity-50">
                                        <i class="fas fa-map-marked-alt text-6xl"></i>
                                    </div>
                                    <p class="text-[10px] font-extrabold text-gray-500 uppercase tracking-widest mb-2 relative z-10">Verifikasi Lokasi GPS</p>
                                    <div id="loc-status" class="flex items-center gap-2 text-sm font-bold text-orange-500 relative z-10">
                                        <i class="fas fa-circle-notch fa-spin"></i> Mendapatkan koordinat...
                                    </div>
                                    <div id="coords-display" class="text-[10px] text-gray-500 mt-1 hidden font-mono relative z-10 bg-white/60 inline-block px-2 py-1 rounded-md">
                                        Lat: <span id="show-lat"></span>, Lng: <span id="show-lng"></span>
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
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden min-h-[500px] flex flex-col xl:h-[120vh]">
                        
                        <!-- Header & Filters -->
                        <div class="p-6 flex flex-col justify-between gap-4 sticky top-0 z-10 glass-effect">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <h3 class="font-extrabold text-gray-800 text-lg">Riwayat Jurnal</h3>
                                    <p class="text-xs text-gray-500 mt-0.5 font-medium">Catatan harian aktivitas magang Anda.</p>
                                </div>
                                
                                <!-- Status Filters -->
                                <div class="flex flex-wrap gap-2">
                                    <button @click="filter = 'semua'" :class="filter === 'semua' ? 'bg-gray-800 text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-4 py-1.5 rounded-full text-xs font-bold transition-all flex items-center gap-2">
                                        Semua <span class="bg-white/20 px-1.5 py-0.5 rounded-full text-[10px]">{{ $logs->count() }}</span>
                                    </button>
                                    <button @click="filter = 'pending'" :class="filter === 'pending' ? 'bg-yellow-500 text-white shadow-md' : 'bg-yellow-50 text-yellow-700 hover:bg-yellow-100'" class="px-4 py-1.5 rounded-full text-xs font-bold transition-all">
                                        Pending
                                    </button>
                                    <button @click="filter = 'disetujui'" :class="filter === 'disetujui' ? 'bg-green-500 text-white shadow-md' : 'bg-green-50 text-green-700 hover:bg-green-100'" class="px-4 py-1.5 rounded-full text-xs font-bold transition-all">
                                        Disetujui
                                    </button>
                                    <button @click="filter = 'revisi'" :class="filter === 'revisi' ? 'bg-red-500 text-white shadow-md' : 'bg-red-50 text-red-700 hover:bg-red-100'" class="px-4 py-1.5 rounded-full text-xs font-bold transition-all">
                                        Revisi
                                    </button>
                                </div>
                                
                            </div>
                            
                            <!-- Advanced Filters -->
                            <div class="flex flex-wrap gap-3 items-end bg-gray-50/50 p-3 rounded-2xl border border-gray-100 mt-2">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5"><i class="far fa-calendar-alt mr-1"></i> Spesifik Tanggal</label>
                                    <input type="date" x-model="filterTanggal" class="text-xs font-bold text-gray-600 rounded-xl border-gray-200 shadow-sm focus:ring-teal-500 focus:border-teal-500 py-2 px-3 bg-white w-full sm:w-auto cursor-pointer">
                                </div>
                                <div class="hidden sm:block text-gray-300 font-light mb-2">|</div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5"><i class="far fa-calendar-check mr-1"></i> Filter Bulan</label>
                                    <input type="month" x-model="filterBulan" class="text-xs font-bold text-gray-600 rounded-xl border-gray-200 shadow-sm focus:ring-teal-500 focus:border-teal-500 py-2 px-3 bg-white w-full sm:w-auto cursor-pointer">
                                </div>
                                <div class="ml-auto flex-1 sm:flex-none flex justify-end">
                                    <button @click="resetFilters()" x-show="filter !== 'semua' || filterTanggal !== '' || filterBulan !== ''" x-transition class="text-xs font-bold text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 border border-red-100 px-4 py-2 rounded-xl transition-all flex items-center justify-center gap-1.5 w-full sm:w-auto shadow-sm">
                                        <i class="fas fa-times-circle"></i> Reset Filter
                                    </button>
                                </div>
                                <a href="{{ route('peserta.logbook.print') }}" target="_blank" class="px-4 py-2 bg-gray-800 text-white rounded-xl text-sm font-bold hover:bg-gray-900 transition shadow-sm flex items-center gap-2">
                <i class="fas fa-print"></i> Cetak Rekap Logbook
            </a>
                            </div>
                        </div>

                        <!-- Card Grid -->
                        <div class="p-6 bg-gray-50/30 flex-1 overflow-y-auto">
                            @if($logs->isEmpty())
                                <div class="flex flex-col items-center justify-center h-full text-gray-400 py-12">
                                    <div class="w-32 h-32 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-folder-open text-4xl text-gray-300"></i>
                                    </div>
                                    <p class="font-bold text-gray-500 text-lg">Belum ada jurnal</p>
                                    <p class="text-sm mt-1 text-center max-w-sm font-medium">Mulai tulis aktivitas pertama Anda menggunakan form di sebelah kiri.</p>
                                </div>
                            @else
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    @foreach($logs as $log)
                                        @php
                                            $badges = [
                                                'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'fa-clock', 'border' => 'border-yellow-200'],
                                                'disetujui' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'fa-check-circle', 'border' => 'border-green-200'],
                                                'revisi' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'fa-exclamation-circle', 'border' => 'border-red-200'],
                                            ];
                                            $status = $badges[$log->status_validasi] ?? $badges['pending'];
                                        @endphp
                                        
                                        <div x-show="matchFilter('{{ $log->status_validasi }}', '{{ \Carbon\Carbon::parse($log->tanggal)->format('Y-m-d') }}')" 
                                             x-transition.opacity.duration.300ms
                                             x-data="{ showRevisiModal: false }"
                                             class="bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col relative group">
                                            
                                            <!-- Status Ribbon -->
                                            <div class="absolute top-4 right-4 z-10">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider shadow-sm {{ $status['bg'] }} {{ $status['text'] }} {{ $status['border'] }} border">
                                                    <i class="fas {{ $status['icon'] }} mr-1.5"></i> {{ $log->status_validasi }}
                                                </span>
                                            </div>

                                            <!-- Image Header -->
                                            @if($log->bukti_foto_path)
                                                <div class="h-48 w-full bg-gray-100 relative cursor-pointer overflow-hidden" @click="openGallery('{{ Storage::url($log->bukti_foto_path) }}')">
                                                    <img src="{{ Storage::url($log->bukti_foto_path) }}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
                                                        <span class="text-white text-xs font-bold bg-white/20 backdrop-blur-sm px-3 py-1.5 rounded-full"><i class="fas fa-expand-alt mr-1.5"></i> Perbesar Foto</span>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="h-16 w-full bg-gradient-to-r from-gray-100 to-gray-50 flex items-center justify-center border-b border-gray-100">
                                                    <span class="text-gray-400 text-xs font-bold uppercase tracking-widest"><i class="fas fa-image mr-1"></i> Tanpa Foto</span>
                                                </div>
                                            @endif

                                            <!-- Content -->
                                            <div class="p-6 flex-1 flex flex-col">
                                                <div class="flex items-center gap-3 mb-4">
                                                    <div class="w-10 h-10 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center shadow-inner border border-teal-100">
                                                        <i class="fas fa-calendar-alt text-sm"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs font-extrabold text-gray-800 uppercase tracking-wider">{{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('l') }}</p>
                                                        <p class="text-[11px] font-bold text-gray-500">{{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('d M Y') }}</p>
                                                    </div>
                                                </div>
                                                
                                                <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line flex-1">{{ $log->kegiatan }}</p>
                                                
                                                @if($log->komentar_pembimbing_lapangan)
                                                    <div class="mt-5 bg-red-50/50 border border-red-100 rounded-2xl p-4 relative overflow-hidden">
                                                        <div class="absolute top-0 left-0 w-1.5 h-full bg-red-400"></div>
                                                        <div class="flex gap-3">
                                                            <div class="mt-0.5">
                                                                <i class="fas fa-comment-dots text-red-500 text-lg"></i>
                                                            </div>
                                                            <div>
                                                                <span class="block text-[10px] font-black text-red-800 uppercase tracking-wider mb-1">Catatan Pembimbing</span>
                                                                <p class="text-xs font-medium text-red-700 leading-relaxed">{{ $log->komentar_pembimbing_lapangan }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- Revisi Button & Modal -->
                                                @if($log->status_validasi === 'revisi')
                                                    <div class="mt-5 pt-5 border-t border-gray-100">
                                                        <button @click="showRevisiModal = true" type="button" class="w-full inline-flex items-center justify-center px-4 py-3 bg-red-50 text-red-600 border border-red-200 rounded-2xl text-xs font-extrabold hover:bg-red-600 hover:text-white hover:border-red-600 transition-all shadow-sm group/btn hover:shadow-md hover:shadow-red-600/20">
                                                            <i class="fas fa-edit mr-2 group-hover/btn:animate-bounce"></i> PERBAIKI JURNAL INI
                                                        </button>
                                                    </div>

                                                    <!-- Modal Revisi -->
                                                    <div x-show="showRevisiModal" class="fixed inset-0 z-[100] overflow-y-auto text-left" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                            <div x-show="showRevisiModal" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showRevisiModal = false" aria-hidden="true"></div>
                                                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                                            <div x-show="showRevisiModal" x-transition class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                                                                <form action="{{ route('peserta.logbook.update', $log->id) }}" method="POST" enctype="multipart/form-data">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="bg-white px-6 pt-6 pb-6">
                                                                        <div class="flex items-center gap-4 mb-6 pb-5 border-b border-gray-100">
                                                                            <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 shadow-inner">
                                                                                <i class="fas fa-edit text-red-600 text-xl"></i>
                                                                            </div>
                                                                            <div>
                                                                                <h3 class="text-xl font-black text-gray-900" id="modal-title">
                                                                                    Revisi Jurnal
                                                                                </h3>
                                                                                <p class="text-xs text-gray-500 font-bold mt-0.5">{{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('d F Y') }}</p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="space-y-5">
                                                                            <div>
                                                                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Deskripsi Kegiatan <span class="text-red-500">*</span></label>
                                                                                <textarea name="kegiatan" rows="4" class="w-full rounded-2xl border-gray-200 focus:border-teal-500 focus:ring-teal-500 text-sm shadow-sm resize-none" required>{{ $log->kegiatan }}</textarea>
                                                                            </div>
                                                                            <div>
                                                                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Ganti Foto (Opsional)</label>
                                                                                <input type="file" name="foto" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer border border-gray-200 rounded-xl transition">
                                                                                <p class="text-[10px] text-gray-400 mt-2 font-bold">Abaikan jika tidak ada perubahan dokumentasi.</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3 border-t border-gray-100">
                                                                        <button type="submit" class="w-full inline-flex justify-center rounded-xl shadow-sm px-6 py-3 bg-teal-600 text-sm font-bold text-white hover:bg-teal-700 transition sm:w-auto hover:-translate-y-0.5 shadow-teal-600/30">
                                                                            Simpan Revisi
                                                                        </button>
                                                                        <button type="button" @click="showRevisiModal = false" class="w-full inline-flex justify-center rounded-xl shadow-sm px-6 py-3 bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 border border-gray-300 transition sm:w-auto hover:-translate-y-0.5">
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

        requestLocation();
    });
    </script>
</x-app-layout>