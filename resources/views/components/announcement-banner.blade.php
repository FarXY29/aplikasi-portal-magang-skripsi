@props(['audience' => null])

@php
    $targetAudience = $audience ?? auth()->user()?->role ?? 'all';
    $announcements = \App\Models\Announcement::published()
        ->forAudience($targetAudience)
        ->recent()
        ->get();
@endphp

@if($announcements->isNotEmpty())
    <div x-data="{ 
            openModal: false, 
            activeAnnouncement: null,
            dismissed: JSON.parse(sessionStorage.getItem('dismissed_announcements_{{ auth()->id() ?? 'guest' }}') || '[]'),
            isDismissed(id) {
                return this.dismissed.includes(id);
            },
            dismiss(id) {
                if (!this.dismissed.includes(id)) {
                    this.dismissed.push(id);
                    sessionStorage.setItem('dismissed_announcements_{{ auth()->id() ?? 'guest' }}', JSON.stringify(this.dismissed));
                }
            },
            showDetail(announcement) {
                this.activeAnnouncement = announcement;
                this.openModal = true;
            }
        }" 
        class="space-y-3 mb-6 font-[Inter]">

        @foreach($announcements as $announcement)
            <div x-show="!isDismissed({{ $announcement->id }})" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                @class([
                    'p-4 sm:p-5 rounded-2xl border shadow-xs transition-all relative overflow-hidden',
                    'bg-rose-50/90 dark:bg-rose-950/40 border-rose-200/80 dark:border-rose-800/50 text-rose-900 dark:text-rose-200' => $announcement->type === 'urgent',
                    'bg-amber-50/90 dark:bg-amber-950/40 border-amber-200/80 dark:border-amber-800/50 text-amber-900 dark:text-amber-200' => $announcement->type === 'warning',
                    'bg-emerald-50/90 dark:bg-emerald-950/40 border-emerald-200/80 dark:border-emerald-800/50 text-emerald-900 dark:text-emerald-200' => $announcement->type === 'event',
                    'bg-teal-50/90 dark:bg-teal-950/40 border-teal-200/80 dark:border-teal-800/50 text-teal-900 dark:text-teal-200' => $announcement->type === 'info',
                ])>

                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-start gap-3.5 min-w-0">
                        <div @class([
                            'w-10 h-10 rounded-xl flex items-center justify-center shrink-0 shadow-xs',
                            'bg-rose-600 text-white' => $announcement->type === 'urgent',
                            'bg-amber-500 text-white' => $announcement->type === 'warning',
                            'bg-emerald-600 text-white' => $announcement->type === 'event',
                            'bg-teal-600 text-white' => $announcement->type === 'info',
                        ])>
                            <i class="{{ $announcement->type_icon }} text-base"></i>
                        </div>

                        <div class="min-w-0">
                            <div class="flex items-center gap-2 mb-1 flex-wrap">
                                <span @class([
                                    'text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md',
                                    'bg-rose-200/70 text-rose-800 dark:bg-rose-900/60 dark:text-rose-300' => $announcement->type === 'urgent',
                                    'bg-amber-200/70 text-amber-800 dark:bg-amber-900/60 dark:text-amber-300' => $announcement->type === 'warning',
                                    'bg-emerald-200/70 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-300' => $announcement->type === 'event',
                                    'bg-teal-200/70 text-teal-800 dark:bg-teal-900/60 dark:text-teal-300' => $announcement->type === 'info',
                                ])>
                                    {{ strtoupper($announcement->type) }}
                                </span>
                                <span class="text-[11px] opacity-75 font-medium">
                                    {{ $announcement->created_at->translatedFormat('d M Y') }}
                                </span>
                            </div>

                            <h4 class="text-sm sm:text-base font-black leading-snug line-clamp-1">
                                {{ $announcement->title }}
                            </h4>

                            <p class="text-xs opacity-85 mt-1 line-clamp-2 leading-relaxed">
                                {{ Str::limit(strip_tags($announcement->content), 160) }}
                            </p>

                            <div class="mt-3 flex items-center gap-3">
                                <button type="button" @click="showDetail({{ json_encode([
                                    'title' => $announcement->title,
                                    'type' => $announcement->type,
                                    'type_icon' => $announcement->type_icon,
                                    'date' => $announcement->created_at->translatedFormat('d F Y H:i'),
                                    'creator' => $announcement->creator->name ?? 'Pemerintah Kota Banjarmasin',
                                    'content' => nl2br(e($announcement->content)),
                                    'banner' => $announcement->banner_image ? asset('storage/' . $announcement->banner_image) : null,
                                ]) }})" class="inline-flex items-center text-xs font-black underline underline-offset-4 hover:opacity-80 transition cursor-pointer">
                                    Baca Pengumuman Selengkapnya &rarr;
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="button" @click="dismiss({{ $announcement->id }})" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/5 transition shrink-0" title="Tutup Notifikasi">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
            </div>
        @endforeach

        <!-- Detail Modal -->
        <div x-show="openModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs transition-opacity" @click="openModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-100 dark:border-gray-700">
                    <div class="p-6 sm:p-8">
                        <template x-if="activeAnnouncement && activeAnnouncement.banner">
                            <img :src="activeAnnouncement.banner" alt="Banner" class="w-full h-48 object-cover rounded-2xl mb-5 border border-gray-100 dark:border-gray-700">
                        </template>

                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-md bg-teal-50 dark:bg-teal-900/30 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-800" x-text="activeAnnouncement ? activeAnnouncement.type.toUpperCase() : ''"></span>
                            <span class="text-xs text-gray-400 font-medium" x-text="activeAnnouncement ? activeAnnouncement.date : ''"></span>
                        </div>

                        <h3 class="text-lg sm:text-xl font-black text-gray-900 dark:text-gray-100 leading-tight mb-4" x-text="activeAnnouncement ? activeAnnouncement.title : ''"></h3>

                        <div class="text-xs text-gray-400 mb-5 flex items-center gap-2 pb-4 border-b border-gray-100 dark:border-gray-700">
                            <span><i class="far fa-user mr-1 text-teal-600"></i> Diterbitkan oleh: <strong class="text-gray-700 dark:text-gray-300" x-text="activeAnnouncement ? activeAnnouncement.creator : ''"></strong></span>
                        </div>

                        <div class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed max-h-96 overflow-y-auto prose dark:prose-invert" x-html="activeAnnouncement ? activeAnnouncement.content : ''"></div>

                        <div class="mt-8 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-end">
                            <button type="button" @click="openModal = false" class="py-2.5 px-6 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-xs font-bold transition shadow-sm">
                                Tutup Pengumuman
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endif
