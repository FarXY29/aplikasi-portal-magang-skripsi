@once
<div x-data="fraudDetailModal()" x-on:open-fraud-detail.window="open($event.detail.id)" x-on:keydown.escape.window="close()">
    <template x-teleport="body">
        <div x-show="isOpen" x-cloak
             class="fixed inset-0 z-[100] overflow-y-auto" style="display: none;">
            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm transition-opacity" x-show="isOpen" @click="close()" x-transition.opacity></div>

            {{-- Modal Centered Container with Padding --}}
            <div class="min-h-full flex items-center justify-center p-4 sm:p-6 text-center">
                <div class="relative inline-block w-full max-w-lg bg-white dark:bg-gray-800 rounded-2xl md:rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all my-6 z-10"
                     x-show="isOpen" x-transition>
                    {{-- Header --}}
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-950/40 dark:to-orange-950/30">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center shadow-md shadow-amber-500/30 flex-shrink-0">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-base font-extrabold text-gray-900 dark:text-gray-100 truncate">Detail Fraud Signals</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium truncate" x-text="attempt.user_name ? attempt.user_name : '-'"></p>
                            </div>
                        </div>
                        <button @click="close()" class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700/50 flex-shrink-0">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>

                    {{-- Body --}}
                    <div class="px-6 py-5 max-h-[65vh] overflow-y-auto custom-scrollbar">
                        {{-- Loading --}}
                        <div x-show="loading" class="flex flex-col items-center py-10 text-gray-400">
                            <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                            <span class="text-xs font-medium">Memuat detail attempt...</span>
                        </div>

                        <div x-show="!loading && attempt.id" x-cloak class="space-y-5">
                            {{-- Risk Header --}}
                            <div class="flex items-center justify-between p-4 rounded-2xl border" :class="attemptBorderClass">
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Risk Score</p>
                                    <p class="text-3xl font-extrabold font-mono" x-text="attempt.risk_score ?? '0'"></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Status</p>
                                    <p class="text-sm font-bold" x-text="attempt.fraud_status_label ?? 'Normal'"></p>
                                </div>
                            </div>

                            {{-- Fraud Events --}}
                            <div>
                                <h4 class="text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">
                                    <i class="fas fa-exclamation-triangle mr-1"></i> Sinyal Fraud
                                </h4>
                                <template x-if="attempt.fraud_events && attempt.fraud_events.length > 0">
                                    <ul class="space-y-2">
                                        <template x-for="event in attempt.fraud_events" :key="event.id">
                                            <li class="p-3 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-700">
                                                <div class="flex items-center justify-between mb-1">
                                                    <span class="text-sm font-bold text-gray-800 dark:text-gray-200" x-text="event.code"></span>
                                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black"
                                                          :class="{
                                                              'bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400': event.severity === 'critical',
                                                              'bg-orange-100 dark:bg-orange-950/60 text-orange-700 dark:text-orange-400': event.severity === 'high',
                                                              'bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400': event.severity === 'medium',
                                                              'bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400': event.severity === 'low'
                                                          }"
                                                          x-text="event.severity"></span>
                                                </div>
                                                <p class="text-[11px] text-emerald-600 dark:text-emerald-400 font-bold">+<span x-text="event.score_delta"></span> risk</p>
                                                <template x-if="event.metadata && Object.keys(event.metadata).length > 0">
                                                    <div class="mt-2 text-[11px] text-gray-600 dark:text-gray-400 font-mono bg-white dark:bg-gray-800 rounded-lg p-2 overflow-x-auto">
                                                        <template x-for="(val, key) in event.metadata" :key="key">
                                                            <div><span class="text-teal-600 dark:text-teal-400" x-text="key"></span>: <span x-text="val"></span></div>
                                                        </template>
                                                    </div>
                                                </template>
                                            </li>
                                        </template>
                                    </ul>
                                </template>
                                <template x-if="!attempt.fraud_events || attempt.fraud_events.length === 0">
                                    <p class="text-xs text-gray-400 dark:text-gray-500 italic">Tidak ada sinyal fraud — attempt bersih.</p>
                                </template>
                            </div>

                            {{-- Location Evidence --}}
                            <div x-show="attempt.latitude">
                                <h4 class="text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">
                                    <i class="fas fa-map-marker-alt mr-1"></i> Bukti Lokasi
                                </h4>
                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div class="p-2 rounded-lg bg-gray-50 dark:bg-gray-900">
                                        <p class="text-gray-400 font-medium">Latitude</p>
                                        <p class="font-mono text-gray-800 dark:text-gray-200" x-text="attempt.latitude"></p>
                                    </div>
                                    <div class="p-2 rounded-lg bg-gray-50 dark:bg-gray-900">
                                        <p class="text-gray-400 font-medium">Longitude</p>
                                        <p class="font-mono text-gray-800 dark:text-gray-200" x-text="attempt.longitude"></p>
                                    </div>
                                    <div class="p-2 rounded-lg bg-gray-50 dark:bg-gray-900">
                                        <p class="text-gray-400 font-medium">Jarak ke Kantor</p>
                                        <p class="font-mono text-gray-800 dark:text-gray-200"><span x-text="attempt.distance_to_instance ? Number(attempt.distance_to_instance).toFixed(0) : '-'"></span> m</p>
                                    </div>
                                    <div class="p-2 rounded-lg bg-gray-50 dark:bg-gray-900">
                                        <p class="text-gray-400 font-medium">Akurasi GPS</p>
                                        <p class="font-mono text-gray-800 dark:text-gray-200"><span x-text="attempt.accuracy ? Number(attempt.accuracy).toFixed(0) : '-'"></span> m</p>
                                    </div>
                                </div>
                                <template x-if="attempt.latitude && attempt.longitude">
                                    <a :href="'https://www.google.com/maps?q=' + attempt.latitude + ',' + attempt.longitude" target="_blank"
                                       class="inline-flex items-center gap-1.5 mt-2 text-xs font-bold text-teal-600 dark:text-teal-400 hover:underline">
                                        <i class="fas fa-external-link-alt text-[10px]"></i> Lihat di Google Maps
                                    </a>
                                </template>
                            </div>

                            {{-- Timing & Network --}}
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <h4 class="text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">Waktu</h4>
                                    <p class="text-xs text-gray-700 dark:text-gray-300 font-mono" x-text="attempt.server_received_at_label ?? '-'"></p>
                                    <p class="text-[11px] text-gray-400 dark:text-gray-500" x-show="attempt.client_timestamp">Client ts: <span x-text="attempt.client_timestamp" class="font-mono"></span></p>
                                </div>
                                <div>
                                    <h4 class="text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">Jaringan</h4>
                                    <p class="text-xs text-gray-700 dark:text-gray-300 font-mono" x-text="attempt.ip_address ?? '-'"></p>
                                    <p class="text-[11px] text-gray-400 dark:text-gray-500 truncate" x-text="attempt.user_agent ?? ''"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 flex justify-end">
                        <button @click="close()" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-xl text-xs font-bold transition">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
function fraudDetailModal() {
    return {
        isOpen: false,
        loading: false,
        attempt: {},

        get attemptBorderClass() {
            const status = this.attempt.fraud_status;
            if (status === 'critical') return 'border-rose-300 dark:border-rose-800 bg-rose-50/40 dark:bg-rose-950/20';
            if (status === 'very_high') return 'border-orange-300 dark:border-orange-800 bg-orange-50/40 dark:bg-orange-950/20';
            if (status === 'high') return 'border-amber-300 dark:border-amber-800 bg-amber-50/40 dark:bg-amber-950/20';
            if (status === 'medium') return 'border-yellow-300 dark:border-yellow-800 bg-yellow-50/40 dark:bg-yellow-950/20';
            return 'border-gray-200 dark:border-gray-700 bg-gray-50/40 dark:bg-gray-900/20';
        },

        async open(id) {
            this.isOpen = true;
            this.loading = true;
            this.attempt = {};
            document.body.classList.add('overflow-hidden');

            try {
                const res = await fetch(`{{ route('dinas.monitoring.fraud.show', ':id') }}`.replace(':id', id), {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin',
                });
                if (!res.ok) throw new Error('fetch_failed');
                const data = await res.json();

                const a = data.attempt;
                this.attempt = {
                    id: a.id,
                    user_name: a.user?.name,
                    risk_score: a.risk_score,
                    fraud_status: a.fraud_status,
                    fraud_status_label: data.fraud_status_label,
                    latitude: a.latitude,
                    longitude: a.longitude,
                    accuracy: a.accuracy,
                    distance_to_instance: a.distance_to_instance,
                    location_margin: a.location_margin,
                    client_timestamp: a.client_timestamp,
                    server_received_at_label: a.server_received_at,
                    ip_address: a.ip_address,
                    user_agent: a.user_agent,
                    fraud_events: a.fraud_events || [],
                };
            } catch (e) {
                alert('Gagal memuat detail attempt. Coba lagi.');
                this.close();
            } finally {
                this.loading = false;
            }
        },

        close() {
            this.isOpen = false;
            this.attempt = {};
            document.body.classList.remove('overflow-hidden');
        },
    };
}
</script>
@endonce
