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
                <i class="fas fa-shield-alt text-sm"></i>
            </div>
            <div>
                <h2 class="font-black text-xl text-gray-800 dark:text-slate-100 leading-tight">Super Admin Dashboard</h2>
                <p class="text-xs text-gray-500 dark:text-slate-500 dark:text-slate-400 font-medium hidden md:block">Pusat Kontrol & Monitoring Portal Magang</p>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="space-y-5 md:space-y-6 font-[Inter] py-2 bg-gray-50 dark:bg-[#0f172a] text-slate-900 dark:text-slate-100 min-h-screen">

        
        
        
        <div class="relative overflow-hidden rounded-3xl bg-white dark:bg-[#161f33] text-slate-900 dark:text-white shadow-xl border border-slate-200 dark:border-slate-800/40 p-6 md:p-7">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                
                
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-teal-100 dark:bg-teal-500/20 border border-teal-200 dark:border-teal-500/30 text-teal-600 dark:text-teal-400 flex items-center justify-center text-xl shrink-0 shadow-inner">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div>
                        <h1 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                            Selamat Datang, Super Admin
                        </h1>
                        <p class="text-xs md:text-sm text-gray-500 dark:text-slate-400 font-semibold mt-1">
                            <span id="current-period-display"><?php echo e(now()->translatedFormat('l, d F Y')); ?> • <?php echo e($periodText); ?></span>
                        </p>
                    </div>
                </div>

                
                <div class="flex flex-wrap items-center gap-2">
                    <div class="inline-flex items-center bg-gray-100 dark:bg-[#0f172a] p-1.5 rounded-2xl border border-slate-200 dark:border-slate-800/60 shadow-inner overflow-x-auto max-w-full flex-nowrap">
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

        
        
        
        <?php echo $__env->make('admin_kota.dashboard._stats-grid', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        
        
        
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-5">
            
            
            <div class="xl:col-span-2 bg-white dark:bg-[#161f33] rounded-3xl border border-slate-200 dark:border-slate-800/40 p-6 flex flex-col justify-between shadow-xl">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-teal-100 dark:bg-teal-500/20 text-teal-600 dark:text-teal-400 border border-teal-200 dark:border-teal-500/30 flex items-center justify-center shadow-xs">
                                <i class="fas fa-chart-line text-base"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-black text-slate-900 dark:text-white">Tren Pendaftaran</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Jumlah pendaftar per hari dalam periode terpilih</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-teal-100 dark:bg-teal-500/20 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-500/30 tracking-wider">
                            <?php echo e(strtoupper($periodText)); ?>

                        </span>
                    </div>

                    <div class="relative w-full mt-4 min-h-[200px] sm:min-h-[280px]" style="height: 280px;">
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
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Distribusi status dalam periode</p>
                        </div>
                    </div>

                    <div class="relative flex items-center justify-center min-h-[180px] sm:min-h-[210px]" style="height: 210px;">
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

        
        
        
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-5">
            
            <div class="xl:col-span-2 bg-white dark:bg-[#161f33] rounded-3xl border border-slate-200 dark:border-slate-800/40 overflow-hidden shadow-xl">
                <div class="p-5 border-b border-slate-200 dark:border-slate-800/60 flex items-center justify-between bg-white dark:bg-[#161f33]">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-teal-500/20 text-teal-400 flex items-center justify-center shadow-xs border border-teal-500/30">
                            <i class="fas fa-building text-sm"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-900 dark:text-white">Statistik Pelamar per Instansi</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Distribusi peminat magang berdasarkan dinas</p>
                        </div>
                    </div>
                    <a href="<?php echo e(route('admin.laporan.peserta_global')); ?>" class="text-xs text-teal-600 dark:text-teal-400 bg-teal-50 dark:bg-teal-500/10 px-3 py-1.5 rounded-xl hover:bg-teal-100 dark:hover:bg-teal-500/20 font-bold transition border border-teal-200 dark:border-teal-500/30">
                        <i class="fas fa-external-link-alt text-[10px] mr-1"></i> Lihat Semua
                    </a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-100 dark:bg-[#0f172a] text-slate-500 dark:text-slate-400 text-[10px] font-black uppercase tracking-wider border-b border-slate-200 dark:border-slate-800/60">
                            <tr>
                                <th class="px-5 py-3.5 w-14">No</th>
                                <th class="px-5 py-3.5">Nama Instansi</th>
                                <th class="px-5 py-3.5 text-right w-32">Persentase</th>
                                <th class="px-5 py-3.5 text-right w-32">Total Pelamar</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800/60 text-xs font-semibold">
                            <?php $__empty_1 = true; $__currentLoopData = $instansiStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $instansi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $percentage = $maxPelamar > 0 ? ($instansi->applications_count / $maxPelamar) * 100 : 0;
                                ?>
                                <tr class="hover:bg-slate-100 dark:hover:bg-slate-800/40 transition">
                                    <td class="px-5 py-3.5 text-slate-500 font-bold">
                                        <?php echo e($instansiStats->firstItem() + $index); ?>

                                    </td>
                                    <td class="px-5 py-3.5 pr-8">
                                        <p class="text-slate-700 dark:text-slate-200 font-bold"><?php echo e($instansi->nama_dinas); ?></p>
                                        <div class="w-full bg-slate-200 dark:bg-slate-900 rounded-full h-1.5 mt-2 overflow-hidden border border-slate-200 dark:border-slate-800/60">
                                            <div class="bg-teal-400 h-1.5 rounded-full transition-all duration-500" style="width: <?php echo e($percentage); ?>%"></div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5 text-right text-slate-500 dark:text-slate-400 font-bold">
                                        <?php echo e(number_format($percentage, 1)); ?>%
                                    </td>
                                    <td class="px-5 py-3.5 text-right">
                                        <span class="inline-flex items-center justify-center px-3 py-1 rounded-xl text-xs font-black bg-teal-50 dark:bg-teal-500/10 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-500/30 min-w-[56px]">
                                            <?php echo e($instansi->applications_count); ?>

                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                                        Belum ada data instansi.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if($instansiStats->hasPages()): ?>
                <div class="p-4 border-t border-slate-200 dark:border-slate-800/60 bg-gray-100 dark:bg-[#0f172a]">
                    <?php echo e($instansiStats->links()); ?>

                </div>
                <?php endif; ?>
            </div>

            
            <div class="space-y-5">
                
                <div class="bg-white dark:bg-[#161f33] rounded-3xl border border-slate-200 dark:border-slate-800/40 overflow-hidden shadow-xl">
                    <div class="p-5 border-b border-slate-200 dark:border-slate-800/60 flex items-center justify-between bg-white dark:bg-[#161f33]">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-teal-500/20 text-teal-400 flex items-center justify-center border border-teal-500/30">
                                <i class="fas fa-plus-circle text-xs"></i>
                            </div>
                            <h3 class="text-sm font-black text-slate-900 dark:text-white">Instansi Terbaru</h3>
                        </div>
                        <a href="<?php echo e(route('admin.instansi.index')); ?>" class="text-[10px] text-teal-600 dark:text-teal-400 bg-teal-50 dark:bg-teal-500/10 px-2.5 py-1 rounded-lg font-black transition border border-teal-200 dark:border-teal-500/30">Semua</a>
                    </div>
                    <div class="divide-y divide-slate-200 dark:divide-slate-800/60">
                        <?php $__currentLoopData = $recentInstansis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dinas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="p-4 flex items-center gap-3 hover:bg-slate-100 dark:hover:bg-slate-800/40 transition">
                            <div class="w-9 h-9 rounded-xl bg-teal-100 dark:bg-teal-500/10 border border-teal-200 dark:border-teal-500/20 text-teal-600 dark:text-teal-400 flex items-center justify-center font-bold text-xs shrink-0">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-slate-700 dark:text-slate-200 truncate"><?php echo e($dinas->nama_dinas); ?></p>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 truncate mt-0.5 font-medium">
                                    <i class="far fa-clock text-[9px]"></i> <?php echo e($dinas->created_at->diffForHumans()); ?>

                                </p>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                
                <div class="bg-white dark:bg-[#161f33] rounded-3xl border border-slate-200 dark:border-slate-800/40 p-6 shadow-xl">
                    <div class="flex items-center gap-2.5 mb-4">
                        <div class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 flex items-center justify-center border border-slate-200 dark:border-slate-800/60">
                            <i class="fas fa-server text-xs"></i>
                        </div>
                        <h3 class="text-sm font-black text-slate-900 dark:text-white">Informasi Sistem</h3>
                    </div>
                    <div class="space-y-3 text-xs font-bold">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-800/60">
                            <span class="text-slate-500 dark:text-slate-400">Framework</span>
                            <span class="text-teal-600 dark:text-teal-300 bg-teal-50 dark:bg-teal-500/10 px-2.5 py-1 rounded-lg border border-teal-200 dark:border-teal-500/30 font-black text-[11px]">v<?php echo e(app()->version()); ?></span>
                        </div>
                        <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-800/60">
                            <span class="text-slate-500 dark:text-slate-400">PHP</span>
                            <span class="text-blue-600 dark:text-blue-300 bg-blue-50 dark:bg-blue-500/10 px-2.5 py-1 rounded-lg border border-blue-200 dark:border-blue-500/30 font-black text-[11px]"><?php echo e(PHP_VERSION); ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500 dark:text-slate-400">Status Auto-Refresh</span>
                            <span class="text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 px-2.5 py-1 rounded-lg border border-emerald-200 dark:border-emerald-500/30 flex items-center gap-1.5 font-black text-[11px]">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                </span>
                                60s Active
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    
    <script>
        (function() {
            let currentPeriod = <?php echo \Illuminate\Support\Js::from($period)->toHtml() ?>;
            let countdown = 60;
            let timerInterval = null;
            let refreshInFlight = false;
            let dashboardBootScheduled = false;
            const chartJsUrl = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js';

            function loadChartJs() {
                if (typeof Chart !== 'undefined') {
                    return Promise.resolve();
                }

                if (window.adminDashboardChartPromise) {
                    return window.adminDashboardChartPromise;
                }

                window.adminDashboardChartPromise = new Promise((resolve, reject) => {
                    const script = document.createElement('script');
                    script.src = chartJsUrl;
                    script.async = true;
                    script.onload = () => resolve();
                    script.onerror = () => reject(new Error('Chart.js gagal dimuat.'));
                    document.head.appendChild(script);
                });

                return window.adminDashboardChartPromise;
            }

            function initCharts() {
                if (typeof Chart === 'undefined') {
                    console.warn('Chart.js gagal dimuat; dashboard dilanjutkan tanpa grafik.');
                    return;
                }

                // 1. TREN PENDAFTARAN (LINE CHART)
                const canvasTrend = document.getElementById('trendChart');
                if (window.adminTrendChart) {
                    try { window.adminTrendChart.destroy(); } catch(e) {}
                    window.adminTrendChart = null;
                }

                if (canvasTrend) {
                    const trendLabels = JSON.parse(canvasTrend.dataset.labels || '[]');
                    const trendValues = JSON.parse(canvasTrend.dataset.values || '[]');
                    const ctx = canvasTrend.getContext('2d');

                    const gradient = ctx.createLinearGradient(0, 0, 0, 260);
                    gradient.addColorStop(0, 'rgba(20, 184, 166, 0.4)');
                    gradient.addColorStop(1, 'rgba(20, 184, 166, 0.0)');

                    window.adminTrendChart = new Chart(ctx, {
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
                if (window.adminStatusChart) {
                    try { window.adminStatusChart.destroy(); } catch(e) {}
                    window.adminStatusChart = null;
                }

                if (canvasStatus) {
                    const statusLabels = JSON.parse(canvasStatus.dataset.labels || '[]');
                    const statusValues = JSON.parse(canvasStatus.dataset.values || '[]');

                    window.adminStatusChart = new Chart(canvasStatus.getContext('2d'), {
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
                if (refreshInFlight) return;

                refreshInFlight = true;
                const refreshIcon = document.getElementById('refresh-icon');
                if (refreshIcon) refreshIcon.classList.add('fa-spin');

                fetch(`<?php echo e(route('admin.dashboard')); ?>?period=${encodeURIComponent(period)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) {
                        throw new Error(`Dashboard request failed with status ${res.status}`);
                    }

                    return res.json();
                })
                .then(data => {
                    // Update Stat Numbers
                    const elInstansi = document.getElementById('stat-instansi');
                    const elPengguna = document.getElementById('stat-pengguna');
                    const elPendaftar = document.getElementById('stat-pendaftar');
                    const elAktif = document.getElementById('stat-aktif');
                    const elSelesai = document.getElementById('stat-selesai');
                    const elPending = document.getElementById('stat-pending');

                    if (elInstansi) elInstansi.textContent = data.totalInstansi;
                    if (elPengguna) elPengguna.textContent = data.totalUser;
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
                    if (window.adminTrendChart) {
                        window.adminTrendChart.data.labels = data.trendLabels;
                        window.adminTrendChart.data.datasets[0].data = data.trendData;
                        window.adminTrendChart.update('none');
                    }

                    // Update Donut Chart
                    if (window.adminStatusChart) {
                        window.adminStatusChart.data.labels = data.statusLabels;
                        window.adminStatusChart.data.datasets[0].data = data.statusData;
                        window.adminStatusChart.update('none');
                    }

                    // Reset Timer
                    countdown = 60;
                    const timerEl = document.getElementById('countdown-timer');
                    if (timerEl) timerEl.textContent = countdown;
                })
                .catch(err => {
                    console.error('Error fetching dashboard data:', err);
                    countdown = 60;
                })
                .finally(() => {
                    refreshInFlight = false;
                    if (refreshIcon) refreshIcon.classList.remove('fa-spin');
                });
            }

            // FILTER BUTTON EVENT LISTENERS
            function setupEventListeners() {
                document.querySelectorAll('.period-btn').forEach(btn => {
                    if (btn.dataset.dashboardListener === 'true') return;

                    btn.dataset.dashboardListener = 'true';
                    btn.addEventListener('click', function() {
                        document.querySelectorAll('.period-btn').forEach(b => b.classList.remove('active'));
                        this.classList.add('active');
                        currentPeriod = this.dataset.period;
                        fetchDashboardData(currentPeriod, true);
                    });
                });

                const refreshBtn = document.getElementById('refresh-btn');
                if (refreshBtn && refreshBtn.dataset.dashboardListener !== 'true') {
                    refreshBtn.dataset.dashboardListener = 'true';
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

            function bootDashboard() {
                if (dashboardBootScheduled) return;

                dashboardBootScheduled = true;
                loadChartJs()
                    .then(() => {
                        initCharts();
                        setupEventListeners();
                        startAutoRefreshTimer();
                    })
                    .catch((error) => {
                        console.warn('Dashboard tetap dimuat tanpa grafik:', error);
                        setupEventListeners();
                        startAutoRefreshTimer();
                    });
            }

            bootDashboard();
            document.addEventListener('DOMContentLoaded', bootDashboard);
            document.addEventListener('turbo:load', bootDashboard);

            document.addEventListener('turbo:before-cache', () => {
                dashboardBootScheduled = false;
                if (timerInterval) clearInterval(timerInterval);
                if (window.adminTrendChart) { try { window.adminTrendChart.destroy(); } catch(e) {} }
                if (window.adminStatusChart) { try { window.adminStatusChart.destroy(); } catch(e) {} }
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
<?php endif; ?>
<?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views/admin_kota/dashboard.blade.php ENDPATH**/ ?>