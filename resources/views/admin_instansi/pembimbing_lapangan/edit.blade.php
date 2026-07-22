<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <i class="fas fa-user-edit text-teal-600 dark:text-teal-400"></i>
                {{ __('Edit Data Pembimbing') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('dinas.pembimbing_lapangan.index') }}" class="group inline-flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-sm">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Daftar Pembimbing Lapangan
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 flex items-center justify-center text-indigo-600 dark:text-indigo-400 text-xl border border-indigo-100 dark:border-indigo-800/50">
                        <i class="fas fa-user-cog"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Form Edit Data</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Perbarui informasi akun pegawai pembimbing.</p>
                    </div>
                </div>

                <div class="p-8">
                    <form action="{{ route('dinas.pembimbing_lapangan.update', $pembimbing_lapangan->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-6">
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nama Pegawai</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" name="name" value="{{ old('name', $pembimbing_lapangan->name) }}" 
                                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm text-sm" 
                                        required>
                                </div>
                                @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">NIP (Nomor Induk Pegawai)</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                                        <i class="fas fa-id-card"></i>
                                    </span>
                                    <input type="text" name="nip" value="{{ old('nip', $pembimbing_lapangan->nik) }}" 
                                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm text-sm" 
                                        placeholder="19xxxxxxxx xxx x xxx">
                                </div>
                                @error('nip') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Email Login</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" name="email" value="{{ old('email', $pembimbing_lapangan->email) }}" 
                                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm text-sm" 
                                        required>
                                </div>
                                @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="bg-yellow-50 dark:bg-yellow-950/20 border border-yellow-100 dark:border-yellow-900/30 rounded-xl p-5">
                                <label class="block text-sm font-bold text-gray-800 dark:text-gray-200 mb-2 flex items-center gap-2">
                                    <i class="fas fa-key text-yellow-600 dark:text-yellow-500"></i> Password Baru (Opsional)
                                </label>
                                <div class="relative">
                                    <input type="password" name="password" 
                                        class="w-full px-4 py-2.5 rounded-lg border-gray-300 dark:border-gray-700 focus:ring-yellow-500 focus:border-yellow-500 transition shadow-sm text-sm bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500" 
                                        placeholder="Kosongkan jika tidak ingin mengubah password">
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 italic">*Hanya isi jika pegawai lupa password atau ingin reset.</p>
                                @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 flex flex-col-reverse sm:flex-row items-center justify-end gap-3">
                            <a href="{{ route('dinas.pembimbing_lapangan.index') }}" class="w-full sm:w-auto text-center px-5 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 font-bold hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-800 dark:hover:text-gray-100 transition text-sm">
                                Batal
                            </a>
                            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-teal-600 text-white rounded-xl font-bold hover:bg-teal-700 shadow-lg shadow-teal-500/20 transition transform active:scale-95 text-sm flex items-center justify-center gap-2">
                                <i class="fas fa-check-circle"></i> Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>