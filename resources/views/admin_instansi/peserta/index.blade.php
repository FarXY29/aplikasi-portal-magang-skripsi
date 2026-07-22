<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <i class="fas fa-users-cog text-teal-600 dark:text-teal-400"></i>
                {{ __('Kelola Peserta Magang') }}
            </h2>
            <div class="text-sm text-gray-500 dark:text-gray-400 font-medium bg-white dark:bg-gray-800 px-4 py-1.5 rounded-full shadow-sm border border-gray-100 dark:border-gray-700">
                Peserta Aktif: <span class="font-bold text-teal-600 dark:text-teal-400">{{ $activeCount }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans" x-data="{
        showAssignModal: false,
        assignActionUrl: '',
        assignApplicantName: '',
        currentPembimbingId: ''
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 print:hidden">
                <a href="{{ route('dinas.dashboard') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-sm">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Dashboard
                </a>
            </div>

            <!-- Filter Status using x-ui.filter-bar -->
            <x-ui.filter-bar :action="route('dinas.peserta.index')" :resetUrl="route('dinas.peserta.index', ['status' => 'semua'])">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-gray-600 dark:text-gray-400">Status Magang:</span>
                    <select name="status" class="text-xs font-bold rounded-xl border border-gray-200 dark:border-gray-700 focus:border-teal-500 focus:ring-teal-500 py-1.5 pl-3 pr-8 shadow-sm bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100">
                        <option value="semua" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua Status</option>
                        <option value="aktif" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" {{ request('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Peserta Aktif Magang</option>
                        <option value="selesai" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai / Alumni</option>
                    </select>
                </div>
            </x-ui.filter-bar>

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
                                <th scope="col" class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Profil Peserta</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-1/3">Assign Pembimbing Lapangan</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Periode Magang</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi & Sertifikat</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700/60 text-sm">
                            @forelse($interns as $intern)
                            @php
                                $statusValue = $intern->status instanceof \App\Enums\ApplicationStatus ? $intern->status->value : $intern->status;
                            @endphp
                            <tr class="hover:bg-teal-50/15 dark:hover:bg-gray-900/60 transition duration-150 {{ $statusValue == 'selesai' ? 'bg-gray-50/50 dark:bg-gray-900/40' : '' }}">
                                
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($statusValue == 'selesai')
                                                <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-950/60 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold border border-blue-200 dark:border-blue-800" title="Alumni">
                                                    <i class="fas fa-graduation-cap"></i>
                                                </div>
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-teal-50 dark:bg-teal-950/60 flex items-center justify-center text-teal-600 dark:text-teal-300 font-bold border border-teal-100 dark:border-teal-900/40">
                                                    {{ strtoupper(substr($intern->user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-bold text-gray-900 dark:text-gray-100 truncate {{ $statusValue == 'selesai' ? 'text-gray-500 dark:text-gray-400 line-through' : '' }}">
                                                {{ $intern->user->name }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $intern->user->email }}</div>
                                            <div class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5" title="Pembimbing Akademik / Sekolah">
                                                <i class="fas fa-chalkboard-teacher mr-1 text-gray-400 dark:text-gray-500"></i> {{ $intern->user->nama_pembimbing_sekolah ?? '-' }}
                                            </div>
                                            
                                            <div class="mt-1.5 flex items-center gap-1.5">
                                                <x-ui.badge :status="$intern->status" />
                                                @if($statusValue == 'selesai' && $intern->nilai_rata_rata)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-yellow-50 dark:bg-yellow-950/60 text-yellow-700 dark:text-yellow-300 border border-yellow-100 dark:border-yellow-900/40">
                                                        <i class="fas fa-star mr-1 text-yellow-500"></i> Nilai: {{ $intern->nilai_rata_rata }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4" x-data="{ editing: false }">
                                    @if($statusValue == 'diterima' || $statusValue == 'selesai')
                                        {{-- Tampilan Normal --}}
                                        <div x-show="!editing" class="flex flex-col gap-1.5">
                                            @if($intern->pembimbing_lapangan_id)
                                                <div class="text-xs font-bold text-gray-800 dark:text-gray-200 flex items-center gap-1.5">
                                                    <i class="fas fa-user-tie text-teal-600 dark:text-teal-400"></i>
                                                    {{ $intern->pembimbing_lapangan->name }}
                                                </div>
                                                <button type="button" @click="editing = true" class="text-[10px] font-bold text-teal-600 dark:text-teal-400 hover:underline flex items-center gap-1 w-fit transition active:scale-95">
                                                    <i class="fas fa-edit text-[9px]"></i> Ubah Pembimbing
                                                </button>
                                            @else
                                                <div class="text-[10px] font-bold text-rose-700 dark:text-rose-300 bg-rose-50 dark:bg-rose-950/60 border border-rose-100 dark:border-rose-900/40 px-2 py-1 rounded-lg w-fit flex items-center gap-1">
                                                    <i class="fas fa-exclamation-circle animate-pulse text-[10px]"></i> Belum Ada Pembimbing
                                                </div>
                                                <button type="button" @click="editing = true" class="mt-1.5 inline-flex items-center gap-1 px-2.5 py-1 bg-teal-600 dark:bg-teal-500 text-white text-[10px] font-bold rounded-lg hover:bg-teal-700 dark:hover:bg-teal-600 active:scale-95 transition shadow-xs w-fit">
                                                    <i class="fas fa-user-plus text-[9px]"></i> Assign Pembimbing
                                                </button>
                                            @endif
                                        </div>

                                        {{-- Form Edit Inline --}}
                                        <div x-show="editing" x-cloak class="flex flex-col gap-2">
                                            <form action="{{ route('dinas.peserta.assign', $intern->id) }}" method="POST" class="flex flex-col gap-2">
                                                @csrf
                                                <div class="flex items-center gap-2">
                                                    <select name="pembimbing_lapangan_id" required class="w-full text-xs rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 cursor-pointer py-1.5 pl-2 pr-8 shadow-xs">
                                                        <option value="" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100">-- Pilih Pembimbing --</option>
                                                        @foreach($pembimbing_lapangan as $pl)
                                                            <option value="{{ $pl->id }}" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" {{ $intern->pembimbing_lapangan_id == $pl->id ? 'selected' : '' }}>
                                                                {{ $pl->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <button type="submit" class="p-1.5 bg-teal-600 dark:bg-teal-500 text-white rounded-lg hover:bg-teal-700 dark:hover:bg-teal-600 transition active:scale-95 shadow-xs" title="Simpan">
                                                        <i class="fas fa-save text-xs"></i>
                                                    </button>
                                                    <button type="button" @click="editing = false" class="p-1.5 bg-gray-100 dark:bg-gray-900 text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-800 transition active:scale-95" title="Batal">
                                                        <i class="fas fa-times text-xs"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    @else
                                        <div class="text-xs text-gray-500 dark:text-gray-400 italic flex items-center gap-1.5">
                                            <i class="fas fa-user-tie text-gray-400 dark:text-gray-500"></i>
                                            {{ $intern->pembimbing_lapangan->name ?? 'Tidak ada pembimbing lapangan' }}
                                        </div>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($intern->tanggal_mulai)
                                        <div class="flex flex-col">
                                            <span class="text-xs font-bold text-gray-800 dark:text-gray-200 flex items-center gap-1.5">
                                                <i class="far fa-calendar-alt text-gray-400 dark:text-gray-500"></i>
                                                {{ \Carbon\Carbon::parse($intern->tanggal_mulai)->format('d M y') }} 
                                                <i class="fas fa-arrow-right text-gray-300 dark:text-gray-600 text-[10px]"></i> 
                                                {{ \Carbon\Carbon::parse($intern->tanggal_selesai)->format('d M y') }}
                                            </span>
                                            
                                            @php
                                                $selesai = \Carbon\Carbon::parse($intern->tanggal_selesai);
                                                $sisa = now()->diffInDays($selesai, false);
                                            @endphp

                                            @if($statusValue != 'selesai')
                                                @if($sisa > 0)
                                                    <span class="text-[10px] text-teal-700 dark:text-teal-300 font-bold mt-1 bg-teal-50 dark:bg-teal-950/60 border border-teal-100 dark:border-teal-900/40 w-fit px-2 py-0.5 rounded-md">
                                                        <i class="fas fa-hourglass-half mr-1"></i> Sisa {{ ceil($sisa) }} hari
                                                    </span>
                                                @else
                                                    <span class="text-[10px] text-red-700 dark:text-red-300 font-bold mt-1 bg-red-50 dark:bg-red-950/60 border border-red-100 dark:border-red-900/40 w-fit px-2 py-0.5 rounded-md">
                                                        <i class="fas fa-flag-checkered mr-1"></i> Waktu Habis
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 dark:text-gray-500 italic">Tanggal belum diatur</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end items-center gap-2">
                                        
                                        <div class="flex bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                                            <a href="{{ route('dinas.peserta.logbook', $intern->id) }}" class="p-2 text-gray-600 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition" title="Logbook">
                                                <i class="fas fa-book-open"></i>
                                            </a>
                                            <div class="w-px bg-gray-200 dark:bg-gray-700 my-1"></div>
                                            <a href="{{ route('dinas.peserta.absensi', $intern->id) }}" class="p-2 text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition" title="Absensi">
                                                <i class="fas fa-calendar-check"></i>
                                            </a>
                                        </div>

                                        @if($intern->nilai_rata_rata)
                                             <a href="{{ route('dinas.sertifikat.create', $intern->id) }}" 
                                                class="group flex items-center gap-2 px-3 py-2 bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-900/50 rounded-xl hover:bg-teal-600 dark:hover:bg-teal-500 hover:text-white dark:hover:text-white transition shadow-xs"
                                                title="Terbitkan Sertifikat Kelulusan">
                                                 <i class="fas fa-certificate text-teal-500 dark:text-teal-400 group-hover:text-white"></i>
                                                 <span class="text-xs font-bold hidden xl:inline">Sertifikat</span>
                                             </a>
                                         @elseif($statusValue == 'diterima')
                                             <form action="{{ route('dinas.peserta.selesai', $intern->id) }}" method="POST" @submit.prevent="$dispatch('open-confirm', { message: 'PERINGATAN!\n\nPeserta ini BELUM DINILAI oleh pembimbing_lapangan.\nJika Anda meluluskan sekarang, peserta TIDAK AKAN MENDAPAT NILAI di sertifikat.\n\nLanjutkan?', onConfirm: () => $el.submit() })">
                                                 @csrf
                                                 <button type="submit" class="p-2 bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 rounded-xl hover:bg-blue-600 hover:text-white transition shadow-xs font-bold border border-blue-100 dark:border-blue-900/50" title="Luluskan Peserta (Tanpa Sertifikat)">
                                                     <i class="fas fa-check-double"></i>
                                                 </button>
                                             </form>
                                             <form action="{{ route('dinas.peserta.keluarkan', $intern->id) }}" method="POST" @submit.prevent="$dispatch('open-confirm', { message: 'PERINGATAN!\n\nApakah Anda yakin ingin mengeluarkan peserta ini dari tempat magang? Tindakan ini tidak dapat dikembalikan.', onConfirm: () => $el.submit() })">
                                                 @csrf
                                                 <button type="submit" class="p-2 bg-red-50 dark:bg-red-950/60 text-red-700 dark:text-red-400 rounded-xl hover:bg-red-600 hover:text-white transition shadow-xs font-bold border border-red-100 dark:border-red-900/50" title="Keluarkan Peserta">
                                                     <i class="fas fa-user-times"></i>
                                                 </button>
                                             </form>
                                         @else
                                             <span class="text-[10px] text-gray-400 dark:text-gray-500 italic px-2">Menunggu Nilai</span>
                                         @endif

                                     </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                        <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-3 border border-gray-200 dark:border-gray-700">
                                            <i class="fas fa-users-slash text-3xl text-gray-400 dark:text-gray-500"></i>
                                        </div>
                                        <p class="font-bold text-gray-700 dark:text-gray-300">Tidak ada peserta ditemukan</p>
                                        <p class="text-xs mt-1 text-gray-500 dark:text-gray-400">Silakan sesuaikan filter atau terima pelamar terlebih dahulu.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    {{ $interns->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
