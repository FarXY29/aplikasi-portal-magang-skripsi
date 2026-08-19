<x-guest-layout>
    <div class="flex flex-col gap-6 lg:gap-8 md:flex-row md:items-stretch max-w-6xl mx-auto my-auto px-2 sm:px-4">

        {{-- Left Card: Branding Panel (5/12) --}}
        <div class="w-full md:w-5/12 flex flex-col">
            <x-auth.branding-panel
                class="h-full"
                backLabel="Kembali ke Login"
                backRoute="login"
                title="Verifikasi Email"
                subtitle="Satu langkah lagi! Verifikasikan alamat email Anda untuk mengaktifkan akun dan mulai mengakses Portal Magang Pemko Banjarmasin."
            />
        </div>

        {{-- Right Card: Form Input (7/12) --}}
        <div class="w-full md:w-7/12 flex flex-col">
            <x-auth.card
                maxWidth="xl"
                heading="Periksa Kotak Masuk Anda"
                class="h-full"
            >
                <div class="flex items-center gap-4 rounded-2xl border border-teal-500/20 bg-teal-50/50 dark:bg-teal-950/30 p-4 mb-5">
                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-teal-600 text-white shadow-md">
                        <i class="fas fa-envelope-open-text text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-xs sm:text-sm font-extrabold text-gray-900 dark:text-gray-100">Tautan Verifikasi Dikirim</h4>
                        <p class="text-xs font-medium text-gray-600 dark:text-gray-400 leading-relaxed">
                            Kami telah mengirimkan email verifikasi. Silakan klik link di dalamnya untuk mengaktifkan akun Anda.
                        </p>
                    </div>
                </div>

                @if (session('status') == 'verification-link-sent')
                    <x-auth.alert type="success" class="mb-5">
                        {{ __('Tautan verifikasi baru telah berhasil dikirim ke alamat email Anda.') }}
                    </x-auth.alert>
                @elseif (session('status'))
                    <x-auth.alert type="info" class="mb-5">
                        {{ session('status') }}
                    </x-auth.alert>
                @endif

                @if (Auth::check())
                    <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <form method="POST" action="{{ route('verification.send') }}" class="w-full sm:w-auto">
                            @csrf
                            <x-auth.button icon="fas fa-paper-plane" class="shimmer-btn">
                                Kirim Ulang Email Verifikasi
                            </x-auth.button>
                        </form>

                        <form method="POST" action="{{ route('logout') }}" class="w-full text-center sm:w-auto">
                            @csrf
                            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center rounded-xl border border-gray-200 dark:border-gray-800 px-4 py-3 text-xs font-bold text-gray-600 dark:text-gray-400 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 dark:hover:bg-rose-950/40 dark:hover:text-rose-400 transition-all">
                                <i class="fas fa-right-from-bracket mr-2"></i>
                                {{ __('Keluar Akun (Log Out)') }}
                            </button>
                        </form>
                    </div>
                @else
                    <div class="mt-6 border-t border-gray-100 dark:border-gray-800/80 pt-6">
                        <h3 class="text-xs sm:text-sm font-extrabold text-gray-900 dark:text-gray-100">Belum Menerima Email Verifikasi?</h3>
                        <p class="mb-4 mt-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                            Masukkan email terdaftar Anda untuk mengirim ulang tautan verifikasi akun:
                        </p>

                        <form method="POST" action="{{ route('verification.send.guest') }}" class="space-y-4">
                            @csrf
                            <x-auth.field
                                name="email"
                                label="Alamat Email"
                                type="email"
                                value="{{ old('email') }}"
                                placeholder="Masukkan alamat email"
                                icon="far fa-envelope"
                                autocomplete="email"
                                :required="true"
                                :errors="$errors"
                            />
                            <x-auth.button icon="fas fa-paper-plane" class="shimmer-btn">
                                Kirim Ulang Link Verifikasi
                            </x-auth.button>
                        </form>
                    </div>
                @endif
            </x-auth.card>
        </div>
    </div>
</x-guest-layout>
