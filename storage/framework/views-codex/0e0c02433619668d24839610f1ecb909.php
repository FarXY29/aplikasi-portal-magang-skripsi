<section>
    <div class="border-b border-gray-100 dark:border-gray-700 pb-5 mb-6">
        <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-100 flex items-center gap-2">
            <i class="fas fa-user-circle text-teal-600 dark:text-teal-400"></i>
            <?php echo e(__('Informasi Profil')); ?>

        </h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            <?php echo e(__("Perbarui foto profil, informasi dasar akun, dan data akademik atau instansi Anda.")); ?>

        </p>
    </div>

    <form id="send-verification" method="post" action="<?php echo e(route('verification.send')); ?>">
        <?php echo csrf_field(); ?>
    </form>

    <form method="post" enctype="multipart/form-data" action="<?php echo e(route('profile.update')); ?>" class="space-y-6">
        <?php echo csrf_field(); ?>
        <?php echo method_field('patch'); ?>

        <!-- Foto Profil dengan Preview Langsung -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5 p-5 rounded-xl bg-gray-50 dark:bg-gray-900/80 border border-gray-200 dark:border-gray-700/60 mb-6">
            <div class="relative flex shrink-0 overflow-hidden rounded-full w-20 h-20 border-2 border-white dark:border-gray-800 shadow-md ring-2 ring-teal-500/20 dark:ring-teal-500/40">
                <?php if($user->photo && \Illuminate\Support\Facades\Storage::exists('public/' . $user->photo)): ?>
                    <img id="profile-preview" src="<?php echo e(asset('storage/' . $user->photo)); ?>" alt="Foto Profil" class="h-full w-full object-cover">
                <?php else: ?>
                    <img id="profile-preview" src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($user->name)); ?>&color=0D9488&background=CCFBF1&size=128" alt="Foto Profil Default" class="h-full w-full object-cover">
                <?php endif; ?>
            </div>

            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-3">
                    <label for="photo" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium transition-all border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-2xs hover:bg-gray-50 dark:hover:bg-gray-900 hover:text-gray-900 dark:hover:text-gray-100 text-gray-700 dark:text-gray-300 h-9 px-4 py-2 cursor-pointer active:scale-95">
                        <i class="fas fa-camera text-teal-600 dark:text-teal-400 text-xs"></i>
                        <span>Ubah Foto</span>
                        <input id="photo" name="photo" type="file" onchange="previewPhoto(event)" class="sr-only" accept="image/*" />
                    </label>
                    <?php if($user->photo): ?>
                        <span class="text-xs font-semibold text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/50 border border-teal-200/60 dark:border-teal-900/40 px-2.5 py-1 rounded-md">
                            <i class="fas fa-check-circle mr-1"></i> Foto Terupload
                        </span>
                    <?php endif; ?>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Format: JPG, PNG. Maksimal ukuran 2MB. Disarankan pas foto formal atau rapi.</p>
                <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['class' => 'mt-2','messages' => $errors->get('photo')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-2','messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('photo'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Nama -->
            <div class="space-y-1.5">
                <label class="flex items-center gap-1.5 text-sm leading-none font-medium text-gray-700 dark:text-gray-300 select-none" for="name">
                    <span><?php echo e(__('Nama Lengkap')); ?></span> <span class="text-red-500">*</span>
                </label>
                <input id="name" name="name" type="text" class="w-full min-w-0 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 shadow-2xs transition-all outline-none focus:border-teal-600 dark:focus:border-teal-500 focus:ring-3 focus:ring-teal-600/15" value="<?php echo e(old('name', $user->name)); ?>" required autofocus autocomplete="name" />
                <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['class' => 'mt-1','messages' => $errors->get('name')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-1','messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('name'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
            </div>

            <!-- Username -->
            <div class="space-y-1.5">
                <label class="flex items-center gap-1.5 text-sm leading-none font-medium text-gray-700 dark:text-gray-300 select-none" for="username">
                    <span><?php echo e(__('Username')); ?></span> <span class="text-red-500">*</span>
                </label>
                <input id="username" name="username" type="text" class="w-full min-w-0 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 shadow-2xs transition-all outline-none focus:border-teal-600 dark:focus:border-teal-500 focus:ring-3 focus:ring-teal-600/15" value="<?php echo e(old('username', $user->username)); ?>" required autocomplete="username" placeholder="username_anda" />
                <p class="text-[11px] text-gray-400 dark:text-gray-500">Digunakan untuk login alternatif selain email.</p>
                <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['class' => 'mt-1','messages' => $errors->get('username')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-1','messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('username'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
            </div>

            <!-- Email -->
            <div class="space-y-1.5 md:col-span-2">
                <label class="flex items-center gap-1.5 text-sm leading-none font-medium text-gray-700 dark:text-gray-300 select-none" for="email">
                    <span><?php echo e(__('Alamat Email')); ?></span> <span class="text-red-500">*</span>
                </label>
                <input id="email" name="email" type="email" class="w-full min-w-0 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 shadow-2xs transition-all outline-none focus:border-teal-600 dark:focus:border-teal-500 focus:ring-3 focus:ring-teal-600/15" value="<?php echo e(old('email', $user->email)); ?>" required autocomplete="username" />
                <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['class' => 'mt-1','messages' => $errors->get('email')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-1','messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('email'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>

                <?php if($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail()): ?>
                    <div class="mt-2 p-3 bg-amber-50 dark:bg-amber-950/50 border border-amber-200 dark:border-amber-900/60 rounded-lg text-xs text-amber-800 dark:text-amber-300 flex items-center justify-between">
                        <span><?php echo e(__('Alamat email Anda belum diverifikasi.')); ?></span>
                        <button form="send-verification" class="font-bold underline text-amber-900 dark:text-amber-200 hover:text-amber-700">
                            <?php echo e(__('Kirim ulang verifikasi.')); ?>

                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if($user->role === 'pembimbing_lapangan' || $user->role === 'dinas'): ?>
        <div class="border-t border-gray-100 dark:border-gray-700 pt-5 mt-5">
            <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
                <i class="fas fa-file-signature text-teal-600 dark:text-teal-400"></i>
                <span><?php echo e(__('Tanda Tangan & Paraf Digital')); ?></span>
            </h4>
            <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900/80 border border-gray-200 dark:border-gray-700/60 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex-1 space-y-1">
                    <label for="signature" class="text-sm font-medium text-gray-700 dark:text-gray-300">Upload Tanda Tangan (PNG/JPG Transparan)</label>
                    <input id="signature" name="signature" type="file" class="block w-full text-xs text-gray-500 dark:text-gray-400 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-teal-50 dark:file:bg-teal-950/60 file:text-teal-700 dark:file:text-teal-300 hover:file:bg-teal-100 dark:hover:file:bg-teal-900" accept="image/*" />
                    <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['class' => 'mt-1','messages' => $errors->get('signature')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-1','messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('signature'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                </div>
                <?php if($user->signature): ?>
                <div class="shrink-0 text-center bg-white dark:bg-gray-900 p-2 rounded-lg border border-gray-200 dark:border-gray-700 shadow-2xs">
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 font-bold uppercase mb-1">Saat Ini:</p>
                    <img src="<?php echo e(asset('storage/' . $user->signature)); ?>" alt="Signature" class="h-14 mx-auto object-contain">
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if($user->role === 'pembimbing'): ?>
        <div class="border-t border-gray-100 dark:border-gray-700 pt-5 mt-5">
            <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2">
                <i class="fas fa-briefcase text-teal-600 dark:text-teal-400"></i>
                <span><?php echo e(__('Informasi Dosen / Pembimbing')); ?></span>
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- NIDN / NIP -->
                <div class="space-y-1.5">
                    <label class="flex items-center gap-1.5 text-sm leading-none font-medium text-gray-700 dark:text-gray-300 select-none" for="nik">
                        <span><?php echo e(__('NIDN / NIP / NIK')); ?></span>
                    </label>
                    <input id="nik" name="nik" type="text" class="w-full min-w-0 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 shadow-2xs transition-all outline-none focus:border-teal-600 dark:focus:border-teal-500 focus:ring-3 focus:ring-teal-600/15" value="<?php echo e(old('nik', $user->nik)); ?>" placeholder="Nomor Induk Dosen / Pegawai" />
                    <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['class' => 'mt-1','messages' => $errors->get('nik')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-1','messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('nik'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                </div>

                <!-- No HP -->
                <div class="space-y-1.5">
                    <label class="flex items-center gap-1.5 text-sm leading-none font-medium text-gray-700 dark:text-gray-300 select-none" for="phone">
                        <span><?php echo e(__('Nomor WhatsApp')); ?></span>
                    </label>
                    <input id="phone" name="phone" type="text" class="w-full min-w-0 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 shadow-2xs transition-all outline-none focus:border-teal-600 dark:focus:border-teal-500 focus:ring-3 focus:ring-teal-600/15" value="<?php echo e(old('phone', $user->phone)); ?>" placeholder="Contoh: 0812xxxx" />
                    <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['class' => 'mt-1','messages' => $errors->get('phone')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-1','messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('phone'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                </div>

                <!-- Asal Instansi -->
                <div class="space-y-1.5 md:col-span-2">
                    <label class="flex items-center gap-1.5 text-sm leading-none font-medium text-gray-700 dark:text-gray-300 select-none" for="asal_instansi">
                        <span><?php echo e(__('Asal Sekolah / Universitas')); ?></span> <span class="text-red-500">*</span>
                    </label>
                    <input id="asal_instansi" name="asal_instansi" type="text" class="w-full min-w-0 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 shadow-2xs transition-all outline-none focus:border-teal-600 dark:focus:border-teal-500 focus:ring-3 focus:ring-teal-600/15" value="<?php echo e(old('asal_instansi', $user->asal_instansi)); ?>" placeholder="Contoh: Universitas Lambung Mangkurat" required />
                    <p class="text-xs text-teal-600 dark:text-teal-400 mt-1 font-bold"><i class="fas fa-info-circle mr-1"></i> Penting! Ketikkan nama kampus persis dengan yang diketik mahasiswa agar mereka bisa menemukan nama Anda di pilihan pembimbing.</p>
                    <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['class' => 'mt-1','messages' => $errors->get('asal_instansi')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-1','messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('asal_instansi'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if($user->role === 'peserta'): ?>
        <div class="border-t border-gray-100 dark:border-gray-700 pt-5 mt-5">
            <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2">
                <i class="fas fa-user-graduate text-teal-600 dark:text-teal-400"></i>
                <span><?php echo e(__('Informasi Akademik & Magang')); ?></span>
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- NIM / NPM -->
                <div class="space-y-1.5">
                    <label class="flex items-center gap-1.5 text-sm leading-none font-medium text-gray-700 dark:text-gray-300 select-none" for="nik">
                        <span><?php echo e(__('NIM / NPM')); ?></span>
                    </label>
                    <input id="nik" name="nik" type="text" class="w-full min-w-0 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 shadow-2xs transition-all outline-none focus:border-teal-600 dark:focus:border-teal-500 focus:ring-3 focus:ring-teal-600/15" value="<?php echo e(old('nik', $user->nik)); ?>" placeholder="Nomor Induk Mahasiswa" />
                    <p class="text-[11px] text-gray-400 dark:text-gray-500">Nomor ini akan tercetak di sertifikat magang.</p>
                    <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['class' => 'mt-1','messages' => $errors->get('nik')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-1','messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('nik'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                </div>

                <!-- No HP -->
                <div class="space-y-1.5">
                    <label class="flex items-center gap-1.5 text-sm leading-none font-medium text-gray-700 dark:text-gray-300 select-none" for="phone">
                        <span><?php echo e(__('Nomor WhatsApp')); ?></span>
                    </label>
                    <input id="phone" name="phone" type="text" class="w-full min-w-0 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 shadow-2xs transition-all outline-none focus:border-teal-600 dark:focus:border-teal-500 focus:ring-3 focus:ring-teal-600/15" value="<?php echo e(old('phone', $user->phone)); ?>" placeholder="Contoh: 0812xxxx" />
                    <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['class' => 'mt-1','messages' => $errors->get('phone')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-1','messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('phone'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                </div>

                <!-- Asal Instansi -->
                <div class="space-y-1.5">
                    <label class="flex items-center gap-1.5 text-sm leading-none font-medium text-gray-700 dark:text-gray-300 select-none" for="asal_instansi">
                        <span><?php echo e(__('Asal Sekolah / Universitas')); ?></span> <span class="text-red-500">*</span>
                    </label>
                    <input id="asal_instansi" name="asal_instansi" type="text" class="w-full min-w-0 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 shadow-2xs transition-all outline-none focus:border-teal-600 dark:focus:border-teal-500 focus:ring-3 focus:ring-teal-600/15" value="<?php echo e(old('asal_instansi', $user->asal_instansi)); ?>" placeholder="Contoh: Universitas Lambung Mangkurat" required />
                    <p class="text-[11px] text-gray-400 dark:text-gray-500">Tulis nama lengkap instansi agar Dosen dapat menemukan data Anda.</p>
                    <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['class' => 'mt-1','messages' => $errors->get('asal_instansi')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-1','messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('asal_instansi'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                </div>

                <!-- Jurusan -->
                <div class="space-y-1.5">
                    <label class="flex items-center gap-1.5 text-sm leading-none font-medium text-gray-700 dark:text-gray-300 select-none" for="major">
                        <span><?php echo e(__('Jurusan / Program Studi')); ?></span> <span class="text-red-500">*</span>
                    </label>
                    <input id="major" name="major" type="text" class="w-full min-w-0 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 shadow-2xs transition-all outline-none focus:border-teal-600 dark:focus:border-teal-500 focus:ring-3 focus:ring-teal-600/15" value="<?php echo e(old('major', $user->major)); ?>" placeholder="Contoh: Teknik Informatika" required />
                    <p class="text-[11px] text-gray-400 dark:text-gray-500">Menentukan posisi magang yang bisa Anda lamar.</p>
                    <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['class' => 'mt-1','messages' => $errors->get('major')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-1','messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('major'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                </div>

                <!-- Pemilihan Pembimbing Sekolah -->
                <div class="md:col-span-2 mt-2 p-5 bg-teal-50/60 dark:bg-teal-950/30 rounded-xl border border-teal-100 dark:border-teal-900/50">
                    <label class="flex items-center gap-2 text-sm font-bold text-teal-900 dark:text-teal-200 select-none mb-2" for="pembimbing_sekolah_id">
                        <i class="fas fa-user-tie text-teal-600 dark:text-teal-400"></i>
                        <span><?php echo e(__('Pilih Dosen / Pembimbing Kampus Anda (Opsional)')); ?></span>
                    </label>
                    
                    <?php if(isset($pembimbings) && count($pembimbings) > 0): ?>
                        <select id="pembimbing_sekolah_id" name="pembimbing_sekolah_id" class="w-full min-w-0 rounded-lg border border-teal-300 dark:border-teal-800 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 shadow-2xs focus:border-teal-600 focus:ring-3 focus:ring-teal-600/15">
                            <option value="">-- Belum ada / Menyusul --</option>
                            <?php $__currentLoopData = $pembimbings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pembimbing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($pembimbing->id); ?>" <?php echo e(old('pembimbing_sekolah_id', $user->pembimbing_sekolah_id) == $pembimbing->id ? 'selected' : ''); ?>>
                                    <?php echo e($pembimbing->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <p class="text-xs text-teal-700 dark:text-teal-400 mt-2"><i class="fas fa-check-circle mr-1"></i> Dengan memilih dosen pembimbing, beliau dapat memantau aktivitas magang (Logbook & Absensi) Anda secara real-time.</p>
                    <?php else: ?>
                        <select id="pembimbing_sekolah_id" name="pembimbing_sekolah_id" class="w-full min-w-0 rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 px-3 py-2 text-sm text-gray-500 dark:text-gray-400 shadow-2xs" disabled>
                            <option value="">Tidak ada pembimbing yang terdaftar dari institusi Anda.</option>
                        </select>
                        <p class="text-xs text-red-500 dark:text-red-400 mt-2 font-medium"><i class="fas fa-exclamation-circle mr-1"></i> Pastikan nama Asal Kampus Anda benar. Jika sudah benar namun tetap kosong, berarti dosen pembimbing Anda belum membuat akun.</p>
                    <?php endif; ?>
                    
                    <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['class' => 'mt-2','messages' => $errors->get('pembimbing_sekolah_id')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-2','messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('pembimbing_sekolah_id'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="pt-6 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-3 mt-6">
            <?php if(session('status') === 'profile-updated'): ?>
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-teal-50 dark:bg-teal-950/60 border border-teal-200 dark:border-teal-800 text-teal-700 dark:text-teal-300 text-xs font-semibold shadow-2xs">
                    <i class="fas fa-check-circle text-teal-600 dark:text-teal-400"></i>
                    <span><?php echo e(__('Perubahan berhasil disimpan')); ?></span>
                </div>
            <?php endif; ?>

            <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium transition-all focus:outline-none focus:ring-2 focus:ring-teal-600/20 text-white h-9 px-5 py-2 bg-teal-600 hover:bg-teal-700 dark:bg-teal-500 dark:hover:bg-teal-600 active:scale-95 shadow-xs cursor-pointer">
                <i class="fas fa-save text-xs"></i>
                <span><?php echo e(__('Simpan Perubahan')); ?></span>
            </button>
        </div>
    </form>
</section>

<?php $__env->startPush('scripts'); ?>
    <script>
        function previewPhoto(event) {
            var input = event.target;
            var reader = new FileReader();
            reader.onload = function(){
                var dataURL = reader.result;
                var output = document.getElementById('profile-preview');
                output.src = dataURL;
            };
            if(input.files[0]) {
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
<?php $__env->stopPush(); ?><?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views/profile/partials/update-profile-information-form.blade.php ENDPATH**/ ?>