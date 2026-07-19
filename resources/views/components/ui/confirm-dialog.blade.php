<div 
    x-data="{ 
        show: false, 
        title: '', 
        message: '',
        confirmText: 'Ya, Lanjutkan',
        cancelText: 'Batal',
        type: 'danger', // danger, warning, info
        onConfirm: null
    }"
    @open-confirm.window="
        show = true;
        title = $event.detail.title || 'Konfirmasi';
        message = $event.detail.message || 'Apakah Anda yakin?';
        confirmText = $event.detail.confirmText || 'Ya, Lanjutkan';
        cancelText = $event.detail.cancelText || 'Batal';
        type = $event.detail.type || 'danger';
        onConfirm = $event.detail.onConfirm || null;
    "
>
    <x-modal name="ui-confirm-dialog" :show="false" maxWidth="sm">
        <!-- We use a custom x-show on the modal content to sync with our component state -->
        <div x-show="show" @click.away="show = false" class="p-6">
            <div class="flex items-center justify-center mb-4">
                <div class="rounded-full p-3 flex items-center justify-center" 
                     :class="{ 'bg-red-100 text-red-600': type === 'danger', 'bg-yellow-100 text-yellow-600': 'warning', 'bg-blue-100 text-blue-600': 'info', }">
                    <i class="fas text-2xl" 
                       :class="{ 'fa-exclamation-triangle': type === 'danger' || 'warning', 'fa-info-circle': 'info' }"></i>
                </div>
            </div>
            
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 text-center mb-2" x-text="title"></h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 text-center mb-6" x-text="message"></p>
            
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <button type="button" 
                        @click="show = false"
                        class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl font-bold text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors">
                    <span x-text="cancelText"></span>
                </button>
                
                <button type="button" 
                        @click="if(onConfirm) onConfirm(); show = false"
                        :class="{ 'bg-red-600 hover:bg-red-700 focus:ring-red-500': type === 'danger', 'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500': 'warning', 'bg-teal-600 hover:bg-teal-700 focus:ring-teal-500': 'info', }"
                        class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-xl font-bold text-sm text-white focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors">
                    <span x-text="confirmText"></span>
                </button>
            </div>
        </div>
    </x-modal>
    
    <!-- Sync modal visibility -->
    <div x-init="$watch('show', value => {
        if (value) {
            $dispatch('open-modal', 'ui-confirm-dialog');
        } else {
            $dispatch('close-modal', 'ui-confirm-dialog');
        }
    })"></div>
</div>
