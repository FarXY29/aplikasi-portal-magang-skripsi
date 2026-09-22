{{-- Card Kop Dokumen Laporan --}}
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
