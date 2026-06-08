<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-plus-circle text-teal-600"></i>
                {{ __('Tambah Instansi Baru') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('admin.instansi.index') }}" class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-teal-600 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Instansi
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                
                <form action="{{ route('admin.instansi.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf
                    
                    <div class="mb-10">
                        <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-100">
                            <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-600">
                                <i class="fas fa-building"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Informasi Instansi</h3>
                                <p class="text-xs text-gray-500">Isi data profil dinas atau badan terkait.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Instansi<span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-landmark text-gray-400"></i>
                                    </div>
                                    <input type="text" name="nama_dinas" value="{{ old('nama_dinas') }}" 
                                        class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-300 focus:border-teal-500 focus:ring focus:ring-teal-200 transition shadow-sm" 
                                        placeholder="Contoh: Dinas Komunikasi dan Informatika" required>
                                </div>
                                @error('nama_dinas') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Kode Unit Kerja <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-barcode text-gray-400"></i>
                                    </div>
                                    <input type="text" name="kode_unit_kerja" value="{{ old('kode_unit_kerja') }}"
                                        class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-300 focus:border-teal-500 focus:ring focus:ring-teal-200 transition shadow-sm" 
                                        required>
                                </div>
                                @error('kode_unit_kerja') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Kantor <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 top-3 pointer-events-none">
                                        <i class="fas fa-map-marker-alt text-gray-400"></i>
                                    </div>
                                    <textarea name="alamat" rows="1"
                                        class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-300 focus:border-teal-500 focus:ring focus:ring-teal-200 transition shadow-sm" 
                                        placeholder="Jl. RE Martadinata No..." required>{{ old('alamat') }}</textarea>
                                </div>
                                @error('alamat') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="md:col-span-2 bg-gray-50 p-4 rounded-xl border border-gray-200">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Scan Tanda Tangan Kepala Dinas</label>
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-white rounded-lg border border-gray-300 flex items-center justify-center text-gray-300">
                                        <i class="fas fa-file-signature text-xl"></i>
                                    </div>
                                    <div class="flex-grow">
                                        <input type="file" name="ttd_kepala" accept="image/png, image/jpeg"
                                            class="block w-full text-sm text-gray-500
                                            file:mr-4 file:py-2 file:px-4
                                            file:rounded-full file:border-0
                                            file:text-xs file:font-semibold
                                            file:bg-teal-600 file:text-white
                                            hover:file:bg-teal-700
                                            cursor-pointer focus:outline-none border border-gray-300 rounded-lg bg-white">
                                        <p class="text-xs text-gray-500 mt-1">Format: PNG Transparan (Disarankan) atau JPG. Maks 2MB.</p>
                                    </div>
                                </div>
                                @error('ttd_kepala') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="md:col-span-2 bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                                <label class="block text-sm font-bold text-gray-700 mb-3 flex justify-between">
                                    <span>Titik Koordinat (Geotagging)</span>
                                    <a href="https://maps.google.com" target="_blank" class="text-blue-600 text-xs font-normal hover:underline flex items-center">
                                        <i class="fas fa-external-link-alt mr-1"></i> Buka Google Maps
                                    </a>
                                </label>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 text-xs font-bold">LAT</span>
                                            <input type="text" name="latitude" value="{{ old('latitude') }}"
                                                class="w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm" 
                                                placeholder="-3.319xxx" required>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 text-xs font-bold">LNG</span>
                                            <input type="text" name="longitude" value="{{ old('longitude') }}"
                                                class="w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm" 
                                                placeholder="114.590xxx" required>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-2"><i class="fas fa-info-circle mr-1"></i> Digunakan untuk validasi radius absensi peserta magang.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-100">
                            <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Buat Akun Admin</h3>
                                <p class="text-xs text-gray-500">Akun ini digunakan instansi untuk login dan mengelola magang.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Email Login <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-envelope text-gray-400"></i>
                                    </div>
                                    <input type="email" name="email_admin" value="{{ old('email_admin') }}"
                                        class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-300 focus:border-orange-500 focus:ring focus:ring-orange-200 transition shadow-sm" 
                                        placeholder="admin.instansi@banjarmasin.go.id" required>
                                </div>
                                @error('email_admin') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-lock text-gray-400"></i>
                                    </div>
                                    <input type="password" name="password_admin"
                                        class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-300 focus:border-orange-500 focus:ring focus:ring-orange-200 transition shadow-sm" 
                                        placeholder="Minimal 8 karakter" required>
                                </div>
                                @error('password_admin') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-100">
                        <a href="{{ route('admin.instansi.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-3 bg-teal-600 text-white rounded-xl font-bold hover:bg-teal-700 shadow-lg shadow-teal-200 transition transform active:scale-95 flex items-center">
                            <i class="fas fa-save mr-2"></i> Simpan Data
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>