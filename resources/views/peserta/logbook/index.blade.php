<x-app-layout>
    @push('head')
        <meta name="turbo-cache-control" content="no-cache">
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
            
            @include('peserta._back-link', ['href' => route('peserta.dashboard'), 'label' => 'Kembali ke Dashboard'])

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
                @include('peserta.logbook._form-card')

                {{-- Right Column: Timeline & Cards --}}
                <div class="xl:col-span-8">
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden min-h-[500px] flex flex-col xl:h-[120vh]">
                        
                        <!-- Header & Filters -->
                        <div class="p-6 flex flex-col justify-between gap-4 sticky top-0 z-10 glass-effect">
                            <form method="GET" action="{{ route('peserta.logbook.index') }}" x-ref="filterForm">
                                <input type="hidden" name="status" x-model="filter">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <h3 class="font-extrabold text-gray-800 dark:text-gray-100 text-base sm:text-lg">Riwayat Jurnal</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 font-medium">Catatan harian aktivitas magang Anda.</p>
                                </div>
                                
                                    <!-- Status Filters -->
                                <div class="flex flex-wrap gap-2">
                                    @php
                                        // Literal class strings per pill (Tailwind JIT-safe). Active vs idle states preserved.
                                        $filterPills = [
                                            'semua' => [
                                                'label' => 'Semua',
                                                'active' => 'bg-gray-800 dark:bg-gray-100 text-white dark:text-gray-900 shadow-xs',
                                                'idle' => 'bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700/50',
                                                'extra' => 'flex items-center gap-1.5',
                                            ],
                                            'pending' => [
                                                'label' => 'Pending',
                                                'active' => 'bg-amber-500 text-white shadow-xs',
                                                'idle' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 hover:bg-amber-100 border border-amber-200 dark:border-amber-800/60',
                                                'extra' => '',
                                            ],
                                            'disetujui' => [
                                                'label' => 'Disetujui',
                                                'active' => 'bg-emerald-600 text-white shadow-xs',
                                                'idle' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 hover:bg-emerald-100 border border-emerald-200 dark:border-emerald-800/60',
                                                'extra' => '',
                                            ],
                                            'revisi' => [
                                                'label' => 'Revisi',
                                                'active' => 'bg-rose-600 text-white shadow-xs',
                                                'idle' => 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 hover:bg-rose-100 border border-rose-200 dark:border-rose-800/60',
                                                'extra' => '',
                                            ],
                                        ];
                                    @endphp
                                    @foreach($filterPills as $value => $pill)
                                        <button type="button" @click="filter = '{{ $value }}'; $nextTick(() => $refs.filterForm.submit())" :aria-pressed="filter === '{{ $value }}'" :class="filter === '{{ $value }}' ? '{{ $pill['active'] }}' : '{{ $pill['idle'] }}'" class="filter-pill px-3.5 py-1.5 rounded-full text-xs font-bold transition {{ $pill['extra'] }}">
                                            {{ $pill['label'] }}
                                            @if($value === 'semua')
                                                <span class="bg-white/20 dark:bg-gray-800/40 px-1.5 py-0.5 rounded-full text-[10px]">{{ $logs->total() }}</span>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>
                                
                            </div>
                            
                            <!-- Advanced Filters -->
                            <div class="flex flex-wrap gap-3 items-end bg-gray-50 dark:bg-gray-900 p-3 rounded-2xl border border-gray-100 dark:border-gray-700 mt-2">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5"><i class="far fa-calendar-alt mr-1"></i> Spesifik Tanggal</label>
                                    <input type="date" name="date" x-model="filterTanggal" @change="$refs.filterForm.submit()" class="text-xs font-bold text-gray-800 dark:text-gray-100 rounded-xl border-gray-300 dark:border-gray-700 shadow-xs focus:ring-teal-500 focus:border-teal-500 py-2 px-3 bg-white dark:bg-gray-800 w-full sm:w-auto cursor-pointer [color-scheme:dark]">
                                </div>
                                <div class="hidden sm:block text-gray-300 dark:text-gray-700 font-light mb-2">|</div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5"><i class="far fa-calendar-check mr-1"></i> Filter Bulan</label>
                                    <input type="month" name="month" x-model="filterBulan" @change="$refs.filterForm.submit()" class="text-xs font-bold text-gray-800 dark:text-gray-100 rounded-xl border-gray-300 dark:border-gray-700 shadow-xs focus:ring-teal-500 focus:border-teal-500 py-2 px-3 bg-white dark:bg-gray-800 w-full sm:w-auto cursor-pointer [color-scheme:dark]">
                                </div>
                                <div class="ml-auto flex-1 sm:flex-none flex justify-end gap-2">
                                    <button type="button" @click="resetFilters()" x-show="filter !== 'semua' || filterTanggal !== '' || filterBulan !== ''" x-transition class="text-xs font-bold text-rose-700 dark:text-rose-300 bg-rose-50 dark:bg-rose-950/60 hover:bg-rose-100 border border-rose-200 dark:border-rose-800/60 px-4 py-2 rounded-xl transition flex items-center justify-center gap-1.5 w-full sm:w-auto shadow-xs">
                                        <i class="fas fa-times-circle"></i> Reset Filter
                                    </button>
                                    <a href="{{ route('peserta.logbook.print', $activeApp->id) }}" target="_blank" class="px-4 py-2 bg-gray-800 hover:bg-gray-900 dark:bg-gray-700 dark:hover:bg-gray-600 text-white rounded-xl text-xs font-bold transition shadow-xs flex items-center gap-2">
                                        <i class="fas fa-file-pdf"></i> Download PDF Rekap
                                    </a>
                                </div>
                            </div>
                                </form>
                        </div>

                        <!-- Card Grid -->
                        <div class="p-6 bg-gray-50/50 dark:bg-gray-900/50 flex-1 overflow-y-auto">
                            @if($logs->isEmpty())
                                @php
                                    $isNarrowed = request()->filled('status') || request()->filled('date') || request()->filled('month');
                                @endphp
                                <x-ui.empty-state
                                    mobile
                                    icon="far fa-clipboard"
                                    :title="$isNarrowed ? 'Tidak Ada Jurnal Sesuai Filter' : 'Belum Ada Jurnal'"
                                    :description="$isNarrowed
                                        ? 'Coba ubah filter status atau tanggal yang dipilih.'
                                        : 'Mulai tulis aktivitas pertama Anda menggunakan formulir di sebelah kiri.'" />
                            @else
                                @php
                                    $badges = [
                                        'pending' => ['bg' => 'bg-amber-50 dark:bg-amber-950/60', 'text' => 'text-amber-700 dark:text-amber-300', 'icon' => 'fa-clock', 'border' => 'border-amber-200 dark:border-amber-800/60'],
                                        'disetujui' => ['bg' => 'bg-emerald-50 dark:bg-emerald-950/60', 'text' => 'text-emerald-700 dark:text-emerald-300', 'icon' => 'fa-check-circle', 'border' => 'border-emerald-200 dark:border-emerald-800/60'],
                                        'revisi' => ['bg' => 'bg-rose-50 dark:bg-rose-950/60', 'text' => 'text-rose-700 dark:text-rose-300', 'icon' => 'fa-exclamation-circle', 'border' => 'border-rose-200 dark:border-rose-800/60'],
                                    ];
                                @endphp
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    @foreach($logs as $log)
                                        @php
                                            $status = $badges[$log->status_validasi] ?? $badges['pending'];
                                        @endphp
                                        
                                        <div class="logbook-card status-{{ $log->status_validasi }} bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xs hover:shadow-md transition duration-300 overflow-hidden flex flex-col relative group">
                                            
                                            <!-- Status Ribbon -->
                                            <div class="absolute top-4 right-4 z-10">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider shadow-xs {{ $status['bg'] }} {{ $status['text'] }} {{ $status['border'] }} border">
                                                    <i class="fas {{ $status['icon'] }} mr-1.5"></i> {{ $log->status_validasi }}
                                                </span>
                                            </div>

                                            <!-- Image Header -->
                                            @if($log->bukti_foto_path)
                                                @php
                                                    $fotoUrl = route('storage.access', ['type' => 'logbook', 'filename' => basename($log->bukti_foto_path)]);
                                                    $fotoTitle = 'Dokumentasi Logbook - ' . \Carbon\Carbon::parse($log->tanggal)->translatedFormat('d F Y');
                                                @endphp
                                                <div class="h-48 w-full bg-gray-100 dark:bg-gray-900 relative cursor-pointer overflow-hidden" onclick="openImageModal(@js($fotoUrl), @js($fotoTitle))">
                                                    <img src="{{ $fotoUrl }}" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500" alt="Dokumentasi">
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

                                                <!-- Action Buttons -->
                                                @if($log->status_validasi === 'pending')
                                                    <div class="mt-5 pt-5 border-t border-gray-100 dark:border-gray-700 flex gap-3">
                                                        <button @click="openEditModal({{ $log->id }}, {{ Js::from(\Carbon\Carbon::parse($log->tanggal)->translatedFormat('d F Y')) }}, {{ Js::from($log->kegiatan) }}, {{ Js::from($log->status_validasi) }})" type="button" class="flex-1 inline-flex items-center justify-center px-4 py-3 bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-800/60 rounded-2xl text-xs font-bold hover:bg-teal-600 hover:text-white transition shadow-xs">
                                                            <i class="fas fa-edit mr-2"></i> Edit Jurnal
                                                        </button>
                                                        
                                                        <form action="{{ route('peserta.logbook.destroy', $log->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jurnal harian ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-3 bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60 rounded-2xl text-xs font-bold hover:bg-rose-600 hover:text-white transition shadow-xs">
                                                                <i class="fas fa-trash-alt mr-2"></i> Hapus
                                                            </button>
                                                        </form>
                                                    </div>
                                                @elseif($log->status_validasi === 'revisi')
                                                    <div class="mt-5 pt-5 border-t border-gray-100 dark:border-gray-700">
                                                        <button @click="openEditModal({{ $log->id }}, {{ Js::from(\Carbon\Carbon::parse($log->tanggal)->translatedFormat('d F Y')) }}, {{ Js::from($log->kegiatan) }}, {{ Js::from($log->status_validasi) }})" type="button" class="w-full inline-flex items-center justify-center px-4 py-3 bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60 rounded-2xl text-xs font-bold hover:bg-rose-600 hover:text-white transition shadow-xs">
                                                            <i class="fas fa-edit mr-2"></i> Perbaiki Jurnal Ini
                                                        </button>
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
        
        <!-- Modal Edit / Revisi Jurnal -->
        @include('peserta.logbook._edit-modal')

    </div>

    @push('scripts')
    @vite('resources/js/peserta/logbook.js')
    <script type="application/json" id="peserta-logbook-config">
        {!! json_encode([
            'filterStatus' => request('status', 'semua'),
            'filterDate' => request('date', ''),
            'filterMonth' => request('month', ''),
            'resetUrl' => route('peserta.logbook.index'),
            'oldLogId' => old('log_id'),
            'oldKegiatan' => old('kegiatan') ?? '',
            'oldStatusValidasi' => old('status_validasi'),
            'oldTanggal' => old('tanggal'),
        ], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}
    </script>
    @endpush
</x-app-layout>
