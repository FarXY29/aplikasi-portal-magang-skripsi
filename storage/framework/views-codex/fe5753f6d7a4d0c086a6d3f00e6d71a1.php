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
                    <i class="fas fa-chalkboard-teacher text-teal-600 dark:text-teal-400 text-lg"></i>
                </div>
                <?php echo e(__('Laporan Kinerja Pembimbing')); ?>

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
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center cursor-help transition hover:shadow-md" title="Jumlah seluruh pegawai atau pembimbing lapangan yang terdaftar aktif mengampu mahasiswa magang di instansi ini.">
                    <div class="w-10 h-10 rounded-2xl bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center mx-auto mb-2.5 border border-teal-100 dark:border-teal-900/50">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <p class="text-2xl font-black text-gray-800 dark:text-gray-100"><?php echo e($stats['total_pembimbing']); ?></p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Pembimbing</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center cursor-help transition hover:shadow-md" title="Jumlah mahasiswa/peserta magang aktif (status 'diterima') yang saat ini sedang menjalani masa bimbingan.">
                    <div class="w-10 h-10 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center mx-auto mb-2.5 border border-blue-100 dark:border-blue-900/50">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <p class="text-2xl font-black text-gray-800 dark:text-gray-100"><?php echo e($stats['total_bimbingan_aktif']); ?></p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Bimbingan Aktif</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center cursor-help transition hover:shadow-md" title="Jumlah alumni peserta bimbingan yang telah menyelesaikan seluruh periode magang dan dinyatakan lulus.">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto mb-2.5 border border-emerald-100 dark:border-emerald-900/50">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <p class="text-2xl font-black text-emerald-700 dark:text-emerald-400"><?php echo e($stats['total_lulus']); ?></p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Alumni Lulus</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center cursor-help transition hover:shadow-md" title="Rata-rata nilai evaluasi dari seluruh peserta bimbingan. Rumus: (Total Nilai Akhir Peserta / Jumlah Peserta Dinilai). Nilai Akhir Peserta = (Nilai Teknis + Nilai Disiplin + Nilai Perilaku) / 3.">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mx-auto mb-2.5 border border-indigo-100 dark:border-indigo-900/50">
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="text-2xl font-black text-indigo-700 dark:text-indigo-400"><?php echo e($stats['rata_nilai_semua'] > 0 ? round($stats['rata_nilai_semua'], 1) : '-'); ?></p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Rata-Rata Nilai</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center cursor-help transition hover:shadow-md" title="Total entri jurnal/logbook harian peserta bimbingan yang masih menunggu verifikasi dan validasi dari pembimbing.">
                    <div class="w-10 h-10 rounded-2xl bg-red-50 dark:bg-red-950/60 text-red-600 dark:text-red-400 flex items-center justify-center mx-auto mb-2.5 border border-red-100 dark:border-red-900/50">
                        <i class="fas fa-clock"></i>
                    </div>
                    <p class="text-2xl font-black text-red-700 dark:text-red-400"><?php echo e($stats['total_logbook_tertunda']); ?></p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Logbook Pending</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center cursor-help transition hover:shadow-md" title="Jumlah pembimbing yang tertib menyelesaikan proses validasi logbook tanpa ada tunggakan logbook pending.">
                    <div class="w-10 h-10 rounded-2xl bg-green-50 dark:bg-green-950/60 text-green-600 dark:text-green-400 flex items-center justify-center mx-auto mb-2.5 border border-green-100 dark:border-green-900/50">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <p class="text-2xl font-black text-green-700 dark:text-green-400"><?php echo e($stats['tertib_validasi']); ?></p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Tertib Validasi</p>
                </div>
            </div>

            
            <?php if($stats['total_pembimbing'] > 0): ?>
            <div class="bg-gradient-to-r from-teal-600 to-emerald-600 rounded-3xl p-6 text-white shadow-lg shadow-teal-600/20 flex flex-col sm:flex-row items-center gap-4 cursor-help" title="Pembimbing lapangan yang menangani alokasi peserta bimbingan aktif terbanyak pada periode saat ini.">
                <div class="w-14 h-14 rounded-2xl bg-white/20 dark:bg-gray-800/30 backdrop-blur-sm flex items-center justify-center text-2xl flex-shrink-0 border border-white/20">
                    <i class="fas fa-award"></i>
                </div>
                <div class="text-center sm:text-left flex-grow">
                    <p class="text-xs font-bold uppercase tracking-wider text-teal-100">Pembimbing Lapangan Teraktif (Beban Tertinggi)</p>
                    <p class="text-xl font-black mt-0.5"><?php echo e($stats['pembimbing_teraktif']); ?></p>
                    <p class="text-sm text-teal-100 font-medium">Membimbing <?php echo e($stats['pembimbing_teraktif_jumlah']); ?> mahasiswa aktif pada periode saat ini.</p>
                </div>
                <?php if($beban->count() > 0): ?>
                <div class="sm:ml-auto flex-shrink-0 flex gap-2">
                    <a href="<?php echo e(route('dinas.laporan.beban_pembimbing.print')); ?>" target="_blank" class="inline-flex items-center px-4 py-2.5 bg-white dark:bg-gray-800 text-teal-700 dark:text-teal-400 hover:text-teal-800 dark:hover:text-teal-300 rounded-xl hover:bg-teal-50 dark:hover:bg-gray-700 transition text-xs font-bold shadow-md border border-white/20 dark:border-gray-700" title="Download PDF">
                        <i class="fas fa-file-pdf mr-1.5 text-red-500"></i> PDF
                    </a>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden" x-data="{ openRow: null, searchQuery: '' }">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gray-50/50 dark:bg-gray-900/50">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                            <i class="fas fa-chalkboard-teacher text-teal-600 dark:text-teal-400"></i>
                            Evaluasi Beban Kerja &amp; Kinerja Pembimbing Lapangan
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Klik baris pembimbing untuk melihat detail data bimbingan aktif, riwayat lulusan, kepatuhan validasi logbook, serta absensi.</p>
                    </div>

                    
                    <div class="relative w-full sm:w-64">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 dark:text-gray-500 pointer-events-none">
                            <i class="fas fa-search text-xs"></i>
                        </span>
                        <input type="text" x-model="searchQuery" placeholder="Cari nama/NIP pembimbing..."
                            class="w-full pl-9 py-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 rounded-xl text-xs font-bold focus:ring-teal-500 focus:border-teal-500 shadow-xs">
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-100 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-12">No</th>
                                <th class="px-5 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Pembimbing / Pegawai</th>
                                <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Bimbingan Aktif</th>
                                <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Alumni Lulus</th>
                                <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Logbook Pending</th>
                                <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Rata-rata Nilai</th>
                                <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-12"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700/60 text-sm">
                            <?php $__empty_1 = true; $__currentLoopData = $beban; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-teal-50/15 dark:hover:bg-teal-900/20 transition cursor-pointer"
                                x-data="{ searchText: <?php echo \Illuminate\Support\Js::from(strtolower($pl->name . ' ' . $pl->nik . ' ' . $pl->email))->toHtml() ?> }"
                                x-show="!searchQuery || searchText.includes(searchQuery.toLowerCase())"
                                @click="openRow = openRow === <?php echo e($loop->index); ?> ? null : <?php echo e($loop->index); ?>">
                                <td class="px-5 py-4 text-xs text-gray-400 dark:text-gray-500 text-center font-bold"><?php echo e($loop->iteration); ?></td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-300 flex items-center justify-center text-sm font-black border border-teal-200 dark:border-teal-800/60 flex-shrink-0 shadow-xs">
                                            <?php echo e(strtoupper(substr($pl->name, 0, 1))); ?>

                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900 dark:text-gray-100"><?php echo e($pl->name); ?></p>
                                            <div class="flex flex-wrap items-center gap-x-2 text-[10px] text-gray-500 dark:text-gray-400 font-medium mt-0.5">
                                                <span>NIP/NIK: <?php echo e($pl->nik ?? '-'); ?></span>
                                                <span>•</span>
                                                <span><?php echo e($pl->email); ?></span>
                                                <?php if($pl->phone): ?>
                                                <span>•</span>
                                                <span><?php echo e($pl->phone); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border border-blue-100 dark:border-blue-800/60">
                                        <?php echo e($pl->total_bimbingan_aktif); ?> Orang
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-100 dark:border-emerald-800/60">
                                        <?php echo e($pl->total_lulus); ?> Orang
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <?php if($pl->logbook_tertunda > 0): ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60 animate-pulse">
                                        <i class="fas fa-exclamation-triangle mr-1"></i> <?php echo e($pl->logbook_tertunda); ?> Pending
                                    </span>
                                    <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-100 dark:border-emerald-800/60">
                                        <i class="fas fa-check-circle mr-1"></i> Tuntas
                                    </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <?php if($pl->rata_nilai_diberikan > 0): ?>
                                    <span class="text-xs font-black text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 px-3 py-1 rounded-full inline-block"><?php echo e(round($pl->rata_nilai_diberikan, 1)); ?></span>
                                    <?php else: ?>
                                    <span class="text-xs text-gray-400 dark:text-gray-500 italic">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <i class="fas fa-chevron-down text-gray-400 dark:text-gray-500 text-xs transition-transform duration-200" :class="openRow === <?php echo e($loop->index); ?> ? 'rotate-180 text-teal-600 dark:text-teal-400' : ''"></i>
                                </td>
                            </tr>
                            
                            
                            <tr x-show="openRow === <?php echo e($loop->index); ?>" x-transition.opacity x-cloak>
                                <td colspan="7" class="px-5 py-4 bg-gray-50/60 dark:bg-gray-900/60 border-y border-gray-100 dark:border-gray-700">
                                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                                        
                                        
                                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 shadow-xs space-y-4">
                                            <div class="flex items-center justify-between border-b pb-3 border-gray-100 dark:border-gray-700">
                                                <h4 class="text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                                                    <i class="fas fa-user-friends text-blue-500 dark:text-blue-400"></i>
                                                    Bimbingan Aktif saat ini (<?php echo e(count($pl->mahasiswa_aktif)); ?>)
                                                </h4>
                                            </div>
                                            
                                            <?php if(count($pl->mahasiswa_aktif) > 0): ?>
                                            <div class="space-y-3 max-h-96 overflow-y-auto pr-1">
                                                <?php $__currentLoopData = $pl->mahasiswa_aktif; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mhs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="p-3 bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 flex flex-col md:flex-row justify-between gap-3 items-start md:items-center">
                                                    <div class="min-w-0 flex-1">
                                                        <p class="text-xs font-black text-gray-900 dark:text-gray-100 truncate"><?php echo e($mhs['nama']); ?></p>
                                                        <p class="text-[10px] text-gray-500 dark:text-gray-400 font-semibold truncate"><?php echo e($mhs['kampus']); ?> — <?php echo e($mhs['jurusan']); ?></p>
                                                        <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                                                            <span class="inline-block px-2 py-0.5 bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 border border-teal-100 dark:border-teal-900/40 text-[9px] font-bold rounded-md">
                                                                <?php echo e($mhs['posisi']); ?>

                                                            </span>
                                                            <span class="text-[9px] text-gray-400 dark:text-gray-500 font-medium">
                                                                <?php echo e(\Carbon\Carbon::parse($mhs['mulai'])->format('d M')); ?> s/d <?php echo e(\Carbon\Carbon::parse($mhs['selesai'])->format('d M Y')); ?>

                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="flex md:flex-col justify-between items-end gap-2 text-right flex-shrink-0">
                                                        
                                                        <div class="w-full md:w-32">
                                                            <div class="flex items-center justify-between text-[9px] text-gray-400 dark:text-gray-500 font-bold mb-1">
                                                                <span>Logbook:</span>
                                                                <span><?php echo e($mhs['logbook']['disetujui']); ?>/<?php echo e($mhs['logbook']['total']); ?></span>
                                                            </div>
                                                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 overflow-hidden">
                                                                <div class="bg-teal-500 h-1.5 rounded-full" style="width: <?php echo e($mhs['logbook']['rate']); ?>%"></div>
                                                            </div>
                                                            <?php if($mhs['logbook']['pending'] > 0 || $mhs['logbook']['revisi'] > 0): ?>
                                                            <div class="flex gap-1.5 justify-end mt-1 text-[8px] font-black">
                                                                <?php if($mhs['logbook']['pending'] > 0): ?>
                                                                <span class="text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/60 px-1 py-0.5 rounded"><?php echo e($mhs['logbook']['pending']); ?> Pending</span>
                                                                <?php endif; ?>
                                                                <?php if($mhs['logbook']['revisi'] > 0): ?>
                                                                <span class="text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-950/60 px-1 py-0.5 rounded"><?php echo e($mhs['logbook']['revisi']); ?> Revisi</span>
                                                                <?php endif; ?>
                                                            </div>
                                                            <?php endif; ?>
                                                        </div>

                                                        
                                                        <?php if($mhs['absensi']['pending'] > 0): ?>
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-black bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60">
                                                            <i class="fas fa-exclamation-circle mr-1"></i> <?php echo e($mhs['absensi']['pending']); ?> Izin/Sakit Pending
                                                        </span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                            <?php else: ?>
                                            <p class="text-xs text-gray-400 dark:text-gray-500 italic py-8 text-center">Tidak ada bimbingan aktif saat ini.</p>
                                            <?php endif; ?>
                                        </div>

                                        
                                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 shadow-xs space-y-4">
                                            <div class="flex items-center justify-between border-b pb-3 border-gray-100 dark:border-gray-700">
                                                <h4 class="text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                                                    <i class="fas fa-graduation-cap text-emerald-600 dark:text-emerald-400"></i>
                                                    Alumni Bimbingan Lulus (<?php echo e(count($pl->mahasiswa_lulus)); ?>)
                                                </h4>
                                            </div>
                                            
                                            <?php if(count($pl->mahasiswa_lulus) > 0): ?>
                                            <div class="space-y-3 max-h-96 overflow-y-auto pr-1">
                                                <?php $__currentLoopData = $pl->mahasiswa_lulus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mhs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="p-3 bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 flex flex-col justify-between gap-2">
                                                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-2 border-b pb-2 border-gray-200 dark:border-gray-700">
                                                        <div>
                                                            <p class="text-xs font-black text-gray-900 dark:text-gray-100 truncate"><?php echo e($mhs['nama']); ?></p>
                                                            <p class="text-[10px] text-gray-500 dark:text-gray-400 font-semibold truncate"><?php echo e($mhs['kampus']); ?> — <?php echo e($mhs['jurusan']); ?></p>
                                                            <p class="text-[9px] text-teal-600 dark:text-teal-400 font-bold mt-0.5">Posisi: <?php echo e($mhs['posisi']); ?></p>
                                                        </div>
                                                        <div class="text-right flex flex-row md:flex-col items-center md:items-end gap-1.5 flex-shrink-0">
                                                            <span class="text-xs font-black text-gray-800 dark:text-gray-200 bg-gray-200 dark:bg-gray-800 px-2 py-0.5 rounded border border-gray-300 dark:border-gray-700">
                                                                Nilai: <?php echo e($mhs['nilai']); ?>

                                                            </span>
                                                            <span class="text-[9px] font-black text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-100 dark:border-emerald-800/60 px-1.5 py-0.5 rounded">
                                                                <?php echo e($mhs['predikat']); ?>

                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="space-y-1 mt-1">
                                                        <?php if($mhs['nomor_sertifikat']): ?>
                                                        <p class="text-[9px] text-gray-500 dark:text-gray-400 font-bold">
                                                            <i class="fas fa-certificate text-yellow-500 mr-1"></i> No. Sertifikat: <span class="font-mono text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 px-1.5 py-0.5 border rounded border-gray-300 dark:border-gray-700"><?php echo e($mhs['nomor_sertifikat']); ?></span>
                                                        </p>
                                                        <?php endif; ?>
                                                        <div class="text-[10px] text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-800 p-2 rounded-xl border border-gray-200 dark:border-gray-700">
                                                            <span class="font-bold text-gray-400 dark:text-gray-500 text-[9px] uppercase block mb-0.5">Catatan Evaluasi:</span>
                                                            <?php echo e($mhs['catatan'] ?: 'Tidak ada catatan khusus.'); ?>

                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                            <?php else: ?>
                                            <p class="text-xs text-gray-400 dark:text-gray-500 italic py-8 text-center">Belum ada mahasiswa bimbingan yang selesai.</p>
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
                                            <i class="fas fa-chalkboard-teacher text-3xl text-gray-400 dark:text-gray-500"></i>
                                        </div>
                                        <p class="font-bold text-gray-700 dark:text-gray-300">Belum ada data Pembimbing Lapangan</p>
                                        <p class="text-xs mt-1 text-gray-500 dark:text-gray-400">Akun pembimbing lapangan perlu dibuat terlebih dahulu di menu manajemen akun.</p>
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
<?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views\admin_instansi\laporan\beban_pembimbing.blade.php ENDPATH**/ ?>