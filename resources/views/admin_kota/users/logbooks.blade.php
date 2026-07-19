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
                <div class="w-9 h-9 rounded-xl bg-teal-600 text-white flex items-center justify-center shadow-sm" style="background-color: #0d9488;">
                    <i class="fas fa-book-open text-sm"></i>
                </div>
                <div>
                    <h2 class="font-black text-xl text-gray-900 dark:text-gray-100 leading-tight">Monitoring Logbook</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium hidden sm:block">Pantau kegiatan harian dan logbook peserta magang</p>
                </div>
            </div>
            <div class="text-xs font-bold text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-800 px-3 py-1.5 rounded-xl shadow-xs border border-gray-200 dark:border-gray-700">
                Total Peserta: <span class="font-black text-teal-600">{{ $participants->total() }}</span>
            </div>
        </div>
    </x-slot>

    <div class="space-y-5 font-[Inter]">
        
        {{-- Navigation & Alert Bar --}}
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <a href="{{ route('admin.dashboard') }}" class="group inline-flex items-center text-xs font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 transition-colors">
                <div class="w-7 h-7 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-xs transition-colors">
                    <i class="fas fa-arrow-left text-[10px]"></i>
                </div>
                Kembali ke Dashboard
            </a>
        </div>

        {{-- Filter & Actions Bar --}}
        <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col md:flex-row justify-between items-center gap-4">
            <form method="GET" action="{{ route('admin.users.logbooks') }}" class="relative w-full md:w-96">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400 text-xs"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Cari nama peserta..." 
                    class="w-full pl-10 pr-20 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-bold focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition shadow-2xs">
                <button type="submit" class="action-btn absolute right-1.5 top-1.5 bg-teal-600 text-white px-3.5 py-1.5 rounded-lg text-xs font-black hover:bg-teal-700 transition shadow-xs" style="background-color: #0d9488;">
                    Cari
                </button>
            </form>
        </div>

        {{-- Table Container --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            
            {{-- Desktop Table View --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th scope="col" class="px-5 py-3.5 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Profil Peserta</th>
                            <th scope="col" class="px-5 py-3.5 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Asal Institusi</th>
                            <th scope="col" class="px-5 py-3.5 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Lokasi Magang</th>
                            <th scope="col" class="px-5 py-3.5 text-right text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100">
                        @forelse($participants as $user)
                        <tr class="table-row hover:bg-gray-50 dark:hover:bg-gray-900/60 group">
                            {{-- Profile --}}
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-teal-50 rounded-xl flex items-center justify-center text-teal-700 font-black border border-teal-100 shadow-2xs">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="ml-3.5">
                                        <div class="text-sm font-bold text-gray-900 dark:text-gray-100 group-hover:text-teal-700 transition-colors">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-medium">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Asal Institusi --}}
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <div class="flex items-center text-xs text-gray-600 dark:text-gray-400 font-medium">
                                    <i class="fas fa-university mr-2 text-gray-400"></i>
                                    {{ $user->asal_instansi ?? '-' }}
                                </div>
                            </td>

                            {{-- Lokasi Magang --}}
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                @if($user->applications->isNotEmpty())
                                    @php
                                        $app = $user->applications->last();
                                        $statusColor = $app->status == 'selesai' ? 'text-green-600 bg-green-50 border-green-200' : 'text-blue-600 bg-blue-50 border-blue-200';
                                    @endphp
                                    <div class="flex items-center px-2.5 py-1 rounded-lg border {{ $statusColor }} w-fit text-xs font-bold">
                                        <i class="fas fa-building mr-1.5 text-[10px]"></i>
                                        <span class="truncate max-w-[200px]" title="{{ $app->position->instansi->nama_dinas }}">
                                            {{ $app->position->instansi->nama_dinas }}
                                        </span>
                                    </div>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-black uppercase bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700">
                                        Belum Magang
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-5 py-3.5 whitespace-nowrap text-right text-sm font-medium">
                                @if($user->applications->isNotEmpty())
                                    <a href="{{ route('admin.users.logbooks.show', $user->id) }}" class="action-btn inline-flex items-center px-3 py-1.5 bg-teal-50 text-teal-700 rounded-lg hover:bg-teal-600 hover:text-white hover:border-teal-600 transition font-bold text-xs border border-teal-200 shadow-2xs">
                                        <i class="fas fa-book-open mr-1.5 text-[10px]"></i> Lihat Logbook
                                    </a>
                                @else
                                    <span class="text-gray-400 text-xs italic font-medium cursor-not-allowed">Tidak ada data</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                        <i class="fas fa-users-slash text-xl text-gray-300"></i>
                                    </div>
                                    <p class="text-sm font-bold text-gray-500 dark:text-gray-400">Belum ada peserta terdaftar.</p>
                                    <p class="text-xs text-gray-400">Logbook akan terisi saat peserta melakukan pengisian harian.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile Card View (<md) --}}
            <div class="md:hidden divide-y divide-gray-100">
                @forelse($participants as $user)
                <div class="p-4 space-y-3.5 hover:bg-gray-50 dark:hover:bg-gray-900/50 transition">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-teal-50 flex items-center justify-center text-teal-700 font-bold text-base border border-teal-100 shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100 truncate">{{ $user->name }}</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">{{ $user->asal_instansi ?? 'Instansi (-)' }}</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-100 dark:border-gray-700 text-xs text-gray-600 dark:text-gray-400 space-y-2">
                        <div>
                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-wider block mb-1">Lokasi Magang</span>
                            @if($user->applications->isNotEmpty())
                                <p class="text-xs font-bold text-gray-800 dark:text-gray-200 flex items-center leading-tight">
                                    <i class="fas fa-building text-teal-600 mr-1.5 shrink-0"></i>
                                    {{ $user->applications->last()->position->instansi->nama_dinas }}
                                </p>
                            @else
                                <p class="text-xs text-gray-400 italic">Belum ada penempatan</p>
                            @endif
                        </div>
                    </div>

                    <div class="pt-1">
                        @if($user->applications->isNotEmpty())
                            <a href="{{ route('admin.users.logbooks.show', $user->id) }}" class="flex items-center justify-center w-full px-4 py-2 bg-teal-600 text-white rounded-xl text-xs font-bold hover:bg-teal-700 transition shadow-2xs" style="background-color: #0d9488;">
                                <i class="fas fa-book-open mr-2 text-[10px]"></i> Buka Logbook
                            </a>
                        @else
                            <button disabled class="w-full px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-400 rounded-xl text-xs font-bold cursor-not-allowed border border-gray-200 dark:border-gray-700">
                                Logbook Tidak Tersedia
                            </button>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-10 text-center text-gray-400">
                    <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-users-slash text-xl text-gray-300"></i>
                    </div>
                    <p class="text-xs font-bold">Tidak ada peserta ditemukan.</p>
                </div>
                @endforelse
            </div>

            @if($participants->hasPages())
            <div class="bg-gray-50 dark:bg-gray-900 px-5 py-3.5 border-t border-gray-100 dark:border-gray-700">
                {{ $participants->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>