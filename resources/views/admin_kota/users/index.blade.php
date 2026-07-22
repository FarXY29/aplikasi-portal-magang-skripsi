<x-app-layout>
    @push('head')
        <meta name="turbo-cache-control" content="no-cache">
    @endpush
    @push('styles')
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
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
                    <i class="fas fa-users text-sm"></i>
                </div>
                <div>
                    <h2 class="font-black text-xl text-gray-900 dark:text-gray-100 leading-tight">Manajemen Pengguna</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium hidden sm:block">Kelola hak akses dan peran pengguna portal magang</p>
                </div>
            </div>
            <div class="text-xs font-bold text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-800 px-3 py-1.5 rounded-xl shadow-xs border border-gray-200 dark:border-gray-700">
                Total User: <span class="font-black text-teal-600 dark:text-teal-400">{{ $users->total() }}</span>
            </div>
        </div>
    </x-slot>

    <div class="space-y-5 font-[Inter]">
        
        {{-- Navigation & Alert Bar --}}
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <a href="{{ route('admin.dashboard') }}" class="group inline-flex items-center text-xs font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition-colors">
                <div class="w-7 h-7 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-xs transition-colors">
                    <i class="fas fa-arrow-left text-[10px] text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                </div>
                Kembali ke Dashboard
            </a>

            @if(session('success'))
                <x-ui.alert type="success" class="mb-4">
                    {{ session('success') }}
                </x-ui.alert>
            @endif
        </div>

        <div class="flex flex-col gap-4 mb-6 print:hidden">
            <!-- Form Filter Multi-Kriteria -->
            <x-ui.filter-bar :action="route('admin.users.index')" :resetUrl="request()->hasAny(['search', 'role']) ? route('admin.users.index') : null">
                <div class="flex-grow min-w-[200px]">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:border-teal-500 focus:ring-teal-500 py-2 px-3 shadow-sm font-medium">
                </div>

                <div class="min-w-[150px]">
                    <select name="role" class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 focus:border-teal-500 focus:ring-teal-500 py-2 pl-3 pr-8 cursor-pointer shadow-sm font-bold">
                        <option value="">Semua Role</option>
                        <option value="admin_kota" {{ request('role') == 'admin_kota' ? 'selected' : '' }}>Super Admin</option>
                        <option value="admin_instansi" {{ request('role') == 'admin_instansi' ? 'selected' : '' }}>Admin Instansi</option>
                        <option value="pembimbing_lapangan" {{ request('role') == 'pembimbing_lapangan' ? 'selected' : '' }}>Pembimbing Lapangan</option>
                        <option value="pembimbing" {{ request('role') == 'pembimbing' ? 'selected' : '' }}>Pembimbing Sekolah</option>
                        <option value="peserta" {{ request('role') == 'peserta' ? 'selected' : '' }}>Peserta Magang</option>
                    </select>
                </div>
                
                <div class="flex items-center ml-auto pl-4 border-l border-gray-100 dark:border-gray-700">
                    <a href="{{ route('admin.users.create') }}" class="action-btn inline-flex items-center justify-center px-4 py-2 bg-blue-600 dark:bg-blue-500 text-white rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-blue-700 dark:hover:bg-blue-600 active:scale-95 transition shadow-sm">
                        <i class="fas fa-user-plus mr-2 text-[10px]"></i> Tambah Pengguna
                    </a>
                </div>
            </x-ui.filter-bar>
        </div>

        {{-- Table Container --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            
            {{-- Desktop Table View --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th scope="col" class="px-5 py-3.5 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama & Email</th>
                            <th scope="col" class="px-5 py-3.5 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-48">Peran / Status</th>
                            <th scope="col" class="px-5 py-3.5 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Afiliasi / Kontak</th>
                            <th scope="col" class="px-5 py-3.5 text-right text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700/60">
                        @forelse($users as $user)
                        <tr class="table-row hover:bg-gray-50 dark:hover:bg-gray-900/60 group">
                            {{-- Profile --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-teal-50 dark:bg-teal-950/40 rounded-xl flex items-center justify-center text-teal-600 dark:text-teal-400 font-black border border-teal-100 dark:border-teal-900/50 shadow-2xs">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="ml-3.5 max-w-xs">
                                        <div class="text-sm font-bold text-gray-900 dark:text-gray-100 truncate leading-tight group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-medium truncate break-all">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Role --}}
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                @include('admin_kota.users.partials.role-badge', ['role' => $user->role])
                            </td>

                            {{-- Detail / Contact --}}
                            <td class="px-5 py-3.5 text-xs text-gray-500 dark:text-gray-400">
                                @include('admin_kota.users.partials.user-detail', ['user' => $user])
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-3.5 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="p-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-teal-600 dark:text-teal-400 hover:bg-teal-50 dark:hover:bg-teal-950/40 hover:border-teal-200 dark:hover:border-teal-800 transition shadow-2xs" title="Edit Pengguna">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                    
                                    @if(auth()->id() != $user->id)
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" @submit.prevent="$dispatch('open-confirm', { message: 'Yakin ingin menghapus user ini?', onConfirm: () => $el.submit() })">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-red-500 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/40 hover:border-red-200 dark:hover:border-red-900/50 transition shadow-2xs" title="Hapus Pengguna">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-900 border border-transparent dark:border-gray-700 flex items-center justify-center">
                                        <i class="fas fa-user-slash text-xl text-gray-300 dark:text-gray-600"></i>
                                    </div>
                                    <p class="text-sm font-bold text-gray-500 dark:text-gray-400">Tidak ada data pengguna.</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">Silakan tambahkan data atau sesuaikan filter Anda.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile Card View (<md) --}}
            <div class="md:hidden divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($users as $user)
                <div class="p-4 space-y-3.5 hover:bg-gray-50 dark:hover:bg-gray-900/50 transition">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="h-10 w-10 rounded-xl bg-teal-50 dark:bg-teal-950/40 flex items-center justify-center text-teal-600 dark:text-teal-400 font-bold text-base border border-teal-100 dark:border-teal-900/50 shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100 truncate">{{ $user->name }}</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="shrink-0">
                            @include('admin_kota.users.partials.role-badge', ['role' => $user->role])
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-100 dark:border-gray-700 text-xs text-gray-600 dark:text-gray-400 space-y-2">
                        @include('admin_kota.users.partials.user-detail', ['user' => $user])
                    </div>

                    <div class="flex gap-2 pt-1 justify-end">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="flex-1 py-2 px-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl text-teal-700 dark:text-teal-300 font-bold text-xs flex items-center justify-center gap-1.5 hover:bg-teal-50 dark:hover:bg-teal-950/40 hover:border-teal-200 dark:hover:border-teal-800 transition shadow-2xs">
                            <i class="fas fa-edit text-teal-600 dark:text-teal-400"></i> Edit Pengguna
                        </a>
                        
                        @if(auth()->id() != $user->id)
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="flex-1" @submit.prevent="$dispatch('open-confirm', { message: 'Yakin ingin menghapus user ini?', onConfirm: () => $el.submit() })">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full py-2 px-3 bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900/50 rounded-xl text-red-600 dark:text-red-400 font-bold text-xs flex items-center justify-center gap-1.5 hover:bg-red-600 hover:text-white dark:hover:bg-red-600 dark:hover:text-white transition shadow-2xs">
                                    <i class="fas fa-trash-alt text-red-500 dark:text-red-400"></i> Hapus
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-10 text-center text-gray-400 dark:text-gray-500">
                    <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-900 border border-transparent dark:border-gray-700 flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-user-slash text-xl text-gray-300 dark:text-gray-600"></i>
                    </div>
                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400">Tidak ada data pengguna ditemukan.</p>
                </div>
                @endforelse
            </div>

            @if($users->hasPages())
            <div class="bg-gray-50 dark:bg-gray-900 px-5 py-3.5 border-t border-gray-100 dark:border-gray-700">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>

    <script>
        let timeout = null;
        function autoSubmitSearch() {
            const loading = document.getElementById('loadingIcon');
            if(loading) loading.classList.remove('hidden');
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                document.getElementById('searchForm').submit();
            }, 800);
        }
    </script>
</x-app-layout>
