<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-950/60 flex items-center justify-center border border-teal-200 dark:border-teal-800/60">
                    <i class="fas fa-chalkboard-teacher text-teal-600 dark:text-teal-400 text-lg"></i>
                </div>
                {{ __('Dashboard Pembimbing Akademik') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Welcome Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 sm:p-8">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-gray-100 mb-2">Selamat datang, {{ Auth::user()->name }}!</h3>
                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-6 font-medium leading-relaxed">
                        Anda login sebagai <strong class="text-gray-800 dark:text-gray-200">Pembimbing Sekolah / Akademik</strong>.
                        @if(Auth::user()->asal_instansi)
                            Mahasiswa di bawah ini adalah mereka yang secara spesifik telah memilih Anda sebagai dosen pembimbing mereka pada portal magang ini.
                        @else
                            <span class="text-rose-600 dark:text-rose-400 block mt-2 font-bold">
                                <i class="fas fa-exclamation-triangle mr-1"></i> Anda belum mengisi Asal Sekolah / Kampus di profil Anda. Silakan perbarui profil Anda.
                            </span>
                        @endif
                    </p>

                    <div class="bg-teal-50/60 dark:bg-teal-950/40 border-l-4 border-teal-500 dark:border-teal-400 p-4 rounded-r-2xl border-t border-b border-r border-teal-200 dark:border-teal-900/40">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-info-circle text-teal-600 dark:text-teal-400 text-base mt-0.5 flex-shrink-0"></i>
                            <p class="text-xs sm:text-sm text-teal-900 dark:text-teal-200 font-medium leading-relaxed">
                                Di halaman ini Anda dapat melihat daftar mahasiswa yang sedang magang dan memantau kegiatan harian (Logbook) serta kehadiran (Absensi) mereka secara langsung.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main List Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <h3 class="text-base font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <i class="fas fa-users text-teal-600 dark:text-teal-400"></i> Daftar Mahasiswa Bimbingan
                    </h3>
                    
                    {{-- Filter Status Segmented Control --}}
                    <form action="{{ route('pembimbing.dashboard') }}" method="GET" class="w-full md:w-auto flex items-center gap-2">
                        <div class="bg-gray-100 dark:bg-gray-900 p-1 rounded-xl flex items-center w-full justify-between md:w-auto border border-gray-200 dark:border-gray-700">
                            <label class="cursor-pointer flex-1 md:flex-initial text-center">
                                <input type="radio" name="status" value="aktif" {{ $statusFilter === 'aktif' ? 'checked' : '' }} class="sr-only peer" onchange="this.form.submit()">
                                <span class="px-3.5 py-1.5 text-xs font-bold rounded-lg text-gray-500 dark:text-gray-400 peer-checked:bg-white dark:peer-checked:bg-gray-800 peer-checked:text-teal-600 dark:peer-checked:text-teal-400 peer-checked:shadow-xs transition block">
                                    Sedang Aktif
                                </span>
                            </label>
                            <label class="cursor-pointer flex-1 md:flex-initial text-center">
                                <input type="radio" name="status" value="selesai" {{ $statusFilter === 'selesai' ? 'checked' : '' }} class="sr-only peer" onchange="this.form.submit()">
                                <span class="px-3.5 py-1.5 text-xs font-bold rounded-lg text-gray-500 dark:text-gray-400 peer-checked:bg-white dark:peer-checked:bg-gray-800 peer-checked:text-teal-600 dark:peer-checked:text-teal-400 peer-checked:shadow-xs transition block">
                                    Selesai
                                </span>
                            </label>
                            <label class="cursor-pointer flex-1 md:flex-initial text-center">
                                <input type="radio" name="status" value="semua" {{ $statusFilter === 'semua' ? 'checked' : '' }} class="sr-only peer" onchange="this.form.submit()">
                                <span class="px-3.5 py-1.5 text-xs font-bold rounded-lg text-gray-500 dark:text-gray-400 peer-checked:bg-white dark:peer-checked:bg-gray-800 peer-checked:text-teal-600 dark:peer-checked:text-teal-400 peer-checked:shadow-xs transition block">
                                    Semua
                                </span>
                            </label>
                        </div>
                    </form>
                </div>
                
                <div>
                    @if($applications->isEmpty())
                        <div class="p-12 text-center text-gray-400 dark:text-gray-500">
                            <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mx-auto mb-3 border border-gray-200 dark:border-gray-700">
                                <i class="fas fa-user-graduate text-3xl text-gray-400 dark:text-gray-500"></i>
                            </div>
                            <p class="font-bold text-gray-700 dark:text-gray-300 text-sm">Tidak Ada Mahasiswa</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Tidak ada mahasiswa magang dengan status "{{ ucfirst($statusFilter) }}".</p>
                        </div>
                    @else
                        {{-- Desktop Table View --}}
                        <div class="hidden md:block overflow-x-auto">
                            <table class="w-full divide-y divide-gray-100 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Mahasiswa</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Instansi Penempatan</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Periode Magang</th>
                                        <th scope="col" class="px-6 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi Pemantauan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700/60 text-sm">
                                    @foreach($applications as $app)
                                        <tr class="hover:bg-teal-50/15 dark:hover:bg-teal-950/20 transition duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center gap-3">
                                                    <div class="h-10 w-10 rounded-full bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-300 flex items-center justify-center font-black text-sm border border-teal-200 dark:border-teal-800/60 flex-shrink-0 shadow-xs">
                                                        {{ strtoupper(substr($app->user->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $app->user->name }}</div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">{{ $app->user->major ?? '-' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $app->position->instansi->nama_dinas }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">{{ $app->position->posisi }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-xs font-bold text-gray-800 dark:text-gray-200">
                                                    {{ \Carbon\Carbon::parse($app->tanggal_mulai)->format('d M Y') }} — 
                                                    {{ \Carbon\Carbon::parse($app->tanggal_selesai)->format('d M Y') }}
                                                </div>
                                                @php
                                                    $statusLabel = $app->status instanceof \App\Enums\ApplicationStatus ? $app->status->label() : ucfirst($app->status);
                                                    $statusVal = $app->status instanceof \UnitEnum ? $app->status->value : $app->status;
                                                @endphp
                                                <span class="inline-flex mt-1.5 items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $statusVal == 'selesai' ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800/60' : 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800/60' }}">
                                                    {{ $statusLabel }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center space-x-2">
                                                <a href="{{ route('pembimbing.peserta.logbook', $app->id) }}" class="inline-flex items-center px-3.5 py-1.5 bg-white dark:bg-gray-800 border border-teal-300 dark:border-teal-700/80 text-teal-700 dark:text-teal-300 rounded-xl hover:bg-teal-50 dark:hover:bg-teal-950/60 transition text-xs font-bold shadow-xs">
                                                    <i class="fas fa-book-open mr-1.5 text-teal-600 dark:text-teal-400"></i> Logbook
                                                </a>
                                                <a href="{{ route('pembimbing.peserta.absensi', $app->id) }}" class="inline-flex items-center px-3.5 py-1.5 bg-white dark:bg-gray-800 border border-blue-300 dark:border-blue-700/80 text-blue-700 dark:text-blue-300 rounded-xl hover:bg-blue-50 dark:hover:bg-blue-950/60 transition text-xs font-bold shadow-xs">
                                                    <i class="fas fa-clipboard-list mr-1.5 text-blue-600 dark:text-blue-400"></i> Absensi
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Mobile Card View (< md) --}}
                        <div class="md:hidden divide-y divide-gray-100 dark:divide-gray-700/60">
                            @foreach($applications as $app)
                            <div class="p-5 space-y-3 hover:bg-teal-50/10 dark:hover:bg-teal-950/20 transition">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-full bg-teal-50 dark:bg-teal-950/60 flex items-center justify-center text-teal-600 dark:text-teal-300 font-black text-sm shrink-0 border border-teal-200 dark:border-teal-800/60">
                                            {{ strtoupper(substr($app->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $app->user->name }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">{{ $app->user->major ?? '-' }}</div>
                                        </div>
                                    </div>
                                    @php
                                        $statusLabel = $app->status instanceof \App\Enums\ApplicationStatus ? $app->status->label() : ucfirst($app->status);
                                        $statusVal = $app->status instanceof \UnitEnum ? $app->status->value : $app->status;
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $statusVal == 'selesai' ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800/60' : 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800/60' }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>

                                <div class="bg-gray-50 dark:bg-gray-900 p-3.5 rounded-2xl border border-gray-200 dark:border-gray-700 space-y-1.5 text-xs">
                                    <div class="flex items-center gap-2 text-gray-800 dark:text-gray-200 font-bold">
                                        <i class="fas fa-building text-teal-600 dark:text-teal-400 w-4"></i>
                                        <span>{{ $app->position->instansi->nama_dinas }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400 font-medium">
                                        <i class="fas fa-briefcase text-gray-400 dark:text-gray-500 w-4"></i>
                                        <span>{{ $app->position->posisi }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400 pt-1.5 border-t border-gray-200 dark:border-gray-700/60 font-bold">
                                        <i class="far fa-calendar-alt text-gray-400 dark:text-gray-500 w-4"></i>
                                        <span>{{ \Carbon\Carbon::parse($app->tanggal_mulai)->format('d M Y') }} — {{ \Carbon\Carbon::parse($app->tanggal_selesai)->format('d M Y') }}</span>
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-2 pt-1">
                                    <a href="{{ route('pembimbing.peserta.logbook', $app->id) }}" class="w-full sm:flex-1 py-2.5 px-3 bg-white dark:bg-gray-800 border border-teal-300 dark:border-teal-700/80 text-teal-700 dark:text-teal-300 rounded-xl hover:bg-teal-50 dark:hover:bg-teal-950/60 transition text-xs font-bold shadow-xs flex items-center justify-center gap-2">
                                        <i class="fas fa-book-open text-teal-600 dark:text-teal-400"></i> Cek Logbook
                                    </a>
                                    <a href="{{ route('pembimbing.peserta.absensi', $app->id) }}" class="w-full sm:flex-1 py-2.5 px-3 bg-white dark:bg-gray-800 border border-blue-300 dark:border-blue-700/80 text-blue-700 dark:text-blue-300 rounded-xl hover:bg-blue-50 dark:hover:bg-blue-950/60 transition text-xs font-bold shadow-xs flex items-center justify-center gap-2">
                                        <i class="fas fa-clipboard-list text-blue-600 dark:text-blue-400"></i> Cek Absensi
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
