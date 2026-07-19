<x-app-layout>
    @push('head')
        <meta name="turbo-cache-control" content="no-cache">
    @endpush

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-3xl text-transparent bg-clip-text bg-gradient-to-r from-teal-700 to-emerald-600 leading-tight flex items-center gap-3 drop-shadow-sm">
                <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-900/40 flex items-center justify-center border border-teal-200 dark:border-teal-800/50">
                    <i class="fas fa-edit text-teal-600 text-lg"></i>
                </div>
                {{ __('Edit Lowongan Magang') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-gray-900 min-h-screen relative overflow-hidden font-sans">
        <!-- Background Ornaments -->
        <div class="absolute top-0 left-0 w-full h-96 bg-gradient-to-b from-teal-50 to-slate-50 z-0"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-emerald-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-24 -left-24 w-72 h-72 bg-teal-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>

        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 relative z-10">
            
            <div class="mb-8">
                <a href="{{ route('dinas.lowongan.index') }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-teal-700 transition-all group bg-white dark:bg-gray-800/60 backdrop-blur-md px-4 py-2 rounded-full border border-slate-200 shadow-sm hover:shadow-md">
                    <div class="w-7 h-7 rounded-full bg-white dark:bg-gray-800 border border-slate-200 flex items-center justify-center mr-3 group-hover:border-teal-400 group-hover:bg-teal-50 transition-colors">
                        <i class="fas fa-arrow-left text-xs text-slate-400 group-hover:text-teal-600"></i>
                    </div>
                    Kembali ke Daftar Lowongan
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800/80 backdrop-blur-xl rounded-3xl shadow-xl border border-slate-100/60 overflow-hidden transform transition-all">
                
                <div class="relative overflow-hidden bg-gradient-to-r from-teal-600 to-emerald-500 p-8 text-white">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white dark:bg-gray-800 rounded-full opacity-10 blur-2xl"></div>
                    <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-24 h-24 bg-teal-900 rounded-full opacity-10 blur-xl"></div>
                    <div class="relative z-10 flex items-center gap-5">
                        <div class="w-16 h-16 rounded-2xl bg-white dark:bg-gray-800/20 backdrop-blur-md flex items-center justify-center text-white text-2xl border border-white/30 shadow-inner">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black tracking-tight">Formulir Perubahan Data</h3>
                            <p class="text-teal-50 text-sm mt-1 font-medium opacity-90">Perbarui informasi posisi magang yang tersedia di instansi Anda.</p>
                        </div>
                    </div>
                </div>

                <div class="p-8 md:p-10">
                    <form action="{{ route('dinas.lowongan.update', $loker->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-8">
                            
                            <div class="relative group">
                                <label class="block text-sm font-bold text-slate-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-briefcase text-teal-600"></i> Nama Posisi / Jabatan Magang
                                </label>
                                <div class="relative transition-all duration-300 group-focus-within:drop-shadow-md">
                                    <input type="text" name="judul_posisi" value="{{ old('judul_posisi', $loker->judul_posisi) }}" 
                                        class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white dark:focus:bg-gray-800 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all font-bold text-slate-800 text-lg">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                
                                <div class="relative group">
                                    <label class="block text-sm font-bold text-slate-700 mb-2 flex items-center gap-2">
                                        <i class="fas fa-graduation-cap text-teal-500"></i> Kualifikasi Jurusan
                                    </label>
                                    <div class="relative transition-all duration-300 group-focus-within:drop-shadow-md">
                                        <input type="text" name="required_major" value="{{ old('required_major', $loker->required_major) }}" 
                                            class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white dark:focus:bg-gray-800 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all font-medium text-slate-800"
                                            placeholder="Contoh: Teknik Informatika, DKV">
                                    </div>
                                    <p class="text-xs text-slate-500 mt-2 ml-1 font-medium"><i class="fas fa-info-circle text-slate-400 mr-1"></i> Pisahkan dengan koma jika lebih dari satu.</p>
                                </div>

                                <div class="relative group">
                                    <label class="block text-sm font-bold text-slate-700 mb-2 flex items-center gap-2">
                                        <i class="fas fa-users text-emerald-500"></i> Kuota Penerimaan
                                    </label>
                                    <div class="relative transition-all duration-300 group-focus-within:drop-shadow-md">
                                        <input type="number" name="kuota" value="{{ old('kuota', $loker->kuota) }}" min="0" 
                                            class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white dark:focus:bg-gray-800 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-medium text-slate-800">
                                    </div>
                                </div>

                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                
                                <div class="relative group">
                                    <label class="block text-sm font-bold text-slate-700 mb-2 flex items-center gap-2">
                                        <i class="fas fa-info-circle text-amber-500"></i> Status Pendaftaran
                                    </label>
                                    <div class="relative transition-all duration-300 group-focus-within:drop-shadow-md">
                                        <select name="status" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white dark:focus:bg-gray-800 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition-all font-bold text-slate-800 cursor-pointer appearance-none">
                                            <option value="buka" {{ $loker->status == 'buka' ? 'selected' : '' }}>🟢 Dibuka (Aktif)</option>
                                            <option value="tutup" {{ $loker->status == 'tutup' ? 'selected' : '' }}>🔴 Ditutup (Arsip)</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                            <i class="fas fa-chevron-down text-xs"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="relative group">
                                    <label class="block text-sm font-bold text-slate-700 mb-2 flex items-center gap-2">
                                        <i class="far fa-calendar-alt text-indigo-500"></i> Batas Akhir Pendaftaran
                                    </label>
                                    <div class="relative transition-all duration-300 group-focus-within:drop-shadow-md">
                                        <input type="date" name="batas_daftar" value="{{ old('batas_daftar', \Carbon\Carbon::parse($loker->batas_daftar)->format('Y-m-d')) }}" 
                                            class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white dark:focus:bg-gray-800 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-800">
                                    </div>
                                </div>

                            </div>

                            <div class="relative group">
                                <label class="block text-sm font-bold text-slate-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-file-alt text-teal-600"></i> Deskripsi & Syarat Detail
                                </label>
                                <div class="rounded-2xl overflow-hidden border border-slate-200 shadow-sm focus-within:ring-4 focus-within:ring-teal-500/10 focus-within:border-teal-500 transition-all duration-300 bg-white dark:bg-gray-800">
                                    <textarea id="editor" name="deskripsi" class="w-full border-0 focus:ring-0">{{ old('deskripsi', $loker->deskripsi) }}</textarea>
                                </div>
                            </div>

                        </div>

                        <div class="mt-10 pt-6 border-t border-slate-100 flex items-center justify-end gap-4">
                            <a href="{{ route('dinas.lowongan.index') }}" class="px-7 py-3 rounded-2xl border-2 border-slate-200 text-slate-600 font-bold hover:bg-slate-50 hover:text-slate-800 transition-all focus:ring-4 focus:ring-slate-100">
                                Batal
                            </a>
                            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-teal-600 to-emerald-500 text-white rounded-2xl font-bold hover:from-teal-700 hover:to-emerald-600 shadow-lg shadow-teal-500/30 transition-all transform hover:-translate-y-0.5 active:translate-y-0 active:scale-95 flex items-center gap-2">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- Inject Keyframes for Blob Animations -->
    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
    </style>

    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/super-build/ckeditor.js"></script>
    <script>
        document.addEventListener("turbo:load", function() {
            const editorElement = document.querySelector('#editor');
            if (editorElement) {
                CKEDITOR.ClassicEditor.create(editorElement, {
                    toolbar: {
                        items: [
                            'undo', 'redo', '|', 'heading', '|',
                            'bold', 'italic', 'underline', 'bulletedList', 'numberedList', '|',
                            'alignment', 'outdent', 'indent', '|', 'link', 'blockQuote'
                        ],
                        shouldNotGroupWhenFull: true
                    },
                    placeholder: 'Jelaskan tanggung jawab dan persyaratan magang secara rinci...',
                    removePlugins: [
                        'CKBox', 'CKFinder', 'EasyImage', 'RealTimeCollaborativeComments', 
                        'RealTimeCollaborativeTrackChanges', 'RealTimeCollaborativeRevisionHistory', 
                        'PresenceList', 'Comments', 'TrackChanges', 'TrackChangesData', 
                        'RevisionHistory', 'Pagination', 'WProofreader', 'MathType', 
                        'Image', 'ImageCaption', 'ImageStyle', 'ImageToolbar', 'ImageUpload', 
                        'Table', 'TableToolbar', 'MediaEmbed'
                    ]
                }).then(editor => {
                    // Set min height for editor content area
                    editor.editing.view.change(writer => {
                        writer.setStyle('min-height', '250px', editor.editing.view.document.getRoot());
                        writer.setStyle('border', 'none', editor.editing.view.document.getRoot());
                        writer.setStyle('background-color', '#ffffff', editor.editing.view.document.getRoot());
                        writer.setStyle('border-radius', '0 0 1rem 1rem', editor.editing.view.document.getRoot());
                    });
                }).catch(error => console.error(error));
            }
        });
    </script>
</x-app-layout>