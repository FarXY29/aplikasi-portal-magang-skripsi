<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-chart-bar text-teal-600"></i>
                {{ __('Analisis Nilai & Kompetensi') }}
            </h2>
            <div class="flex items-center gap-2 text-xs font-medium text-gray-500 bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100">
                <i class="fas fa-building text-teal-600"></i>
                <span>{{ Auth::user()->instansi->nama_dinas }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen font-sans">
        <div class="flex justify-between items-center mb-6 print:hidden">
            <a href="{{ route('dinas.laporan.hub') }}" class="group flex items-center text-sm font-bold text-gray-500 hover:text-teal-600 transition">
                <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                    <i class="fas fa-arrow-left text-xs"></i>
                </div>
                Kembali ke Pusat Laporan
            </a>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="bg-gradient-to-r from-teal-600 to-teal-800 rounded-3xl p-8 shadow-lg text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <h3 class="text-2xl font-bold mb-2">Evaluasi Kualitas SDM Magang</h3>
                        <p class="text-teal-100 text-sm max-w-2xl leading-relaxed">
                            Laporan ini memproses nilai rata-rata dari seluruh peserta magang di instansi Anda untuk mengukur efektivitas bimbingan dan kualitas kompetensi yang dihasilkan.
                        </p>
                    </div>
                    <div class="hidden md:block">
                        <i class="fas fa-chart-line text-6xl text-teal-200 opacity-50"></i>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-blue-100 hover:shadow-md transition group">
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-xs font-bold text-blue-500 uppercase tracking-widest">Rata-rata Teknis</p>
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 shadow-sm group-hover:scale-110 transition">
                            <i class="fas fa-tools"></i>
                        </div>
                    </div>
                    <h3 class="text-4xl font-black text-gray-800">{{ number_format($statsGlobal['avg_teknis'] ?? 0, 1) }}</h3>
                    <p class="text-xs text-gray-400 mt-2">Skor keahlian bidang & hasil kerja</p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-purple-100 hover:shadow-md transition group">
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-xs font-bold text-purple-500 uppercase tracking-widest">Rata-rata Disiplin</p>
                        <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600 shadow-sm group-hover:scale-110 transition">
                            <i class="fas fa-user-clock"></i>
                        </div>
                    </div>
                    <h3 class="text-4xl font-black text-gray-800">{{ number_format($statsGlobal['avg_disiplin'] ?? 0, 1) }}</h3>
                    <p class="text-xs text-gray-400 mt-2">Skor kehadiran & ketepatan waktu</p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-teal-100 hover:shadow-md transition group">
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-xs font-bold text-teal-500 uppercase tracking-widest">Rata-rata Perilaku</p>
                        <div class="w-10 h-10 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600 shadow-sm group-hover:scale-110 transition">
                            <i class="fas fa-heart"></i>
                        </div>
                    </div>
                    <h3 class="text-4xl font-black text-gray-800">{{ number_format($statsGlobal['avg_perilaku'] ?? 0, 1) }}</h3>
                    <p class="text-xs text-gray-400 mt-2">Skor etika, sikap & kerjasama tim</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                            <div>
                                <h3 class="font-bold text-gray-800">Peringkat Peserta Terbaik</h3>
                                <p class="text-xs text-gray-500 mt-1">Diurutkan berdasarkan akumulasi nilai akhir.</p>
                            </div>
                            <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center text-yellow-500 shadow-sm">
                                <i class="fas fa-trophy"></i>
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="text-xs font-bold text-gray-400 uppercase tracking-wider bg-white border-b border-gray-100">
                                        <th class="px-6 py-4 w-16 text-center">Rank</th>
                                        <th class="px-6 py-4">Nama Peserta</th>
                                        <th class="px-6 py-4 text-center">Nilai Akhir</th>
                                        <th class="px-6 py-4 text-center">Predikat</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @forelse($ranking as $index => $res)
                                    <tr class="hover:bg-teal-50/30 transition group">
                                        <td class="px-6 py-4">
                                            @if($index == 0)
                                                <div class="w-8 h-8 mx-auto flex items-center justify-center rounded-lg bg-yellow-100 text-yellow-700 shadow-sm ring-2 ring-yellow-50">
                                                    <i class="fas fa-crown text-xs"></i>
                                                </div>
                                            @elseif($index == 1)
                                                <div class="w-8 h-8 mx-auto flex items-center justify-center rounded-lg bg-gray-100 text-gray-600 font-bold text-sm border border-gray-200">2</div>
                                            @elseif($index == 2)
                                                <div class="w-8 h-8 mx-auto flex items-center justify-center rounded-lg bg-orange-50 text-orange-700 font-bold text-sm border border-orange-100">3</div>
                                            @else
                                                <div class="text-center text-sm font-bold text-gray-400">{{ $index + 1 }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-gray-500 font-bold text-xs">
                                                    {{ substr($res['nama'], 0, 1) }}
                                                </div>
                                                <span class="text-sm font-bold text-gray-700 group-hover:text-teal-700 transition">{{ $res['nama'] }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="text-lg font-black text-gray-800">{{ number_format($res['rata_rata'], 1) }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @php
                                                $badgeColor = match($res['predikat']) {
                                                    'Sangat Baik' => 'bg-green-100 text-green-700 border-green-200',
                                                    'Baik' => 'bg-blue-100 text-blue-700 border-blue-200',
                                                    'Cukup' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                                    default => 'bg-gray-100 text-gray-600'
                                                };
                                            @endphp
                                            <span class="inline-flex px-3 py-1 text-[10px] font-bold uppercase rounded-full border {{ $badgeColor }}">
                                                {{ $res['predikat'] }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-gray-400 text-sm italic">
                                            Belum ada data penilaian yang masuk.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                        <h3 class="font-bold text-gray-800 text-sm mb-6 pb-2 border-b border-gray-50">Distribusi Predikat</h3>
                        <div class="space-y-5">
                            @foreach($distribusi as $label => $jumlah)
                                @php
                                    $total = array_sum($distribusi);
                                    $percent = $total > 0 ? ($jumlah / $total) * 100 : 0;
                                    $color = match($label) {
                                        'Sangat Baik' => 'bg-green-500',
                                        'Baik' => 'bg-blue-500',
                                        'Cukup' => 'bg-yellow-400',
                                        default => 'bg-red-500'
                                    };
                                @endphp
                                <div>
                                    <div class="flex justify-between items-end mb-1">
                                        <span class="text-xs font-bold text-gray-600">{{ $label }}</span>
                                        <span class="text-xs font-bold text-gray-800">{{ $jumlah }} <span class="text-gray-400 font-normal">({{ round($percent) }}%)</span></span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-2">
                                        <div class="{{ $color }} h-2 rounded-full" style="width: {{ $percent }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-8 pt-6 border-t border-gray-50">
                            <p class="text-[10px] text-gray-400 leading-relaxed italic flex items-start gap-2">
                                <i class="fas fa-info-circle mt-0.5"></i>
                                Data ini diproses secara otomatis berdasarkan input nilai harian dan evaluasi akhir oleh Pembimbing Lapangan Lapangan.
                            </p>
                        </div>
                    </div>

                    <a href="{{ route('dinas.laporan.grading.print') }}" target="_blank" class="w-full flex items-center justify-center gap-3 bg-gray-900 text-white py-4 rounded-2xl font-bold text-sm hover:bg-gray-800 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:scale-95">
                        <i class="fas fa-file-pdf text-red-400"></i>
                        Unduh Laporan PDF
                    </a>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>