<!-- Modal Edit / Revisi Jurnal -->
<div x-show="showEditModal" class="fixed inset-0 z-[100] overflow-y-auto text-left" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="showEditModal" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showEditModal = false" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div x-show="showEditModal" x-transition class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-gray-200 dark:border-gray-700">
            <form :action="'{{ route('peserta.logbook.update', ':id') }}'.replace(':id', editLogId)" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="log_id" x-model="editLogId">
                <input type="hidden" name="status_validasi" x-model="editStatusValidasi">
                <input type="hidden" name="tanggal" x-model="editTanggal">
                
                <div class="bg-white dark:bg-gray-800 px-6 pt-6 pb-6">
                    <div class="flex items-center gap-4 mb-6 pb-5 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-2xl"
                             :class="editStatusValidasi === 'revisi' ? 'bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800/60' : 'bg-teal-100 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 border border-teal-200 dark:border-teal-800/60'">
                            <i class="fas text-lg" :class="editStatusValidasi === 'revisi' ? 'fa-edit' : 'fa-pen-nib'"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 dark:text-gray-100" id="modal-title" x-text="editStatusValidasi === 'revisi' ? 'Revisi Jurnal Harian' : 'Edit Jurnal Harian'"></h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-bold mt-0.5" x-text="editTanggal"></p>
                        </div>
                    </div>
                    <div class="space-y-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Deskripsi Kegiatan <span class="text-rose-500">*</span></label>
                            <textarea name="kegiatan" rows="4" class="w-full rounded-2xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 text-xs sm:text-sm font-medium shadow-xs resize-none" required oninvalid="this.setCustomValidity('Harap isi bidang ini.')" oninput="this.setCustomValidity('')" x-model="editKegiatan"></textarea>
                            <x-ui.input-error :messages="$errors->get('kegiatan')" class="mt-2" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Ganti Foto (Opsional)</label>
                            <input type="file" name="foto" accept="image/*" class="w-full text-xs text-gray-500 dark:text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-teal-50 dark:file:bg-teal-950/60 file:text-teal-700 dark:file:text-teal-300 hover:file:bg-teal-100 border border-gray-300 dark:border-gray-700 rounded-xl p-1 bg-white dark:bg-gray-900 cursor-pointer">
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-2 font-medium">Abaikan jika tidak ada perubahan bukti dokumentasi.</p>
                            <x-ui.input-error :messages="$errors->get('foto')" class="mt-2" />
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900 px-6 py-4 flex flex-row-reverse gap-3 border-t border-gray-100 dark:border-gray-700">
                    <button type="submit" class="w-full inline-flex justify-center rounded-xl shadow-xs px-6 py-2.5 bg-teal-600 text-xs font-bold uppercase tracking-wider text-white hover:bg-teal-700 transition sm:w-auto" x-text="editStatusValidasi === 'revisi' ? 'Simpan Revisi' : 'Simpan Perubahan'">
                    </button>
                    <button type="button" @click="showEditModal = false" class="w-full inline-flex justify-center rounded-xl shadow-xs px-6 py-2.5 bg-white dark:bg-gray-800 text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900 border border-gray-300 dark:border-gray-700 transition sm:w-auto">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
