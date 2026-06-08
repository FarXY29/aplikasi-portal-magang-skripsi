<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-globe-asia text-teal-600"></i>
                {{ __('Laporan Global Peserta') }}
            </h2>
            <div class="text-sm text-gray-500 font-medium bg-white px-4 py-1.5 rounded-full shadow-sm border border-gray-100">
                Total Data: <span class="font-bold text-teal-600">{{ $allInterns->count() }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-center mb-6 print:hidden">
                <a href="{{ route('admin.laporan.hub') }}" class="group flex items-center text-sm font-bold text-gray-500 hover:text-teal-600 transition">
                    <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                    Kembali ke Pusat Laporan
                </a>
            </div>

            <div class="flex flex-col lg:flex-row gap-8 items-start">

                <div class="w-full lg:w-1/4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100 print:hidden lg:sticky lg:top-8">
                    <div class="mb-5 border-b border-gray-100 pb-3 flex items-center justify-between">
                        <h3 class="text-gray-800 font-bold text-sm uppercase tracking-wide flex items-center">
                            <i class="fas fa-filter mr-2 text-teal-500"></i> Filter Data
                        </h3>
                        @if(request()->anyFilled(['instansi', 'instansi_id', 'status', 'start_date', 'end_date']))
                            <a href="{{ route('admin.laporan.peserta_global') }}" class="text-xs text-red-500 hover:text-red-700 font-bold">Reset</a>
                        @endif
                    </div>
                    
                    <form method="GET" action="{{ route('admin.laporan.peserta_global') }}" class="flex flex-col gap-5">
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Asal Universitas/Sekolah</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none"><i class="fas fa-university text-xs"></i></span>
                                <select name="instansi" class="w-full pl-9 border-gray-200 rounded-xl text-sm focus:ring-teal-500 focus:border-teal-500 shadow-sm cursor-pointer bg-gray-50 hover:bg-white transition">
                                    <option value="">Semua Asal Instansi</option>
                                    @foreach($listInstansi as $instansi)
                                        <option value="{{ $instansi }}" {{ request('instansi') == $instansi ? 'selected' : '' }}>
                                            {{ Str::limit($instansi, 25) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Lokasi Magang (Instansi)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none"><i class="fas fa-building text-xs"></i></span>
                                <select name="instansi_id" class="w-full pl-9 border-gray-200 rounded-xl text-sm focus:ring-teal-500 focus:border-teal-500 shadow-sm cursor-pointer bg-gray-50 hover:bg-white transition">
                                    <option value="">Semua Lokasi</option>
                                    @foreach($listDinas as $dinas)
                                        <option value="{{ $dinas->id }}" {{ request('instansi_id') == $dinas->id ? 'selected' : '' }}>
                                            {{ Str::limit($dinas->nama_dinas, 25) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Status Magang</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none"><i class="fas fa-info-circle text-xs"></i></span>
                                <select name="status" class="w-full pl-9 border-gray-200 rounded-xl text-sm focus:ring-teal-500 focus:border-teal-500 shadow-sm cursor-pointer bg-gray-50 hover:bg-white transition">
                                    <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua Status</option>
                                    <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Sedang Berjalan (Aktif)</option>
                                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Dari Tanggal</label>
                                <input type="date" name="start_date" value="{{ request('start_date') }}" 
                                    class="w-full border-gray-200 rounded-xl text-xs focus:ring-teal-500 focus:border-teal-500 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Sampai Tanggal</label>
                                <input type="date" name="end_date" value="{{ request('end_date') }}" 
                                    class="w-full border-gray-200 rounded-xl text-xs focus:ring-teal-500 focus:border-teal-500 shadow-sm">
                            </div>
                        </div>

                        <button type="submit" class="mt-2 w-full bg-teal-600 text-white py-2.5 rounded-xl shadow-lg shadow-teal-200 hover:bg-teal-700 text-sm font-bold transition transform active:scale-95 flex items-center justify-center">
                            <i class="fas fa-search mr-2"></i> Terapkan Filter
                        </button>
                    </form>
                </div>

                <div class="w-full lg:w-3/4">
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 flex flex-col h-full">
                        
                        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-white sticky top-0 z-10">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Data Peserta</h3>
                                <p class="text-xs text-gray-500 mt-1">
                                    Menampilkan data berdasarkan filter yang dipilih.
                                </p>
                            </div>
                            
                            @if($allInterns->count() > 0)
                                <a href="{{ route('admin.laporan.peserta_global.print', request()->query()) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 shadow transition text-sm font-bold">
                                    <i class="fas fa-file-pdf mr-2"></i> Ekspor PDF
                                </a>
                            @endif
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Peserta / Asal</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lokasi Magang</th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Periode</th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-50">
                                    @forelse($allInterns as $data)
                                    <tr class="hover:bg-gray-50 transition duration-150">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-gray-500 font-bold border border-gray-300 text-xs">
                                                    {{ strtoupper(substr($data->user->name, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <div class="font-bold text-gray-900 text-sm">{{ $data->user->name }}</div>
                                                    <div class="text-xs text-gray-500 flex items-center mt-0.5">
                                                        <i class="fas fa-university mr-1.5 text-gray-300"></i> {{ Str::limit($data->user->asal_instansi, 30) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="flex flex-col gap-1 items-start">
                                                <div class="text-sm font-medium text-gray-700 bg-gray-50 px-3 py-1 rounded-lg border border-gray-200">
                                                    {{ $data->position->instansi->nama_dinas }}
                                                </div>
                                                @if($data->is_automatic_placement)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-teal-50 text-teal-700 border border-teal-200 gap-1 mt-1">
                                                        <i class="fas fa-magic text-[8px]"></i> Penempatan Otomatis
                                                    </span>
                                                @endif
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="flex flex-col text-xs text-gray-500 font-medium">
                                                <span>{{ \Carbon\Carbon::parse($data->tanggal_mulai)->format('d M Y') }}</span>
                                                <span class="text-gray-300 text-[10px] py-0.5"><i class="fas fa-arrow-down"></i></span>
                                                <span>{{ \Carbon\Carbon::parse($data->tanggal_selesai)->format('d M Y') }}</span>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($data->status == 'selesai')
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                                                    Selesai
                                                </span>
                                            @elseif($data->status == 'diterima')
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-green-50 text-green-700 border border-green-200">
                                                    Aktif
                                                </span>
                                            @elseif($data->status == 'pending')
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-yellow-50 text-yellow-700 border border-yellow-200">
                                                    Pending
                                                </span>
                                            @elseif($data->status == 'ditolak')
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-red-50 text-red-700 border border-red-200">
                                                    Ditolak
                                                </span>
                                            @else
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                                                    {{ ucfirst($data->status) }}
                                                </span>
                                            @endif
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
                                                <p class="text-gray-500 text-sm mt-1">Coba ubah filter pencarian Anda.</p>
                                                <a href="{{ route('admin.laporan.peserta_global') }}" class="mt-4 text-teal-600 hover:text-teal-800 text-sm font-bold hover:underline">
                                                    Reset Semua Filter
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>