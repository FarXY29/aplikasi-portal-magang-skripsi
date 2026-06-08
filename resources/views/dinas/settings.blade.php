<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-cog text-teal-600"></i>
                {{ __('Pengaturan Instansi') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen font-sans">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="mb-6">
                <a href="{{ route('dinas.dashboard') }}" class="group inline-flex items-center text-sm font-bold text-gray-500 hover:text-teal-600 transition">
                    <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                    Kembali ke Dashboard
                </a>
            </div>

            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" class="flex items-center p-4 mb-4 text-green-800 rounded-xl bg-green-50 border border-green-100 shadow-sm relative">
                    <i class="fas fa-check-circle flex-shrink-0 w-5 h-5 mr-3 text-green-600"></i>
                    <div class="text-sm font-bold">{{ session('success') }}</div>
                    <button @click="show = false" class="ml-auto text-green-500 hover:text-green-700"><i class="fas fa-times"></i></button>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 bg-gray-50/50 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 text-xl border border-blue-100">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Jam Operasional Absensi</h3>
                        <p class="text-xs text-gray-500">Tentukan batas waktu absensi bagi peserta magang.</p>
                    </div>
                </div>
                
                <div class="p-8">
                    <form action="{{ route('dinas.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Jam Buka Absen Datang</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                        <i class="fas fa-sign-in-alt"></i>
                                    </span>
                                    <input type="time" name="jam_mulai_masuk" value="{{ $instansi->jam_mulai_masuk }}" 
                                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition shadow-sm font-medium">
                                </div>
                                <p class="text-xs text-gray-400 mt-2">Tombol absen datang aktif setelah jam ini.</p>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Jam Buka Absen Pulang</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </span>
                                    <input type="time" name="jam_mulai_pulang" value="{{ $instansi->jam_mulai_pulang }}" 
                                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition shadow-sm font-medium">
                                </div>
                                <p class="text-xs text-gray-400 mt-2">Tombol absen pulang aktif setelah jam ini.</p>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 shadow-md transition flex items-center transform active:scale-95">
                                <i class="fas fa-save mr-2"></i> Simpan Jam Kerja
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 bg-gray-50/50 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600 text-xl border border-teal-100">
                        <i class="fas fa-file-signature"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Data Penandatangan Dokumen</h3>
                        <p class="text-xs text-gray-500">Digunakan untuk Sertifikat dan Transkrip Nilai.</p>
                    </div>
                </div>

                <div class="p-8">
                    
                    <div class="bg-teal-50 border border-teal-100 rounded-xl p-4 mb-8 flex items-start gap-3">
                        <i class="fas fa-info-circle text-teal-600 mt-0.5"></i>
                        <p class="text-sm text-teal-800 leading-relaxed">
                            Data pejabat di bawah ini akan otomatis muncul pada bagian tanda tangan dokumen resmi (Sertifikat & Transkrip). 
                            Pastikan data <strong>Jabatan, Nama, NIP</strong>, dan <strong>Scan Tanda Tangan</strong> sudah benar.
                        </p>
                    </div>

                    <form action="{{ route('dinas.pejabat.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Jabatan</label>
                                    <input type="text" name="jabatan_pejabat" value="{{ old('jabatan_pejabat', $instansi->jabatan_pejabat) }}"
                                        class="w-full rounded-xl border-gray-300 focus:border-teal-500 focus:ring focus:ring-teal-200 transition shadow-sm text-sm"
                                        placeholder="Contoh: Kepala Dinas Kominfo">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Pejabat</label>
                                    <input type="text" name="nama_pejabat" value="{{ old('nama_pejabat', $instansi->nama_pejabat) }}"
                                        class="w-full rounded-xl border-gray-300 focus:border-teal-500 focus:ring focus:ring-teal-200 transition shadow-sm text-sm font-bold"
                                        placeholder="Nama Lengkap beserta gelar">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">NIP</label>
                                    <input type="text" name="nip_pejabat" value="{{ old('nip_pejabat', $instansi->nip_pejabat) }}"
                                        class="w-full rounded-xl border-gray-300 focus:border-teal-500 focus:ring focus:ring-teal-200 transition shadow-sm text-sm font-mono"
                                        placeholder="19xxxxxxxx xxx x xxx">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Scan Tanda Tangan</label>
                                
                                <div class="border-2 border-dashed border-gray-300 rounded-xl bg-gray-50 h-40 flex items-center justify-center mb-4 relative overflow-hidden group">
                                    @if($instansi->ttd_kepala)
                                        <img src="{{ asset('storage/' . $instansi->ttd_kepala) }}" class="h-28 object-contain z-10">
                                        <div class="absolute inset-0 bg-white/80 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300 z-20">
                                            <span class="text-xs font-bold text-gray-500">Tanda tangan saat ini</span>
                                        </div>
                                    @else
                                        <div class="text-center text-gray-400">
                                            <i class="fas fa-image text-3xl mb-2"></i>
                                            <p class="text-xs">Belum ada tanda tangan</p>
                                        </div>
                                    @endif
                                </div>

                                <input type="file" name="ttd_kepala" accept="image/png"
                                    class="block w-full text-xs text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-full file:border-0
                                    file:text-xs file:font-semibold
                                    file:bg-teal-50 file:text-teal-700
                                    hover:file:bg-teal-100
                                    cursor-pointer border border-gray-300 rounded-lg p-1 bg-white">
                                <p class="text-[10px] text-gray-400 mt-2 ml-1">
                                    *Format wajib <strong>PNG Transparan</strong> agar hasil cetak rapi.
                                </p>
                            </div>

                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                            <button type="submit" class="px-6 py-2.5 bg-teal-600 text-white rounded-xl font-bold hover:bg-teal-700 shadow-md transition flex items-center transform active:scale-95">
                                <i class="fas fa-check-circle mr-2"></i> Simpan Data Pejabat
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-gray-100 rounded-2xl p-8 border border-dashed border-gray-300 text-center">
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6">Preview Tampilan di Dokumen</h4>
                <div class="inline-block bg-white p-8 shadow-lg max-w-sm w-full mx-auto relative rotate-1 hover:rotate-0 transition duration-500 transform">
                    <div class="text-left space-y-1">
                        <p class="text-sm font-medium text-gray-600">Mengetahui,</p>
                        <p class="text-sm font-bold text-gray-800">{{ $instansi->jabatan_pejabat ?? '[Jabatan Kosong]' }}</p>
                    </div>
                    
                    <div class="h-24 flex items-center justify-start my-2">
                        @if($instansi->ttd_kepala)
                            <img src="{{ asset('storage/' . $instansi->ttd_kepala) }}" class="h-20 object-contain">
                        @else
                            <div class="w-full h-full border border-dashed border-gray-200 bg-gray-50 flex items-center justify-center text-xs text-gray-300 italic rounded">
                                Area Tanda Tangan
                            </div>
                        @endif
                    </div>

                    <div class="text-left">
                        <p class="text-sm font-bold text-gray-800 border-b border-black inline-block pb-0.5 mb-1">
                            {{ $instansi->nama_pejabat ?? '[Nama Pejabat Kosong]' }}
                        </p>
                        <p class="text-xs text-gray-600">NIP. {{ $instansi->nip_pejabat ?? '-' }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>