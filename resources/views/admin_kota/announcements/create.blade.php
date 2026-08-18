<x-app-layout>
    @push('head')
        <meta name="turbo-cache-control" content="no-cache">
    @endpush
    @push('styles')
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    @endpush

    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-teal-600 dark:bg-teal-500 text-white flex items-center justify-center shadow-sm">
                <i class="fas fa-plus text-sm"></i>
            </div>
            <div>
                <h2 class="font-black text-xl text-gray-900 dark:text-gray-100 leading-tight">Buat Pengumuman Baru</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium hidden sm:block">Terbitkan surat edaran, instruksi dinas, atau informasi penting untuk seluruh pengguna portal</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6 font-[Inter]">
        <!-- Navigation Back -->
        <div class="flex justify-between items-center">
            <a href="{{ route('admin.announcements.index') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                    <i class="fas fa-arrow-left text-xs text-gray-400 group-hover:text-teal-600"></i>
                </div>
                Kembali ke Daftar Pengumuman
            </a>
        </div>

        @if($errors->any())
            <x-ui.alert type="error" class="mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-ui.alert>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm p-6 sm:p-8">
            <form action="{{ route('admin.announcements.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Judul Pengumuman -->
                <div>
                    <label class="block text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
                        Judul Pengumuman / Edaran <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="Contoh: Jadwal Pembekalan & Pengisian Penilaian Akhir Magang Semester Genap" required
                        class="w-full px-4 py-3 text-sm font-bold rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:bg-white focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Tipe Pengumuman -->
                    <div>
                        <label class="block text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
                            Tipe / Kategori <span class="text-rose-500">*</span>
                        </label>
                        <select name="type" required class="w-full px-4 py-3 text-sm font-bold rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:bg-white focus:ring-2 focus:ring-teal-500">
                            <option value="info" {{ old('type') == 'info' ? 'selected' : '' }}>ℹ️ Info Umum (Biasa)</option>
                            <option value="urgent" {{ old('type') == 'urgent' ? 'selected' : '' }}>🚨 Mendesak / Urgent (Merah)</option>
                            <option value="warning" {{ old('type') == 'warning' ? 'selected' : '' }}>⚠️ Peringatan / Warning (Kuning)</option>
                            <option value="event" {{ old('type') == 'event' ? 'selected' : '' }}>📅 Agenda Kegiatan / Event (Hijau)</option>
                        </select>
                    </div>

                    <!-- Target Audiens -->
                    <div>
                        <label class="block text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
                            Target Audiens <span class="text-rose-500">*</span>
                        </label>
                        <select name="target_audience" required class="w-full px-4 py-3 text-sm font-bold rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:bg-white focus:ring-2 focus:ring-teal-500">
                            <option value="all" {{ old('target_audience') == 'all' ? 'selected' : '' }}>🌐 Seluruh Pengguna Portal</option>
                            <option value="peserta" {{ old('target_audience') == 'peserta' ? 'selected' : '' }}>🎓 Khusus Peserta Magang</option>
                            <option value="admin_instansi" {{ old('target_audience') == 'admin_instansi' ? 'selected' : '' }}>🏢 Khusus Admin Instansi / OPD</option>
                            <option value="pembimbing" {{ old('target_audience') == 'pembimbing' ? 'selected' : '' }}>👨‍🏫 Khusus Dosen & Pembimbing Lapangan</option>
                        </select>
                    </div>
                </div>

                <!-- Isi Pengumuman -->
                <div>
                    <label class="block text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
                        Isi Lengkap Pengumuman <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="content" rows="7" placeholder="Tuliskan detail pengumuman, instruksi, dan informasi lengkap di sini..." required
                        class="w-full px-4 py-3 text-sm rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:bg-white focus:ring-2 focus:ring-teal-500 leading-relaxed">{{ old('content') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Banner / Lampiran Gambar -->
                    <div>
                        <label class="block text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
                            Banner / Lampiran Gambar (Opsional)
                        </label>
                        <input type="file" name="banner_image" accept="image/jpeg,image/png,image/webp"
                            class="w-full text-xs font-bold text-gray-500 dark:text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 dark:file:bg-teal-900/30 dark:file:text-teal-400">
                        <p class="text-[11px] text-gray-400 mt-1">Maksimal 2 MB (Format JPG, PNG, WEBP).</p>
                    </div>

                    <!-- Batas Waktu Tayang (Expires At) -->
                    <div>
                        <label class="block text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
                            Batas Waktu Tayang (Opsional)
                        </label>
                        <input type="date" name="expires_at" value="{{ old('expires_at') }}"
                            class="w-full px-4 py-2.5 text-sm font-bold rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:bg-white focus:ring-2 focus:ring-teal-500">
                        <p class="text-[11px] text-gray-400 mt-1">Kosongkan jika ingin ditampilkan seterusnya tanpa batas waktu.</p>
                    </div>
                </div>

                <!-- Opsi Publikasi & Broadcast -->
                <div class="bg-gray-50 dark:bg-gray-900/50 p-5 rounded-2xl border border-gray-100 dark:border-gray-700/60 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-sm font-bold text-gray-900 dark:text-gray-100">Status Penayangan Langsung</span>
                            <p class="text-xs text-gray-400">Aktifkan agar pengumuman langsung muncul di dashboard target audiens.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_published" value="1" class="sr-only peer" {{ old('is_published', true) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-600"></div>
                        </label>
                    </div>

                    <div class="pt-3 border-t border-gray-200/60 dark:border-gray-700/60 flex items-center justify-between">
                        <div>
                            <span class="text-sm font-bold text-gray-900 dark:text-gray-100 flex items-center gap-1.5">
                                <i class="fas fa-paper-plane text-teal-600 text-xs"></i> Kirim Broadcast Email Serentak
                            </span>
                            <p class="text-xs text-gray-400">Sistem akan mengirimkan salinan email resmi ke seluruh pengguna target di latar belakang (*queue*).</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="send_email_broadcast" value="1" class="sr-only peer" {{ old('send_email_broadcast') ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-600"></div>
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <a href="{{ route('admin.announcements.index') }}" class="px-5 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl text-xs font-bold hover:bg-gray-200 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-xs font-bold transition shadow-md flex items-center gap-2">
                        <i class="fas fa-check text-xs"></i> Simpan & Terbitkan Pengumuman
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
