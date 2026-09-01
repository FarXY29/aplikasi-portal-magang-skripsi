<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\User;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    /**
     * Tampilkan halaman portal pelacakan publik
     */
    public function index(Request $request)
    {
        if ($request->filled('keyword')) {
            return $this->search($request);
        }

        return view('public.tracking');
    }

    /**
     * Cari dan lacak status permohonan magang
     */
    public function search(Request $request)
    {
        $keyword = trim($request->input('keyword', ''));

        if (empty($keyword) || mb_strlen($keyword) < 4) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan masukkan Nomor Registrasi atau Token Verifikasi lengkap Anda.',
                    'count' => 0,
                    'data' => [],
                ], 422);
            }

            return redirect()->route('tracking.index')->withErrors([
                'keyword' => 'Silakan masukkan Nomor Registrasi atau Token Verifikasi lengkap Anda.',
            ]);
        }

        $applications = Application::with(['user', 'position.instansi'])
            ->where(function ($query) use ($keyword) {
                $query->where('nomor_registrasi', $keyword)
                    ->orWhere('token_verifikasi', $keyword);
            })
            ->latest('created_at')
            ->get();

        $formattedData = $applications->map(function ($app) {
            return $this->formatTrackingData($app);
        });

        if ($request->wantsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'keyword' => $keyword,
                'count' => $applications->count(),
                'data' => $formattedData,
            ]);
        }

        return view('public.tracking', [
            'keyword' => $keyword,
            'applications' => $applications,
            'formattedApplications' => $formattedData,
            'searched' => true,
        ]);
    }

    /**
     * Format data publik dengan sensor data pribadi (masking)
     */
    private function formatTrackingData(Application $app): array
    {
        $rawName = $app->user?->name ?? 'Pemohon';
        $maskedName = $this->maskName($rawName);

        $status = $app->display_status;
        $statusLabels = [
            'pending' => [
                'label' => 'Dalam Peninjauan',
                'badge' => 'amber',
                'badgeClass' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-200/80 dark:border-amber-800/60',
                'icon' => 'fa-clock',
                'step' => 2,
                'desc' => 'Berkas pendaftaran Anda telah diterima di sistem dan sedang dalam tahap verifikasi & peninjauan kualifikasi oleh Admin Instansi terkait.'
            ],
            'menunggu' => [
                'label' => 'Daftar Tunggu (Waiting List)',
                'badge' => 'amber',
                'badgeClass' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-200/80 dark:border-amber-800/60',
                'icon' => 'fa-hourglass-half',
                'step' => 2,
                'desc' => 'Kuota instansi untuk periode yang dipilih sedang terisi penuh. Lamaran Anda masuk antrean prioritas otomatis dan akan dipromosikan begitu ada kuota yang terbuka.'
            ],
            'diterima' => [
                'label' => 'Diterima Magang',
                'badge' => 'emerald',
                'badgeClass' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border-emerald-200/80 dark:border-emerald-800/60',
                'icon' => 'fa-check-circle',
                'step' => 3,
                'desc' => 'Selamat! Permohonan magang Anda telah disetujui. Silakan masuk ke akun Anda untuk mengunduh Surat Balasan (LoA) dan ID Card resmi.'
            ],
            'belum mulai' => [
                'label' => 'Diterima (Belum Mulai)',
                'badge' => 'indigo',
                'badgeClass' => 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-800 dark:text-indigo-300 border-indigo-200/80 dark:border-indigo-800/60',
                'icon' => 'fa-calendar-check',
                'step' => 3,
                'desc' => 'Permohonan magang Anda telah disetujui. Magang akan dimulai sesuai jadwal yang telah ditentukan.'
            ],
            'ditolak' => [
                'label' => 'Tidak Diterima',
                'badge' => 'rose',
                'badgeClass' => 'bg-rose-50 dark:bg-rose-950/60 text-rose-800 dark:text-rose-300 border-rose-200/80 dark:border-rose-800/60',
                'icon' => 'fa-times-circle',
                'step' => 3,
                'desc' => $app->rejected_reason ? 'Alasan: "' . $app->rejected_reason . '"' : 'Mohon maaf, permohonan magang belum dapat diterima.'
            ],
            'selesai' => [
                'label' => 'Telah Selesai',
                'badge' => 'blue',
                'badgeClass' => 'bg-blue-50 dark:bg-blue-950/60 text-blue-800 dark:text-blue-300 border-blue-200/80 dark:border-blue-800/60',
                'icon' => 'fa-award',
                'step' => 4,
                'desc' => 'Masa magang telah berakhir. Nilai evaluasi dan sertifikat kelulusan resmi telah diterbitkan.'
            ],
            'dibatalkan' => [
                'label' => 'Dibatalkan',
                'badge' => 'slate',
                'badgeClass' => 'bg-slate-100 dark:bg-gray-800 text-slate-700 dark:text-gray-300 border-slate-200 dark:border-gray-700',
                'icon' => 'fa-ban',
                'step' => 1,
                'desc' => 'Permohonan magang ini telah dibatalkan oleh pemohon.'
            ],
        ];

        $meta = $statusLabels[$status] ?? [
            'label' => ucfirst($status),
            'badge' => 'slate',
            'badgeClass' => 'bg-slate-100 dark:bg-gray-800 text-slate-700 dark:text-gray-300 border-slate-200 dark:border-gray-700',
            'icon' => 'fa-info-circle',
            'step' => 1,
            'desc' => '-'
        ];

        return [
            'id' => $app->id,
            'nomor_registrasi' => $app->nomor_registrasi ?? ('REG-' . $app->id),
            'nama_pemohon' => $maskedName,
            'asal_instansi' => $app->user?->asal_instansi ?? '-',
            'jurusan' => $app->user?->major ?? '-',
            'instansi' => $app->position?->instansi?->nama_dinas ?? '-',
            'posisi' => $app->position?->judul_posisi ?? '-',
            'is_automatic_placement' => (bool) $app->is_automatic_placement,
            'status' => $status,
            'status_label' => $meta['label'],
            'status_badge_class' => $meta['badgeClass'],
            'status_icon' => $meta['icon'],
            'status_step' => $meta['step'],
            'status_desc' => $meta['desc'],
            'tgl_daftar' => $app->created_at ? $app->created_at->translatedFormat('d M Y H:i') : '-',
            'periode_mulai' => $app->tanggal_mulai ? \Carbon\Carbon::parse($app->tanggal_mulai)->translatedFormat('d F Y') : null,
            'periode_selesai' => $app->tanggal_selesai ? \Carbon\Carbon::parse($app->tanggal_selesai)->translatedFormat('d F Y') : null,
        ];
    }

    /**
     * Mask nama untuk melindungi privasi di portal publik (misal: "Budi Santoso" -> "B*** S******")
     */
    private function maskName(string $name): string
    {
        $words = explode(' ', trim($name));
        $maskedWords = array_map(function ($word) {
            $len = mb_strlen($word);
            if ($len <= 2) {
                return $word;
            }
            return mb_substr($word, 0, 1) . str_repeat('*', $len - 1);
        }, $words);

        return implode(' ', $maskedWords);
    }
}
