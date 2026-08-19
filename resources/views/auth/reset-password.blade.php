<x-guest-layout>
    <div
        x-data="{
            password: '',
            password_confirmation: '',
            get isMismatch() {
                return this.password && this.password_confirmation && this.password !== this.password_confirmation;
            }
        }"
        class="flex flex-col gap-6 lg:gap-8 md:flex-row md:items-stretch max-w-6xl mx-auto my-auto px-2 sm:px-4"
    >

        {{-- Left Card: Branding Panel (5/12) --}}
        <div class="w-full md:w-5/12 flex flex-col">
            <x-auth.branding-panel
                class="h-full"
                backLabel="Kembali ke Login"
                backRoute="login"
                title="Password Baru"
                subtitle="Buat password baru yang kuat dan aman untuk melindungi akun Portal Magang Anda."
            />
        </div>

        {{-- Right Card: Form Input (7/12) --}}
        <div class="w-full md:w-7/12 flex flex-col">
            <x-auth.card
                maxWidth="xl"
                heading="Atur Ulang Password"
                description="Silakan lengkapi formulir di bawah ini dengan password baru Anda."
                class="h-full"
            >
                <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                    @csrf

                    {{-- Password Reset Token --}}
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <x-auth.field
                        name="email"
                        label="Alamat Email"
                        type="email"
                        value="{{ old('email', $request->email) }}"
                        placeholder="Masukkan alamat email"
                        icon="far fa-envelope"
                        autocomplete="username"
                        :required="true"
                        :autofocus="true"
                        :errors="$errors"
                    />

                    <x-auth.field
                        name="password"
                        label="Password Baru"
                        type="password"
                        placeholder="Masukkan password baru"
                        icon="fas fa-lock"
                        autocomplete="new-password"
                        :required="true"
                        :errors="$errors"
                        x-model="password"
                    />

                    <div class="min-h-[94px]">
                        <x-auth.field
                            name="password_confirmation"
                            label="Konfirmasi Password Baru"
                            type="password"
                            placeholder="Ulangi password baru Anda"
                            icon="fas fa-shield-check"
                            autocomplete="new-password"
                            :required="true"
                            :errors="$errors"
                            x-model="password_confirmation"
                        />

                        {{-- Real-time Mismatch Alert --}}
                        <div x-show="isMismatch" x-transition x-cloak class="mt-2 ml-1 flex items-center gap-1.5 text-xs font-bold text-rose-500">
                            <i class="fas fa-circle-exclamation"></i>
                            <span>Password konfirmasi tidak cocok.</span>
                        </div>
                    </div>

                    <div class="pt-3">
                        <x-auth.button icon="fas fa-key" class="shimmer-btn">
                            Simpan Password Baru
                        </x-auth.button>
                    </div>
                </form>
            </x-auth.card>
        </div>
    </div>
</x-guest-layout>
