<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use App\Services\Attendance\DynamicQrService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class KioskPresensiController extends Controller
{
    public function __construct(
        private readonly DynamicQrService $qrService,
    ) {}

    /**
     * Tampilkan halaman Kiosk Standalone untuk Monitor/TV/Tablet kantor instansi.
     */
    public function showKiosk(Request $request, ?string $kiosk_token = null)
    {
        $instansi = $this->resolveInstansi($request, $kiosk_token);

        // GET publik tidak boleh menulis baris instansi pada setiap kunjungan;
        // token hanya dibuat bila belum ada.
        if (empty($instansi->kiosk_token)) {
            $instansi->ensureKioskToken();
        }

        $tokenData = $this->qrService->generateTokenData($instansi);
        $qrSvg = $this->qrService->generateQrSvg($tokenData['token'], 320);

        // URL bertanda tangan untuk poller kiosk (layar publik tanpa sesi login).
        $liveQrSignedUrl = URL::temporarySignedRoute(
            'kiosk.live_qr',
            now()->addHours(12),
            ['kiosk_token' => $instansi->kiosk_token]
        );

        return view('kiosk.presensi', compact('instansi', 'tokenData', 'qrSvg', 'liveQrSignedUrl'));
    }

    /**
     * API Fetch live dynamic QR code baru (JSON).
     */
    public function fetchLiveQr(Request $request, ?string $kiosk_token = null)
    {
        $instansi = $this->resolveInstansi($request, $kiosk_token);

        $tokenData = $this->qrService->generateTokenData($instansi);
        $svg = $this->qrService->generateQrSvg($tokenData['token'], 320);

        return response()->json([
            'success' => true,
            'svg' => $svg,
            'remaining_seconds' => $tokenData['remaining_seconds'],
            'interval' => $tokenData['interval'],
            'expires_at' => $tokenData['expires_at'],
            'server_time' => $tokenData['server_time'],
        ]);
    }

    /**
     * Resolve instansi baik melalui public kiosk_token maupun authenticated user session.
     */
    private function resolveInstansi(Request $request, ?string $kiosk_token): Instansi
    {
        if (! empty($kiosk_token)) {
            return Instansi::where('kiosk_token', $kiosk_token)->firstOrFail();
        }

        $user = Auth::user();
        if ($user && $user->instansi) {
            return $user->instansi;
        }

        abort(403, 'Akses Kiosk tidak diizinkan. Token kantor tidak ditemukan.');
    }
}
