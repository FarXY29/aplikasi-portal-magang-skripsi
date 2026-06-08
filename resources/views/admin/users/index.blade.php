<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-users-cog text-teal-600"></i>
                {{ __('Manajemen Pengguna') }}
            </h2>
            <div class="text-sm text-gray-500 font-medium bg-white px-4 py-1.5 rounded-full shadow-sm border border-gray-100">
                Total User: <span class="font-bold text-teal-600">{{ $users->total() }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="group flex items-center text-sm font-bold text-gray-500 hover:text-teal-600 transition">
                    <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                    Kembali
                </a>

                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" class="flex items-center p-3 text-green-800 rounded-lg bg-green-50 border border-green-100 shadow-sm text-sm font-medium w-full md:w-auto">
                        <i class="fas fa-check-circle flex-shrink-0 w-4 h-4 mr-2"></i>
                        {{ session('success') }}
                        <button @click="show = false" class="ml-4 text-green-600 hover:text-green-800"><i class="fas fa-times"></i></button>
                    </div>
                @endif
            </div>

            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
                
                <form method="GET" action="{{ route('admin.users.index') }}" id="searchForm" class="flex flex-col md:flex-row w-full md:w-auto flex-1 max-w-3xl gap-2">
                    
                    <div class="relative w-full md:w-[180px]">
                        <select name="role" onchange="document.getElementById('searchForm').submit()"
                            class="w-full pl-9 pr-8 py-2.5 bg-gray-50 border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-teal-500 focus:border-teal-500 cursor-pointer hover:bg-gray-100 transition font-medium appearance-none">
                            <option value="">Semua Role</option>
                            <option value="admin_kota" {{ request('role') == 'admin_kota' ? 'selected' : '' }}>Super Admin</option>
                            <option value="admin_instansi" {{ request('role') == 'admin_instansi' ? 'selected' : '' }}>Admin Instansi</option>
                            <option value="pembimbing_lapangan" {{ request('role') == 'pembimbing_lapangan' ? 'selected' : '' }}>Pembimbing</option>
                            <option value="peserta" {{ request('role') == 'peserta' ? 'selected' : '' }}>Peserta Magang</option>
                        </select>
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-filter text-gray-400 text-xs"></i>
                        </div>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                        </div>
                    </div>

                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400 text-sm"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Cari nama atau email..." 
                            class="w-full pl-10 pr-10 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition shadow-sm"
                            oninput="autoSubmitSearch()">
                        
                        <div id="loadingIcon" class="absolute inset-y-0 right-0 pr-3 flex items-center hidden">
                            <i class="fas fa-circle-notch fa-spin text-teal-500"></i>
                        </div>
                        
                        @if(request('search'))
                            <a href="{{ route('admin.users.index') }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-500 transition" title="Hapus Pencarian">
                                <i class="fas fa-times-circle"></i>
                            </a>
                        @endif
                    </div>
                </form>

                <a href="{{ route('admin.users.create') }}" class="flex items-center justify-center px-5 py-2.5 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition transform active:scale-95 text-sm w-full md:w-auto whitespace-nowrap">
                    <i class="fas fa-user-plus mr-2"></i> Tambah User
                </a>
            </div>

            <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">User Profile</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Role & Status</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Afiliasi / Detail</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-gray-600 font-bold border border-gray-300">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div class="ml-4 max-w-xs">
                                        <div class="text-sm font-bold text-gray-900 truncate">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500 flex items-center gap-1 break-all">
                                            {{ $user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @include('admin.users.partials.role-badge', ['role' => $user->role])
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                @include('admin.users.partials.user-detail', ['user' => $user])
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                @include('admin.users.partials.action-buttons', ['user' => $user])
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">Tidak ada data.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="grid grid-cols-1 gap-4 md:hidden">
                @forelse($users as $user)
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col gap-3">
                    
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-gray-600 font-bold border border-gray-300">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm">{{ $user->name }}</h4>
                                <p class="text-xs text-gray-500 break-all">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div>
                            @include('admin.users.partials.role-badge', ['role' => $user->role])
                        </div>
                    </div>

                    <div class="border-t border-gray-100 my-1"></div>

                    <div class="text-sm text-gray-600 space-y-2">
                        @include('admin.users.partials.user-detail', ['user' => $user])
                    </div>

                    <div class="flex items-center justify-end gap-2 mt-2 pt-2">
                        @include('admin.users.partials.action-buttons', ['user' => $user])
                    </div>
                </div>
                @empty
                <div class="text-center py-10 text-gray-500 bg-white rounded-xl">
                    <i class="fas fa-user-slash text-2xl mb-2"></i>
                    <p>Tidak ada data.</p>
                </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $users->links() }}
            </div>

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

{{-- 
    BLOCK KECIL UNTUK MENGHINDARI DUPLIKASI KODE
    (Bisa dipindah ke file terpisah, tapi disatukan disini agar mudah dicopy) 
--}}

@php
    // Logic Badge Warna
    function getRoleBadge($role) {
        $colors = [
            'admin_kota' => 'bg-purple-100 text-purple-700 border-purple-200',
            'admin_instansi' => 'bg-teal-100 text-teal-700 border-teal-200',
            'pembimbing_lapangan'     => 'bg-blue-100 text-blue-700 border-blue-200',
            'peserta'    => 'bg-green-100 text-green-700 border-green-200',
            'dosen/guru' => 'bg-orange-100 text-orange-700 border-orange-200',
        ];
        return $colors[$role] ?? 'bg-gray-100 text-gray-700';
    }
    
    function getRoleName($role) {
        $names = [
            'admin_kota' => 'Super Admin',
            'admin_instansi' => 'Admin Instansi',
            'pembimbing_lapangan'     => 'Pembimbing Lap',
            'peserta'    => 'Peserta Magang',
        ];
        return $names[$role] ?? ucwords(str_replace('_', ' ', $role));
    }
@endphp

@if(!function_exists('renderRoleBadge')) 
    {{-- Hacky way inside single file, but idealnya dipisah --}}
@endif

{{-- Karena kita di dalam satu file untuk jawaban ini, saya pakai include directive manual logic diatas --}}
@section('role-badge')
@endsection