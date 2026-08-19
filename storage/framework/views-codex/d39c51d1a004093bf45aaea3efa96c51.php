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
    <?php $__env->startPush('head'); ?>
        <meta name="turbo-cache-control" content="no-cache">
    <?php $__env->stopPush(); ?>
    <?php $__env->startPush('styles'); ?>
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet">
        <style>
            .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
            .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #475569; }

            .period-btn.active {
                background-color: #14b8a6;
                color: #ffffff;
                box-shadow: 0 2px 8px rgba(20,184,166,0.3);
                border: 1px solid #0d9488;
            }
            .dark .period-btn.active {
                background-color: #1e293b;
                color: #ffffff;
                box-shadow: 0 2px 8px rgba(0,0,0,0.3);
                border: 1px solid #334155;
            }
        </style>
    <?php $__env->stopPush(); ?>

     <?php $__env->slot('header', null, []); ?> 
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-teal-500 text-white flex items-center justify-center shadow-md shadow-teal-500/20">
                <i class="fas fa-building text-sm"></i>
            </div>
            <div>
                <h2 class="font-black text-xl text-gray-800 dark:text-slate-100 leading-tight">Dashboard Admin Instansi</h2>
                <p class="text-xs text-gray-500 dark:text-slate-400 font-medium hidden md:block">Pusat Pengelolaan & Monitoring Magang — <?php echo e($instansi->nama_dinas); ?></p>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="space-y-5 md:space-y-6 font-[Inter] py-2 bg-gray-50 dark:bg-[#0f172a] text-slate-900 dark:text-slate-100 min-h-screen">

        
        
        
        <?php
            $globalAnnouncement = \App\Models\Setting::where('key', 'announcement')->value('value');
        ?>
        <?php if(!empty($globalAnnouncement)): ?>
            <div class="bg-white dark:bg-[#161f33] border-l-4 border-amber-500 p-5 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-800/40 flex gap-4 items-start relative overflow-hidden">
                <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0 border border-amber-200 dark:border-amber-500/30">
                    <i class="fas fa-bullhorn text-lg"></i>
                </div>
                <div class="flex-1">
                    <h4 class="text-xs font-black text-amber-600 dark:text-amber-400 uppercase tracking-wider mb-1">Pengumuman Kota Banjarmasin</h4>
                    <div class="text-xs text-slate-700 dark:text-slate-300 font-medium leading-relaxed">
                        <?php echo nl2br(e($globalAnnouncement)); ?>

                    </div>
                </div>
            </div>
        <?php endif; ?>

        
        
        
        <div class="relative overflow-hidden rounded-3xl bg-white dark:bg-[#161f33] text-slate-900 dark:text-white shadow-xl border border-slate-200 dark:border-slate-800/40 p-6 md:p-7">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                
                
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-teal-100 dark:bg-teal-500/20 border border-teal-200 dark:border-teal-500/30 text-teal-600 dark:text-teal-400 flex items-center justify-center text-xl shrink-0 shadow-inner">
                        <i class="fas fa-building-user"></i>
                    </div>
                    <div>
                        <h1 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                            Selamat Datang, Admin <?php echo e($instansi->nama_dinas); ?>! 👋
                        </h1>
                        <p class="text-xs md:text-sm text-gray-500 dark:text-slate-400 font-semibold mt-1">
                            <span id="current-period-display"><?php echo e(now()->translatedFormat('l, d F Y')); ?> • <?php echo e($periodText); ?></span>
                        </p>
                    </div>
                </div>

                
                <div class="flex flex-wrap items-center gap-2">
                    <div class="inline-flex items-center bg-gray-100 dark:bg-[#0f172a] p-1.5 rounded-2xl border border-slate-200 dark:border-slate-800/60 shadow-inner">
                        <button type="button" data-period="hari_ini" class="period-btn px-3.5 py-1.5 rounded-xl text-xs font-bold text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition <?php echo e($period === 'hari_ini' ? 'active' : ''); ?>">
                            Hari Ini
                        </button>
                        <button type="button" data-period="7_hari" class="period-btn px-3.5 py-1.5 rounded-xl text-xs font-bold text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition <?php echo e($period === '7_hari' ? 'active' : ''); ?>">
                            7 Hari
                        </button>
                        <button type="button" data-period="30_hari" class="period-btn px-3.5 py-1.5 rounded-xl text-xs font-bold text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition <?php echo e($period === '30_hari' ? 'active' : ''); ?>">
                            30 Hari
                        </button>
                        <button type="button" data-period="semester" class="period-btn px-3.5 py-1.5 rounded-xl text-xs font-bold text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition <?php echo e($period === 'semester' ? 'active' : ''); ?>">
                            Semester
                        </button>
                        <button type="button" data-period="tahun" class="period-btn px-3.5 py-1.5 rounded-xl text-xs font-bold text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition <?php echo e($period === 'tahun' ? 'active' : ''); ?>">
                            Tahun
                        </button>
                    </div>

                    
                    <button id="refresh-btn" type="button" class="inline-flex items-center gap-2 px-4 py-2 bg-teal-500 hover:bg-teal-400 active:scale-95 text-slate-950 font-black text-xs rounded-2xl shadow-lg shadow-teal-500/20 transition-all">
                        <i id="refresh-icon" class="fas fa-sync-alt text-xs"></i>
                        <span>Refresh (<span id="countdown-timer">60</span>s)</span>
                    </button>
                </div>

            </div>
        </div>

        
        
        
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3 md:gap-4">
            
            <a href="<?php echo e(route('dinas.lowongan.index')); ?>" class="group block relative overflow-hidden rounded-2xl md:rounded-3xl border border-slate-200 dark:border-slate-800/40 bg-white dark:bg-[#161f33] p-4 md:p-5 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:border-teal-500/40 hover:shadow-teal-500/10">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-[10px] md:text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">LOWONG...</p>
                        <h3 id="stat-lowongan" class="mt-2 text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white"><?php echo e(number_format($totalLowongan)); ?></h3>
                        <p class="mt-1 text-[10px] md:text-xs font-semibold text-slate-500 dark:text-slate-400">Posisi magang</p>
                    </div>
                    <div class="flex h-10 w-10 md:h-12 md:w-12 shrink-0 items-center justify-center rounded-xl bg-teal-500 text-white shadow-md shadow-teal-500/30 transition-transform duration-300 group-hover:scale-110">
                        <i class="fas fa-briefcase text-base md:text-lg"></i>
                    </div>
                </div>
            </a>

            
            <a href="<?php echo e(route('dinas.pembimbing_lapangan.index')); ?>" class="group block relative overflow-hidden rounded-2xl md:rounded-3xl border border-slate-200 dark:border-slate-800/40 bg-white dark:bg-[#161f33] p-4 md:p-5 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:border-blue-500/40 hover:shadow-blue-500/10">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-[10px] md:text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">PEMBIMBI...</p>
                        <h3 id="stat-pembimbing" class="mt-2 text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white"><?php echo e(number_format($totalPembimbing)); ?></h3>
                        <p class="mt-1 text-[10px] md:text-xs font-semibold text-slate-500 dark:text-slate-400">Pembimbing lapangan</p>
                    </div>
                    <div class="flex h-10 w-10 md:h-12 md:w-12 shrink-0 items-center justify-center rounded-xl bg-blue-500 text-white shadow-md shadow-blue-500/30 transition-transform duration-300 group-hover:scale-110">
                        <i class="fas fa-user-tie text-base md:text-lg"></i>
                    </div>
                </div>
            </a>

            
            <a href="<?php echo e(route('dinas.pelamar')); ?>" class="group block relative overflow-hidden rounded-2xl md:rounded-3xl border border-slate-200 dark:border-slate-800/40 bg-white dark:bg-[#161f33] p-4 md:p-5 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:border-purple-500/40 hover:shadow-purple-500/10">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-[10px] md:text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">PENDAFT...</p>
                        <h3 id="stat-pendaftar" class="mt-2 text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white"><?php echo e(number_format($totalApplications)); ?></h3>
                        <p class="stat-period-subtitle mt-1 text-[10px] md:text-xs font-semibold text-slate-500 dark:text-slate-400"><?php echo e($periodText); ?></p>
                    </div>
                    <div class="flex h-10 w-10 md:h-12 md:w-12 shrink-0 items-center justify-center rounded-xl bg-purple-500 text-white shadow-md shadow-purple-500/30 transition-transform duration-300 group-hover:scale-110">
                        <i class="fas fa-file-signature text-base md:text-lg"></i>
                    </div>
                </div>
                <div class="mt-3 inline-flex items-center gap-1 rounded-full bg-emerald-50 dark:bg-emerald-500/15 px-2 py-0.5 text-[9px] md:text-[10px] font-black text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30">
                    <i class="fas fa-chart-line text-[9px]"></i> +100%
                </div>
            </a>

            
            <a href="<?php echo e(route('dinas.peserta.index')); ?>" class="group block relative overflow-hidden rounded-2xl md:rounded-3xl border border-slate-200 dark:border-slate-800/40 bg-white dark:bg-[#161f33] p-4 md:p-5 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:border-emerald-500/40 hover:shadow-emerald-500/10">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-[10px] md:text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">AKTIF</p>
                        <h3 id="stat-aktif" class="mt-2 text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white"><?php echo e(number_format($activeInterns)); ?></h3>
                        <p class="stat-period-subtitle mt-1 text-[10px] md:text-xs font-semibold text-slate-500 dark:text-slate-400"><?php echo e($periodText); ?></p>
                    </div>
                    <div class="flex h-10 w-10 md:h-12 md:w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-500 text-white shadow-md shadow-emerald-500/30 transition-transform duration-300 group-hover:scale-110">
                        <i class="fas fa-user-check text-base md:text-lg"></i>
                    </div>
                </div>
                <div class="mt-3 inline-flex items-center gap-1 rounded-full bg-emerald-50 dark:bg-emerald-500/15 px-2 py-0.5 text-[9px] md:text-[10px] font-black text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30">
                    <i class="fas fa-chart-line text-[9px]"></i> +100%
                </div>
            </a>

            
            <a href="<?php echo e(route('dinas.peserta.index')); ?>" class="group block relative overflow-hidden rounded-2xl md:rounded-3xl border border-slate-200 dark:border-slate-800/40 bg-white dark:bg-[#161f33] p-4 md:p-5 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:border-indigo-500/40 hover:shadow-indigo-500/10">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-[10px] md:text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">SELESAI</p>
                        <h3 id="stat-selesai" class="mt-2 text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white"><?php echo e(number_format($completedInterns)); ?></h3>
                        <p class="stat-period-subtitle mt-1 text-[10px] md:text-xs font-semibold text-slate-500 dark:text-slate-400"><?php echo e($periodText); ?></p>
                    </div>
                    <div class="flex h-10 w-10 md:h-12 md:w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-500 text-white shadow-md shadow-indigo-500/30 transition-transform duration-300 group-hover:scale-110">
                        <i class="fas fa-graduation-cap text-base md:text-lg"></i>
                    </div>
                </div>
                <div class="mt-3 inline-flex items-center gap-1 rounded-full bg-emerald-50 dark:bg-emerald-500/15 px-2 py-0.5 text-[9px] md:text-[10px] font-black text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30">
                    <i class="fas fa-chart-line text-[9px]"></i> +100%
                </div>
            </a>

            
            <a href="<?php echo e(route('dinas.pelamar')); ?>" class="group block relative overflow-hidden rounded-2xl md:rounded-3xl border border-slate-200 dark:border-slate-800/40 bg-white dark:bg-[#161f33] p-4 md:p-5 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:border-amber-500/40 hover:shadow-amber-500/10">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-[10px] md:text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">PENDING</p>
                        <h3 id="stat-pending" class="mt-2 text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white"><?php echo e(number_format($pendingApplications)); ?></h3>
                        <p class="stat-period-subtitle mt-1 text-[10px] md:text-xs font-semibold text-slate-500 dark:text-slate-400"><?php echo e($periodText); ?></p>
                    </div>
                    <div class="flex h-10 w-10 md:h-12 md:w-12 shrink-0 items-center justify-center rounded-xl bg-amber-500 text-white shadow-md shadow-amber-500/30 transition-transform duration-300 group-hover:scale-110">
                        <i class="fas fa-hourglass-half text-base md:text-lg"></i>
                    </div>
                </div>
                <div class="mt-3 inline-flex items-center gap-1 rounded-full bg-emerald-50 dark:bg-emerald-500/15 px-2 py-0.5 text-[9px] md:text-[10px] font-black text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30">
                    <i class="fas fa-chart-line text-[9px]"></i> +100%
                </div>
            </a>
        </div>

        
        
        
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">
            
            
            <div class="xl:col-span-2 bg-white dark:bg-[#161f33] rounded-3xl border border-slate-200 dark:border-slate-800/40 p-6 flex flex-col justify-between shadow-xl">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-teal-100 dark:bg-teal-500/20 text-teal-600 dark:text-teal-400 border border-teal-200 dark:border-teal-500/30 flex items-center justify-center shadow-xs">
                                <i class="fas fa-chart-line text-base"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-black text-slate-900 dark:text-white">Tren Pendaftaran Magang</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Jumlah pendaftar per hari ke <?php echo e($instansi->nama_dinas); ?></p>
                            </div>
                        </div>
                        <span id="chart-period-badge" class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-teal-100 dark:bg-teal-500/20 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-500/30 tracking-wider">
                            <?php echo e(strtoupper($periodText)); ?>

                        </span>
                    </div>

                    <div class="relative w-full mt-4" style="height: 280px;">
                        <canvas id="trendChart"
                            data-labels="<?php echo e(json_encode($trendLabels)); ?>"
                            data-values="<?php echo e(json_encode($trendData)); ?>">
                        </canvas>
                    </div>
                </div>
            </div>

            
            <div class="bg-white dark:bg-[#161f33] rounded-3xl border border-slate-200 dark:border-slate-800/40 p-6 flex flex-col justify-between shadow-xl">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-2xl bg-orange-100 dark:bg-orange-500/20 text-orange-600 dark:text-orange-400 border border-orange-200 dark:border-orange-500/30 flex items-center justify-center shadow-xs">
                            <i class="fas fa-chart-pie text-base"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-900 dark:text-white">Status Lamaran</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Distribusi status pelamar di instansi ini</p>
                        </div>
                    </div>

                    <div class="relative flex items-center justify-center" style="height: 210px;">
                        <canvas id="statusChart"
                            data-labels="<?php echo e(json_encode($statusLabels)); ?>"
                            data-values="<?php echo e(json_encode($statusData)); ?>">
                        </canvas>
                    </div>
                </div>

                
                <div class="grid grid-cols-2 gap-3 mt-4 pt-4 border-t border-slate-200 dark:border-slate-800/60">
                    <div class="bg-emerald-50 dark:bg-emerald-950/30 p-3 rounded-2xl border border-emerald-200 dark:border-emerald-900/40 text-center">
                        <span class="block text-[10px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider">LOLOS</span>
                        <span id="lolos-percentage" class="text-xl font-black text-emerald-600 dark:text-emerald-400 mt-0.5 block">
                            <?php echo e($lolosPercentage); ?>%
                        </span>
                    </div>
                    <div class="bg-red-50 dark:bg-red-950/30 p-3 rounded-2xl border border-red-200 dark:border-red-900/40 text-center">
                        <span class="block text-[10px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider">TOLAK</span>
                        <span id="tolak-percentage" class="text-xl font-black text-red-600 dark:text-red-400 mt-0.5 block">
                            <?php echo e($tolakPercentage); ?>%
                        </span>
                    </div>
                </div>
            </div>

        </div>

        
        
        
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
            
            <div class="bg-white dark:bg-[#161f33] rounded-3xl border border-slate-200 dark:border-slate-800/40 p-6 shadow-xl flex flex-col justify-between">
                <div class="mb-4 pb-4 border-b border-slate-200 dark:border-slate-800/60 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-black text-slate-900 dark:text-white">Demografi Asal Peserta</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Sekolah / Perguruan Tinggi pengirim terbanyak</p>
                    </div>
                    <div class="w-8 h-8 rounded-xl bg-indigo-100 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center border border-indigo-200 dark:border-indigo-500/30">
                        <i class="fas fa-university text-xs"></i>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <?php if(count($topInstansi) > 0): ?>
                        <?php $__currentLoopData = $topInstansi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $inst): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between group">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-900 flex items-center justify-center text-slate-500 dark:text-slate-400 font-bold text-xs border border-slate-200 dark:border-slate-800 shrink-0">
                                    <?php echo e($index + 1); ?>

                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-slate-700 dark:text-slate-200 truncate cursor-help hover:text-teal-600 dark:hover:text-teal-400 transition" 
                                       title="<?php echo e($inst->asal_instansi); ?>">
                                        <?php echo e($inst->asal_instansi); ?>

                                    </p>
                                    <div class="w-full bg-slate-200 dark:bg-slate-900 rounded-full h-1.5 mt-1.5 overflow-hidden border border-slate-200 dark:border-slate-800">
                                        <div class="bg-teal-400 h-1.5 rounded-full" style="width: <?php echo e(min(($inst->total_peserta / $topInstansi[0]->total_peserta) * 100, 100)); ?>%"></div>
                                    </div>
                                </div>
                            </div>
                            <span class="text-xs font-black text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-500/10 px-2.5 py-1 rounded-xl border border-teal-200 dark:border-teal-500/30 shrink-0 ml-3">
                                <?php echo e($inst->total_peserta); ?> Peserta
                            </span>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="text-center py-8 text-slate-500 dark:text-slate-400">
                            <i class="fas fa-university text-2xl text-slate-400 dark:text-slate-600 mb-2 block"></i>
                            <p class="text-xs font-bold">Belum ada data pendaftar.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="bg-white dark:bg-[#161f33] rounded-3xl border border-slate-200 dark:border-slate-800/40 p-6 shadow-xl">
                <div class="flex items-center justify-between mb-4 pb-4 border-b border-slate-200 dark:border-slate-800/60">
                    <div>
                        <h3 class="text-base font-black text-slate-900 dark:text-white">Posisi Magang Lowongan</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Daftar posisi magang aktif di instansi ini</p>
                    </div>
                    <a href="<?php echo e(route('dinas.lowongan.index')); ?>" class="text-[10px] text-teal-600 dark:text-teal-400 bg-teal-50 dark:bg-teal-500/10 px-2.5 py-1 rounded-lg font-black transition border border-teal-200 dark:border-teal-500/30">Kelola</a>
                </div>

                <div class="divide-y divide-slate-200 dark:divide-slate-800/60">
                    <?php $__empty_1 = true; $__currentLoopData = $recentPositions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="py-3 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-xl bg-teal-100 dark:bg-teal-500/10 border border-teal-200 dark:border-teal-500/20 text-teal-600 dark:text-teal-400 flex items-center justify-center font-bold text-xs shrink-0">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-slate-700 dark:text-slate-200 truncate"><?php echo e($pos->judul_posisi); ?></p>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 truncate mt-0.5 font-medium">
                                    Kuota: <?php echo e($pos->kuota); ?> orang
                                </p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase <?php echo e($pos->status == 'buka' ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30' : 'bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-500/30'); ?>">
                            <?php echo e($pos->status == 'buka' ? 'Aktif' : 'Non-Aktif'); ?>

                        </span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-8 text-slate-500 dark:text-slate-400">
                        <i class="fas fa-briefcase text-2xl text-slate-400 dark:text-slate-600 mb-2 block"></i>
                        <p class="text-xs font-bold">Belum ada posisi magang.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (function() {
            let currentPeriod = '<?php echo e($period); ?>';
            let countdown = 60;
            let timerInterval = null;

            function initCharts() {
                if (typeof Chart === 'undefined') {
                    setTimeout(initCharts, 50);
                    return;
                }

                // 1. TREN PENDAFTARAN (LINE CHART)
                const canvasTrend = document.getElementById('trendChart');
                if (window.dinasTrendChart) {
                    try { window.dinasTrendChart.destroy(); } catch(e) {}
                    window.dinasTrendChart = null;
                }

                if (canvasTrend) {
                    const trendLabels = JSON.parse(canvasTrend.dataset.labels || '[]');
                    const trendValues = JSON.parse(canvasTrend.dataset.values || '[]');
                    const ctx = canvasTrend.getContext('2d');

                    const gradient = ctx.createLinearGradient(0, 0, 0, 260);
                    gradient.addColorStop(0, 'rgba(20, 184, 166, 0.4)');
                    gradient.addColorStop(1, 'rgba(20, 184, 166, 0.0)');

                    window.dinasTrendChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: trendLabels,
                            datasets: [{
                                label: 'Pendaftar',
                                data: trendValues,
                                borderColor: '#14b8a6',
                                borderWidth: 3,
                                backgroundColor: gradient,
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: '#2dd4bf',
                                pointBorderColor: '#0f172a',
                                pointBorderWidth: 2,
                                pointRadius: 4,
                                pointHoverRadius: 6,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: '#0f172a',
                                    borderColor: '#334155',
                                    borderWidth: 1,
                                    titleFont: { family: 'Inter', size: 12, weight: 'bold' },
                                    bodyFont: { family: 'Inter', size: 12 },
                                    padding: 12,
                                    cornerRadius: 10,
                                    callbacks: {
                                        label: (context) => ` ${context.parsed.y} Pendaftar`
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: { color: 'rgba(51, 65, 85, 0.3)', borderDash: [4, 4] },
                                    ticks: { color: '#94a3b8', font: { family: 'Inter', size: 10 } }
                                },
                                x: {
                                    grid: { display: false },
                                    ticks: { color: '#94a3b8', font: { family: 'Inter', size: 10 } }
                                }
                            }
                        }
                    });
                }

                // 2. STATUS LAMARAN (DONUT CHART)
                const canvasStatus = document.getElementById('statusChart');
                if (window.dinasStatusChart) {
                    try { window.dinasStatusChart.destroy(); } catch(e) {}
                    window.dinasStatusChart = null;
                }

                if (canvasStatus) {
                    const statusLabels = JSON.parse(canvasStatus.dataset.labels || '[]');
                    const statusValues = JSON.parse(canvasStatus.dataset.values || '[]');

                    window.dinasStatusChart = new Chart(canvasStatus.getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: statusLabels,
                            datasets: [{
                                data: statusValues,
                                backgroundColor: ['#f59e0b', '#10b981', '#6366f1', '#ef4444'],
                                borderWidth: 3,
                                borderColor: '#161f33',
                                hoverOffset: 6,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        usePointStyle: true,
                                        pointStyle: 'circle',
                                        boxWidth: 8,
                                        font: { family: 'Inter', size: 11, weight: '600' },
                                        padding: 14,
                                        color: '#94a3b8'
                                    }
                                },
                                tooltip: {
                                    backgroundColor: '#0f172a',
                                    borderColor: '#334155',
                                    borderWidth: 1,
                                    padding: 12,
                                    cornerRadius: 10,
                                    callbacks: {
                                        label: (ctx) => ` ${ctx.label}: ${ctx.parsed} Orang`
                                    }
                                }
                            },
                            cutout: '70%',
                            animation: { animateRotate: true, duration: 800 }
                        }
                    });
                }
            }

            // AJAX DATA FETCHER
            function fetchDashboardData(period, isManual = false) {
                const refreshIcon = document.getElementById('refresh-icon');
                if (refreshIcon) refreshIcon.classList.add('fa-spin');

                fetch(`<?php echo e(route('dinas.dashboard')); ?>?period=${period}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    // Update Stat Numbers
                    const elLowongan = document.getElementById('stat-lowongan');
                    const elPembimbing = document.getElementById('stat-pembimbing');
                    const elPendaftar = document.getElementById('stat-pendaftar');
                    const elAktif = document.getElementById('stat-aktif');
                    const elSelesai = document.getElementById('stat-selesai');
                    const elPending = document.getElementById('stat-pending');

                    if (elLowongan) elLowongan.textContent = data.totalLowongan;
                    if (elPembimbing) elPembimbing.textContent = data.totalPembimbing;
                    if (elPendaftar) elPendaftar.textContent = data.totalApplications;
                    if (elAktif) elAktif.textContent = data.activeInterns;
                    if (elSelesai) elSelesai.textContent = data.completedInterns;
                    if (elPending) elPending.textContent = data.pendingApplications;

                    // Update Subtitles & Badges
                    const periodMap = {
                        'hari_ini': 'Hari Ini',
                        '7_hari': '7 Hari Terakhir',
                        '30_hari': '30 Hari Terakhir',
                        'semester': 'Semester Ini',
                        'tahun': 'Tahun Ini'
                    };
                    const pText = (data && data.periodText) ? data.periodText : (periodMap[period] || '30 Hari Terakhir');

                    document.querySelectorAll('.stat-period-subtitle').forEach(el => {
                        el.textContent = pText;
                    });
                    const periodDisplay = document.getElementById('current-period-display');
                    if (periodDisplay) {
                        periodDisplay.textContent = `<?php echo e(now()->translatedFormat('l, d F Y')); ?> • ${pText}`;
                    }
                    const chartBadge = document.getElementById('chart-period-badge');
                    if (chartBadge) chartBadge.textContent = pText.toUpperCase();

                    // Update Summary Percentages
                    const lolosEl = document.getElementById('lolos-percentage');
                    const tolakEl = document.getElementById('tolak-percentage');
                    if (lolosEl) lolosEl.textContent = `${data.lolosPercentage}%`;
                    if (tolakEl) tolakEl.textContent = `${data.tolakPercentage}%`;

                    // Update Line Chart
                    if (window.dinasTrendChart) {
                        window.dinasTrendChart.data.labels = data.trendLabels;
                        window.dinasTrendChart.data.datasets[0].data = data.trendData;
                        window.dinasTrendChart.update('none');
                    }

                    // Update Donut Chart
                    if (window.dinasStatusChart) {
                        window.dinasStatusChart.data.labels = data.statusLabels;
                        window.dinasStatusChart.data.datasets[0].data = data.statusData;
                        window.dinasStatusChart.update('none');
                    }

                    // Reset Timer
                    countdown = 60;
                    const timerEl = document.getElementById('countdown-timer');
                    if (timerEl) timerEl.textContent = countdown;
                })
                .catch(err => console.error('Error fetching dashboard data:', err))
                .finally(() => {
                    if (refreshIcon) refreshIcon.classList.remove('fa-spin');
                });
            }

            // FILTER BUTTON EVENT LISTENERS
            function setupEventListeners() {
                document.querySelectorAll('.period-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        document.querySelectorAll('.period-btn').forEach(b => b.classList.remove('active'));
                        this.classList.add('active');
                        currentPeriod = this.dataset.period;
                        fetchDashboardData(currentPeriod, true);
                    });
                });

                const refreshBtn = document.getElementById('refresh-btn');
                if (refreshBtn) {
                    refreshBtn.addEventListener('click', function() {
                        fetchDashboardData(currentPeriod, true);
                    });
                }
            }

            // TIMER COUNTDOWN (60s AUTO REFRESH)
            function startAutoRefreshTimer() {
                if (timerInterval) clearInterval(timerInterval);
                timerInterval = setInterval(() => {
                    countdown--;
                    const timerEl = document.getElementById('countdown-timer');
                    if (timerEl) timerEl.textContent = countdown;

                    if (countdown <= 0) {
                        fetchDashboardData(currentPeriod);
                    }
                }, 1000);
            }

            document.addEventListener('DOMContentLoaded', () => {
                initCharts();
                setupEventListeners();
                startAutoRefreshTimer();
            });

            document.addEventListener('turbo:load', () => {
                initCharts();
                setupEventListeners();
                startAutoRefreshTimer();
            });

            document.addEventListener('turbo:before-cache', () => {
                if (timerInterval) clearInterval(timerInterval);
                if (window.dinasTrendChart) { try { window.dinasTrendChart.destroy(); } catch(e) {} }
                if (window.dinasStatusChart) { try { window.dinasStatusChart.destroy(); } catch(e) {} }
            });
        })();
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views/admin_instansi/dashboard.blade.php ENDPATH**/ ?>