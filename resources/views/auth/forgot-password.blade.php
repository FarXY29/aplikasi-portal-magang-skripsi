<x-guest-layout>
    <div class="flex flex-col gap-6 lg:gap-8 md:flex-row md:items-stretch max-w-6xl mx-auto my-auto px-2 sm:px-4">

        {{-- Left Card: Branding Panel (5/12) --}}
        <div class="w-full md:w-5/12 flex flex-col">
            <x-auth.branding-panel
                class="h-full"
                backLabel="Kembali ke Login"
                backRoute="login"
                title="Lupa Password?"
                subtitle="Jangan khawatir! Masukkan alamat email Anda yang terdaftar, dan kami akan mengirimkan tautan untuk mengatur ulang password Anda."
            />
        </div>

        {{-- Right Card: Form Input (7/12) --}}
        <div class="w-full md:w-7/12 flex flex-col">
            <x-auth.card
                maxWidth="xl"
                heading="Pemulihan Akun"
                description="Silakan masukkan email yang terdaftar pada akun Anda."
                class="h-full"
            >
                @if (session('status'))
                    <x-auth.alert type="success" class="mb-5">
                        {{ session('status') }}
                    </x-auth.alert>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <x-auth.field
                        name="email"
                        label="Alamat Email Terdaftar"
                        type="email"
                        value="{{ old('email') }}"
                        placeholder="Masukkan alamat email"
                        icon="far fa-envelope"
                        autocomplete="email"
                        :required="true"
                        :autofocus="true"
                        :errors="$errors"
                    />

                    <div class="pt-2">
                        <x-auth.button icon="fas fa-paper-plane" class="shimmer-btn">
                            Kirim Link Reset Password
                        </x-auth.button>
                    </div>
                </form>

                <div class="mt-8 border-t border-gray-100 dark:border-gray-800/80 pt-6 text-center">
                    <p class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400">
                        Sudah ingat password Anda?
                        <a href="{{ route('login') }}" class="font-bold text-teal-600 hover:text-teal-700 dark:text-teal-400 dark:hover:text-teal-300 underline underline-offset-2 transition-colors">
                            Masuk Sekarang
                        </a>
                    </p>
                </div>
            </x-auth.card>
        </div>
    </div>
</x-guest-layout>