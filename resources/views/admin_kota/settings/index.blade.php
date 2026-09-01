<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <i class="fas fa-cogs text-teal-600"></i>
                {{ __('Pengaturan Sistem') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50 dark:bg-gray-950 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition group">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
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

            @if(session('error'))
                <x-ui.alert type="error" class="mb-4">
                    {{ session('error') }}
                </x-ui.alert>
            @endif

            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="space-y-8">

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl border border-gray-100 dark:border-gray-700">
                        <div class="p-6 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-950/40 flex items-center justify-center text-blue-600 dark:text-blue-400 shadow-inner">
                                <i class="fas fa-laptop-code text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Identitas Aplikasi</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Konfigurasi nama dan branding dasar sistem.</p>
                            </div>
                        </div>
                        <div class="p-8">
                            <div class="max-w-2xl">
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nama Aplikasi</label>
                                <div class="relative group">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 group-focus-within:text-blue-500 transition">
                                        <i class="fas fa-heading"></i>
                                    </span>
                                    <input type="text" name="app_name" value="{{ old('app_name', $settings['app_name'] ?? 'SiMagang Banjarmasin') }}"
                                        class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-300 dark:border-gray-800 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm"
                                        placeholder="Masukkan nama aplikasi...">
                                </div>
                                <p class="text-xs text-gray-400 mt-2 flex items-center">
                                    <i class="fas fa-info-circle mr-1.5"></i> Nama ini akan tampil di halaman login, title bar browser, dan footer.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl border border-gray-100 dark:border-gray-700">
                        <div class="p-6 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-teal-100 dark:bg-teal-950/40 flex items-center justify-center text-teal-600 dark:text-teal-400 shadow-inner">
                                <i class="fas fa-user-tie text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Pejabat Penandatangan</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Data ini digunakan pada dokumen resmi yang diterbitkan sistem.</p>
                            </div>
                        </div>
                        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="pejabat_name" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nama Pejabat</label>
                                <input id="pejabat_name" type="text" name="pejabat_name" value="{{ old('pejabat_name', $settings['pejabat_name'] ?? '') }}"
                                    class="w-full py-3 px-4 rounded-xl border-gray-300 dark:border-gray-800 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition shadow-sm">
                                @error('pejabat_name') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="pejabat_nip" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">NIP</label>
                                <input id="pejabat_nip" type="text" name="pejabat_nip" value="{{ old('pejabat_nip', $settings['pejabat_nip'] ?? '') }}"
                                    class="w-full py-3 px-4 rounded-xl border-gray-300 dark:border-gray-800 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition shadow-sm">
                                @error('pejabat_nip') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label for="pejabat_jabatan" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Jabatan</label>
                                <input id="pejabat_jabatan" type="text" name="pejabat_jabatan" value="{{ old('pejabat_jabatan', $settings['pejabat_jabatan'] ?? '') }}"
                                    class="w-full py-3 px-4 rounded-xl border-gray-300 dark:border-gray-800 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition shadow-sm">
                                @error('pejabat_jabatan') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-2"
                                 x-data="{
                                     currentUrl: @js(!empty($settings['ttd_image']) ? asset('storage/' . $settings['ttd_image']) : '', JSON_UNESCAPED_SLASHES),
                                     previewUrl: @js(!empty($settings['ttd_image']) ? asset('storage/' . $settings['ttd_image']) : '', JSON_UNESCAPED_SLASHES),
                                     isNew: false,
                                     imgFailed: false,
                                     handleFileChange(event) {
                                         const file = event.target.files[0];
                                         if (file) {
                                             if (file.size > 2 * 1024 * 1024) {
                                                 alert('Ukuran file maksimal adalah 2MB');
                                                 this.resetSelection();
                                                 return;
                                             }
                                             this.previewUrl = URL.createObjectURL(file);
                                             this.isNew = true;
                                             this.imgFailed = false;
                                         }
                                     },
                                     resetSelection() {
                                         const input = this.$refs.fileInput;
                                         if (input) input.value = '';
                                         this.previewUrl = this.currentUrl;
                                         this.isNew = false;
                                         this.imgFailed = false;
                                     }
                                 }">
                                <div class="flex items-center justify-between mb-2">
                                    <label for="ttd_image" class="block text-sm font-bold text-gray-700 dark:text-gray-300">Tanda Tangan Digital / Stempel (Opsional)</label>
                                    <template x-if="isNew">
                                        <button type="button" x-on:click="resetSelection()" class="text-xs text-rose-600 dark:text-rose-400 hover:underline font-semibold flex items-center gap-1">
                                            <i class="fas fa-undo text-[10px]"></i> Batalkan Pilihan
                                        </button>
                                    </template>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Format: PNG Transparan (Disarankan), JPG, JPEG. Maks 2MB.</p>

                                <div class="flex items-start gap-4">
                                    {{-- Preview Box --}}
                                    <div class="flex-shrink-0 text-center">
                                        <div class="w-24 h-24 sm:w-28 sm:h-28 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl flex items-center justify-center bg-white dark:bg-gray-800 overflow-hidden p-1.5 transition-all duration-200 group relative shadow-2xs">
                                            <img :src="previewUrl" x-show="previewUrl && !imgFailed" x-on:error="imgFailed = true" alt="TTD Pejabat" class="max-h-full max-w-full object-contain filter drop-shadow-xs transition-transform duration-200 group-hover:scale-105">
                                            <div x-show="!previewUrl || imgFailed" class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500 text-xs p-2 text-center">
                                                <i class="fas fa-signature text-2xl mb-1 text-gray-300 dark:text-gray-600"></i>
                                                <span class="text-[10px] leading-tight font-medium">Belum ada TTD</span>
                                            </div>
                                        </div>
                                        
                                        {{-- Status Badge --}}
                                        <div class="mt-1.5">
                                            <template x-if="isNew">
                                                <span class="inline-flex items-center gap-1 text-[11px] text-teal-600 dark:text-teal-400 font-bold bg-teal-50 dark:bg-teal-950/50 px-2 py-0.5 rounded-full border border-teal-200 dark:border-teal-800">
                                                    <i class="fas fa-magic text-[10px]"></i> TTD Baru
                                                </span>
                                            </template>
                                            <template x-if="!isNew && previewUrl && !imgFailed">
                                                <span class="inline-flex items-center gap-1 text-[11px] text-emerald-600 dark:text-emerald-400 font-bold bg-emerald-50 dark:bg-emerald-950/50 px-2 py-0.5 rounded-full border border-emerald-200 dark:border-emerald-800">
                                                    <i class="fas fa-check-circle text-[10px]"></i> Terpasang
                                                </span>
                                            </template>
                                            <template x-if="!isNew && (!previewUrl || imgFailed)">
                                                <span class="inline-flex items-center gap-1 text-[11px] text-gray-400 dark:text-gray-500 font-medium">
                                                    <i class="fas fa-minus-circle text-[10px]"></i> Kosong
                                                </span>
                                            </template>
                                        </div>
                                    </div>

                                    {{-- Upload Input --}}
                                    <div class="flex-grow space-y-2">
                                        <input id="ttd_image" type="file" name="ttd_image" x-ref="fileInput" x-on:change="handleFileChange($event)" accept="image/png, image/jpeg, image/jpg"
                                            class="block w-full text-xs text-gray-500 dark:text-gray-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-teal-50 dark:file:bg-teal-950/40 file:text-teal-700 dark:file:text-teal-300 hover:file:bg-teal-100 dark:hover:file:bg-teal-900/50 cursor-pointer border border-gray-300 dark:border-gray-600 dark:bg-gray-900 rounded-xl shadow-xs transition">
                                        <p class="text-xs text-gray-400 dark:text-gray-500 italic">Biarkan kosong jika tidak ingin mengubah tanda tangan pejabat.</p>
                                        @error('ttd_image') <p class="mt-2 text-xs text-red-600 font-semibold">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl border border-gray-100 dark:border-gray-700"
                         x-data="{
                             kopLine1: @js(old('kop_line1', $settings['kop_line1'] ?? 'PEMERINTAH KOTA BANJARMASIN')),
                             kopLine2: @js(old('kop_line2', $settings['kop_line2'] ?? 'BADAN KESATUAN BANGSA DAN POLITIK')),
                             kopLine3: @js(old('kop_line3', $settings['kop_line3'] ?? 'Jalan RE Martadinata No. 1, Telp (0511) 3352932, Banjarmasin 70111')),
                             defaultLogoUrl: @js(asset('images/Banjarmasin_Logo.svg.png'), JSON_UNESCAPED_SLASHES),
                             currentLogoUrl: @js(!empty($settings['kop_logo']) ? asset('storage/' . $settings['kop_logo']) : asset('images/Banjarmasin_Logo.svg.png'), JSON_UNESCAPED_SLASHES),
                             previewLogoUrl: @js(!empty($settings['kop_logo']) ? asset('storage/' . $settings['kop_logo']) : asset('images/Banjarmasin_Logo.svg.png'), JSON_UNESCAPED_SLASHES),
                             isCustomLogo: @js(!empty($settings['kop_logo'])),
                             isNewLogo: false,
                             logoFailed: false,
                             handleLogoChange(event) {
                                 const file = event.target.files[0];
                                 if (file) {
                                     if (file.size > 2 * 1024 * 1024) {
                                         alert('Ukuran file maksimal adalah 2MB');
                                         this.resetLogo();
                                         return;
                                     }
                                     this.previewLogoUrl = URL.createObjectURL(file);
                                     this.isNewLogo = true;
                                     this.logoFailed = false;
                                 }
                             },
                             resetLogo() {
                                 const input = this.$refs.kopLogoInput;
                                 if (input) input.value = '';
                                 this.previewLogoUrl = this.currentLogoUrl;
                                 this.isNewLogo = false;
                                 this.logoFailed = false;
                             }
                         }">
                        <div class="p-6 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-indigo-100 dark:bg-indigo-950/40 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shadow-inner">
                                <i class="fas fa-file-invoice text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Kop Dokumen Laporan</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Konfigurasi teks dan logo kop surat resmi pada seluruh dokumen cetak PDF Super Admin.</p>
                            </div>
                        </div>
                        <div class="p-8 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="kop_line1" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Kop Baris 1 (Pemerintah / Instansi Induk)</label>
                                    <input id="kop_line1" type="text" name="kop_line1" x-model="kopLine1"
                                        class="w-full py-3 px-4 rounded-xl border-gray-300 dark:border-gray-800 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm"
                                        placeholder="Contoh: PEMERINTAH KOTA BANJARMASIN">
                                    @error('kop_line1') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="kop_line2" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Kop Baris 2 (Nama Badan / Dinas)</label>
                                    <input id="kop_line2" type="text" name="kop_line2" x-model="kopLine2"
                                        class="w-full py-3 px-4 rounded-xl border-gray-300 dark:border-gray-800 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm"
                                        placeholder="Contoh: BADAN KESATUAN BANGSA DAN POLITIK">
                                    @error('kop_line2') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label for="kop_line3" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Kop Baris 3 (Alamat & Informasi Kontak)</label>
                                    <input id="kop_line3" type="text" name="kop_line3" x-model="kopLine3"
                                        class="w-full py-3 px-4 rounded-xl border-gray-300 dark:border-gray-800 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm"
                                        placeholder="Contoh: Jalan RE Martadinata No. 1, Telp (0511) 3352932, Banjarmasin 70111">
                                    @error('kop_line3') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <div class="flex items-center justify-between mb-2">
                                        <label for="kop_logo" class="block text-sm font-bold text-gray-700 dark:text-gray-300">Logo Kop Surat (Opsional)</label>
                                        <template x-if="isNewLogo">
                                            <button type="button" x-on:click="resetLogo()" class="text-xs text-rose-600 dark:text-rose-400 hover:underline font-semibold flex items-center gap-1">
                                                <i class="fas fa-undo text-[10px]"></i> Batalkan Pilihan
                                            </button>
                                        </template>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Format: PNG, JPG, JPEG, WebP. Maksimal 2MB. Jika dikosongkan, logo default Kota Banjarmasin akan otomatis digunakan.</p>

                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0 text-center">
                                            <div class="w-20 h-20 sm:w-24 sm:h-24 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl flex items-center justify-center bg-white dark:bg-gray-800 overflow-hidden p-1.5 transition-all duration-200 group relative shadow-2xs">
                                                <img :src="previewLogoUrl" x-show="previewLogoUrl && !logoFailed" x-on:error="logoFailed = true" alt="Logo Kop" class="max-h-full max-w-full object-contain filter drop-shadow-xs transition-transform duration-200 group-hover:scale-105">
                                                <div x-show="!previewLogoUrl || logoFailed" class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500 text-xs p-2 text-center">
                                                    <i class="fas fa-landmark text-2xl mb-1 text-gray-300 dark:text-gray-600"></i>
                                                    <span class="text-[10px] leading-tight font-medium">Logo Bawaan</span>
                                                </div>
                                            </div>
                                            <div class="mt-1.5">
                                                <template x-if="isNewLogo">
                                                    <span class="inline-flex items-center gap-1 text-[11px] text-indigo-600 dark:text-indigo-400 font-bold bg-indigo-50 dark:bg-indigo-950/50 px-2 py-0.5 rounded-full border border-indigo-200 dark:border-indigo-800">
                                                        <i class="fas fa-magic text-[10px]"></i> Logo Baru
                                                    </span>
                                                </template>
                                                <template x-if="!isNewLogo && isCustomLogo">
                                                    <span class="inline-flex items-center gap-1 text-[11px] text-emerald-600 dark:text-emerald-400 font-bold bg-emerald-50 dark:bg-emerald-950/50 px-2 py-0.5 rounded-full border border-emerald-200 dark:border-emerald-800">
                                                        <i class="fas fa-check-circle text-[10px]"></i> Logo Kustom
                                                    </span>
                                                </template>
                                                <template x-if="!isNewLogo && !isCustomLogo">
                                                    <span class="inline-flex items-center gap-1 text-[11px] text-gray-500 dark:text-gray-400 font-medium bg-gray-100 dark:bg-gray-800 px-2 py-0.5 rounded-full">
                                                        <i class="fas fa-shield-alt text-[10px]"></i> Logo Bawaan
                                                    </span>
                                                </template>
                                            </div>
                                        </div>

                                        <div class="flex-grow space-y-2">
                                            <input id="kop_logo" type="file" name="kop_logo" x-ref="kopLogoInput" x-on:change="handleLogoChange($event)" accept="image/png, image/jpeg, image/jpg, image/webp"
                                                class="block w-full text-xs text-gray-500 dark:text-gray-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 dark:file:bg-indigo-950/40 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900/50 cursor-pointer border border-gray-300 dark:border-gray-600 dark:bg-gray-900 rounded-xl shadow-xs transition">
                                            <p class="text-xs text-gray-400 dark:text-gray-500 italic">Unggah gambar logo jika ingin mengganti logo Pemerintah Kota Banjarmasin standar.</p>
                                            @error('kop_logo') <p class="mt-2 text-xs text-red-600 font-semibold">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Live Preview Kop Box --}}
                            <div class="bg-gray-50 dark:bg-gray-900/60 rounded-xl p-5 border border-dashed border-gray-300 dark:border-gray-700">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider flex items-center gap-1.5">
                                        <i class="fas fa-eye text-indigo-500"></i> Live Preview Kop Surat (Ukuran Dokumen PDF)
                                    </span>
                                    <span class="text-[11px] text-gray-400 dark:text-gray-500">Tampilan otomatis diperbarui secara real-time</span>
                                </div>
                                <div class="bg-white dark:bg-gray-950 p-6 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm">
                                    <div class="border-b-4 border-double border-gray-800 dark:border-gray-200 pb-3 flex items-center gap-4 sm:gap-6">
                                        <div class="w-14 sm:w-16 flex-shrink-0 flex items-center justify-center">
                                            <img :src="previewLogoUrl" alt="Logo Kop Preview" class="max-h-16 max-w-full object-contain">
                                        </div>
                                        <div class="flex-grow text-center">
                                            <div class="text-xs sm:text-sm font-bold uppercase tracking-wide text-gray-900 dark:text-gray-100" x-text="kopLine1 || 'PEMERINTAH KOTA BANJARMASIN'"></div>
                                            <div class="text-sm sm:text-base font-extrabold uppercase tracking-wider text-gray-900 dark:text-gray-100 mt-0.5" x-text="kopLine2 || 'BADAN KESATUAN BANGSA DAN POLITIK'"></div>
                                            <div class="text-[11px] sm:text-xs text-gray-600 dark:text-gray-400 italic mt-1" x-text="kopLine3 || 'Jalan RE Martadinata No. 1, Telp (0511) 3352932, Banjarmasin 70111'"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl border border-gray-100 dark:border-gray-700"
                         x-data="{ announcement: @js(old('announcement', $settings['announcement'] ?? '')) }">
                        <div class="p-6 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-orange-100 dark:bg-orange-950/40 flex items-center justify-center text-orange-600 dark:text-orange-400 shadow-inner">
                                <i class="fas fa-bullhorn text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Papan Pengumuman</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Informasi global untuk seluruh peserta magang.</p>
                            </div>
                        </div>

                        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Isi Pengumuman</label>
                                <textarea name="announcement" x-model="announcement" rows="5"
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-800 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition shadow-sm text-sm"
                                    placeholder="Contoh: Pendaftaran magang periode Juli dibuka mulai tanggal..."></textarea>
                                <p class="text-xs text-gray-400 mt-2">
                                    Kosongkan jika tidak ada pengumuman.
                                </p>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-4 border border-dashed border-gray-300 dark:border-gray-700 flex flex-col h-full">
                                <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-3 block text-center">Live Preview Dashboard</span>

                                <div class="bg-yellow-50 dark:bg-yellow-950/20 border-l-4 border-yellow-400 dark:border-yellow-500 p-4 rounded-r shadow-sm flex-grow">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-info-circle text-yellow-600 dark:text-yellow-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700 dark:text-yellow-400 font-medium">
                                                <span x-text="announcement ? announcement : 'Tidak ada pengumuman aktif saat ini.'"></span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end pt-2">
                        <button type="submit" class="inline-flex items-center px-8 py-3 bg-gray-900 dark:bg-teal-600 text-white font-bold rounded-xl shadow-lg hover:bg-gray-800 dark:hover:bg-teal-500 hover:shadow-xl transition transform hover:-translate-y-0.5 active:scale-95">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan Pengaturan
                        </button>
                    </div>

                </div>
            </form>

            {{-- Card Backup Database --}}
            <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl border border-gray-100 dark:border-gray-700">
                <div class="p-6 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-950/40 flex items-center justify-center text-purple-600 dark:text-purple-400 shadow-inner">
                            <i class="fas fa-database text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Backup Database</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Mencadangkan seluruh data sistem saat ini (Format .sql). Berkas aktif selama 7 hari.</p>
                        </div>
                    </div>

                    {{-- Form Backup --}}
                    <form action="{{ route('admin.settings.backup') }}" method="POST" class="flex flex-col sm:flex-row gap-2.5 sm:items-center"
                          x-data="{ isSubmitting: false }" x-on:submit="isSubmitting = true">
                        @csrf
                        <div class="relative">
                            <input type="password" name="password" autocomplete="current-password" placeholder="Konfirmasi password akun"
                                   class="w-full sm:w-56 py-2.5 px-4 rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-sm text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition shadow-sm"
                                   aria-label="Konfirmasi password untuk backup" required>
                        </div>
                        <button type="submit" :disabled="isSubmitting"
                                class="inline-flex items-center justify-center px-4 py-2.5 bg-purple-600 dark:bg-purple-700 hover:bg-purple-700 dark:hover:bg-purple-600 text-white font-bold text-sm rounded-xl shadow-sm hover:shadow transition disabled:opacity-50 disabled:cursor-not-allowed">
                            <template x-if="!isSubmitting">
                                <span class="flex items-center"><i class="fas fa-cloud-arrow-down mr-2"></i> Buat Backup Sekarang</span>
                            </template>
                            <template x-if="isSubmitting">
                                <span class="flex items-center"><i class="fas fa-spinner fa-spin mr-2"></i> Memproses Backup...</span>
                            </template>
                        </button>
                    </form>
                </div>
                @error('password')
                    <div class="px-6 pt-3">
                        <p class="text-xs text-red-600 dark:text-red-400 font-semibold">{{ $message }}</p>
                    </div>
                @enderror

                {{-- Tabel Riwayat Backup --}}
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                            <i class="fas fa-history text-purple-500"></i> Riwayat Cadangan Database
                        </h4>
                        <span class="text-xs text-gray-400 dark:text-gray-500">Menampilkan hingga 10 riwayat terakhir</span>
                    </div>

                    @if($backups->isNotEmpty())
                        <div class="overflow-x-auto rounded-xl border border-gray-100 dark:border-gray-700">
                            <table class="w-full text-left text-sm divide-y divide-gray-100 dark:divide-gray-700">
                                <thead class="bg-gray-50/80 dark:bg-gray-900/60 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    <tr>
                                        <th class="px-4 py-3">Nama Berkas</th>
                                        <th class="px-4 py-3">Waktu Dibuat</th>
                                        <th class="px-4 py-3 text-center">Ukuran</th>
                                        <th class="px-4 py-3 text-center">Status</th>
                                        <th class="px-4 py-3">Masa Berlaku</th>
                                        <th class="px-4 py-3 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700 text-gray-700 dark:text-gray-300">
                                    @foreach($backups as $backup)
                                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/30 transition">
                                            <td class="px-4 py-3.5 font-mono text-xs font-semibold text-gray-900 dark:text-gray-100">
                                                <div class="flex items-center gap-2">
                                                    <i class="fas fa-file-code text-purple-500"></i>
                                                    <span>{{ $backup->filename }}</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3.5 text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                                {{ $backup->created_at->translatedFormat('d M Y, H:i') }}
                                                @if($backup->requester)
                                                    <span class="block text-[10px] text-gray-400">oleh: {{ $backup->requester->name }}</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3.5 text-xs font-mono text-center whitespace-nowrap">
                                                {{ $backup->file_size ?? '-' }}
                                            </td>
                                            <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                                @if($backup->status === 'completed')
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                                        <i class="fas fa-check-circle text-[10px]"></i> Selesai
                                                    </span>
                                                @elseif($backup->status === 'failed')
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800" title="{{ $backup->error_message }}">
                                                        <i class="fas fa-times-circle text-[10px]"></i> Gagal
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-800">
                                                        <i class="fas fa-spinner fa-spin text-[10px]"></i> Memproses
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3.5 text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                                @if($backup->expires_at)
                                                    @if($backup->isExpired())
                                                        <span class="text-red-500 font-semibold">Kedaluwarsa</span>
                                                    @else
                                                        <span>s.d {{ $backup->expires_at->translatedFormat('d M Y, H:i') }}</span>
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                                <div class="inline-flex items-center gap-2">
                                                    @if($backup->download_url)
                                                        <a href="{{ $backup->download_url }}"
                                                           class="inline-flex items-center px-3 py-1.5 bg-purple-50 dark:bg-purple-950/50 hover:bg-purple-100 dark:hover:bg-purple-900/60 text-purple-700 dark:text-purple-300 font-bold text-xs rounded-lg border border-purple-200 dark:border-purple-800 transition shadow-2xs">
                                                            <i class="fas fa-download mr-1.5 text-[11px]"></i> Unduh
                                                        </a>
                                                    @endif
                                                    <form action="{{ route('admin.settings.backups.destroy', $backup) }}" method="POST"
                                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus berkas backup ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="inline-flex items-center px-2.5 py-1.5 bg-red-50 dark:bg-red-950/30 hover:bg-red-100 dark:hover:bg-red-900/50 text-red-600 dark:text-red-400 font-bold text-xs rounded-lg border border-red-200 dark:border-red-800 transition"
                                                                title="Hapus berkas backup">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8 bg-gray-50/50 dark:bg-gray-900/30 rounded-xl border border-dashed border-gray-200 dark:border-gray-700">
                            <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center mx-auto mb-2 text-gray-400 dark:text-gray-500">
                                <i class="fas fa-database text-lg"></i>
                            </div>
                            <p class="text-sm font-bold text-gray-600 dark:text-gray-300">Belum ada riwayat backup database</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Gunakan form di atas untuk membuat cadangan database sistem pertama kali.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
