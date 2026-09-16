@props(['applications' => []])

{{-- Mobile Card View (< md) --}}
<div class="md:hidden divide-y divide-gray-100 dark:divide-gray-700/60">
    @foreach($applications as $app)
    <div class="p-5 space-y-3 hover:bg-teal-50/10 dark:hover:bg-teal-950/20 transition">
        <div class="flex items-start justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-teal-50 dark:bg-teal-950/60 flex items-center justify-center text-teal-600 dark:text-teal-300 font-black text-sm shrink-0 border border-teal-200 dark:border-teal-800/60">
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
            @php
                $statusLabel = $app->status_label;
                $badgeClass = $app->status_badge_class;
            @endphp
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $badgeClass }}">
                {{ $statusLabel }}
            </span>
        </div>

        <div class="bg-gray-50 dark:bg-gray-900 p-3.5 rounded-2xl border border-gray-200 dark:border-gray-700 space-y-1.5 text-xs">
            <div class="flex items-center gap-2 text-gray-800 dark:text-gray-200 font-bold">
                <i class="fas fa-building text-teal-600 dark:text-teal-400 w-4"></i>
                <span>{{ $app->position?->instansi?->nama_dinas ?? '-' }}</span>
            </div>
            <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400 font-medium">
                <i class="fas fa-briefcase text-gray-400 dark:text-gray-500 w-4"></i>
                <span>{{ $app->position?->posisi ?? '-' }}</span>
            </div>
            <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400 pt-1.5 border-t border-gray-200 dark:border-gray-700/60 font-bold">
                <i class="far fa-calendar-alt text-gray-400 dark:text-gray-500 w-4"></i>
                <span>{{ \Carbon\Carbon::parse($app->tanggal_mulai)->format('d M Y') }} — {{ \Carbon\Carbon::parse($app->tanggal_selesai)->format('d M Y') }}</span>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-2 pt-1">
            <a href="{{ route('pembimbing.peserta.logbook', $app->id) }}" class="w-full sm:flex-1 py-2.5 px-3 bg-white dark:bg-gray-800 border border-teal-300 dark:border-teal-700/80 text-teal-700 dark:text-teal-300 rounded-xl hover:bg-teal-50 dark:hover:bg-teal-950/60 transition text-xs font-bold shadow-xs flex items-center justify-center gap-2">
                <i class="fas fa-book-open text-teal-600 dark:text-teal-400"></i> Cek Logbook
            </a>
            <a href="{{ route('pembimbing.peserta.absensi', $app->id) }}" class="w-full sm:flex-1 py-2.5 px-3 bg-white dark:bg-gray-800 border border-blue-300 dark:border-blue-700/80 text-blue-700 dark:text-blue-300 rounded-xl hover:bg-blue-50 dark:hover:bg-blue-950/60 transition text-xs font-bold shadow-xs flex items-center justify-center gap-2">
                <i class="fas fa-clipboard-list text-blue-600 dark:text-blue-400"></i> Cek Absensi
            </a>
        </div>
    </div>
    @endforeach
</div>
