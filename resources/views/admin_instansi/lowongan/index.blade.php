<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-briefcase text-teal-600"></i>
                {{ __('Kelola Lowongan Magang') }}
            </h2>
            <div class="text-sm text-gray-500 font-medium bg-white px-4 py-1.5 rounded-full shadow-sm border border-gray-100">
                Total Posisi: <span class="font-bold text-teal-600">{{ $lowongans->count() }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4 print:hidden">
                <a href="{{ route('dinas.dashboard') }}" class="group flex items-center text-sm font-bold text-gray-500 hover:text-teal-600 transition">
                    <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                    Kembali ke Dashboard
                </a>

                <a href="{{ route('dinas.lowongan.create') }}" class="flex items-center px-5 py-2.5 bg-teal-600 text-white rounded-xl font-bold hover:bg-teal-700 shadow-lg shadow-teal-200 transition transform active:scale-95 text-sm">
                    <i class="fas fa-plus mr-2"></i> Buat Lowongan Baru
                </a>
            </div>

            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" class="flex items-center p-4 mb-4 text-green-800 rounded-xl bg-green-50 border border-green-100 shadow-sm relative">
                    <i class="fas fa-check-circle flex-shrink-0 w-5 h-5 mr-3 text-green-600"></i>
                    <div class="text-sm font-bold">{{ session('success') }}</div>
                    <button @click="show = false" class="ml-auto text-green-500 hover:text-green-700"><i class="fas fa-times"></i></button>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-1/3">Posisi & Syarat</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kuota & Deadline</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @forelse($lowongans as $loker)
                            <tr class="hover:bg-gray-50 transition duration-150 group">
                                
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-900 mb-1 group-hover:text-teal-600 transition">
                                            {{ $loker->judul_posisi }}
                                        </span>
                                        <p class="text-xs text-gray-500 line-clamp-2 mb-2" title="{{ $loker->deskripsi }}">
                                            {{ $loker->deskripsi }}
                                        </p>
                                        <div class="flex items-center gap-1 text-[10px] text-teal-600 bg-teal-50 px-2 py-1 rounded w-fit font-medium">
                                            <i class="fas fa-graduation-cap"></i> {{ Str::limit($loker->required_major, 30) }}
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center text-sm font-bold text-gray-700">
                                            <i class="fas fa-users mr-2 text-gray-400"></i> {{ $loker->kuota }} <span class="text-xs font-normal text-gray-500 ml-1">orang</span>
                                        </div>
                                        <div class="flex items-center text-xs text-gray-500">
                                            <i class="far fa-calendar-alt mr-2 text-gray-400"></i> 
                                            {{ \Carbon\Carbon::parse($loker->batas_daftar)->format('d M Y') }}
                                        </div>
                                        
                                        @php
                                            $daysLeft = now()->diffInDays(\Carbon\Carbon::parse($loker->batas_daftar), false);
                                        @endphp
                                        @if($daysLeft >= 0)
                                            <span class="text-[10px] text-green-600 font-bold mt-1">Sisa {{ ceil($daysLeft) }} hari lagi</span>
                                        @else
                                            <span class="text-[10px] text-red-500 font-bold mt-1">Pendaftaran Tutup</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($loker->status == 'buka')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5 animate-pulse"></span> Buka
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                            Tutup
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity duration-200">
                                        <a href="{{ route('dinas.lowongan.edit', $loker->id) }}" class="p-2 bg-white border border-gray-200 rounded-lg text-indigo-600 hover:bg-indigo-50 hover:border-indigo-200 transition shadow-sm" title="Edit Data">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <form action="{{ route('dinas.lowongan.destroy', $loker->id) }}" method="POST" onsubmit="return confirm('Hapus lowongan ini? Data tidak dapat dikembalikan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 bg-white border border-gray-200 rounded-lg text-red-500 hover:bg-red-50 hover:border-red-200 transition shadow-sm" title="Hapus Lowongan">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 text-gray-300">
                                            <i class="fas fa-briefcase text-3xl"></i>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-700">Belum Ada Lowongan</h3>
                                        <p class="text-sm text-gray-500 mt-1">Buat lowongan magang pertama Anda sekarang.</p>
                                        <a href="{{ route('dinas.lowongan.create') }}" class="mt-4 text-teal-600 font-bold text-sm hover:underline">
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