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
                <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-950/60 flex items-center justify-center border border-blue-200 dark:border-blue-800/60">
                    <i class="fas fa-chart-line text-blue-600 dark:text-blue-400 text-lg"></i>
                </div>
                <?php echo e(__('Laporan Kinerja Peserta')); ?>

            </h2>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="flex justify-between items-center mb-6 print:hidden max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="<?php echo e(route('dinas.laporan.hub')); ?>" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition">
                <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-blue-500 dark:group-hover:border-blue-400 shadow-xs">
                    <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400"></i>
                </div>
                Kembali ke Pusat Laporan
            </a>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center cursor-help transition hover:shadow-md" title="Total seluruh peserta magang (aktif dan selesai) yang tercatat dalam laporan kinerja instansi.">
                    <div class="w-10 h-10 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center mx-auto mb-2.5 border border-blue-100 dark:border-blue-900/50">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <p class="text-2xl font-black text-gray-800 dark:text-gray-100"><?php echo e($stats['total_peserta']); ?></p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Total Peserta</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center cursor-help transition hover:shadow-md" title="Jumlah peserta magang yang saat ini sedang aktif menjalankan kegiatan magang (status 'diterima').">
                    <div class="w-10 h-10 rounded-2xl bg-green-50 dark:bg-green-950/60 text-green-600 dark:text-green-400 flex items-center justify-center mx-auto mb-2.5 border border-green-100 dark:border-green-900/50">
                        <i class="fas fa-user-clock"></i>
                    </div>
                    <p class="text-2xl font-black text-green-700 dark:text-green-400"><?php echo e($stats['aktif']); ?></p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Peserta Aktif</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center cursor-help transition hover:shadow-md" title="Jumlah peserta magang yang telah tuntas menyelesaikan seluruh program magang dan dinyatakan lulus.">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mx-auto mb-2.5 border border-indigo-100 dark:border-indigo-900/50">
                        <i class="fas fa-flag-checkered"></i>
                    </div>
                    <p class="text-2xl font-black text-indigo-700 dark:text-indigo-400"><?php echo e($stats['selesai']); ?></p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Selesai / Lulus</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center cursor-help transition hover:shadow-md" title="Rata-rata persentase presensi seluruh peserta. Rumus per peserta: (Jumlah Hari Hadir / Total Hari Presensi) x 100%, lalu dirata-ratakan seluruh peserta.">
                    <div class="w-10 h-10 rounded-2xl bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center mx-auto mb-2.5 border border-teal-100 dark:border-teal-900/50">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <p class="text-2xl font-black text-teal-700 dark:text-teal-400"><?php echo e($stats['avg_kehadiran']); ?>%</p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Avg Kehadiran</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center cursor-help transition hover:shadow-md" title="Rata-rata persentase validasi logbook peserta. Rumus per peserta: (Jumlah Jurnal Disetujui / Total Jurnal Diinput) x 100%, lalu dirata-ratakan.">
                    <div class="w-10 h-10 rounded-2xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center mx-auto mb-2.5 border border-purple-100 dark:border-purple-900/50">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <p class="text-2xl font-black text-purple-700 dark:text-purple-400"><?php echo e($stats['avg_logbook']); ?>%</p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Avg Logbook</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center cursor-help transition hover:shadow-md" title="Rata-rata nilai akhir evaluasi peserta yang telah lulus. Rumus Nilai Peserta: (Nilai Teknis + Nilai Disiplin + Nilai Perilaku) / 3, lalu dirata-ratakan.">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center mx-auto mb-2.5 border border-amber-100 dark:border-amber-900/50">
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="text-2xl font-black text-amber-600 dark:text-amber-400"><?php echo e($stats['avg_nilai'] > 0 ? $stats['avg_nilai'] : '-'); ?></p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Avg Nilai Lulus</p>
                </div>
            </div>

            
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-6 text-white shadow-lg shadow-blue-600/20 flex flex-col sm:flex-row items-center gap-4 cursor-help" title="Scorecard ringkasan performa presensi, validasi jurnal, dan rerata nilai akhir seluruh peserta magang.">
                <div class="w-14 h-14 rounded-2xl bg-white/20 dark:bg-gray-800/30 backdrop-blur-sm flex items-center justify-center text-2xl flex-shrink-0 border border-white/20">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="text-center sm:text-left flex-grow">
                    <p class="text-xs font-bold uppercase tracking-wider text-blue-100">Scorecard Kinerja Peserta Dinas</p>
                    <p class="text-xl font-black mt-0.5">Rata-rata Kehadiran <?php echo e($stats['avg_kehadiran']); ?>%</p>
                    <p class="text-sm text-blue-100 font-medium">Tingkat kepatuhan validasi logbook peserta mencapai <?php echo e($stats['avg_logbook']); ?>% dari keseluruhan peserta aktif/selesai.</p>
                </div>
                <?php if($kinerja->count() > 0): ?>
                <div class="sm:ml-auto flex-shrink-0 flex gap-2">
                    <a href="<?php echo e(route('dinas.laporan.kinerja_peserta.print')); ?>" target="_blank" class="inline-flex items-center px-4 py-2.5 bg-white dark:bg-gray-800 text-blue-700 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 rounded-xl hover:bg-blue-50 dark:hover:bg-gray-700 transition text-xs font-bold shadow-md border border-white/20 dark:border-gray-700" title="Download PDF">
                        <i class="fas fa-file-pdf mr-1.5 text-red-500"></i> PDF
                    </a>
                </div>
                <?php endif; ?>
            </div>

            
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <i class="fas fa-user-graduate text-blue-500 dark:text-blue-400"></i>
                        Scorecard Performa Peserta Magang
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Klik baris peserta untuk melihat rincian absensi harian, daftar logbook, serta rincian penilaian akhir.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-100 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-12">No</th>
                                <th class="px-5 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Peserta & Kampus</th>
                                <th class="px-5 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Posisi Magang</th>
                                <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kehadiran</th>
                                <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Validasi Logbook</th>
                                <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nilai Akhir</th>
                                <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-12"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700/60 text-sm" x-data="{ openRow: null }">
                            <?php $__empty_1 = true; $__currentLoopData = $kinerja; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-blue-50/15 dark:hover:bg-blue-900/20 transition cursor-pointer" @click="openRow = openRow === <?php echo e($loop->index); ?> ? null : <?php echo e($loop->index); ?>">
                                <td class="px-5 py-4 text-xs text-gray-400 dark:text-gray-500 text-center font-bold"><?php echo e($loop->iteration); ?></td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-300 flex items-center justify-center text-sm font-black border border-blue-200 dark:border-blue-800/60 flex-shrink-0 shadow-xs">
                                            <?php echo e(strtoupper(substr($app->user->name, 0, 1))); ?>

                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900 dark:text-gray-100"><?php echo e($app->user->name); ?></p>
                                            <div class="flex flex-wrap items-center gap-x-2 text-[10px] text-gray-500 dark:text-gray-400 font-semibold mt-0.5">
                                                <span><?php echo e($app->user->asal_instansi ?? '-'); ?></span>
                                                <span>•</span>
                                                <?php if($app->status?->value == 'diterima'): ?>
                                                     <span class="inline-flex px-1.5 py-0.5 rounded text-[8px] font-black bg-green-100 dark:bg-green-950/60 text-green-700 dark:text-green-300 uppercase border border-green-200 dark:border-green-800/60">Aktif</span>
                                                 <?php elseif($app->status?->value == 'selesai'): ?>
                                                     <span class="inline-flex px-1.5 py-0.5 rounded text-[8px] font-black bg-blue-100 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 uppercase border border-blue-200 dark:border-blue-800/60">Selesai</span>
                                                 <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-xs text-gray-800 dark:text-gray-200 font-bold">
                                    <?php echo e($app->position->judul_posisi); ?>

                                </td>
                                <td class="px-5 py-4 text-center">
                                    <?php
                                        $attRate = $app->attendance_rate;
                                        $attColor = $attRate >= 80 ? 'text-green-700 dark:text-green-300 bg-green-50 dark:bg-green-950/60 border border-green-200 dark:border-green-800/60' : ($attRate >= 50 ? 'text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-800/60' : 'text-rose-700 dark:text-rose-300 bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800/60');
                                    ?>
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-black <?php echo e($attColor); ?>">
                                        <?php echo e(round($attRate, 1)); ?>%
                                    </span>
                                    <div class="text-[9px] text-gray-400 dark:text-gray-500 mt-1 font-semibold"><?php echo e($app->attendances->where('status', 'hadir')->count()); ?>/<?php echo e($app->attendances->count()); ?> hari</div>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <?php
                                        $logRate = $app->log_rate;
                                        $logColor = $logRate >= 80 ? 'text-green-700 dark:text-green-300 bg-green-50 dark:bg-green-950/60 border border-green-200 dark:border-green-800/60' : ($logRate >= 50 ? 'text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-800/60' : 'text-rose-700 dark:text-rose-300 bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800/60');
                                    ?>
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-black <?php echo e($logColor); ?>">
                                        <?php echo e(round($logRate, 1)); ?>%
                                    </span>
                                    <div class="text-[9px] text-gray-400 dark:text-gray-500 mt-1 font-semibold"><?php echo e($app->logs->where('status_validasi', 'disetujui')->count()); ?> disetujui</div>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <?php if($app->avg_nilai > 0): ?>
                                        <span class="text-xs font-black text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 px-3 py-1 rounded-full inline-block">
                                            <?php echo e(round($app->avg_nilai, 1)); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-400 dark:text-gray-500 italic">Belum dinilai</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <i class="fas fa-chevron-down text-gray-400 dark:text-gray-500 text-xs transition-transform duration-200" :class="openRow === <?php echo e($loop->index); ?> ? 'rotate-180 text-blue-600 dark:text-blue-400' : ''"></i>
                                </td>
                            </tr>
                            
                            
                            <tr x-show="openRow === <?php echo e($loop->index); ?>" x-transition.opacity x-cloak>
                                <td colspan="7" class="px-5 py-4 bg-gray-50/60 dark:bg-gray-900/60 border-y border-gray-100 dark:border-gray-700">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                        
                                        
                                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-4 shadow-xs space-y-3">
                                            <h4 class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider flex items-center gap-2 border-b pb-2 border-gray-100 dark:border-gray-700">
                                                <i class="fas fa-calendar-alt text-teal-600 dark:text-teal-400"></i> Rincian Absensi
                                            </h4>
                                            <div class="space-y-1.5 text-xs text-gray-700 dark:text-gray-300">
                                                <div class="flex justify-between">
                                                    <span>Hadir:</span>
                                                    <span class="font-bold text-gray-800 dark:text-gray-200"><?php echo e($app->attendances->where('status', 'hadir')->count()); ?> hari</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span>Sakit:</span>
                                                    <span class="font-bold text-gray-800 dark:text-gray-200"><?php echo e($app->attendances->where('status', 'sakit')->count()); ?> hari</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span>Izin:</span>
                                                    <span class="font-bold text-gray-800 dark:text-gray-200"><?php echo e($app->attendances->where('status', 'izin')->count()); ?> hari</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span>Tanpa Keterangan (Alfa):</span>
                                                    <span class="font-bold text-gray-800 dark:text-gray-200"><?php echo e($app->attendances->where('status', 'alfa')->count()); ?> hari</span>
                                                </div>
                                                <div class="flex justify-between border-t pt-1.5 border-gray-100 dark:border-gray-700 mt-1 font-bold">
                                                    <span>Izin/Sakit Pending:</span>
                                                    <?php $pendAtt = $app->attendances->where('validation_status', 'pending')->count(); ?>
                                                    <span class="<?php echo e($pendAtt > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-800 dark:text-gray-200'); ?>"><?php echo e($pendAtt); ?> hari</span>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-4 shadow-xs space-y-3">
                                            <h4 class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider flex items-center gap-2 border-b pb-2 border-gray-100 dark:border-gray-700">
                                                <i class="fas fa-book text-purple-600 dark:text-purple-400"></i> Kepatuhan Logbook
                                            </h4>
                                            <div class="space-y-1.5 text-xs text-gray-700 dark:text-gray-300">
                                                <div class="flex justify-between">
                                                    <span>Total Jurnal Harian:</span>
                                                    <span class="font-bold text-gray-800 dark:text-gray-200"><?php echo e($app->logs->count()); ?> entri</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span>Disetujui Pembimbing:</span>
                                                    <span class="font-bold text-green-600 dark:text-green-400"><?php echo e($app->logs->where('status_validasi', 'disetujui')->count()); ?> entri</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span>Ditolak / Perlu Revisi:</span>
                                                    <span class="font-bold text-red-600 dark:text-red-400"><?php echo e($app->logs->where('status_validasi', 'revisi')->count()); ?> entri</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span>Belum Divalidasi:</span>
                                                    <?php $pendLogs = $app->logs->where('status_validasi', 'pending')->count(); ?>
                                                    <span class="font-bold <?php echo e($pendLogs > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-800 dark:text-gray-200'); ?>"><?php echo e($pendLogs); ?> entri</span>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-4 shadow-xs space-y-3">
                                            <h4 class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider flex items-center gap-2 border-b pb-2 border-gray-100 dark:border-gray-700">
                                                <i class="fas fa-award text-amber-500 dark:text-amber-400"></i> Penilaian Akhir & Sertifikat
                                            </h4>
                                            
                                            <?php if($app->status?->value === 'selesai'): ?>
                                                <div class="space-y-2 text-xs">
                                                    <div class="grid grid-cols-2 gap-x-2 gap-y-1 bg-gray-50 dark:bg-gray-900 p-2 rounded-xl border border-gray-200 dark:border-gray-700 text-[10px]">
                                                        <div>Kerajinan: <span class="font-bold text-gray-800 dark:text-gray-200"><?php echo e($app->nilai_kerajinan ?? '-'); ?></span></div>
                                                        <div>Disiplin: <span class="font-bold text-gray-800 dark:text-gray-200"><?php echo e($app->nilai_disiplin ?? '-'); ?></span></div>
                                                        <div>Adaptasi: <span class="font-bold text-gray-800 dark:text-gray-200"><?php echo e($app->nilai_adaptasi ?? '-'); ?></span></div>
                                                        <div>Kreatifitas: <span class="font-bold text-gray-800 dark:text-gray-200"><?php echo e($app->nilai_kreatifitas ?? '-'); ?></span></div>
                                                        <div class="col-span-2">Skill & Pengetahuan: <span class="font-bold text-gray-800 dark:text-gray-200"><?php echo e($app->nilai_skill_pengetahuan ?? '-'); ?></span></div>
                                                    </div>
                                                    
                                                    <div class="space-y-1 mt-2 text-gray-700 dark:text-gray-300">
                                                        <div class="flex justify-between">
                                                            <span>Nilai Rata-rata:</span>
                                                            <span class="font-black text-gray-900 dark:text-gray-100"><?php echo e(round($app->avg_nilai, 1)); ?> (<?php echo e($app->predikat ?? '-'); ?>)</span>
                                                        </div>
                                                        <?php if($app->nomor_sertifikat): ?>
                                                        <div class="flex justify-between">
                                                            <span>Sertifikat:</span>
                                                            <span class="font-mono text-gray-900 dark:text-gray-200 font-bold bg-yellow-50 dark:bg-yellow-950/60 px-1.5 py-0.5 rounded border border-yellow-200 dark:border-yellow-800/60 text-[9px]"><?php echo e($app->nomor_sertifikat); ?></span>
                                                        </div>
                                                        <?php endif; ?>
                                                        <div class="flex justify-between">
                                                            <span>Pembimbing:</span>
                                                            <span class="font-bold text-gray-800 dark:text-gray-200"><?php echo e($app->pembimbing_lapangan->name ?? '-'); ?></span>
                                                        </div>
                                                    </div>
                                                    
                                                    <?php if($app->catatan_pembimbing_lapangan): ?>
                                                    <div class="text-[9px] text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-900 p-2 rounded-xl border border-gray-200 dark:border-gray-700 italic mt-2">
                                                        <span class="font-bold text-gray-400 dark:text-gray-500 text-[8px] uppercase block not-italic mb-0.5">Catatan Pembimbing:</span>
                                                        "<?php echo e($app->catatan_pembimbing_lapangan); ?>"
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php else: ?>
                                                <div class="text-xs text-gray-500 dark:text-gray-400 py-2">
                                                    <p class="font-bold text-gray-700 dark:text-gray-300 flex items-center gap-1"><i class="fas fa-info-circle text-blue-500 dark:text-blue-400"></i> Magang Belum Selesai</p>
                                                    <p class="mt-1 text-[10px]">Penilaian akhir akan diisi oleh Pembimbing Lapangan <strong>(<?php echo e($app->pembimbing_lapangan->name ?? 'Belum ditentukan'); ?>)</strong> ketika peserta menyelesaikan periode magang.</p>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                        <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-3 border border-gray-200 dark:border-gray-700">
                                            <i class="fas fa-graduation-cap text-3xl text-gray-400 dark:text-gray-500"></i>
                                        </div>
                                        <p class="font-bold text-gray-700 dark:text-gray-300">Belum ada data Peserta</p>
                                        <p class="text-xs mt-1 text-gray-500 dark:text-gray-400">Data peserta magang dengan status aktif atau selesai belum terdaftar di instansi Anda.</p>
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
<?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views\admin_instansi\laporan\kinerja_peserta.blade.php ENDPATH**/ ?>