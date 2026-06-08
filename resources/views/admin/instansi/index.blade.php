<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-building text-teal-600"></i>
                {{ __('Data Master Instansi') }}
            </h2>
            <div class="text-sm text-gray-500 font-medium bg-white px-4 py-1.5 rounded-full shadow-sm border border-gray-100">
                Total Instansi: <span class="font-bold text-teal-600">{{ $instansis->total() }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="group flex items-center text-sm font-bold text-gray-500 hover:text-teal-600 transition">
                    <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                    Kembali ke Dashboard
                </a>

                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50 border border-green-100 shadow-sm w-full md:w-auto" role="alert">
                        <i class="fas fa-check-circle flex-shrink-0 w-5 h-5"></i>
                        <div class="ml-3 text-sm font-medium mr-4">
                            {{ session('success') }}
                        </div>
                        <button type="button" @click="show = false" class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8">
                            <span class="sr-only">Close</span>
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.instansi.print_pdf') }}" target="_blank" class="flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md">
                        <i class="fas fa-print mr-2"></i> Cetak PDF
                    </a>
                    <a href="{{ route('admin.instansi.create') }}" class="flex items-center px-4 py-2 bg-teal-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-teal-700 active:bg-teal-900 focus:outline-none focus:border-teal-900 focus:ring ring-teal-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md">
                        <i class="fas fa-plus mr-2"></i> Tambah Instansi
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Instansi</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kode Unit</th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Statistik</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lokasi</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @forelse($instansis as $dinas)
                            <tr class="hover:bg-gray-50 transition duration-150 ease-in-out group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-teal-50 rounded-full flex items-center justify-center text-teal-600 font-bold border border-teal-100">
                                            {{ substr($dinas->nama_dinas, 0, 1) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900 line-clamp-1" title="{{ $dinas->nama_dinas }}">
                                                {{ $dinas->nama_dinas }}
                                            </div>
                                            <div class="text-xs text-gray-500 flex items-center mt-0.5">
                                                <i class="fas fa-map-marker-alt mr-1.5 text-gray-400"></i>
                                                <span class="line-clamp-1" title="{{ $dinas->alamat }}">
                                                    {{ $dinas->alamat ? Str::limit($dinas->alamat, 35) : 'Alamat belum diisi' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-gray-100 text-gray-600 border border-gray-200">
                                        {{ $dinas->kode_unit_kerja }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        @php
                                            // Asumsi: Jika di controller belum pakai withCount, kita pakai relation count
                                            // Lebih baik jika di controller sudah dioptimasi dengan withCount('positions')
                                            $posCount = $dinas->positions_count ?? $dinas->positions->count();
                                        @endphp
                                        <div class="flex flex-col items-center" title="Jumlah Lowongan">
                                            <span class="text-xs font-bold text-gray-500 mb-0.5">Lowongan</span>
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $posCount > 0 ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-400' }}">
                                                {{ $posCount }}
                                            </span>
                                        </div>

                                        <div class="h-8 w-px bg-gray-200 mx-2"></div>

                                        @php
                                            $totalPeserta = $dinas->applications_count ?? 0; // Jika sudah dioptimasi di controller
                                            
                                            // Fallback jika controller belum dioptimasi (meski disarankan pakai withCount di controller)
                                            if(!isset($dinas->applications_count)) {
                                                $totalPeserta = $dinas->positions->flatMap->applications
                                                    ->whereIn('status', ['diterima', 'selesai'])->count();
                                            }
                                        @endphp
                                        <div class="flex flex-col items-center" title="Peserta Diterima/Selesai">
                                            <span class="text-xs font-bold text-gray-500 mb-0.5">Peserta</span>
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $totalPeserta > 0 ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-400' }}">
                                                {{ $totalPeserta }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex flex-col text-xs">
                                        <span class="font-mono bg-gray-50 px-1.5 rounded w-fit mb-1">Lat: {{ $dinas->latitude ?? '-' }}</span>
                                        <span class="font-mono bg-gray-50 px-1.5 rounded w-fit">Lng: {{ $dinas->longitude ?? '-' }}</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                        <a href="{{ route('admin.instansi.edit', $dinas->id) }}" class="p-2 bg-white border border-gray-200 rounded-lg text-teal-600 hover:bg-teal-50 hover:border-teal-200 transition shadow-sm" title="Edit Data">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <form action="{{ route('admin.instansi.destroy', $dinas->id) }}" method="POST" onsubmit="return confirm('APAKAH ANDA YAKIN?\n\nMenghapus instansi ini akan menghapus:\n- Semua User Admin terkait\n- Semua Lowongan Magang\n- Data Pelamar terkait\n\nTindakan ini tidak dapat dibatalkan!');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 bg-white border border-gray-200 rounded-lg text-red-500 hover:bg-red-50 hover:border-red-200 transition shadow-sm" title="Hapus Instansi">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                    </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4 text-gray-400">
                                            <i class="fas fa-folder-open text-2xl"></i>
                                        </div>
                                        <p class="text-sm font-semibold">Belum ada data Instansi.</p>
                                        <p class="text-xs mt-1">Silakan klik tombol tambah di atas.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($instansis->hasPages())
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                    {{ $instansis->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>