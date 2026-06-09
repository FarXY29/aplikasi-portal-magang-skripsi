<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fas fa-chalkboard-teacher text-teal-600 mr-2"></i>
            {{ __('Dashboard Pembimbing Sekolah') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-8">
                    <h3 class="text-2xl font-extrabold mb-2">Selamat datang, {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-500 mb-6">
                        Anda login sebagai <strong>Pembimbing Sekolah / Akademik</strong>.
                        @if(Auth::user()->asal_instansi)
                            Mahasiswa di bawah ini adalah mereka yang secara spesifik telah memilih Anda sebagai dosen pembimbing mereka pada portal magang ini.
                        @else
                            <span class="text-red-500 block mt-2">
                                <i class="fas fa-exclamation-triangle mr-1"></i> Anda belum mengisi Asal Sekolah / Kampus di profil Anda. Silakan perbarui profil Anda.
                            </span>
                        @endif
                    </p>

                    <div class="bg-teal-50 border-l-4 border-teal-500 p-4 rounded-r-xl">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-teal-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-teal-800 font-medium">
                                    Di halaman ini Anda dapat melihat daftar mahasiswa yang sedang magang dan memantau kegiatan harian (Logbook) serta kehadiran (Absensi) mereka secara langsung.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-6 border-b border-gray-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-users text-teal-600 mr-2"></i> Daftar Mahasiswa Magang</h3>
                    
                    <!-- Filter Status -->
                    <form action="{{ route('pembimbing.dashboard') }}" method="GET" class="flex items-center gap-2">
                        <div class="bg-gray-100 p-1 rounded-xl flex items-center">
                            <label class="cursor-pointer">
                                <input type="radio" name="status" value="aktif" {{ $statusFilter === 'aktif' ? 'checked' : '' }} class="sr-only peer" onchange="this.form.submit()">
                                <span class="px-3 py-1.5 text-xs font-bold rounded-lg text-gray-500 peer-checked:bg-white peer-checked:text-teal-600 peer-checked:shadow-sm transition block">
                                    Sedang Aktif
                                </span>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="status" value="selesai" {{ $statusFilter === 'selesai' ? 'checked' : '' }} class="sr-only peer" onchange="this.form.submit()">
                                <span class="px-3 py-1.5 text-xs font-bold rounded-lg text-gray-500 peer-checked:bg-white peer-checked:text-teal-600 peer-checked:shadow-sm transition block">
                                    Selesai
                                </span>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="status" value="semua" {{ $statusFilter === 'semua' ? 'checked' : '' }} class="sr-only peer" onchange="this.form.submit()">
                                <span class="px-3 py-1.5 text-xs font-bold rounded-lg text-gray-500 peer-checked:bg-white peer-checked:text-teal-600 peer-checked:shadow-sm transition block">
                                    Semua
                                </span>
                            </label>
                        </div>
                    </form>
                </div>
                
                <div class="p-0">
                    @if($applications->isEmpty())
                        <div class="p-12 text-center text-gray-500">
                            <i class="fas fa-user-graduate text-4xl text-gray-300 mb-4"></i>
                            <p>Tidak ada mahasiswa magang dengan status "{{ ucfirst($statusFilter) }}".</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Mahasiswa</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Instansi Penempatan</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Periode Magang</th>
                                        <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi Pemantauan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($applications as $app)
                                        <tr class="hover:bg-teal-50/30 transition">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="h-10 w-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-600 font-bold">
                                                        {{ substr($app->user->name, 0, 1) }}
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-bold text-gray-900">{{ $app->user->name }}</div>
                                                        <div class="text-xs text-gray-500">{{ $app->user->major ?? '-' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-bold text-gray-900">{{ $app->position->instansi->nama_dinas }}</div>
                                                <div class="text-xs text-gray-500">{{ $app->position->posisi }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ \Carbon\Carbon::parse($app->tanggal_mulai)->format('d M Y') }} - 
                                                    {{ \Carbon\Carbon::parse($app->tanggal_selesai)->format('d M Y') }}
                                                </div>
                                                <span class="inline-flex mt-1 items-center px-2 py-0.5 rounded text-xs font-bold {{ $app->status == 'selesai' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                    {{ ucfirst($app->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center space-x-2">
                                                <a href="{{ route('pembimbing.peserta.logbook', $app->id) }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-teal-200 text-teal-700 rounded-lg hover:bg-teal-50 transition text-xs font-bold shadow-sm">
                                                    <i class="fas fa-book-open mr-1.5"></i> Logbook
                                                </a>
                                                <a href="{{ route('pembimbing.peserta.absensi', $app->id) }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-blue-200 text-blue-700 rounded-lg hover:bg-blue-50 transition text-xs font-bold shadow-sm">
                                                    <i class="fas fa-clipboard-list mr-1.5"></i> Absensi
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
