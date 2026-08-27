<div x-data="realtimeNotificationHandler()" x-init="init()" class="relative">
    <!-- Bell Button -->
    <button @click="toggleDropdown()" 
            type="button"
            class="relative w-10 h-10 rounded-xl bg-slate-100/90 dark:bg-gray-800/80 hover:bg-slate-200/80 dark:hover:bg-gray-700/80 text-slate-600 dark:text-gray-300 hover:text-teal-700 dark:hover:text-teal-400 flex items-center justify-center transition active:scale-95 shadow-2xs border border-slate-200/80 dark:border-gray-700/60 focus:outline-none"
            title="Notifikasi">
        <i class="far fa-bell text-sm"></i>
        <template x-if="unreadCount > 0">
            <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] rounded-full bg-rose-600 text-white text-[10px] font-black flex items-center justify-center px-1 shadow-2xs border-2 border-white dark:border-gray-900 animate-pulse"
                  x-text="unreadCount > 9 ? '9+' : unreadCount"></span>
        </template>
    </button>

    <!-- Dropdown Panel -->
    <div x-show="open" 
         @click.away="open = false" 
         x-cloak
         x-transition:enter="transition ease-out duration-200 transform"
         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150 transform"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
         class="absolute right-0 mt-2 w-80 sm:w-96 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-slate-200/80 dark:border-gray-700 z-50 overflow-hidden text-left">
        
        <!-- Header -->
        <div class="p-3.5 border-b border-slate-100 dark:border-gray-700 flex items-center justify-between bg-slate-50/80 dark:bg-gray-900/60">
            <div class="flex items-center gap-2">
                <i class="fas fa-bell text-teal-700 dark:text-teal-400 text-xs"></i>
                <span class="text-xs font-black text-slate-800 dark:text-gray-100">Notifikasi</span>
                <template x-if="unreadCount > 0">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-rose-100 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400" 
                          x-text="unreadCount + ' Baru'"></span>
                </template>
            </div>
            <button x-show="unreadCount > 0" 
                    @click="markAllRead()" 
                    type="button"
                    class="text-[11px] font-bold text-teal-700 dark:text-teal-400 hover:underline">
                Tandai Semua Dibaca
            </button>
        </div>

        <!-- Notification List -->
        <div class="max-h-80 overflow-y-auto custom-scrollbar divide-y divide-slate-100 dark:divide-gray-700/60">
            <template x-for="item in notifications" :key="item.id">
                <div @click="handleClickItem(item)" 
                     :class="item.read ? 'bg-white dark:bg-gray-800 opacity-75' : 'bg-teal-50/30 dark:bg-teal-950/20'" 
                     class="p-3.5 hover:bg-slate-50 dark:hover:bg-gray-700/50 cursor-pointer transition flex items-start gap-3">
                    <div :class="getTypeIconWrapper(item.type)" class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 mt-0.5">
                        <i :class="getTypeIcon(item.type)" class="text-xs"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-1">
                            <p class="text-xs font-black text-slate-900 dark:text-white leading-tight truncate" x-text="item.title"></p>
                            <span x-show="!item.read" class="w-2 h-2 rounded-full bg-teal-600 shrink-0"></span>
                        </div>
                        <p class="text-[11px] text-slate-600 dark:text-gray-300 font-medium line-clamp-2 mt-0.5 leading-snug" x-text="item.message"></p>
                        <span class="text-[10px] text-slate-400 dark:text-gray-500 font-semibold mt-1 block" x-text="item.time_ago"></span>
                    </div>
                </div>
            </template>

            <!-- Empty State -->
            <div x-show="notifications.length === 0" class="p-8 text-center text-xs text-slate-400 dark:text-gray-500">
                <i class="far fa-bell-slash text-2xl mb-2 block text-slate-300 dark:text-gray-600"></i>
                <p class="font-bold">Tidak ada notifikasi</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Pembaruan status lamaran dan magang Anda akan muncul di sini.</p>
            </div>
        </div>
    </div>

    <!-- Toast Notification Popup -->
    <div x-show="showToast" 
         x-cloak
         x-transition:enter="transition ease-out duration-300 transform" 
         x-transition:enter-start="opacity-0 translate-y-4 scale-95" 
         x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
         x-transition:leave="transition ease-in duration-200 transform" 
         x-transition:leave-start="opacity-100 translate-y-0 scale-100" 
         x-transition:leave-end="opacity-0 translate-y-4 scale-95" 
         class="fixed bottom-6 right-6 z-[9999] max-w-sm w-full bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-2xl border-2 border-teal-500/60 pointer-events-auto flex items-start gap-3">
        <div :class="getTypeIconWrapper(toastData.type)" class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0">
            <i :class="getTypeIcon(toastData.type)" class="text-sm"></i>
        </div>
        <div class="flex-1 min-w-0">
            <h5 class="text-xs font-black text-slate-900 dark:text-white leading-tight" x-text="toastData.title"></h5>
            <p class="text-xs text-slate-600 dark:text-gray-300 font-medium line-clamp-2 mt-0.5" x-text="toastData.message"></p>
        </div>
        <button @click="showToast = false" type="button" class="text-slate-400 hover:text-slate-600 dark:hover:text-white text-xs p-1">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>

<script>
function realtimeNotificationHandler() {
    return {
        open: false,
        unreadCount: 0,
        notifications: [],
        lastIds: [],
        showToast: false,
        toastData: { title: '', message: '', type: 'info' },
        pollInterval: null,

        init() {
            this.fetchNotifications(true);
            // Polling interval 20 detik secara efisien
            this.pollInterval = setInterval(() => {
                this.fetchNotifications(false);
            }, 20000);
        },

        toggleDropdown() {
            this.open = !this.open;
            if (this.open) {
                this.fetchNotifications(false);
            }
        },

        async fetchNotifications(isFirstLoad = false) {
            try {
                const res = await fetch('{{ route('notifications.unread') }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                if (!res.ok) return;

                const data = await res.json();
                const prevCount = this.unreadCount;
                this.unreadCount = data.unread_count || 0;
                this.notifications = data.notifications || [];

                // Cek jika ada notifikasi baru masuk setelah load awal untuk memicu toast
                if (!isFirstLoad && data.notifications && data.notifications.length > 0) {
                    const latestNotif = data.notifications[0];
                    if (!latestNotif.read && !this.lastIds.includes(latestNotif.id)) {
                        this.triggerToast(latestNotif);
                    }
                }

                this.lastIds = this.notifications.map(n => n.id);
            } catch (err) {
                // Ignore network errors during polling
            }
        },

        triggerToast(item) {
            this.toastData = {
                title: item.title,
                message: item.message,
                type: item.type || 'info'
            };
            this.showToast = true;
            setTimeout(() => {
                this.showToast = false;
            }, 6000);
        },

        async handleClickItem(item) {
            if (!item.read) {
                item.read = true;
                this.unreadCount = Math.max(0, this.unreadCount - 1);
                try {
                    await fetch(`/notifications/${item.id}/read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                            'Accept': 'application/json',
                        }
                    });
                } catch (e) {}
            }
            if (item.action_url) {
                window.location.href = item.action_url;
            }
        },

        async markAllRead() {
            this.notifications.forEach(n => n.read = true);
            this.unreadCount = 0;
            try {
                await fetch('{{ route('notifications.read_all') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                        'Accept': 'application/json',
                    }
                });
            } catch (e) {}
        },

        getTypeIcon(type) {
            switch (type) {
                case 'success': return 'fas fa-check-circle text-emerald-600 dark:text-emerald-400';
                case 'warning': return 'fas fa-exclamation-triangle text-amber-600 dark:text-amber-400';
                case 'danger': return 'fas fa-times-circle text-rose-600 dark:text-rose-400';
                default: return 'fas fa-info-circle text-teal-600 dark:text-teal-400';
            }
        },

        getTypeIconWrapper(type) {
            switch (type) {
                case 'success': return 'bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800';
                case 'warning': return 'bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-800';
                case 'danger': return 'bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800';
                default: return 'bg-teal-50 dark:bg-teal-950/60 border border-teal-200 dark:border-teal-800';
            }
        }
    };
}
</script>
