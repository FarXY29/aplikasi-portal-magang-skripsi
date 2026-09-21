{{-- Card Pejabat Penandatangan --}}
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
