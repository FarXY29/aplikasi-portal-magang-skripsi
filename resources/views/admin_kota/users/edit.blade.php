<x-app-layout>
    @push('head')
        <meta name="turbo-cache-control" content="no-cache">
    @endpush
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-user-edit text-teal-600"></i>
                {{ __('Edit Data Pengguna') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-teal-600 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Manajemen User
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <div class="space-y-6">
                            <div class="pb-2 border-b border-gray-100 mb-4">
                                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                    <i class="fas fa-id-card text-teal-500"></i> Identitas Akun
                                </h3>
                                <p class="text-xs text-gray-500">Perbarui informasi login pengguna.</p>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-300 focus:ring-teal-500 focus:border-teal-500 transition shadow-sm" 
                                        required>
                                </div>
                                @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Email Login</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-300 focus:ring-teal-500 focus:border-teal-500 transition shadow-sm" 
                                        required>
                                </div>
                                @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-100">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Ubah Password (Opsional)</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                        <i class="fas fa-key"></i>
                                    </span>
                                    <input type="password" name="password"
                                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-300 focus:ring-teal-500 focus:border-teal-500 transition shadow-sm bg-white" 
                                        placeholder="Kosongkan jika tidak ingin mengubah">
                                </div>
                                <p class="text-xs text-gray-500 mt-2 italic">*Hanya isi jika user meminta reset password.</p>
                                @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="pb-2 border-b border-gray-100 mb-4">
                                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                    <i class="fas fa-user-tag text-blue-500"></i> Pengaturan Akses
                                </h3>
                                <p class="text-xs text-gray-500">Sesuaikan peran dan afiliasi pengguna.</p>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Role Pengguna</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                        <i class="fas fa-users-cog"></i>
                                    </span>
                                    <select name="role" id="roleSelect" onchange="toggleFields()"
                                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-300 focus:ring-teal-500 focus:border-teal-500 transition shadow-sm cursor-pointer bg-gray-50">
                                        <option value="peserta" {{ $user->role == 'peserta' ? 'selected' : '' }}>Peserta Magang</option>
                                        <option value="pembimbing" {{ $user->role == 'pembimbing' ? 'selected' : '' }}>Dosen / Guru Pembimbing</option>
                                        <option value="pembimbing_lapangan" {{ $user->role == 'pembimbing_lapangan' ? 'selected' : '' }}>Pembimbing Lapangan (Pegawai)</option>
                                        <option value="admin_instansi" {{ $user->role == 'admin_instansi' ? 'selected' : '' }}>Admin Instansi</option>
                                    </select>
                                </div>
                            </div>

                            <div class="bg-gray-50 p-5 rounded-xl border border-gray-200 transition-all duration-300">
                                
                                <div id="instansiField" class="{{ in_array($user->role, ['admin_instansi', 'pembimbing_lapangan']) ? '' : 'hidden' }}">
                                    <label class="block text-xs font-bold text-blue-600 uppercase mb-2 tracking-wide">
                                        <i class="fas fa-building mr-1"></i> Asal Instansi
                                    </label>
                                    <select name="instansi_id" class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">-- Pilih Instansi --</option>
                                        @foreach($instansis as $instansi)
                                            <option value="{{ $instansi->id }}" {{ $user->instansi_id == $instansi->id ? 'selected' : '' }}>
                                                {{ $instansi->nama_dinas }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div id="instansiField" class="{{ in_array($user->role, ['peserta', 'pembimbing']) ? '' : 'hidden' }}">
                                    <label class="block text-xs font-bold text-green-600 uppercase mb-2 tracking-wide">
                                        <i class="fas fa-university mr-1"></i> Asal Sekolah / Kampus
                                    </label>
                                    <input type="text" name="asal_instansi" value="{{ old('asal_instansi', $user->asal_instansi) }}"
                                        class="w-full rounded-lg border-gray-300 text-sm focus:ring-green-500 focus:border-green-500" 
                                        placeholder="Contoh: Universitas Lambung Mangkurat">
                                </div>

                                <div id="noneField" class="{{ $user->role == 'admin_kota' ? '' : 'hidden' }} text-center text-gray-400 text-sm py-2">
                                    <i class="fas fa-info-circle mr-1"></i> Super Admin memiliki akses penuh.
                                </div>
                            </div>

                            @if($user->created_at)
                                <div class="text-xs text-gray-400 pt-2 border-t border-gray-100">
                                    Terdaftar sejak: {{ $user->created_at->translatedFormat('d F Y') }}
                                </div>
                            @endif

                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-100">
                        <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-xl font-bold hover:bg-gray-50 transition shadow-sm">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-teal-600 text-white rounded-xl font-bold hover:bg-teal-700 shadow-lg shadow-teal-200 transition transform active:scale-95 flex items-center">
                            <i class="fas fa-save mr-2"></i> Perbarui Data
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        window.toggleFields = function() {
            const roleSelect = document.getElementById('roleSelect');
            if (!roleSelect) return;
            const role = roleSelect.value;
            const instansiField = document.getElementById('instansiField');
            const instansiField = document.getElementById('instansiField');
            const noneField = document.getElementById('noneField');

            if (instansiField) instansiField.classList.add('hidden');
            if (instansiField) instansiField.classList.add('hidden');
            if (noneField) noneField.classList.add('hidden');

            if (role === 'admin_instansi' || role === 'pembimbing_lapangan') {
                if (instansiField) instansiField.classList.remove('hidden');
            } else if (role === 'pembimbing' || role === 'peserta') {
                if (instansiField) instansiField.classList.remove('hidden');
            } else {
                if (noneField) noneField.classList.remove('hidden');
            }
        }
    </script>
</x-app-layout>