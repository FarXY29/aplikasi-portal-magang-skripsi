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
                    <i class="fas fa-graduation-cap text-teal-600 dark:text-teal-400 text-lg"></i>
                </div>
                <?php echo e(__('Demografi Kualifikasi Jurusan')); ?>

            </h2>
            <div class="text-xs font-bold text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 px-4 py-2 rounded-2xl shadow-xs border border-gray-200 dark:border-gray-700">
                Total Kualifikasi: <span class="font-black text-teal-600 dark:text-teal-400 font-mono"><?php echo e($jurusans->count()); ?></span> Jurusan
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans" x-data="{ searchQuery: <?php echo \Illuminate\Support\Js::from(request('q'))->toHtml() ?> }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            
            <div class="flex justify-between items-center print:hidden">
                <a href="<?php echo e(route('admin.laporan.hub')); ?>" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-xs">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Pusat Laporan
                </a>
                
                <?php if($jurusans->count() > 0): ?>
                <div class="flex flex-wrap gap-2">
                    <a href="<?php echo e(route('admin.laporan.demografi_jurusan.print', request()->query())); ?>" target="_blank" class="flex-1 sm:flex-none px-3.5 py-1.5 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 hover:bg-teal-50 dark:hover:bg-gray-700 rounded-xl font-bold text-xs transition shadow-xs flex items-center justify-center gap-1.5" title="Download PDF">
                        <i class="fas fa-file-pdf text-rose-500"></i> PDF
                    </a>
                </div>
                <?php endif; ?>
            </div>

            
            <?php
                $totalLowongan = $jurusans->sum('total_lowongan');
                $totalKuota = $jurusans->sum('total_kuota');
                $topJurusan = $jurusans->first() ? $jurusans->first()->required_major : '-';
                $maxKuota = $jurusans->max('total_kuota') ?: 1;
            ?>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 shadow-xs border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition cursor-help" title="Jumlah kualifikasi atau jurusan yang memiliki data lowongan pada laporan.">
                    <div class="w-9 h-9 rounded-2xl bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center mx-auto mb-3 border border-teal-100 dark:border-teal-800/60 shadow-xs">
                        <i class="fas fa-graduation-cap text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-gray-800 dark:text-gray-100 font-mono tracking-tight"><?php echo e(number_format($jurusans->count())); ?></p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Total Kualifikasi</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 shadow-xs border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition cursor-help" title="Jumlah akumulasi seluruh posisi lowongan magang yang dipublikasikan oleh instansi Pemerintah Kota Banjarmasin.">
                    <div class="w-9 h-9 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center mx-auto mb-3 border border-blue-100 dark:border-blue-800/60 shadow-xs">
                        <i class="fas fa-briefcase text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-blue-600 dark:text-blue-400 font-mono tracking-tight"><?php echo e(number_format($totalLowongan)); ?></p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Lowongan Terbuka</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 shadow-xs border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition cursor-help" title="Total kapasitas daya tampung kursi peserta magang yang disediakan dari seluruh formasi lowongan terbuka.">
                    <div class="w-9 h-9 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mx-auto mb-3 border border-indigo-100 dark:border-indigo-800/60 shadow-xs">
                        <i class="fas fa-chair text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-indigo-600 dark:text-indigo-400 font-mono tracking-tight"><?php echo e(number_format($totalKuota)); ?></p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Total Kuota Kursi</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 shadow-xs border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition cursor-help bg-gradient-to-br from-teal-50/50 via-white to-indigo-50/30 dark:from-teal-950/20 dark:via-gray-800 dark:to-indigo-950/20" title="Kualifikasi jurusan dengan alokasi total kuota penerimaan magang terbanyak di Kota Banjarmasin.">
                    <div class="w-9 h-9 rounded-2xl bg-teal-600 text-white flex items-center justify-center mx-auto mb-3 shadow-xs">
                        <i class="fas fa-star text-xs"></i>
                    </div>
                    <p class="text-xs font-extrabold text-teal-600 dark:text-teal-400 truncate w-full px-1" title="<?php echo e($topJurusan); ?>"><?php echo e($topJurusan); ?></p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-2">Jurusan Terfavorit</p>
                </div>
            </div>

            
            <div class="w-full space-y-6">
                <div class="bg-white dark:bg-gray-800 shadow-xs rounded-3xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                    
                    
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gray-50/50 dark:bg-gray-900/50">
                        <div>
                            <h3 class="font-extrabold text-gray-900 dark:text-gray-100 text-lg flex items-center gap-2.5">
                                <i class="fas fa-graduation-cap text-teal-600 dark:text-teal-400"></i>
                                Daftar Pemeringkatan Jurusan Paling Dicari
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-medium">Kualifikasi jurusan terurut dari alokasi kuota terbanyak ke terendah.</p>
                        </div>

                        
                        <form method="GET" action="<?php echo e(route('admin.laporan.demografi_jurusan')); ?>" class="flex flex-col sm:flex-row gap-2.5 items-center w-full sm:w-auto">
                            <div class="relative w-full sm:w-64">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 dark:text-gray-500 pointer-events-none">
                                    <i class="fas fa-search text-xs"></i>
                                </span>
                                <input type="text" name="q" value="<?php echo e(request('q')); ?>" x-model="searchQuery"
                                    placeholder="Cari nama jurusan..."
                                    class="w-full pl-9 py-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 rounded-xl text-xs font-bold focus:ring-teal-500 focus:border-teal-500 shadow-xs">
                            </div>

                            <div class="flex gap-2 w-full sm:w-auto">
                                <button type="submit" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 w-full sm:w-auto">
                                    <i class="fas fa-filter text-xs"></i> Filter
                                </button>
                                <?php if(request()->filled('q')): ?>
                                    <a href="<?php echo e(route('admin.laporan.demografi_jurusan')); ?>" class="px-3 py-2 border border-gray-300 dark:border-gray-700 text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl font-bold text-xs shadow-xs transition flex items-center justify-center">
                                        Reset
                                    </a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>

                    
                    <div class="hidden md:block overflow-x-auto max-h-[650px] overflow-y-auto">
                        <table class="w-full divide-y divide-gray-100 dark:divide-gray-700 border-collapse">
                            <thead class="bg-gray-50 dark:bg-gray-900 sticky top-0 z-20">
                                <tr>
                                    <th class="px-4 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-14">Rank</th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider min-w-[220px] max-w-[320px]">Jurusan / Kualifikasi</th>
                                    <th class="px-4 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-40">Lowongan Terkait</th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Visual Proporsi Kuota</th>
                                    <th class="px-4 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-40">Total Kuota Kursi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700/60 text-sm">
                                <?php $__empty_1 = true; $__currentLoopData = $jurusans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $jurusan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-teal-50/15 dark:hover:bg-teal-950/20 transition group"
                                    x-show="!searchQuery || <?php echo \Illuminate\Support\Js::from(strtolower((string) $jurusan->required_major))->toHtml() ?>.includes(searchQuery.toLowerCase())">
                                    <td class="px-4 py-4 text-center">
                                        <?php if($index == 0): ?>
                                            <div class="w-7 h-7 rounded-full bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center mx-auto shadow-xs border border-amber-200 dark:border-amber-800/50">
                                                <i class="fas fa-crown text-xs"></i>
                                            </div>
                                        <?php elseif($index == 1): ?>
                                            <div class="w-7 h-7 rounded-full bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400 flex items-center justify-center mx-auto border border-gray-300 dark:border-gray-700 font-bold text-xs">2</div>
                                        <?php elseif($index == 2): ?>
                                            <div class="w-7 h-7 rounded-full bg-amber-100/60 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 flex items-center justify-center mx-auto border border-amber-200 dark:border-amber-800/50 font-bold text-xs">3</div>
                                        <?php else: ?>
                                            <span class="text-gray-400 dark:text-gray-500 font-bold text-xs">#<?php echo e($index + 1); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 min-w-[220px] max-w-[320px]">
                                        <div class="flex items-center gap-3">
                                            <div class="h-9 w-9 rounded-full bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 font-black border border-teal-200 dark:border-teal-800/60 text-xs flex-shrink-0 flex items-center justify-center">
                                                <i class="fas fa-graduation-cap"></i>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="font-bold text-gray-900 dark:text-gray-100 leading-snug line-clamp-2" title="<?php echo e($jurusan->required_major); ?>"><?php echo e($jurusan->required_major); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="px-3 py-1 bg-blue-50 dark:bg-blue-950/60 border border-blue-200 dark:border-blue-800/60 text-blue-700 dark:text-blue-300 rounded-full font-bold text-xs inline-block">
                                            <strong class="font-mono"><?php echo e($jurusan->total_lowongan); ?></strong> Lowongan
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <?php
                                                $percentage = round(($jurusan->total_kuota / $maxKuota) * 100);
                                            ?>
                                            <div class="w-full bg-gray-100 dark:bg-gray-900 h-2 rounded-full overflow-hidden border border-transparent dark:border-gray-700">
                                                <div class="bg-gradient-to-r from-teal-500 to-indigo-500 h-2 rounded-full" style="width: <?php echo e($percentage); ?>%"></div>
                                            </div>
                                            <span class="text-[10px] font-bold font-mono text-gray-500 dark:text-gray-400 whitespace-nowrap"><?php echo e($percentage); ?>%</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="font-black text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/60 border border-teal-200 dark:border-teal-800/60 px-3.5 py-1 rounded-full text-xs inline-block font-mono">
                                            <?php echo e($jurusan->total_kuota); ?> Kursi
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-3 text-gray-400 dark:text-gray-500 border border-gray-200 dark:border-gray-700">
                                                <i class="fas fa-search text-2xl"></i>
                                            </div>
                                            <p class="text-gray-900 dark:text-gray-100 font-bold">Data jurusan tidak ditemukan</p>
                                        </div>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    
                    <div class="md:hidden divide-y divide-gray-100 dark:divide-gray-700">
                        <?php $__empty_1 = true; $__currentLoopData = $jurusans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $jurusan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="p-4 space-y-3.5"
                            x-show="!searchQuery || <?php echo \Illuminate\Support\Js::from(strtolower((string) $jurusan->required_major))->toHtml() ?>.includes(searchQuery.toLowerCase())">
                            
                            <div class="flex items-center gap-3">
                                <?php if($index == 0): ?>
                                    <div class="w-7 h-7 rounded-full bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center flex-shrink-0 shadow-xs border border-amber-200 dark:border-amber-800/50">
                                        <i class="fas fa-crown text-xs"></i>
                                    </div>
                                <?php elseif($index == 1): ?>
                                    <div class="w-7 h-7 rounded-full bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400 flex items-center justify-center flex-shrink-0 border border-gray-300 dark:border-gray-700 font-bold text-xs">2</div>
                                <?php elseif($index == 2): ?>
                                    <div class="w-7 h-7 rounded-full bg-amber-100/60 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 flex items-center justify-center flex-shrink-0 border border-amber-200 dark:border-amber-800/50 font-bold text-xs">3</div>
                                <?php else: ?>
                                    <span class="text-gray-400 dark:text-gray-500 font-bold text-xs flex-shrink-0">#<?php echo e($index + 1); ?></span>
                                <?php endif; ?>
                                <div class="h-9 w-9 rounded-full bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 font-black border border-teal-200 dark:border-teal-800/60 text-xs flex-shrink-0 flex items-center justify-center">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="font-bold text-gray-900 dark:text-gray-100 leading-snug line-clamp-2" title="<?php echo e($jurusan->required_major); ?>"><?php echo e($jurusan->required_major); ?></div>
                                </div>
                            </div>

                            
                            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-2xl p-3.5 space-y-3 border border-gray-100 dark:border-gray-700">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="px-3 py-1 bg-blue-50 dark:bg-blue-950/60 border border-blue-200 dark:border-blue-800/60 text-blue-700 dark:text-blue-300 rounded-full font-bold text-xs inline-block">
                                        <strong class="font-mono"><?php echo e($jurusan->total_lowongan); ?></strong> Lowongan
                                    </span>
                                    <span class="font-black text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/60 border border-teal-200 dark:border-teal-800/60 px-3.5 py-1 rounded-full text-xs inline-block font-mono">
                                        <?php echo e($jurusan->total_kuota); ?> Kursi
                                    </span>
                                </div>
                                <?php
                                    $percentage = round(($jurusan->total_kuota / $maxKuota) * 100);
                                ?>
                                <div class="flex items-center gap-3">
                                    <div class="w-full bg-gray-100 dark:bg-gray-900 h-2 rounded-full overflow-hidden border border-transparent dark:border-gray-700">
                                        <div class="bg-gradient-to-r from-teal-500 to-indigo-500 h-2 rounded-full" style="width: <?php echo e($percentage); ?>%"></div>
                                    </div>
                                    <span class="text-[10px] font-bold font-mono text-gray-500 dark:text-gray-400 whitespace-nowrap"><?php echo e($percentage); ?>%</span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="p-10 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-3 text-gray-400 dark:text-gray-500 border border-gray-200 dark:border-gray-700">
                                    <i class="fas fa-search text-2xl"></i>
                                </div>
                                <p class="text-gray-900 dark:text-gray-100 font-bold">Data jurusan tidak ditemukan</p>
                            </div>
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
<?php endif; ?>
<?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views/admin_kota/laporan/demografi_jurusan.blade.php ENDPATH**/ ?>