<x-guest-layout>
    <div class="flex flex-col gap-6 lg:gap-8 md:flex-row md:items-stretch max-w-6xl mx-auto my-auto px-2 sm:px-4">

        {{-- Left Card: Branding Panel (5/12) --}}
        <div class="w-full md:w-5/12 flex flex-col">
            <x-auth.branding-panel
                class="h-full"
                title="Selamat Datang!"
                subtitle="Masuk untuk mengakses dashboard, memantau status lamaran, dan mengisi logbook harian magang Anda."
            />
        </div>

        {{-- Right Card: Form Input (7/12) --}}
        <div class="w-full md:w-7/12 flex flex-col">
            <x-auth.card maxWidth="xl" heading="Masuk ke Akun" class="h-full">
                <x-slot:description>
                    Belum punya akun? <a href="{{ route('register') }}" class="font-bold text-teal-600 hover:text-teal-700 dark:text-teal-400 dark:hover:text-teal-300 underline underline-offset-2 transition-colors">Daftar di sini</a>.
                </x-slot:description>

                @if (session('status'))
                    <x-auth.alert type="success" class="mb-5">
                        {{ session('status') }}
                    </x-auth.alert>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <x-auth.field
                        name="email"
                        label="Email / Username"
                        type="text"
                        value="{{ old('email') }}"
                        placeholder="Masukkan email atau username"
                        icon="far fa-envelope"
                        :required="true"
                        :autofocus="true"
                        :errors="$errors"
                    />

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="auth-label !mb-0">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-bold text-teal-600 transition hover:text-teal-800 dark:text-teal-400 dark:hover:text-teal-300">
                                    Lupa Password?
                                </a>
                            @endif
                        </div>
                        <x-auth.field
                            name="password"
                            type="password"
                            placeholder="Masukkan password"
                            icon="fas fa-lock"
                            autocomplete="current-password"
                            :required="true"
                            :errors="$errors"
                        />
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <label for="remember_me" class="group inline-flex cursor-pointer items-center select-none">
                            <input
                                id="remember_me"
                                type="checkbox"
                                name="remember"
                                class="h-4 w-4 cursor-pointer rounded border-gray-300 bg-white text-teal-600 shadow-sm focus:ring-teal-500 dark:border-gray-700 dark:bg-gray-900 dark:focus:ring-offset-gray-900 transition"
                            >
                            <span class="ml-2.5 text-xs sm:text-sm font-semibold text-gray-600 transition group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-gray-200">
                                Ingat saya di perangkat ini
                            </span>
                        </label>
                    </div>

                    <div class="pt-2">
                        <x-auth.button icon="fas fa-sign-in-alt" class="shimmer-btn">
                            Masuk Sekarang
                        </x-auth.button>
                    </div>
                </form>
            </x-auth.card>
        </div>
    </div>
</x-guest-layout>