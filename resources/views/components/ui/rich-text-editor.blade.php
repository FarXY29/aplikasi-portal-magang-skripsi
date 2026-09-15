@props([
    'name' => 'deskripsi',
    'id' => 'editor',
    'placeholder' => 'Jelaskan tanggung jawab, jobdesk, dan kualifikasi khusus di sini...',
    'minHeight' => '250px',
    'toolbar' => [
        'undo', 'redo', '|', 'heading', '|',
        'bold', 'italic', 'underline', 'bulletedList', 'numberedList', '|',
        'outdent', 'indent',
    ],
])

{{--
    Komponen CKEditor 5 (super-build) yang di-load on-demand saat user
    berinteraksi dengan editor. Menghindari ~1 MB blocking script dan
    menghilangkan duplikasi tema dark + logika init antar form lowongan.
--}}

@php
    $ckConfig = json_encode([
        'toolbar' => array_values($toolbar),
        'placeholder' => $placeholder,
        'minHeight' => $minHeight,
    ]);
@endphp

<style>
    /* ── CKEditor 5 Dark Theme via CSS Variables ── */
    .dark {
        --ck-color-base-background:       #1f2937;
        --ck-color-base-foreground:       #111827;
        --ck-color-base-border:           #374151;
        --ck-color-base-text:             #f3f4f6;
        --ck-color-base-active:           #374151;
        --ck-color-base-active-focus:     #4b5563;
        --ck-color-base-error:            #ef4444;
        --ck-color-base-action:           #14b8a6;
        --ck-color-base-focus:            #14b8a6;
        --ck-color-focus-border:          #14b8a6;
        --ck-color-focus-outer-shadow:    rgba(20,184,166,0.25);
        --ck-color-toolbar-background:    #1f2937;
        --ck-color-toolbar-border:        #374151;
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
        --ck-color-dropdown-panel-background: #1f2937;
        --ck-color-dropdown-panel-border:     #374151;
        --ck-color-panel-background:          #1f2937;
        --ck-color-panel-border:              #374151;
        --ck-color-list-background:              #1f2937;
        --ck-color-list-button-hover-background: #374151;
        --ck-color-list-button-on-background:    #374151;
        --ck-color-list-button-on-background-focus: #4b5563;
        --ck-color-list-button-on-text:          #f3f4f6;
        --ck-color-input-background:          #111827;
        --ck-color-input-border:              #374151;
        --ck-color-input-text:                #f3f4f6;
        --ck-color-input-disabled-background: #1f2937;
        --ck-color-input-disabled-border:     #374151;
        --ck-color-input-disabled-text:       #9ca3af;
        --ck-color-editor-base-text:          #f3f4f6;
        --ck-color-shadow-drop:  rgba(0,0,0,0.5);
        --ck-color-shadow-inner: rgba(0,0,0,0.5);
        --ck-color-shadow-small: rgba(0,0,0,0.5);
        --ck-color-tooltip-background: #374151;
        --ck-color-tooltip-text:       #f3f4f6;
        --ck-color-table-focused-cell-background: rgba(20,184,166,0.1);
        --ck-color-toolbar-separator: #374151;
    }

    .dark .ck-editor__editable_inline,
    .dark .ck.ck-editor__editable:not(.ck-editor__nested-editable) {
        background: #111827 !important;
        color: #f3f4f6 !important;
    }
</style>

<textarea id="{{ $id }}" name="{{ $name }}"
    data-ck-editor
    data-ck-config='{{ $ckConfig }}'
    {{ $attributes->merge(['class' => 'w-full border-0 focus:ring-0 bg-white dark:bg-gray-900 text-slate-800 dark:text-gray-100']) }}>{{ $slot }}</textarea>

@once
    @push('scripts')
    <script>
        (function () {
            const CKEDITOR_CDN = 'https://cdn.ckeditor.com/ckeditor5/40.0.0/super-build/ckeditor.js';
            const REMOVE_PLUGINS = [
                'CKBox', 'CKFinder', 'EasyImage', 'RealTimeCollaborativeComments',
                'RealTimeCollaborativeTrackChanges', 'RealTimeCollaborativeRevisionHistory',
                'PresenceList', 'Comments', 'TrackChanges', 'TrackChangesData',
                'RevisionHistory', 'Pagination', 'WProofreader', 'MathType',
                'Image', 'ImageCaption', 'ImageStyle', 'ImageToolbar', 'ImageUpload',
                'Table', 'TableToolbar', 'MediaEmbed'
            ];

            function loadCkEditor() {
                if (window.CKEDITOR && window.CKEDITOR.ClassicEditor) {
                    return Promise.resolve();
                }
                if (window.__ckEditorPromise) {
                    return window.__ckEditorPromise;
                }
                window.__ckEditorPromise = new Promise(function (resolve, reject) {
                    const script = document.createElement('script');
                    script.src = CKEDITOR_CDN;
                    script.onload = resolve;
                    script.onerror = reject;
                    document.head.appendChild(script);
                });
                return window.__ckEditorPromise;
            }

            function initRichText(el) {
                if (!el || el.dataset.ckLoaded) return;
                el.dataset.ckLoaded = '1';

                loadCkEditor().then(function () {
                    let config = {};
                    try { config = JSON.parse(el.dataset.ckConfig || '{}'); } catch (e) {}

                    window.CKEDITOR.ClassicEditor.create(el, {
                        toolbar: { items: config.toolbar, shouldNotGroupWhenFull: true },
                        placeholder: config.placeholder,
                        removePlugins: REMOVE_PLUGINS,
                    }).then(function (editor) {
                        editor.editing.view.change(function (writer) {
                            writer.setStyle('min-height', config.minHeight || '250px', editor.editing.view.document.getRoot());
                            writer.setStyle('border', 'none', editor.editing.view.document.getRoot());
                        });
                    }).catch(function (error) { console.error(error); });
                }).catch(function () {
                    console.warn('CKEditor gagal dimuat.');
                });
            }

            function bindEditors() {
                document.querySelectorAll('textarea[data-ck-editor]').forEach(function (el) {
                    if (el.dataset.ckListenerBound) return;
                    el.dataset.ckListenerBound = '1';
                    const trigger = function () { initRichText(el); };
                    el.addEventListener('focus', trigger, { once: true });
                    el.addEventListener('pointerdown', trigger, { once: true });
                });
            }

            document.addEventListener('DOMContentLoaded', bindEditors);
            document.addEventListener('turbo:load', bindEditors);
        })();
    </script>
    @endpush
@endonce
