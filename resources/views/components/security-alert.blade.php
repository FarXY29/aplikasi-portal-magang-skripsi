@php
    $blockedAttempts = auth()->user()->recentBlockedAttempts();
@endphp

@if($blockedAttempts > 0)
<div x-data="{ show: true }" x-show="show" x-transition
     class="mb-6 rounded-xl border-2 border-amber-400 bg-gradient-to-r from-amber-50 to-orange-50 p-4 shadow-md">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center animate-pulse">
                <i class="fas fa-exclamation-triangle text-amber-600 text-lg"></i>
            </div>
        </div>
        <div class="ml-4 flex-1">
            <h3 class="text-sm font-bold text-amber-800">
                <i class="fas fa-shield-alt mr-1"></i> Peringatan Keamanan
            </h3>
            <p class="mt-1 text-sm text-amber-700">
                Ada <strong>{{ $blockedAttempts }} percobaan login</strong> ke akun Anda dari perangkat lain dalam 24 jam terakhir yang berhasil <strong>diblokir</strong>.
                Jika ini bukan Anda, segera <a href="{{ route('profile.edit') }}" class="font-bold underline hover:text-amber-900">ubah password Anda</a>.
            </p>
        </div>
        <button @click="show = false" class="ml-2 text-amber-400 hover:text-amber-600 transition flex-shrink-0">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>
@endif
