<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-950/60 flex items-center justify-center border border-teal-200 dark:border-teal-800/60">
                    <i class="fas fa-check-double text-teal-600 dark:text-teal-400 text-lg"></i>
                </div>
                <?php echo e(__('Validasi Logbook')); ?>

            </h2>
            <div class="flex items-center gap-3">
                <span class="text-xs font-bold text-gray-500 dark:text-gray-400">Mahasiswa:</span>
                <span class="px-3.5 py-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full text-xs font-bold text-gray-800 dark:text-gray-200 shadow-xs">
                    <?php echo e($app->user->name); ?>

                </span>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex justify-between items-center mb-6 print:hidden">
                <a href="<?php echo e(route('pembimbing_lapangan.dashboard')); ?>" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-xs">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Dashboard
                </a>
            </div>

            <?php if(session('success')): ?>
                <?php if (isset($component)) { $__componentOriginal746de018ded8594083eb43be3f1332e1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal746de018ded8594083eb43be3f1332e1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.alert','data' => ['type' => 'success','class' => 'mb-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'success','class' => 'mb-4']); ?>
                    <?php echo e(session('success')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal746de018ded8594083eb43be3f1332e1)): ?>
<?php $attributes = $__attributesOriginal746de018ded8594083eb43be3f1332e1; ?>
<?php unset($__attributesOriginal746de018ded8594083eb43be3f1332e1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal746de018ded8594083eb43be3f1332e1)): ?>
<?php $component = $__componentOriginal746de018ded8594083eb43be3f1332e1; ?>
<?php unset($__componentOriginal746de018ded8594083eb43be3f1332e1); ?>
<?php endif; ?>
            <?php endif; ?>

            
            <div class="bg-white dark:bg-gray-800 p-5 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex items-center gap-2">
                    <i class="fas fa-filter text-teal-600 dark:text-teal-400"></i>
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Filter Periode Logbook</span>
                </div>
                
                <form action="<?php echo e(route('pembimbing_lapangan.logbook', $app->id)); ?>" method="GET" class="w-full md:w-auto flex flex-wrap items-center gap-4">
                    
                    <div class="bg-gray-100 dark:bg-gray-900 p-1 rounded-xl flex items-center border border-gray-200 dark:border-gray-700">
                        <label class="cursor-pointer">
                            <input type="radio" name="filter_type" value="semua" <?php echo e($filterType === 'semua' ? 'checked' : ''); ?> class="sr-only peer" onchange="this.form.submit()">
                            <span class="px-3 py-1.5 text-xs font-bold rounded-lg text-gray-500 dark:text-gray-400 peer-checked:bg-white dark:peer-checked:bg-gray-800 peer-checked:text-teal-600 dark:peer-checked:text-teal-400 peer-checked:shadow-xs transition block">
                                Semua
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="filter_type" value="mingguan" <?php echo e($filterType === 'mingguan' ? 'checked' : ''); ?> class="sr-only peer" onchange="this.form.submit()">
                            <span class="px-3 py-1.5 text-xs font-bold rounded-lg text-gray-500 dark:text-gray-400 peer-checked:bg-white dark:peer-checked:bg-gray-800 peer-checked:text-teal-600 dark:peer-checked:text-teal-400 peer-checked:shadow-xs transition block">
                                Mingguan
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="filter_type" value="bulanan" <?php echo e($filterType === 'bulanan' ? 'checked' : ''); ?> class="sr-only peer" onchange="this.form.submit()">
                            <span class="px-3 py-1.5 text-xs font-bold rounded-lg text-gray-500 dark:text-gray-400 peer-checked:bg-white dark:peer-checked:bg-gray-800 peer-checked:text-teal-600 dark:peer-checked:text-teal-400 peer-checked:shadow-xs transition block">
                                Bulanan
                            </span>
                        </label>
                    </div>

                    <?php if($filterType !== 'semua'): ?>
                        
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-gray-500 dark:text-gray-400">
                                <?php echo e($filterType === 'bulanan' ? 'Bulan:' : 'Tanggal:'); ?>

                            </span>
                            <?php if($filterType === 'bulanan'): ?>
                                <input type="month" name="month" value="<?php echo e(\Carbon\Carbon::parse($selectedDate)->format('Y-m')); ?>" 
                                    class="border border-gray-300 dark:border-gray-700 rounded-xl text-xs shadow-xs focus:border-teal-500 focus:ring-teal-500 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 font-bold transition py-1.5 px-3 [color-scheme:dark]"
                                    onchange="this.form.date.value = this.value + '-01'; this.form.submit();">
                                <input type="hidden" name="date" value="<?php echo e($selectedDate); ?>">
                            <?php else: ?>
                                <input type="date" name="date" value="<?php echo e($selectedDate); ?>" onchange="this.form.submit()" 
                                    class="border border-gray-300 dark:border-gray-700 rounded-xl text-xs shadow-xs focus:border-teal-500 focus:ring-teal-500 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 font-bold transition py-1.5 px-3 [color-scheme:dark]">
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if(request('filter_type') && request('filter_type') != 'semua'): ?>
                        <a href="<?php echo e(route('pembimbing_lapangan.logbook', $app->id)); ?>" class="p-2 bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 hover:bg-rose-100 border border-rose-200 dark:border-rose-800/60 rounded-xl transition text-xs font-bold flex items-center gap-1.5" title="Reset Filter">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    <?php endif; ?>
                </form>
            </div>

            <?php if($logs->isEmpty()): ?>
                <div class="flex flex-col items-center justify-center py-16 bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-3 border border-gray-200 dark:border-gray-700">
                        <i class="fas fa-book-open text-3xl text-gray-400 dark:text-gray-500"></i>
                    </div>
                    <h3 class="text-base font-bold text-gray-800 dark:text-gray-200">Logbook Kosong</h3>
                    <?php if($filterType !== 'semua'): ?>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Tidak ada aktivitas logbook pada periode yang dipilih.</p>
                        <a href="<?php echo e(route('pembimbing_lapangan.logbook', $app->id)); ?>" class="mt-4 px-4 py-2 bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 font-bold rounded-xl text-xs hover:bg-teal-100 border border-teal-200 dark:border-teal-800/60 transition shadow-xs">Reset Filter</a>
                    <?php else: ?>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Mahasiswa ini belum mengunggah aktivitas jurnal apapun.</p>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start" 
                     x-data="{ activeTab: <?php echo e(session('last_id') ?? $logs->first()->id); ?> }">
                    
                    
                    <div class="md:col-span-4 col-span-1">
                        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden sticky top-8">
                            <div class="p-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex justify-between items-center">
                                <h3 class="font-bold text-gray-800 dark:text-gray-200 text-xs uppercase tracking-wider flex items-center gap-2">
                                    <i class="fas fa-list-ul text-teal-600 dark:text-teal-400"></i> Riwayat Aktivitas
                                </h3>
                                <span class="text-[10px] font-black bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-2 py-0.5 rounded-full"><?php echo e($logs->count()); ?></span>
                            </div>
                            
                            <form action="<?php echo e(route('pembimbing_lapangan.logbook.batch_validasi')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <div class="max-h-[60vh] overflow-y-auto custom-scrollbar">
                                    <ul class="divide-y divide-gray-100 dark:divide-gray-700/60">
                                        <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="flex items-center pr-2 hover:bg-gray-50 dark:hover:bg-gray-900/60 transition duration-150 group">
                                            <?php if($log->status_validasi != 'disetujui'): ?>
                                                <div class="pl-4 pr-1">
                                                    <input type="checkbox" name="log_ids[]" value="<?php echo e($log->id); ?>" class="rounded border-gray-300 dark:border-gray-700 text-teal-600 focus:ring-teal-500 cursor-pointer">
                                                </div>
                                            <?php else: ?>
                                                <div class="pl-4 pr-1 opacity-0 w-5"></div>
                                            <?php endif; ?>

                                            <button type="button"
                                                @click="activeTab = <?php echo e($log->id); ?>"
                                                :class="{ 'bg-teal-50/70 dark:bg-teal-950/40 border-l-4 border-teal-500 dark:border-teal-400': activeTab === <?php echo e($log->id); ?>, 'border-l-4 border-transparent': activeTab !== <?php echo e($log->id); ?> }"
                                                class="w-full text-left px-3 py-3 focus:outline-none">
                                                
                                                <div class="flex justify-between items-start mb-1">
                                                    <span class="text-xs font-bold text-gray-800 dark:text-gray-200" 
                                                          :class="{ 'text-teal-700 dark:text-teal-300': activeTab === <?php echo e($log->id); ?> }">
                                                        <?php echo e(\Carbon\Carbon::parse($log->tanggal)->format('d M Y')); ?>

                                                    </span>
                                                    
                                                    <?php if($log->status_validasi == 'disetujui'): ?>
                                                        <i class="fas fa-check-circle text-emerald-500 text-xs" title="Disetujui"></i>
                                                    <?php elseif($log->status_validasi == 'revisi'): ?>
                                                        <i class="fas fa-exclamation-circle text-rose-500 text-xs" title="Revisi"></i>
                                                    <?php else: ?>
                                                        <div class="w-2.5 h-2.5 rounded-full bg-amber-400 mt-1" title="Pending"></div>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <p class="text-[11px] text-gray-500 dark:text-gray-400 truncate group-hover:text-gray-700 dark:group-hover:text-gray-300">
                                                    <?php echo e(Str::limit($log->kegiatan, 32)); ?>

                                                </p>
                                            </button>
                                        </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                                
                                
                                <div class="p-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex flex-col gap-2">
                                    <div class="flex items-center justify-between mb-1">
                                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300 flex items-center gap-1.5 cursor-pointer">
                                            <input type="checkbox" onchange="document.querySelectorAll('input[name=\'log_ids[]\']').forEach(c => c.checked = this.checked)" class="rounded border-gray-300 dark:border-gray-700 text-teal-600 focus:ring-teal-500">
                                            Pilih Semua
                                        </label>
                                        <span class="text-[10px] text-gray-400 dark:text-gray-500 font-bold uppercase tracking-wider">Validasi Massal</span>
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="submit" name="status" value="disetujui" class="flex-1 bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold py-2 rounded-xl transition shadow-xs flex items-center justify-center">
                                            <i class="fas fa-check mr-1"></i> Terima
                                        </button>
                                        <button type="submit" name="status" value="revisi" class="flex-1 bg-white dark:bg-gray-800 border border-rose-200 dark:border-rose-800/60 text-rose-700 dark:text-rose-300 hover:bg-rose-50 dark:hover:bg-gray-700 text-xs font-bold py-2 rounded-xl transition shadow-xs flex items-center justify-center">
                                            <i class="fas fa-undo mr-1"></i> Revisi
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    
                    <div class="md:col-span-8 col-span-1">
                        <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div x-show="activeTab === <?php echo e($log->id); ?>" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             style="display: none;">
                            
                            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
                                
                                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                    <div>
                                        <h3 class="text-xl font-black text-gray-800 dark:text-gray-100">Detail Kegiatan Jurnal</h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center font-bold">
                                            <i class="far fa-calendar-alt mr-1.5 text-teal-600 dark:text-teal-400"></i> 
                                            <?php echo e(\Carbon\Carbon::parse($log->tanggal)->translatedFormat('l, d F Y')); ?>

                                        </p>
                                    </div>
                                    
                                    <?php
                                        $statusClass = match($log->status_validasi) {
                                            'disetujui' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800/60',
                                            'revisi' => 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800/60',
                                            default => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800/60'
                                        };
                                        $statusIcon = match($log->status_validasi) {
                                            'disetujui' => 'fa-check-circle',
                                            'revisi' => 'fa-undo',
                                            default => 'fa-clock'
                                        };
                                    ?>
                                    <span class="px-3.5 py-1 rounded-full text-xs font-bold uppercase border <?php echo e($statusClass); ?> flex items-center gap-1.5">
                                        <i class="fas <?php echo e($statusIcon); ?>"></i> <?php echo e(ucfirst($log->status_validasi)); ?>

                                    </span>
                                </div>

                                <div class="p-6 sm:p-8 space-y-6">
                                    <div class="flex flex-col lg:flex-row gap-6">
                                        
                                        <div class="w-full lg:w-1/3 flex-shrink-0">
                                            <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Dokumentasi</h4>
                                            <?php if($log->bukti_foto_path): ?>
                                                <div class="relative group rounded-2xl overflow-hidden shadow-xs border border-gray-200 dark:border-gray-700 cursor-zoom-in">
                                                    <img src="<?php echo e(route('storage.access', ['type' => 'logbook', 'filename' => basename($log->bukti_foto_path)])); ?>" class="w-full h-48 object-cover transition transform group-hover:scale-105 duration-500">
                                                    <a href="<?php echo e(route('storage.access', ['type' => 'logbook', 'filename' => basename($log->bukti_foto_path)])); ?>" target="_blank" class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition flex items-center justify-center">
                                                        <i class="fas fa-search-plus text-white text-xl opacity-0 group-hover:opacity-100 transition duration-200 drop-shadow"></i>
                                                    </a>
                                                </div>
                                                <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-2 text-center">*Klik gambar untuk membuka file ukuran penuh</p>
                                            <?php else: ?>
                                                <div class="w-full h-44 bg-gray-50 dark:bg-gray-900 rounded-2xl flex flex-col items-center justify-center text-gray-400 dark:text-gray-500 text-xs border-2 border-dashed border-gray-200 dark:border-gray-700">
                                                    <i class="far fa-image text-3xl mb-2 text-gray-300 dark:text-gray-600"></i>
                                                    <span class="font-bold">Tidak ada foto bukti</span>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="w-full lg:w-2/3">
                                            <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Deskripsi Pekerjaan</h4>
                                            <div class="p-5 bg-gray-50 dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-200 text-xs sm:text-sm leading-relaxed whitespace-pre-line min-h-[11rem]">
                                                <?php echo e($log->kegiatan); ?>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="pt-6 border-t border-gray-100 dark:border-gray-700">
                                        
                                        <?php if($log->komentar_pembimbing_lapangan): ?>
                                            <div class="mb-6 p-4 bg-blue-50/60 dark:bg-blue-950/40 rounded-2xl border border-blue-200 dark:border-blue-800/60 flex gap-3 items-start">
                                                <i class="fas fa-comment-dots text-blue-600 dark:text-blue-400 mt-0.5 text-base flex-shrink-0"></i>
                                                <div>
                                                    <span class="block text-xs font-bold text-blue-800 dark:text-blue-300 uppercase mb-1">Catatan Anda Sebelumnya:</span>
                                                    <p class="text-xs sm:text-sm text-blue-900 dark:text-blue-200 italic">"<?php echo e($log->komentar_pembimbing_lapangan); ?>"</p>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <?php if($log->status_validasi != 'disetujui_permanen'): ?> 
                                            <form action="<?php echo e(route('pembimbing_lapangan.logbook.validasi', $log->id)); ?>" method="POST" class="bg-gray-50 dark:bg-gray-900 p-5 rounded-2xl border border-gray-200 dark:border-gray-700">
                                                <?php echo csrf_field(); ?>
                                                <h4 class="text-xs font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-3 flex items-center gap-2">
                                                    <i class="fas fa-pen-nib text-teal-600 dark:text-teal-400"></i> Berikan Validasi & Catatan
                                                </h4>
                                                
                                                <div class="flex flex-col sm:flex-row gap-3">
                                                    <input type="text" name="komentar" 
                                                        class="flex-grow rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 text-xs font-bold shadow-xs" 
                                                        placeholder="Tulis catatan revisi atau apresiasi (Opsional)..."
                                                        value="<?php echo e($log->status_validasi == 'revisi' ? $log->komentar_pembimbing_lapangan : ''); ?>">
                                                    
                                                    <div class="flex gap-2 flex-shrink-0">
                                                        <button type="submit" name="status" value="disetujui" 
                                                            class="bg-teal-600 hover:bg-teal-700 text-white px-5 py-2.5 rounded-xl text-xs font-bold transition shadow-xs flex items-center gap-1.5">
                                                            <i class="fas fa-check"></i> Terima
                                                        </button>
                                                        
                                                        <button type="submit" name="status" value="revisi" 
                                                            class="bg-white dark:bg-gray-800 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60 px-5 py-2.5 rounded-xl text-xs font-bold hover:bg-rose-50 dark:hover:bg-gray-700 transition shadow-xs flex items-center gap-1.5">
                                                            <i class="fas fa-undo"></i> Revisi
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        <?php else: ?>
                                            <div class="text-center py-4">
                                                <p class="text-xs text-gray-400 dark:text-gray-500 italic">Logbook ini telah disetujui secara permanen.</p>
                                            </div>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                </div>
            <?php endif; ?>

        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #475569; }
    </style>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views\pembimbing_lapangan\logbook.blade.php ENDPATH**/ ?>