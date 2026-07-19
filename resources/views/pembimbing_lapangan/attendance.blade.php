<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Monitoring Absensi Mahasiswa') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between mb-6 print:hidden">
                <a href="{{ route('pembimbing_lapangan.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-indigo-600 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
                </a>
            </div>

            <div class="flex flex-col lg:flex-row gap-8 items-start">
                
                <div class="w-full lg:w-1/4 flex-shrink-0 print:hidden">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 sticky top-8">
                        <div class="mb-5 pb-3 border-b border-gray-50">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                                <i class="fas fa-filter"></i> Filter Data
                            </h3>
                        </div>

                        <form action="{{ route('pembimbing_lapangan.attendance.index') }}" method="GET">
                            
                            <!-- Filter Type Segmented Control -->
                            <div class="mb-5">
                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase mb-2">Rentang Waktu</label>
                                <div class="grid grid-cols-3 gap-1 bg-gray-100 dark:bg-gray-800 p-1 rounded-xl">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="filter_type" value="harian" {{ $filterType === 'harian' ? 'checked' : '' }} class="sr-only peer" onchange="this.form.submit()">
                                        <span class="block text-center text-[10px] font-bold py-1.5 rounded-lg text-gray-500 dark:text-gray-400 peer-checked:bg-white dark:peer-checked:bg-gray-800 peer-checked:text-indigo-600 peer-checked:shadow-sm transition">
                                            Harian
                                        </span>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="filter_type" value="mingguan" {{ $filterType === 'mingguan' ? 'checked' : '' }} class="sr-only peer" onchange="this.form.submit()">
                                        <span class="block text-center text-[10px] font-bold py-1.5 rounded-lg text-gray-500 dark:text-gray-400 peer-checked:bg-white dark:peer-checked:bg-gray-800 peer-checked:text-indigo-600 peer-checked:shadow-sm transition">
                                            Mingguan
                                        </span>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="filter_type" value="bulanan" {{ $filterType === 'bulanan' ? 'checked' : '' }} class="sr-only peer" onchange="this.form.submit()">
                                        <span class="block text-center text-[10px] font-bold py-1.5 rounded-lg text-gray-500 dark:text-gray-400 peer-checked:bg-white dark:peer-checked:bg-gray-800 peer-checked:text-indigo-600 peer-checked:shadow-sm transition">
                                            Bulanan
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <div class="mb-5">
                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase mb-2">
                                    @if($filterType === 'bulanan')
                                        Pilih Bulan
                                    @else
                                        Pilih Tanggal
                                    @endif
                                </label>
                                @if($filterType === 'bulanan')
                                    <input type="month" name="month" value="{{ \Carbon\Carbon::parse($selectedDate)->format('Y-m') }}" 
                                        class="w-full border-gray-250 rounded-xl text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 dark:bg-gray-900 text-gray-700 dark:text-gray-300 transition"
                                        onchange="this.form.date.value = this.value + '-01'; this.form.submit();">
                                    <input type="hidden" name="date" value="{{ $selectedDate }}">
                                @else
                                    <input type="date" name="date" value="{{ $selectedDate }}" 
                                        class="w-full border-gray-255 rounded-xl text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 dark:bg-gray-900 text-gray-700 dark:text-gray-300 transition">
                                    @if($filterType === 'mingguan')
                                        <p class="text-[10px] text-gray-400 mt-1.5">*Mengambil 1 minggu dari tanggal tersebut.</p>
                                    @endif
                                @endif
                            </div>

                            @if($filterType === 'harian')
                            <div class="mb-6">
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Pilihan Cepat</label>
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach($dateList->take(4) as $dateItem)
                                        @php
                                            $isActive = $dateItem->format('Y-m-d') == $selectedDate;
                                            $activeClass = $isActive ? 'bg-indigo-600 text-white border-indigo-600 shadow-md transform scale-105' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 border-gray-200 dark:border-gray-700 hover:border-indigo-300 hover:text-indigo-600';
                                        @endphp
                                        <a href="{{ route('pembimbing_lapangan.attendance.index', ['date' => $dateItem->format('Y-m-d'), 'filter_type' => 'harian']) }}" 
                                           class="text-xs text-center py-2 px-1 rounded-lg border transition duration-200 font-bold {{ $activeClass }}">
                                            {{ $dateItem->isToday() ? 'HARI INI' : $dateItem->translatedFormat('D, d M') }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-4 rounded-xl shadow-lg shadow-indigo-200 transition transform active:scale-95 text-sm flex items-center justify-center gap-2">
                                <i class="fas fa-search"></i> Terapkan
                            </button>
                            
                            @if(request('date') && request('date') != date('Y-m-d') || request('filter_type') && request('filter_type') != 'harian')
                                <a href="{{ route('pembimbing_lapangan.attendance.index') }}" class="block text-center mt-4 text-xs text-gray-400 hover:text-red-500 transition">
                                    <i class="fas fa-times"></i> Reset Filter
                                </a>
                            @endif
                        </form>
                    </div>
                </div>

                <div class="w-full lg:w-3/4">
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                        
                        <div class="p-6 border-b border-gray-50 flex flex-col md:flex-row justify-between items-center gap-4 bg-white dark:bg-gray-800">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">
                                    @if($filterType === 'mingguan')
                                        Rekap Mingguan
                                    @elseif($filterType === 'bulanan')
                                        Rekap Bulanan
                                    @else
                                        Rekap Harian
                                    @endif
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    @if($filterType === 'mingguan')
                                        Periode: <span class="font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">{{ \Carbon\Carbon::parse($selectedDate)->startOfWeek()->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($selectedDate)->endOfWeek()->translatedFormat('d M Y') }}</span>
                                    @elseif($filterType === 'bulanan')
                                        Bulan: <span class="font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">{{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('F Y') }}</span>
                                    @else
                                        Tanggal: <span class="font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">{{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y') }}</span>
                                    @endif
                                </p>
                            </div>
                            
                            <button onclick="window.print()" class="bg-gray-800 text-white px-4 py-2 rounded-xl text-sm font-bold shadow hover:bg-gray-700 transition flex items-center gap-2 print:hidden">
                                <i class="fas fa-print"></i> Cetak
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-50">
                                <thead class="bg-gray-50 dark:bg-gray-900/50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Peserta</th>
                                        @if($filterType !== 'harian')
                                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal</th>
                                        @endif
                                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Jam Masuk</th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Jam Pulang</th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi Validasi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-50">
                                    @forelse($attendances as $row)
                                    <tr class="hover:bg-indigo-50/30 transition duration-150">
                                        
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-indigo-500 to-purple-600 text-white rounded-full flex items-center justify-center font-bold text-sm shadow-sm">
                                                    {{ substr($row->application->user->name, 0, 1) }}
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $row->application->user->name }}</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ Str::limit($row->application->position->judul_posisi, 25) }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        @if($filterType !== 'harian')
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-gray-600 dark:text-gray-400">
                                                {{ \Carbon\Carbon::parse($row->date)->translatedFormat('d M Y') }}
                                            </td>
                                        @endif
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($row->status == 'hadir')
                                                <span class="text-sm font-mono font-bold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded">
                                                    {{ \Carbon\Carbon::parse($row->clock_in)->format('H:i') }}
                                                </span>
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($row->status == 'hadir')
                                                @if($row->clock_out)
                                                    <span class="text-sm font-mono font-bold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded">
                                                        {{ \Carbon\Carbon::parse($row->clock_out)->format('H:i') }}
                                                    </span>
                                                @else
                                                    <span class="text-[10px] text-red-500 italic bg-red-50 px-2 py-1 rounded">Belum Absen</span>
                                                @endif
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @php
                                                $statusStyles = [
                                                    'hadir' => 'bg-green-100 text-green-700 border-green-200',
                                                    'sakit' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                                    'izin' => 'bg-blue-100 text-blue-700 border-blue-200',
                                                ];
                                                $statusVal = $row->status instanceof \App\Enums\AttendanceStatus ? $row->status->value : $row->status;
                                                $style = $statusStyles[$statusVal] ?? 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400';
                                                
                                                // Cek Pending Validation
                                                $isPending = ($statusVal != 'hadir' && ($row->validation_status instanceof \App\Enums\ValidationStatus ? $row->validation_status->value : $row->validation_status) == 'pending');
                                            @endphp

                                            <div class="relative inline-block">
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full border {{ $style }}">
                                                    {{ ucfirst($statusVal) }}
                                                </span>
                                                @if($isPending)
                                                    <span class="absolute -top-1 -right-2 flex h-3 w-3">
                                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                                        <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                                                    </span>
                                                @endif
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            @if($row->status == 'hadir')
                                                <span class="text-xs font-bold text-gray-300 uppercase tracking-wider flex items-center justify-end gap-1">
                                                    <i class="fas fa-check-double"></i> Auto
                                                </span>
                                            @else
                                                @if($row->validation_status == 'pending')
                                                    <div class="flex items-center justify-end gap-2">
                                                        <button x-data="" x-on:click="$dispatch('open-modal', 'modal-bukti-{{ $row->id }}')" 
                                                            class="p-1.5 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 rounded hover:bg-indigo-100 hover:text-indigo-600 transition" title="Lihat Bukti">
                                                            <i class="fas fa-eye"></i>
                                                        </button>

                                                        <form action="{{ route('pembimbing_lapangan.attendance.validate', $row->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            <input type="hidden" name="status_validasi" value="approved">
                                                            <button type="submit" class="p-1.5 bg-green-100 text-green-600 rounded hover:bg-green-200 transition" title="Setujui">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>

                                                        <button x-data="" x-on:click="$dispatch('open-modal', 'modal-tolak-{{ $row->id }}')" 
                                                            class="p-1.5 bg-red-100 text-red-600 rounded hover:bg-red-200 transition" title="Tolak">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                @else
                                                    <div class="flex items-center justify-end gap-2">
                                                        <button x-data="" x-on:click="$dispatch('open-modal', 'modal-bukti-{{ $row->id }}')" class="text-xs text-gray-400 hover:text-indigo-600 underline decoration-dashed">
                                                            Detail
                                                        </button>
                                                        @if($row->validation_status == 'approved')
                                                            <span class="text-xs text-green-600 font-bold flex items-center gap-1"><i class="fas fa-check-circle"></i> Valid</span>
                                                        @else
                                                            <span class="text-xs text-red-500 font-bold flex items-center gap-1"><i class="fas fa-times-circle"></i> Ditolak</span>
                                                        @endif
                                                    </div>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>

                                    @if($row->proof_file)
                                    <x-modal name="modal-bukti-{{ $row->id }}" focusable>
                                        <div class="p-6">
                                            <div class="flex justify-between items-center mb-4 border-b pb-2">
                                                <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                                                    <i class="fas fa-file-medical text-indigo-500"></i> Bukti Pengajuan {{ ucfirst($row->status) }}
                                                </h2>
                                                <button x-on:click="$dispatch('close')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-400"><i class="fas fa-times"></i></button>
                                            </div>
                                            
                                            <div class="flex justify-center bg-gray-100 dark:bg-gray-800 rounded-xl p-2 mb-4 border border-gray-200 dark:border-gray-700">
                                                <img src="{{ asset('storage/' . $row->proof_file) }}" class="max-h-[60vh] rounded shadow-sm hover:scale-105 transition duration-300" alt="Bukti">
                                            </div>
                                            
                                            <div class="bg-indigo-50 p-4 rounded-xl border border-indigo-100">
                                                <p class="text-[10px] text-indigo-500 font-bold uppercase mb-1">Keterangan Mahasiswa</p>
                                                <p class="text-gray-800 dark:text-gray-200 text-sm italic font-medium">"{{ $row->description }}"</p>
                                            </div>
                                            
                                            <div class="mt-6 flex justify-end">
                                                <x-secondary-button x-on:click="$dispatch('close')">Tutup</x-secondary-button>
                                            </div>
                                        </div>
                                    </x-modal>
                                    @endif

                                    <x-modal name="modal-tolak-{{ $row->id }}" focusable>
                                        <div class="p-6">
                                            <h2 class="text-lg font-bold text-red-600 mb-4 border-b pb-2 flex items-center gap-2">
                                                <i class="fas fa-user-times"></i> Tolak Pengajuan
                                            </h2>
                                            <form action="{{ route('pembimbing_lapangan.attendance.validate', $row->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status_validasi" value="rejected">
                                                
                                                <div class="mb-4">
                                                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Alasan Penolakan</label>
                                                    <textarea name="pembimbing_lapangan_note" rows="3" class="w-full border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" required placeholder="Contoh: Bukti surat dokter tidak terbaca..."></textarea>
                                                </div>
                                                
                                                <div class="mt-6 flex justify-end gap-3">
                                                    <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                                                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold text-sm shadow transition">
                                                        Konfirmasi Tolak
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </x-modal>

                                    @empty
                                    <tr>
                                        <td colspan="{{ $filterType !== 'harian' ? 6 : 5 }}" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center text-gray-400">
                                                <div class="h-16 w-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-3">
                                                    <i class="fas fa-clipboard-check text-3xl text-gray-300"></i>
                                                </div>
                                                <p class="font-medium text-gray-600 dark:text-gray-400">Data absensi kosong</p>
                                                <p class="text-xs mt-1">Tidak ada data untuk tanggal/periode yang dipilih.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        @if($attendances instanceof \Illuminate\Pagination\LengthAwarePaginator && $attendances->hasPages())
                        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                            {{ $attendances->links() }}
                        </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>