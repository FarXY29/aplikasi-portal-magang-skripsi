<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header
            title="Dashboard Pengguna"
            description="Portal Layanan Magang & Penelitian Kota Banjarmasin"
            icon="fas fa-home"
            iconColor="teal" />
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-[60vh]">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-ui.alert type="warning" title="Belum Ada Peran Aktif">
                Akun Anda saat ini belum memiliki hak akses atau peran khusus (Admin Kota, Admin Instansi, Pembimbing, atau Peserta). Silakan hubungi administrator Badan Kesatuan Bangsa dan Politik Kota Banjarmasin untuk penetapan peran.
            </x-ui.alert>

            <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 border border-gray-100 dark:border-gray-700 shadow-xs text-center space-y-4">
                <x-ui.empty-state
                    title="Menunggu Penugasan Peran"
                    description="Anda telah berhasil masuk ke sistem. Hubungi admin untuk mendapatkan akses penuh."
                    icon="fas fa-user-shield" />

                <div class="pt-4">
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-bold transition">
                            <i class="fas fa-sign-out-alt"></i> Keluar dari Akun
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
