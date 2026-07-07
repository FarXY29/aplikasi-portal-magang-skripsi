<x-app-layout>
    @push('head')
        <meta name="turbo-cache-control" content="no-cache">
    @endpush
    @push('styles')
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @endpush

    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-tachometer-alt text-teal-600"></i>
                {{ __('Super Admin Overview') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <x-security-alert />

            <!-- ROW 1: Banner Sambutan & Cek Sertifikat -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Banner Sambutan -->
                <div class="xl:col-span-2 relative overflow-hidden rounded-3xl bg-gradient-to-r from-teal-600 to-teal-800 text-white shadow-xl shadow-teal-100 flex items-center">
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-48 h-48 bg-teal-300 opacity-20 rounded-full blur-2xl"></div>
                    
                    <div class="relative z-10 p-6 md:p-8 flex items-center justify-between gap-6 w-full">
                        <div>
                            <span class="inline-block px-3 py-1 bg-white/20 rounded-full text-xs font-bold mb-3 border border-white/30 backdrop-blur-sm shadow-sm"><i class="fas fa-clock mr-1"></i> {{ now()->translatedFormat('l, d F Y') }}</span>
                            <h1 class="text-2xl md:text-3xl font-extrabold mb-1">Selamat Datang, Super Admin!</h1>
                            <p class="text-teal-50 text-sm font-medium">
                                Pantau performa dan statistik dari <span class="font-black text-white underline decoration-wavy decoration-teal-300">{{ $totalInstansi }}</span> instansi di lingkungan Pemerintah Kota Banjarmasin.
                            </p>
                        </div>
                        <div class="hidden md:block shrink-0">
                             <div class="w-20 h-20 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl flex items-center justify-center text-4xl shadow-lg transform rotate-3">
                                👑
                             </div>
                        </div>
                    </div>
                </div>

                <!-- Cek Sertifikat (Dipindah ke atas) -->
                <div class="xl:col-span-1 bg-gradient-to-br from-blue-600 to-blue-800 rounded-3xl shadow-lg p-6 text-white relative overflow-hidden border border-blue-500 flex flex-col justify-center">
                    <div class="relative z-10">
                        <h3 class="text-lg font-extrabold mb-1 flex items-center gap-2">
                            <i class="fas fa-certificate text-blue-300"></i> Verifikasi Sertifikat
                        </h3>
                        <p class="text-blue-100 text-xs mb-4 font-medium">Cek validitas sertifikat magang instan.</p>
                        
                        <form action="{{ route('certificate.search') }}" method="POST" class="space-y-3">
                            @csrf
                            <div class="relative text-gray-800">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <i class="fas fa-search text-xs"></i>
                                </span>
                                <input type="text" name="nomor_sertifikat" 
                                    placeholder="No. Seri (ex: MG-202X-...)" 
                                    class="w-full pl-9 py-2 rounded-xl border-0 focus:ring-2 focus:ring-blue-300 text-sm font-bold shadow-inner placeholder-gray-400 bg-white/95"
                                    required>
                            </div>
                            <button type="submit" class="w-full py-2 bg-blue-500 hover:bg-blue-400 text-white font-bold rounded-xl transition shadow-md text-sm flex items-center justify-center gap-2 hover:-translate-y-0.5">
                                Periksa Validitas &rarr;
                            </button>
                        </form>
                    </div>
                    <i class="fas fa-award absolute -bottom-6 -right-4 text-8xl text-white opacity-10 transform -rotate-12"></i>
                </div>
            </div>

            <!-- ROW 2: Aksi Cepat (Shortcut Menu) -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-2 overflow-x-auto custom-scrollbar">
                <div class="flex items-center min-w-max gap-2 p-2">
                    <a href="{{ route('admin.instansi.index') }}" class="group flex items-center gap-3 px-5 py-3 rounded-2xl hover:bg-teal-50 transition duration-300">
                        <div class="w-10 h-10 rounded-xl bg-teal-100 text-teal-600 flex items-center justify-center shadow-inner group-hover:scale-110 transition"><i class="fas fa-building"></i></div>
                        <div><span class="block text-xs font-black text-gray-800">Instansi</span><span class="block text-[10px] text-gray-500">Kelola Dinas</span></div>
                    </a>
                    <div class="w-px h-8 bg-gray-100"></div>
                    
                    <a href="{{ route('admin.users.index') }}" class="group flex items-center gap-3 px-5 py-3 rounded-2xl hover:bg-blue-50 transition duration-300">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center shadow-inner group-hover:scale-110 transition"><i class="fas fa-users-cog"></i></div>
                        <div><span class="block text-xs font-black text-gray-800">Pengguna</span><span class="block text-[10px] text-gray-500">Akses Akun</span></div>
                    </a>
                    <div class="w-px h-8 bg-gray-100"></div>

                    <a href="{{ route('admin.laporan') }}" class="group flex items-center gap-3 px-5 py-3 rounded-2xl hover:bg-orange-50 transition duration-300">
                        <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-500 flex items-center justify-center shadow-inner group-hover:scale-110 transition"><i class="fas fa-chart-pie"></i></div>
                        <div><span class="block text-xs font-black text-gray-800">Laporan</span><span class="block text-[10px] text-gray-500">Unduh PDF/Excel</span></div>
                    </a>
                    <div class="w-px h-8 bg-gray-100"></div>

                    <a href="{{ route('admin.laporan.peserta_global') }}" class="group flex items-center gap-3 px-5 py-3 rounded-2xl hover:bg-pink-50 transition duration-300">
                        <div class="w-10 h-10 rounded-xl bg-pink-100 text-pink-500 flex items-center justify-center shadow-inner group-hover:scale-110 transition"><i class="fas fa-globe-asia"></i></div>
                        <div><span class="block text-xs font-black text-gray-800">Data Global</span><span class="block text-[10px] text-gray-500">Semua Pelamar</span></div>
                    </a>
                    <div class="w-px h-8 bg-gray-100"></div>

                    <a href="{{ route('admin.users.logbooks') }}" class="group flex items-center gap-3 px-5 py-3 rounded-2xl hover:bg-purple-50 transition duration-300">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center shadow-inner group-hover:scale-110 transition"><i class="fas fa-book-open"></i></div>
                        <div><span class="block text-xs font-black text-gray-800">Logbook</span><span class="block text-[10px] text-gray-500">Cek Harian</span></div>
                    </a>
                    <div class="w-px h-8 bg-gray-100"></div>

                    <a href="{{ route('admin.settings.index') }}" class="group flex items-center gap-3 px-5 py-3 rounded-2xl hover:bg-gray-100 transition duration-300">
                        <div class="w-10 h-10 rounded-xl bg-gray-200 text-gray-700 flex items-center justify-center shadow-inner group-hover:scale-110 transition"><i class="fas fa-cogs"></i></div>
                        <div><span class="block text-xs font-black text-gray-800">Sistem</span><span class="block text-[10px] text-gray-500">Konfigurasi Web</span></div>
                    </a>
                </div>
            </div>

            <!-- ROW 3: Grid 6 Metrik Utama -->
            <div class="grid grid-cols-2 lg:grid-cols-6 gap-4">
                <a href="{{ route('admin.instansi.index') }}" class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 relative overflow-hidden group hover:border-teal-300 transition">
                    <div class="absolute -right-2 -top-2 w-16 h-16 bg-teal-50 rounded-full opacity-50 group-hover:scale-150 transition duration-500"></div>
                    <div class="flex justify-between items-start mb-2 relative z-10">
                        <div class="w-8 h-8 rounded-xl bg-teal-100 text-teal-600 flex items-center justify-center shadow-inner"><i class="fas fa-building text-sm"></i></div>
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Instansi</span>
                    </div>
                    <h3 class="text-3xl font-black text-gray-800 relative z-10">{{ $totalInstansi }}</h3>
                </a>

                <a href="{{ route('admin.users.index') }}" class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 relative overflow-hidden group hover:border-blue-300 transition">
                    <div class="absolute -right-2 -top-2 w-16 h-16 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition duration-500"></div>
                    <div class="flex justify-between items-start mb-2 relative z-10">
                        <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center shadow-inner"><i class="fas fa-users text-sm"></i></div>
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Pengguna</span>
                    </div>
                    <h3 class="text-3xl font-black text-gray-800 relative z-10">{{ $totalUser }}</h3>
                </a>

                <a href="{{ route('admin.laporan.peserta_global', ['status' => 'semua']) }}" class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 relative overflow-hidden group hover:border-purple-300 transition">
                    <div class="absolute -right-2 -top-2 w-16 h-16 bg-purple-50 rounded-full opacity-50 group-hover:scale-150 transition duration-500"></div>
                    <div class="flex justify-between items-start mb-2 relative z-10">
                        <div class="w-8 h-8 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center shadow-inner"><i class="fas fa-file-signature text-sm"></i></div>
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Pendaftar</span>
                    </div>
                    <h3 class="text-3xl font-black text-gray-800 relative z-10">{{ $totalApplications }}</h3>
                </a>

                <a href="{{ route('admin.laporan.peserta_global', ['status' => 'diterima']) }}" class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 relative overflow-hidden group hover:border-green-300 transition">
                    <div class="absolute -right-2 -top-2 w-16 h-16 bg-green-50 rounded-full opacity-50 group-hover:scale-150 transition duration-500"></div>
                    <div class="flex justify-between items-start mb-2 relative z-10">
                        <div class="w-8 h-8 rounded-xl bg-green-100 text-green-600 flex items-center justify-center shadow-inner"><i class="fas fa-user-clock text-sm"></i></div>
                        <span class="text-[10px] font-black text-green-600 uppercase tracking-widest bg-green-50 px-2 py-0.5 rounded border border-green-200">Aktif</span>
                    </div>
                    <h3 class="text-3xl font-black text-gray-800 relative z-10">{{ $activeInterns }}</h3>
                </a>

                <a href="{{ route('admin.laporan.peserta_global', ['status' => 'selesai']) }}" class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 relative overflow-hidden group hover:border-indigo-300 transition">
                    <div class="absolute -right-2 -top-2 w-16 h-16 bg-indigo-50 rounded-full opacity-50 group-hover:scale-150 transition duration-500"></div>
                    <div class="flex justify-between items-start mb-2 relative z-10">
                        <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center shadow-inner"><i class="fas fa-graduation-cap text-sm"></i></div>
                        <span class="text-[10px] font-black text-indigo-600 uppercase tracking-widest bg-indigo-50 px-2 py-0.5 rounded border border-indigo-200">Selesai</span>
                    </div>
                    <h3 class="text-3xl font-black text-gray-800 relative z-10">{{ $completedInterns }}</h3>
                </a>

                <a href="{{ route('admin.laporan.peserta_global', ['status' => 'pending']) }}" class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 relative overflow-hidden group hover:border-amber-300 transition">
                    <div class="absolute -right-2 -top-2 w-16 h-16 bg-amber-50 rounded-full opacity-50 group-hover:scale-150 transition duration-500"></div>
                    <div class="flex justify-between items-start mb-2 relative z-10">
                        <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center shadow-inner"><i class="fas fa-hourglass-half text-sm"></i></div>
                        <span class="text-[10px] font-black text-amber-600 uppercase tracking-widest bg-amber-50 px-2 py-0.5 rounded border border-amber-200">Pending</span>
                    </div>
                    <h3 class="text-3xl font-black text-gray-800 relative z-10">{{ $pendingApplications }}</h3>
                </a>
            </div>

            <!-- ROW 4: Main Content (2 Columns) -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Kolom Kiri: Tabel Statistik & Feed Terbaru (Lebih Lebar) -->
                <div class="xl:col-span-2 space-y-6">
                    
                    <!-- Tabel: Distribusi Pelamar per INSTANSI -->
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 flex flex-col overflow-hidden">
                        <div class="p-5 md:p-6 border-b border-gray-50 flex justify-between items-center bg-white sticky top-0 z-20">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2"><i class="fas fa-chart-bar text-teal-500"></i> Statistik Pelamar per Instansi</h3>
                                <p class="text-xs text-gray-500 mt-1">Distribusi peminat magang berdasarkan dinas.</p>
                            </div>
                        </div>
                        <!-- Desktop Table View (md and above) -->
                        <div class="hidden md:block overflow-y-auto custom-scrollbar flex-1 p-0 relative">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-gray-50/80 backdrop-blur-sm sticky top-0 z-10">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-[10px] font-black text-gray-500 uppercase tracking-wider w-12">No</th>
                                        <th class="px-6 py-3 text-left text-[10px] font-black text-gray-500 uppercase tracking-wider">Nama Instansi</th>
                                        <th class="px-6 py-3 text-right text-[10px] font-black text-gray-500 uppercase tracking-wider w-32">Total Pelamar</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-50">
                                    @forelse($instansiStats as $index => $instansi)
                                        @php
                                            $percentage = ($instansi->applications_count / $maxPelamar) * 100;
                                        @endphp
                                        <tr class="hover:bg-gray-50/80 transition duration-150 group">
                                            <td class="px-6 py-3.5 whitespace-nowrap text-xs font-bold text-gray-400 group-hover:text-teal-600">
                                                {{ $instansiStats->firstItem() + $index }}
                                            </td>
                                            <td class="px-6 py-3.5 pr-10">
                                                <p class="text-sm font-extrabold text-gray-800 group-hover:text-teal-700 transition">{{ $instansi->nama_dinas }}</p>
                                                <div class="w-full bg-gray-100 rounded-full h-1.5 mt-2 overflow-hidden flex">
                                                    <div class="bg-teal-500 h-1.5 rounded-full" style="width: {{ $percentage }}%"></div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-3.5 whitespace-nowrap text-right">
                                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-lg text-xs font-black bg-teal-50 text-teal-700 border border-teal-100 group-hover:bg-teal-600 group-hover:text-white transition">
                                                    {{ $instansi->applications_count }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-12 text-center">
                                                <i class="fas fa-building text-3xl text-gray-300 mb-3"></i>
                                                <p class="text-sm font-bold text-gray-500">Belum ada data instansi.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Card View (< md) -->
                        <div class="md:hidden divide-y divide-gray-50 flex-1">
                            @forelse($instansiStats as $index => $instansi)
                                @php
                                    $percentage = ($instansi->applications_count / $maxPelamar) * 100;
                                @endphp
                                <div class="p-4 space-y-2 hover:bg-gray-50/80 transition">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <span class="w-6 h-6 rounded-lg bg-teal-100 text-teal-700 font-black text-xs flex items-center justify-center shrink-0">
                                                {{ $instansiStats->firstItem() + $index }}
                                            </span>
                                            <p class="text-sm font-bold text-gray-800 truncate">{{ $instansi->nama_dinas }}</p>
                                        </div>
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-lg text-xs font-black bg-teal-50 text-teal-700 border border-teal-100 shrink-0">
                                            {{ $instansi->applications_count }} Pelamar
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden flex">
                                        <div class="bg-teal-500 h-1.5 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center text-gray-400">
                                    <i class="fas fa-building text-3xl mb-2"></i>
                                    <p class="text-xs font-bold">Belum ada data instansi.</p>
                                </div>
                            @endforelse
                        </div>
                        @if($instansiStats->hasPages())
                        <div class="p-4 border-t border-gray-50 bg-gray-50/30">
                            {{ $instansiStats->links() }}
                        </div>
                        @endif
                    </div>

                    <!-- Feed Lamaran Terbaru -->
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-5 md:p-6 border-b border-gray-50 flex justify-between items-center bg-white">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2"><i class="fas fa-broadcast-tower text-indigo-500"></i> Aktivitas Pendaftaran</h3>
                                <p class="text-xs text-gray-500 mt-1">Live feed pelamar magang terbaru.</p>
                            </div>
                        </div>
                        
                        <div class="divide-y divide-gray-50">
                            @forelse($recentApplications as $app)
                            <div class="p-5 flex items-center justify-between hover:bg-gray-50/80 transition gap-4">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-indigo-50 to-blue-50 text-indigo-600 flex items-center justify-center font-bold text-sm shrink-0 border border-indigo-100 shadow-inner">
                                        {{ strtoupper(substr($app->user->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <p class="text-sm font-bold text-gray-900 truncate">{{ $app->user->name }}</p>
                                            <span class="text-[10px] text-gray-400 font-medium truncate">({{ $app->user->asal_instansi }})</span>
                                        </div>
                                        <p class="text-xs text-gray-500 truncate mt-0.5">
                                            <i class="fas fa-arrow-right text-[10px] text-gray-300 mr-1"></i> <span class="font-bold text-gray-700">{{ $app->position->instansi->nama_dinas }}</span> &bull; <span class="italic text-gray-600">{{ $app->position->judul_posisi }}</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    @php
                                        $statuses = [
                                            'pending' => ['bg' => 'bg-amber-50 text-amber-700 border-amber-200', 'label' => 'Pending'],
                                            'diterima' => ['bg' => 'bg-green-50 text-green-700 border-green-200', 'label' => 'Aktif'],
                                            'selesai' => ['bg' => 'bg-blue-50 text-blue-700 border-blue-200', 'label' => 'Selesai'],
                                            'ditolak' => ['bg' => 'bg-red-50 text-red-700 border-red-200', 'label' => 'Ditolak'],
                                        ];
                                        $st = $statuses[$app->status] ?? $statuses['pending'];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase border {{ $st['bg'] }}">
                                        {{ $st['label'] }}
                                    </span>
                                    <span class="block text-[10px] text-gray-400 mt-1 font-medium"><i class="far fa-clock"></i> {{ $app->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            @empty
                            <div class="p-8 text-center text-gray-400">
                                <i class="fas fa-inbox text-3xl mb-2"></i>
                                <p class="text-sm">Belum ada aktivitas lamaran</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                </div>

                <!-- Kolom Kanan: Donut Chart, Instansi Terbaru, & Info Server (Lebih Sempit) -->
                <div class="space-y-6">
                    
                    <!-- Donut Chart: Status Lamaran -->
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-center mb-6">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2"><i class="fas fa-chart-pie text-orange-500"></i> Status Lamaran</h3>
                                </div>
                            </div>
                            <div class="relative flex items-center justify-center" style="height: 200px;">
                                <canvas id="statusChart"
                                    data-labels="{{ json_encode($statusLabels) }}"
                                    data-values="{{ json_encode($statusData) }}">
                                </canvas>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3 mt-6 pt-4 border-t border-gray-100">
                            <div class="bg-green-50/50 p-3 rounded-2xl border border-green-100 text-center">
                                <span class="block text-[10px] text-gray-400 font-bold uppercase">Lolos</span>
                                <span class="text-lg font-black text-green-600">
                                    @if($totalApplications > 0)
                                        {{ round((($activeInterns + $completedInterns) / $totalApplications) * 100, 1) }}%
                                    @else
                                        0%
                                    @endif
                                </span>
                            </div>
                            <div class="bg-red-50/50 p-3 rounded-2xl border border-red-100 text-center">
                                <span class="block text-[10px] text-gray-400 font-bold uppercase">Tolak</span>
                                <span class="text-lg font-black text-red-500">
                                    @if($totalApplications > 0)
                                        {{ round((($totalApplications - $activeInterns - $completedInterns - $pendingApplications) / $totalApplications) * 100, 1) }}%
                                    @else
                                        0%
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- INSTANSI Terbaru Bergabung -->
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-5 border-b border-gray-50 flex justify-between items-center bg-white">
                            <div>
                                <h3 class="text-md font-bold text-gray-800">Instansi Terbaru</h3>
                            </div>
                            <a href="{{ route('admin.instansi.index') }}" class="text-xs text-teal-600 bg-teal-50 px-2 py-1 rounded hover:bg-teal-100 font-bold transition">Semua</a>
                        </div>
                        <div class="divide-y divide-gray-50">
                            @foreach($recentInstansis as $dinas)
                            <div class="p-4 flex items-center gap-3 hover:bg-gray-50 transition">
                                <div class="w-8 h-8 rounded-lg bg-teal-50 border border-teal-100 text-teal-600 flex items-center justify-center font-bold text-xs shrink-0">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-gray-800 truncate">{{ $dinas->nama_dinas }}</p>
                                    <p class="text-[10px] text-gray-400 truncate mt-0.5">{{ $dinas->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Info Status Server & Laravel -->
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fas fa-server text-gray-400"></i> Informasi Sistem</h3>
                        <div class="space-y-3.5">
                            <div class="flex items-center justify-between text-xs font-bold border-b border-gray-50 pb-2">
                                <span class="text-gray-500">Framework</span>
                                <span class="text-teal-700 bg-teal-50 px-2.5 py-1 rounded-md border border-teal-100">v{{ app()->version() }}</span>
                            </div>
                            <div class="flex items-center justify-between text-xs font-bold border-b border-gray-50 pb-2">
                                <span class="text-gray-500">PHP</span>
                                <span class="text-blue-700 bg-blue-50 px-2.5 py-1 rounded-md border border-blue-100">{{ PHP_VERSION }}</span>
                            </div>
                            <div class="flex items-center justify-between text-xs font-bold border-b border-gray-50 pb-2">
                                <span class="text-gray-500">Lingkungan</span>
                                <span class="text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-md border border-indigo-100 uppercase">{{ app()->environment() }}</span>
                            </div>
                            <div class="flex items-center justify-between text-xs font-bold">
                                <span class="text-gray-500">Scheduler</span>
                                <span class="text-green-700 bg-green-50 px-2.5 py-1 rounded-md border border-green-100 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-ping"></span> Aktif
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (function() {
            function initCharts() {
                if (typeof Chart === 'undefined') {
                    setTimeout(initCharts, 50);
                    return;
                }
                
                const canvasStatus = document.getElementById('statusChart');
                
                if (window.adminStatusChart) {
                    try { window.adminStatusChart.destroy(); } catch(e) {}
                    window.adminStatusChart = null;
                }
                
                if (canvasStatus) {
                    const labels = JSON.parse(canvasStatus.dataset.labels);
                    const dataValues = JSON.parse(canvasStatus.dataset.values);

                    window.adminStatusChart = new Chart(canvasStatus.getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: dataValues,
                                backgroundColor: ['#F59E0B', '#10B981', '#3B82F6', '#EF4444'],
                                borderWidth: 0,
                                hoverOffset: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        usePointStyle: true,
                                        pointStyle: 'circle',
                                        boxWidth: 8,
                                        font: { family: 'Inter', size: 11, weight: '600' },
                                        padding: 20
                                    }
                                },
                                tooltip: {
                                    backgroundColor: '#111827',
                                    titleFont: { family: 'Inter', size: 13, weight: 'bold' },
                                    bodyFont: { family: 'Inter', size: 12 },
                                    padding: 12,
                                    cornerRadius: 12,
                                    callbacks: {
                                        label: (context) => ` ${context.label}: ${context.parsed} Orang`
                                    }
                                }
                            },
                            cutout: '70%'
                        }
                    });
                }
            }

            document.addEventListener('DOMContentLoaded', initCharts);
            document.addEventListener('turbo:load', initCharts);

            document.addEventListener('turbo:before-cache', function() {
                if (window.adminStatusChart) {
                    try { window.adminStatusChart.destroy(); } catch(e) {}
                    window.adminStatusChart = null;
                }
            });
        })();
    </script>
    
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</x-app-layout>