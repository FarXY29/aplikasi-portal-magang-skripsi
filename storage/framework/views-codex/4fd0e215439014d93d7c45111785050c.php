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
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <i class="fas fa-inbox text-teal-600 dark:text-teal-400"></i>
                <?php echo e(__('Daftar Pelamar Magang')); ?>

            </h2>
            <div class="text-sm text-gray-500 dark:text-gray-400 font-medium bg-white dark:bg-gray-800 px-4 py-1.5 rounded-full shadow-sm border border-gray-100 dark:border-gray-700">
                Total Pelamar: <span class="font-bold text-teal-600 dark:text-teal-400"><?php echo e($applicants->total()); ?></span>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans" x-data="{
        showPdfModal: false,
        pdfUrl: '',
        pdfTitle: '',
        openPdf(url, title) {
            this.pdfUrl = url;
            this.pdfTitle = title;
            this.showPdfModal = true;
        },
        showRejectModal: false,
        rejectActionUrl: '',
        rejectApplicantName: '',
        openReject(url, name) {
            this.rejectActionUrl = url;
            this.rejectApplicantName = name;
            this.showRejectModal = true;
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Tombol Kembali & Multi-Criteria Filter Bar -->
            <div class="flex flex-col gap-4 mb-6 print:hidden">
                <div class="flex justify-between items-center">
                    <a href="<?php echo e(route('dinas.dashboard')); ?>" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                        <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-sm">
                            <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                        </div>
                        Kembali ke Dashboard
                    </a>
                </div>

                <!-- Form Filter Multi-Kriteria -->
                <?php if (isset($component)) { $__componentOriginal14f469cfc51ebc3cb5d7fb0ffa2702ed = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal14f469cfc51ebc3cb5d7fb0ffa2702ed = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.filter-bar','data' => ['action' => route('dinas.pelamar'),'resetUrl' => request()->hasAny(['search', 'posisi_id', 'status']) ? route('dinas.pelamar') : null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.filter-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['action' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dinas.pelamar')),'resetUrl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->hasAny(['search', 'posisi_id', 'status']) ? route('dinas.pelamar') : null)]); ?>
                    <div class="flex-grow min-w-[200px]">
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari nama, email, no. surat..." class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 focus:border-teal-500 focus:ring-teal-500 py-2 px-3 shadow-sm font-medium bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500">
                    </div>

                    <div class="min-w-[150px]">
                        <select name="posisi_id" class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 focus:border-teal-500 focus:ring-teal-500 py-2 pl-3 pr-8 cursor-pointer shadow-sm font-bold text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-900">
                            <option value="" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100">-- Semua Posisi --</option>
                            <?php $__currentLoopData = $positions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($pos->id); ?>" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" <?php echo e(request('posisi_id') == $pos->id ? 'selected' : ''); ?>>
                                    <?php echo e($pos->judul_posisi); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="min-w-[150px]">
                        <select name="status" class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 focus:border-teal-500 focus:ring-teal-500 py-2 pl-3 pr-8 cursor-pointer shadow-sm font-bold text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-900">
                            <option value="semua" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" <?php echo e(request('status') == 'semua' || !request()->has('status') ? 'selected' : ''); ?>>Semua Status</option>
                            <option value="pending" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                            <option value="menunggu" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" <?php echo e(request('status') == 'menunggu' ? 'selected' : ''); ?>>Menunggu (Waiting List)</option>
                            <option value="diterima" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" <?php echo e(request('status') == 'diterima' ? 'selected' : ''); ?>>Diterima</option>
                            <option value="ditolak" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" <?php echo e(request('status') == 'ditolak' ? 'selected' : ''); ?>>Ditolak</option>
                            <option value="selesai" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" <?php echo e(request('status') == 'selesai' ? 'selected' : ''); ?>>Selesai</option>
                        </select>
                    </div>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal14f469cfc51ebc3cb5d7fb0ffa2702ed)): ?>
<?php $attributes = $__attributesOriginal14f469cfc51ebc3cb5d7fb0ffa2702ed; ?>
<?php unset($__attributesOriginal14f469cfc51ebc3cb5d7fb0ffa2702ed); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal14f469cfc51ebc3cb5d7fb0ffa2702ed)): ?>
<?php $component = $__componentOriginal14f469cfc51ebc3cb5d7fb0ffa2702ed; ?>
<?php unset($__componentOriginal14f469cfc51ebc3cb5d7fb0ffa2702ed); ?>
<?php endif; ?>
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
            
            <?php if(session('error')): ?>
                <?php if (isset($component)) { $__componentOriginal746de018ded8594083eb43be3f1332e1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal746de018ded8594083eb43be3f1332e1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.alert','data' => ['type' => 'error','class' => 'mb-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'error','class' => 'mb-4']); ?>
                    <?php echo e(session('error')); ?>

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

            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-100 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th scope="col" class="px-5 py-3.5 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Profil Peserta</th>
                                <th scope="col" class="px-5 py-3.5 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Periode & Posisi</th>
                                <th scope="col" class="px-5 py-3.5 text-center text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-5 py-3.5 text-right text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi / Dokumen</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700/60 text-sm">
                            <?php $__empty_1 = true; $__currentLoopData = $applicants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-teal-50/15 dark:hover:bg-gray-900/60 transition duration-150 group">
                                
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-teal-100 to-teal-200 dark:from-teal-950/60 dark:to-teal-900/60 flex items-center justify-center text-teal-700 dark:text-teal-300 font-bold border border-teal-300 dark:border-teal-800/60 shadow-xs">
                                                <?php echo e(strtoupper(substr($app->user->name, 0, 1))); ?>

                                            </div>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-bold text-gray-900 dark:text-gray-100 truncate group-hover:text-teal-600 dark:group-hover:text-teal-400 transition"><?php echo e($app->user->name); ?></div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 flex flex-col gap-0.5 mt-0.5">
                                                <span class="flex items-center"><i class="far fa-envelope mr-1.5 w-3 text-gray-400 dark:text-gray-500"></i> <?php echo e($app->user->email); ?></span>
                                                <span class="flex items-center"><i class="fas fa-phone-alt mr-1.5 w-3 text-gray-400 dark:text-gray-500"></i> <?php echo e($app->user->phone ?? '-'); ?></span>
                                                <span class="flex items-center"><i class="fas fa-university mr-1.5 w-3 text-gray-400 dark:text-gray-500"></i> <?php echo e($app->user->university?->name ?? $app->user->school?->name ?? $app->user->asal_instansi ?? '-'); ?></span>
                                                <?php if($app->letter_number): ?>
                                                    <span class="flex items-center font-bold text-teal-700 dark:text-teal-400 mt-0.5"><i class="fas fa-file-signature mr-1.5 w-3"></i> No. Surat: <?php echo e($app->letter_number); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1">
                                        <div class="text-sm font-bold text-gray-800 dark:text-gray-200 mb-1">
                                            <?php echo e($app->position->judul_posisi ?? 'Posisi Umum'); ?>

                                        </div>
                                        
                                        <?php if($app->is_automatic_placement): ?>
                                            <div class="mb-1.5">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-800/60 gap-1">
                                                    <i class="fas fa-magic text-[8px]"></i> Penempatan Otomatis
                                                </span>
                                            </div>
                                        <?php endif; ?>

                                        <?php if($app->tanggal_mulai): ?>
                                            <div class="flex items-center text-xs text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-900 border border-transparent dark:border-gray-700 px-2.5 py-1 rounded-lg w-fit font-medium">
                                                <i class="far fa-calendar-alt mr-1.5 text-gray-400 dark:text-gray-500"></i>
                                                <span><?php echo e(\Carbon\Carbon::parse($app->tanggal_mulai)->format('d M Y')); ?></span>
                                                <i class="fas fa-arrow-right mx-1.5 text-gray-300 dark:text-gray-600 text-[10px]"></i>
                                                <span><?php echo e(\Carbon\Carbon::parse($app->tanggal_selesai)->format('d M Y')); ?></span>
                                            </div>
                                            <div class="text-[10px] text-teal-600 dark:text-teal-400 font-bold mt-0.5 ml-1">
                                                Durasi: <?php echo e(\Carbon\Carbon::parse($app->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($app->tanggal_selesai))); ?> Hari
                                            </div>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-400 dark:text-gray-500 italic">Tanggal belum ditentukan</span>
                                        <?php endif; ?>

                                        <div class="mt-1">
                                            <?php if($app->position->kuota > 0): ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border border-blue-100 dark:border-blue-800/60">
                                                    Sisa Kuota: <?php echo e($app->position->kuota); ?>

                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-red-50 dark:bg-red-950/60 text-red-700 dark:text-red-400 border border-red-100 dark:border-red-800/60">
                                                    Kuota Penuh (0)
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <?php
                                        $statusLabel = $app->status instanceof \App\Enums\ApplicationStatus ? $app->status->label() : ucfirst($app->status);
                                        $statusClass = $app->status instanceof \App\Enums\ApplicationStatus ? $app->status->badgeClass() : 'bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-700';
                                        $statusIcon = $app->status instanceof \App\Enums\ApplicationStatus ? $app->status->icon() : 'fas fa-question-circle';
                                    ?>
                                     <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full border <?php echo e($statusClass); ?> items-center gap-1.5">
                                         <i class="<?php echo e($statusIcon); ?>"></i> <?php echo e($statusLabel); ?>

                                     </span>

                                    <?php if($app->status?->value == 'diterima'): ?>
                                        <div class="mt-2 text-[10px] text-gray-500 dark:text-gray-400 font-medium">
                                            Pembimbing Lapangan: <span class="text-gray-700 dark:text-gray-300 font-bold"><?php echo e($app->pembimbing_lapangan->name ?? 'Belum Ada'); ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if($app->rejected_reason && in_array($app->status?->value, ['ditolak', 'dibatalkan'])): ?>
                                        <div class="mt-1.5 text-[10px] text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/40 border border-red-100 dark:border-red-900/40 p-2 rounded-lg max-w-[180px] mx-auto truncate" title="<?php echo e($app->rejected_reason); ?>">
                                            Catatan: <?php echo e($app->rejected_reason); ?>

                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <div class="flex flex-col items-end gap-2">

                                        <?php
                                            $canAct = in_array(
                                                $app->status instanceof \App\Enums\ApplicationStatus ? $app->status->value : $app->status,
                                                ['pending', 'menunggu']
                                            );
                                        ?>

                                        <?php if($canAct): ?>
                                            
                                            <?php if(($app->status instanceof \App\Enums\ApplicationStatus ? $app->status->value : $app->status) === 'menunggu'): ?>
                                                <span class="text-[10px] font-bold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-900/60 px-2 py-0.5 rounded-full mb-0.5">
                                                    <i class="fas fa-clock mr-1"></i>Dalam Waiting List
                                                </span>
                                            <?php endif; ?>

                                            <div class="flex items-center gap-2">
                                                <?php if($app->position->kuota > 0): ?>
                                                    <form action="<?php echo e(route('dinas.pelamar.terima', $app->id)); ?>" method="POST" @submit.prevent="$dispatch('open-confirm', { message: 'Apakah Anda yakin ingin menerima peserta ini? Kuota akan dikunci dan notifikasi dikirim.', onConfirm: () => $el.submit() })">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-teal-600 dark:bg-teal-500 text-white text-xs font-bold rounded-xl hover:bg-teal-700 dark:hover:bg-teal-600 active:scale-95 transition shadow-xs" title="Terima Peserta">
                                                            <i class="fas fa-check mr-1.5"></i> Terima
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <button disabled class="inline-flex items-center px-3 py-1.5 bg-gray-100 dark:bg-gray-900 text-gray-400 dark:text-gray-500 text-xs font-bold rounded-xl cursor-not-allowed border border-gray-200 dark:border-gray-700" title="Kuota Penuh">
                                                        <i class="fas fa-ban mr-1.5"></i> Kuota Penuh
                                                    </button>
                                                <?php endif; ?>

                                                <button type="button" @click="openReject(<?php echo \Illuminate\Support\Js::from(route('dinas.pelamar.tolak', $app->id))->toHtml() ?>, <?php echo \Illuminate\Support\Js::from($app->user->name)->toHtml() ?>)" class="inline-flex items-center px-3 py-1.5 bg-white dark:bg-gray-800 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-900/60 text-xs font-bold rounded-xl hover:bg-red-50 dark:hover:bg-red-950/40 active:scale-95 transition shadow-xs" title="Tolak Peserta">
                                                    <i class="fas fa-times mr-1"></i> Tolak
                                                </button>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Tombol In-Browser PDF Viewer -->
                                        <?php if($app->surat_pengantar_path): ?>
                                            <button type="button" @click="openPdf(<?php echo \Illuminate\Support\Js::from(route('storage.access', ['type' => 'surat', 'filename' => basename($app->surat_pengantar_path)]))->toHtml() ?>, <?php echo \Illuminate\Support\Js::from('Surat Pengantar - ' . $app->user->name)->toHtml() ?>)" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 hover:underline flex items-center cursor-pointer bg-indigo-50 dark:bg-indigo-950/50 px-2.5 py-1 rounded-lg border border-indigo-100 dark:border-indigo-900/50 transition">
                                                <i class="fas fa-file-pdf mr-1.5 text-red-500"></i> Lihat Surat
                                            </button>
                                        <?php else: ?>
                                            <span class="text-[10px] text-gray-400 dark:text-gray-500 italic">Tidak ada surat</span>
                                        <?php endif; ?>

                                        <?php if(!$canAct && !$app->surat_pengantar_path): ?>
                                            <span class="text-[10px] text-gray-400 dark:text-gray-500 italic">—</span>
                                        <?php endif; ?>

                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-12">
                                    <?php if (isset($component)) { $__componentOriginal3607a477fdef7402bc742abad5df9c51 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3607a477fdef7402bc742abad5df9c51 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.empty-state','data' => ['title' => 'Tidak Ada Pelamar','description' => 'Belum ada pelamar magang yang sesuai dengan kriteria filter pencarian Anda.','icon' => 'fa-inbox','class' => 'shadow-none border-none py-6 bg-transparent']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Tidak Ada Pelamar','description' => 'Belum ada pelamar magang yang sesuai dengan kriteria filter pencarian Anda.','icon' => 'fa-inbox','class' => 'shadow-none border-none py-6 bg-transparent']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $attributes = $__attributesOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__attributesOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $component = $__componentOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__componentOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    <?php echo e($applicants->links()); ?>

                </div>
            </div>

        </div>

        <!-- Modal In-Browser PDF Viewer -->
        <div x-show="showPdfModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showPdfModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-900/80 backdrop-blur-sm" @click="showPdfModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showPdfModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-gray-100 dark:border-gray-700">
                    <div class="bg-gray-900 px-6 py-4 flex items-center justify-between text-white border-b border-gray-800">
                        <h3 class="text-sm font-bold flex items-center gap-2" x-text="pdfTitle">Surat Pengantar</h3>
                        <div class="flex items-center gap-3">
                            <a :href="pdfUrl" target="_blank" class="text-xs bg-gray-800 hover:bg-gray-700 px-3 py-1.5 rounded-lg font-bold flex items-center gap-1.5 text-gray-200 transition border border-gray-700">
                                <i class="fas fa-external-link-alt"></i> Buka Tab Baru / Download
                            </a>
                            <button type="button" @click="showPdfModal = false" class="text-gray-400 hover:text-white transition">
                                <i class="fas fa-times text-lg"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-3 bg-gray-100 dark:bg-gray-900">
                        <iframe :src="pdfUrl" class="w-full h-[75vh] rounded-2xl border border-gray-300 dark:border-gray-700 shadow-inner bg-white dark:bg-gray-900"></iframe>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Alasan Penolakan -->
        <div x-show="showRejectModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showRejectModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-900/80 backdrop-blur-sm" @click="showRejectModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showRejectModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-gray-100 dark:border-gray-700">
                    <form :action="rejectActionUrl" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="p-6 space-y-4">
                            <div class="flex items-center gap-3 text-red-600 dark:text-red-400">
                                <div class="w-10 h-10 rounded-2xl bg-red-50 dark:bg-red-950/60 border border-red-100 dark:border-red-900/40 flex items-center justify-center font-bold text-lg flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">Tolak Lamaran Magang</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400" x-text="'Peserta: ' + rejectApplicantName"></p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Alasan Penolakan / Catatan untuk Peserta <span class="text-red-500">*</span></label>
                                <textarea name="alasan" rows="3" required placeholder="Misal: Kuota periode tersebut sudah penuh, atau berkas surat pengantar tidak sesuai..." class="w-full text-xs rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:ring-red-500 focus:border-red-500 p-3 shadow-xs"></textarea>
                                <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1.5 font-medium">Alasan ini akan dikirimkan ke email peserta dan tercatat di sistem.</p>
                            </div>

                            <div class="flex justify-end gap-2 pt-3 border-t border-gray-100 dark:border-gray-700">
                                <button type="button" @click="showRejectModal = false" class="px-4 py-2 bg-gray-100 dark:bg-gray-900 hover:bg-gray-200 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300 text-xs font-bold rounded-xl transition border border-transparent dark:border-gray-700">
                                    Batal
                                </button>
                                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl transition shadow-xs flex items-center gap-1.5">
                                    <i class="fas fa-times"></i> Konfirmasi Penolakan
                                </button>
                            </div>
                        </div>
                    </form>
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
<?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views\admin_instansi\pelamar.blade.php ENDPATH**/ ?>