<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profil Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- 1. Form Update Informasi Dasar (Nama, Email, NIK, dll) -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- 2. Form Ganti Password (FITUR NOMOR 2) -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- 3. Perangkat Aktif -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-laptop text-teal-600"></i>
                        Perangkat Aktif
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 mb-4">
                        Daftar perangkat yang saat ini login dengan akun Anda (maksimal 3 perangkat).
                    </p>

                    <div class="space-y-3">
                        @forelse($activeSessions as $session)
                        <div class="flex items-center justify-between p-3 rounded-xl border {{ $session->session_id === $currentSessionId ? 'border-teal-300 bg-teal-50' : 'border-gray-200 bg-gray-50' }}">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full {{ $session->session_id === $currentSessionId ? 'bg-teal-100 text-teal-600' : 'bg-gray-200 text-gray-500' }} flex items-center justify-center">
                                    @if(str_contains($session->device_name ?? '', 'Phone') || str_contains($session->device_name ?? '', 'iPhone') || str_contains($session->device_name ?? '', 'Android'))
                                        <i class="fas fa-mobile-alt"></i>
                                    @elseif(str_contains($session->device_name ?? '', 'iPad') || str_contains($session->device_name ?? '', 'Tablet'))
                                        <i class="fas fa-tablet-alt"></i>
                                    @else
                                        <i class="fas fa-laptop"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">
                                        {{ $session->device_name ?? 'Perangkat Tidak Dikenal' }}
                                        @if($session->session_id === $currentSessionId)
                                            <span class="ml-1 text-xs font-bold text-teal-600 bg-teal-100 px-2 py-0.5 rounded-full">Ini Perangkat Anda</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        IP: {{ $session->ip_address ?? '-' }} · 
                                        {{ $session->last_activity_at ? $session->last_activity_at->diffForHumans() : 'Baru saja' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="text-sm text-gray-500">Tidak ada perangkat aktif.</p>
                        @endforelse
                    </div>

                    @if($activeSessions->count() > 0)
                    <div class="mt-5 pt-4 border-t border-gray-200">
                        <form method="POST" action="{{ route('profile.logout-all') }}" onsubmit="return confirm('Yakin ingin logout dari semua perangkat? Anda akan dialihkan ke halaman login.')">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2.5 bg-red-50 border border-red-200 rounded-xl text-sm font-bold text-red-600 hover:bg-red-100 hover:border-red-300 transition">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Logout dari Semua Perangkat ({{ $activeSessions->count() }})
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>

            <!-- 4. Form Hapus Akun -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>