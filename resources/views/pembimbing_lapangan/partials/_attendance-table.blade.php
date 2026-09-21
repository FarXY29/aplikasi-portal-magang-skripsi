{{-- Card Utama Absensi --}}
<div class="w-full">
    <section class="bg-white dark:bg-gray-800 shadow-xs rounded-3xl border border-gray-100 dark:border-gray-700 overflow-hidden">
        
        <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white dark:bg-gray-800">
            <div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                    <i class="fas fa-calendar-check text-teal-600 dark:text-teal-400"></i>
                    @if($filterType === 'mingguan')
                        Rekap Absensi Mingguan
                    @elseif($filterType === 'bulanan')
                        Rekap Absensi Bulanan
                    @elseif($filterType === 'semua')
                        Rekap Semua Absensi
                    @else
                        Rekap Absensi Harian
                    @endif
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-medium">
                    @if($filterType === 'mingguan')
                        Periode: <span class="font-bold text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/60 border border-teal-200 dark:border-teal-800/60 px-2 py-0.5 rounded-md">{{ \Carbon\Carbon::parse($selectedDate)->startOfWeek()->translatedFormat('d M Y') }} — {{ \Carbon\Carbon::parse($selectedDate)->endOfWeek()->translatedFormat('d M Y') }}</span>
                    @elseif($filterType === 'bulanan')
                        Bulan: <span class="font-bold text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/60 border border-teal-200 dark:border-teal-800/60 px-2 py-0.5 rounded-md">{{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('F Y') }}</span>
                    @elseif($filterType === 'semua')
                        Rentang: <span class="font-bold text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/60 border border-teal-200 dark:border-teal-800/60 px-2 py-0.5 rounded-md">Semua Riwayat Tanggal</span>
                    @else
                        Tanggal: <span class="font-bold text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/60 border border-teal-200 dark:border-teal-800/60 px-2 py-0.5 rounded-md">{{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y') }}</span>
                    @endif
                </p>
            </div>
            
            <button onclick="window.print()" class="bg-gray-800 dark:bg-gray-700 text-white hover:bg-gray-700 dark:hover:bg-gray-600 px-4 py-2 rounded-xl text-xs font-bold shadow-xs transition flex items-center gap-2 print:hidden" aria-label="Cetak Riwayat Absensi">
                <i class="fas fa-print"></i> Cetak
            </button>
        </div>

        {{-- Desktop Table View --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full divide-y divide-gray-100 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th scope="col" class="px-5 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Peserta</th>
                        @if($filterType !== 'harian')
                            <th scope="col" class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                        @endif
                        <th scope="col" class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jam Masuk</th>
                        <th scope="col" class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jam Pulang</th>
                        <th scope="col" class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-5 py-4 text-right text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi Validasi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700/60 text-sm">
                    @forelse($attendances as $row)
                    <tr class="hover:bg-teal-50/15 dark:hover:bg-teal-950/20 transition duration-150">
                        
                        <td class="px-5 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-300 rounded-full flex items-center justify-center font-black text-xs border border-teal-200 dark:border-teal-800/60 flex-shrink-0 shadow-xs">
                                    {{ strtoupper(substr($row->application->user->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="text-sm font-bold text-gray-900 dark:text-gray-100 truncate">{{ $row->application->user->name }}</div>
                                    <div class="text-[10px] text-gray-500 dark:text-gray-400 font-semibold truncate">{{ Str::limit($row->application->position->judul_posisi ?? '-', 28) }}</div>
                                </div>
                            </div>
                        </td>

                        @if($filterType !== 'harian')
                            <td class="px-5 py-4 whitespace-nowrap text-center text-xs font-bold text-gray-700 dark:text-gray-300">
                                {{ \Carbon\Carbon::parse($row->date)->translatedFormat('d M Y') }}
                            </td>
                        @endif
                        
                        <td class="px-5 py-4 whitespace-nowrap text-center">
                            @if($row->status == 'hadir')
                                <span class="text-xs font-mono font-bold text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 px-2.5 py-1 rounded-md">
                                    {{ \Carbon\Carbon::parse($row->clock_in)->format('H:i') }}
                                </span>
                            @else
                                <span class="text-gray-400 dark:text-gray-500">-</span>
                            @endif
                        </td>

                        <td class="px-5 py-4 whitespace-nowrap text-center">
                            @if($row->status == 'hadir')
                                @if($row->clock_out)
                                    <span class="text-xs font-mono font-bold text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 px-2.5 py-1 rounded-md">
                                        {{ \Carbon\Carbon::parse($row->clock_out)->format('H:i') }}
                                    </span>
                                @else
                                    <span class="text-[10px] text-rose-600 dark:text-rose-400 italic bg-rose-50 dark:bg-rose-950/60 border border-rose-100 dark:border-rose-900/40 px-2 py-0.5 rounded-md font-bold">Belum Absen</span>
                                @endif
                            @else
                                <span class="text-gray-400 dark:text-gray-500">-</span>
                            @endif
                        </td>

                        <td class="px-5 py-4 whitespace-nowrap text-center">
                            @php
                                $statusStyles = [
                                    'hadir' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800/60',
                                    'sakit' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800/60',
                                    'izin' => 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800/60',
                                    'alpa' => 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800/60',
                                ];
                                $statusVal = $row->status instanceof \App\Enums\AttendanceStatus ? $row->status->value : $row->status;
                                $style = $statusStyles[$statusVal] ?? 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 border-gray-200 dark:border-gray-700';
                                $isPending = ($statusVal != 'hadir' && ($row->validation_status instanceof \App\Enums\ValidationStatus ? $row->validation_status->value : $row->validation_status) == 'pending');
                            @endphp

                            <div class="relative inline-block">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full border {{ $style }}">
                                    {{ ucfirst($statusVal) }}
                                </span>
                                @if($isPending)
                                    <span class="absolute -top-1 -right-2 flex h-3 w-3">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500"></span>
                                    </span>
                                @endif
                            </div>
                        </td>

                        <td class="px-5 py-4 whitespace-nowrap text-right">
                            @if($row->status == 'hadir')
                                <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider flex items-center justify-end gap-1">
                                    <i class="fas fa-check-double text-teal-500"></i> Auto
                                </span>
                            @else
                                @if($row->validation_status == 'pending')
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'modal-bukti-{{ $row->id }}')" 
                                            class="p-1.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-xl hover:bg-teal-50 dark:hover:bg-teal-900/50 hover:text-teal-600 transition border border-gray-200 dark:border-gray-600" title="Lihat Bukti" aria-label="Lihat Bukti">
                                            <i class="fas fa-eye text-xs"></i>
                                        </button>

                                        <form action="{{ route('pembimbing_lapangan.attendance.validate', $row->id) }}" method="POST" class="inline" onsubmit="event.submitter && (event.submitter.disabled = true)">
                                            @csrf
                                            <input type="hidden" name="status_validasi" value="approved">
                                            <button type="submit" class="p-1.5 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 rounded-xl border border-emerald-200 dark:border-emerald-800/60 hover:bg-emerald-100 transition" title="Setujui" aria-label="Setujui">
                                                <i class="fas fa-check text-xs"></i>
                                            </button>
                                        </form>

                                        <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'modal-tolak-{{ $row->id }}')" 
                                            class="p-1.5 bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 rounded-xl border border-rose-200 dark:border-rose-800/60 hover:bg-rose-100 transition" title="Tolak" aria-label="Tolak">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="flex items-center justify-end gap-2">
                                        @if($row->proof_file)
                                            <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'modal-bukti-{{ $row->id }}')" class="text-xs text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 underline decoration-dashed font-bold">
                                                Detail
                                            </button>
                                        @endif
                                        @if($row->validation_status == 'approved')
                                            <span class="text-xs text-emerald-600 dark:text-emerald-400 font-bold flex items-center gap-1"><i class="fas fa-check-circle"></i> Valid</span>
                                        @else
                                            <span class="text-xs text-rose-600 dark:text-rose-400 font-bold flex items-center gap-1"><i class="fas fa-times-circle"></i> Ditolak</span>
                                        @endif
                                    </div>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $filterType !== 'harian' ? 6 : 5 }}" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                <div class="h-16 w-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-3 border border-gray-200 dark:border-gray-700">
                                    <i class="fas fa-clipboard-check text-3xl text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <p class="font-bold text-gray-700 dark:text-gray-300 text-sm">Data absensi kosong</p>
                                <p class="text-xs mt-1 text-gray-500 dark:text-gray-400">Tidak ada data absensi untuk tanggal/periode yang dipilih.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Card View (< md) --}}
        <div class="md:hidden divide-y divide-gray-100 dark:divide-gray-700/60">
            @forelse($attendances as $row)
            @php
                $statusStyles = [
                    'hadir' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800/60',
                    'sakit' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800/60',
                    'izin' => 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800/60',
                    'alpa' => 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800/60',
                ];
                $statusVal = $row->status instanceof \App\Enums\AttendanceStatus ? $row->status->value : $row->status;
                $style = $statusStyles[$statusVal] ?? 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 border-gray-200 dark:border-gray-700';
                $isPending = ($statusVal != 'hadir' && ($row->validation_status instanceof \App\Enums\ValidationStatus ? $row->validation_status->value : $row->validation_status) == 'pending');
            @endphp
            <article class="p-5 space-y-3.5 hover:bg-teal-50/10 dark:hover:bg-teal-950/20 transition">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-300 flex items-center justify-center font-black text-xs border border-teal-200 dark:border-teal-800/60 shrink-0 shadow-xs">
                            {{ strtoupper(substr($row->application->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $row->application->user->name }}</div>
                            <div class="text-[11px] text-gray-500 dark:text-gray-400 font-semibold">{{ Str::limit($row->application->position->judul_posisi ?? '-', 30) }}</div>
                        </div>
                    </div>
                    <div class="relative">
                        <span class="px-2.5 py-0.5 inline-flex text-[10px] font-bold rounded-full border {{ $style }}">
                            {{ ucfirst($statusVal) }}
                        </span>
                        @if($isPending)
                            <span class="absolute -top-1 -right-1 flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-rose-500"></span>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-2xl border border-gray-100 dark:border-gray-700 text-xs space-y-2">
                    <div class="flex items-center justify-between text-gray-600 dark:text-gray-400 font-medium">
                        <span class="flex items-center gap-1.5 font-bold">
                            <i class="far fa-calendar-alt text-teal-600 dark:text-teal-400"></i>
                            {{ \Carbon\Carbon::parse($row->date)->translatedFormat('d M Y') }}
                        </span>
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-gray-800 dark:text-gray-200">
                                {{ $row->clock_in ? \Carbon\Carbon::parse($row->clock_in)->format('H:i') : '-' }}
                            </span>
                            <span>→</span>
                            <span class="font-mono text-gray-800 dark:text-gray-200">
                                {{ $row->clock_out ? \Carbon\Carbon::parse($row->clock_out)->format('H:i') : '-' }}
                            </span>
                        </div>
                    </div>

                    @if($row->description)
                        <div class="pt-1.5 border-t border-gray-200 dark:border-gray-700/60 text-[11px] text-gray-700 dark:text-gray-300 italic">
                            "{{ Str::limit($row->description, 60) }}"
                        </div>
                    @endif
                </div>

                {{-- Action Buttons for Mobile --}}
                <div class="flex items-center justify-end gap-2 pt-1">
                    @if($row->status == 'hadir')
                        <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider flex items-center gap-1">
                            <i class="fas fa-check-double text-teal-500"></i> Auto Valid
                        </span>
                    @else
                        @if($row->validation_status == 'pending')
                            @if($row->proof_file)
                                <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'modal-bukti-{{ $row->id }}')" 
                                    class="py-1.5 px-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl text-xs font-bold hover:bg-teal-50 dark:hover:bg-teal-900/50 hover:text-teal-600 transition border border-gray-200 dark:border-gray-600 flex items-center gap-1">
                                    <i class="fas fa-eye text-xs"></i> Bukti
                                </button>
                            @endif

                            <form action="{{ route('pembimbing_lapangan.attendance.validate', $row->id) }}" method="POST" class="inline" onsubmit="event.submitter && (event.submitter.disabled = true)">
                                @csrf
                                <input type="hidden" name="status_validasi" value="approved">
                                <button type="submit" class="py-1.5 px-3 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 rounded-xl border border-emerald-200 dark:border-emerald-800/60 hover:bg-emerald-100 text-xs font-bold transition flex items-center gap-1">
                                    <i class="fas fa-check text-xs"></i> Setujui
                                </button>
                            </form>

                            <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'modal-tolak-{{ $row->id }}')" 
                                class="py-1.5 px-3 bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 rounded-xl border border-rose-200 dark:border-rose-800/60 hover:bg-rose-100 text-xs font-bold transition flex items-center gap-1">
                                <i class="fas fa-times text-xs"></i> Tolak
                            </button>
                        @else
                            @if($row->proof_file)
                                <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'modal-bukti-{{ $row->id }}')" class="text-xs text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 underline decoration-dashed font-bold mr-2">
                                    Lihat Detail
                                </button>
                            @endif
                            @if($row->validation_status == 'approved')
                                <span class="text-xs text-emerald-600 dark:text-emerald-400 font-bold flex items-center gap-1"><i class="fas fa-check-circle"></i> Valid</span>
                            @else
                                <span class="text-xs text-rose-600 dark:text-rose-400 font-bold flex items-center gap-1"><i class="fas fa-times-circle"></i> Ditolak</span>
                            @endif
                        @endif
                    @endif
                </div>
            </article>
            @empty
            <div class="p-8 text-center text-gray-400">
                <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mx-auto mb-3 border border-gray-200 dark:border-gray-700">
                    <i class="fas fa-clipboard-check text-3xl text-gray-400 dark:text-gray-500"></i>
                </div>
                <p class="font-bold text-gray-600 dark:text-gray-400">Data absensi kosong</p>
                <p class="text-xs mt-1">Tidak ada data absensi untuk tanggal/periode yang dipilih.</p>
            </div>
            @endforelse
        </div>
        
        @if($attendances instanceof \Illuminate\Pagination\LengthAwarePaginator && $attendances->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
            {{ $attendances->links() }}
        </div>
        @endif

    </section>
</div>
