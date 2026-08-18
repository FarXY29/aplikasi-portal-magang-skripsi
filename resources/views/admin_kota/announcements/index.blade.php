<x-app-layout>
    @push('head')
        <meta name="turbo-cache-control" content="no-cache">
    @endpush
    @push('styles')
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet">
        <style>
            .action-btn { transition: all 0.2s ease; }
            .action-btn:hover { transform: translateY(-1px); }
            .table-row { transition: background-color 0.15s ease; }
        </style>
    @endpush

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 w-full">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-teal-600 dark:bg-teal-500 text-white flex items-center justify-center shadow-sm">
                    <i class="fas fa-bullhorn text-sm"></i>
                </div>
                <div>
                    <h2 class="font-black text-xl text-gray-900 dark:text-gray-100 leading-tight">Pusat Pengumuman & Broadcast Notifikasi</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium hidden sm:block">Kelola surat edaran, pengumuman massal, dan siaran notifikasi email se-Kota Banjarmasin</p>
                </div>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('admin.announcements.create') }}" class="action-btn inline-flex items-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider shadow-xs">
                    <i class="fas fa-plus mr-2 text-[10px]"></i> Buat Pengumuman Baru
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6 font-[Inter]" x-data="{ broadcastModalOpen: false, selectedAnnouncement: null }">
        
        <!-- Navigation Back -->
        <div class="flex justify-between items-center print:hidden">
            <a href="{{ route('admin.dashboard') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                    <i class="fas fa-arrow-left text-xs text-gray-400 group-hover:text-teal-600"></i>
                </div>
                Kembali ke Dashboard
            </a>
        </div>

        @if(session('success'))
            <x-ui.alert type="success" class="mb-2">
                {{ session('success') }}
            </x-ui.alert>
        @endif

        @if(session('error'))
            <x-ui.alert type="error" class="mb-2">
                {{ session('error') }}
            </x-ui.alert>
        @endif

        <!-- Metric KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Pengumuman</span>
                    <div class="text-2xl font-black text-gray-900 dark:text-gray-100 mt-1">{{ number_format($stats['total']) }}</div>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-teal-50 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 flex items-center justify-center">
                    <i class="fas fa-newspaper text-lg"></i>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Aktif Tayang</span>
                    <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1">{{ number_format($stats['active']) }}</div>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <i class="fas fa-check-circle text-lg"></i>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Broadcast Email</span>
                    <div class="text-2xl font-black text-blue-600 dark:text-blue-400 mt-1">{{ number_format($stats['broadcasted']) }}</div>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                    <i class="fas fa-paper-plane text-lg"></i>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Mendesak (Urgent)</span>
                    <div class="text-2xl font-black text-rose-600 dark:text-rose-400 mt-1">{{ number_format($stats['urgent']) }}</div>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="bg-white dark:bg-gray-800 p-4 sm:p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
            <form action="{{ route('admin.announcements.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3.5 items-end">
                <div class="lg:col-span-4">
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">Cari Judul / Konten</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik kata kunci..." class="w-full pl-9 pr-3 py-2 text-xs font-bold rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 focus:bg-white focus:ring-2 focus:ring-teal-500">
                        <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-xs"></i>
                    </div>
                </div>

                <div class="lg:col-span-3">
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">Tipe</label>
                    <select name="type" class="w-full px-3 py-2 text-xs font-bold rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 focus:bg-white focus:ring-2 focus:ring-teal-500">
                        <option value="">Semua Tipe</option>
                        <option value="info" {{ request('type') == 'info' ? 'selected' : '' }}>Info Umum</option>
                        <option value="urgent" {{ request('type') == 'urgent' ? 'selected' : '' }}>Mendesak (Urgent)</option>
                        <option value="warning" {{ request('type') == 'warning' ? 'selected' : '' }}>Peringatan (Warning)</option>
                        <option value="event" {{ request('type') == 'event' ? 'selected' : '' }}>Agenda Kegiatan (Event)</option>
                    </select>
                </div>

                <div class="lg:col-span-3">
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">Target Audiens</label>
                    <select name="target_audience" class="w-full px-3 py-2 text-xs font-bold rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 focus:bg-white focus:ring-2 focus:ring-teal-500">
                        <option value="">Semua Audiens</option>
                        <option value="all" {{ request('target_audience') == 'all' ? 'selected' : '' }}>Semua Pengguna</option>
                        <option value="peserta" {{ request('target_audience') == 'peserta' ? 'selected' : '' }}>Peserta Magang</option>
                        <option value="admin_instansi" {{ request('target_audience') == 'admin_instansi' ? 'selected' : '' }}>Admin Instansi / OPD</option>
                        <option value="pembimbing" {{ request('target_audience') == 'pembimbing' ? 'selected' : '' }}>Pembimbing</option>
                    </select>
                </div>

                <div class="lg:col-span-2 flex gap-2">
                    <button type="submit" class="w-full py-2 px-3 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5">
                        <i class="fas fa-filter text-[10px]"></i> Filter
                    </button>
                    @if(request()->anyFilled(['search', 'type', 'target_audience', 'status']))
                        <a href="{{ route('admin.announcements.index') }}" class="p-2 bg-gray-100 dark:bg-gray-700 text-gray-500 hover:text-rose-600 rounded-xl text-xs transition flex items-center justify-center" title="Reset Filter">
                            <i class="fas fa-undo"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50/75 dark:bg-gray-900/50 text-gray-400 font-extrabold uppercase tracking-wider border-b border-gray-100 dark:border-gray-700">
                        <tr>
                            <th class="py-4 px-5">Pengumuman & Isi</th>
                            <th class="py-4 px-4 text-center">Tipe</th>
                            <th class="py-4 px-4 text-center">Target Audiens</th>
                            <th class="py-4 px-4 text-center">Periode Tayang</th>
                            <th class="py-4 px-4 text-center">Status</th>
                            <th class="py-4 px-4 text-center">Broadcast</th>
                            <th class="py-4 px-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700 font-medium text-gray-700 dark:text-gray-300">
                        @forelse($announcements as $announcement)
                            <tr class="table-row hover:bg-gray-50/50 dark:hover:bg-gray-700/20">
                                <td class="py-4 px-5">
                                    <div class="flex items-start gap-3">
                                        @if($announcement->banner_image)
                                            <img src="{{ asset('storage/' . $announcement->banner_image) }}" alt="Banner" class="w-12 h-12 object-cover rounded-xl border border-gray-200 dark:border-gray-700 shrink-0">
                                        @else
                                            <div class="w-10 h-10 rounded-xl bg-teal-50 dark:bg-teal-900/20 text-teal-600 dark:text-teal-400 flex items-center justify-center shrink-0">
                                                <i class="{{ $announcement->type_icon }} text-base"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-black text-sm text-gray-900 dark:text-gray-100 hover:text-teal-600 transition">
                                                {{ $announcement->title }}
                                            </div>
                                            <div class="text-[11px] text-gray-400 mt-0.5 line-clamp-1">
                                                {{ Str::limit(strip_tags($announcement->content), 80) }}
                                            </div>
                                            <div class="text-[10px] text-gray-400 mt-1 flex items-center gap-2">
                                                <span><i class="far fa-user mr-1"></i> {{ $announcement->creator->name ?? 'Admin Kota' }}</span>
                                                <span>•</span>
                                                <span><i class="far fa-clock mr-1"></i> {{ $announcement->created_at->translatedFormat('d M Y H:i') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-4 px-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider border {{ $announcement->type_badge_class }}">
                                        {{ strtoupper($announcement->type) }}
                                    </span>
                                </td>

                                <td class="py-4 px-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                        <i class="fas fa-users mr-1.5 text-[9px]"></i> {{ $announcement->target_label }}
                                    </span>
                                </td>

                                <td class="py-4 px-4 text-center text-[11px]">
                                    @if($announcement->expires_at)
                                        <span class="{{ $announcement->expires_at->isPast() ? 'text-rose-500 font-bold' : 'text-gray-500' }}">
                                            Sampai {{ $announcement->expires_at->translatedFormat('d M Y') }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 font-medium">Selamanya</span>
                                    @endif
                                </td>

                                <td class="py-4 px-4 text-center">
                                    <form action="{{ route('admin.announcements.toggle_publish', $announcement->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider transition cursor-pointer {{ $announcement->is_published ? 'bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 hover:bg-emerald-200' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 hover:bg-gray-200' }}" title="Klik untuk ubah status tayang">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $announcement->is_published ? 'bg-emerald-500' : 'bg-gray-400' }} mr-1.5"></span>
                                            {{ $announcement->is_published ? 'Tayang' : 'Draft' }}
                                        </button>
                                    </form>
                                </td>

                                <td class="py-4 px-4 text-center">
                                    @if($announcement->send_email_broadcast)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold text-blue-700 bg-blue-50 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800">
                                            <i class="fas fa-check-double mr-1 text-[9px]"></i> Terkirim
                                        </span>
                                    @else
                                        <button @click="selectedAnnouncement = { id: {{ $announcement->id }}, title: '{{ addslashes($announcement->title) }}', audience: '{{ $announcement->target_label }}' }; broadcastModalOpen = true" class="action-btn px-2.5 py-1 bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-700 rounded-lg text-[10px] font-bold hover:bg-amber-100">
                                            <i class="fas fa-paper-plane mr-1"></i> Broadcast
                                        </button>
                                    @endif
                                </td>

                                <td class="py-4 px-5 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('admin.announcements.edit', $announcement->id) }}" class="action-btn p-2 text-gray-500 hover:text-teal-600 bg-gray-50 dark:bg-gray-700 rounded-xl" title="Edit Pengumuman">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>

                                        <form action="{{ route('admin.announcements.destroy', $announcement->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn p-2 text-gray-500 hover:text-rose-600 bg-gray-50 dark:bg-gray-700 rounded-xl" title="Hapus Pengumuman">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-gray-400">
                                    <i class="fas fa-bullhorn text-4xl mb-3 text-gray-200 dark:text-gray-700 block"></i>
                                    Belum ada data pengumuman yang dibuat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($announcements->hasPages())
                <div class="p-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30">
                    {{ $announcements->links() }}
                </div>
            @endif
        </div>

        <!-- Broadcast Confirmation Modal -->
        <div x-show="broadcastModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="broadcastModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs transition-opacity" @click="broadcastModalOpen = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="broadcastModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100 dark:border-gray-700">
                    <form :action="'{{ url('admin/announcements') }}/' + (selectedAnnouncement ? selectedAnnouncement.id : '') + '/broadcast'" method="POST">
                        @csrf
                        <div class="p-6 sm:p-8">
                            <div class="w-14 h-14 rounded-3xl bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 flex items-center justify-center mb-5 mx-auto">
                                <i class="fas fa-paper-plane text-2xl"></i>
                            </div>

                            <h3 class="text-lg font-black text-gray-900 dark:text-gray-100 text-center mb-2">Kirim Broadcast Email Massal?</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 text-center mb-5 leading-relaxed">
                                Notifikasi pengumuman <strong class="text-gray-800 dark:text-gray-200" x-text="selectedAnnouncement ? selectedAnnouncement.title : ''"></strong> akan dikirimkan serentak via email ke seluruh <strong class="text-teal-600" x-text="selectedAnnouncement ? selectedAnnouncement.audience : ''"></strong>.
                            </p>

                            <div class="bg-amber-50 dark:bg-amber-950/40 p-4 rounded-2xl border border-amber-200/60 dark:border-amber-800/40 text-xs text-amber-800 dark:text-amber-300 mb-6">
                                <i class="fas fa-info-circle mr-1.5"></i> Proses pengiriman akan dieksekusi secara asinkron di latar belakang (*queue worker*) tanpa memperlambat performa sistem.
                            </div>

                            <div class="flex gap-3">
                                <button type="button" @click="broadcastModalOpen = false" class="w-1/2 py-3 px-4 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl text-xs font-bold hover:bg-gray-200 transition">
                                    Batal
                                </button>
                                <button type="submit" class="w-1/2 py-3 px-4 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-xs font-bold transition shadow-md">
                                    <i class="fas fa-paper-plane mr-1"></i> Ya, Kirimkan Sekarang
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
