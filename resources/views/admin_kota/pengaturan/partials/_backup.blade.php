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
