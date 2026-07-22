<?php

namespace App\Http\Controllers\AdminKota;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Instansi;
use App\Models\User;
use App\Services\PdfExportService;
use App\Services\ReportService;
use Illuminate\Http\Request;
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

    /**
     * Pusat Laporan Hub (Superadmin)
     */
    public function laporanHub()
    {
        return view('admin_kota.laporan_hub');
    }

    /**
     * Laporan Statistik Rekapitulasi Umum Instansi
     */
    public function report(Request $request)
    {
        $data = $this->getGeneralReportData($request);
        return view('admin_kota.laporan', $data);
    }

    /**
     * Cetak Laporan Statistik Rekapitulasi Umum Instansi (PDF/Excel/CSV)
     */
    public function printLaporan(Request $request)
    {
        $data = $this->getGeneralReportData($request);
        $data['request'] = $request;
        return $this->exportData('pdf.admin_kota.laporan', $data, 'Laporan-Statistik-Magang', 'a4', 'portrait', $request->query('format', 'pdf'));
    }

    /**
     * Laporan Master Instansi
     */
    public function laporanInstansi()
    {
        $instansis = Instansi::orderBy('nama_dinas', 'asc')->get();
        return view('admin_kota.laporan_instansi', compact('instansis'));
    }

    /**
     * Cetak Laporan Master Instansi (PDF/Excel/CSV)
     */
    public function printInstansi(Request $request)
    {
        $instansis = Instansi::with(['positions.applications'])->orderBy('nama_dinas', 'asc')->get();
        return $this->exportData('pdf.admin_kota.instansi', compact('instansis'), 'Laporan-Master-INSTANSI', 'a4', 'portrait', $request->query('format', 'pdf'));
    }

    /**
     * Laporan Peserta Global (Seluruh Kota)
     */
    public function laporanPesertaGlobal(Request $request)
    {
        $data = $this->reportService->getGlobalInternsData($request);
        $data['listDinas'] = Instansi::orderBy('nama_dinas', 'asc')->get();
        $data['listInstansi'] = User::where('role', 'peserta')->whereNotNull('asal_instansi')->distinct()->orderBy('asal_instansi', 'asc')->pluck('asal_instansi');
        return view('admin_kota.laporan.peserta_global', $data);
    }

    /**
     * Cetak Laporan Peserta Global (PDF/Excel/CSV)
     */
    public function printPesertaGlobal(Request $request)
    {
        $data = $this->reportService->getGlobalInternsData($request, false);
        $title = 'Laporan Global Peserta Magang';
        if ($request->has('status') && $request->status !== '' && $request->status !== 'semua') {
            $title .= ' (' . ucfirst($request->status) . ')';
        }
        $data['title'] = $title;
        $data['request'] = $request;
        return $this->exportData('pdf.admin_kota.laporan_global', $data, 'Laporan-Global-Peserta', 'a4', 'portrait', $request->query('format', 'pdf'));
    }

    /**
     * Laporan Grading & Evaluasi
     */
    public function laporanGrading(Request $request)
    {
        $data = $this->reportService->getGradingReportData($request);
        $data['listDinas'] = Instansi::orderBy('nama_dinas', 'asc')->get();
        $data['listCampus'] = User::where('role', 'peserta')->whereNotNull('asal_instansi')->distinct()->orderBy('asal_instansi', 'asc')->pluck('asal_instansi');
        $data['request'] = $request;
        return view('admin_kota.laporan.grading', $data);
    }

    /**
     * Cetak Laporan Grading & Evaluasi (PDF/Excel/CSV)
     */
    public function printGrading(Request $request)
    {
        $data = $this->reportService->getGradingReportData($request);
        $title = 'Laporan Evaluasi & Penilaian Peserta Magang';
        if ($request->filled('predikat')) {
            $title .= ' - Predikat: ' . ucfirst($request->predikat);
        }
        $data['title'] = $title;
        $data['request'] = $request;
        return $this->exportData('pdf.admin_kota.grading', $data, 'Laporan-Grading-Peserta', 'a4', 'portrait', $request->query('format', 'pdf'));
    }

    /**
     * Laporan Instansi Paling Disiplin
     */
    public function laporanInstansiDisiplin(Request $request)
    {
        $data = $this->reportService->getInstansiDisiplinData($request);
        $data['request'] = $request;
        return view('admin_kota.laporan.instansi_disiplin', $data);
    }

    /**
     * Cetak Laporan Instansi Paling Disiplin (PDF/Excel/CSV)
     */
    public function printInstansiDisiplin(Request $request)
    {
        $data = $this->reportService->getInstansiDisiplinData($request);
        $title = 'Laporan Kedisiplinan Kehadiran Peserta per Instansi';
        if ($request->filled('disiplin_range')) {
            $title .= ' - Kategori ' . ucfirst($request->disiplin_range);
        }
        $data['title'] = $title;
        $data['request'] = $request;
        return $this->exportData('pdf.admin_kota.instansi_disiplin', $data, 'Laporan-Kedisiplinan-Instansi', 'a4', 'portrait', $request->query('format', 'pdf'));
    }

    /**
     * Laporan Durasi Magang
     */
    public function laporanDurasiMagang(Request $request)
    {
        $instansis = $this->reportService->getDurasiMagangData($request);
        return view('admin_kota.laporan.durasi_magang', compact('instansis'));
    }

    /**
     * Cetak Laporan Durasi Magang (PDF/Excel/CSV)
     */
    public function printDurasiMagang(Request $request)
    {
        $data = $this->reportService->getDurasiMagangData($request);
        return $this->exportData('pdf.admin_kota.durasi_magang', ['instansis' => $data, 'request' => $request], 'Laporan-Durasi-Magang', 'a4', 'portrait', $request->query('format', 'pdf'));
    }

    /**
     * Laporan Demografi Jurusan
     */
    public function laporanDemografiJurusan(Request $request)
    {
        $jurusans = $this->reportService->getDemografiJurusanData($request);
        return view('admin_kota.laporan.demografi_jurusan', compact('jurusans'));
    }

    /**
     * Cetak Laporan Demografi Jurusan (PDF/Excel/CSV)
     */
    public function printDemografiJurusan(Request $request)
    {
        $data = $this->reportService->getDemografiJurusanData($request);
        return $this->exportData('pdf.admin_kota.demografi_jurusan', [
            'jurusans' => $data,
            'request' => $request
        ], 'Laporan-Demografi-Jurusan', 'a4', 'portrait', $request->query('format', 'pdf'));
    }

    /**
     * Laporan Penyerapan Kuota
     */
    public function laporanPenyerapanKuota(Request $request)
    {
        $penyerapan = $this->reportService->getPenyerapanKuotaData($request);
        return view('admin_kota.laporan.penyerapan_kuota', compact('penyerapan'));
    }

    /**
     * Cetak Laporan Penyerapan Kuota (PDF/Excel/CSV)
     */
    public function printPenyerapanKuota(Request $request)
    {
        $data = $this->reportService->getPenyerapanKuotaData($request);
        return $this->exportData('pdf.admin_kota.penyerapan_kuota', [
            'penyerapan' => $data,
            'request' => $request
        ], 'Laporan-Penyerapan-Kuota', 'a4', 'portrait', $request->query('format', 'pdf'));
    }

    /**
     * Helper privat untuk logika agregasi statistik umum
     */
    protected function getGeneralReportData(Request $request): array
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'pelamar_desc');

        $query = Instansi::with(['positions.applications']);
        
        if ($search) {
            $query->where('nama_dinas', 'like', '%' . $search . '%');
        }

        $instansis = $query->get();

        $allInstansis = Instansi::with(['positions.applications'])->get();
        $totalInstansi = $allInstansis->count();
        $totalLowongan = $allInstansis->flatMap->positions->where('status', 'buka')->count();
        
        $allApps = $allInstansis->flatMap->positions->flatMap->applications;
        $totalPelamar = $allApps->count();
        $totalDiterima = $allApps->whereIn('status', ['diterima', 'selesai'])->count();
        
        $avgSeleksiRate = $totalPelamar > 0 ? round(($totalDiterima / $totalPelamar) * 100, 1) : 0;
        
        $favDinas = $allInstansis->map(function($d) {
            return [
                'nama' => $d->nama_dinas,
                'count' => $d->positions->flatMap->applications->count()
            ];
        })->sortByDesc('count')->first();
        $favDinasName = $favDinas && $favDinas['count'] > 0 ? $favDinas['nama'] : '-';

        $stats = [
            'total_instansi' => $totalInstansi,
            'total_lowongan' => $totalLowongan,
            'total_pelamar' => $totalPelamar,
            'total_diterima' => $totalDiterima,
            'avg_seleksi_rate' => $avgSeleksiRate,
            'fav_dinas' => $favDinasName
        ];

        $laporan = $instansis->map(function($dinas) {
            $positions = $dinas->positions;
            $applications = $positions->flatMap->applications;
            
            $totalPelamar = $applications->count();
            $totalDiterima = $applications->whereIn('status', ['diterima', 'selesai'])->count();
            $totalPosisi = $positions->count();
            
            $seleksiRate = $totalPelamar > 0 ? round(($totalDiterima / $totalPelamar) * 100, 1) : 0;
            $avgPelamar = $totalPosisi > 0 ? round($totalPelamar / $totalPosisi, 1) : 0;

            return [
                'nama_dinas' => $dinas->nama_dinas,
                'lowongan_aktif' => $positions->where('status', 'buka')->count(),
                'total_pelamar' => $totalPelamar,
                'total_magang' => $totalDiterima,
                'seleksi_rate' => $seleksiRate,
                'avg_peminat' => $avgPelamar,
            ];
        });

        if ($sort === 'name_asc') {
            $laporan = $laporan->sortBy('nama_dinas');
        } elseif ($sort === 'name_desc') {
            $laporan = $laporan->sortByDesc('nama_dinas');
        } elseif ($sort === 'pelamar_asc') {
            $laporan = $laporan->sortBy('total_pelamar');
        } elseif ($sort === 'pelamar_desc') {
            $laporan = $laporan->sortByDesc('total_pelamar');
        } elseif ($sort === 'lowongan_desc') {
            $laporan = $laporan->sortByDesc('lowongan_aktif');
        } elseif ($sort === 'lowongan_asc') {
            $laporan = $laporan->sortBy('lowongan_aktif');
        } elseif ($sort === 'seleksi_desc') {
            $laporan = $laporan->sortByDesc('seleksi_rate');
        } elseif ($sort === 'seleksi_asc') {
            $laporan = $laporan->sortBy('seleksi_rate');
        } else {
            $laporan = $laporan->sortByDesc('total_pelamar');
        }

        $laporan = $laporan->values();

        return compact('laporan', 'stats');
    }
}
