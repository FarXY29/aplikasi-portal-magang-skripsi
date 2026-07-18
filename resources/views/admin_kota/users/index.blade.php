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
                <div class="w-9 h-9 rounded-xl bg-teal-600 text-white flex items-center justify-center shadow-sm" style="background-color: #0d9488;">
                    <i class="fas fa-users text-sm"></i>
                </div>
                <div>
                    <h2 class="font-black text-xl text-gray-900 leading-tight">Manajemen Pengguna</h2>
                    <p class="text-xs text-gray-500 font-medium hidden sm:block">Kelola hak akses dan peran pengguna portal magang</p>
                </div>
            </div>
            <div class="text-xs font-bold text-gray-600 bg-white px-3 py-1.5 rounded-xl shadow-xs border border-gray-200">
                Total User: <span class="font-black text-teal-600">{{ $users->total() }}</span>
            </div>
        </div>
    </x-slot>

    <div class="space-y-5 font-[Inter]">
        
        {{-- Navigation & Alert Bar --}}
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <a href="{{ route('admin.dashboard') }}" class="group inline-flex items-center text-xs font-bold text-gray-500 hover:text-teal-600 transition-colors">
                <div class="w-7 h-7 rounded-full bg-white border border-gray-200 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-xs transition-colors">
                    <i class="fas fa-arrow-left text-[10px]"></i>
                </div>
                Kembali ke Dashboard
            </a>

            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" class="flex items-center p-3 text-green-800 rounded-xl bg-green-50 border border-green-200 shadow-xs text-xs font-bold w-full lg:w-auto" role="alert">
                    <i class="fas fa-check-circle flex-shrink-0 w-4 h-4 mr-2 text-green-600"></i>
                    <div class="flex-1">{{ session('success') }}</div>
                    <button type="button" @click="show = false" class="ml-3 text-green-500 hover:text-green-800">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
            @endif
        </div>

        {{-- Filter & Actions Bar --}}
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
            <form method="GET" action="{{ route('admin.users.index') }}" id="searchForm" class="flex flex-col sm:flex-row w-full md:w-auto flex-1 max-w-3xl gap-2.5">
                
                {{-- Role Filter --}}
                <div class="relative w-full sm:w-48 shrink-0">
                    <select name="role" onchange="document.getElementById('searchForm').submit()"
                        class="w-full pl-9 pr-8 py-2.5 bg-gray-50 border border-gray-200 text-gray-700 text-xs rounded-xl focus:ring-teal-500 focus:border-teal-500 cursor-pointer hover:bg-gray-100 transition font-bold appearance-none">
                        <option value="">Semua Role</option>
                        <option value="admin_kota" {{ request('role') == 'admin_kota' ? 'selected' : '' }}>Super Admin</option>
                        <option value="admin_instansi" {{ request('role') == 'admin_instansi' ? 'selected' : '' }}>Admin Instansi</option>
                        <option value="pembimbing_lapangan" {{ request('role') == 'pembimbing_lapangan' ? 'selected' : '' }}>Pembimbing Lapangan</option>
                        <option value="pembimbing" {{ request('role') == 'pembimbing' ? 'selected' : '' }}>Pembimbing Sekolah</option>
                        <option value="peserta" {{ request('role') == 'peserta' ? 'selected' : '' }}>Peserta Magang</option>
                    </select>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-filter text-gray-400 text-[10px]"></i>
                    </div>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400 text-[10px]"></i>
                    </div>
                </div>

                {{-- Search Input --}}
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400 text-xs"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Cari nama atau email..." 
                        class="w-full pl-10 pr-10 py-2.5 bg-white border border-gray-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition shadow-2xs"
                        oninput="autoSubmitSearch()">
                    
                    <div id="loadingIcon" class="absolute inset-y-0 right-0 pr-3 flex items-center hidden">
                        <i class="fas fa-circle-notch fa-spin text-teal-500 text-xs"></i>
                    </div>
                    
                    @if(request('search'))
                        <a href="{{ route('admin.users.index') }}" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-red-500 transition" title="Hapus Pencarian">
                            <i class="fas fa-times-circle text-xs"></i>
                        </a>
                    @endif
                </div>
            </form>

            <a href="{{ route('admin.users.create') }}" class="action-btn inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 text-white rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-blue-700 active:scale-95 transition shadow-sm w-full md:w-auto" style="background-color: #2563eb;">
                <i class="fas fa-user-plus mr-2 text-[10px]"></i> Tambah Pengguna
            </a>
        </div>

        {{-- Table Container --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            
            {{-- Desktop Table View --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-5 py-3.5 text-left text-[10px] font-black text-gray-500 uppercase tracking-wider">Nama & Email</th>
                            <th scope="col" class="px-5 py-3.5 text-left text-[10px] font-black text-gray-500 uppercase tracking-wider w-48">Peran / Status</th>
                            <th scope="col" class="px-5 py-3.5 text-left text-[10px] font-black text-gray-500 uppercase tracking-wider">Afiliasi / Kontak</th>
                            <th scope="col" class="px-5 py-3.5 text-right text-[10px] font-black text-gray-500 uppercase tracking-wider w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($users as $user)
                        <tr class="table-row hover:bg-gray-50/60 group">
                            {{-- Profile --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-teal-50 rounded-xl flex items-center justify-center text-teal-600 font-black border border-teal-100 shadow-2xs">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="ml-3.5 max-w-xs">
                                        <div class="text-sm font-bold text-gray-900 truncate leading-tight group-hover:text-teal-700 transition-colors">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500 mt-1 font-medium truncate break-all">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Role --}}
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                @include('admin_kota.users.partials.role-badge', ['role' => $user->role])
                            </td>

                            {{-- Detail / Contact --}}
                            <td class="px-5 py-3.5 text-xs text-gray-500">
                                @include('admin_kota.users.partials.user-detail', ['user' => $user])
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-3.5 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="p-2 bg-white border border-gray-200 rounded-lg text-teal-600 hover:bg-teal-50 hover:border-teal-200 transition shadow-2xs" title="Edit Pengguna">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                    
                                    @if(auth()->id() != $user->id)
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 bg-white border border-gray-200 rounded-lg text-red-500 hover:bg-red-50 hover:border-red-200 transition shadow-2xs" title="Hapus Pengguna">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center">
                                        <i class="fas fa-user-slash text-xl text-gray-300"></i>
                                    </div>
                                    <p class="text-sm font-bold text-gray-500">Tidak ada data pengguna.</p>
                                    <p class="text-xs text-gray-400">Silakan tambahkan data atau sesuaikan filter Anda.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile Card View (<md) --}}
            <div class="md:hidden divide-y divide-gray-100">
                @forelse($users as $user)
                <div class="p-4 space-y-3.5 hover:bg-gray-50/50 transition">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="h-10 w-10 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600 font-bold text-base border border-teal-100 shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-sm font-bold text-gray-900 truncate">{{ $user->name }}</h4>
                                <p class="text-xs text-gray-500 truncate mt-0.5">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="shrink-0">
                            @include('admin_kota.users.partials.role-badge', ['role' => $user->role])
                        </div>
                    </div>

                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 text-xs text-gray-600 space-y-2">
                        @include('admin_kota.users.partials.user-detail', ['user' => $user])
                    </div>

                    <div class="flex gap-2 pt-1 justify-end">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="flex-1 py-2 px-3 bg-white border border-gray-300 rounded-xl text-teal-700 font-bold text-xs flex items-center justify-center gap-1.5 hover:bg-teal-50 hover:border-teal-200 transition shadow-2xs">
                            <i class="fas fa-edit text-teal-600"></i> Edit Pengguna
                        </a>
                        
                        @if(auth()->id() != $user->id)
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full py-2 px-3 bg-red-50 border border-red-200 rounded-xl text-red-600 font-bold text-xs flex items-center justify-center gap-1.5 hover:bg-red-600 hover:text-white transition shadow-2xs">
                                    <i class="fas fa-trash-alt text-red-500"></i> Hapus
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-10 text-center text-gray-400">
                    <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-user-slash text-xl text-gray-300"></i>
                    </div>
                    <p class="text-xs font-bold">Tidak ada data pengguna ditemukan.</p>
                </div>
                @endforelse
            </div>

            @if($users->hasPages())
            <div class="bg-gray-50 px-5 py-3.5 border-t border-gray-100">
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
