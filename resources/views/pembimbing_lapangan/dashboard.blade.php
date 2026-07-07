<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-chalkboard-teacher text-indigo-600"></i>
                {{ __('Dashboard Pembimbing Lapangan') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @php
                $globalAnnouncement = \App\Models\Setting::where('key', 'announcement')->value('value');
            @endphp
            @if(!empty($globalAnnouncement))
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 border-l-4 border-amber-500 p-6 rounded-r-2xl shadow-sm border border-amber-100 flex gap-4 items-start relative overflow-hidden">
                    <div class="absolute right-0 top-0 translate-x-4 -translate-y-4 opacity-5 text-amber-500 pointer-events-none">
                        <i class="fas fa-bullhorn text-9xl"></i>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0 shadow-inner">
                        <i class="fas fa-bullhorn text-lg"></i>
                    </div>
                    <div class="flex-grow">
                        <h4 class="text-sm font-extrabold text-amber-800 uppercase tracking-wider mb-1">Pengumuman Kota Banjarmasin</h4>
                        <div class="text-sm text-amber-955 font-semibold leading-relaxed prose prose-amber max-w-none">
                            {!! nl2br(e($globalAnnouncement)) !!}
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-indigo-100 relative overflow-hidden group hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-indigo-500 uppercase tracking-widest mb-1">Total Bimbingan</p>
                            <h3 class="text-3xl font-black text-gray-800">{{ $interns->count() }}</h3>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 shadow-sm">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-teal-100 relative overflow-hidden group hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-teal-500 uppercase tracking-widest mb-1">Sedang Magang</p>
                            <h3 class="text-3xl font-black text-gray-800">{{ $interns->where('status', 'diterima')->count() }}</h3>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600 shadow-sm">
                            <i class="fas fa-user-clock"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-blue-100 relative overflow-hidden group hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-blue-500 uppercase tracking-widest mb-1">Selesai / Lulus</p>
                            <h3 class="text-3xl font-black text-gray-800">{{ $interns->where('status', 'selesai')->count() }}</h3>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 shadow-sm">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
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
                <div class="p-6 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-list-ul text-indigo-500"></i> Daftar Mahasiswa Bimbingan
                        </h3>
                        <p class="text-xs text-gray-500 mt-1">Pantau aktivitas, validasi kehadiran, dan berikan penilaian.</p>
                    </div>
                </div>
                
                <!-- Desktop Table View (md and above) -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Profil Peserta</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Periode & Status</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Kehadiran (Valid)</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Nilai Akhir</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @forelse($interns as $mhs)
                            <tr class="hover:bg-gray-50 transition group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-100 to-indigo-200 flex items-center justify-center text-indigo-700 font-bold text-sm border border-indigo-200 shadow-sm">
                                            {{ strtoupper(substr($mhs->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900 group-hover:text-indigo-600 transition">{{ $mhs->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $mhs->user->email }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1">
                                        @if($mhs->tanggal_mulai)
                                            <span class="text-xs font-medium text-gray-600 flex items-center gap-1">
                                                <i class="far fa-calendar-alt"></i>
                                                {{ \Carbon\Carbon::parse($mhs->tanggal_mulai)->format('d M') }} - {{ \Carbon\Carbon::parse($mhs->tanggal_selesai)->format('d M Y') }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Jadwal belum diset</span>
                                        @endif

                                        <div>
                                            @if($mhs->status == 'diterima')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-700 border border-green-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5 animate-pulse"></span> AKTIF
                                                </span>
                                            @elseif($mhs->status == 'selesai')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700 border border-blue-200">
                                                    <i class="fas fa-check-circle mr-1 text-[8px]"></i> SELESAI
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @php
                                        $validLogs = $mhs->logs->where('status_validasi', 'disetujui')->count();
                                    @endphp
                                    <div class="inline-flex flex-col items-center">
                                        <span class="text-lg font-black text-gray-800">{{ $validLogs }}</span>
                                        <span class="text-[10px] text-gray-400 font-bold uppercase">Hari Valid</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if($mhs->nilai_angka)
                                        <div class="inline-flex flex-col items-center bg-indigo-50 px-3 py-1 rounded-lg border border-indigo-100">
                                            <span class="text-lg font-black text-indigo-600">{{ $mhs->nilai_angka }}</span>
                                            <span class="text-[10px] font-bold text-indigo-400 uppercase">{{ $mhs->predikat }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 italic bg-gray-100 px-2 py-1 rounded">Belum dinilai</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2 flex-wrap sm:flex-nowrap">
                                        
                                        <a href="{{ route('pembimbing_lapangan.logbook', $mhs->id) }}" 
                                           class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-gray-600 text-xs font-bold hover:text-indigo-600 hover:border-indigo-300 hover:bg-indigo-50 transition shadow-sm" 
                                           title="Periksa Logbook">
                                            <i class="fas fa-book-open mr-1.5"></i> Logbook
                                        </a>

                                        <a href="{{ route('pembimbing_lapangan.attendance.index', $mhs->id) }}" 
                                           class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-gray-600 text-xs font-bold hover:text-teal-600 hover:border-teal-300 hover:bg-teal-50 transition shadow-sm" 
                                           title="Riwayat Absensi">
                                            <i class="fas fa-clock mr-1.5"></i> Absensi
                                        </a>

                                        @if($mhs->status == 'diterima' || $mhs->status == 'selesai')
                                            <a href="{{ route('pembimbing_lapangan.penilaian', $mhs->id) }}" 
                                               class="inline-flex items-center px-3 py-1.5 border rounded-lg text-xs font-bold transition shadow-sm
                                               {{ $mhs->nilai_angka 
                                                    ? 'bg-yellow-50 text-yellow-700 border-yellow-200 hover:bg-yellow-100' 
                                                    : 'bg-indigo-600 text-white border-transparent hover:bg-indigo-700' 
                                               }}">
                                                <i class="fas fa-star mr-1.5"></i> {{ $mhs->nilai_angka ? 'Edit Nilai' : 'Input Nilai' }}
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                            <i class="fas fa-users-slash text-3xl text-gray-300"></i>
                                        </div>
                                        <p class="font-bold text-gray-600">Belum ada mahasiswa bimbingan</p>
                                        <p class="text-xs mt-1">Anda belum ditugaskan untuk membimbing peserta magang manapun.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View (< md) -->
                <div class="md:hidden divide-y divide-gray-100">
                    @forelse($interns as $mhs)
                    <div class="p-5 space-y-4 hover:bg-gray-50/50 transition">
                        <!-- Header Card: Foto, Nama, Email -->
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-indigo-100 to-indigo-200 flex items-center justify-center text-indigo-700 font-bold text-base border border-indigo-200 shadow-sm shrink-0">
                                    {{ strtoupper(substr($mhs->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-base font-bold text-gray-900">{{ $mhs->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $mhs->user->email }}</div>
                                </div>
                            </div>
                            <div>
                                @if($mhs->status == 'diterima')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700 border border-green-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5 animate-pulse"></span> AKTIF
                                    </span>
                                @elseif($mhs->status == 'selesai')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700 border border-blue-200">
                                        <i class="fas fa-check-circle mr-1 text-[8px]"></i> SELESAI
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Periode & Stats Grid -->
                        <div class="grid grid-cols-2 gap-2 bg-gray-50 p-3 rounded-xl border border-gray-100 text-xs">
                            <div class="col-span-2 flex items-center gap-1.5 text-gray-600 font-medium pb-2 border-b border-gray-200/60">
                                <i class="far fa-calendar-alt text-indigo-500"></i>
                                @if($mhs->tanggal_mulai)
                                    <span>{{ \Carbon\Carbon::parse($mhs->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($mhs->tanggal_selesai)->format('d M Y') }}</span>
                                @else
                                    <span class="text-gray-400 italic">Jadwal belum diset</span>
                                @endif
                            </div>
                            <div class="pt-1 flex flex-col">
                                <span class="text-[10px] text-gray-400 font-bold uppercase">Kehadiran Valid</span>
                                @php
                                    $validLogs = $mhs->logs->where('status_validasi', 'disetujui')->count();
                                @endphp
                                <span class="text-base font-black text-gray-800">{{ $validLogs }} <span class="text-xs font-normal text-gray-500">Hari</span></span>
                            </div>
                            <div class="pt-1 flex flex-col border-l border-gray-200/60 pl-3">
                                <span class="text-[10px] text-gray-400 font-bold uppercase">Nilai Akhir</span>
                                @if($mhs->nilai_angka)
                                    <span class="text-base font-black text-indigo-600">{{ $mhs->nilai_angka }} <span class="text-xs font-bold text-indigo-400">({{ $mhs->predikat }})</span></span>
                                @else
                                    <span class="text-xs text-gray-400 italic pt-0.5">Belum dinilai</span>
                                @endif
                            </div>
                        </div>

                        <!-- Tombol Aksi Full-Width untuk HP -->
                        <div class="flex flex-col sm:flex-row gap-2 pt-1">
                            <a href="{{ route('pembimbing_lapangan.logbook', $mhs->id) }}" 
                               class="w-full sm:flex-1 py-2.5 px-3 bg-white border border-gray-300 rounded-xl text-gray-700 text-xs font-bold hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-300 transition shadow-sm flex items-center justify-center gap-2">
                                <i class="fas fa-book-open text-indigo-500"></i> Cek Logbook
                            </a>

                            <a href="{{ route('pembimbing_lapangan.attendance.index', $mhs->id) }}" 
                               class="w-full sm:flex-1 py-2.5 px-3 bg-white border border-gray-300 rounded-xl text-gray-700 text-xs font-bold hover:bg-teal-50 hover:text-teal-600 hover:border-teal-300 transition shadow-sm flex items-center justify-center gap-2">
                                <i class="fas fa-clock text-teal-500"></i> Cek Absensi
                            </a>

                            @if($mhs->status == 'diterima' || $mhs->status == 'selesai')
                                <a href="{{ route('pembimbing_lapangan.penilaian', $mhs->id) }}" 
                                   class="w-full sm:flex-1 py-2.5 px-3 rounded-xl text-xs font-bold transition shadow-sm flex items-center justify-center gap-2
                                   {{ $mhs->nilai_angka 
                                        ? 'bg-yellow-50 text-yellow-700 border border-yellow-300 hover:bg-yellow-100' 
                                        : 'bg-indigo-600 text-white hover:bg-indigo-700' 
                                   }}">
                                    <i class="fas fa-star text-yellow-400"></i> {{ $mhs->nilai_angka ? 'Edit Nilai' : 'Input Nilai' }}
                                </a>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-gray-400">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-users-slash text-3xl text-gray-300"></i>
                        </div>
                        <p class="font-bold text-gray-600">Belum ada mahasiswa bimbingan</p>
                        <p class="text-xs mt-1">Anda belum ditugaskan untuk membimbing peserta magang manapun.</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>