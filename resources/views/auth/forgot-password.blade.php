<x-guest-layout>
    <div class="px-4 py-2">
        <div class="mb-8 text-center">
            {{-- Opsi: Tambahkan ikon kunci atau logo di sini jika diinginkan --}}
            {{--
            <div class="flex justify-center mb-4">
                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-gray-500">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1115.75 5.25z" />
                </svg>
            </div>
            --}}
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ __('Lupa Password Anda?') }}
            </h2>
        </div>

        <div class="mb-6 text-sm text-gray-600 dark:text-gray-400 leading-relaxed text-center">
            {{ __('Jangan khawatir. Cukup beri tahu kami alamat email Anda dan kami akan mengirimkan tautan pengaturan ulang password yang memungkinkan Anda memilih yang baru.') }}
        </div>

        <x-auth-session-status class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-6">
                <x-input-label for="email" :value="__('Alamat Email')" />
                {{-- Menambahkan placeholder dan sedikit padding tambahan --}}
                <x-text-input id="email" class="block mt-2 w-full py-3" type="email" name="email" :value="old('email')" required autofocus placeholder="nama@contoh.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mt-6">
                <x-primary-button class="w-full justify-center py-3 text-base">
                    {{ __('Kirim Link Reset Password') }}
                </x-primary-button>
            </div>

            <div class="mt-8 text-center text-sm text-gray-600 dark:text-gray-400">
                Ingat password Anda?
                <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 font-medium hover:underline transition duration-150 ease-in-out">
                    {{ __('Kembali ke Login') }}
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>