<x-app-layout>
    @push('head')
        <meta name="turbo-cache-control" content="no-cache">
    @endpush

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-3xl text-transparent bg-clip-text bg-gradient-to-r from-teal-700 to-emerald-600 leading-tight flex items-center gap-3 drop-shadow-sm">
                <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-900/40 flex items-center justify-center border border-teal-200 dark:border-teal-800/50">
                    <i class="fas fa-plus text-teal-600 text-lg"></i>
                </div>
                {{ __('Buat Lowongan Baru') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-gray-900 min-h-screen relative overflow-hidden font-sans">
        <!-- Background Ornaments -->
        <div class="absolute top-0 left-0 w-full h-96 bg-gradient-to-b from-teal-50 dark:from-teal-950/20 to-slate-50 dark:to-gray-900 z-0"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-emerald-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-24 -left-24 w-72 h-72 bg-teal-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>

        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 relative z-10">
            
            <div class="mb-8">
                <a href="{{ route('dinas.lowongan.index') }}" class="inline-flex items-center text-sm font-bold text-slate-500 dark:text-gray-400 hover:text-teal-700 dark:hover:text-teal-400 transition-all group bg-white dark:bg-gray-800/60 backdrop-blur-md px-4 py-2 rounded-full border border-slate-200 dark:border-gray-700 shadow-sm hover:shadow-md">
                    <div class="w-7 h-7 rounded-full bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 flex items-center justify-center mr-3 group-hover:border-teal-400 dark:group-hover:border-teal-600 group-hover:bg-teal-50 dark:group-hover:bg-teal-950/30 transition-colors">
                        <i class="fas fa-arrow-left text-xs text-slate-400 group-hover:text-teal-600"></i>
                    </div>
                    Kembali ke Daftar Lowongan
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800/80 backdrop-blur-xl rounded-3xl shadow-xl border border-slate-100/60 dark:border-gray-700/60 overflow-hidden transform transition-all">
                
                <div class="relative overflow-hidden bg-gradient-to-r from-teal-600 to-emerald-500 p-8 text-white">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white dark:bg-gray-800 rounded-full opacity-10 blur-2xl"></div>
                    <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-24 h-24 bg-teal-900 rounded-full opacity-10 blur-xl"></div>
                    <div class="relative z-10 flex items-center gap-5">
                        <div class="w-16 h-16 rounded-2xl bg-white dark:bg-gray-800/20 backdrop-blur-md flex items-center justify-center text-white text-2xl border border-white/30 shadow-inner">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black tracking-tight">Detail Lowongan Magang</h3>
                            <p class="text-teal-50 text-sm mt-1 font-medium opacity-90">Lengkapi informasi posisi magang yang dibutuhkan oleh instansi Anda.</p>
                        </div>
                    </div>
                </div>

                <div class="p-8 md:p-10">
                    <form action="{{ route('dinas.lowongan.store') }}" method="POST" id="lowongan-form">
                        @csrf
                        
                        <div class="space-y-10">
                            <!-- Section 1: Informasi Utama -->
                            <div>
                                <div class="flex items-center gap-3 mb-6 pb-2 border-b border-slate-100 dark:border-gray-700">
                                    <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-teal-50 dark:bg-teal-950/30 text-teal-600 dark:text-teal-400 font-bold text-sm">1</span>
                                    <h4 class="text-md font-bold text-slate-800 dark:text-gray-200 tracking-wide uppercase">Informasi Posisi Magang</h4>
                                </div>

                                <div class="space-y-6">
                                    <!-- Nama Posisi / Jabatan Magang -->
                                    <div class="relative group">
                                        <label for="judul_posisi" class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2 flex items-center gap-1.5">
                                            <i class="fas fa-briefcase text-teal-500"></i> Nama Posisi / Jabatan Magang <span class="text-red-500" title="Wajib diisi">*</span>
                                        </label>
                                        <div class="relative transition-all duration-300 group-focus-within:drop-shadow-sm">
                                            <input type="text" name="judul_posisi" id="judul_posisi" value="{{ old('judul_posisi') }}" 
                                                class="w-full px-5 py-3.5 bg-slate-50 dark:bg-gray-900 border @error('judul_posisi') border-red-400 focus:border-red-500 focus:ring-red-500/10 @else border-slate-200 dark:border-gray-700 focus:border-teal-500 focus:ring-teal-500/10 @enderror rounded-2xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 transition-all font-bold text-slate-800 dark:text-gray-100 text-lg placeholder-slate-400 dark:placeholder-gray-600"
                                                placeholder="Contoh: Programmer Web, Staff Administrasi, Desainer Grafis" required>
                                            @error('judul_posisi')
                                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-red-500">
                                                    <i class="fas fa-exclamation-circle text-lg"></i>
                                                </div>
                                            @enderror
                                        </div>
                                        <p class="text-xs text-slate-400 dark:text-gray-500 mt-2 ml-1 font-medium"><i class="fas fa-info-circle mr-1"></i> Tuliskan nama spesifik untuk posisi magang yang ditawarkan.</p>
                                        @error('judul_posisi') 
                                            <span class="text-red-500 text-xs mt-2 ml-1 font-bold flex items-center gap-1"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</span> 
                                        @enderror
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Syarat Jurusan -->
                                        <div class="relative group">
                                            <label for="required_major" class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2 flex items-center gap-1.5">
                                                <i class="fas fa-graduation-cap text-teal-500"></i> Kualifikasi Jurusan
                                            </label>
                                            <div class="relative transition-all duration-300 group-focus-within:drop-shadow-sm">
                                                <input type="text" name="required_major" id="required_major" value="{{ old('required_major') }}" 
                                                    class="w-full px-5 py-3.5 bg-slate-50 dark:bg-gray-900 border @error('required_major') border-red-400 focus:border-red-500 focus:ring-red-500/10 @else border-slate-200 dark:border-gray-700 focus:border-teal-500 focus:ring-teal-500/10 @enderror rounded-2xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 transition-all font-medium text-slate-800 dark:text-gray-100 placeholder-slate-400 dark:placeholder-gray-600"
                                                    placeholder="Contoh: Teknik Informatika, DKV, Akuntansi">
                                                @error('required_major')
                                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-red-500">
                                                        <i class="fas fa-exclamation-circle text-lg"></i>
                                                    </div>
                                                @enderror
                                            </div>
                                            <p class="text-xs text-slate-400 dark:text-gray-500 mt-2 ml-1 font-medium"><i class="fas fa-info-circle mr-1"></i> Pisahkan dengan koma jika lebih dari satu. Kosongkan jika terbuka untuk semua jurusan.</p>
                                            @error('required_major') 
                                                <span class="text-red-500 text-xs mt-2 ml-1 font-bold flex items-center gap-1"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</span> 
                                            @enderror
                                        </div>

                                        <!-- Kuota Penerimaan -->
                                        <div class="relative group">
                                            <label for="kuota" class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2 flex items-center gap-1.5">
                                                <i class="fas fa-users text-emerald-500"></i> Kuota Penerimaan <span class="text-red-500" title="Wajib diisi">*</span>
                                            </label>
                                            <div class="relative transition-all duration-300 group-focus-within:drop-shadow-md">
                                                <input type="number" name="kuota" id="kuota" value="{{ old('kuota', 1) }}" min="1" 
                                                    class="w-full px-5 py-3.5 bg-slate-50 dark:bg-gray-900 border @error('kuota') border-red-400 focus:border-red-500 focus:ring-red-500/10 @else border-slate-200 dark:border-gray-700 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 @enderror rounded-2xl focus:bg-white dark:focus:bg-gray-800 transition-all font-medium text-slate-800 dark:text-gray-100" required>
                                                @error('kuota')
                                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-red-500">
                                                        <i class="fas fa-exclamation-circle text-lg"></i>
                                                    </div>
                                                @enderror
                                            </div>
                                            <p class="text-xs text-slate-400 dark:text-gray-500 mt-2 ml-1 font-medium"><i class="fas fa-info-circle mr-1"></i> Masukkan jumlah peserta magang yang dibutuhkan (minimal 1).</p>
                                            @error('kuota') 
                                                <span class="text-red-500 text-xs mt-2 ml-1 font-bold flex items-center gap-1"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</span> 
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 2: Jadwal & Deskripsi -->
                            <div>
                                <div class="flex items-center gap-3 mb-6 pb-2 border-b border-slate-100 dark:border-gray-700">
                                    <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-teal-50 dark:bg-teal-950/30 text-teal-600 dark:text-teal-400 font-bold text-sm">2</span>
                                    <h4 class="text-md font-bold text-slate-800 dark:text-gray-200 tracking-wide uppercase">Jadwal & Deskripsi Pekerjaan</h4>
                                </div>

                                <div class="space-y-6">
                                    <!-- Batas Akhir Pendaftaran -->
                                    <div class="relative group">
                                        <label for="batas_daftar" class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2 flex items-center gap-1.5">
                                            <i class="far fa-calendar-alt text-indigo-500"></i> Batas Akhir Pendaftaran
                                        </label>
                                        <div class="relative transition-all duration-300 group-focus-within:drop-shadow-md md:w-1/2">
                                            <input type="date" name="batas_daftar" id="batas_daftar" value="{{ old('batas_daftar') }}" 
                                                class="w-full px-5 py-3.5 bg-slate-50 dark:bg-gray-900 border @error('batas_daftar') border-red-400 focus:border-red-500 focus:ring-red-500/10 @else border-slate-200 dark:border-gray-700 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 @enderror rounded-2xl focus:bg-white dark:focus:bg-gray-800 transition-all font-medium text-slate-800 dark:text-gray-100"
                                                min="{{ date('Y-m-d') }}">
                                            @error('batas_daftar')
                                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-red-500">
                                                    <i class="fas fa-exclamation-circle text-lg"></i>
                                                </div>
                                            @enderror
                                        </div>
                                        <p class="text-xs text-slate-400 dark:text-gray-500 mt-2 ml-1 font-medium"><i class="fas fa-info-circle mr-1"></i> Kosongkan jika pendaftaran tidak dibatasi tenggat waktu tertentu.</p>
                                        @error('batas_daftar') 
                                            <span class="text-red-500 text-xs mt-2 ml-1 font-bold flex items-center gap-1"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</span> 
                                        @enderror
                                    </div>

                                    <!-- Deskripsi Pekerjaan -->
                                    <div class="relative group">
                                        <label for="editor" class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2 flex items-center gap-1.5">
                                            <i class="fas fa-file-alt text-teal-600"></i> Deskripsi Pekerjaan & Persyaratan Detail
                                        </label>
                                        <div class="rounded-2xl overflow-hidden border @error('deskripsi') border-red-400 focus-within:ring-red-500/10 focus-within:border-red-500 @else border-slate-200 dark:border-gray-700 focus-within:ring-teal-500/10 focus-within:border-teal-500 @enderror shadow-sm focus-within:ring-4 transition-all duration-300 bg-white dark:bg-gray-800">
                                            <textarea id="editor" name="deskripsi" class="w-full border-0 focus:ring-0">{{ old('deskripsi') }}</textarea>
                                        </div>
                                        <p class="text-xs text-slate-400 dark:text-gray-500 mt-2 ml-1 font-medium"><i class="fas fa-info-circle mr-1"></i> Jabarkan ruang lingkup kerja, tugas, serta kompetensi khusus yang dibutuhkan.</p>
                                        @error('deskripsi') 
                                            <span class="text-red-500 text-xs mt-2 ml-1 font-bold flex items-center gap-1"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</span> 
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-12 pt-6 border-t border-slate-100 dark:border-gray-700 flex flex-col-reverse sm:flex-row items-center justify-end gap-4">
                            <a href="{{ route('dinas.lowongan.index') }}" class="w-full sm:w-auto text-center px-7 py-3.5 rounded-2xl border-2 border-slate-200 dark:border-gray-600 text-slate-600 dark:text-gray-300 font-bold hover:bg-slate-50 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-100 transition-all focus:ring-4 focus:ring-slate-100 dark:focus:ring-gray-700">
                                Batal
                            </a>
                            <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-gradient-to-r from-teal-600 to-emerald-500 text-white rounded-2xl font-bold hover:from-teal-700 hover:to-emerald-600 shadow-lg shadow-teal-500/20 transition-all transform hover:-translate-y-0.5 active:translate-y-0 active:scale-95 flex items-center justify-center gap-2">
                                <i class="fas fa-paper-plane text-sm"></i> Terbitkan Lowongan
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

        /* ── CKEditor 5 Dark Theme via CSS Variables ── */
        /* CKEditor 5 is built on CSS custom properties — override them globally in .dark */
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

            /* Input fields (heading dropdown, etc) */
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

            /* Table (just in case) */
            --ck-color-table-focused-cell-background: rgba(20,184,166,0.1);

            /* Separator */
            --ck-color-toolbar-separator: #374151;
        }

        /* Editable area background & text (needs explicit bg since it renders in its own scrollable div) */
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
                            'heading', '|',
                            'bold', 'italic', 'underline', 'bulletedList', 'numberedList', '|',
                            'outdent', 'indent', '|', 'undo', 'redo'
                        ],
                        shouldNotGroupWhenFull: true
                    },
                    placeholder: 'Jelaskan tanggung jawab, jobdesk, dan kualifikasi khusus di sini...',
                    removePlugins: [
                        'CKBox', 'CKFinder', 'EasyImage', 'RealTimeCollaborativeComments', 
                        'RealTimeCollaborativeTrackChanges', 'RealTimeCollaborativeRevisionHistory', 
                        'PresenceList', 'Comments', 'TrackChanges', 'TrackChangesData', 
                        'RevisionHistory', 'Pagination', 'WProofreader', 'MathType', 
                        'Image', 'ImageCaption', 'ImageStyle', 'ImageToolbar', 'ImageUpload', 
                        'Table', 'TableToolbar', 'MediaEmbed'
                    ]
                }).then(editor => {
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