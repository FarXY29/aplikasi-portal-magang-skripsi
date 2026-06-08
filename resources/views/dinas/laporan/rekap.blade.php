<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-file-alt text-teal-600"></i>
                {{ __('Laporan Rekap Peserta') }}
            </h2>
            <div class="text-sm text-gray-500 font-medium bg-white px-4 py-1.5 rounded-full shadow-sm border border-gray-100">
                {{-- PERBAIKAN 1: Menggunakan count() agar aman untuk Collection --}}
                Total Data: <span class="font-bold text-teal-600">{{ $applications->count() }}</span>
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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex flex-col lg:flex-row gap-8 items-start">

                <div class="w-full lg:w-1/4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100 lg:sticky lg:top-8">
                    <div class="flex justify-between items-center mb-5 border-b border-gray-50 pb-3">
                        <h3 class="text-gray-800 font-bold text-sm uppercase tracking-wide flex items-center">
                            <i class="fas fa-filter mr-2 text-teal-500"></i> Filter Laporan
                        </h3>
                        @if(request()->anyFilled(['status', 'asal_instansi', 'start_date', 'end_date', 'sort']))
                            <a href="{{ route('dinas.laporan.rekap') }}" class="text-xs text-red-500 hover:text-red-700 font-bold">Reset</a>
                        @endif
                    </div>

                    <form action="{{ route('dinas.laporan.rekap') }}" method="GET" class="space-y-5">
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Status Peserta</label>
                            <div class="relative">
                                <select name="status" class="w-full border-gray-200 rounded-xl text-sm focus:ring-teal-500 focus:border-teal-500 shadow-sm cursor-pointer bg-gray-50">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Aktif (Sedang Magang)</option>
                                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai / Lulus</option>
                                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Asal Kampus / Sekolah</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <i class="fas fa-university text-xs"></i>
                                </span>
                                <input type="text" name="asal_instansi" value="{{ request('asal_instansi') }}" 
                                    placeholder="Contoh: UNISKA..."
                                    class="w-full pl-9 border-gray-200 rounded-xl text-sm focus:ring-teal-500 focus:border-teal-500 shadow-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Periode Magang</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="date" name="start_date" value="{{ request('start_date') }}" 
                                    class="w-full border-gray-200 rounded-xl text-xs focus:ring-teal-500 focus:border-teal-500 shadow-sm" title="Dari Tanggal">
                                <input type="date" name="end_date" value="{{ request('end_date') }}" 
                                    class="w-full border-gray-200 rounded-xl text-xs focus:ring-teal-500 focus:border-teal-500 shadow-sm" title="Sampai Tanggal">
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1 italic">*Menampilkan irisan tanggal.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Urutkan</label>
                            <select name="sort" class="w-full border-gray-200 rounded-xl text-sm focus:ring-teal-500 focus:border-teal-500 shadow-sm bg-gray-50">
                                <option value="">Terbaru (Default)</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama (A - Z)</option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama (Z - A)</option>
                            </select>
                        </div>

                        <button type="submit" class="w-full bg-teal-600 text-white py-2.5 rounded-xl font-bold shadow-lg shadow-teal-100 hover:bg-teal-700 transition transform active:scale-95 text-sm flex items-center justify-center gap-2">
                            <i class="fas fa-search"></i> Terapkan Filter
                        </button>

                    </form>
                </div>

                <div class="w-full lg:w-3/4">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full">
                        
                        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-white">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Data Hasil Rekapitulasi</h3>
                                <p class="text-xs text-gray-500 mt-1">
                                    Silakan unduh laporan untuk keperluan administrasi.
                                </p>
                            </div>
                            
                            @if($applications->count() > 0)
                                <a href="{{ route('dinas.laporan.rekap.print', request()->query()) }}" target="_blank" class="inline-flex items-center px-5 py-2 bg-gray-800 text-white rounded-xl hover:bg-gray-700 shadow-lg transition text-sm font-bold transform hover:-translate-y-0.5">
                                    <i class="fas fa-file-pdf mr-2"></i> Download PDF
                                </a>
                            @endif
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-12">No</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Peserta & Instansi</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Periode Magang</th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-50">
                                    @forelse($applications as $index => $app)
                                    <tr class="hover:bg-gray-50 transition duration-150">
                                        <td class="px-6 py-4 text-xs text-gray-500 text-center">
                                            {{ $loop->iteration }}
                                        </td>
                                        
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="h-9 w-9 rounded-full bg-gradient-to-br from-teal-100 to-teal-200 flex items-center justify-center text-teal-700 font-bold text-xs border border-teal-100">
                                                    {{ strtoupper(substr($app->user->name, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <div class="text-sm font-bold text-gray-900">{{ $app->user->name }}</div>
                                                    <div class="text-xs text-gray-500 flex items-center mt-0.5">
                                                        <i class="fas fa-university mr-1.5 text-gray-300"></i> 
                                                        {{ Str::limit($app->user->asal_instansi ?? '-', 30) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="flex flex-col gap-1">
                                                <span class="text-xs font-medium text-gray-700 flex items-center gap-2">
                                                    <i class="far fa-calendar text-gray-400"></i>
                                                    {{ \Carbon\Carbon::parse($app->tanggal_mulai)->format('d M Y') }} 
                                                    <span class="text-gray-300">➜</span> 
                                                    {{ \Carbon\Carbon::parse($app->tanggal_selesai)->format('d M Y') }}
                                                </span>
                                                <span class="text-[10px] text-teal-600 bg-teal-50 px-2 py-0.5 rounded w-fit font-bold">
                                                    {{ \Carbon\Carbon::parse($app->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($app->tanggal_selesai)) }} Hari
                                                </span>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            @php
                                                $statusConfig = [
                                                    'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Pending'],
                                                    'diterima' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Aktif'],
                                                    'selesai' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Selesai'],
                                                    'ditolak' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Ditolak'],
                                                ];
                                                $s = $statusConfig[$app->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => ucfirst($app->status)];
                                            @endphp
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full border border-transparent {{ $s['bg'] }} {{ $s['text'] }}">
                                                {{ $s['label'] }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-16 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3 text-gray-300">
                                                    <i class="fas fa-search text-2xl"></i>
                                                </div>
                                                <p class="text-gray-900 font-bold">Data tidak ditemukan</p>
                                                <p class="text-gray-500 text-sm mt-1">Coba sesuaikan filter pencarian Anda.</p>
                                                <a href="{{ route('dinas.laporan.rekap') }}" class="mt-4 text-teal-600 hover:text-teal-800 text-sm font-bold hover:underline">
                                                    Reset Semua Filter
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if(method_exists($applications, 'hasPages') && $applications->hasPages())
                            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                                {{ $applications->withQueryString()->links() }}
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>