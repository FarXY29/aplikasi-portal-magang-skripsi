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
                    <i class="fas fa-user-clock text-teal-600 dark:text-teal-400 text-lg"></i>
                </div>
                <?php echo e(__('Monitoring Absensi Mahasiswa')); ?>

            </h2>
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

            <div class="flex flex-col lg:flex-row gap-8 items-start">
                
                
                <div class="w-full lg:w-1/4 flex-shrink-0 print:hidden">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 sticky top-8 space-y-5">
                        <div class="pb-3 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                                <i class="fas fa-filter text-teal-600 dark:text-teal-400"></i> Filter Data Absensi
                            </h3>
                        </div>

                        <form action="<?php echo e(route('pembimbing_lapangan.attendance.index')); ?>" method="GET" class="space-y-5">
                            
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Rentang Waktu</label>
                                <div class="grid grid-cols-3 gap-1 bg-gray-100 dark:bg-gray-900 p-1 rounded-xl border border-gray-200 dark:border-gray-700">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="filter_type" value="harian" <?php echo e($filterType === 'harian' ? 'checked' : ''); ?> class="sr-only peer" onchange="this.form.submit()">
                                        <span class="block text-center text-[10px] font-bold py-1.5 rounded-lg text-gray-500 dark:text-gray-400 peer-checked:bg-white dark:peer-checked:bg-gray-800 peer-checked:text-teal-600 dark:peer-checked:text-teal-400 peer-checked:shadow-xs transition">
                                            Harian
                                        </span>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="filter_type" value="mingguan" <?php echo e($filterType === 'mingguan' ? 'checked' : ''); ?> class="sr-only peer" onchange="this.form.submit()">
                                        <span class="block text-center text-[10px] font-bold py-1.5 rounded-lg text-gray-500 dark:text-gray-400 peer-checked:bg-white dark:peer-checked:bg-gray-800 peer-checked:text-teal-600 dark:peer-checked:text-teal-400 peer-checked:shadow-xs transition">
                                            Mingguan
                                        </span>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="filter_type" value="bulanan" <?php echo e($filterType === 'bulanan' ? 'checked' : ''); ?> class="sr-only peer" onchange="this.form.submit()">
                                        <span class="block text-center text-[10px] font-bold py-1.5 rounded-lg text-gray-500 dark:text-gray-400 peer-checked:bg-white dark:peer-checked:bg-gray-800 peer-checked:text-teal-600 dark:peer-checked:text-teal-400 peer-checked:shadow-xs transition">
                                            Bulanan
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">
                                    <?php if($filterType === 'bulanan'): ?>
                                        Pilih Bulan
                                    <?php else: ?>
                                        Pilih Tanggal
                                    <?php endif; ?>
                                </label>
                                <?php if($filterType === 'bulanan'): ?>
                                    <input type="month" name="month" value="<?php echo e(\Carbon\Carbon::parse($selectedDate)->format('Y-m')); ?>" 
                                        class="w-full border border-gray-300 dark:border-gray-700 rounded-xl text-xs font-bold shadow-xs focus:border-teal-500 focus:ring-teal-500 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 transition py-2 px-3 [color-scheme:dark]"
                                        onchange="this.form.date.value = this.value + '-01'; this.form.submit();">
                                    <input type="hidden" name="date" value="<?php echo e($selectedDate); ?>">
                                <?php else: ?>
                                    <input type="date" name="date" value="<?php echo e($selectedDate); ?>" 
                                        class="w-full border border-gray-300 dark:border-gray-700 rounded-xl text-xs font-bold shadow-xs focus:border-teal-500 focus:ring-teal-500 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 transition py-2 px-3 [color-scheme:dark]">
                                    <?php if($filterType === 'mingguan'): ?>
                                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1.5">*Mengambil 1 minggu dari tanggal tersebut.</p>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>

                            <?php if($filterType === 'harian'): ?>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase mb-2">Pilihan Cepat</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <?php $__currentLoopData = $dateList->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dateItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $isActive = $dateItem->format('Y-m-d') == $selectedDate;
                                            $activeClass = $isActive ? 'bg-teal-600 text-white border-teal-600 shadow-sm' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700 hover:border-teal-500 dark:hover:border-teal-400';
                                        ?>
                                        <a href="<?php echo e(route('pembimbing_lapangan.attendance.index', ['date' => $dateItem->format('Y-m-d'), 'filter_type' => 'harian'])); ?>" 
                                           class="text-[10px] text-center py-2 px-1 rounded-xl border transition duration-200 font-bold <?php echo e($activeClass); ?>">
                                            <?php echo e($dateItem->isToday() ? 'HARI INI' : $dateItem->translatedFormat('D, d M')); ?>

                                        </a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if (isset($component)) { $__componentOriginald411d1792bd6cc877d687758b753742c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald411d1792bd6cc877d687758b753742c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.primary-button','data' => ['class' => 'w-full justify-center py-2.5 text-xs']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('primary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-full justify-center py-2.5 text-xs']); ?>
                                <i class="fas fa-search mr-1.5"></i> Terapkan Filter
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $attributes = $__attributesOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__attributesOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $component = $__componentOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__componentOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
                            
                            <?php if(request('date') && request('date') != date('Y-m-d') || request('filter_type') && request('filter_type') != 'harian'): ?>
                                <a href="<?php echo e(route('pembimbing_lapangan.attendance.index')); ?>" class="block text-center text-xs text-gray-400 dark:text-gray-500 hover:text-rose-600 dark:hover:text-rose-400 transition font-bold">
                                    <i class="fas fa-times mr-1"></i> Reset Filter
                                </a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>

                
                <div class="w-full lg:w-3/4">
                    <div class="bg-white dark:bg-gray-800 shadow-xs rounded-3xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                        
                        <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white dark:bg-gray-800">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                                    <i class="fas fa-calendar-check text-teal-600 dark:text-teal-400"></i>
                                    <?php if($filterType === 'mingguan'): ?>
                                        Rekap Absensi Mingguan
                                    <?php elseif($filterType === 'bulanan'): ?>
                                        Rekap Absensi Bulanan
                                    <?php else: ?>
                                        Rekap Absensi Harian
                                    <?php endif; ?>
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-medium">
                                    <?php if($filterType === 'mingguan'): ?>
                                        Periode: <span class="font-bold text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/60 border border-teal-200 dark:border-teal-800/60 px-2 py-0.5 rounded-md"><?php echo e(\Carbon\Carbon::parse($selectedDate)->startOfWeek()->translatedFormat('d M Y')); ?> — <?php echo e(\Carbon\Carbon::parse($selectedDate)->endOfWeek()->translatedFormat('d M Y')); ?></span>
                                    <?php elseif($filterType === 'bulanan'): ?>
                                        Bulan: <span class="font-bold text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/60 border border-teal-200 dark:border-teal-800/60 px-2 py-0.5 rounded-md"><?php echo e(\Carbon\Carbon::parse($selectedDate)->translatedFormat('F Y')); ?></span>
                                    <?php else: ?>
                                        Tanggal: <span class="font-bold text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/60 border border-teal-200 dark:border-teal-800/60 px-2 py-0.5 rounded-md"><?php echo e(\Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y')); ?></span>
                                    <?php endif; ?>
                                </p>
                            </div>
                            
                            <button onclick="window.print()" class="bg-gray-800 dark:bg-gray-700 text-white hover:bg-gray-700 dark:hover:bg-gray-600 px-4 py-2 rounded-xl text-xs font-bold shadow-xs transition flex items-center gap-2 print:hidden">
                                <i class="fas fa-print"></i> Cetak
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full divide-y divide-gray-100 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th class="px-5 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Peserta</th>
                                        <?php if($filterType !== 'harian'): ?>
                                            <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                                        <?php endif; ?>
                                        <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jam Masuk</th>
                                        <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jam Pulang</th>
                                        <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                        <th class="px-5 py-4 text-right text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi Validasi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700/60 text-sm">
                                    <?php $__empty_1 = true; $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="hover:bg-teal-50/15 dark:hover:bg-teal-950/20 transition duration-150">
                                        
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="h-9 w-9 bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-300 rounded-full flex items-center justify-center font-black text-xs border border-teal-200 dark:border-teal-800/60 flex-shrink-0 shadow-xs">
                                                    <?php echo e(strtoupper(substr($row->application->user->name, 0, 1))); ?>

                                                </div>
                                                <div class="min-w-0">
                                                    <div class="text-sm font-bold text-gray-900 dark:text-gray-100 truncate"><?php echo e($row->application->user->name); ?></div>
                                                    <div class="text-[10px] text-gray-500 dark:text-gray-400 font-semibold truncate"><?php echo e(Str::limit($row->application->position->judul_posisi, 28)); ?></div>
                                                </div>
                                            </div>
                                        </td>

                                        <?php if($filterType !== 'harian'): ?>
                                            <td class="px-5 py-4 whitespace-nowrap text-center text-xs font-bold text-gray-700 dark:text-gray-300">
                                                <?php echo e(\Carbon\Carbon::parse($row->date)->translatedFormat('d M Y')); ?>

                                            </td>
                                        <?php endif; ?>
                                        
                                        <td class="px-5 py-4 whitespace-nowrap text-center">
                                            <?php if($row->status == 'hadir'): ?>
                                                <span class="text-xs font-mono font-bold text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 px-2.5 py-1 rounded-md">
                                                    <?php echo e(\Carbon\Carbon::parse($row->clock_in)->format('H:i')); ?>

                                                </span>
                                            <?php else: ?>
                                                <span class="text-gray-400 dark:text-gray-500">-</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="px-5 py-4 whitespace-nowrap text-center">
                                            <?php if($row->status == 'hadir'): ?>
                                                <?php if($row->clock_out): ?>
                                                    <span class="text-xs font-mono font-bold text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 px-2.5 py-1 rounded-md">
                                                        <?php echo e(\Carbon\Carbon::parse($row->clock_out)->format('H:i')); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-[10px] text-rose-600 dark:text-rose-400 italic bg-rose-50 dark:bg-rose-950/60 border border-rose-100 dark:border-rose-900/40 px-2 py-0.5 rounded-md font-bold">Belum Absen</span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-gray-400 dark:text-gray-500">-</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="px-5 py-4 whitespace-nowrap text-center">
                                            <?php
                                                $statusStyles = [
                                                    'hadir' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800/60',
                                                    'sakit' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800/60',
                                                    'izin' => 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800/60',
                                                    'alfa' => 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800/60',
                                                ];
                                                $statusVal = $row->status instanceof \App\Enums\AttendanceStatus ? $row->status->value : $row->status;
                                                $style = $statusStyles[$statusVal] ?? 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 border-gray-200 dark:border-gray-700';
                                                
                                                // Cek Pending Validation
                                                $isPending = ($statusVal != 'hadir' && ($row->validation_status instanceof \App\Enums\ValidationStatus ? $row->validation_status->value : $row->validation_status) == 'pending');
                                            ?>

                                            <div class="relative inline-block">
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full border <?php echo e($style); ?>">
                                                    <?php echo e(ucfirst($statusVal)); ?>

                                                </span>
                                                <?php if($isPending): ?>
                                                    <span class="absolute -top-1 -right-2 flex h-3 w-3">
                                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                                        <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500"></span>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </td>

                                        <td class="px-5 py-4 whitespace-nowrap text-right">
                                            <?php if($row->status == 'hadir'): ?>
                                                <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider flex items-center justify-end gap-1">
                                                    <i class="fas fa-check-double text-teal-500"></i> Auto
                                                </span>
                                            <?php else: ?>
                                                <?php if($row->validation_status == 'pending'): ?>
                                                    <div class="flex items-center justify-end gap-2">
                                                        <button x-data="" x-on:click="$dispatch('open-modal', 'modal-bukti-<?php echo e($row->id); ?>')" 
                                                            class="p-1.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-xl hover:bg-teal-50 dark:hover:bg-teal-900/50 hover:text-teal-600 transition border border-gray-200 dark:border-gray-600" title="Lihat Bukti">
                                                            <i class="fas fa-eye text-xs"></i>
                                                        </button>

                                                        <form action="<?php echo e(route('pembimbing_lapangan.attendance.validate', $row->id)); ?>" method="POST" class="inline">
                                                            <?php echo csrf_field(); ?>
                                                            <input type="hidden" name="status_validasi" value="approved">
                                                            <button type="submit" class="p-1.5 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 rounded-xl border border-emerald-200 dark:border-emerald-800/60 hover:bg-emerald-100 transition" title="Setujui">
                                                                <i class="fas fa-check text-xs"></i>
                                                            </button>
                                                        </form>

                                                        <button x-data="" x-on:click="$dispatch('open-modal', 'modal-tolak-<?php echo e($row->id); ?>')" 
                                                            class="p-1.5 bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 rounded-xl border border-rose-200 dark:border-rose-800/60 hover:bg-rose-100 transition" title="Tolak">
                                                            <i class="fas fa-times text-xs"></i>
                                                        </button>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="flex items-center justify-end gap-2">
                                                        <button x-data="" x-on:click="$dispatch('open-modal', 'modal-bukti-<?php echo e($row->id); ?>')" class="text-xs text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 underline decoration-dashed font-bold">
                                                            Detail
                                                        </button>
                                                        <?php if($row->validation_status == 'approved'): ?>
                                                            <span class="text-xs text-emerald-600 dark:text-emerald-400 font-bold flex items-center gap-1"><i class="fas fa-check-circle"></i> Valid</span>
                                                        <?php else: ?>
                                                            <span class="text-xs text-rose-600 dark:text-rose-400 font-bold flex items-center gap-1"><i class="fas fa-times-circle"></i> Ditolak</span>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>

                                    <?php if($row->proof_file): ?>
                                    <?php if (isset($component)) { $__componentOriginal9f64f32e90b9102968f2bc548315018c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f64f32e90b9102968f2bc548315018c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal','data' => ['name' => 'modal-bukti-'.e($row->id).'','focusable' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'modal-bukti-'.e($row->id).'','focusable' => true]); ?>
                                        <div class="p-6 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                                            <div class="flex justify-between items-center mb-4 border-b border-gray-100 dark:border-gray-700 pb-3">
                                                <h2 class="text-base font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                                                    <i class="fas fa-file-medical text-teal-600 dark:text-teal-400"></i> Bukti Pengajuan <?php echo e(ucfirst($row->status)); ?>

                                                </h2>
                                                <button x-on:click="$dispatch('close')" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300"><i class="fas fa-times"></i></button>
                                            </div>
                                            
                                            <div class="flex justify-center bg-gray-50 dark:bg-gray-900 rounded-2xl p-3 mb-4 border border-gray-200 dark:border-gray-700 w-full">
                                                <?php if(Str::endsWith(strtolower($row->proof_file), '.pdf')): ?>
                                                    <iframe src="<?php echo e(route('storage.access', ['type' => 'attendance', 'filename' => basename($row->proof_file)])); ?>" class="w-full h-[50vh] rounded-xl border-0"></iframe>
                                                <?php else: ?>
                                                    <img src="<?php echo e(route('storage.access', ['type' => 'attendance', 'filename' => basename($row->proof_file)])); ?>" class="max-h-[60vh] rounded-xl shadow-xs hover:scale-105 transition duration-300" alt="Bukti">
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="bg-teal-50/60 dark:bg-teal-950/40 p-4 rounded-2xl border border-teal-200 dark:border-teal-800/60">
                                                <p class="text-[10px] text-teal-700 dark:text-teal-300 font-bold uppercase mb-1">Keterangan Mahasiswa</p>
                                                <p class="text-teal-900 dark:text-teal-200 text-xs sm:text-sm italic font-medium">"<?php echo e($row->description); ?>"</p>
                                            </div>
                                            
                                            <div class="mt-6 flex justify-end">
                                                <?php if (isset($component)) { $__componentOriginal3b0e04e43cf890250cc4d85cff4d94af = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.secondary-button','data' => ['xOn:click' => '$dispatch(\'close\')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('secondary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['x-on:click' => '$dispatch(\'close\')']); ?>Tutup <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af)): ?>
<?php $attributes = $__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af; ?>
<?php unset($__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3b0e04e43cf890250cc4d85cff4d94af)): ?>
<?php $component = $__componentOriginal3b0e04e43cf890250cc4d85cff4d94af; ?>
<?php unset($__componentOriginal3b0e04e43cf890250cc4d85cff4d94af); ?>
<?php endif; ?>
                                            </div>
                                        </div>
                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $attributes = $__attributesOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__attributesOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $component = $__componentOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__componentOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>
                                    <?php endif; ?>

                                    <?php if (isset($component)) { $__componentOriginal9f64f32e90b9102968f2bc548315018c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f64f32e90b9102968f2bc548315018c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal','data' => ['name' => 'modal-tolak-'.e($row->id).'','focusable' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'modal-tolak-'.e($row->id).'','focusable' => true]); ?>
                                        <div class="p-6 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                                            <h2 class="text-base font-bold text-rose-600 dark:text-rose-400 mb-4 border-b border-gray-100 dark:border-gray-700 pb-3 flex items-center gap-2">
                                                <i class="fas fa-user-times"></i> Tolak Pengajuan <?php echo e(ucfirst($row->status)); ?>

                                            </h2>
                                            <form action="<?php echo e(route('pembimbing_lapangan.attendance.validate', $row->id)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="status_validasi" value="rejected">
                                                
                                                <div class="mb-4">
                                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Alasan Penolakan</label>
                                                    <textarea name="pembimbing_lapangan_note" rows="3" class="w-full border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 shadow-xs focus:border-rose-500 focus:ring-rose-500 text-xs font-bold" required placeholder="Contoh: Bukti surat dokter tidak jelas atau tidak menyantumkan tanggal..."></textarea>
                                                </div>
                                                
                                                <div class="mt-6 flex justify-end gap-3">
                                                    <?php if (isset($component)) { $__componentOriginal3b0e04e43cf890250cc4d85cff4d94af = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.secondary-button','data' => ['xOn:click' => '$dispatch(\'close\')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('secondary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['x-on:click' => '$dispatch(\'close\')']); ?>Batal <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af)): ?>
<?php $attributes = $__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af; ?>
<?php unset($__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3b0e04e43cf890250cc4d85cff4d94af)): ?>
<?php $component = $__componentOriginal3b0e04e43cf890250cc4d85cff4d94af; ?>
<?php unset($__componentOriginal3b0e04e43cf890250cc4d85cff4d94af); ?>
<?php endif; ?>
                                                    <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-xs transition">
                                                        Konfirmasi Tolak
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $attributes = $__attributesOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__attributesOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $component = $__componentOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__componentOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="<?php echo e($filterType !== 'harian' ? 6 : 5); ?>" class="px-6 py-16 text-center">
                                            <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                                <div class="h-16 w-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-3 border border-gray-200 dark:border-gray-700">
                                                    <i class="fas fa-clipboard-check text-3xl text-gray-400 dark:text-gray-500"></i>
                                                </div>
                                                <p class="font-bold text-gray-700 dark:text-gray-300 text-sm">Data absensi kosong</p>
                                                <p class="text-xs mt-1 text-gray-500 dark:text-gray-400">Tidak ada data absensi untuk tanggal/periode yang dipilih.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <?php if($attendances instanceof \Illuminate\Pagination\LengthAwarePaginator && $attendances->hasPages()): ?>
                        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                            <?php echo e($attendances->links()); ?>

                        </div>
                        <?php endif; ?>

                    </div>
                </div>

            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views\pembimbing_lapangan\attendance.blade.php ENDPATH**/ ?>