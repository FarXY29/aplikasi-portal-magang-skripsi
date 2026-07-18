<?php

namespace App\Http\Controllers\AdminInstansi;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\DailyLog;
use App\Models\User;
use App\Services\PdfExportService;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GenericViewExport;

class ReportController extends Controller
{
    protected $reportService;
    protected $pdfService;

    public function __construct(ReportService $reportService, PdfExportService $pdfService)
    {
        $this->reportService = $reportService;
        $this->pdfService = $pdfService;
    }

    /**
     * Helper untuk handle multi-format (PDF, Excel, CSV)
     */
    protected function exportData($view, $data, $filenameBase, $paper = 'a4', $orientation = 'portrait', $format = 'pdf')
    {
        if ($format === 'excel') {
            return Excel::download(new GenericViewExport($view, $data), $filenameBase . '.xlsx');
        } elseif ($format === 'csv') {
            return Excel::download(new GenericViewExport($view, $data), $filenameBase . '.csv', \Maatwebsite\Excel\Excel::CSV);
        }
        return $this->pdfService->stream($view, $data, $filenameBase . '.pdf', $paper, $orientation);
    }

    public function laporanHub()
    {
        return view('admin_instansi.laporan_hub');
    }

    public function laporanRekap(Request $request)
    {
        $user = Auth::user();
        $data = $this->reportService->getInstansiRekapData($request, $user->instansi_id);

        return view('admin_instansi.laporan.rekap', $data);
    }

    public function printRekap(Request $request)
    {
        $user = Auth::user();
        $data = $this->reportService->getInstansiRekapData($request, $user->instansi_id, false);
        $applications = $data['applications'];
        $stats = $data['stats'];
        $instansi = $user->instansi; 

        return $this->exportData('pdf.admin_instansi.rekap_peserta', compact('applications', 'instansi', 'request', 'stats'), 'Laporan-Rekap-Peserta', 'a4', 'landscape', $request->query('format', 'pdf'));
    }

    public function laporanKinerjaMahasiswa()
    {
        $instansiId = Auth::user()->instansi_id;
        $data = $this->reportService->getKinerjaMahasiswaData($instansiId);
        return view('admin_instansi.laporan.kinerja_mahasiswa', $data);
    }

    public function printKinerjaMahasiswa(Request $request)
    {
        $instansiId = Auth::user()->instansi_id;
        $data = $this->reportService->getKinerjaMahasiswaData($instansiId);
        return $this->exportData('pdf.admin_instansi.kinerja_mahasiswa', $data, 'Laporan-Kinerja-Mahasiswa', 'a4', 'landscape', $request->query('format', 'pdf'));
    }

    public function laporanBebanPembimbing()
    {
        $data = $this->getBebanPembimbingPayload();
        return view('admin_instansi.laporan.beban_pembimbing', $data);
    }

    public function printBebanPembimbing(Request $request)
    {
        $data = $this->getBebanPembimbingPayload();
        return $this->exportData('pdf.admin_instansi.beban_pembimbing', $data, 'Laporan-Beban-Pembimbing', 'a4', 'landscape', $request->query('format', 'pdf'));
    }

    public function laporanDemografiKampus()
    {
        $data = $this->getDemografiKampusPayload();
        return view('admin_instansi.laporan.demografi_kampus', $data);
    }

    public function printDemografiKampus(Request $request)
    {
        $data = $this->getDemografiKampusPayload();
        return $this->exportData('pdf.admin_instansi.demografi_kampus', $data, 'Laporan-Demografi-Kampus', 'a4', 'landscape', $request->query('format', 'pdf'));
    }

    public function laporanJurnalHarian(Request $request)
    {
        $data = $this->getJurnalHarianPayload($request);
        return view('admin_instansi.laporan.jurnal_harian', $data);
    }

    public function printJurnalHarian(Request $request)
    {
        $data = $this->getJurnalHarianPayload($request);
        return $this->exportData('pdf.admin_instansi.jurnal_harian', $data, 'Laporan-Jurnal-Harian', 'a4', 'landscape', $request->query('format', 'pdf'));
    }

    protected function getBebanPembimbingPayload(): array
    {
        $instansiId = Auth::user()->instansi_id;
        $beban = User::where('instansi_id', $instansiId)->where('role', 'pembimbing_lapangan')
            ->with(['bimbingan' => function($q) {
                $q->whereIn('status', ['diterima', 'selesai'])
                  ->with(['user', 'position', 'logs', 'attendances']);
            }])
            ->get()->map(function($pl) {
                $pl->total_bimbingan_aktif = $pl->bimbingan->where('status', 'diterima')->count();
                $pl->total_lulus = $pl->bimbingan->where('status', 'selesai')->count();
                
                $pending_logs = 0;
                $total_nilai = 0;
                $count_nilai = 0;

                $pl->mahasiswa_aktif = $pl->bimbingan->where('status', 'diterima')->map(function($app) use (&$pending_logs) {
                    $total_l = $app->logs->count();
                    $approved_l = $app->logs->where('status_validasi', 'disetujui')->count();
                    $pending_l = $app->logs->where('status_validasi', 'pending')->count();
                    $revisi_l = $app->logs->where('status_validasi', 'revisi')->count();
                    $pending_logs += $pending_l;

                    $total_att = $app->attendances->count();
                    $hadir_att = $app->attendances->where('status', 'hadir')->count();
                    $pending_att = $app->attendances->where('validation_status', 'pending')->count();
                    
                    return [
                        'nama' => $app->user->name ?? '-',
                        'kampus' => $app->user->asal_instansi ?? '-',
                        'jurusan' => $app->user->major ?? '-',
                        'posisi' => $app->position->judul_posisi ?? '-',
                        'mulai' => $app->tanggal_mulai,
                        'selesai' => $app->tanggal_selesai,
                        'logbook' => [
                            'total' => $total_l,
                            'disetujui' => $approved_l,
                            'pending' => $pending_l,
                            'revisi' => $revisi_l,
                            'rate' => $total_l > 0 ? round(($approved_l / $total_l) * 100) : 0,
                        ],
                        'absensi' => [
                            'total' => $total_att,
                            'hadir' => $hadir_att,
                            'pending' => $pending_att,
                        ]
                    ];
                })->values();

                $pl->mahasiswa_lulus = $pl->bimbingan->where('status', 'selesai')->map(function($app) use (&$total_nilai, &$count_nilai) {
                    $grade = 0;
                    if ($app->nilai_rata_rata) {
                        $grade = (float) $app->nilai_rata_rata;
                    } elseif ($app->nilai_teknis) {
                        $grade = ((float) $app->nilai_teknis + (float) $app->nilai_disiplin + (float) $app->nilai_perilaku) / 3;
                    }

                    if ($grade > 0) {
                        $total_nilai += $grade;
                        $count_nilai++;
                    }

                    return [
                        'nama' => $app->user->name ?? '-',
                        'kampus' => $app->user->asal_instansi ?? '-',
                        'jurusan' => $app->user->major ?? '-',
                        'posisi' => $app->position->judul_posisi ?? '-',
                        'nilai' => $grade > 0 ? round($grade, 2) : '-',
                        'predikat' => $app->predikat ?? '-',
                        'catatan' => $app->catatan_pembimbing_lapangan ?? '-',
                        'nomor_sertifikat' => $app->nomor_sertifikat ?? '-',
                    ];
                })->values();

                $pl->logbook_tertunda = $pending_logs;
                $pl->rata_nilai_diberikan = $count_nilai > 0 ? $total_nilai / $count_nilai : 0;

                return $pl;
            });

        $stats = [
            'total_pembimbing' => $beban->count(),
            'total_bimbingan_aktif' => $beban->sum('total_bimbingan_aktif'),
            'total_lulus' => $beban->sum('total_lulus'),
            'total_logbook_tertunda' => $beban->sum('logbook_tertunda'),
            'rata_nilai_semua' => $beban->where('rata_nilai_diberikan', '>', 0)->count() > 0 
                ? $beban->where('rata_nilai_diberikan', '>', 0)->avg('rata_nilai_diberikan') 
                : 0,
            'pembimbing_teraktif' => $beban->sortByDesc('total_bimbingan_aktif')->first()?->name ?? '-',
            'pembimbing_teraktif_jumlah' => $beban->sortByDesc('total_bimbingan_aktif')->first()?->total_bimbingan_aktif ?? 0,
            'tertib_validasi' => $beban->where('logbook_tertunda', 0)->where('total_bimbingan_aktif', '>', 0)->count()
        ];

        return compact('beban', 'stats');
    }

    protected function getDemografiKampusPayload(): array
    {
        $instansiId = Auth::user()->instansi_id;
        
        $applications = Application::whereHas('position', function($q) use ($instansiId) {
            $q->where('instansi_id', $instansiId);
        })
        ->with(['user', 'position'])
        ->get();

        $demografi = $applications
        ->groupBy(function($app) {
            return empty($app->user->asal_instansi) ? 'Lainnya / Tidak Diketahui' : $app->user->asal_instansi;
        })
        ->map(function($group) {
            $diterima = $group->whereIn('status', ['diterima', 'selesai']);
            $selesai = $group->where('status', 'selesai');
            return [
                'total_pelamar' => $group->count(),
                'diterima' => $diterima->count(),
                'selesai' => $selesai->count(),
                'ditolak' => $group->where('status', 'ditolak')->count(),
                'pending' => $group->where('status', 'pending')->count(),
                'dibatalkan' => $group->whereIn('status', ['dibatalkan', 'dikeluarkan'])->count(),
                'acceptance_rate' => $group->count() > 0 ? round(($diterima->count() / $group->count()) * 100) : 0,
                'jurusan' => $group->groupBy(function($app) {
                    return $app->user->major ?? 'Tidak Diketahui';
                })->map->count()->sortByDesc(fn($v) => $v),
                'posisi' => $group->groupBy(function($app) {
                    return $app->position->judul_posisi ?? 'Tidak Diketahui';
                })->map->count()->sortByDesc(fn($v) => $v),
                'peserta_aktif' => $diterima->map(function($app) {
                    return [
                        'nama' => $app->user->name ?? '-',
                        'jurusan' => $app->user->major ?? '-',
                        'posisi' => $app->position->judul_posisi ?? '-',
                        'status' => $app->status,
                        'mulai' => $app->tanggal_mulai,
                        'selesai' => $app->tanggal_selesai,
                    ];
                })->values(),
                'peserta' => $diterima->map(function($app) {
                    return [
                        'nama' => $app->user->name ?? '-',
                        'jurusan' => $app->user->major ?? '-',
                        'posisi' => $app->position->judul_posisi ?? '-',
                        'status' => $app->status,
                    ];
                })->values(),
            ];
        })
        ->sortByDesc('total_pelamar');

        $demografiJurusan = $applications
        ->groupBy(function($app) {
            return empty($app->user->major) ? 'Tidak Diketahui' : $app->user->major;
        })
        ->map(function($group) {
            $diterima = $group->whereIn('status', ['diterima', 'selesai']);
            return [
                'total' => $group->count(),
                'diterima' => $diterima->count(),
                'acceptance_rate' => $group->count() > 0 ? round(($diterima->count() / $group->count()) * 100) : 0,
            ];
        })
        ->sortByDesc('total');

        $stats = [
            'total_kampus' => $demografi->count(),
            'total_jurusan' => $demografiJurusan->count(),
            'total_pelamar' => $applications->count(),
            'total_diterima' => $applications->whereIn('status', ['diterima', 'selesai'])->count(),
            'total_selesai' => $applications->where('status', 'selesai')->count(),
            'total_ditolak' => $applications->where('status', 'ditolak')->count(),
            'total_pending' => $applications->where('status', 'pending')->count(),
            'kampus_terbanyak' => $demografi->count() > 0 ? $demografi->keys()->first() : '-',
            'kampus_terbanyak_jumlah' => $demografi->count() > 0 ? $demografi->first()['total_pelamar'] : 0,
        ];

        return compact('demografi', 'demografiJurusan', 'stats');
    }

    protected function getJurnalHarianPayload(Request $request): array
    {
        $instansiId = Auth::user()->instansi_id;
        $filter = $request->query('filter', 'semua');
        
        $query = DailyLog::whereHas('application.position', function($q) use ($instansiId) {
            $q->where('instansi_id', $instansiId);
        })
        ->with(['application.user', 'application.position', 'application.pembimbing_lapangan']);

        $label_waktu = 'Semua Waktu';
        if ($filter == 'hari') {
            $query->whereDate('tanggal', Carbon::today());
            $label_waktu = 'Hari Ini (' . Carbon::today()->format('d M Y') . ')';
        } elseif ($filter == 'minggu') {
            $query->whereBetween('tanggal', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            $label_waktu = 'Minggu Ini (' . Carbon::now()->startOfWeek()->format('d M') . ' - ' . Carbon::now()->endOfWeek()->format('d M Y') . ')';
        } elseif ($filter == 'bulan') {
            $query->whereMonth('tanggal', Carbon::now()->month)
                  ->whereYear('tanggal', Carbon::now()->year);
            $label_waktu = 'Bulan Ini (' . Carbon::now()->format('F Y') . ')';
        }

        $jurnal = $query->orderBy('tanggal', 'desc')->get();

        $total_peserta_aktif = Application::whereHas('position', function($q) use ($instansiId) {
            $q->where('instansi_id', $instansiId);
        })->where('status', 'diterima')->count();

        $stats = [
            'total_jurnal' => $jurnal->count(),
            'disetujui' => $jurnal->where('status_validasi', 'disetujui')->count(),
            'pending' => $jurnal->where('status_validasi', 'pending')->count(),
            'revisi' => $jurnal->where('status_validasi', 'revisi')->count(),
            'total_peserta_aktif' => $total_peserta_aktif,
            'rasio_validasi' => $jurnal->count() > 0 
                ? round(($jurnal->where('status_validasi', 'disetujui')->count() / $jurnal->count()) * 100) 
                : 0
        ];

        return compact('jurnal', 'filter', 'label_waktu', 'stats');
    }
}
