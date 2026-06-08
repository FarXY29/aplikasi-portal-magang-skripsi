<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-edit text-teal-600"></i>
                {{ __('Edit Data Instansi') }}
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
                
                <form action="{{ route('admin.instansi.update', $instansi->id) }}" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="flex items-center gap-3 mb-8 pb-4 border-b border-gray-100">
                        <div class="w-12 h-12 rounded-full bg-teal-50 flex items-center justify-center text-teal-600 text-xl border border-teal-100">
                            <i class="fas fa-building"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">{{ $instansi->nama_dinas }}</h3>
                            <p class="text-sm text-gray-500">Perbarui informasi profil dan lokasi instansi.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Instansi</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 pointer-events-none">
                                        <i class="fas fa-landmark"></i>
                                    </span>
                                    <input type="text" name="nama_dinas" value="{{ old('nama_dinas', $instansi->nama_dinas) }}" 
                                        class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-300 focus:border-teal-500 focus:ring focus:ring-teal-200 transition shadow-sm" required>
                                </div>
                                @error('nama_dinas') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Kode Unit Kerja</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 pointer-events-none">
                                        <i class="fas fa-barcode"></i>
                                    </span>
                                    <input type="text" name="kode_unit_kerja" value="{{ old('kode_unit_kerja', $instansi->kode_unit_kerja) }}"
                                        class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-300 focus:border-teal-500 focus:ring focus:ring-teal-200 transition shadow-sm" required>
                                </div>
                                @error('kode_unit_kerja') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Kantor</label>
                                <div class="relative">
                                    <textarea name="alamat" rows="4"
                                        class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-300 focus:border-teal-500 focus:ring focus:ring-teal-200 transition shadow-sm" 
                                        required>{{ old('alamat', $instansi->alamat) }}</textarea>
                                    <span class="absolute top-3 left-3 text-gray-400 pointer-events-none">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </span>
                                </div>
                                @error('alamat') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="space-y-6">
                            
                            <div class="bg-blue-50/50 p-5 rounded-xl border border-blue-100">
                                <label class="block text-sm font-bold text-gray-700 mb-3 flex justify-between">
                                    <span>Koordinat Lokasi</span>
                                    <span class="text-xs text-blue-600 font-normal"><i class="fas fa-satellite-dish mr-1"></i> Untuk Absensi</span>
                                </label>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 text-xs font-bold pointer-events-none">LAT</span>
                                        <input type="text" name="latitude" value="{{ old('latitude', $instansi->latitude) }}"
                                            class="w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 focus:border-teal-500 focus:ring-teal-200 text-sm" required>
                                    </div>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 text-xs font-bold pointer-events-none">LNG</span>
                                        <input type="text" name="longitude" value="{{ old('longitude', $instansi->longitude) }}"
                                            class="w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 focus:border-teal-500 focus:ring-teal-200 text-sm" required>
                                    </div>
                                </div>
                                @error('latitude') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Scan Tanda Tangan Kepala Dinas</label>
                                <p class="text-xs text-gray-500 mb-3">Format: PNG Transparan (Disarankan). Maks 2MB.</p>
                                
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0">
                                        @if($instansi->ttd_kepala)
                                            <div class="relative group">
                                                <div class="w-24 h-24 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-white overflow-hidden p-1">
                                                    <img src="{{ asset('storage/' . $instansi->ttd_kepala) }}" alt="TTD Preview" class="max-h-full max-w-full object-contain">
                                                </div>
                                                <span class="text-[10px] text-center block mt-1 text-green-600 font-bold">Terupload <i class="fas fa-check"></i></span>
                                            </div>
                                        @else
                                            <div class="w-24 h-24 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-gray-100 text-gray-400 text-xs text-center p-2">
                                                Belum ada TTD
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex-grow">
                                        <input type="file" name="ttd_kepala" accept="image/png, image/jpeg"
                                            class="block w-full text-sm text-gray-500
                                            file:mr-4 file:py-2 file:px-4
                                            file:rounded-full file:border-0
                                            file:text-xs file:font-semibold
                                            file:bg-teal-50 file:text-teal-700
                                            hover:file:bg-teal-100
                                            cursor-pointer focus:outline-none border border-gray-300 rounded-lg">
                                        <p class="text-xs text-gray-400 mt-2 italic">Biarkan kosong jika tidak ingin mengubah tanda tangan.</p>
                                    </div>
                                </div>
                                @error('ttd_kepala') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 mt-10 pt-6 border-t border-gray-100">
                        <a href="{{ route('admin.instansi.index') }}" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-xl font-bold hover:bg-gray-50 transition shadow-sm">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-teal-600 text-white rounded-xl font-bold hover:bg-teal-700 shadow-lg shadow-teal-200 transition transform active:scale-95 flex items-center">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>