<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-inbox text-teal-600"></i>
                {{ __('Daftar Pelamar Magang') }}
            </h2>
            <div class="text-sm text-gray-500 font-medium bg-white px-4 py-1.5 rounded-full shadow-sm border border-gray-100">
                Total Pelamar: <span class="font-bold text-teal-600">{{ $applicants->count() }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 print:hidden">
                <a href="{{ route('dinas.dashboard') }}" class="group flex items-center text-sm font-bold text-gray-500 hover:text-teal-600 transition">
                    <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                    Kembali ke Dashboard
                </a>

                <!-- Filter Status -->
                <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-1">
                        <i class="fas fa-filter text-teal-600"></i> Status:
                    </span>
                    <div class="flex flex-wrap rounded-lg overflow-hidden border border-gray-200">
                        <a href="{{ route('dinas.pelamar', ['status' => 'semua']) }}" 
                           class="px-3 py-1 text-xs font-bold transition {{ request('status') == 'semua' || !request()->has('status') ? 'bg-teal-600 text-white' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                            Semua
                        </a>
                        <a href="{{ route('dinas.pelamar', ['status' => 'pending']) }}" 
                           class="px-3 py-1 text-xs font-bold transition border-l border-r border-gray-200 {{ request('status') == 'pending' ? 'bg-teal-600 text-white' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                            Pending
                        </a>
                        <a href="{{ route('dinas.pelamar', ['status' => 'menunggu']) }}" 
                           class="px-3 py-1 text-xs font-bold transition border-r border-gray-200 {{ request('status') == 'menunggu' ? 'bg-teal-600 text-white' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                            Menunggu
                        </a>
                        <a href="{{ route('dinas.pelamar', ['status' => 'diterima']) }}" 
                           class="px-3 py-1 text-xs font-bold transition border-r border-gray-200 {{ request('status') == 'diterima' ? 'bg-teal-600 text-white' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                            Diterima
                        </a>
                        <a href="{{ route('dinas.pelamar', ['status' => 'ditolak']) }}" 
                           class="px-3 py-1 text-xs font-bold transition border-r border-gray-200 {{ request('status') == 'ditolak' ? 'bg-teal-600 text-white' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                            Ditolak
                        </a>
                        <a href="{{ route('dinas.pelamar', ['status' => 'selesai']) }}" 
                           class="px-3 py-1 text-xs font-bold transition {{ request('status') == 'selesai' ? 'bg-teal-600 text-white' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                            Selesai
                        </a>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" class="flex items-center p-4 mb-4 text-green-800 rounded-xl bg-green-50 border border-green-100 shadow-sm relative">
                    <i class="fas fa-check-circle flex-shrink-0 w-5 h-5 mr-3 text-green-600"></i>
                    <div class="text-sm font-bold">{{ session('success') }}</div>
                    <button @click="show = false" class="ml-auto text-green-500 hover:text-green-700"><i class="fas fa-times"></i></button>
                </div>
            @endif
            
            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" class="flex items-center p-4 mb-4 text-red-800 rounded-xl bg-red-50 border border-red-100 shadow-sm relative">
                    <i class="fas fa-exclamation-triangle flex-shrink-0 w-5 h-5 mr-3 text-red-600"></i>
                    <div class="text-sm font-bold">{{ session('error') }}</div>
                    <button @click="show = false" class="ml-auto text-red-500 hover:text-red-700"><i class="fas fa-times"></i></button>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Profil Peserta</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Periode & Posisi</th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi / Dokumen</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @forelse($applicants as $app)
                            <tr class="hover:bg-gray-50 transition duration-150 ease-in-out group">
                                
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-teal-100 to-teal-200 flex items-center justify-center text-teal-700 font-bold border border-teal-300 shadow-sm">
                                                {{ strtoupper(substr($app->user->name, 0, 1)) }}
                                            </div>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-bold text-gray-900 truncate">{{ $app->user->name }}</div>
                                            <div class="text-xs text-gray-500 flex flex-col gap-0.5">
                                                <span class="flex items-center"><i class="far fa-envelope mr-1.5 w-3"></i> {{ $app->user->email }}</span>
                                                <span class="flex items-center"><i class="fas fa-phone-alt mr-1.5 w-3"></i> {{ $app->user->phone ?? '-' }}</span>
                                                <span class="flex items-center" title="Pembimbing Akademik / Sekolah"><i class="fas fa-chalkboard-teacher mr-1.5 w-3"></i> {{ $app->user->nama_pembimbing_sekolah ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1">
                                        <div class="text-sm font-bold text-gray-800 mb-1">
                                            {{ $app->position->judul_posisi ?? 'Posisi Umum' }}
                                        </div>
                                        
                                        @if($app->is_automatic_placement)
                                            <div class="mb-1.5">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-teal-50 text-teal-700 border border-teal-200 gap-1">
                                                    <i class="fas fa-magic text-[8px]"></i> Penempatan Otomatis
                                                </span>
                                            </div>
                                        @endif

                                        @if($app->tanggal_mulai)
                                            <div class="flex items-center text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded w-fit">
                                                <i class="far fa-calendar-alt mr-1.5 text-gray-400"></i>
                                                <span>{{ \Carbon\Carbon::parse($app->tanggal_mulai)->format('d M Y') }}</span>
                                                <i class="fas fa-arrow-right mx-1.5 text-gray-300 text-[10px]"></i>
                                                <span>{{ \Carbon\Carbon::parse($app->tanggal_selesai)->format('d M Y') }}</span>
                                            </div>
                                            <div class="text-[10px] text-teal-600 font-semibold mt-0.5 ml-1">
                                                Durasi: {{ \Carbon\Carbon::parse($app->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($app->tanggal_selesai)) }} Hari
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Tanggal belum ditentukan</span>
                                        @endif

                                        <div class="mt-1">
                                            @if($app->position->kuota > 0)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                                    Sisa Kuota: {{ $app->position->kuota }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-red-50 text-red-700 border border-red-100">
                                                    Kuota Penuh (0)
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusClass = match($app->status) {
                                            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            'diterima' => 'bg-green-100 text-green-800 border-green-200',
                                            'ditolak' => 'bg-red-100 text-red-800 border-red-200',
                                            'selesai' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            default => 'bg-gray-100 text-gray-800 border-gray-200'
                                        };
                                        $statusIcon = match($app->status) {
                                            'pending' => 'fa-clock',
                                            'diterima' => 'fa-check-circle',
                                            'ditolak' => 'fa-times-circle',
                                            'selesai' => 'fa-graduation-cap',
                                            default => 'fa-question-circle'
                                        };
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full border {{ $statusClass }} items-center gap-1.5">
                                        <i class="fas {{ $statusIcon }}"></i> {{ ucfirst($app->status) }}
                                    </span>

                                    @if($app->status == 'diterima')
                                        <div class="mt-2 text-[10px] text-gray-500 font-medium">
                                            Pembimbing Lapangan: <span class="text-gray-700">{{ $app->pembimbing_lapangan->name ?? 'Belum Ada' }}</span>
                                        </div>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <div class="flex flex-col items-end gap-2">
                                        
                                        @if($app->status == 'pending')
                                            <div class="flex items-center gap-2">
                                                @if($app->position->kuota > 0)
                                                    <form action="{{ route('dinas.pelamar.terima', $app->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menerima peserta ini?')">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-teal-600 text-white text-xs font-bold rounded-lg hover:bg-teal-700 transition shadow-sm" title="Terima Peserta">
                                                            <i class="fas fa-check mr-1.5"></i> Terima
                                                        </button>
                                                    </form>
                                                @else
                                                    <button disabled class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-400 text-xs font-bold rounded-lg cursor-not-allowed border border-gray-200" title="Kuota Penuh">
                                                        <i class="fas fa-ban mr-1.5"></i> Penuh
                                                    </button>
                                                @endif

                                                <form action="{{ route('dinas.pelamar.tolak', $app->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menolak peserta ini?')">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-white text-red-600 border border-red-200 text-xs font-bold rounded-lg hover:bg-red-50 transition shadow-sm" title="Tolak Peserta">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif

                                        @if($app->surat_pengantar_path)
                                            <a href="{{ route('dinas.pelamar.download_surat', $app->id) }}" target="_blank" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 hover:underline flex items-center mt-1">
                                                <i class="fas fa-file-pdf mr-1"></i> Surat Pengantar
                                            </a>
                                        @else
                                            <span class="text-[10px] text-gray-400 italic mt-1">Tidak ada surat</span>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                            <i class="fas fa-inbox text-3xl text-gray-300"></i>
                                        </div>
                                        <p class="font-bold text-gray-600">Belum ada pelamar baru</p>
                                        <p class="text-xs mt-1">Pelamar yang mendaftar akan muncul di sini.</p>
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