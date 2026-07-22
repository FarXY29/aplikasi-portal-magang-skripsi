<x-app-layout>
    @push('head')
        <meta name="turbo-cache-control" content="no-cache">
    @endpush
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <i class="fas fa-user-plus text-teal-600 dark:text-teal-400"></i>
                {{ __('Tambah Pengguna Baru') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('admin.users.index') }}" class="group inline-flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-sm">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Manajemen User
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 dark:border-gray-700">
                
                <form action="{{ route('admin.users.store') }}" method="POST" class="p-8">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <div class="space-y-6">
                            <div class="pb-2 border-b border-gray-100 dark:border-gray-700 mb-4">
                                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                                    <i class="fas fa-id-card text-teal-500 dark:text-teal-400"></i> Identitas Akun
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Informasi dasar untuk login sistem.</p>
                            </div>

                            <div>
                                <x-input-label for="name" value="Nama Lengkap" class="mb-2 font-bold" />
                                <x-text-input id="name" name="name" type="text" icon="fas fa-user" value="{{ old('name') }}" placeholder="Nama Lengkap User" required />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="email" value="Email Login" class="mb-2 font-bold" />
                                <x-text-input id="email" name="email" type="email" icon="fas fa-envelope" value="{{ old('email') }}" placeholder="email@example.com" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="password" value="Password" class="mb-2 font-bold" />
                                <x-text-input id="password" name="password" type="password" icon="fas fa-lock" placeholder="Minimal 8 karakter" required />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="pb-2 border-b border-gray-100 dark:border-gray-700 mb-4">
                                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                                    <i class="fas fa-user-tag text-blue-500 dark:text-blue-400"></i> Peran & Akses
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Tentukan hak akses pengguna ini.</p>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Role Pengguna</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                                        <i class="fas fa-users-cog"></i>
                                    </span>
                                    <select name="role" id="roleSelect" onchange="toggleFields()"
                                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:ring-teal-500 focus:border-teal-500 transition shadow-sm cursor-pointer font-bold text-sm">
                                        <option value="peserta">Peserta Magang</option>
                                        <option value="pembimbing">Dosen / Guru Pembimbing</option>
                                        <option value="pembimbing_lapangan">Pembimbing Lapangan (Pegawai)</option>
                                        <option value="admin_instansi">Admin Instansi</option>
                                    </select>
                                </div>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-900 p-5 rounded-xl border border-gray-200 dark:border-gray-700 transition-all duration-300" id="conditionalContainer">
                                
                                <div id="instansiDinasField" class="hidden">
                                    <label class="block text-xs font-bold text-blue-600 dark:text-blue-400 uppercase mb-2 tracking-wide">
                                        <i class="fas fa-building mr-1"></i> Asal Instansi (Wajib)
                                    </label>
                                    <select name="instansi_id" class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 text-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">-- Pilih Instansi --</option>
                                        @foreach($instansis as $instansi)
                                            <option value="{{ $instansi->id }}">{{ $instansi->nama_dinas }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1">*Admin/Pembimbing Lapangan akan terikat pada instansi ini.</p>
                                </div>

                                <div id="asalSekolahField">
                                    <label class="block text-xs font-bold text-green-600 dark:text-green-400 uppercase mb-2 tracking-wide">
                                        <i class="fas fa-university mr-1"></i> Asal Sekolah / Kampus
                                    </label>
                                    <input type="text" name="asal_instansi" value="{{ old('asal_instansi') }}"
                                        class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 text-sm focus:ring-green-500 focus:border-green-500" 
                                        placeholder="Contoh: Universitas Lambung Mangkurat">
                                </div>

                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <x-input-label for="phone" value="Nomor HP (Opsional)" class="mb-2 font-bold" />
                                    <x-text-input id="phone" name="phone" type="text" placeholder="08xxxxxxxxxx" />
                                </div>

                                <div id="noneField" class="hidden text-center text-gray-400 dark:text-gray-500 text-sm py-2">
                                    <i class="fas fa-info-circle mr-1"></i> Super Admin memiliki akses penuh.
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-bold hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-teal-600 text-white rounded-xl font-bold hover:bg-teal-700 shadow-lg shadow-teal-500/20 transition transform active:scale-95 flex items-center text-sm">
                            <i class="fas fa-save mr-2"></i> Simpan Pengguna
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
            const instansiDinasField = document.getElementById('instansiDinasField');
            const asalSekolahField = document.getElementById('asalSekolahField');
            const noneField = document.getElementById('noneField');

            if (instansiDinasField) instansiDinasField.classList.add('hidden');
            if (asalSekolahField) asalSekolahField.classList.add('hidden');
            if (noneField) noneField.classList.add('hidden');

            if (role === 'admin_instansi' || role === 'pembimbing_lapangan') {
                if (instansiDinasField) instansiDinasField.classList.remove('hidden');
            } else if (role === 'pembimbing' || role === 'peserta') {
                if (asalSekolahField) asalSekolahField.classList.remove('hidden');
            } else {
                if (noneField) noneField.classList.remove('hidden');
            }
        }
        
        document.addEventListener('DOMContentLoaded', window.toggleFields);
        document.addEventListener('turbo:load', window.toggleFields);
    </script>
</x-app-layout>