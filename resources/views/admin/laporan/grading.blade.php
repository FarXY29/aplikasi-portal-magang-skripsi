<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-chart-line text-teal-600"></i>
                {{ __('Analisis Kompetensi & Performa') }}
            </h2>
            <div class="text-sm text-gray-500 font-medium bg-white px-4 py-1.5 rounded-full shadow-sm border border-gray-100">
                Data dari <span class="font-bold text-teal-600">{{ count($ranking) }}</span> Peserta Dinilai
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="flex justify-between items-center mb-6 print:hidden">
                <a href="{{ route('admin.laporan.hub') }}" class="group flex items-center text-sm font-bold text-gray-500 hover:text-teal-600 transition">
                    <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                    Kembali ke Pusat Laporan
                </a>
                <a href="{{ route('admin.laporan.grading.print') }}" target="_blank" class="bg-white border border-gray-200 hover:border-teal-500 text-gray-700 hover:text-teal-600 font-bold py-2 px-4 rounded-full shadow-sm flex items-center gap-2 transition text-sm">
                    <i class="fas fa-print text-teal-500"></i> Cetak Laporan PDF
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-blue-100 relative overflow-hidden group hover:shadow-md transition">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition">
                        <i class="fas fa-laptop-code text-6xl text-blue-500"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-xs font-bold text-blue-500 uppercase tracking-widest mb-1">Kompetensi Teknis</p>
                        <div class="flex items-baseline gap-2">
                            <h3 class="text-4xl font-black text-gray-800">{{ $statsGlobal['avg_teknis'] }}</h3>
                            <span class="text-sm text-gray-400">/ 100</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Rata-rata kemampuan hard skill peserta.</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-purple-100 relative overflow-hidden group hover:shadow-md transition">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition">
                        <i class="fas fa-clock text-6xl text-purple-500"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-xs font-bold text-purple-500 uppercase tracking-widest mb-1">Kedisiplinan</p>
                        <div class="flex items-baseline gap-2">
                            <h3 class="text-4xl font-black text-gray-800">{{ $statsGlobal['avg_disiplin'] }}</h3>
                            <span class="text-sm text-gray-400">/ 100</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Rata-rata ketepatan waktu & kehadiran.</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-teal-100 relative overflow-hidden group hover:shadow-md transition">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition">
                        <i class="fas fa-user-check text-6xl text-teal-500"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-xs font-bold text-teal-500 uppercase tracking-widest mb-1">Sikap & Perilaku</p>
                        <div class="flex items-baseline gap-2">
                            <h3 class="text-4xl font-black text-gray-800">{{ $statsGlobal['avg_perilaku'] }}</h3>
                            <span class="text-sm text-gray-400">/ 100</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Rata-rata soft skill & etika kerja.</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 bg-gray-50/50">
                    <h3 class="font-bold text-gray-800">Distribusi Kualitas Peserta</h3>
                    <p class="text-xs text-gray-500 mt-1">Sebaran predikat berdasarkan nilai akhir.</p>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                    @php
                        $totalData = array_sum($distribusi);
                        $colors = [
                            'Sangat Baik' => 'bg-green-500',
                            'Baik' => 'bg-blue-500',
                            'Cukup' => 'bg-yellow-400',
                            'Kurang' => 'bg-red-500'
                        ];
                    @endphp

                    @foreach($distribusi as $label => $jumlah)
                        @php
                            $percentage = $totalData > 0 ? ($jumlah / $totalData) * 100 : 0;
                            $color = $colors[$label] ?? 'bg-gray-400';
                        @endphp
                        <div>
                            <div class="flex justify-between items-end mb-1">
                                <span class="text-sm font-bold text-gray-700">{{ $label }}</span>
                                <span class="text-xs font-bold text-gray-500">{{ $jumlah }} Peserta ({{ round($percentage) }}%)</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                <div class="{{ $color }} h-2.5 rounded-full transition-all duration-1000" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                    <div>
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-trophy text-yellow-500"></i> Peringkat 10 Besar Terbaik
                        </h3>
                        <p class="text-xs text-gray-500 mt-1">Peserta dengan performa tertinggi dari seluruh instansi.</p>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-xs font-bold text-gray-400 uppercase tracking-wider bg-gray-50 border-b border-gray-100">
                                <th class="px-6 py-4 w-16 text-center">Rank</th>
                                <th class="px-6 py-4">Peserta</th>
                                <th class="px-6 py-4">Instansi Magang</th>
                                <th class="px-6 py-4 text-center">Nilai Akhir</th>
                                <th class="px-6 py-4 text-center">Predikat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($ranking as $index => $res)
                            <tr class="hover:bg-teal-50/30 transition group">
                                <td class="px-6 py-4 text-center">
                                    @if($index == 0)
                                        <div class="w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center mx-auto shadow-sm ring-2 ring-yellow-200">
                                            <i class="fas fa-crown text-sm"></i>
                                        </div>
                                    @elseif($index == 1)
                                        <div class="w-8 h-8 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center mx-auto border border-gray-300 font-bold text-sm">2</div>
                                    @elseif($index == 2)
                                        <div class="w-8 h-8 rounded-full bg-orange-100 text-orange-700 flex items-center justify-center mx-auto border border-orange-200 font-bold text-sm">3</div>
                                    @else
                                        <span class="text-gray-400 font-bold text-sm">#{{ $index + 1 }}</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-teal-100 to-teal-200 flex items-center justify-center text-teal-700 font-bold text-xs">
                                            {{ substr($res['nama'], 0, 1) }}
                                        </div>
                                        <span class="font-bold text-gray-800 text-sm group-hover:text-teal-700 transition">{{ $res['nama'] }}</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="text-xs font-medium text-gray-500 bg-gray-50 px-2 py-1 rounded-md border border-gray-100 w-fit">
                                        <i class="far fa-building mr-1"></i> {{ $res['instansi'] }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="text-lg font-black text-teal-600">{{ $res['rata_rata'] }}</span>
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
                                    <span class="px-3 py-1 text-[10px] font-bold uppercase rounded-full border {{ $badgeColor }}">
                                        {{ $res['predikat'] }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>