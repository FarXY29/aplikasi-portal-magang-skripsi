<x-guest-layout>
    <div class="flex flex-col md:flex-row gap-6 max-w-6xl mx-auto my-8 px-4 sm:px-6">
        
        <div class="w-full md:w-5/12 bg-teal-600 dark:bg-teal-950/80 rounded-3xl shadow-xl overflow-hidden relative flex flex-col justify-between p-8 md:p-12 min-h-[400px] border border-teal-500/20 dark:border-teal-800/60">
            
            <div class="absolute top-0 right-0 -mt-12 -mr-12 w-48 h-48 bg-white opacity-10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-12 -ml-12 w-64 h-64 bg-teal-800 opacity-20 rounded-full blur-3xl"></div>

            <div class="relative z-10">
                <a href="{{ route('home') }}" class="group inline-flex items-center text-sm font-bold text-teal-100 dark:text-teal-200 hover:text-white transition">
                    <div class="w-10 h-10 rounded-full bg-teal-700/50 dark:bg-teal-900/60 flex items-center justify-center mr-3 group-hover:bg-teal-500 transition shadow-xs border border-teal-500/30 dark:border-teal-700/50">
                        <i class="fas fa-arrow-left text-sm"></i>
                    </div>
                    Kembali ke Beranda
                </a>
            </div>

            <div class="relative z-10 mt-10 md:mt-0 text-center md:text-left">
                <div class="w-20 h-20 bg-white/10 dark:bg-gray-800/40 rounded-2xl flex items-center justify-center mb-6 backdrop-blur-md border border-white/20 dark:border-gray-700/50 shadow-inner mx-auto md:mx-0">
                    <i class="fas fa-shield-alt text-3xl text-white"></i>
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-white mb-4 drop-shadow-xs">
                    Konfirmasi Keamanan
                </h1>
                <p class="text-teal-50 dark:text-teal-100/90 text-lg font-medium leading-relaxed opacity-90">
                    Area ini memerlukan konfirmasi password Anda demi keamanan akun sebelum melanjutkan.
                </p>
            </div>

            <div class="relative z-10 mt-12 text-center md:text-left hidden md:block">
                <p class="text-xs text-teal-200/60 dark:text-teal-300/60 font-medium">
                    &copy; {{ date('Y') }} Diskominfotik Banjarmasin.
                </p>
            </div>
        </div>

        <div class="w-full md:w-7/12 bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden p-8 md:p-12 border border-gray-100 dark:border-gray-700 flex flex-col justify-center">
            
            <div class="mb-8">
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100">Konfirmasi Password</h2>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 font-medium">
                    Silakan masukkan password Anda untuk melanjutkan tindakan aman ini.
                </p>
            </div>

            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="password" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1.5 ml-1">Password Saat Ini</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <input id="password" name="password" type="password" required autocomplete="current-password" autofocus
                            class="block w-full pl-11 pr-4 py-3.5 border border-gray-300 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 transition placeholder-gray-400 dark:placeholder-gray-500 shadow-xs font-medium"
                            placeholder="••••••••">
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <button type="submit" class="w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-xl shadow-md text-sm font-extrabold text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition tracking-wide uppercase">
                    KONFIRMASI PASSWORD <i class="fas fa-shield-check ml-2"></i>
                </button>

            </form>

        </div>
    </div>
</x-guest-layout>
