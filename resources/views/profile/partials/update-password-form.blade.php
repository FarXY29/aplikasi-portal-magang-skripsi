<section>
    <div class="border-b border-gray-100 pb-5 mb-6">
        <h3 class="font-semibold text-lg text-gray-900 flex items-center gap-2">
            <i class="fas fa-shield-alt text-teal-600"></i>
            {{ __('Keamanan & Kata Sandi') }}
        </h3>
        <p class="text-sm text-gray-500 mt-1">
            {{ __('Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.') }}
        </p>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Password Saat Ini -->
            <div class="space-y-1.5 md:col-span-2">
                <label class="flex items-center gap-1.5 text-sm leading-none font-medium text-gray-700 select-none" for="current_password">
                    <span>{{ __('Password Saat Ini') }}</span> <span class="text-red-500">*</span>
                </label>
                <input id="current_password" name="current_password" type="password" class="w-full min-w-0 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-2xs transition-all outline-none focus:border-teal-600 focus:ring-3 focus:ring-teal-600/15" autocomplete="current-password" />
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
            </div>

            <!-- Password Baru -->
            <div class="space-y-1.5">
                <label class="flex items-center gap-1.5 text-sm leading-none font-medium text-gray-700 select-none" for="password">
                    <span>{{ __('Password Baru') }}</span> <span class="text-red-500">*</span>
                </label>
                <input id="password" name="password" type="password" class="w-full min-w-0 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-2xs transition-all outline-none focus:border-teal-600 focus:ring-3 focus:ring-teal-600/15" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
            </div>

            <!-- Konfirmasi Password Baru -->
            <div class="space-y-1.5">
                <label class="flex items-center gap-1.5 text-sm leading-none font-medium text-gray-700 select-none" for="password_confirmation">
                    <span>{{ __('Konfirmasi Password Baru') }}</span> <span class="text-red-500">*</span>
                </label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="w-full min-w-0 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-2xs transition-all outline-none focus:border-teal-600 focus:ring-3 focus:ring-teal-600/15" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1" />
            </div>
        </div>

        <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3 mt-6">
            @if (session('status') === 'password-updated')
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-teal-50 border border-teal-200 text-teal-700 text-xs font-semibold shadow-2xs">
                    <i class="fas fa-check-circle text-teal-600"></i>
                    <span>{{ __('Kata sandi berhasil disimpan') }}</span>
                </div>
            @endif

            <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium transition-all focus:outline-none focus:ring-2 focus:ring-teal-600/20 text-white h-9 px-5 py-2 bg-teal-600 hover:bg-teal-700 active:scale-95 shadow-xs cursor-pointer">
                <i class="fas fa-key text-xs"></i>
                <span>{{ __('Simpan Password') }}</span>
            </button>
        </div>
    </form>
</section>