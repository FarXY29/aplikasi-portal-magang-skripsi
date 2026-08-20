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
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <?php echo e(__('Detail Lowongan Magang')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <?php
                $cleanDinas = trim(str_ireplace(['dinas', 'badan', 'kantor', 'bagian', 'sekretariat'], '', $position->instansi->nama_dinas ?? ''));
                $initials = strtoupper(substr($cleanDinas, 0, 2));
                $isOpen = $position->status === 'buka' && $position->kuota > 0;
            ?>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-[2rem] border border-slate-100 dark:border-gray-700">
                <!-- Header -->
                <div class="p-6 sm:p-8 border-b border-slate-100 dark:border-gray-700 flex items-start gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-teal-500 to-emerald-600 text-white flex items-center justify-center font-black text-xl shadow-lg shadow-teal-500/20 shrink-0">
                        <?php echo e($initials); ?>

                    </div>
                    <div class="min-w-0 flex-grow">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <?php if($isOpen): ?>
                                <span class="inline-flex items-center gap-1.5 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60 text-[10px] px-2.5 py-1 rounded-lg font-black uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400"></span>
                                    Buka
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1.5 bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-300 border border-rose-200/60 dark:border-rose-800/60 text-[10px] px-2.5 py-1 rounded-lg font-black uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 dark:bg-rose-400"></span>
                                    Tutup
                                </span>
                            <?php endif; ?>
                            <?php if($isOpen && $position->kuota < 3): ?>
                                <span class="inline-flex items-center gap-1 bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-300 border border-rose-200/60 dark:border-rose-800/60 text-[10px] px-2.5 py-1 rounded-lg font-black uppercase tracking-wider">
                                    🔥 Sisa <?php echo e($position->kuota); ?> Kursi
                                </span>
                            <?php endif; ?>
                        </div>
                        <h1 class="text-2xl font-black text-gray-900 dark:text-gray-100 leading-tight"><?php echo e($position->judul_posisi); ?></h1>
                        <p class="text-sm font-bold text-teal-600 dark:text-teal-400 mt-1 flex items-center gap-2">
                            <i class="fas fa-building text-xs"></i> <?php echo e($position->instansi->nama_dinas ?? 'Instansi Tidak Diketahui'); ?>

                        </p>
                        <?php if(!empty($position->instansi->alamat)): ?>
                            <p class="text-xs text-gray-500 dark:text-gray-400 flex items-start gap-2 mt-1.5 leading-relaxed font-medium">
                                <i class="fas fa-map-marker-alt text-rose-500 shrink-0 mt-0.5"></i>
                                <span><?php echo e($position->instansi->alamat); ?></span>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quick Info Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 p-6 sm:p-8">
                    <div class="bg-slate-50 dark:bg-gray-900 border border-slate-100 dark:border-gray-700 rounded-2xl p-3.5 flex flex-col justify-between">
                        <span class="text-[9px] font-extrabold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Kapasitas Kursi</span>
                        <span class="text-xs font-bold text-slate-800 dark:text-gray-100 mt-1 flex items-center gap-1.5">
                            <i class="fas fa-users text-teal-600 dark:text-teal-400 text-[10px]"></i>
                            <span><?php echo e($position->kuota); ?> Posisi Tersedia</span>
                        </span>
                    </div>

                    <div class="bg-slate-50 dark:bg-gray-900 border border-slate-100 dark:border-gray-700 rounded-2xl p-3.5 flex flex-col justify-between">
                        <span class="text-[9px] font-extrabold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Kualifikasi Utama</span>
                        <span class="text-xs font-bold text-slate-800 dark:text-gray-100 mt-1 truncate flex items-center gap-1.5" title="<?php echo e($position->required_major ?? 'Semua Jurusan'); ?>">
                            <i class="fas fa-graduation-cap text-teal-600 dark:text-teal-400 text-[10px]"></i>
                            <span class="truncate"><?php echo e($position->required_major ?? 'Semua Jurusan'); ?></span>
                        </span>
                    </div>

                    <div class="bg-slate-50 dark:bg-gray-900 border border-slate-100 dark:border-gray-700 rounded-2xl p-3.5 flex flex-col justify-between">
                        <span class="text-[9px] font-extrabold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Batas Pendaftaran</span>
                        <span class="text-xs font-bold text-slate-800 dark:text-gray-100 mt-1 flex items-center gap-1.5">
                            <i class="fas fa-calendar-alt text-teal-600 dark:text-teal-400 text-[10px]"></i>
                            <span><?php echo e($position->batas_daftar ? \Carbon\Carbon::parse($position->batas_daftar)->translatedFormat('d F Y') : 'Ditentukan Admin'); ?></span>
                        </span>
                    </div>

                    <div class="bg-slate-50 dark:bg-gray-900 border border-slate-100 dark:border-gray-700 rounded-2xl p-3.5 flex flex-col justify-between">
                        <span class="text-[9px] font-extrabold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Status Lowongan</span>
                        <span class="text-xs font-bold <?php echo e($isOpen ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400'); ?> mt-1 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full <?php echo e($isOpen ? 'bg-emerald-500' : 'bg-rose-500'); ?>"></span>
                            <?php echo e($isOpen ? 'Buka' : 'Tutup'); ?>

                        </span>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="px-6 sm:px-8 pb-8 space-y-3">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-gray-100 uppercase tracking-wider flex items-center gap-2">
                        <i class="fas fa-file-alt text-teal-600 dark:text-teal-400"></i> Deskripsi Pekerjaan & Persyaratan
                    </h3>
                    <div class="prose prose-sm dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 bg-slate-50 dark:bg-gray-900 p-5 rounded-2xl border border-slate-100 dark:border-gray-700 text-sm font-medium leading-relaxed">
                        <?php echo $position->deskripsi ? nl2br(e($position->deskripsi)) : '<p>Tidak ada deskripsi.</p>'; ?>

                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="px-6 sm:px-8 py-5 border-t border-slate-100 dark:border-gray-700 bg-slate-50 dark:bg-gray-900 flex items-center justify-end gap-3">
                    <a href="<?php echo e(route('home')); ?>#lowongan" class="px-5 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-slate-700 dark:text-gray-200 rounded-xl font-bold transition text-xs uppercase tracking-wider">
                        Kembali
                    </a>

                    <?php if(auth()->guard()->check()): ?>
                        <?php if(auth()->user()->hasPortalRole('peserta')): ?>
                            <?php if($isOpen): ?>
                                <a href="<?php echo e(route('peserta.daftar.form', $position->id)); ?>" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-xl font-bold shadow-md transition text-xs uppercase tracking-wider flex items-center gap-2">
                                    Daftar Sekarang <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                            <?php else: ?>
                                <button disabled class="bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 px-6 py-3 rounded-xl font-bold cursor-not-allowed text-xs uppercase tracking-wider">
                                    Lowongan Ditutup
                                </button>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-xl font-bold shadow-md transition text-xs uppercase tracking-wider flex items-center gap-2">
                            Login untuk Daftar <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    <?php endif; ?>
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
<?php endif; ?><?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views\public\lowongan\show.blade.php ENDPATH**/ ?>