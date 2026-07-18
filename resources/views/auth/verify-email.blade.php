<x-guest-layout>
    <div class="flex flex-col md:flex-row gap-6 max-w-6xl mx-auto my-8 px-4 sm:px-6">
        
        <div class="w-full md:w-5/12 bg-teal-600 rounded-3xl shadow-xl overflow-hidden relative flex flex-col justify-between p-8 md:p-12 min-h-[400px]">
            
            <div class="absolute top-0 right-0 -mt-12 -mr-12 w-48 h-48 bg-white opacity-10 rounded-full blur-3xl"></div>
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
                <div class="w-20 h-20 bg-white/10 rounded-2xl flex items-center justify-center mb-6 backdrop-blur-md border border-white/20 shadow-inner mx-auto md:mx-0">
                    <i class="fas fa-envelope-open-text text-3xl text-white"></i>
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-white mb-4 drop-shadow-md">
                    Verifikasi Email
                </h1>
                <p class="text-teal-50 text-lg font-medium leading-relaxed opacity-90">
                    Satu langkah lagi! Verifikasikan alamat email Anda untuk mengaktifkan akun dan mulai mengakses Portal Magang.
                </p>
            </div>

            <div class="relative z-10 mt-12 text-center md:text-left hidden md:block">
                <p class="text-xs text-teal-200/60 font-medium">
                    &copy; {{ date('Y') }} Diskominfotik Banjarmasin.
                </p>
            </div>
        </div>

        <div class="w-full md:w-7/12 bg-white rounded-3xl shadow-xl overflow-hidden p-8 md:p-12 border border-gray-100 flex flex-col justify-center">
            
            <div class="mb-6">
                <h2 class="text-3xl font-extrabold text-gray-900">Periksa Inbox Anda</h2>
                <p class="mt-3 text-sm text-gray-600 leading-relaxed">
                    {{ __('Terima kasih telah mendaftar! Sebelum memulai, silakan verifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan ke email Anda. Jika Anda tidak menerima email tersebut, kami dengan senang hati akan mengirimkan ulang.') }}
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-6 bg-teal-50 border border-teal-200 text-teal-800 px-4 py-3 rounded-xl font-medium text-sm">
                    <i class="fas fa-check-circle mr-2"></i> {{ __('Tautan verifikasi baru telah berhasil dikirim ke alamat email Anda.') }}
                </div>
            @elseif (session('status'))
                <div class="mb-6 bg-teal-50 border border-teal-200 text-teal-800 px-4 py-3 rounded-xl font-medium text-sm">
                    <i class="fas fa-info-circle mr-2"></i> {{ session('status') }}
                </div>
            @endif

            @if (Auth::check())
                <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <form method="POST" action="{{ route('verification.send') }}" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto py-3.5 px-6 border border-transparent rounded-xl shadow-md shadow-teal-100 text-sm font-extrabold text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition transform hover:-translate-y-0.5 tracking-wide">
                            KIRIM ULANG EMAIL VERIFIKASI <i class="fas fa-paper-plane ml-2"></i>
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto text-center">
                        @csrf
                        <button type="submit" class="text-sm font-bold text-gray-500 hover:text-red-600 transition underline">
                            {{ __('Keluar Akun (Log Out)') }}
                        </button>
                    </form>
                </div>
            @else
                <div class="mt-6 border-t border-gray-100 pt-6">
                    <h3 class="text-sm font-bold text-gray-800 mb-2">Belum Menerima Email Verifikasi?</h3>
                    <p class="text-xs text-gray-500 mb-4">Masukkan email Anda yang terdaftar untuk mengirim ulang tautan verifikasi akun:</p>
                    
                    <form method="POST" action="{{ route('verification.send.guest') }}" class="space-y-4">
                        @csrf
                        <div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="far fa-envelope text-gray-400"></i>
                                </div>
                                <input id="email" name="email" type="email" required
                                    class="block w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm bg-gray-50 focus:bg-white transition placeholder-gray-400 shadow-sm"
                                    placeholder="nama@contoh.com" value="{{ old('email') }}">
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-1" />
                        </div>

                        <button type="submit" class="w-full py-3.5 px-6 border border-transparent rounded-xl shadow-md shadow-teal-100 text-sm font-extrabold text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition transform hover:-translate-y-0.5 tracking-wide">
                            KIRIM ULANG LINK VERIFIKASI <i class="fas fa-paper-plane ml-2"></i>
                        </button>
                    </form>
                </div>
            @endif

        </div>
    </div>
</x-guest-layout>
