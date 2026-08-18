<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-950/60 flex items-center justify-center border border-teal-200 dark:border-teal-800/60">
                    <i class="fas fa-clipboard-list text-teal-600 dark:text-teal-400 text-lg"></i>
                </div>
                {{ __('Pemantauan Absensi Mahasiswa') }}
            </h2>
            <x-pembimbing.student-switcher :applications="$applications" :current="$app" route-name="pembimbing.peserta.absensi" />
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

            @php
                $isFiltered = request()->hasAny(['search', 'status'])
                    || (request()->has('filter_type') && request('filter_type') !== 'semua')
                    || (request()->has('date') && request('date') !== date('Y-m-d'));
            @endphp

            {{-- Filter Bar --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 print:hidden space-y-5">
                <x-pembimbing.filter-bar
                    route-name="pembimbing.peserta.absensi"
                    :app="$app"
                    :applications="$applications"
                    :filter-type="$filterType"
                    :selected-date="$selectedDate"
                    :is-filtered="$isFiltered"
                    title="Filter Pemantauan Absensi"
                    subtitle="Filter data absensi mahasiswa berdasarkan rentang waktu, status, atau pencarian"
                    search-label="Cari Catatan:"
                    search-placeholder="Cari keterangan..."
                    status-field="status"
                    :status-options="[
                        ['value' => 'hadir', 'label' => 'Hadir'],
                        ['value' => 'izin', 'label' => 'Izin'],
                        ['value' => 'sakit', 'label' => 'Sakit'],
                        ['value' => 'alpa', 'label' => 'Alpa'],
                    ]"
                    status-badge-label="Status"
                />
            </div>

            {{-- Main Table Container --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900">
                    <h3 class="text-base font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <i class="fas fa-calendar-check text-teal-600 dark:text-teal-400"></i> Rekap Kehadiran
                    </h3>
                    <span class="bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 text-xs font-black px-3 py-1 rounded-full border border-teal-200 dark:border-teal-800/60">
                        Jumlah Catatan: {{ $attendances->count() }}
                    </span>
                </div>
                
                <div>
                    @if($attendances->isEmpty())
                        <div class="p-16 text-center text-gray-400 dark:text-gray-500">
                            <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mx-auto mb-3 border border-gray-200 dark:border-gray-700">
                                <i class="far fa-clipboard text-3xl text-gray-400 dark:text-gray-500"></i>
                            </div>
                            <p class="font-bold text-gray-700 dark:text-gray-300 text-sm">Belum Ada Rekaman Absensi</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Belum ada catatan kehadiran untuk mahasiswa ini pada periode yang dipilih.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full divide-y divide-gray-100 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jam Masuk</th>
                                        <th class="px-6 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jam Pulang</th>
                                        <th class="px-6 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Catatan / Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700/60 text-sm">
                                    @foreach($attendances as $absen)
                                        <tr class="hover:bg-teal-50/15 dark:hover:bg-teal-950/20 transition duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                                    {{ \Carbon\Carbon::parse($absen->date)->translatedFormat('l, d M Y') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                                @if($absen->clock_in)
                                                    <span class="px-3 py-1 bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-mono font-bold text-gray-800 dark:text-gray-200">
                                                        {{ \Carbon\Carbon::parse($absen->clock_in)->format('H:i') }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400 dark:text-gray-500">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                                @if($absen->clock_out)
                                                    <span class="px-3 py-1 bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-mono font-bold text-gray-800 dark:text-gray-200">
                                                        {{ \Carbon\Carbon::parse($absen->clock_out)->format('H:i') }}
                                                    </span>
                                                @else
                                                    <span class="text-xs text-rose-600 dark:text-rose-400 italic bg-rose-50 dark:bg-rose-950/60 border border-rose-100 dark:border-rose-900/40 px-2.5 py-0.5 rounded-md font-bold">Belum Pulang</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                                @php
                                                    $statusClass = match($absen->status) {
                                                        'hadir' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800/60',
                                                        'izin', 'sakit' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800/60',
                                                        'alpa' => 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800/60',
                                                        default => 'bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700'
                                                    };
                                                @endphp
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full border {{ $statusClass }}">
                                                    {{ ucfirst($absen->status) }}
                                                </span>
                                                
                                                @if($absen->validation_status == 'approved')
                                                    <div class="mt-1 flex items-center justify-center text-[10px] text-emerald-600 dark:text-emerald-400 font-bold" title="Divalidasi Pembimbing Lapangan">
                                                        <i class="fas fa-check-circle mr-1"></i> Valid
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-xs sm:text-sm text-gray-800 dark:text-gray-200 font-medium leading-relaxed max-w-xs truncate" title="{{ $absen->description }}">
                                                    {{ $absen->description ?: '-' }}
                                                </div>
                                                @if($absen->pembimbing_lapangan_note)
                                                    <div class="mt-1.5 p-2 bg-blue-50/60 dark:bg-blue-950/40 rounded-xl border border-blue-200 dark:border-blue-800/60 text-xs text-blue-900 dark:text-blue-200 italic">
                                                        <strong class="not-italic text-blue-800 dark:text-blue-300">Catatan Lapangan:</strong> {{ $absen->pembimbing_lapangan_note }}
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <x-pembimbing.pagination :paginator="$attendances" />
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
