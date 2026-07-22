<x-app-layout>
    @push('head')
        <meta name="turbo-cache-control" content="no-cache">
    @endpush

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-3xl text-transparent bg-clip-text bg-gradient-to-r from-teal-700 to-emerald-600 dark:from-teal-400 dark:to-emerald-300 leading-tight flex items-center gap-3 drop-shadow-sm">
                <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-900/40 flex items-center justify-center border border-teal-200 dark:border-teal-800/50">
                    <i class="fas fa-edit text-teal-600 dark:text-teal-400 text-lg"></i>
                </div>
                {{ __('Edit Lowongan Magang') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-gray-900 min-h-screen relative overflow-hidden font-sans">
        <!-- Background Ornaments -->
        <div class="absolute top-0 left-0 w-full h-96 bg-gradient-to-b from-teal-50 dark:from-teal-950/20 to-slate-50 dark:to-gray-900 z-0"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-emerald-200 dark:bg-emerald-900/30 rounded-full mix-blend-multiply dark:mix-blend-soft-light filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-24 -left-24 w-72 h-72 bg-teal-300 dark:bg-teal-900/30 rounded-full mix-blend-multiply dark:mix-blend-soft-light filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>

        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 relative z-10">
            
            <div class="mb-8">
                <a href="{{ route('dinas.lowongan.index') }}" class="inline-flex items-center text-sm font-bold text-slate-500 dark:text-gray-400 hover:text-teal-700 dark:hover:text-teal-400 transition-all group bg-white dark:bg-gray-800/60 backdrop-blur-md px-4 py-2 rounded-full border border-slate-200 dark:border-gray-700 shadow-sm hover:shadow-md">
                    <div class="w-7 h-7 rounded-full bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 flex items-center justify-center mr-3 group-hover:border-teal-400 dark:group-hover:border-teal-600 group-hover:bg-teal-50 dark:group-hover:bg-teal-950/30 transition-colors">
                        <i class="fas fa-arrow-left text-xs text-slate-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Daftar Lowongan
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800/80 backdrop-blur-xl rounded-3xl shadow-xl border border-slate-100/60 dark:border-gray-700/60 overflow-hidden transform transition-all">
                
                <div class="relative overflow-hidden bg-gradient-to-r from-teal-600 to-emerald-500 p-8 text-white">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white dark:bg-gray-800 rounded-full opacity-10 blur-2xl"></div>
                    <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-24 h-24 bg-teal-900 dark:bg-teal-950 rounded-full opacity-10 blur-xl"></div>
                    <div class="relative z-10 flex items-center gap-5">
                        <div class="w-16 h-16 rounded-2xl bg-white/20 dark:bg-gray-800/30 backdrop-blur-md flex items-center justify-center text-white text-2xl border border-white/30 shadow-inner">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black tracking-tight">Formulir Perubahan Data</h3>
                            <p class="text-teal-50 text-sm mt-1 font-medium opacity-90">Perbarui informasi posisi magang yang tersedia di instansi Anda.</p>
                        </div>
                    </div>
                </div>

                <div class="p-8 md:p-10">
                    <form action="{{ route('dinas.lowongan.update', $loker->id) }}" method="POST" id="lowongan-form">
                        @csrf
                        @method('PUT')

                        <div class="space-y-8">
                            
                            <div class="relative group">
                                <label for="judul_posisi" class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                                    <i class="fas fa-briefcase text-teal-600 dark:text-teal-400"></i> Nama Posisi / Jabatan Magang <span class="text-red-500" title="Wajib diisi">*</span>
                                </label>
                                <div class="relative transition-all duration-300 group-focus-within:drop-shadow-md">
                                    <input type="text" name="judul_posisi" id="judul_posisi" value="{{ old('judul_posisi', $loker->judul_posisi) }}" 
                                        class="w-full px-5 py-3.5 bg-slate-50 dark:bg-gray-900 border @error('judul_posisi') border-red-400 focus:border-red-500 focus:ring-red-500/10 @else border-slate-200 dark:border-gray-700 focus:border-teal-500 focus:ring-teal-500/10 @enderror rounded-2xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 transition-all font-bold text-slate-800 dark:text-gray-100 text-lg placeholder-slate-400 dark:placeholder-gray-600" required>
                                    @error('judul_posisi')
                                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-red-500">
                                            <i class="fas fa-exclamation-circle text-lg"></i>
                                        </div>
                                    @enderror
                                </div>
                                @error('judul_posisi') 
                                    <span class="text-red-500 text-xs mt-2 ml-1 font-bold flex items-center gap-1"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</span> 
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                
                                <div class="relative group">
                                    <label for="required_major" class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                                        <i class="fas fa-graduation-cap text-teal-500 dark:text-teal-400"></i> Kualifikasi Jurusan
                                    </label>
                                    <div class="relative transition-all duration-300 group-focus-within:drop-shadow-md">
                                        <input type="text" name="required_major" id="required_major" value="{{ old('required_major', $loker->required_major) }}" 
                                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-gray-900 border @error('required_major') border-red-400 focus:border-red-500 focus:ring-red-500/10 @else border-slate-200 dark:border-gray-700 focus:border-teal-500 focus:ring-teal-500/10 @enderror rounded-2xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 transition-all font-medium text-slate-800 dark:text-gray-100 placeholder-slate-400 dark:placeholder-gray-600"
                                            placeholder="Contoh: Teknik Informatika, DKV">
                                        @error('required_major')
                                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-red-500">
                                                <i class="fas fa-exclamation-circle text-lg"></i>
                                            </div>
                                        @enderror
                                    </div>
                                    <p class="text-xs text-slate-500 dark:text-gray-400 mt-2 ml-1 font-medium"><i class="fas fa-info-circle text-slate-400 dark:text-gray-500 mr-1"></i> Pisahkan dengan koma jika lebih dari satu.</p>
                                    @error('required_major') 
                                        <span class="text-red-500 text-xs mt-2 ml-1 font-bold flex items-center gap-1"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</span> 
                                    @enderror
                                </div>

                                <div class="relative group">
                                    <label for="kuota" class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                                        <i class="fas fa-users text-emerald-500 dark:text-emerald-400"></i> Kuota Penerimaan
                                    </label>
                                    <div class="relative transition-all duration-300 group-focus-within:drop-shadow-md">
                                        <input type="number" name="kuota" id="kuota" value="{{ old('kuota', $loker->kuota) }}" min="0" 
                                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-gray-900 border @error('kuota') border-red-400 focus:border-red-500 focus:ring-red-500/10 @else border-slate-200 dark:border-gray-700 focus:border-emerald-500 focus:ring-emerald-500/10 @enderror rounded-2xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 transition-all font-medium text-slate-800 dark:text-gray-100">
                                        @error('kuota')
                                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-red-500">
                                                <i class="fas fa-exclamation-circle text-lg"></i>
                                            </div>
                                        @enderror
                                    </div>
                                    @error('kuota') 
                                        <span class="text-red-500 text-xs mt-2 ml-1 font-bold flex items-center gap-1"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</span> 
                                    @enderror
                                </div>

                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                
                                <div class="relative group">
                                    <label for="status" class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                                        <i class="fas fa-info-circle text-amber-500 dark:text-amber-400"></i> Status Pendaftaran
                                    </label>
                                    <div class="relative transition-all duration-300 group-focus-within:drop-shadow-md">
                                        <select name="status" id="status" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-gray-900 border @error('status') border-red-400 focus:border-red-500 focus:ring-red-500/10 @else border-slate-200 dark:border-gray-700 focus:border-amber-500 focus:ring-amber-500/10 @enderror rounded-2xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 transition-all font-bold text-slate-800 dark:text-gray-100 cursor-pointer appearance-none">
                                            <option value="buka" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100" {{ $loker->status == 'buka' ? 'selected' : '' }}>🟢 Dibuka (Aktif)</option>
                                            <option value="tutup" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100" {{ $loker->status == 'tutup' ? 'selected' : '' }}>🔴 Ditutup (Arsip)</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500 dark:text-gray-400">
                                            <i class="fas fa-chevron-down text-xs"></i>
                                        </div>
                                    </div>
                                    @error('status') 
                                        <span class="text-red-500 text-xs mt-2 ml-1 font-bold flex items-center gap-1"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</span> 
                                    @enderror
                                </div>

                                <div class="relative group">
                                    <label for="batas_daftar" class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                                        <i class="far fa-calendar-alt text-indigo-500 dark:text-indigo-400"></i> Batas Akhir Pendaftaran
                                    </label>
                                    <div class="relative transition-all duration-300 group-focus-within:drop-shadow-md">
                                        <input type="date" name="batas_daftar" id="batas_daftar" value="{{ old('batas_daftar', \Carbon\Carbon::parse($loker->batas_daftar)->format('Y-m-d')) }}" 
                                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-gray-900 border @error('batas_daftar') border-red-400 focus:border-red-500 focus:ring-red-500/10 @else border-slate-200 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500/10 @enderror rounded-2xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 transition-all font-medium text-slate-800 dark:text-gray-100 [color-scheme:light] dark:[color-scheme:dark]">
                                        @error('batas_daftar')
                                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-red-500">
                                                <i class="fas fa-exclamation-circle text-lg"></i>
                                            </div>
                                        @enderror
                                    </div>
                                    @error('batas_daftar') 
                                        <span class="text-red-500 text-xs mt-2 ml-1 font-bold flex items-center gap-1"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</span> 
                                    @enderror
                                </div>

                            </div>

                            <div class="relative group">
                                <label for="editor" class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                                    <i class="fas fa-file-alt text-teal-600 dark:text-teal-400"></i> Deskripsi & Syarat Detail
                                </label>
                                <div class="rounded-2xl overflow-hidden border @error('deskripsi') border-red-400 focus-within:ring-red-500/10 focus-within:border-red-500 @else border-slate-200 dark:border-gray-700 focus-within:ring-teal-500/10 focus-within:border-teal-500 @enderror shadow-sm focus-within:ring-4 transition-all duration-300 bg-white dark:bg-gray-800">
                                    <textarea id="editor" name="deskripsi" class="w-full border-0 focus:ring-0 bg-white dark:bg-gray-900 text-slate-800 dark:text-gray-100">{{ old('deskripsi', $loker->deskripsi) }}</textarea>
                                </div>
                                @error('deskripsi') 
                                    <span class="text-red-500 text-xs mt-2 ml-1 font-bold flex items-center gap-1"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</span> 
                                @enderror
                            </div>

                        </div>

                        <div class="mt-10 pt-6 border-t border-slate-100 dark:border-gray-700 flex flex-col-reverse sm:flex-row items-center justify-end gap-4">
                            <a href="{{ route('dinas.lowongan.index') }}" class="w-full sm:w-auto text-center px-7 py-3 rounded-2xl border-2 border-slate-200 dark:border-gray-600 text-slate-600 dark:text-gray-300 font-bold hover:bg-slate-50 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-100 transition-all focus:ring-4 focus:ring-slate-100 dark:focus:ring-gray-700">
                                Batal
                            </a>
                            <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-teal-600 to-emerald-500 text-white rounded-2xl font-bold hover:from-teal-700 hover:to-emerald-600 shadow-lg shadow-teal-500/30 transition-all transform hover:-translate-y-0.5 active:translate-y-0 active:scale-95 flex items-center justify-center gap-2">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- Inject Keyframes for Blob Animations & CKEditor Dark Mode CSS -->
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

        /* ── CKEditor 5 Dark Theme via CSS Variables ── */
        .dark {
            /* Base */
            --ck-color-base-background:       #1f2937;  /* gray-800 */
            --ck-color-base-foreground:       #111827;  /* gray-900 */
            --ck-color-base-border:           #374151;  /* gray-700 */
            --ck-color-base-text:             #f3f4f6;  /* gray-100 */
            --ck-color-base-active:           #374151;
            --ck-color-base-active-focus:     #4b5563;
            --ck-color-base-error:            #ef4444;
            --ck-color-base-action:           #14b8a6;  /* teal */
            --ck-color-base-focus:            #14b8a6;
            --ck-color-focus-border:          #14b8a6;
            --ck-color-focus-outer-shadow:    rgba(20,184,166,0.25);

            /* Toolbar */
            --ck-color-toolbar-background:    #1f2937;
            --ck-color-toolbar-border:        #374151;

            /* Buttons */
            --ck-color-button-default-background:       transparent;
            --ck-color-button-default-hover-background: #374151;
            --ck-color-button-default-active-background:#4b5563;
            --ck-color-button-on-background:            #374151;
            --ck-color-button-on-hover-background:      #4b5563;
            --ck-color-button-on-active-background:     #6b7280;
            --ck-color-button-on-disabled-background:   #1f2937;
            --ck-color-button-action-background:        #0d9488;
            --ck-color-button-action-hover-background:  #0f766e;
            --ck-color-button-action-text:              #ffffff;
            --ck-color-button-save:                     #14b8a6;
            --ck-color-button-cancel:                   #ef4444;

            /* Dropdown / Panel */
            --ck-color-dropdown-panel-background: #1f2937;
            --ck-color-dropdown-panel-border:     #374151;
            --ck-color-panel-background:          #1f2937;
            --ck-color-panel-border:              #374151;

            /* List items */
            --ck-color-list-background:              #1f2937;
            --ck-color-list-button-hover-background: #374151;
            --ck-color-list-button-on-background:    #374151;
            --ck-color-list-button-on-background-focus: #4b5563;
            --ck-color-list-button-on-text:          #f3f4f6;

            /* Input fields */
            --ck-color-input-background:          #111827;
            --ck-color-input-border:              #374151;
            --ck-color-input-text:                #f3f4f6;
            --ck-color-input-disabled-background: #1f2937;
            --ck-color-input-disabled-border:     #374151;
            --ck-color-input-disabled-text:       #9ca3af;

            /* Editable area */
            --ck-color-editor-base-text:          #f3f4f6;

            /* Shadow */
            --ck-color-shadow-drop:  rgba(0,0,0,0.5);
            --ck-color-shadow-inner: rgba(0,0,0,0.5);
            --ck-color-shadow-small: rgba(0,0,0,0.5);

            /* Tooltip */
            --ck-color-tooltip-background: #374151;
            --ck-color-tooltip-text:       #f3f4f6;

            /* Table */
            --ck-color-table-focused-cell-background: rgba(20,184,166,0.1);

            /* Separator */
            --ck-color-toolbar-separator: #374151;
        }

        .dark .ck-editor__editable_inline,
        .dark .ck.ck-editor__editable:not(.ck-editor__nested-editable) {
            background: #111827 !important;  /* gray-900 */
            color: #f3f4f6 !important;
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
                    });
                }).catch(error => console.error(error));
            }

            // Double Submit Prevention
            const form = document.querySelector('#lowongan-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
                        submitBtn.innerHTML = `
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Memproses...
                        `;
                    }
                });
            }

            // Auto-focus & Scroll to first error field
            const firstError = document.querySelector('.border-red-400');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                setTimeout(() => {
                    firstError.focus();
                }, 500);
            }
        });
    </script>
</x-app-layout>