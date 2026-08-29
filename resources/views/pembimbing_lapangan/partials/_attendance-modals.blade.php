@props(['row'])

@if($row->proof_file)
<x-modal name="modal-bukti-{{ $row->id }}" focusable>
    <div class="p-6 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center mb-4 border-b border-gray-100 dark:border-gray-700 pb-3">
            <h2 class="text-base font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                <i class="fas fa-file-medical text-teal-600 dark:text-teal-400"></i> Bukti Pengajuan {{ ucfirst($row->status) }}
            </h2>
            <button x-on:click="$dispatch('close')" aria-label="Tutup" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="flex justify-center bg-gray-50 dark:bg-gray-900 rounded-2xl p-3 mb-4 border border-gray-200 dark:border-gray-700 w-full">
            @if(Str::endsWith(strtolower($row->proof_file), '.pdf'))
                <iframe src="{{ route('storage.access', ['type' => 'attendance', 'filename' => basename($row->proof_file)]) }}" class="w-full h-[50vh] rounded-xl border-0" title="Pratinjau Bukti PDF"></iframe>
            @else
                <img src="{{ route('storage.access', ['type' => 'attendance', 'filename' => basename($row->proof_file)]) }}" class="max-h-[60vh] rounded-xl shadow-xs hover:scale-105 transition duration-300" alt="Bukti Pengajuan Absensi">
            @endif
        </div>
        
        <div class="bg-teal-50/60 dark:bg-teal-950/40 p-4 rounded-2xl border border-teal-200 dark:border-teal-800/60">
            <p class="text-[10px] text-teal-700 dark:text-teal-300 font-bold uppercase mb-1">Keterangan Mahasiswa</p>
            <p class="text-teal-900 dark:text-teal-200 text-xs sm:text-sm italic font-medium">"{{ $row->description }}"</p>
        </div>
        
        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">Tutup</x-secondary-button>
        </div>
    </div>
</x-modal>
@endif

@if($row->status != 'hadir' && $row->validation_status == 'pending')
<x-modal name="modal-tolak-{{ $row->id }}" focusable>
    <div class="p-6 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
        <h2 class="text-base font-bold text-rose-600 dark:text-rose-400 mb-4 border-b border-gray-100 dark:border-gray-700 pb-3 flex items-center gap-2">
            <i class="fas fa-user-times"></i> Tolak Pengajuan {{ ucfirst($row->status) }}
        </h2>
        <form action="{{ route('pembimbing_lapangan.attendance.validate', $row->id) }}" method="POST" onsubmit="event.submitter && (event.submitter.disabled = true)">
            @csrf
            <input type="hidden" name="status_validasi" value="rejected">
            
            <div class="mb-4">
                <label for="pembimbing_lapangan_note_{{ $row->id }}" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Alasan Penolakan</label>
                <textarea id="pembimbing_lapangan_note_{{ $row->id }}" name="pembimbing_lapangan_note" rows="3" class="w-full border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 shadow-xs focus:border-rose-500 focus:ring-rose-500 text-xs font-bold" required placeholder="Contoh: Bukti surat dokter tidak jelas atau tidak menyantumkan tanggal..."></textarea>
            </div>
            
            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-xs transition">
                    Konfirmasi Tolak
                </button>
            </div>
        </form>
    </div>
</x-modal>
@endif
