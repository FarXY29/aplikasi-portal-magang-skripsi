<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-chalkboard-teacher text-teal-600"></i>
                {{ __('Kelola Pembimbing Lapangan') }}
            </h2>
            <div class="text-sm text-gray-500 font-medium bg-white px-4 py-1.5 rounded-full shadow-sm border border-gray-100">
                Total Pembimbing Lapangan: <span class="font-bold text-teal-600">{{ $pembimbing_lapangan->count() }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex justify-between items-center mb-6 print:hidden">
                <a href="{{ route('dinas.dashboard') }}" class="group flex items-center text-sm font-bold text-gray-500 hover:text-teal-600 transition">
                    <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                    Kembali ke Dashboard
                </a>
            </div>

            @if(session('success'))
    <x-ui.alert type="success" class="mb-4">
        {{ session('success') }}
    </x-ui.alert>
@endif

            <div class="flex flex-col lg:flex-row gap-8 items-start">
                
                <div class="w-full lg:w-1/3 bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-8">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                        <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">Tambah Pembimbing Lapangan Baru</h3>
                            <p class="text-xs text-gray-500">Buat akun untuk pegawai pembimbing.</p>
                        </div>
                    </div>

                    <form action="{{ route('dinas.pembimbing_lapangan.store') }}" method="POST" class="space-y-5">
                        @csrf
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Nama Pegawai</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" name="name" class="w-full pl-9 pr-4 py-2.5 rounded-xl border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm text-sm" placeholder="Nama Lengkap" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">NIP (Nomor Induk Pegawai)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <i class="fas fa-id-card"></i>
                                </span>
                                <input type="text" name="nip" class="w-full pl-9 pr-4 py-2.5 rounded-xl border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm text-sm" placeholder="19xxxxxxxx xxx x xxx">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Email Login</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" name="email" class="w-full pl-9 pr-4 py-2.5 rounded-xl border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm text-sm" placeholder="email@instansi.go.id" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" name="password" class="w-full pl-9 pr-4 py-2.5 rounded-xl border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm text-sm" placeholder="Minimal 8 karakter" required>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition transform active:scale-95 flex items-center justify-center gap-2">
                                <i class="fas fa-save"></i> Simpan Akun
                            </button>
                        </div>
                    </form>
                </div>

                <div class="w-full lg:w-2/3 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
                    <div class="p-6 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-gray-800">Daftar Pembimbing</h3>
                            <p class="text-xs text-gray-500 mt-1">Kelola data pembimbing lapangan yang terdaftar.</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center shadow-sm text-gray-400">
                            <i class="fas fa-list"></i>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Profil Pegawai</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kontak</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 bg-white">
                                @forelse($pembimbing_lapangan as $pembimbing_lapangan)
                                <tr class="hover:bg-gray-50 transition group">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                                {{ strtoupper(substr($pembimbing_lapangan->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">{{ $pembimbing_lapangan->name }}</div>
                                                <div class="text-xs text-gray-500 font-mono bg-gray-100 px-1.5 rounded w-fit mt-0.5">
                                                    NIP: {{ $pembimbing_lapangan->nik ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        <div class="flex items-center gap-2">
                                            <i class="far fa-envelope text-gray-400"></i> {{ $pembimbing_lapangan->email }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity duration-200">
                                            <a href="{{ route('dinas.pembimbing_lapangan.edit', $pembimbing_lapangan->id) }}" class="p-2 bg-white border border-gray-200 rounded-lg text-indigo-600 hover:bg-indigo-50 hover:border-indigo-200 transition shadow-sm" title="Edit Data">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <form action="{{ route('dinas.pembimbing_lapangan.destroy', $pembimbing_lapangan->id) }}" method="POST" @submit.prevent="$dispatch('open-confirm', { message: 'Hapus akun ini? Pembimbing ini akan hilang dari data peserta yang dibimbingnya.', onConfirm: () => $el.submit() })">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 bg-white border border-gray-200 rounded-lg text-red-500 hover:bg-red-50 hover:border-red-200 transition shadow-sm" title="Hapus Akun">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-400">
                                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                                <i class="fas fa-user-slash text-2xl text-gray-300"></i>
                                            </div>
                                            <p class="text-sm font-medium">Belum ada pembimbing_lapangan yang ditambahkan.</p>
                                            <p class="text-xs mt-1">Silakan tambah pembimbing_lapangan melalui formulir di samping.</p>
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
    </div>
</x-app-layout>