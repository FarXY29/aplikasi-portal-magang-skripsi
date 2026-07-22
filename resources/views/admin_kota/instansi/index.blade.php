<x-app-layout>
    @push('head')
        <meta name="turbo-cache-control" content="no-cache">
    @endpush
    @push('styles')
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <style>
            .action-btn { transition: all 0.2s ease; }
            .action-btn:hover { transform: translateY(-1px); }
            .table-row { transition: background-color 0.15s ease; }
        </style>
    @endpush

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 w-full">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-teal-600 dark:bg-teal-500 text-white flex items-center justify-center shadow-sm">
                    <i class="fas fa-building text-sm"></i>
                </div>
                <div>
                    <h2 class="font-black text-xl text-gray-900 dark:text-gray-100 leading-tight">Data Master Instansi</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium hidden sm:block">Kelola daftar dinas & instansi pemerintah kota</p>
                </div>
            </div>
            <div class="text-xs font-bold text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-800 px-3 py-1.5 rounded-xl shadow-xs border border-gray-200 dark:border-gray-700">
                Total Instansi: <span class="font-black text-teal-600 dark:text-teal-400">{{ $instansis->total() }}</span>
            </div>
        </div>
    </x-slot>

    <div class="space-y-5 font-[Inter]">
        
        <div class="flex flex-col gap-4 mb-6 print:hidden">
            <div class="flex justify-between items-center">
                <a href="{{ route('admin.dashboard') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-sm">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Dashboard
                </a>
            </div>

            @if(session('success'))
                <x-ui.alert type="success" class="mb-2">
                    {{ session('success') }}
                </x-ui.alert>
            @endif

            <x-ui.filter-bar :action="route('admin.instansi.index')" :resetUrl="request()->has('search') ? route('admin.instansi.index') : null">
                <div class="flex-grow min-w-[200px]">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama instansi atau kode unit..." class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:border-teal-500 focus:ring-teal-500 py-2 px-3 shadow-sm font-medium">
                </div>
                
                <div class="flex items-center gap-2.5 ml-auto pl-4 border-l border-gray-100 dark:border-gray-700">
                    <a href="{{ route('admin.instansi.print_pdf') }}" target="_blank" class="action-btn inline-flex items-center justify-center px-4 py-2 bg-gray-800 dark:bg-gray-700 text-white rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-gray-700 dark:hover:bg-gray-600 active:scale-95 transition shadow-sm">
                        <i class="fas fa-print mr-2 text-[10px]"></i> Cetak PDF
                    </a>
                    <a href="{{ route('admin.instansi.create') }}" class="action-btn inline-flex items-center justify-center px-4 py-2 bg-teal-600 dark:bg-teal-500 text-white rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-teal-700 dark:hover:bg-teal-600 active:scale-95 transition shadow-sm">
                        <i class="fas fa-plus mr-2 text-[10px]"></i> Tambah
                    </a>
                </div>
            </x-ui.filter-bar>
        </div>

        {{-- Table & Card List Container --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            
            {{-- Desktop Table View (md+) --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th scope="col" class="px-5 py-3.5 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Instansi</th>
                            <th scope="col" class="px-5 py-3.5 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-40">Kode Unit</th>
                            <th scope="col" class="px-5 py-3.5 text-center text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-48">Statistik Magang</th>
                            <th scope="col" class="px-5 py-3.5 scope-col text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-40">Koordinat</th>
                            <th scope="col" class="px-5 py-3.5 text-right text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700/60">
                        @forelse($instansis as $index => $dinas)
                        <tr class="table-row hover:bg-gray-50 dark:hover:bg-gray-900/60 group">
                            {{-- Nama Instansi --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-teal-50 dark:bg-teal-950/40 rounded-xl flex items-center justify-center text-teal-600 dark:text-teal-400 font-black border border-teal-100 dark:border-teal-900/50">
                                        {{ substr($dinas->nama_dinas, 0, 1) }}
                                    </div>
                                    <div class="ml-3.5 max-w-md">
                                        <div class="text-sm font-bold text-gray-900 dark:text-gray-100 leading-tight group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors">
                                            {{ $dinas->nama_dinas }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 flex items-start mt-1 gap-1.5 font-medium">
                                            <i class="fas fa-map-marker-alt text-gray-400 dark:text-gray-500 mt-0.5 shrink-0"></i>
                                            <span class="line-clamp-1" title="{{ $dinas->alamat }}">
                                                {{ $dinas->alamat ? $dinas->alamat : 'Alamat belum diisi' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Kode Unit --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-700 font-mono">
                                    {{ $dinas->kode_unit_kerja }}
                                </span>
                            </td>

                            {{-- Statistik --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center gap-3">
                                    @php
                                        $posCount = $dinas->positions_count ?? $dinas->positions->count();
                                    @endphp
                                    <div class="flex flex-col items-center">
                                        <span class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-0.5">Lowongan</span>
                                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-black {{ $posCount > 0 ? 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 border border-blue-100 dark:border-blue-900/50' : 'bg-gray-50 dark:bg-gray-900 text-gray-400 dark:text-gray-500 border border-gray-100 dark:border-gray-700' }}">
                                            {{ $posCount }}
                                        </span>
                                    </div>

                                    <div class="h-6 w-px bg-gray-100 dark:bg-gray-700"></div>

                                    @php
                                        $totalPeserta = $dinas->applications_count ?? 0;
                                        if(!isset($dinas->applications_count)) {
                                            $totalPeserta = $dinas->positions->flatMap->applications
                                                ->whereIn('status', ['diterima', 'selesai'])->count();
                                        }
                                    @endphp
                                    <div class="flex flex-col items-center">
                                        <span class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-0.5">Peserta</span>
                                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-black {{ $totalPeserta > 0 ? 'bg-green-50 dark:bg-green-950/40 text-green-700 dark:text-green-400 border border-green-100 dark:border-green-900/50' : 'bg-gray-50 dark:bg-gray-900 text-gray-400 dark:text-gray-500 border border-gray-100 dark:border-gray-700' }}">
                                            {{ $totalPeserta }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            {{-- Lokasi Koordinat --}}
                            <td class="px-5 py-4 whitespace-nowrap text-xs">
                                <div class="flex flex-col gap-1 font-mono text-[10px] text-gray-500 dark:text-gray-400">
                                    <span class="bg-gray-50 dark:bg-gray-900 px-1.5 py-0.5 rounded border border-gray-100 dark:border-gray-700 w-fit">Lat: {{ $dinas->latitude ?? '-' }}</span>
                                    <span class="bg-gray-50 dark:bg-gray-900 px-1.5 py-0.5 rounded border border-gray-100 dark:border-gray-700 w-fit">Lng: {{ $dinas->longitude ?? '-' }}</span>
                                </div>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-5 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.instansi.edit', $dinas->id) }}" class="p-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-teal-600 dark:text-teal-400 hover:bg-teal-50 dark:hover:bg-teal-950/40 hover:border-teal-200 dark:hover:border-teal-800 transition shadow-2xs" title="Edit Data">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                    
                                    <form action="{{ route('admin.instansi.destroy', $dinas->id) }}" method="POST" @submit.prevent="$dispatch('open-confirm', { message: 'APAKAH ANDA YAKIN?\n\nMenghapus instansi ini akan menghapus:\n- Semua User Admin terkait\n- Semua Lowongan Magang\n- Data Pelamar terkait\n\nTindakan ini tidak dapat dibatalkan!', onConfirm: () => $el.submit() })">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-red-500 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/40 hover:border-red-200 dark:hover:border-red-900/50 transition shadow-2xs" title="Hapus Instansi">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-900 border border-transparent dark:border-gray-700 flex items-center justify-center">
                                        <i class="fas fa-folder-open text-xl text-gray-300 dark:text-gray-600"></i>
                                    </div>
                                    <p class="text-sm font-bold text-gray-500 dark:text-gray-400">Belum ada data Instansi.</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">Mulai dengan menambahkan data dinas baru.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile Card View (<md) --}}
            <div class="md:hidden divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($instansis as $index => $dinas)
                <div class="p-4 space-y-3.5 hover:bg-gray-50 dark:hover:bg-gray-900/50 transition">
                    <div class="flex items-start gap-3">
                        <div class="h-10 w-10 rounded-xl bg-teal-50 dark:bg-teal-950/40 flex items-center justify-center text-teal-600 dark:text-teal-400 font-bold text-base border border-teal-100 dark:border-teal-900/50 shrink-0">
                            {{ substr($dinas->nama_dinas, 0, 1) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100 leading-snug break-words">
                                {{ $dinas->nama_dinas }}
                            </h4>
                            <span class="inline-block mt-1 px-2 py-0.5 rounded-md bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400 font-bold text-[10px] border border-gray-200 dark:border-gray-700 font-mono">
                                {{ $dinas->kode_unit_kerja }}
                            </span>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-100 dark:border-gray-700 text-xs text-gray-600 dark:text-gray-400 space-y-2">
                        <div class="flex items-start gap-1.5">
                            <i class="fas fa-map-marker-alt text-teal-600 dark:text-teal-400 mt-0.5 shrink-0"></i>
                            <span class="leading-relaxed break-words font-medium">
                                {{ $dinas->alamat ? $dinas->alamat : 'Alamat belum diisi' }}
                            </span>
                        </div>
                        <div class="flex gap-2 pt-1.5 border-t border-gray-200 dark:border-gray-700/60 font-mono text-[10px] text-gray-500 dark:text-gray-400">
                            <span class="bg-white dark:bg-gray-800 px-2 py-0.5 rounded border border-gray-200 dark:border-gray-700">Lat: {{ $dinas->latitude ?? '-' }}</span>
                            <span class="bg-white dark:bg-gray-800 px-2 py-0.5 rounded border border-gray-200 dark:border-gray-700">Lng: {{ $dinas->longitude ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        @php
                            $posCount = $dinas->positions_count ?? $dinas->positions->count();
                            $totalPeserta = $dinas->applications_count ?? 0;
                            if(!isset($dinas->applications_count)) {
                                $totalPeserta = $dinas->positions->flatMap->applications
                                    ->whereIn('status', ['diterima', 'selesai'])->count();
                            }
                        @endphp
                        <div class="flex flex-col items-center justify-center p-2 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700/60 shadow-2xs">
                            <span class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Lowongan</span>
                            <span class="text-sm font-black text-blue-600 dark:text-blue-400 mt-0.5">{{ $posCount }}</span>
                        </div>
                        <div class="flex flex-col items-center justify-center p-2 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700/60 shadow-2xs">
                            <span class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Peserta</span>
                            <span class="text-sm font-black text-green-600 dark:text-green-400 mt-0.5">{{ $totalPeserta }}</span>
                        </div>
                    </div>

                    <div class="flex gap-2 pt-1">
                        <a href="{{ route('admin.instansi.edit', $dinas->id) }}" class="flex-1 py-2 px-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl text-teal-700 dark:text-teal-300 font-bold text-xs flex items-center justify-center gap-1.5 hover:bg-teal-50 dark:hover:bg-teal-950/40 hover:border-teal-200 dark:hover:border-teal-800 transition shadow-2xs">
                            <i class="fas fa-edit text-teal-600 dark:text-teal-400"></i> Edit
                        </a>
                        
                        <form action="{{ route('admin.instansi.destroy', $dinas->id) }}" method="POST" class="flex-1" @submit.prevent="$dispatch('open-confirm', { message: 'APAKAH ANDA YAKIN?\n\nMenghapus instansi ini akan menghapus:\n- Semua User Admin terkait\n- Semua Lowongan Magang\n- Data Pelamar terkait\n\nTindakan ini tidak dapat dibatalkan!', onConfirm: () => $el.submit() })">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full py-2 px-3 bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900/50 rounded-xl text-red-600 dark:text-red-400 font-bold text-xs flex items-center justify-center gap-1.5 hover:bg-red-600 hover:text-white dark:hover:bg-red-600 dark:hover:text-white transition shadow-2xs">
                                <i class="fas fa-trash-alt text-red-500 dark:text-red-400"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="p-10 text-center text-gray-400 dark:text-gray-500">
                    <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-900 border border-transparent dark:border-gray-700 flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-folder-open text-xl text-gray-300 dark:text-gray-600"></i>
                    </div>
                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400">Belum ada data Instansi.</p>
                </div>
                @endforelse
            </div>
            
            @if($instansis->hasPages())
            <div class="bg-gray-50 dark:bg-gray-900 px-5 py-3.5 border-t border-gray-100 dark:border-gray-700">
                {{ $instansis->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>