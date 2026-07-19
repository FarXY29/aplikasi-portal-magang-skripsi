<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <i class="fas fa-clipboard-list text-teal-600"></i>
                {{ __('Pemantauan Absensi') }}
            </h2>
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-500 dark:text-gray-400">Mahasiswa:</span>
                <span class="px-3 py-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full text-sm font-bold text-gray-700 dark:text-gray-300 shadow-sm">
                    {{ $app->user->name }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900/50 min-h-screen">
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
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <div class="flex items-center gap-2">
                    <i class="fas fa-filter text-teal-600"></i>
                    <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Filter Absensi</span>
                </div>
                
                <form action="{{ route('pembimbing.peserta.absensi', $app->id) }}" method="GET" class="w-full md:w-auto flex flex-wrap items-center gap-4">
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
                        <a href="{{ route('pembimbing.peserta.absensi', $app->id) }}" class="p-2 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white rounded-xl transition text-xs font-bold flex items-center gap-1.5">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    @endif
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 dark:border-gray-700">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200"><i class="fas fa-calendar-check text-teal-600 mr-2"></i> Rekap Kehadiran</h3>
                    <span class="bg-teal-50 text-teal-700 text-xs font-bold px-3 py-1 rounded-full border border-teal-100">
                        Total Hari: {{ $attendances->count() }}
                    </span>
                </div>
                
                <div class="p-0">
                    @if($attendances->isEmpty())
                        <div class="p-12 text-center text-gray-500 dark:text-gray-400">
                            <i class="fas fa-clipboard text-4xl text-gray-300 mb-4"></i>
                            <p>Belum ada rekaman absensi dari mahasiswa ini.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jam Masuk</th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jam Pulang</th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Catatan/Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100">
                                    @foreach($attendances as $absen)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                                    {{ \Carbon\Carbon::parse($absen->date)->translatedFormat('l, d M Y') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                                @if($absen->clock_in)
                                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($absen->clock_in)->format('H:i') }}</span>
                                                @else
                                                    <span class="text-sm text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                                @if($absen->clock_out)
                                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($absen->clock_out)->format('H:i') }}</span>
                                                @else
                                                    <span class="text-sm text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                                @php
                                                    $statusClass = match($absen->status) {
                                                        'hadir' => 'bg-green-100 text-green-800 border-green-200',
                                                        'izin', 'sakit' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                        'alpa' => 'bg-red-100 text-red-800 border-red-200',
                                                        default => 'bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-700'
                                                    };
                                                @endphp
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full border {{ $statusClass }}">
                                                    {{ ucfirst($absen->status) }}
                                                </span>
                                                
                                                @if($absen->validation_status == 'valid')
                                                    <div class="mt-1 flex items-center justify-center text-[10px] text-green-600 font-bold" title="Divalidasi Pembimbing Lapangan">
                                                        <i class="fas fa-check-circle mr-1"></i> Valid
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900 dark:text-gray-100 truncate max-w-xs" title="{{ $absen->description }}">
                                                    {{ $absen->description ?: '-' }}
                                                </div>
                                                @if($absen->pembimbing_lapangan_note)
                                                    <div class="text-xs text-blue-600 mt-1 italic">
                                                        Note Lapangan: {{ $absen->pembimbing_lapangan_note }}
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
