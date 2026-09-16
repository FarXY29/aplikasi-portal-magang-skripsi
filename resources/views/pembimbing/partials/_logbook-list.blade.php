@props(['logs' => []])

{{-- Sidebar Log List (Master Pane) --}}
<div class="md:col-span-4 col-span-1">
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden sticky top-8">
        <div class="p-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex justify-between items-center">
            <h3 class="font-bold text-gray-800 dark:text-gray-200 text-xs uppercase tracking-wider flex items-center gap-2">
                <i class="fas fa-list-ul text-teal-600 dark:text-teal-400"></i> Riwayat Aktivitas
            </h3>
            <span class="text-[10px] font-black bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-2 py-0.5 rounded-full">{{ $logs->count() }}</span>
        </div>
        
        <div class="max-h-[70vh] overflow-y-auto custom-scrollbar">
            <ul class="divide-y divide-gray-100 dark:divide-gray-700/60" role="tablist" aria-label="Riwayat Aktivitas Logbook">
                @foreach($logs as $log)
                <li role="presentation">
                    <button @click="activeTab = {{ $log->id }}"
                        :aria-selected="(activeTab === {{ $log->id }}).toString()"
                        role="tab"
                        :class="{ 'bg-teal-50/70 dark:bg-teal-950/40 border-l-4 border-teal-500 dark:border-teal-400': activeTab === {{ $log->id }}, 'border-l-4 border-transparent hover:bg-gray-50 dark:hover:bg-gray-900/60': activeTab !== {{ $log->id }} }"
                        class="w-full text-left px-4 py-3 transition duration-150 ease-in-out focus:outline-none group">
                        
                        <div class="flex justify-between items-start mb-1">
                            <span class="text-xs font-bold text-gray-800 dark:text-gray-200" :class="{ 'text-teal-700 dark:text-teal-300': activeTab === {{ $log->id }} }">
                                {{ \Carbon\Carbon::parse($log->tanggal)->format('d M Y') }}
                            </span>
                            @if($log->status_validasi == 'disetujui')
                                <i class="fas fa-check-circle text-emerald-500 text-xs" title="Disetujui"></i>
                            @elseif($log->status_validasi == 'revisi')
                                <i class="fas fa-exclamation-circle text-rose-500 text-xs" title="Revisi"></i>
                            @elseif($log->status_validasi == 'ditolak')
                                <i class="fas fa-ban text-rose-500 text-xs" title="Ditolak"></i>
                            @else
                                <div class="w-2.5 h-2.5 rounded-full bg-amber-400 mt-1" title="Pending"></div>
                            @endif
                        </div>
                        
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 truncate group-hover:text-gray-700 dark:group-hover:text-gray-300">
                            {{ Str::limit($log->kegiatan, 40) }}
                        </p>
                    </button>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
