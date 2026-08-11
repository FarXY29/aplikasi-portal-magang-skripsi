<x-guest-layout>
    <div
        x-data="{
            role: '{{ old('role', 'peserta') }}',
            password: '',
            get passwordStrength() {
                if (!this.password) return 0;
                let score = 0;
                if (this.password.length >= 8) score++;
                if (/[A-Z]/.test(this.password)) score++;
                if (/[0-9]/.test(this.password)) score++;
                if (/[^A-Za-z0-9]/.test(this.password)) score++;
                return score;
            },
            get strengthText() {
                let s = this.passwordStrength;
                if (s === 1) return 'Sangat Lemah';
                if (s === 2) return 'Lemah';
                if (s === 3) return 'Sedang';
                if (s >= 4) return 'Kuat';
                return '';
            },
            get strengthWidth() {
                let s = this.passwordStrength;
                if (s === 1) return '25%';
                if (s === 2) return '50%';
                if (s === 3) return '75%';
                if (s >= 4) return '100%';
                return '0%';
            },
            get strengthColor() {
                let s = this.passwordStrength;
                if (s === 1) return 'bg-rose-500';
                if (s === 2) return 'bg-amber-500';
                if (s === 3) return 'bg-sky-500';
                if (s >= 4) return 'bg-emerald-500';
                return 'bg-gray-200 dark:bg-gray-700';
            }
        }"
        class="flex flex-col gap-6 lg:gap-8 md:flex-row md:items-stretch max-w-6xl mx-auto my-auto px-2 sm:px-4"
    >

        {{-- Left Card: Branding Panel (5/12) --}}
        <div class="w-full md:w-5/12 flex flex-col">
            <x-auth.branding-panel
                class="h-full"
                title="Daftar Akun"
                subtitle="Buat akun baru untuk memulai perjalanan karir profesional Anda bersama Pemerintah Kota Banjarmasin."
            />
        </div>

        {{-- Right Card: Form Input (7/12) --}}
        <div class="w-full md:w-7/12 flex flex-col">
            <x-auth.card maxWidth="xl" heading="Buat Akun Baru" class="h-full">
                <x-slot:description>
                    Sudah memiliki akun? <a href="{{ route('login') }}" class="font-bold text-teal-600 hover:text-teal-700 dark:text-teal-400 dark:hover:text-teal-300 underline underline-offset-2 transition-colors">Masuk di sini</a>
                </x-slot:description>

                <p class="mb-5 text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400" id="form-description">
                    Lengkapi formulir di bawah ini untuk mendaftar sebagai peserta magang atau pembimbing.
                </p>

                <form method="POST" action="{{ route('register') }}" class="space-y-4" id="registerForm">
                    @csrf

                    {{-- Dynamic Role Selector Cards --}}
                    <div>
                        <span class="auth-label">Mendaftar Sebagai</span>
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <label class="group relative cursor-pointer">
                                <input
                                    type="radio"
                                    name="role"
                                    value="peserta"
                                    x-model="role"
                                    class="peer sr-only"
                                    {{ old('role', 'peserta') == 'peserta' ? 'checked' : '' }}
                                >
                                <div class="flex items-center justify-between rounded-xl border-2 border-gray-200 bg-white/70 p-3.5 transition-all duration-300 transform active:scale-95 group-hover:border-teal-400 group-hover:bg-teal-50/50 group-hover:shadow-sm peer-checked:border-teal-500 peer-checked:bg-teal-50/80 peer-checked:shadow-md peer-checked:scale-[1.03] peer-checked:ring-2 peer-checked:ring-teal-500/25 dark:border-gray-800 dark:bg-gray-900/60 dark:group-hover:border-teal-500/50 dark:peer-checked:border-teal-500 dark:peer-checked:bg-teal-950/60 dark:peer-checked:ring-teal-400/25">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-teal-100 text-teal-700 dark:bg-teal-900/60 dark:text-teal-300 transition-transform duration-300 group-hover:scale-110 group-active:scale-95">
                                            <i class="fas fa-user-graduate text-sm"></i>
                                        </div>
                                        <div>
                                            <span class="block text-xs sm:text-sm font-extrabold text-gray-800 dark:text-gray-100">Peserta Magang</span>
                                            <span class="block text-[11px] text-gray-500 dark:text-gray-400">Siswa / Mahasiswa</span>
                                        </div>
                                    </div>
                                    <i class="fas fa-circle-check text-teal-500 opacity-0 scale-75 transition-all duration-300 peer-checked:opacity-100 peer-checked:scale-100"></i>
                                </div>
                            </label>

                            <label class="group relative cursor-pointer">
                                <input
                                    type="radio"
                                    name="role"
                                    value="pembimbing"
                                    x-model="role"
                                    class="peer sr-only"
                                    {{ old('role') == 'pembimbing' ? 'checked' : '' }}
                                >
                                <div class="flex items-center justify-between rounded-xl border-2 border-gray-200 bg-white/70 p-3.5 transition-all duration-300 transform active:scale-95 group-hover:border-teal-400 group-hover:bg-teal-50/50 group-hover:shadow-sm peer-checked:border-teal-500 peer-checked:bg-teal-50/80 peer-checked:shadow-md peer-checked:scale-[1.03] peer-checked:ring-2 peer-checked:ring-teal-500/25 dark:border-gray-800 dark:bg-gray-900/60 dark:group-hover:border-teal-500/50 dark:peer-checked:border-teal-500 dark:peer-checked:bg-teal-950/60 dark:peer-checked:ring-teal-400/25">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-teal-100 text-teal-700 dark:bg-teal-900/60 dark:text-teal-300 transition-transform duration-300 group-hover:scale-110 group-active:scale-95">
                                            <i class="fas fa-chalkboard-teacher text-sm"></i>
                                        </div>
                                        <div>
                                            <span class="block text-xs sm:text-sm font-extrabold text-gray-800 dark:text-gray-100">Pembimbing Sekolah</span>
                                            <span class="block text-[11px] text-gray-500 dark:text-gray-400">Guru / Dosen Pembimbing</span>
                                        </div>
                                    </div>
                                    <i class="fas fa-circle-check text-teal-500 opacity-0 scale-75 transition-all duration-300 peer-checked:opacity-100 peer-checked:scale-100"></i>
                                </div>
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('role')" class="mt-1.5" />
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <x-auth.field
                            name="name"
                            label="Nama Lengkap"
                            type="text"
                            value="{{ old('name') }}"
                            placeholder="Masukkan nama lengkap"
                            icon="fas fa-user"
                            :required="true"
                            :autofocus="true"
                            :errors="$errors"
                        />
                        <x-auth.field
                            name="username"
                            label="Username"
                            type="text"
                            value="{{ old('username') }}"
                            placeholder="Masukkan username"
                            icon="fas fa-at"
                            :required="true"
                            :errors="$errors"
                        />
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <x-auth.field
                            name="email"
                            label="Alamat Email"
                            type="email"
                            value="{{ old('email') }}"
                            placeholder="Masukkan alamat email"
                            icon="far fa-envelope"
                            :required="true"
                            :errors="$errors"
                        />

                        <x-auth.field
                            id="asal_instansi"
                            name="asal_instansi"
                            label="Asal Sekolah / Kampus"
                            type="text"
                            value="{{ old('asal_instansi') }}"
                            placeholder="Masukkan nama instansi"
                            icon="fas fa-building-columns"
                            :errors="$errors"
                            :required="true"
                        />
                    </div>

                    {{-- Field Khusus Peserta --}}
                    <div
                        x-show="role === 'peserta'"
                        x-transition:enter="transition-all ease-out duration-300"
                        x-transition:enter-start="opacity-0 -translate-y-2 max-h-0 overflow-hidden"
                        x-transition:enter-end="opacity-100 translate-y-0 max-h-[120px] overflow-visible"
                        x-transition:leave="transition-all ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 max-h-[120px] overflow-visible"
                        x-transition:leave-end="opacity-0 -translate-y-2 max-h-0 overflow-hidden"
                        class="overflow-hidden"
                    >
                        <x-auth.field
                            id="major"
                            name="major"
                            label="Jurusan / Program Studi"
                            type="text"
                            value="{{ old('major') }}"
                            placeholder="Masukkan jurusan"
                            icon="fas fa-graduation-cap"
                            :errors="$errors"
                            ::required="role === 'peserta'"
                        />
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="min-h-[94px]">
                            <x-auth.field
                                name="password"
                                label="Password"
                                type="password"
                                placeholder="Masukkan password"
                                icon="fas fa-lock"
                                autocomplete="new-password"
                                :required="true"
                                :errors="$errors"
                                x-model="password"
                            />
                            {{-- Real-time Password Strength Indicator --}}
                            <div x-show="password.length > 0" class="mt-2 ml-1" x-transition x-cloak>
                                <div class="flex items-center justify-between text-[10px] font-bold text-gray-500 dark:text-gray-400 mb-1">
                                    <span>Kekuatan Password:</span>
                                    <span x-text="strengthText" :class="
                                        passwordStrength === 1 ? 'text-rose-500' :
                                        passwordStrength === 2 ? 'text-amber-500' :
                                        passwordStrength === 3 ? 'text-sky-500' : 'text-emerald-500'
                                    "></span>
                                </div>
                                <div class="w-full h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                    <div class="pwd-strength-bar h-full" :class="strengthColor" :style="{ width: strengthWidth }"></div>
                                </div>
                            </div>
                        </div>

                        <x-auth.field
                            name="password_confirmation"
                            label="Konfirmasi Password"
                            type="password"
                            placeholder="Ulangi password Anda"
                            icon="fas fa-lock"
                            autocomplete="new-password"
                            :required="true"
                            :errors="$errors"
                        />
                    </div>

                    <div class="pt-3">
                        <x-auth.button icon="fas fa-user-plus" class="shimmer-btn">
                            Daftar Sekarang
                        </x-auth.button>
                    </div>
                </form>
            </x-auth.card>
        </div>
    </div>
</x-guest-layout>