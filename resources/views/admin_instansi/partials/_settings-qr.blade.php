{{-- CARD: VALIDASI DYNAMIC QR & KIOSK PRESENSI KANTOR --}}
<div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-teal-50 dark:bg-teal-950/60 flex items-center justify-center text-teal-600 dark:text-teal-400 text-xl border border-teal-100 dark:border-teal-900/50 flex-shrink-0">
                <i class="fas fa-qrcode"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Validasi Dynamic QR & Kiosk Kantor</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Integrasi kode QR dinamis berputar (30-detik) di layar kantor untuk validasi kehadiran fisik peserta.</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('dinas.kiosk.presensi') }}" target="_blank" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-xl font-bold text-xs shadow-xs transition flex items-center gap-2 active:scale-95">
                <i class="fas fa-desktop"></i> Buka Layar Kiosk
            </a>
        </div>
    </div>
    
    <div class="p-6 sm:p-8 space-y-6">
        <form action="{{ route('dinas.settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            {{-- Toggle Switch Wajib QR --}}
            <div class="flex items-start justify-between gap-4 p-5 rounded-2xl bg-slate-50 dark:bg-gray-900/60 border border-slate-200 dark:border-gray-700">
                <div class="space-y-1">
                    <label for="qr_absensi_enabled" class="text-sm font-bold text-gray-800 dark:text-gray-200 cursor-pointer">
                        Wajibkan Scan Dynamic QR Kantor saat Absen
                    </label>
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                        Bila diaktifkan, peserta magang wajib berada di depan monitor/TV kantor untuk memindai kode QR yang berotasi tiap 30 detik di samping validasi radius GPS.
                    </p>
                </div>
                <div class="flex items-center">
                    <input type="hidden" name="qr_absensi_enabled" value="0">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="qr_absensi_enabled" name="qr_absensi_enabled" value="1" {{ $instansi->qr_absensi_enabled ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-teal-600"></div>
                    </label>
                </div>
            </div>

            <div class="mt-4 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-xl font-bold text-xs uppercase tracking-wider shadow-md transition flex items-center active:scale-95">
                    <i class="fas fa-save mr-2 text-sm"></i> Simpan Kebijakan QR
                </button>
            </div>
        </form>

        {{-- Public Kiosk Secret URL Section --}}
        <div class="pt-6 border-t border-gray-100 dark:border-gray-700">
            <h4 class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2 flex items-center gap-2">
                <i class="fas fa-link text-teal-600 dark:text-teal-400"></i> Link Display Kiosk Lobi (Smart TV / Monitor Kantor)
            </h4>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                Gunakan tautan rahasia ini untuk membuka layar Kiosk di perangkat TV lobi / tablet resepsionis kantor tanpa perlu login akun admin secara permanen.
            </p>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <div class="flex-1 relative">
                    <input type="text" readonly id="kiosk-public-url" value="{{ route('kiosk.presensi.public', $instansi->kiosk_token ?? '') }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300 font-mono text-xs shadow-xs focus:outline-none">
                </div>
                <button type="button" onclick="copyKioskUrl()" id="btn-copy-kiosk" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-bold text-xs shadow-xs transition flex items-center justify-center gap-2">
                    <i class="fas fa-copy"></i> <span id="text-copy-kiosk">Salin Tautan</span>
                </button>
                <form action="{{ route('dinas.pengaturan.regenerate_kiosk') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membuat link Kiosk baru? Layar yang sedang terbuka dengan link lama akan berhenti berputar.');">
                    @csrf
                    <button type="submit" class="w-full sm:w-auto px-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-bold text-xs shadow-xs transition flex items-center justify-center gap-2">
                        <i class="fas fa-arrows-rotate"></i> Buat Token Baru
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
