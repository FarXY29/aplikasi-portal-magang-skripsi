<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <i class="fas fa-book-open text-teal-600"></i>
                {{ __('Pemantauan Logbook') }}
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
            
            <div class="flex justify-between items-center mb-6">
                <a href="{{ route('pembimbing.dashboard') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                    Kembali ke Dashboard
                </a>
            </div>

            <!-- Filter Bar -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex items-center gap-2">
                    <i class="fas fa-filter text-teal-600"></i>
                    <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Filter Logbook</span>
                </div>
                
                <form action="{{ route('pembimbing.peserta.logbook', $app->id) }}" method="GET" class="w-full md:w-auto flex flex-wrap items-center gap-4">
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
                                    class="border-gray-200 dark:border-gray-700 rounded-xl text-xs shadow-sm focus:border-teal-500 focus:ring-teal-500 bg-gray-50 dark:bg-gray-900 text-gray-700 dark:text-gray-300 transition py-1.5 px-3">
                            @endif
                        </div>
                    @endif

                    @if(request('filter_type') && request('filter_type') != 'semua')
                        <a href="{{ route('pembimbing.peserta.logbook', $app->id) }}" class="p-2 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white rounded-xl transition text-xs font-bold flex items-center gap-1.5">
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
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Belum ada aktivitas logbook pada periode yang dipilih.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start" x-data="{ activeTab: {{ $logs->first()->id }} }">
                    
                    <div class="md:col-span-4 col-span-1">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden sticky top-8">
                            <div class="p-4 border-b border-gray-50 bg-gray-50 dark:bg-gray-900/50 flex justify-between items-center">
                                <h3 class="font-bold text-gray-700 dark:text-gray-300 text-sm">Riwayat Aktivitas</h3>
                                <span class="text-[10px] font-bold bg-gray-200 text-gray-600 dark:text-gray-400 px-2 py-0.5 rounded-full">{{ $logs->count() }}</span>
                            </div>
                            
                            <div class="max-h-[70vh] overflow-y-auto custom-scrollbar">
                                <ul class="divide-y divide-gray-50">
                                    @foreach($logs as $log)
                                    <li>
                                        <button @click="activeTab = {{ $log->id }}"
                                            :class="{ 'bg-teal-50 border-l-4 border-teal-500': activeTab === {{ $log->id }}, 'hover:bg-gray-50 dark:hover:bg-gray-900 border-transparent': !== }} }"
                                            class="w-full text-left px-4 py-3 transition duration-150 ease-in-out focus:outline-none group">
                                            
                                            <div class="flex justify-between items-start mb-1">
                                                <span class="text-sm font-bold text-gray-800 dark:text-gray-200" :class="{ 'text-teal-700': activeTab === {{ $log->id }} }">
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
                                                {{ Str::limit($log->kegiatan, 40) }}
                                            </p>
                                        </button>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-8 col-span-1">
                        @foreach($logs as $log)
                        <div x-show="activeTab === {{ $log->id }}" style="display: none;">
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
                                                <div class="relative rounded-xl overflow-hidden shadow-sm border border-gray-200 dark:border-gray-700">
                                                    <img src="{{ route('storage.access', ['type' => 'logbook', 'filename' => basename($log->bukti_foto_path)]) }}" class="w-full h-48 object-cover">
                                                </div>
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

                                    @if($log->komentar_pembimbing_lapangan)
                                        <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700">
                                            <div class="p-4 bg-blue-50 rounded-xl border border-blue-100 flex gap-3 items-start">
                                                <i class="fas fa-comment-dots text-blue-500 mt-1"></i>
                                                <div>
                                                    <span class="block text-xs font-bold text-blue-700 uppercase mb-1">Catatan Pembimbing Lapangan:</span>
                                                    <p class="text-sm text-blue-900 italic">"{{ $log->komentar_pembimbing_lapangan }}"</p>
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
</x-app-layout>
