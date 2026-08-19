<nav x-data="{ mobileMenuOpen: false, scrolled: false }" 
     x-init="scrolled = ((window.scrollY || window.pageYOffset) > 20)"
     @scroll.window="scrolled = ((window.scrollY || window.pageYOffset) > 20)"
     :class="scrolled ? 'bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl shadow-md border-b border-slate-200/50 dark:border-gray-800 py-3' : 'bg-transparent py-5 sm:py-6'"
     class="fixed w-full top-0 z-50 transition-all duration-300 ease-in-out">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="flex justify-between h-14 sm:h-16 items-center w-full">
            <!-- Brand Logo -->
            <a href="<?php echo e(url('/')); ?>" class="flex items-center gap-3 group focus:outline-none">
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-2 shadow-sm border border-slate-200/60 dark:border-gray-700/50 flex items-center justify-center shrink-0 transition-transform duration-300 group-hover:scale-105">
                    <?php if (isset($component)) { $__componentOriginal8892e718f3d0d7a916180885c6f012e7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8892e718f3d0d7a916180885c6f012e7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.application-logo','data' => ['class' => 'w-8 h-8 sm:w-9 sm:h-9 fill-current text-teal-600 dark:text-teal-400']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('application-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-8 h-8 sm:w-9 sm:h-9 fill-current text-teal-600 dark:text-teal-400']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8892e718f3d0d7a916180885c6f012e7)): ?>
<?php $attributes = $__attributesOriginal8892e718f3d0d7a916180885c6f012e7; ?>
<?php unset($__attributesOriginal8892e718f3d0d7a916180885c6f012e7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8892e718f3d0d7a916180885c6f012e7)): ?>
<?php $component = $__componentOriginal8892e718f3d0d7a916180885c6f012e7; ?>
<?php unset($__componentOriginal8892e718f3d0d7a916180885c6f012e7); ?>
<?php endif; ?>
                </div>
                <div class="flex flex-col">
                    <span class="text-base sm:text-lg font-black leading-none tracking-tight uppercase transition-colors duration-300 font-display" 
                          :class="scrolled ? 'text-slate-900 dark:text-white' : 'text-white group-hover:text-teal-200'">SiMagang</span>
                    <span class="text-[9px] font-extrabold tracking-widest uppercase transition-colors duration-300 mt-1" 
                          :class="scrolled ? 'text-teal-600 dark:text-teal-400' : 'text-teal-300/90'">Kota Banjarmasin</span>
                </div>
            </a>

            <!-- Desktop Navigation Links -->
            <div class="hidden md:flex items-center gap-8">
                <a href="#lowongan" class="text-sm font-bold tracking-wide transition-colors" :class="scrolled ? 'text-slate-600 dark:text-slate-300 hover:text-teal-600 dark:hover:text-teal-400' : 'text-white/90 hover:text-white'">Cari Lowongan</a>
                <a href="#langkah" class="text-sm font-bold tracking-wide transition-colors" :class="scrolled ? 'text-slate-600 dark:text-slate-300 hover:text-teal-600 dark:hover:text-teal-400' : 'text-white/90 hover:text-white'">Alur Magang</a>
                <a href="#faq" class="text-sm font-bold tracking-wide transition-colors" :class="scrolled ? 'text-slate-600 dark:text-slate-300 hover:text-teal-600 dark:hover:text-teal-400' : 'text-white/90 hover:text-white'">FAQ</a>
                <a href="<?php echo e(url('/scan-qr')); ?>" class="text-sm font-bold tracking-wide transition-colors" :class="scrolled ? 'text-slate-600 dark:text-slate-300 hover:text-teal-600 dark:hover:text-teal-400' : 'text-white/90 hover:text-white'">Scan QR</a>

                <div class="h-5 w-[1px]" :class="scrolled ? 'bg-slate-200 dark:bg-gray-700' : 'bg-white/20'"></div>

                <?php if(Route::has('login')): ?>
                    <?php if(auth()->guard()->check()): ?>
                        <div class="flex items-center gap-3">
                            <a href="<?php echo e(url('/dashboard')); ?>" class="bg-teal-600 hover:bg-teal-700 text-white px-5 py-2.5 rounded-2xl font-bold text-xs sm:text-sm shadow-md transition-all">
                                <i class="fas fa-columns mr-2"></i>Dashboard Saya
                            </a>
                            <?php if (isset($component)) { $__componentOriginal2090438866f3dcdb76cd8b070bcc302d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2090438866f3dcdb76cd8b070bcc302d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.theme-toggle','data' => ['class' => 'p-2.5 text-slate-400 hover:text-teal-600 dark:text-gray-400 dark:hover:text-white rounded-xl bg-slate-100 dark:bg-gray-800 border border-slate-200/50 dark:border-gray-700/50']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('theme-toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'p-2.5 text-slate-400 hover:text-teal-600 dark:text-gray-400 dark:hover:text-white rounded-xl bg-slate-100 dark:bg-gray-800 border border-slate-200/50 dark:border-gray-700/50']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2090438866f3dcdb76cd8b070bcc302d)): ?>
<?php $attributes = $__attributesOriginal2090438866f3dcdb76cd8b070bcc302d; ?>
<?php unset($__attributesOriginal2090438866f3dcdb76cd8b070bcc302d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2090438866f3dcdb76cd8b070bcc302d)): ?>
<?php $component = $__componentOriginal2090438866f3dcdb76cd8b070bcc302d; ?>
<?php unset($__componentOriginal2090438866f3dcdb76cd8b070bcc302d); ?>
<?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="flex items-center gap-3">
                            <a href="<?php echo e(route('login')); ?>" class="px-4 py-2.5 text-xs sm:text-sm font-bold transition-all rounded-2xl" :class="scrolled ? 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-gray-800' : 'text-white hover:bg-white/10'">Masuk</a>
                            <?php if(Route::has('register')): ?>
                                <a href="<?php echo e(route('register')); ?>" class="bg-white text-teal-800 hover:bg-teal-50 px-5 py-2.5 rounded-2xl font-extrabold text-xs sm:text-sm shadow-sm transition-all border border-slate-100">Daftar Sekarang</a>
                            <?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginal2090438866f3dcdb76cd8b070bcc302d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2090438866f3dcdb76cd8b070bcc302d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.theme-toggle','data' => ['class' => 'p-2.5 text-slate-400 hover:text-teal-600 dark:text-gray-400 dark:hover:text-white rounded-xl bg-slate-100 dark:bg-gray-800 border border-slate-200/50 dark:border-gray-700/50']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('theme-toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'p-2.5 text-slate-400 hover:text-teal-600 dark:text-gray-400 dark:hover:text-white rounded-xl bg-slate-100 dark:bg-gray-800 border border-slate-200/50 dark:border-gray-700/50']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2090438866f3dcdb76cd8b070bcc302d)): ?>
<?php $attributes = $__attributesOriginal2090438866f3dcdb76cd8b070bcc302d; ?>
<?php unset($__attributesOriginal2090438866f3dcdb76cd8b070bcc302d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2090438866f3dcdb76cd8b070bcc302d)): ?>
<?php $component = $__componentOriginal2090438866f3dcdb76cd8b070bcc302d; ?>
<?php unset($__componentOriginal2090438866f3dcdb76cd8b070bcc302d); ?>
<?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <!-- Mobile Drawer Button -->
            <div class="md:hidden flex items-center gap-2">
                <?php if (isset($component)) { $__componentOriginal2090438866f3dcdb76cd8b070bcc302d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2090438866f3dcdb76cd8b070bcc302d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.theme-toggle','data' => ['class' => 'p-2 text-slate-400 hover:text-teal-600 dark:text-gray-400 dark:hover:text-white rounded-xl bg-white/10']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('theme-toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'p-2 text-slate-400 hover:text-teal-600 dark:text-gray-400 dark:hover:text-white rounded-xl bg-white/10']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2090438866f3dcdb76cd8b070bcc302d)): ?>
<?php $attributes = $__attributesOriginal2090438866f3dcdb76cd8b070bcc302d; ?>
<?php unset($__attributesOriginal2090438866f3dcdb76cd8b070bcc302d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2090438866f3dcdb76cd8b070bcc302d)): ?>
<?php $component = $__componentOriginal2090438866f3dcdb76cd8b070bcc302d; ?>
<?php unset($__componentOriginal2090438866f3dcdb76cd8b070bcc302d); ?>
<?php endif; ?>
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="p-2.5 rounded-xl transition focus:outline-none" :class="scrolled ? 'text-slate-800 dark:text-white' : 'text-white'">
                    <i class="fas" :class="mobileMenuOpen ? 'fa-times text-lg' : 'fa-bars text-lg'"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Drawer Side Menu -->
    <div x-show="mobileMenuOpen" 
         x-cloak 
         @click.outside="mobileMenuOpen = false" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="md:hidden bg-white dark:bg-gray-900 border-t border-slate-100 dark:border-gray-800 shadow-2xl px-5 py-6 space-y-3 absolute w-full left-0 top-full rounded-b-3xl">
        <a href="#lowongan" @click="mobileMenuOpen = false" class="flex items-center px-4 py-3 text-sm font-bold text-slate-800 dark:text-slate-200 bg-slate-50 dark:bg-gray-800 rounded-xl hover:text-teal-600">
            <i class="fas fa-search text-teal-600 mr-3"></i> Cari Lowongan Magang
        </a>
        <a href="#langkah" @click="mobileMenuOpen = false" class="flex items-center px-4 py-3 text-sm font-bold text-slate-800 dark:text-slate-200 bg-slate-50 dark:bg-gray-800 rounded-xl hover:text-teal-600">
            <i class="fas fa-route text-teal-600 mr-3"></i> Alur Pendaftaran
        </a>
        <a href="#faq" @click="mobileMenuOpen = false" class="flex items-center px-4 py-3 text-sm font-bold text-slate-800 dark:text-slate-200 bg-slate-50 dark:bg-gray-800 rounded-xl hover:text-teal-600">
            <i class="fas fa-question-circle text-teal-600 mr-3"></i> FAQ & Bantuan
        </a>
        <a href="<?php echo e(url('/scan-qr')); ?>" id="scan-qr-btn"@click="mobileMenuOpen = false" class="flex items-center px-4 py-3 text-sm font-bold text-slate-800 dark:text-slate-200 bg-slate-50 dark:bg-gray-800 rounded-xl hover:text-teal-600">
            <i class="fas fa-qrcode text-teal-600 mr-3"></i> Scan QR
        </a>
        <div class="pt-3 border-t border-slate-100 dark:border-gray-800 flex flex-col gap-2">
            <?php if(auth()->guard()->check()): ?>
                <a href="<?php echo e(url('/dashboard')); ?>" class="w-full text-center py-3 bg-teal-600 text-white rounded-xl font-bold text-sm shadow-md">Dashboard Saya</a>
            <?php else: ?>
                <a href="<?php echo e(route('login')); ?>" class="w-full text-center py-3 bg-slate-100 dark:bg-gray-800 text-slate-800 dark:text-white rounded-xl font-bold text-sm">Masuk</a>
                <a href="<?php echo e(route('register')); ?>" class="w-full text-center py-3 bg-teal-600 text-white rounded-xl font-bold text-sm shadow-md">Daftar Akun Baru</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views\public\welcome\_navbar.blade.php ENDPATH**/ ?>