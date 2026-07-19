<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <i class="fas fa-check-double text-teal-600"></i>
                {{ __('Validasi Logbook') }}
            </h2>
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-500 dark:text-gray-400">Mahasiswa:</span>
                <span class="px-3 py-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full text-sm font-bold text-gray-700 dark:text-gray-300 shadow-sm">
                    {{ $app->user->name }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900/50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex justify-between items-center mb-6 print:hidden">
                <a href="{{ route('pembimbing_lapangan.dashboard') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 transition">
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

            <!-- Filter Bar -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex items-center gap-2">
                    <i class="fas fa-filter text-teal-600"></i>
                    <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Filter Logbook</span>
                </div>
                
                <form action="{{ route('pembimbing_lapangan.logbook', $app->id) }}" method="GET" class="w-full md:w-auto flex flex-wrap items-center gap-4">
                    <!-- Segmented Control for Period -->
                    <div class="bg-gray-100 dark:bg-gray-800 p-1 rounded-xl flex items-center">
                        <label class="cursor-pointer">
                            <input type="radio" name="filter_type" value="semua" {{ $filterType === 'semua' ? 'checked' : '' }} class="sr-only peer" onchange="this.form.submit()">
                            <span class="px-3 py-1.5 text-xs font-bold rounded-lg text-gray-500 dark:text-gray-400 peer-checked:bg-white dark:peer-checked:bg-gray-800 peer-checked:text-teal-600 peer-checked:shadow-sm transition block">
                                Semua
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="filter_type" value="mingguan" {{ $filterType === 'mingguan' ? 'checked' : '' }} class="sr-only peer" onchange="this.form.submit()">
                            <span class="px-3 py-1.5 text-xs font-bold rounded-lg text-gray-500 dark:text-gray-400 peer-checked:bg-white dark:peer-checked:bg-gray-800 peer-checked:text-teal-600 peer-checked:shadow-sm transition block">
                                Mingguan
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="filter_type" value="bulanan" {{ $filterType === 'bulanan' ? 'checked' : '' }} class="sr-only peer" onchange="this.form.submit()">
                            <span class="px-3 py-1.5 text-xs font-bold rounded-lg text-gray-500 dark:text-gray-400 peer-checked:bg-white dark:peer-checked:bg-gray-800 peer-checked:text-teal-600 peer-checked:shadow-sm transition block">
                                Bulanan
                            </span>
                        </label>
                    </div>

                    @if($filterType !== 'semua')
                        <!-- Date Picker -->
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-gray-500 dark:text-gray-400">
                                {{ $filterType === 'bulanan' ? 'Bulan:' : 'Tanggal:' }}
                            </span>
                            @if($filterType === 'bulanan')
                                <input type="month" name="month" value="{{ \Carbon\Carbon::parse($selectedDate)->format('Y-m') }}" 
                                    class="border-gray-200 dark:border-gray-700 rounded-xl text-xs shadow-sm focus:border-teal-500 focus:ring-teal-500 bg-gray-50 dark:bg-gray-900 text-gray-700 dark:text-gray-300 transition py-1.5 px-3"
                                    onchange="this.form.date.value = this.value + '-01'; this.form.submit();">
                                <input type="hidden" name="date" value="{{ $selectedDate }}">
                            @else
                                <input type="date" name="date" value="{{ $selectedDate }}" onchange="this.form.submit()" 
                                    class="border-gray-255 rounded-xl text-xs shadow-sm focus:border-teal-500 focus:ring-teal-500 bg-gray-50 dark:bg-gray-900 text-gray-700 dark:text-gray-300 transition py-1.5 px-3">
                            @endif
                        </div>
                    @endif

                    @if(request('filter_type') && request('filter_type') != 'semua')
                        <a href="{{ route('pembimbing_lapangan.logbook', $app->id) }}" class="p-2 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white rounded-xl transition text-xs font-bold flex items-center gap-1.5" title="Reset Filter">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    @endif
                </form>
            </div>

            @if($logs->isEmpty())
                <div class="flex flex-col items-center justify-center py-16 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="w-20 h-20 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-book-open text-3xl text-gray-300"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-700 dark:text-gray-300">Logbook Kosong</h3>
                    @if($filterType !== 'semua')
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Tidak ada aktivitas logbook pada periode yang dipilih.</p>
                        <a href="{{ route('pembimbing_lapangan.logbook', $app->id) }}" class="mt-4 px-4 py-2 bg-teal-50 text-teal-700 font-bold rounded-xl text-xs hover:bg-teal-100 transition shadow-sm">Reset Filter</a>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Mahasiswa ini belum mengunggah aktivitas apapun.</p>
                    @endif
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start" 
                     x-data="{ activeTab: {{ session('last_id') ?? $logs->first()->id }} }">
                    
                    <div class="md:col-span-4 col-span-1">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden sticky top-8">
                            <div class="p-4 border-b border-gray-50 bg-gray-50 dark:bg-gray-900/50 flex justify-between items-center">
                                <h3 class="font-bold text-gray-700 dark:text-gray-300 text-sm">Riwayat Aktivitas</h3>
                                <span class="text-[10px] font-bold bg-gray-200 text-gray-600 dark:text-gray-400 px-2 py-0.5 rounded-full">{{ $logs->count() }}</span>
                            </div>
                            
                            <form action="{{ route('pembimbing_lapangan.logbook.batch_validasi') }}" method="POST">
                                @csrf
                                <div class="max-h-[60vh] overflow-y-auto custom-scrollbar">
                                    <ul class="divide-y divide-gray-50">
                                        @foreach($logs as $log)
                                        <li class="flex items-center pr-2 hover:bg-gray-50 dark:hover:bg-gray-900 transition duration-150 group">
                                            @if($log->status_validasi != 'disetujui')
                                                <div class="pl-4 pr-1">
                                                    <input type="checkbox" name="log_ids[]" value="{{ $log->id }}" class="rounded border-gray-300 dark:border-gray-600 text-teal-600 focus:ring-teal-500 cursor-pointer">
                                                </div>
                                            @else
                                                <div class="pl-4 pr-1 opacity-0 w-5"></div>
                                            @endif

                                            <button type="button"
                                                @click="activeTab = {{ $log->id }}"
                                                :class="{ 'bg-teal-50 border-l-4 border-teal-500': activeTab === {{ $log->id }}, 'border-l-4 border-transparent': !== }} }"
                                                class="w-full text-left px-3 py-3 focus:outline-none">
                                                
                                                <div class="flex justify-between items-start mb-1">
                                                    <span class="text-sm font-bold text-gray-800 dark:text-gray-200" 
                                                          :class="{ 'text-teal-700': activeTab === {{ $log->id }} }">
                                                        {{ \Carbon\Carbon::parse($log->tanggal)->format('d M Y') }}
                                                    </span>
                                                    
                                                    @if($log->status_validasi == 'disetujui')
                                                        <i class="fas fa-check-circle text-green-500 text-xs" title="Disetujui"></i>
                                                    @elseif($log->status_validasi == 'revisi')
                                                        <i class="fas fa-exclamation-circle text-red-500 text-xs" title="Revisi"></i>
                                                    @else
                                                        <div class="w-2 h-2 rounded-full bg-yellow-400 mt-1.5" title="Pending"></div>
                                                    @endif
                                                </div>
                                                
                                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate group-hover:text-gray-700 dark:group-hover:text-gray-300">
                                                    {{ Str::limit($log->kegiatan, 30) }}
                                                </p>
                                            </button>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                
                                <!-- Batch Action Buttons -->
                                <div class="p-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex flex-col gap-2">
                                    <div class="flex items-center justify-between mb-1">
                                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300 flex items-center gap-1 cursor-pointer">
                                            <input type="checkbox" onchange="document.querySelectorAll('input[name=\'log_ids[]\']').forEach(c => c.checked = this.checked)" class="rounded border-gray-300 dark:border-gray-600 text-teal-600 focus:ring-teal-500">
                                            Pilih Semua
                                        </label>
                                        <span class="text-[10px] text-gray-500 dark:text-gray-400 font-medium">Validasi Massal</span>
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="submit" name="status" value="disetujui" class="flex-1 bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold py-2 rounded-lg transition shadow-sm">
                                            <i class="fas fa-check mr-1"></i> Terima
                                        </button>
                                        <button type="submit" name="status" value="revisi" class="flex-1 bg-white dark:bg-gray-800 border border-red-200 text-red-600 hover:bg-red-50 text-xs font-bold py-2 rounded-lg transition shadow-sm">
                                            <i class="fas fa-undo mr-1"></i> Revisi
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="md:col-span-8 col-span-1">
                        @foreach($logs as $log)
                        <div x-show="activeTab === {{ $log->id }}" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             style="display: none;">
                            
                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                                
                                <div class="p-6 border-b border-gray-50 flex justify-between items-start">
                                    <div>
                                        <h3 class="text-xl font-extrabold text-gray-800 dark:text-gray-200">Detail Kegiatan</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 flex items-center">
                                            <i class="far fa-calendar-alt mr-2 text-teal-500"></i> 
                                            {{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('l, d F Y') }}
                                        </p>
                                    </div>
                                    
                                    @php
                                        $statusClass = match($log->status_validasi) {
                                            'disetujui' => 'bg-green-100 text-green-700 border-green-200',
                                            'revisi' => 'bg-red-100 text-red-700 border-red-200',
                                            default => 'bg-yellow-100 text-yellow-800 border-yellow-200'
                                        };
                                        $statusIcon = match($log->status_validasi) {
                                            'disetujui' => 'fa-check-circle',
                                            'revisi' => 'fa-undo',
                                            default => 'fa-clock'
                                        };
                                    @endphp
                                    <span class="px-4 py-1.5 rounded-full text-xs font-bold uppercase border {{ $statusClass }} flex items-center gap-2">
                                        <i class="fas {{ $statusIcon }}"></i> {{ ucfirst($log->status_validasi) }}
                                    </span>
                                </div>

                                <div class="p-6">
                                    <div class="flex flex-col lg:flex-row gap-8">
                                        
                                        <div class="w-full lg:w-1/3 flex-shrink-0">
                                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Dokumentasi</h4>
                                            @if($log->bukti_foto_path)
                                                <div class="relative group rounded-xl overflow-hidden shadow-sm border border-gray-200 dark:border-gray-700 cursor-zoom-in" onclick="openImageModal('{{ Storage::url($log->bukti_foto_path) }}')">
                                                    <img src="{{ Storage::url($log->bukti_foto_path) }}" class="w-full h-48 object-cover transition transform group-hover:scale-105 duration-500">
                                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition"></div>
                                                </div>
                                                <p class="text-[10px] text-gray-400 mt-2 text-center">Klik gambar untuk memperbesar</p>
                                            @else
                                                <div class="w-full h-40 bg-gray-50 dark:bg-gray-900 rounded-xl flex flex-col items-center justify-center text-gray-400 text-xs border-2 border-dashed border-gray-200 dark:border-gray-700">
                                                    <i class="far fa-image text-2xl mb-1"></i>
                                                    <span>Tidak ada foto</span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="w-full lg:w-2/3">
                                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Deskripsi Pekerjaan</h4>
                                            <div class="p-5 bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm leading-relaxed whitespace-pre-line min-h-[10rem]">
                                                {{ $log->kegiatan }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700">
                                        
                                        @if($log->komentar_pembimbing_lapangan)
                                            <div class="mb-6 p-4 bg-blue-50 rounded-xl border border-blue-100 flex gap-3 items-start">
                                                <i class="fas fa-comment-dots text-blue-500 mt-1"></i>
                                                <div>
                                                    <span class="block text-xs font-bold text-blue-700 uppercase mb-1">Catatan Anda Sebelumnya:</span>
                                                    <p class="text-sm text-blue-900 italic">"{{ $log->komentar_pembimbing_lapangan }}"</p>
                                                </div>
                                            </div>
                                        @endif

                                        @if($log->status_validasi != 'disetujui_permanen') 
                                        <form action="{{ route('pembimbing_lapangan.logbook.validasi', $log->id) }}" method="POST" class="bg-gray-50 dark:bg-gray-900 p-5 rounded-2xl border border-gray-200 dark:border-gray-700">
                                                @csrf
                                                <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200 mb-3 flex items-center gap-2">
                                                    <i class="fas fa-pen-nib text-teal-600"></i> Berikan Validasi & Catatan
                                                </h4>
                                                
                                                <div class="flex flex-col sm:flex-row gap-3">
                                                    <input type="text" name="komentar" 
                                                        class="flex-grow rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-teal-500 focus:ring-teal-500 text-sm" 
                                                        placeholder="Tulis catatan revisi atau apresiasi (Opsional)..."
                                                        value="{{ $log->status_validasi == 'revisi' ? $log->komentar_pembimbing_lapangan : '' }}">
                                                    
                                                    <div class="flex gap-2 flex-shrink-0">
                                                        <button type="submit" name="status" value="disetujui" 
                                                            class="bg-teal-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-teal-700 transition shadow-sm flex items-center gap-2">
                                                            <i class="fas fa-check"></i> Terima
                                                        </button>
                                                        
                                                        <button type="submit" name="status" value="revisi" 
                                                            class="bg-white dark:bg-gray-800 text-red-600 border border-red-200 px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-red-50 transition shadow-sm flex items-center gap-2">
                                                            <i class="fas fa-undo"></i> Revisi
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        @else
                                            <div class="text-center py-4">
                                                <p class="text-sm text-gray-400 italic">Logbook ini telah disetujui.</p>
                                            </div>
                                        @endif

                                    </div>
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
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
    </style>
</x-app-layout>