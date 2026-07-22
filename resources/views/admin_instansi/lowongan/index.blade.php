<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <i class="fas fa-briefcase text-teal-600 dark:text-teal-400"></i>
                {{ __('Kelola Lowongan Magang') }}
            </h2>
            <div class="text-sm text-gray-500 dark:text-gray-400 font-medium bg-white dark:bg-gray-800 px-4 py-1.5 rounded-full shadow-sm border border-gray-100 dark:border-gray-700">
                Total Posisi: <span class="font-bold text-teal-600 dark:text-teal-400">{{ $lowongans->count() }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex flex-col gap-4 mb-6 print:hidden">
                <div class="flex justify-between items-center">
                    <a href="{{ route('dinas.dashboard') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                        <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-sm">
                            <i class="fas fa-arrow-left text-xs"></i>
                        </div>
                        Kembali ke Dashboard
                    </a>
                </div>

                <x-ui.filter-bar :action="route('dinas.lowongan.index')" :resetUrl="request()->hasAny(['search', 'status']) ? route('dinas.lowongan.index') : null">
                    <div class="flex-grow min-w-[200px]">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari posisi, syarat, atau deskripsi..." class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:border-teal-500 focus:ring-teal-500 py-2 px-3 shadow-sm font-medium">
                    </div>

                    <div class="min-w-[150px]">
                        <select name="status" class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 focus:border-teal-500 focus:ring-teal-500 py-2 pl-3 pr-8 cursor-pointer shadow-sm font-bold">
                            <option value="">Semua Status</option>
                            <option value="buka" {{ request('status') == 'buka' ? 'selected' : '' }}>Buka</option>
                            <option value="tutup" {{ request('status') == 'tutup' ? 'selected' : '' }}>Tutup</option>
                        </select>
                    </div>
                    
                    <div class="flex items-center ml-auto pl-4 border-l border-gray-100 dark:border-gray-700">
                        <a href="{{ route('dinas.lowongan.create') }}" class="flex items-center px-4 py-2 bg-teal-600 dark:bg-teal-500 text-white rounded-xl font-bold hover:bg-teal-700 dark:hover:bg-teal-600 shadow-sm transition transform active:scale-95 text-xs uppercase tracking-wider">
                            <i class="fas fa-plus mr-2 text-[10px]"></i> Buat Lowongan
                        </a>
                    </div>
                </x-ui.filter-bar>
            </div>

            @if(session('success'))
                <x-ui.alert type="success" class="mb-4">
                    {{ session('success') }}
                </x-ui.alert>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-100 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th scope="col" class="px-5 py-3.5 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-1/3">Posisi & Syarat</th>
                                <th scope="col" class="px-5 py-3.5 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kuota & Deadline</th>
                                <th scope="col" class="px-5 py-3.5 text-center text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-5 py-3.5 text-right text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700/60">
                            @forelse($lowongans as $loker)
                            <tr class="table-row hover:bg-teal-50/15 dark:hover:bg-gray-900/60 transition duration-150 group">
                                
                                <td class="px-5 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-900 dark:text-gray-100 mb-1 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition">
                                            {{ $loker->judul_posisi }}
                                        </span>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 mb-2" title="{{ $loker->deskripsi }}">
                                            {{ $loker->deskripsi }}
                                        </p>
                                        <div class="flex items-center gap-1.5 text-[10px] text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/60 border border-teal-100 dark:border-teal-900/40 px-2.5 py-1 rounded-lg w-fit font-bold">
                                            <i class="fas fa-graduation-cap text-teal-600 dark:text-teal-400"></i> {{ Str::limit($loker->required_major, 30) }}
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center text-sm font-bold text-gray-800 dark:text-gray-200">
                                            <i class="fas fa-users mr-2 text-gray-400 dark:text-gray-500"></i> {{ $loker->kuota }} <span class="text-xs font-normal text-gray-500 dark:text-gray-400 ml-1">orang</span>
                                        </div>
                                        <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                            <i class="far fa-calendar-alt mr-2 text-gray-400 dark:text-gray-500"></i> 
                                            {{ \Carbon\Carbon::parse($loker->batas_daftar)->format('d M Y') }}
                                        </div>
                                        
                                        @php
                                            $daysLeft = now()->diffInDays(\Carbon\Carbon::parse($loker->batas_daftar), false);
                                        @endphp
                                        @if($daysLeft >= 0)
                                            <span class="text-[10px] text-green-600 dark:text-green-400 font-bold mt-1">Sisa {{ ceil($daysLeft) }} hari lagi</span>
                                        @else
                                            <span class="text-[10px] text-red-500 dark:text-red-400 font-bold mt-1">Pendaftaran Tutup</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap text-center">
                                    @if($loker->status == 'buka')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 dark:bg-green-950/60 text-green-700 dark:text-green-300 border border-green-200 dark:border-green-800">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5 animate-pulse"></span> Buka
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 dark:bg-red-950/60 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-800">
                                            Tutup
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('dinas.lowongan.edit', $loker->id) }}" class="p-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 transition shadow-xs" title="Edit Data">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <form action="{{ route('dinas.lowongan.destroy', $loker->id) }}" method="POST" onsubmit="return confirm('Hapus lowongan ini? Data tidak dapat dikembalikan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-red-500 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/40 transition shadow-xs" title="Hapus Lowongan">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                        <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-4 text-gray-400 dark:text-gray-500 border border-gray-200 dark:border-gray-700">
                                            <i class="fas fa-briefcase text-3xl"></i>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Belum Ada Lowongan</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Buat lowongan magang pertama Anda sekarang.</p>
                                        <a href="{{ route('dinas.lowongan.create') }}" class="mt-4 text-teal-600 dark:text-teal-400 font-bold text-sm hover:underline">
                                            + Tambah Lowongan
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
</x-app-layout>