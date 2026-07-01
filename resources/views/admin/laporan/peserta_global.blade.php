<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-globe-asia text-teal-600"></i>
                {{ __('Laporan Global Peserta') }}
            </h2>
            <div class="text-sm text-gray-500 font-medium bg-white px-4 py-1.5 rounded-full shadow-sm border border-gray-100">
                Total Data Terfilter: <span class="font-bold text-teal-600">{{ $stats['total'] }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen font-sans">
        <div class="flex justify-between items-center mb-6 print:hidden max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('admin.laporan.hub') }}" class="group flex items-center text-sm font-bold text-gray-500 hover:text-teal-600 transition">
                <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                    <i class="fas fa-arrow-left text-xs"></i>
                </div>
                Kembali ke Pusat Laporan
            </a>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8 items-start">

                {{-- Kolom Kiri: Filter --}}
                <div class="w-full lg:w-1/4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100 print:hidden lg:sticky lg:top-8">
                    <div class="mb-5 border-b border-gray-100 pb-3 flex items-center justify-between">
                        <h3 class="text-gray-800 font-bold text-sm uppercase tracking-wide flex items-center">
                            <i class="fas fa-filter mr-2 text-teal-500"></i> Filter Laporan
                        </h3>
                        @if(request()->anyFilled(['instansi', 'instansi_id', 'status', 'start_date', 'end_date', 'posisi']))
                            <a href="{{ route('admin.laporan.peserta_global') }}" class="text-xs text-red-500 hover:text-red-700 font-bold">Reset</a>
                        @endif
                    </div>
                    
                    <form method="GET" action="{{ route('admin.laporan.peserta_global') }}" class="flex flex-col gap-5">
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Asal Kampus / Sekolah</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                                    <i class="fas fa-university text-xs"></i>
                                </span>
                                <select name="instansi" class="w-full pl-9 border-gray-200 rounded-xl text-sm focus:ring-teal-500 focus:border-teal-500 shadow-sm cursor-pointer bg-gray-50 hover:bg-white transition">
                                    <option value="">Semua Kampus</option>
                                    @foreach($listInstansi as $instansi)
                                        <option value="{{ $instansi }}" {{ request('instansi') == $instansi ? 'selected' : '' }}>
                                            {{ Str::limit($instansi, 25) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Lokasi Penempatan Dinas</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                                    <i class="fas fa-building text-xs"></i>
                                </span>
                                <select name="instansi_id" class="w-full pl-9 border-gray-200 rounded-xl text-sm focus:ring-teal-500 focus:border-teal-500 shadow-sm cursor-pointer bg-gray-50 hover:bg-white transition">
                                    <option value="">Semua Lokasi Dinas</option>
                                    @foreach($listDinas as $dinas)
                                        <option value="{{ $dinas->id }}" {{ request('instansi_id') == $dinas->id ? 'selected' : '' }}>
                                            {{ Str::limit($dinas->nama_dinas, 25) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Kata Kunci Posisi</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                                    <i class="fas fa-briefcase text-xs"></i>
                                </span>
                                <input type="text" name="posisi" value="{{ request('posisi') }}" 
                                    placeholder="Contoh: Programmer..."
                                    class="w-full pl-9 border-gray-200 rounded-xl text-sm focus:ring-teal-500 focus:border-teal-500 shadow-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Status Magang</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                                    <i class="fas fa-info-circle text-xs"></i>
                                </span>
                                <select name="status" class="w-full pl-9 border-gray-200 rounded-xl text-sm focus:ring-teal-500 focus:border-teal-500 shadow-sm cursor-pointer bg-gray-50 hover:bg-white transition">
                                    <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua Status</option>
                                    <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Aktif (Sedang Magang)</option>
                                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai / Lulus</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1.5 leading-relaxed">*Default filter hanya menampilkan status Aktif & Selesai jika belum ada filter status yang dipilih.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Irisan Periode Magang</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="date" name="start_date" value="{{ request('start_date') }}" 
                                    class="w-full border-gray-200 rounded-xl text-xs focus:ring-teal-500 focus:border-teal-500 shadow-sm" title="Dari Tanggal">
                                <input type="date" name="end_date" value="{{ request('end_date') }}" 
                                    class="w-full border-gray-200 rounded-xl text-xs focus:ring-teal-500 focus:border-teal-500 shadow-sm" title="Sampai Tanggal">
                            </div>
                        </div>

                        <button type="submit" class="mt-2 w-full bg-teal-600 text-white py-2.5 rounded-xl shadow-lg shadow-teal-200 hover:bg-teal-700 text-sm font-bold transition transform active:scale-95 flex items-center justify-center">
                            <i class="fas fa-search mr-2"></i> Terapkan Filter
                        </button>
                    </form>
                </div>

                {{-- Kolom Kanan: Stats & Tabel --}}
                <div class="w-full lg:w-3/4 space-y-6">
                    
                    {{-- Stats Cards Grid --}}
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 text-center">
                            <div class="w-8 h-8 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center mx-auto mb-2 border border-teal-100">
                                <i class="fas fa-users text-xs"></i>
                            </div>
                            <p class="text-xl font-black text-gray-800">{{ $stats['total'] }}</p>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mt-1">Total Pendaftar</p>
                        </div>
                        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 text-center">
                            <div class="w-8 h-8 rounded-full bg-green-50 text-green-600 flex items-center justify-center mx-auto mb-2 border border-green-100">
                                <i class="fas fa-user-clock text-xs"></i>
                            </div>
                            <p class="text-xl font-black text-green-700">{{ $stats['aktif'] }}</p>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mt-1">Status Aktif</p>
                        </div>
                        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 text-center">
                            <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-2 border border-blue-100">
                                <i class="fas fa-graduation-cap text-xs"></i>
                            </div>
                            <p class="text-xl font-black text-blue-700">{{ $stats['selesai'] }}</p>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mt-1">Selesai / Lulus</p>
                        </div>
                        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 text-center">
                            <div class="w-8 h-8 rounded-full bg-yellow-50 text-yellow-600 flex items-center justify-center mx-auto mb-2 border border-yellow-100">
                                <i class="fas fa-hourglass-half text-xs"></i>
                            </div>
                            <p class="text-xl font-black text-yellow-650" style="color: #d97706;">{{ $stats['pending'] }}</p>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mt-1">Pending</p>
                        </div>
                        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 text-center">
                            <div class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-2 border border-indigo-100">
                                <i class="fas fa-building text-xs"></i>
                            </div>
                            <p class="text-xl font-black text-indigo-700">{{ $stats['total_dinas'] }}</p>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mt-1">Dinas Terlibat</p>
                        </div>
                        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 text-center">
                            <div class="w-8 h-8 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-2 border border-rose-100">
                                <i class="fas fa-university text-xs"></i>
                            </div>
                            <p class="text-xl font-black text-rose-700">{{ $stats['total_kampus'] }}</p>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mt-1">Kampus Terlibat</p>
                        </div>
                    </div>

                    {{-- Highlight Banner --}}
                    <div class="bg-gradient-to-r from-teal-700 to-indigo-700 rounded-3xl p-6 text-white shadow-lg shadow-teal-700/20 flex flex-col sm:flex-row items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-2xl flex-shrink-0">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="text-center sm:text-left flex-grow">
                            <p class="text-xs font-bold uppercase tracking-wider text-teal-100">Rekapitulasi Global Peserta Magang Kota</p>
                            <p class="text-xl font-black mt-0.5">Total {{ $stats['total'] }} Peserta Terfilter</p>
                            <p class="text-sm text-teal-50 font-medium">Tersebar di {{ $stats['total_dinas'] }} dinas instansi dari {{ $stats['total_kampus'] }} kampus sekolah mitra.</p>
                        </div>
                        @if($allInterns->count() > 0)
                        <div class="sm:ml-auto flex-shrink-0">
                            <a href="{{ route('admin.laporan.peserta_global.print', request()->query()) }}" target="_blank" class="inline-flex items-center px-5 py-2.5 bg-white text-teal-700 rounded-xl hover:bg-teal-50 transition text-sm font-bold shadow-md">
                                <i class="fas fa-file-pdf mr-2"></i> Download PDF
                            </a>
                        </div>
                        @endif
                    </div>

                    {{-- Main Table Card --}}
                    <div class="bg-white shadow-sm sm:rounded-3xl border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-bold text-gray-800">Daftar Rekapitulasi Global Peserta</h3>
                            <p class="text-xs text-gray-500 mt-1">Menampilkan rincian profil peserta, penempatan divisi dinas instansi, periode/durasi kerja, serta status magang saat ini.</p>
                        </div>

                        <div class="overflow-x-auto max-h-[500px] overflow-y-auto">
                            <table class="min-w-full divide-y divide-gray-100 border-collapse">
                                <thead class="bg-gray-50 sticky top-0 z-20 shadow-[inset_0_-1px_0_rgba(229,231,235,1)]">
                                    <tr>
                                        <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-12">No</th>
                                        <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Peserta & Asal Kampus</th>
                                        <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Penempatan Dinas & Posisi</th>
                                        <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Periode Magang</th>
                                        <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-28">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-50 text-sm">
                                    @forelse($allInterns as $data)
                                    <tr class="hover:bg-teal-50/15 transition group">
                                        <td class="px-5 py-4 text-center text-gray-400 font-bold text-xs">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="h-9 w-9 rounded-full bg-gradient-to-br from-teal-50 to-teal-100 flex items-center justify-center text-teal-700 font-black border border-teal-200/50 text-xs flex-shrink-0">
                                                    {{ strtoupper(substr($data->user->name, 0, 2)) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="font-bold text-gray-900 truncate">{{ $data->user->name }}</div>
                                                    <div class="text-[10px] text-gray-500 font-semibold truncate flex items-center mt-0.5">
                                                        <i class="fas fa-university mr-1.5 text-gray-300"></i> {{ $data->user->asal_instansi }}
                                                    </div>
                                                    <div class="flex items-center gap-2 text-[9px] text-gray-400 mt-1 flex-wrap font-medium">
                                                        <span>{{ $data->user->email }}</span>
                                                        @if($data->user->phone)
                                                        <span class="text-gray-300">•</span>
                                                        <span>{{ $data->user->phone }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-5 py-4">
                                            <div class="flex flex-col gap-1 items-start">
                                                <span class="font-bold text-gray-800 text-xs flex items-center gap-1.5">
                                                    <i class="far fa-building text-gray-400"></i>
                                                    {{ $data->position->instansi->nama_dinas }}
                                                </span>
                                                <span class="text-[10px] text-gray-500 font-medium">
                                                    Posisi: <span class="font-bold text-gray-600">{{ $data->position->judul_posisi }}</span>
                                                </span>
                                                @if($data->is_automatic_placement)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-extrabold bg-teal-50 text-teal-700 border border-teal-100 gap-0.5 mt-0.5">
                                                        <i class="fas fa-magic text-[7px]"></i> Penempatan Otomatis
                                                    </span>
                                                @endif
                                            </div>
                                        </td>

                                        <td class="px-5 py-4">
                                            <div class="flex flex-col gap-1">
                                                <span class="text-xs font-medium text-gray-700 flex items-center gap-1.5">
                                                    <i class="far fa-calendar text-gray-400"></i>
                                                    {{ \Carbon\Carbon::parse($data->tanggal_mulai)->format('d M Y') }} 
                                                    <span class="text-gray-300">➜</span> 
                                                    {{ \Carbon\Carbon::parse($data->tanggal_selesai)->format('d M Y') }}
                                                </span>
                                                <span class="text-[9px] text-teal-600 bg-teal-50 px-2 py-0.5 rounded w-fit font-bold">
                                                    {{ \Carbon\Carbon::parse($data->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($data->tanggal_selesai)) }} Hari
                                                </span>
                                            </div>
                                        </td>

                                        <td class="px-5 py-4 text-center">
                                            @php
                                                $statusConfig = [
                                                    'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Pending'],
                                                    'menunggu' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Pending'],
                                                    'diterima' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Aktif'],
                                                    'selesai' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Selesai'],
                                                    'ditolak' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Ditolak'],
                                                ];
                                                $s = $statusConfig[$data->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => ucfirst($data->status)];
                                            @endphp
                                            <span class="px-3 py-1 inline-flex text-[10px] leading-5 font-black rounded-full {{ $s['bg'] }} {{ $s['text'] }}">
                                                {{ $s['label'] }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-16 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3 text-gray-300">
                                                    <i class="fas fa-search text-2xl"></i>
                                                </div>
                                                <p class="text-gray-900 font-bold">Data tidak ditemukan</p>
                                                <p class="text-gray-500 text-sm mt-1">Coba sesuaikan kata kunci pencarian Anda.</p>
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