{{--
    Shared form fields for both "lamar magang" forms:
      - resources/views/peserta/apply.blade.php           (manual apply + availability check)
      - resources/views/peserta/apply_automatic.blade.php (automatic placement, no availability check)

    Props:
      - $withWaitingList (bool) : render the #is_waiting_list hidden input + #availability-result container.
                                  The apply-form.js reads these by id; keep them in sync.

    Rendered INSIDE the <form>. All ids/classes are consumed by resources/js/peserta/apply-form.js.
--}}
@props(['withWaitingList' => false])

@if($withWaitingList)
    <input type="hidden" name="is_waiting_list" id="is_waiting_list" value="0">
@endif

{{-- Upload Surat Pengantar --}}
<div class="mb-8">
    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-3">Upload Surat Pengantar <span class="text-rose-500">*</span></label>
    <label for="surat" class="upload-zone flex-col group border-2 border-dashed border-gray-300 dark:border-gray-700 hover:border-teal-500 dark:hover:border-teal-400 bg-gray-50/50 dark:bg-gray-900/50 rounded-3xl p-6 transition cursor-pointer flex items-center justify-center">
        <div id="upload-empty" class="flex flex-col items-center justify-center py-4">
            <div class="w-14 h-14 bg-white dark:bg-gray-800 rounded-2xl shadow-xs flex items-center justify-center mb-3 border border-gray-200 dark:border-gray-700">
                <i class="fas fa-file-pdf text-2xl text-teal-600 dark:text-teal-400 upload-icon"></i>
            </div>
            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 text-center font-medium"><span class="font-bold text-teal-600 dark:text-teal-400">Klik untuk upload</span> atau drag & drop</p>
            <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-1">Format PDF saja (Maksimal 2MB)</p>
        </div>
        <div id="upload-preview" class="hidden flex-col items-center py-4 gap-2">
            <i class="fas fa-file-pdf text-3xl text-teal-600 dark:text-teal-400"></i>
            <span id="file-name-chip" class="upload-success-chip px-3 py-1 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800/60 rounded-full text-xs font-bold text-emerald-700 dark:text-emerald-300 flex items-center gap-1.5"><i class="fas fa-check"></i> <span id="file-name-text"></span></span>
            <span class="text-xs text-gray-400 dark:text-gray-500">Klik untuk mengganti file</span>
        </div>
        <input id="surat" name="surat" type="file" class="hidden" accept=".pdf" required />
    </label>
    @error('surat') <p class="text-rose-500 text-xs mt-2 font-bold flex items-center gap-1"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p> @enderror
</div>

{{-- Rencana Periode Magang --}}
<div class="mb-6">
    <div class="flex items-center justify-between mb-3">
        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
            Rencana Periode Magang
        </label>
        <span class="text-[11px] font-semibold text-gray-400 dark:text-gray-500 hidden sm:inline">Pilih durasi cepat atau atur tanggal manual</span>
    </div>

    {{-- Quick Preset Duration Buttons --}}
    <div class="mb-4">
        <label class="block text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2 flex items-center gap-1.5">
            <i class="fas fa-bolt text-amber-500"></i> Pilihan Cepat Periode
        </label>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5" id="preset-buttons-group">
            @foreach([1 => 'Bulan', 2 => 'Bulan', 3 => 'Bulan', 6 => 'Bulan'] as $months => $unit)
                <button type="button" data-months="{{ $months }}" class="preset-duration-btn group px-3.5 py-2.5 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-teal-500 dark:hover:border-teal-400 text-gray-700 dark:text-gray-300 font-bold text-xs transition flex items-center justify-between shadow-xs cursor-pointer">
                    <span class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center text-[10px] font-black group-hover:scale-105 transition">{{ $months }}B</span>
                        <span>{{ $months }} {{ $unit }}</span>
                    </span>
                    <i class="fas fa-check text-[10px] text-teal-600 dark:text-teal-400 opacity-0 group-[.active]:opacity-100 transition"></i>
                </button>
            @endforeach
        </div>
    </div>

    <div class="date-timeline-wrapper bg-gray-50/50 dark:bg-gray-900/50 p-5 rounded-3xl border border-gray-200 dark:border-gray-700">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2"><i class="fas fa-play-circle text-teal-600 dark:text-teal-400 mr-1"></i>Tanggal Mulai</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai"
                    class="w-full rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 transition shadow-xs text-xs font-bold [color-scheme:dark]"
                    value="{{ old('tanggal_mulai') }}"
                    min="{{ date('Y-m-d') }}" required>
                @error('tanggal_mulai')
                    <p class="text-rose-500 text-xs mt-2 font-bold">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2"><i class="fas fa-stop-circle text-rose-500 mr-1"></i>Tanggal Selesai</label>
                <input type="date" id="tanggal_selesai" name="tanggal_selesai"
                    class="w-full rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 transition shadow-xs text-xs font-bold [color-scheme:dark]"
                    value="{{ old('tanggal_selesai') }}"
                    min="{{ date('Y-m-d') }}" required>
                @error('tanggal_selesai')
                    <p class="text-rose-500 text-xs mt-2 font-bold">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div id="duration-badge-wrap" class="mt-4 hidden flex items-center flex-wrap gap-2">
            <span id="duration-badge" class="px-3.5 py-1.5 bg-teal-50 dark:bg-teal-950/60 border border-teal-200 dark:border-teal-800/60 rounded-full text-xs font-bold text-teal-700 dark:text-teal-300 inline-flex items-center gap-1.5 shadow-xs"><i class="fas fa-clock"></i> <span id="duration-text"></span></span>
            <span id="duration-preset-indicator" class="px-3 py-1 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full text-[11px] font-bold text-gray-600 dark:text-gray-400 hidden"></span>
        </div>
    </div>
</div>

@if($withWaitingList)
    <div id="availability-result" class="hidden mb-8"></div>
@endif
