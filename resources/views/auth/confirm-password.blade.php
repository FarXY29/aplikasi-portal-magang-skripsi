<x-guest-layout>
    <div class="flex flex-col gap-6 lg:gap-8 md:flex-row md:items-stretch max-w-6xl mx-auto my-auto px-2 sm:px-4">

        {{-- Left Card: Branding Panel (5/12) --}}
        <div class="w-full md:w-5/12 flex flex-col">
            <x-auth.branding-panel
                class="h-full"
                title="Konfirmasi Keamanan"
                subtitle="Area ini memerlukan verifikasi password Anda demi menjaga keamanan data akun sebelum melanjutkan."
            />
        </div>

        {{-- Right Card: Form Input (7/12) --}}
        <div class="w-full md:w-7/12 flex flex-col">
            <x-auth.card
                maxWidth="xl"
                heading="Konfirmasi Password"
                description="Silakan masukkan password Anda saat ini untuk melanjutkan tindakan sensitif ini."
                class="h-full"
            >
                <div class="flex items-center gap-3.5 rounded-2xl border border-amber-500/20 bg-amber-50/50 dark:bg-amber-950/30 p-4 mb-6">
                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-amber-500 text-white shadow-sm">
                        <i class="fas fa-shield-halved text-lg"></i>
                    </div>
                    <p class="text-xs font-semibold text-amber-900 dark:text-amber-300 leading-relaxed">
                        Verifikasi ini diperlukan untuk memastikan bahwa tindakan keamanan ini dilakukan oleh pemilik sah akun.
                    </p>
                </div>

                <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
                    @csrf

                    <x-auth.field
                        name="password"
                        label="Password Saat Ini"
                        type="password"
                        placeholder="Masukkan password"
                        icon="fas fa-lock"
                        autocomplete="current-password"
                        :required="true"
                        :autofocus="true"
                        :errors="$errors"
                    />

                    <div class="pt-2">
                        <x-auth.button icon="fas fa-shield-check" class="shimmer-btn">
                            Konfirmasi Password
                        </x-auth.button>
                    </div>
                </form>
            </x-auth.card>
        </div>
    </div>
</x-guest-layout>
