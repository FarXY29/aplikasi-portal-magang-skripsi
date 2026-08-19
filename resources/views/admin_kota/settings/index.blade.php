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

            <form id="backup-form" action="{{ route('admin.settings.backup') }}" method="POST">
                @csrf
            </form>

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

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl border border-gray-100 dark:border-gray-700">
                        <div class="p-6 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-950/40 flex items-center justify-center text-purple-600 dark:text-purple-400 shadow-inner">
                                    <i class="fas fa-database text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Backup Database</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Mencadangkan seluruh data sistem saat ini (Format .sql).</p>
                                </div>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-2 sm:items-center">
                                <input type="password" name="password" form="backup-form" autocomplete="current-password" placeholder="Konfirmasi password" class="rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-sm" aria-label="Konfirmasi password untuk backup" required>
                                <button type="submit" form="backup-form" class="inline-flex items-center justify-center px-4 py-2 bg-purple-600 dark:bg-purple-700 hover:bg-purple-700 dark:hover:bg-purple-600 text-white font-bold rounded-xl shadow-sm transition">
                                    <i class="fas fa-database mr-2"></i> Antrekan Backup
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-2 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        @if($backups->isNotEmpty())
                            <div class="px-6 py-4 space-y-2 text-sm border-t border-gray-100 dark:border-gray-700">
                                @foreach($backups as $backup)
                                    <div class="flex flex-wrap items-center justify-between gap-3">
                                        <span class="text-gray-600 dark:text-gray-300">{{ $backup->filename }} · {{ ucfirst($backup->status) }}</span>
                                        @if($backup->download_url)
                                            <a href="{{ $backup->download_url }}" class="font-bold text-purple-600 dark:text-purple-400 hover:underline">Unduh (berlaku sampai {{ $backup->expires_at->format('d M H:i') }})</a>
                                        @elseif($backup->status === 'failed')
                                            <span class="text-red-600 dark:text-red-400">{{ $backup->error_message }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-end">
                        <button type="submit" class="inline-flex items-center px-8 py-3 bg-gray-900 dark:bg-teal-600 text-white font-bold rounded-xl shadow-lg hover:bg-gray-800 dark:hover:bg-teal-500 hover:shadow-xl transition transform hover:-translate-y-0.5 active:scale-95">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</x-app-layout>
