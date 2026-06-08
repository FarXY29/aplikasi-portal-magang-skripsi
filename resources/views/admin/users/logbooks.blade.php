<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-book-reader text-teal-600"></i>
                {{ __('Monitoring Logbook Peserta') }}
            </h2>
            <div class="text-sm text-gray-500 font-medium bg-white px-4 py-1.5 rounded-full shadow-sm border border-gray-100">
                Total Peserta: <span class="font-bold text-teal-600">{{ $participants->total() }}</span>
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
            </div>

            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                <form method="GET" action="{{ route('admin.users.logbooks') }}" class="relative w-full md:w-96">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Cari nama peserta..." 
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-300 focus:ring-teal-500 focus:border-teal-500 transition shadow-sm text-sm">
                    <button type="submit" class="absolute right-2 top-1.5 bg-teal-600 text-white px-3 py-1 rounded-lg text-xs font-bold hover:bg-teal-700 transition">
                        Cari
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Profil Peserta</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Asal Institusi</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lokasi Magang</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @forelse($participants as $user)
                            <tr class="hover:bg-gray-50 transition duration-150 ease-in-out group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-teal-100 to-teal-200 flex items-center justify-center text-teal-700 font-bold border border-teal-300 shadow-sm">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-university mr-2 text-gray-400"></i>
                                        {{ $user->asal_instansi ?? '-' }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->applications->isNotEmpty())
                                        @php
                                            $app = $user->applications->last(); // Ambil aplikasi terakhir
                                            $statusColor = $app->status == 'selesai' ? 'text-green-600 bg-green-50 border-green-200' : 'text-blue-600 bg-blue-50 border-blue-200';
                                        @endphp
                                        <div class="flex items-center px-3 py-1 rounded-full border {{ $statusColor }} w-fit">
                                            <i class="fas fa-building mr-2 text-xs"></i>
                                            <span class="text-xs font-bold truncate max-w-[150px]" title="{{ $app->position->instansi->nama_dinas }}">
                                                {{ $app->position->instansi->nama_dinas }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Belum Magang
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if($user->applications->isNotEmpty())
                                        <a href="{{ route('admin.users.logbooks.show', $user->id) }}" class="inline-flex items-center px-3 py-1.5 bg-teal-50 text-teal-700 rounded-lg hover:bg-teal-100 hover:text-teal-800 transition font-bold text-xs border border-teal-200 shadow-sm">
                                            <i class="fas fa-book-open mr-1.5"></i> Lihat Logbook
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-xs italic cursor-not-allowed">Tidak ada data</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-users-slash text-4xl mb-3 text-gray-300"></i>
                                        <p class="text-sm font-medium">Belum ada peserta terdaftar.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="md:hidden grid grid-cols-1 gap-4 p-4 bg-gray-50">
                    @forelse($participants as $user)
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                        <div class="flex items-center justify-between mb-3 pb-3 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 font-bold text-sm">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900">{{ $user->name }}</h4>
                                    <p class="text-xs text-gray-500">{{ $user->asal_instansi ?? 'Instansi (-)' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <span class="text-xs font-bold text-gray-400 uppercase">Lokasi Magang</span>
                                @if($user->applications->isNotEmpty())
                                    <p class="text-sm font-medium text-gray-800 mt-1 flex items-center">
                                        <i class="fas fa-building text-teal-500 mr-2"></i>
                                        {{ $user->applications->last()->position->instansi->nama_dinas }}
                                    </p>
                                @else
                                    <p class="text-xs text-red-400 italic mt-1">Belum ada penempatan</p>
                                @endif
                            </div>

                            <div class="pt-2">
                                @if($user->applications->isNotEmpty())
                                    <a href="{{ route('admin.users.logbooks.show', $user->id) }}" class="flex items-center justify-center w-full px-4 py-2 bg-teal-600 text-white rounded-lg text-xs font-bold hover:bg-teal-700 transition shadow-md">
                                        <i class="fas fa-book-open mr-2"></i> Buka Logbook
                                    </a>
                                @else
                                    <button disabled class="w-full px-4 py-2 bg-gray-100 text-gray-400 rounded-lg text-xs font-bold cursor-not-allowed">
                                        Logbook Tidak Tersedia
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500">
                        <p class="text-sm">Tidak ada peserta ditemukan.</p>
                    </div>
                    @endforelse
                </div>

                @if($participants->hasPages())
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                    {{ $participants->links() }}
                </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>