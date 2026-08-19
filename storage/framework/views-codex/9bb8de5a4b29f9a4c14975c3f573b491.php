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
                    <i class="fas fa-file-alt text-teal-600 dark:text-teal-400 text-lg"></i>
                </div>
                <?php echo e(__('Laporan Rekap Peserta')); ?>

            </h2>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="flex justify-between items-center mb-6 print:hidden max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="<?php echo e(route('dinas.laporan.hub')); ?>" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-xs">
                    <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                </div>
                Kembali ke Pusat Laporan
            </a>
        </div>
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center cursor-help transition hover:shadow-md" title="Jumlah seluruh lamaran peserta yang tercatat pada laporan.">
                    <div class="w-8 h-8 rounded-full bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center mx-auto mb-2 border border-teal-100 dark:border-teal-900/50">
                        <i class="fas fa-file-alt text-xs"></i>
                    </div>
                    <p class="text-xl font-black text-gray-800 dark:text-gray-100"><?php echo e($stats['total']); ?></p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Total Pendaftar</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center cursor-help transition hover:shadow-md" title="Jumlah peserta magang dengan status 'diterima' yang saat ini sedang aktif melakoni program magang di instansi.">
                    <div class="w-8 h-8 rounded-full bg-green-50 dark:bg-green-950/60 text-green-600 dark:text-green-400 flex items-center justify-center mx-auto mb-2 border border-green-100 dark:border-green-900/50">
                        <i class="fas fa-user-clock text-xs"></i>
                    </div>
                    <p class="text-xl font-black text-green-700 dark:text-green-400"><?php echo e($stats['aktif']); ?></p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Status Aktif</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center cursor-help transition hover:shadow-md" title="Jumlah alumni peserta magang yang telah menyelesaikan seluruh periode magang dan dinyatakan lulus (status 'selesai').">
                    <div class="w-8 h-8 rounded-full bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center mx-auto mb-2 border border-blue-100 dark:border-blue-900/50">
                        <i class="fas fa-graduation-cap text-xs"></i>
                    </div>
                    <p class="text-xl font-black text-blue-700 dark:text-blue-400"><?php echo e($stats['selesai']); ?></p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Selesai / Lulus</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center cursor-help transition hover:shadow-md" title="Jumlah lamaran pendaftaran magang yang masih dalam proses verifikasi atau menantikan keputusan instansi (status 'pending'/'menunggu').">
                    <div class="w-8 h-8 rounded-full bg-yellow-50 dark:bg-yellow-950/60 text-yellow-600 dark:text-yellow-400 flex items-center justify-center mx-auto mb-2 border border-yellow-100 dark:border-yellow-900/50">
                        <i class="fas fa-hourglass-half text-xs"></i>
                    </div>
                    <p class="text-xl font-black text-yellow-600 dark:text-yellow-400"><?php echo e($stats['pending']); ?></p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Pending</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center cursor-help transition hover:shadow-md" title="Jumlah berkas pendaftaran magang yang ditolak oleh instansi karena tidak memenuhi persyaratan atau kuota penuh.">
                    <div class="w-8 h-8 rounded-full bg-red-50 dark:bg-red-950/60 text-red-600 dark:text-red-400 flex items-center justify-center mx-auto mb-2 border border-red-100 dark:border-red-900/50">
                        <i class="fas fa-user-times text-xs"></i>
                    </div>
                    <p class="text-xl font-black text-red-700 dark:text-red-400"><?php echo e($stats['ditolak']); ?></p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Ditolak</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center cursor-help transition hover:shadow-md" title="Total akumulasi instansi perguruan tinggi atau sekolah unik asal peserta yang terdaftar pada laporan rekap ini.">
                    <div class="w-8 h-8 rounded-full bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mx-auto mb-2 border border-indigo-100 dark:border-indigo-900/50">
                        <i class="fas fa-university text-xs"></i>
                    </div>
                    <p class="text-xl font-black text-indigo-700 dark:text-indigo-400"><?php echo e($stats['total_campuses'] ?? $stats['total_kampus']); ?></p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Kampus Terlibat</p>
                </div>
            </div>

            
            <div class="bg-gradient-to-r from-teal-600 to-indigo-600 rounded-3xl p-6 text-white shadow-lg shadow-teal-600/20 flex flex-col sm:flex-row items-center gap-4 cursor-help" title="Ringkasan rekapitulasi akumulasi seluruh lamaran peserta dan total instansi pendidikan/kampus yang bermitra.">
                <div class="w-14 h-14 rounded-2xl bg-white/20 dark:bg-gray-800/30 backdrop-blur-sm flex items-center justify-center text-2xl flex-shrink-0 border border-white/20">
                    <i class="fas fa-file-contract"></i>
                </div>
                <div class="text-center sm:text-left flex-grow">
                    <p class="text-xs font-bold uppercase tracking-wider text-teal-100">Rekapitulasi Lamaran Peserta Magang</p>
                    <p class="text-xl font-black mt-0.5">Total <?php echo e($stats['total']); ?> Lamaran Masuk</p>
                    <p class="text-sm text-teal-100 font-medium">Terdapat <?php echo e($stats['total_kampus']); ?> instansi pendidikan/kampus terdaftar yang telah bermitra.</p>
                </div>
                <?php if($applications->count() > 0): ?>
                <div class="sm:ml-auto flex-shrink-0 flex gap-2">
                    <a href="<?php echo e(route('dinas.laporan.rekap.print', request()->query())); ?>" target="_blank" class="inline-flex items-center px-4 py-2.5 bg-white dark:bg-gray-800 text-teal-700 dark:text-teal-400 hover:text-teal-800 dark:hover:text-teal-300 rounded-xl hover:bg-teal-50 dark:hover:bg-gray-700 transition text-xs font-bold shadow-md border border-white/20 dark:border-gray-700" title="Download PDF">
                        <i class="fas fa-file-pdf mr-1.5 text-red-500"></i> PDF
                    </a>
                </div>
                <?php endif; ?>
            </div>

            
            <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700">
                <div class="flex justify-between items-center mb-4 border-b border-gray-100 dark:border-gray-700 pb-3">
                    <h3 class="text-gray-800 dark:text-gray-200 font-bold text-sm uppercase tracking-wide flex items-center gap-2">
                        <i class="fas fa-filter text-teal-600 dark:text-teal-400"></i> Filter Laporan
                    </h3>
                    <?php if(request()->anyFilled(['status', 'asal_instansi', 'start_date', 'end_date', 'sort'])): ?>
                        <a href="<?php echo e(route('dinas.laporan.rekap')); ?>" class="text-xs text-red-600 dark:text-red-400 hover:underline font-bold flex items-center gap-1">
                            <i class="fas fa-redo text-[10px]"></i> Reset Filter
                        </a>
                    <?php endif; ?>
                </div>

                <form action="<?php echo e(route('dinas.laporan.rekap')); ?>" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1.5">Status Peserta</label>
                        <select name="status" class="w-full border border-gray-300 dark:border-gray-700 rounded-xl text-xs focus:ring-teal-500 focus:border-teal-500 shadow-xs cursor-pointer bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 py-2">
                            <option value="" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100">Semua Status</option>
                            <option value="pending" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                            <option value="diterima" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" <?php echo e(request('status') == 'diterima' ? 'selected' : ''); ?>>Aktif (Sedang Magang)</option>
                            <option value="selesai" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" <?php echo e(request('status') == 'selesai' ? 'selected' : ''); ?>>Selesai / Lulus</option>
                            <option value="ditolak" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" <?php echo e(request('status') == 'ditolak' ? 'selected' : ''); ?>>Ditolak</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1.5">Asal Kampus / Sekolah</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 dark:text-gray-500">
                                <i class="fas fa-university text-xs"></i>
                            </span>
                            <input type="text" name="asal_instansi" value="<?php echo e(request('asal_instansi')); ?>" 
                                placeholder="Contoh: Universitas..."
                                class="w-full pl-9 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 rounded-xl text-xs focus:ring-teal-500 focus:border-teal-500 shadow-xs py-2">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1.5">Periode Magang</label>
                        <div class="grid grid-cols-2 gap-1.5">
                            <input type="date" name="start_date" value="<?php echo e(request('start_date')); ?>" 
                                class="w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 rounded-xl text-[11px] focus:ring-teal-500 focus:border-teal-500 shadow-xs px-2 py-1.5 [color-scheme:light] dark:[color-scheme:dark]" title="Dari Tanggal">
                            <input type="date" name="end_date" value="<?php echo e(request('end_date')); ?>" 
                                class="w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 rounded-xl text-[11px] focus:ring-teal-500 focus:border-teal-500 shadow-xs px-2 py-1.5 [color-scheme:light] dark:[color-scheme:dark]" title="Sampai Tanggal">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1.5">Urutkan</label>
                        <select name="sort" class="w-full border border-gray-300 dark:border-gray-700 rounded-xl text-xs focus:ring-teal-500 focus:border-teal-500 shadow-xs bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 py-2">
                            <option value="" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100">Terbaru (Default)</option>
                            <option value="name_asc" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" <?php echo e(request('sort') == 'name_asc' ? 'selected' : ''); ?>>Nama (A - Z)</option>
                            <option value="name_desc" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" <?php echo e(request('sort') == 'name_desc' ? 'selected' : ''); ?>>Nama (Z - A)</option>
                        </select>
                    </div>

                    <div>
                        <?php if (isset($component)) { $__componentOriginald411d1792bd6cc877d687758b753742c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald411d1792bd6cc877d687758b753742c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.primary-button','data' => ['class' => 'w-full justify-center py-2 text-xs']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('primary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-full justify-center py-2 text-xs']); ?>
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
                    </div>
                </form>
            </div>

            
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Daftar Rekapitulasi Lamaran</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Menampilkan data pendaftar magang beserta penempatan posisi, pembimbing lapangan, dan statusnya.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-100 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-12 whitespace-nowrap">No</th>
                                <th class="px-5 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap min-w-[200px]">Peserta & Kampus</th>
                                <th class="px-5 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap min-w-[200px]">Posisi & Pembimbing</th>
                                <th class="px-5 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap min-w-[200px]">Periode Magang</th>
                                <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-28 whitespace-nowrap">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700/60 text-sm">
                            <?php $__empty_1 = true; $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-teal-50/15 dark:hover:bg-gray-900/60 transition duration-150">
                                <td class="px-5 py-4 text-xs text-gray-400 dark:text-gray-500 text-center font-bold">
                                    <?php echo e($loop->iteration); ?>

                                </td>
                                
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-full bg-gradient-to-br from-teal-100 to-teal-200 dark:from-teal-950/60 dark:to-teal-900/60 flex items-center justify-center text-teal-700 dark:text-teal-300 font-bold text-xs border border-teal-300 dark:border-teal-800/60 flex-shrink-0 shadow-xs">
                                            <?php echo e(strtoupper(substr($app->user->name, 0, 2))); ?>

                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-bold text-gray-900 dark:text-gray-100 truncate"><?php echo e($app->user->name); ?></div>
                                            <p class="text-[10px] text-gray-500 dark:text-gray-400 font-semibold truncate"><?php echo e($app->user->asal_instansi ?? '-'); ?></p>
                                            <div class="flex items-center gap-2 text-[9px] text-gray-400 dark:text-gray-500 mt-1 flex-wrap font-medium">
                                                <span><?php echo e($app->user->email); ?></span>
                                                <?php if($app->user->phone): ?>
                                                <span>•</span>
                                                <span><?php echo e($app->user->phone); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="text-xs font-bold text-gray-800 dark:text-gray-200"><?php echo e($app->position->judul_posisi); ?></div>
                                    <?php if($app->pembimbing_lapangan): ?>
                                        <div class="text-[10px] text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-1">
                                            <i class="fas fa-chalkboard-teacher text-[9px] text-gray-400 dark:text-gray-500"></i>
                                            PL: <span class="font-bold text-gray-600 dark:text-gray-300"><?php echo e($app->pembimbing_lapangan->name); ?></span>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-[9px] text-gray-400 dark:text-gray-500 italic mt-1 block">PL: Belum ditentukan</span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300 flex items-center gap-1.5">
                                            <i class="far fa-calendar text-gray-400 dark:text-gray-500"></i>
                                            <?php echo e(\Carbon\Carbon::parse($app->tanggal_mulai)->format('d M Y')); ?> 
                                            <i class="fas fa-arrow-right text-[9px] text-gray-300 dark:text-gray-600"></i> 
                                            <?php echo e(\Carbon\Carbon::parse($app->tanggal_selesai)->format('d M Y')); ?>

                                        </span>
                                        <span class="text-[9px] text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/60 border border-teal-100 dark:border-teal-900/40 px-2 py-0.5 rounded-md w-fit font-bold">
                                            <?php echo e(\Carbon\Carbon::parse($app->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($app->tanggal_selesai))); ?> Hari
                                        </span>
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-center">
                                    <?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['status' => $app->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($app->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                        <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-3 border border-gray-200 dark:border-gray-700">
                                            <i class="fas fa-search text-2xl text-gray-400 dark:text-gray-500"></i>
                                        </div>
                                        <p class="text-gray-900 dark:text-gray-100 font-bold">Data tidak ditemukan</p>
                                        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Coba sesuaikan filter pencarian Anda.</p>
                                        <a href="<?php echo e(route('dinas.laporan.rekap')); ?>" class="mt-4 text-teal-600 dark:text-teal-400 hover:underline text-sm font-bold">
                                            Reset Semua Filter
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
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
<?php endif; ?>
<?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views/admin_instansi/laporan/rekap.blade.php ENDPATH**/ ?>