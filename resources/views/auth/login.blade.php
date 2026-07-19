<x-guest-layout>
    <div class="flex flex-col md:flex-row gap-6 max-w-6xl mx-auto my-auto px-4 sm:px-6">
        
        <div class="w-full md:w-5/12 bg-teal-600 rounded-3xl shadow-xl overflow-hidden relative flex flex-col justify-between p-8 md:p-12 min-h-[220px] md:min-h-[460px]">
            
            <div class="absolute top-0 right-0 -mt-12 -mr-12 w-48 h-48 bg-white dark:bg-gray-800 opacity-10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-12 -ml-12 w-64 h-64 bg-teal-800 opacity-20 rounded-full blur-3xl"></div>

            <div class="relative z-10">
                <a href="{{ route('home') }}" class="group inline-flex items-center text-sm font-bold text-teal-100 hover:text-white transition">
                    <div class="w-10 h-10 rounded-full bg-teal-700/50 flex items-center justify-center mr-3 group-hover:bg-teal-500 transition shadow-sm border border-teal-500/30">
                        <i class="fas fa-arrow-left text-sm"></i>
                    </div>
                    Kembali ke Beranda
                </a>
            </div>

            <div class="relative z-10 mt-8 md:mt-0 text-center md:text-left">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-white dark:bg-gray-800/10 rounded-2xl flex items-center justify-center mb-5 backdrop-blur-md border border-white/20 shadow-inner mx-auto md:mx-0">
                    <x-application-logo class="w-10 h-10 sm:w-12 sm:h-12 fill-current text-white" />
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-white mb-3 drop-shadow-md">
                    Selamat Datang!
                </h1>
                <p class="text-teal-50 text-sm sm:text-base font-medium leading-relaxed opacity-90">
                    Masuk untuk mengakses dashboard, memantau status lamaran, dan mengisi logbook harian.
                </p>
            </div>

            <div class="relative z-10 mt-8 text-center md:text-left hidden md:block">
                <p class="text-xs text-teal-200/60 font-medium">
                    &copy; {{ date('Y') }} Diskominfotik Banjarmasin.
                </p>
            </div>
        </div>

        <div class="w-full md:w-7/12 bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden p-8 md:p-11 border border-gray-100 dark:border-gray-700 flex flex-col justify-center">
            
            <div class="mb-6">
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 leading-tight">Masuk ke Akun</h2>
                <p class="mt-1.5 text-sm text-gray-500 dark:text-gray-400">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="font-bold text-teal-600 hover:text-teal-700 hover:underline transition">
                        Daftar di sini
                    </a>
                </p>
            </div>

            <x-auth-session-status class="mb-5" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1.5 ml-1">Email / Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="far fa-envelope text-gray-400 text-base"></i>
                        </div>
                        <input id="email" name="email" type="text" required autofocus
                            class="block w-full pl-11 pr-4 py-3.5 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm bg-gray-50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-800 transition placeholder-gray-400 shadow-sm font-medium"
                            placeholder="email@contoh.com" :value="old('email')">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1.5 ml-1">
                        <label for="password" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs font-bold text-teal-600 hover:text-teal-800 transition">
                                Lupa Password?
                            </a>
                        @endif
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400 text-base"></i>
                        </div>
                        <input id="password" name="password" type="password" required autocomplete="current-password"
                            class="block w-full pl-11 pr-4 py-3.5 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm bg-gray-50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-800 transition placeholder-gray-400 shadow-sm font-medium"
                            placeholder="••••••••">
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <div class="flex items-center pt-0.5">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 dark:border-gray-600 dark:border-gray-700 text-teal-600 shadow-sm focus:ring-teal-500 w-4 h-4 cursor-pointer" name="remember">
                        <span class="ml-2.5 text-sm text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-100 dark:text-gray-100 transition font-medium">Ingat saya</span>
                    </label>
                </div>

                <div class="pt-1">
                    <button type="submit" class="w-full flex justify-center items-center py-3.5 px-6 border border-transparent rounded-xl shadow-lg shadow-teal-200 text-sm font-extrabold text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition transform hover:-translate-y-0.5 tracking-wide">
                        MASUK SEKARANG <i class="fas fa-sign-in-alt ml-2.5"></i>
                    </button>
                </div>

            </form>

            <div class="relative my-8 sm:my-9">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200 dark:border-gray-700"></div>
                </div>
                <div class="relative flex justify-center text-xs">
                    <span class="px-3.5 bg-white dark:bg-gray-800 text-gray-400 dark:text-gray-500 text-xs font-medium uppercase tracking-wider">Atau masuk dengan</span>
                </div>
            </div>

            <div>
                <a href="{{ route('google.login') }}" class="flex items-center justify-center w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:border-gray-700 rounded-xl shadow-sm text-sm font-bold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-900 hover:border-gray-400 transition transform hover:-translate-y-0.5">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="h-5 w-5 mr-3" alt="Google">
                    Lanjutkan dengan Google
                </a>
            </div>

        </div>
    </div>
</x-guest-layout>