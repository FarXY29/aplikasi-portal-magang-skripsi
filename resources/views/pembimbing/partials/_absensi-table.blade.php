@props(['attendances' => []])

{{-- Attendance Table --}}
<div class="overflow-x-auto">
    <table class="w-full divide-y divide-gray-100 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-900">
            <tr>
                <th scope="col" class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                <th scope="col" class="px-6 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jam Masuk</th>
                <th scope="col" class="px-6 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jam Pulang</th>
                <th scope="col" class="px-6 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                <th scope="col" class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Catatan / Keterangan</th>
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
