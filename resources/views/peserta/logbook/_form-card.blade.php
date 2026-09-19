<div class="xl:col-span-4">
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden sticky top-8">
        @if($activeApp->status === 'selesai' || $activeApp->status_value === 'selesai')
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-950/40 dark:to-orange-950/40">
                <h3 class="font-extrabold text-gray-800 dark:text-gray-100 flex items-center gap-2 text-base">
                    <i class="fas fa-lock text-amber-600 dark:text-amber-400"></i> Logbook Dikunci
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-medium">Masa magang Anda telah selesai.</p>
            </div>
            
            <div class="p-6 space-y-5">
                <div class="p-4 bg-amber-50 dark:bg-amber-950/50 border border-amber-200 dark:border-amber-800/60 rounded-2xl text-xs text-amber-800 dark:text-amber-200 flex items-start gap-3">
                    <i class="fas fa-info-circle text-amber-600 dark:text-amber-400 text-lg shrink-0 mt-0.5"></i>
                    <div>
                        <p class="font-bold">Masa Magang Telah Selesai</p>
                        <p class="mt-1 text-amber-700 dark:text-amber-300 leading-relaxed">Anda tidak dapat lagi menulis jurnal baru atau menyimpan laporan karena status magang Anda sudah selesai.</p>
                    </div>
                </div>

                <a href="{{ route('peserta.logbook.print', $activeApp->id) }}" target="_blank" 
                    class="w-full bg-teal-600 hover:bg-teal-700 text-white py-3.5 rounded-2xl font-bold shadow-md transition flex items-center justify-center gap-2 text-xs uppercase tracking-wider">
                    <i class="fas fa-file-pdf text-sm"></i> Download Rekap Logbook (PDF)
                </a>
            </div>
        @else
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-teal-50 to-emerald-50 dark:from-teal-950/40 dark:to-emerald-950/40">
                <h3 class="font-extrabold text-gray-800 dark:text-gray-100 flex items-center gap-2 text-base">
                    <i class="fas fa-pen-nib text-teal-600 dark:text-teal-400"></i> Tulis Jurnal Baru
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-medium">Catat aktivitas magang Anda hari ini.</p>
            </div>
            
            <div class="p-6">
                <form action="{{ route('peserta.logbook.store') }}" method="POST" enctype="multipart/form-data" id="logbookForm">
                    @csrf
                    <input type="hidden" name="latitude" id="lat">
                    <input type="hidden" name="longitude" id="lng">

                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Deskripsi Kegiatan <span class="text-rose-500">*</span></label>
                        <textarea name="kegiatan" rows="5" class="w-full rounded-2xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 text-xs sm:text-sm shadow-xs transition hover:shadow-md resize-none font-medium" placeholder="Apa saja kegiatan yang Anda kerjakan hari ini?" required oninvalid="this.setCustomValidity('Harap isi bidang ini.')" oninput="this.setCustomValidity('')"></textarea>
                        <x-input-error :messages="$errors->get('kegiatan')" class="mt-2" />
                    </div>

                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Dokumentasi (Foto)</label>
                        <div class="relative w-full h-40 rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 hover:bg-teal-50 dark:hover:bg-teal-950/20 hover:border-teal-400 dark:hover:border-teal-500 transition-all group overflow-hidden flex flex-col items-center justify-center cursor-pointer">
                            <!-- Image Preview Container -->
                            <div id="image-preview" class="absolute inset-0 z-10 hidden bg-black">
                                <img id="preview-img" src="" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition-opacity" alt="Pratinjau Foto" />
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="text-white text-xs font-bold bg-black/60 px-3 py-1 rounded-full"><i class="fas fa-camera mr-1"></i> Ganti Foto</span>
                                </div>
                            </div>
                            
                            <!-- Default Empty State -->
                            <div id="empty-state" class="flex flex-col items-center justify-center z-0">
                                <div class="w-12 h-12 bg-white dark:bg-gray-800 rounded-2xl shadow-xs flex items-center justify-center mb-2 border border-gray-200 dark:border-gray-700 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-cloud-upload-alt text-teal-600 dark:text-teal-400 text-xl"></i>
                                </div>
                                <p class="text-xs font-bold text-gray-600 dark:text-gray-300">Klik untuk upload foto</p>
                                <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1 font-semibold">PNG, JPG, JPEG maks 5MB</p>
                            </div>
                            
                            <input id="foto" name="foto" type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" accept="image/*" onchange="previewImage(this)" />
                        </div>
                        <button type="button" id="remove-btn" onclick="removeImage()" class="hidden mt-2 text-xs text-rose-600 dark:text-rose-400 hover:underline font-bold items-center gap-1">
                            <i class="fas fa-trash"></i> Hapus Foto
                        </button>
                        <x-input-error :messages="$errors->get('foto')" class="mt-2" />
                    </div>

                    <div class="mb-6 bg-gray-50 dark:bg-gray-900 p-4 rounded-2xl border border-gray-200 dark:border-gray-700 relative overflow-hidden">
                        <div class="absolute top-0 right-0 -mt-2 -mr-2 text-gray-200 dark:text-gray-800 opacity-40">
                            <i class="fas fa-map-marked-alt text-6xl"></i>
                        </div>
                        <p class="text-[10px] font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2 relative z-10">Verifikasi Lokasi GPS</p>
                        <div id="loc-status" class="flex items-center gap-2 text-xs font-bold text-amber-600 dark:text-amber-400 relative z-10">
                            <i class="fas fa-circle-notch fa-spin"></i> Mendapatkan koordinat...
                        </div>
                        <div id="coords-display" class="text-[10px] text-gray-500 dark:text-gray-400 mt-1 hidden font-mono relative z-10 bg-white dark:bg-gray-800 inline-block px-2.5 py-1 rounded-md border border-gray-200 dark:border-gray-700">
                            Lat: <span id="show-lat"></span>, Lng: <span id="show-lng"></span>
                        </div>
                        <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                        <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                    </div>

                    <button type="submit" id="btn-submit" disabled 
                        class="w-full bg-teal-600 hover:bg-teal-700 text-white py-3.5 rounded-2xl font-bold shadow-md transition active:scale-95 disabled:opacity-50 disabled:transform-none disabled:cursor-not-allowed flex items-center justify-center gap-2 text-xs uppercase tracking-wider">
                        <i class="fas fa-paper-plane"></i> Simpan Laporan
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
