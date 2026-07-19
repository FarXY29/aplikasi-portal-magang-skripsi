<x-guest-layout>
    <div class="flex flex-col md:flex-row gap-6 max-w-6xl mx-auto my-8 px-4 sm:px-6">
        
        <div class="w-full md:w-5/12 bg-teal-600 rounded-3xl shadow-xl overflow-hidden relative flex flex-col justify-between p-8 md:p-12 min-h-[400px]">
            
            <div class="absolute top-0 right-0 -mt-12 -mr-12 w-48 h-48 bg-white dark:bg-gray-800 opacity-10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-12 -ml-12 w-64 h-64 bg-teal-800 opacity-20 rounded-full blur-3xl"></div>

            <div class="relative z-10">
                <a href="{{ route('login') }}" class="group inline-flex items-center text-sm font-bold text-teal-100 hover:text-white transition">
                    <div class="w-10 h-10 rounded-full bg-teal-700/50 flex items-center justify-center mr-3 group-hover:bg-teal-500 transition shadow-sm border border-teal-500/30">
                        <i class="fas fa-arrow-left text-sm"></i>
                    </div>
                    Kembali ke Login
                </a>
            </div>

            <div class="relative z-10 mt-10 md:mt-0 text-center md:text-left">
                <div class="w-20 h-20 bg-white dark:bg-gray-800/10 rounded-2xl flex items-center justify-center mb-6 backdrop-blur-md border border-white/20 shadow-inner mx-auto md:mx-0">
                    <i class="fas fa-lock text-3xl text-white"></i>
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-white mb-4 drop-shadow-md">
                    Password Baru
                </h1>
                <p class="text-teal-50 text-lg font-medium leading-relaxed opacity-90">
                    Buat password baru yang kuat dan aman untuk akun Portal Magang Anda.
                </p>
            </div>

            <div class="relative z-10 mt-12 text-center md:text-left hidden md:block">
                <p class="text-xs text-teal-200/60 font-medium">
                    &copy; {{ date('Y') }} Diskominfotik Banjarmasin.
                </p>
            </div>
        </div>

        <div class="w-full md:w-7/12 bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden p-8 md:p-12 border border-gray-100 dark:border-gray-700 flex flex-col justify-center">
            
            <div class="mb-8">
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100">Atur Ulang Password</h2>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Silakan lengkapi form di bawah ini dengan password baru Anda.
                </p>
            </div>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1.5 ml-1">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="far fa-envelope text-gray-400"></i>
                        </div>
                        <input id="email" name="email" type="email" required autofocus autocomplete="username"
                            class="block w-full pl-11 pr-4 py-3.5 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm bg-gray-50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-800 transition placeholder-gray-400 shadow-sm"
                            placeholder="nama@contoh.com" :value="old('email', $request->email)">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1.5 ml-1">Password Baru</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="password" name="password" type="password" required autocomplete="new-password"
                            class="block w-full pl-11 pr-4 py-3.5 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm bg-gray-50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-800 transition placeholder-gray-400 shadow-sm"
                            placeholder="Min. 8 karakter">
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1.5 ml-1">Konfirmasi Password Baru</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-check-double text-gray-400"></i>
                        </div>
                        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                            class="block w-full pl-11 pr-4 py-3.5 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm bg-gray-50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-800 transition placeholder-gray-400 shadow-sm"
                            placeholder="Ulangi password baru">
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                </div>

                <button type="submit" class="w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-xl shadow-lg shadow-teal-200 text-sm font-extrabold text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition transform hover:-translate-y-0.5 tracking-wide">
                    SIMPAN PASSWORD BARU <i class="fas fa-save ml-2"></i>
                </button>

            </form>

        </div>
    </div>
</x-guest-layout>
