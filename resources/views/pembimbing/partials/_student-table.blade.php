@props(['applications' => []])

{{-- Desktop Table View (>= md) --}}
<div class="hidden md:block overflow-x-auto">
    <table class="w-full divide-y divide-gray-100 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-900">
            <tr>
                <th scope="col" class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Mahasiswa</th>
                <th scope="col" class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Instansi Penempatan</th>
                <th scope="col" class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Periode Magang</th>
                <th scope="col" class="px-6 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi Pemantauan</th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700/60 text-sm">
            @foreach($applications as $app)
                <tr class="hover:bg-teal-50/15 dark:hover:bg-teal-950/20 transition duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-300 flex items-center justify-center font-black text-sm border border-teal-200 dark:border-teal-800/60 flex-shrink-0 shadow-xs">
                                {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($app->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $app->user->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">
                                    {{ $app->user->majorDetail?->name ?? ($app->user->major ?? '-') }}
                                    @if($app->user->majorDetail?->degree_level)
                                        <span class="text-[10px] font-mono text-teal-600 dark:text-teal-400 font-bold">({{ $app->user->majorDetail->degree_level }})</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $app->position?->instansi?->nama_dinas ?? '-' }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">{{ $app->position?->posisi ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-xs font-bold text-gray-800 dark:text-gray-200">
                            {{ \Carbon\Carbon::parse($app->tanggal_mulai)->format('d M Y') }} — 
                            {{ \Carbon\Carbon::parse($app->tanggal_selesai)->format('d M Y') }}
                        </div>
                        @php
                            $statusLabel = $app->status_label;
                            $badgeClass = $app->status_badge_class;
                        @endphp
                        <span class="inline-flex mt-1.5 items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $badgeClass }}">
                            {{ $statusLabel }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center space-x-2">
                        <a href="{{ route('pembimbing.peserta.logbook', $app->id) }}" class="inline-flex items-center px-3.5 py-1.5 bg-white dark:bg-gray-800 border border-teal-300 dark:border-teal-700/80 text-teal-700 dark:text-teal-300 rounded-xl hover:bg-teal-50 dark:hover:bg-teal-950/60 transition text-xs font-bold shadow-xs">
                            <i class="fas fa-book-open mr-1.5 text-teal-600 dark:text-teal-400"></i> Logbook
                        </a>
                        <a href="{{ route('pembimbing.peserta.absensi', $app->id) }}" class="inline-flex items-center px-3.5 py-1.5 bg-white dark:bg-gray-800 border border-blue-300 dark:border-blue-700/80 text-blue-700 dark:text-blue-300 rounded-xl hover:bg-blue-50 dark:hover:bg-blue-950/60 transition text-xs font-bold shadow-xs">
                            <i class="fas fa-clipboard-list mr-1.5 text-blue-600 dark:text-blue-400"></i> Absensi
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
