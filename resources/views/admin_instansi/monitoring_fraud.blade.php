<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-amber-100 dark:bg-amber-950/60 flex items-center justify-center border border-amber-200 dark:border-amber-800/60">
                        <i class="fas fa-shield-alt text-amber-600 dark:text-amber-400 text-lg"></i>
                    </div>
                    Monitoring Fraud Absensi
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-medium">
                    Pantau &amp; selidiki percobaan absensi yang ditandai mencurigakan oleh sistem anti-fraud.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('dinas.monitoring.fraud.export', request()->query()) }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 border border-transparent rounded-xl text-white text-xs font-bold transition shadow-xs flex items-center uppercase tracking-wider">
                    <i class="fas fa-file-csv mr-2"></i> Export CSV
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <a href="{{ route('dinas.dashboard') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-xs">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Dashboard
                </a>
            </div>

            {{-- Stat Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="glass-panel hover-lift p-5 rounded-3xl flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Total Attempt</p>
                        <p class="text-2xl font-black text-gray-800 dark:text-gray-100 mt-1 font-mono">{{ $stats['total_attempts'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-600 dark:text-slate-400 text-lg shadow-xs">
                        <i class="fas fa-list"></i>
                    </div>
                </div>

                <div class="glass-panel hover-lift p-5 rounded-3xl flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Ditandai</p>
                        <p class="text-2xl font-black text-amber-600 dark:text-amber-400 mt-1 font-mono">{{ $stats['flagged_attempts'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/60 border border-amber-100 dark:border-amber-800/60 flex items-center justify-center text-amber-600 dark:text-amber-400 text-lg shadow-xs">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                </div>

                <div class="glass-panel hover-lift p-5 rounded-3xl flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Ditolak Sistem</p>
                        <p class="text-2xl font-black text-rose-600 dark:text-rose-400 mt-1 font-mono">{{ $stats['rejected_attempts'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/60 border border-rose-100 dark:border-rose-800/60 flex items-center justify-center text-rose-600 dark:text-rose-400 text-lg shadow-xs">
                        <i class="fas fa-ban"></i>
                    </div>
                </div>

                <div class="glass-panel hover-lift p-5 rounded-3xl flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Rata-rata Risk</p>
                        <p class="text-2xl font-black text-gray-800 dark:text-gray-100 mt-1 font-mono">{{ $stats['average_risk'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 dark:bg-teal-950/60 border border-teal-100 dark:border-teal-800/60 flex items-center justify-center text-teal-600 dark:text-teal-400 text-lg shadow-xs">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>

            {{-- Top Participants (hanya bila ada) --}}
            @if($topParticipants->isNotEmpty())
            <div class="glass-panel rounded-3xl p-5 border-l-4 border-amber-400">
                <h3 class="font-bold text-gray-800 dark:text-gray-100 text-sm flex items-center gap-2 mb-3">
                    <i class="fas fa-ranking-star text-amber-500"></i> Peserta Paling Sering Ditandai
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    @foreach($topParticipants as $idx => $p)
                    <a href="{{ route('dinas.monitoring.fraud', ['user_id' => $p->user_id]) }}" class="flex items-center gap-3 p-3 rounded-2xl bg-amber-50/60 dark:bg-amber-950/20 border border-amber-200/60 dark:border-amber-800/40 hover:bg-amber-50 transition">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-amber-500 text-white font-black text-xs">{{ $idx + 1 }}</span>
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-gray-800 dark:text-gray-200 truncate">{{ $p->name }}</p>
                            <p class="text-[11px] text-amber-700 dark:text-amber-400 font-medium">{{ $p->total }} attempt ditandai</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Filter Panel --}}
            <div class="glass-panel rounded-3xl overflow-hidden">
                <div class="p-6 border-b border-gray-100/50 dark:border-gray-700/50 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white/30 dark:bg-gray-900/30">
                    <h3 class="font-bold text-gray-800 dark:text-gray-100 text-base flex items-center gap-2">
                        <i class="fas fa-list-check text-teal-600 dark:text-teal-400"></i> Daftar Attempt Mencurigakan
                    </h3>

                    <form action="" method="GET" class="flex flex-wrap items-center gap-2">
                        <div class="relative">
                            <i class="fas fa-filter absolute left-3.5 top-1/2 transform -translate-y-1/2 text-gray-400 dark:text-gray-500 text-xs"></i>
                            <select name="status" onchange="this.form.submit()" class="pl-9 pr-8 py-2 text-xs font-bold border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 rounded-xl shadow-xs cursor-pointer [color-scheme:dark]">
                                <option value="flagged" class="bg-white dark:bg-gray-900" {{ request('status') == 'flagged' || !request('status') ? 'selected' : '' }}>Semua Ditandai</option>
                                <option value="medium" class="bg-white dark:bg-gray-900" {{ request('status') == 'medium' ? 'selected' : '' }}>Mencurigakan (Ringan)</option>
                                <option value="high" class="bg-white dark:bg-gray-900" {{ request('status') == 'high' ? 'selected' : '' }}>Mencurigakan (Tinggi)</option>
                                <option value="very_high" class="bg-white dark:bg-gray-900" {{ request('status') == 'very_high' ? 'selected' : '' }}>Sangat Mencurigakan</option>
                                <option value="critical" class="bg-white dark:bg-gray-900" {{ request('status') == 'critical' ? 'selected' : '' }}>Kritis</option>
                            </select>
                        </div>

                        <div class="relative">
                            <i class="fas fa-user absolute left-3.5 top-1/2 transform -translate-y-1/2 text-gray-400 dark:text-gray-500 text-xs"></i>
                            <select name="user_id" onchange="this.form.submit()" class="pl-9 pr-8 py-2 text-xs font-bold border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 rounded-xl shadow-xs cursor-pointer [color-scheme:dark] max-w-[180px]">
                                <option value="" class="bg-white dark:bg-gray-900">Semua Peserta</option>
                                @foreach($participants as $p)
                                <option value="{{ $p->id }}" class="bg-white dark:bg-gray-900" {{ request('user_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="relative">
                            <i class="fas fa-calendar absolute left-3.5 top-1/2 transform -translate-y-1/2 text-gray-400 dark:text-gray-500 text-xs"></i>
                            <select name="days" onchange="this.form.submit()" class="pl-9 pr-8 py-2 text-xs font-bold border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 rounded-xl shadow-xs cursor-pointer [color-scheme:dark]">
                                <option value="7" class="bg-white dark:bg-gray-900" {{ request('days', 7) == 7 ? 'selected' : '' }}>7 Hari</option>
                                <option value="30" class="bg-white dark:bg-gray-900" {{ request('days') == 30 ? 'selected' : '' }}>30 Hari</option>
                                <option value="90" class="bg-white dark:bg-gray-900" {{ request('days') == 90 ? 'selected' : '' }}>90 Hari</option>
                            </select>
                        </div>
                    </form>
                </div>

                {{-- Attempts Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-100 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Peserta</th>
                                <th class="px-6 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tipe</th>
                                <th class="px-6 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Waktu</th>
                                <th class="px-6 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Risk</th>
                                <th class="px-6 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jarak</th>
                                <th class="px-6 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Akurasi</th>
                                <th class="px-6 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700/60 text-sm">
                            @forelse($attempts as $a)
                            @php
                                $fraudEnum = $a->fraud_status
                                    ? \App\Enums\AttendanceFraudStatus::tryFrom($a->fraud_status)
                                    : null;
                                $borderClass = match($a->fraud_status) {
                                    'critical' => 'border-l-4 border-l-rose-500',
                                    'very_high' => 'border-l-4 border-l-orange-500',
                                    'high' => 'border-l-4 border-l-amber-500',
                                    'medium' => 'border-l-4 border-l-yellow-400',
                                    default => '',
                                };
                            @endphp
                            <tr class="table-row-hover border-b border-gray-100/50 dark:border-gray-700/50 {{ $borderClass }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $a->user?->name ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-xs font-bold {{ $a->attendance_type === 'clock_in' ? 'text-teal-600 dark:text-teal-400' : 'text-rose-600 dark:text-rose-400' }}">
                                        {{ $a->attendance_type === 'clock_in' ? 'Masuk' : 'Pulang' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-xs text-gray-600 dark:text-gray-300 font-mono">{{ $a->server_received_at->translatedFormat('d M H:i') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($fraudEnum)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $fraudEnum->badgeClass() }} gap-1.5">
                                            <i class="fas fa-shield-alt text-[10px]"></i> {{ $a->risk_score }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400 dark:text-gray-500 font-mono">{{ $a->risk_score ?? 0 }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($fraudEnum)
                                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ $fraudEnum->label() }}</span>
                                    @else
                                        <span class="text-xs text-gray-400 dark:text-gray-500">Normal</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($a->distance_to_instance !== null)
                                        <span class="text-xs text-gray-600 dark:text-gray-300 font-mono">{{ number_format($a->distance_to_instance, 0) }} m</span>
                                    @else
                                        <span class="text-xs text-gray-400 dark:text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($a->accuracy !== null)
                                        <span class="text-xs {{ $a->accuracy > 200 ? 'text-rose-600 dark:text-rose-400 font-bold' : 'text-gray-600 dark:text-gray-300' }} font-mono">{{ number_format($a->accuracy, 0) }} m</span>
                                    @else
                                        <span class="text-xs text-gray-400 dark:text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <button type="button"
                                            x-data
                                            @click="$dispatch('open-fraud-detail', { id: {{ $a->id }} })"
                                            class="px-3 py-1.5 bg-teal-50 dark:bg-teal-950/40 border border-teal-200 dark:border-teal-800 text-teal-700 dark:text-teal-400 rounded-lg text-xs font-bold hover:bg-teal-100 dark:hover:bg-teal-900/60 transition">
                                        <i class="fas fa-eye text-[10px] mr-1"></i> Detail
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                        <div class="w-16 h-16 bg-emerald-50 dark:bg-emerald-950/60 rounded-full flex items-center justify-center mb-3 border border-emerald-200 dark:border-emerald-800/60">
                                            <i class="fas fa-check-double text-3xl text-emerald-500"></i>
                                        </div>
                                        <p class="font-bold text-gray-700 dark:text-gray-300 text-sm">Tidak Ada Attempt Mencurigakan</p>
                                        <p class="text-xs mt-1 text-gray-500 dark:text-gray-400">Semua percobaan absensi pada periode ini bersih.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-gray-100/50 dark:border-gray-700/50 bg-white/30 dark:bg-gray-900/30">
                    {{ $attempts->links() }}
                </div>
            </div>

        </div>
    </div>

    @include('admin_instansi.partials._fraud-detail-modal')
</x-app-layout>
