<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-chalkboard-teacher text-teal-600"></i>
                {{ __('Dashboard Pembimbing Lapangan Lapangan') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-indigo-100 relative overflow-hidden group hover:shadow-md transition">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition">
                        <i class="fas fa-users text-6xl text-indigo-500"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-xs font-bold text-indigo-500 uppercase tracking-widest mb-1">Peserta Bimbingan</p>
                        <h3 class="text-3xl font-black text-gray-800">{{ $interns->count() }}</h3>
                        <p class="text-xs text-gray-500 mt-2">Total mahasiswa yang Anda bimbing.</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-teal-100 relative overflow-hidden group hover:shadow-md transition">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition">
                        <i class="fas fa-user-check text-6xl text-teal-500"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-xs font-bold text-teal-500 uppercase tracking-widest mb-1">Status Aktif</p>
                        <h3 class="text-3xl font-black text-gray-800">{{ $interns->where('status', 'diterima')->count() }}</h3>
                        <p class="text-xs text-gray-500 mt-2">Mahasiswa yang sedang menjalani magang.</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-list text-gray-400"></i> Daftar Mahasiswa Bimbingan
                        </h3>
                        <p class="text-xs text-gray-500 mt-1">Kelola dan pantau aktivitas harian peserta.</p>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Profil Peserta</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Posisi Magang</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Periode & Status</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @forelse($interns as $mhs)
                            <tr class="hover:bg-gray-50 transition group {{ $mhs->status == 'selesai' ? 'bg-gray-50/60' : '' }}">
                                
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-100 to-indigo-200 flex items-center justify-center text-indigo-700 font-bold border border-indigo-300 shadow-sm">
                                                {{ strtoupper(substr($mhs->user->name, 0, 1)) }}
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900 group-hover:text-indigo-600 transition">{{ $mhs->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $mhs->user->email }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-700 bg-gray-100 px-3 py-1 rounded-lg w-fit border border-gray-200">
                                        {{ $mhs->position->judul_posisi }}
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1">
                                        <div class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($mhs->tanggal_mulai)->format('d M') }} - {{ \Carbon\Carbon::parse($mhs->tanggal_selesai)->format('d M Y') }}
                                        </div>
                                        <div>
                                            @if($mhs->status == 'diterima')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-700 border border-green-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5 animate-pulse"></span>
                                                    Sedang Magang
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700 border border-blue-200">
                                                    <i class="fas fa-check-circle mr-1.5"></i> Selesai
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-right">
                                    @if($mhs->status == 'diterima')
                                        <a href="{{ route('pembimbing_lapangan.logbook', $mhs->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition shadow-md shadow-indigo-200 transform hover:-translate-y-0.5">
                                            <i class="fas fa-book-reader mr-2"></i> Periksa Logbook
                                        </a>
                                    @else
                                        <a href="{{ route('pembimbing_lapangan.logbook', $mhs->id) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 text-gray-600 rounded-xl text-xs font-bold hover:bg-gray-50 transition shadow-sm">
                                            <i class="fas fa-history mr-2"></i> Riwayat
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                            <i class="fas fa-user-slash text-3xl text-gray-300"></i>
                                        </div>
                                        <p class="font-bold text-gray-600">Belum ada peserta bimbingan</p>
                                        <p class="text-xs mt-1">Anda belum ditugaskan untuk membimbing peserta magang manapun.</p>
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