<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-users-cog text-teal-600"></i>
                {{ __('Kelola Peserta') }}
            </h2>
            <div class="text-sm text-gray-500 font-medium bg-white px-4 py-1.5 rounded-full shadow-sm border border-gray-100">
                Peserta Aktif: <span class="font-bold text-teal-600">{{ $activeCount }}</span>
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
                    <div class="flex rounded-lg overflow-hidden border border-gray-200">
                        <a href="{{ route('dinas.peserta.index', ['status' => 'semua']) }}" 
                           class="px-3 py-1 text-xs font-bold transition {{ request('status') == 'semua' ? 'bg-teal-600 text-white' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                            Semua
                        </a>
                        <a href="{{ route('dinas.peserta.index', ['status' => 'aktif']) }}" 
                           class="px-3 py-1 text-xs font-bold transition border-l border-r border-gray-200 {{ request('status', 'aktif') == 'aktif' ? 'bg-teal-600 text-white' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                            Aktif
                        </a>
                        <a href="{{ route('dinas.peserta.index', ['status' => 'selesai']) }}" 
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

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Profil Peserta</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-1/3">Assign Pembimbing Lapangan</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Periode Magang</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi & Sertifikat</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-5">
                            @forelse($interns as $intern)
                            <tr class="hover:bg-gray-50 transition duration-150 {{ $intern->status == 'selesai' ? 'bg-gray-50/50' : '' }}">
                                
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($intern->status == 'selesai')
                                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold border border-blue-200" title="Alumni">
                                                    <i class="fas fa-graduation-cap"></i>
                                                </div>
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-teal-50 flex items-center justify-center text-teal-600 font-bold border border-teal-100">
                                                    {{ strtoupper(substr($intern->user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-bold text-gray-900 truncate {{ $intern->status == 'selesai' ? 'text-gray-500 line-through' : '' }}">
                                                {{ $intern->user->name }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $intern->user->email }}</div>
                                            <div class="text-[11px] text-gray-500 mt-0.5" title="Pembimbing Akademik / Sekolah">
                                                <i class="fas fa-chalkboard-teacher mr-1 text-gray-400"></i> {{ $intern->user->nama_pembimbing_sekolah ?? '-' }}
                                            </div>
                                            
                                            @if($intern->status == 'selesai')
                                                <div class="mt-1 flex items-center gap-1">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                                        LULUS
                                                    </span>
                                                    @if($intern->nilai_rata_rata)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-yellow-50 text-yellow-700 border border-yellow-100">
                                                            Nilai: {{ $intern->nilai_rata_rata }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    @if($intern->status == 'diterima')
                                        <form action="{{ route('dinas.peserta.assign', $intern->id) }}" method="POST" class="flex flex-col gap-2">
                                            @csrf
                                            <div class="flex items-center gap-2">
                                                <select name="pembimbing_lapangan_id" class="w-full text-xs rounded-lg border-gray-300 focus:border-teal-500 focus:ring-teal-500 cursor-pointer py-1.5 pl-2 pr-8 shadow-sm">
                                                    <option value="">-- Pilih Pembimbing Lapangan --</option>
                                                    @foreach($pembimbing_lapangan as $pl)
                                                        <option value="{{ $pl->id }}" {{ $intern->pembimbing_lapangan_id == $pl->id ? 'selected' : '' }}>
                                                            {{ $pl->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="p-1.5 bg-teal-600 text-white rounded-md hover:bg-teal-700 transition shadow-sm" title="Simpan Pembimbing Lapangan">
                                                    <i class="fas fa-save text-xs"></i>
                                                </button>
                                            </div>
                                            @if($intern->pembimbing_lapangan_id)
                                                <div class="text-[10px] text-green-600 font-medium flex items-center">
                                                    <i class="fas fa-check-circle mr-1"></i> Pembimbing Lapangan: {{ $intern->pembimbing_lapangan->name }}
                                                </div>
                                            @else
                                                <div class="text-[10px] text-red-500 font-medium flex items-center animate-pulse">
                                                    <i class="fas fa-exclamation-circle mr-1"></i> Wajib pilih pembimbing_lapangan!
                                                </div>
                                            @endif
                                        </form>
                                    @else
                                        <div class="text-xs text-gray-500 italic flex items-center">
                                            <i class="fas fa-user-tie mr-1.5 text-gray-400"></i>
                                            {{ $intern->pembimbing_lapangan->name ?? 'Tidak ada pembimbing_lapangan' }}
                                        </div>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($intern->tanggal_mulai)
                                        <div class="flex flex-col">
                                            <span class="text-xs font-bold text-gray-700 flex items-center gap-1.5">
                                                <i class="far fa-calendar-alt text-gray-400"></i>
                                                {{ \Carbon\Carbon::parse($intern->tanggal_mulai)->format('d M y') }} 
                                                <i class="fas fa-arrow-right text-gray-300 text-[10px]"></i> 
                                                {{ \Carbon\Carbon::parse($intern->tanggal_selesai)->format('d M y') }}
                                            </span>
                                            
                                            @php
                                                $selesai = \Carbon\Carbon::parse($intern->tanggal_selesai);
                                                $sisa = now()->diffInDays($selesai, false);
                                            @endphp

                                            @if($intern->status != 'selesai')
                                                @if($sisa > 0)
                                                    <span class="text-[10px] text-teal-600 font-bold mt-1 bg-teal-50 w-fit px-1.5 py-0.5 rounded">
                                                        <i class="fas fa-hourglass-half mr-1"></i> Sisa {{ ceil($sisa) }} hari
                                                    </span>
                                                @else
                                                    <span class="text-[10px] text-red-500 font-bold mt-1 bg-red-50 w-fit px-1.5 py-0.5 rounded">
                                                        <i class="fas fa-flag-checkered mr-1"></i> Waktu Habis
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Tanggal belum diatur</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end items-center gap-2">
                                        
                                        <div class="flex bg-gray-50 rounded-lg border border-gray-200">
                                            <a href="{{ route('dinas.peserta.logbook', $intern->id) }}" class="p-2 text-gray-600 hover:text-teal-600 transition" title="Logbook">
                                                <i class="fas fa-book-open"></i>
                                            </a>
                                            <div class="w-px bg-gray-200 my-1"></div>
                                            <a href="{{ route('dinas.peserta.absensi', $intern->id) }}" class="p-2 text-gray-600 hover:text-purple-600 transition" title="Absensi">
                                                <i class="fas fa-calendar-check"></i>
                                            </a>
                                        </div>

                                        @if($intern->nilai_rata_rata)
                                            <a href="{{ route('dinas.sertifikat.create', $intern->id) }}" 
                                               class="group flex items-center gap-2 px-3 py-2 bg-teal-50 text-teal-700 border border-teal-200 rounded-lg hover:bg-teal-600 hover:text-white hover:border-teal-600 transition shadow-sm"
                                               title="Terbitkan Sertifikat Kelulusan">
                                                <i class="fas fa-certificate text-teal-500 group-hover:text-white"></i>
                                                <span class="text-xs font-bold hidden xl:inline">Sertifikat</span>
                                            </a>
                                        @elseif($intern->status == 'diterima')
                                            <form action="{{ route('dinas.peserta.selesai', $intern->id) }}" method="POST" onsubmit="return confirm('PERINGATAN!\n\nPeserta ini BELUM DINILAI oleh pembimbing_lapangan.\nJika Anda meluluskan sekarang, peserta TIDAK AKAN MENDAPAT NILAI di sertifikat.\n\nLanjutkan?')">
                                                @csrf
                                                <button type="submit" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition shadow-sm font-bold border border-blue-100" title="Luluskan Peserta (Tanpa Sertifikat)">
                                                    <i class="fas fa-check-double"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('dinas.peserta.keluarkan', $intern->id) }}" method="POST" onsubmit="return confirm('PERINGATAN!\n\nApakah Anda yakin ingin mengeluarkan peserta ini dari tempat magang? Tindakan ini tidak dapat dikembalikan.')">
                                                @csrf
                                                <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition shadow-sm font-bold border border-red-100" title="Keluarkan Peserta">
                                                    <i class="fas fa-user-times"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-[10px] text-gray-400 italic px-2">Menunggu Nilai</span>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                            <i class="fas fa-users-slash text-3xl text-gray-300"></i>
                                        </div>
                                        <p class="font-bold text-gray-600">Tidak ada peserta ditemukan</p>
                                        <p class="text-xs mt-1">Silakan sesuaikan filter atau terima pelamar terlebih dahulu.</p>
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