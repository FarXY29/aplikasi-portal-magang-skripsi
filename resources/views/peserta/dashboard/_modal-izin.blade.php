{{-- Form Pengajuan Izin / Sakit Modal --}}
<x-ui.modal name="modal-izin" focusable>
    <div class="p-4 sm:p-6 bg-white dark:bg-gray-800 text-slate-900 dark:text-gray-100">
        <h2 class="text-base font-bold text-slate-900 dark:text-gray-100 mb-4 pb-3 border-b border-slate-100 dark:border-gray-700 flex items-center gap-2">
            <i class="fas fa-file-alt text-amber-600"></i> Form Pengajuan Izin / Sakit
        </h2>
        <form action="{{ route('peserta.absen.izin') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-gray-300 uppercase mb-2">Jenis Keterangan</label>
                    <select name="status" class="w-full min-h-[44px] border-slate-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-800 dark:text-gray-100 rounded-xl shadow-2xs focus:border-teal-600 focus:ring-teal-600 text-xs font-bold [color-scheme:dark]">
                        <option value="sakit">Sakit (Upload Surat Dokter)</option>
                        <option value="izin">Izin (Keperluan Mendesak)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-gray-300 uppercase mb-2">Alasan Detail</label>
                    <textarea name="description" rows="3" class="w-full border-slate-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-800 dark:text-gray-100 rounded-xl shadow-2xs focus:border-teal-600 focus:ring-teal-600 text-xs font-medium" required placeholder="Jelaskan alasan pengajuan Anda..."></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-gray-300 uppercase mb-2">Bukti Foto / Surat (PNG / JPG / PDF)</label>
                    <input type="file" name="proof_file" class="w-full text-xs text-slate-500 dark:text-gray-400 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-teal-50 dark:file:bg-teal-950/60 file:text-teal-800 dark:file:text-teal-300 hover:file:bg-teal-100/80 border border-slate-300 dark:border-gray-700 rounded-xl p-1 bg-white dark:bg-gray-900" required>
                </div>
            </div>

            <div class="mt-6 flex flex-col sm:flex-row justify-end gap-3">
                <button type="button" x-on:click.prevent="$dispatch('close')" class="min-h-[44px] px-5 py-2.5 bg-white dark:bg-gray-800 border border-slate-300 dark:border-gray-700 text-slate-700 dark:text-gray-300 hover:bg-slate-50 dark:hover:bg-gray-900 rounded-xl font-bold text-xs transition uppercase tracking-wider">Batal</button>
                <button type="submit" class="min-h-[44px] px-5 py-2.5 bg-teal-700 hover:bg-teal-800 text-white rounded-xl font-bold text-xs shadow-2xs transition uppercase tracking-wider">Kirim Pengajuan</button>
            </div>
        </form>
    </div>
</x-ui.modal>
