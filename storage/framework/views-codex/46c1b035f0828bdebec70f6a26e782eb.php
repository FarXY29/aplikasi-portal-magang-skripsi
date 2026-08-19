<?php if (isset($component)) { $__componentOriginal69dc84650370d1d4dc1b42d016d7226b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b = $attributes; } ?>
<?php $component = App\View\Components\GuestLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('guest-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\GuestLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="flex flex-col md:flex-row gap-6 max-w-6xl mx-auto my-8 px-4 sm:px-6">
        
        <div class="w-full md:w-5/12 bg-teal-600 dark:bg-teal-950/80 rounded-3xl shadow-xl overflow-hidden relative flex flex-col justify-between p-8 md:p-12 min-h-[400px] border border-teal-500/20 dark:border-teal-800/60">
            
            <div class="absolute top-0 right-0 -mt-12 -mr-12 w-48 h-48 bg-white opacity-10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-12 -ml-12 w-64 h-64 bg-teal-800 opacity-20 rounded-full blur-3xl"></div>

            <div class="relative z-10">
                <a href="<?php echo e(route('login')); ?>" class="group inline-flex items-center text-sm font-bold text-teal-100 dark:text-teal-200 hover:text-white transition">
                    <div class="w-10 h-10 rounded-full bg-teal-700/50 dark:bg-teal-900/60 flex items-center justify-center mr-3 group-hover:bg-teal-500 transition shadow-xs border border-teal-500/30 dark:border-teal-700/50">
                        <i class="fas fa-arrow-left text-sm"></i>
                    </div>
                    Kembali ke Login
                </a>
            </div>

            <div class="relative z-10 mt-10 md:mt-0 text-center md:text-left">
                <div class="w-20 h-20 bg-white/10 dark:bg-gray-800/40 rounded-2xl flex items-center justify-center mb-6 backdrop-blur-md border border-white/20 dark:border-gray-700/50 shadow-inner mx-auto md:mx-0">
                    <i class="fas fa-envelope-open-text text-3xl text-white"></i>
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-white mb-4 drop-shadow-xs">
                    Verifikasi Email
                </h1>
                <p class="text-teal-50 dark:text-teal-100/90 text-lg font-medium leading-relaxed opacity-90">
                    Satu langkah lagi! Verifikasikan alamat email Anda untuk mengaktifkan akun dan mulai mengakses Portal Magang.
                </p>
            </div>

            <div class="relative z-10 mt-12 text-center md:text-left hidden md:block">
                <p class="text-xs text-teal-200/60 dark:text-teal-300/60 font-medium">
                    &copy; <?php echo e(date('Y')); ?> Diskominfotik Banjarmasin.
                </p>
            </div>
        </div>

        <div class="w-full md:w-7/12 bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden p-8 md:p-12 border border-gray-100 dark:border-gray-700 flex flex-col justify-center">
            
            <div class="mb-6">
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100">Periksa Inbox Anda</h2>
                <p class="mt-3 text-sm text-gray-600 dark:text-gray-400 leading-relaxed font-medium">
                    <?php echo e(__('Terima kasih telah mendaftar! Sebelum memulai, silakan verifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan ke email Anda. Jika Anda tidak menerima email tersebut, kami dengan senang hati akan mengirimkan ulang.')); ?>

                </p>
            </div>

            <?php if(session('status') == 'verification-link-sent'): ?>
                <div class="mb-6 bg-teal-50 dark:bg-teal-950/60 border border-teal-200 dark:border-teal-800/60 text-teal-800 dark:text-teal-300 px-4 py-3 rounded-xl font-medium text-sm">
                    <i class="fas fa-check-circle mr-2"></i> <?php echo e(__('Tautan verifikasi baru telah berhasil dikirim ke alamat email Anda.')); ?>

                </div>
            <?php elseif(session('status')): ?>
                <div class="mb-6 bg-teal-50 dark:bg-teal-950/60 border border-teal-200 dark:border-teal-800/60 text-teal-800 dark:text-teal-300 px-4 py-3 rounded-xl font-medium text-sm">
                    <i class="fas fa-info-circle mr-2"></i> <?php echo e(session('status')); ?>

                </div>
            <?php endif; ?>

            <?php if(Auth::check()): ?>
                <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <form method="POST" action="<?php echo e(route('verification.send')); ?>" class="w-full sm:w-auto">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-full sm:w-auto py-3.5 px-6 border border-transparent rounded-xl shadow-md text-xs sm:text-sm font-extrabold text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition uppercase tracking-wide">
                            KIRIM ULANG EMAIL VERIFIKASI <i class="fas fa-paper-plane ml-2"></i>
                        </button>
                    </form>

                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="w-full sm:w-auto text-center">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-rose-600 dark:hover:text-rose-400 transition underline">
                            <?php echo e(__('Keluar Akun (Log Out)')); ?>

                        </button>
                    </form>
                </div>
            <?php else: ?>
                <div class="mt-6 border-t border-gray-100 dark:border-gray-700 pt-6">
                    <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200 mb-2">Belum Menerima Email Verifikasi?</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-4 font-medium">Masukkan email Anda yang terdaftar untuk mengirim ulang tautan verifikasi akun:</p>
                    
                    <form method="POST" action="<?php echo e(route('verification.send.guest')); ?>" class="space-y-4">
                        <?php echo csrf_field(); ?>
                        <div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="far fa-envelope text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <input id="email" name="email" type="email" required
                                    class="block w-full pl-11 pr-4 py-3.5 border border-gray-300 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 transition placeholder-gray-400 dark:placeholder-gray-500 shadow-xs font-medium"
                                    placeholder="nama@contoh.com" value="<?php echo e(old('email')); ?>">
                            </div>
                            <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('email'),'class' => 'mt-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('email')),'class' => 'mt-1']); ?>
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

                        <button type="submit" class="w-full py-3.5 px-6 border border-transparent rounded-xl shadow-md text-xs sm:text-sm font-extrabold text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition uppercase tracking-wide">
                            KIRIM ULANG LINK VERIFIKASI <i class="fas fa-paper-plane ml-2"></i>
                        </button>
                    </form>
                </div>
            <?php endif; ?>

        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $attributes = $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $component = $__componentOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views/auth/verify-email.blade.php ENDPATH**/ ?>