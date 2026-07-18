<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportService
{
    public function getInstansiRekapData(Request $request, int $instansiId, bool $paginate = true): array
    {
        $query = Application::with(['user', 'position', 'pembimbing_lapangan'])
            ->whereHas('position', function ($q) use ($instansiId) {
                $q->where('instansi_id', $instansiId);
            });

        $this->applyInstansiRekapFilters($query, $request);

        $statsQuery = clone $query;
        $stats = [
            'total' => (clone $statsQuery)->count(),
            'aktif' => (clone $statsQuery)->where('status', 'diterima')->count(),
            'selesai' => (clone $statsQuery)->where('status', 'selesai')->count(),
            'pending' => (clone $statsQuery)->whereIn('status', ['pending', 'menunggu'])->count(),
            'ditolak' => (clone $statsQuery)->where('status', 'ditolak')->count(),
            'total_kampus' => (clone $statsQuery)
                ->join('users', 'applications.user_id', '=', 'users.id')
                ->whereNotNull('users.asal_instansi')
                ->distinct('users.asal_instansi')
                ->count('users.asal_instansi'),
        ];

        $this->applyInstansiRekapSort($query, $request);

        $applications = $paginate
            ? $query->paginate(20)->withQueryString()
            : $query->get();

        return compact('applications', 'stats');
    }

    protected function applyInstansiRekapFilters($query, Request $request): void
    {
        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->whereIn('status', ['pending', 'menunggu']);
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('asal_instansi')) {
            $searchInstansi = $request->asal_instansi;
            $query->whereHas('user', function ($q) use ($searchInstansi) {
                $q->where('asal_instansi', 'like', '%' . $searchInstansi . '%');
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->where(function ($q) use ($request) {
                $q->whereBetween('tanggal_mulai', [$request->start_date, $request->end_date])
                    ->orWhereBetween('tanggal_selesai', [$request->start_date, $request->end_date]);
            });
        }
    }

    protected function applyInstansiRekapSort($query, Request $request): void
    {
        if ($request->sort === 'name_asc') {
            $query->join('users', 'applications.user_id', '=', 'users.id')
                ->orderBy('users.name', 'asc')
                ->select('applications.*');

            return;
        }

        if ($request->sort === 'name_desc') {
            $query->join('users', 'applications.user_id', '=', 'users.id')
                ->orderBy('users.name', 'desc')
                ->select('applications.*');

            return;
        }

        $query->latest();
    }

    /**
     * Get Durasi Magang Report Data (Admin Kota)
     */
    public function getDurasiMagangData()
    {
        return Instansi::with(['applications' => function($q) {
            $q->whereIn('applications.status', ['diterima', 'selesai'])->whereNotNull('tanggal_mulai')->whereNotNull('tanggal_selesai');
        }])->get()->map(function($instansi) {
            $totalHari = 0;
            $count = 0;

            foreach($instansi->applications as $app) {
                $mulai = Carbon::parse($app->tanggal_mulai);
                $selesai = Carbon::parse($app->tanggal_selesai);
                $totalHari += $mulai->diffInDays($selesai);
                $count++;
            }

            $instansi->avg_durasi_hari = $count > 0 ? round($totalHari / $count) : 0;
            $instansi->avg_durasi_bulan = $count > 0 ? round(($totalHari / $count) / 30, 1) : 0;
            
            return $instansi;
        })->sortByDesc('avg_durasi_hari');
    }

    /**
     * Get Demografi Jurusan Report Data (Admin Kota)
     */
    public function getDemografiJurusanData()
    {
        return InternshipPosition::select('required_major', DB::raw('count(*) as total_lowongan'), DB::raw('sum(kuota) as total_kuota'))
            ->groupBy('required_major')
            ->orderBy('total_kuota', 'desc')
            ->get();
    }

    /**
     * Get Penyerapan Kuota Report Data (Admin Kota)
     */
    public function getPenyerapanKuotaData()
    {
        return Instansi::with(['positions' => function($q) {
            $q->withCount(['applications as diterima' => function($query) {
                $query->whereIn('applications.status', ['diterima', 'selesai']);
            }]);
        }])->get()->map(function($instansi) {
            $totalKuota = $instansi->positions->sum('kuota');
            $totalTerserap = $instansi->positions->sum('diterima');
            
            $instansi->total_kuota = $totalKuota;
            $instansi->total_terserap = $totalTerserap;
            $instansi->persentase_penyerapan = $totalKuota > 0 ? ($totalTerserap / $totalKuota) * 100 : 0;
            
            return $instansi;
        })->sortByDesc('persentase_penyerapan');
    }

    /**
     * Get Grading Report Data (Admin Kota)
     */
    public function getGradingReportData(Request $request)
    {
        $query = Application::with(['user', 'position.instansi'])
                    ->where(function($q) {
                        $q->whereNotNull('nilai_rata_rata')
                          ->orWhereNotNull('nilai_teknis');
                    })
                    ->get();

        $gradedData = $query->map(function($app) {
            if ($app->nilai_rata_rata !== null) {
                $avg = (float) $app->nilai_rata_rata;
                $kerajinan = (float) ($app->nilai_kerajinan ?? 0);
                $disiplin = (float) ($app->nilai_disiplin ?? 0);
                $adaptasi = (float) ($app->nilai_adaptasi ?? 0);
                $kreatifitas = (float) ($app->nilai_kreatifitas ?? 0);
                $skill = (float) ($app->nilai_skill_pengetahuan ?? 0);
                
                $teknis = $skill; 
                $perilaku = ($adaptasi + $kreatifitas + $kerajinan) / 3;
            } else {
                $teknis = (float) ($app->nilai_teknis ?? 0);
                $disiplin = (float) ($app->nilai_disiplin ?? 0);
                $perilaku = (float) ($app->nilai_perilaku ?? 0);
                $avg = ($teknis + $disiplin + $perilaku) / 3;
                
                $kerajinan = $disiplin;
                $adaptasi = $perilaku;
                $kreatifitas = $perilaku;
                $skill = $teknis;
            }
            
            if ($avg >= 86) $predikat = 'Sangat Baik';
            elseif ($avg >= 71) $predikat = 'Baik';
            elseif ($avg >= 56) $predikat = 'Cukup';
            else $predikat = 'Kurang';

            $app->computed_avg = $avg;
            $app->computed_teknis = $teknis;
            $app->computed_disiplin = $disiplin;
            $app->computed_perilaku = $perilaku;
            $app->computed_predikat = $predikat;
            $app->computed_kerajinan = $kerajinan;
            $app->computed_adaptasi = $adaptasi;
            $app->computed_kreatifitas = $kreatifitas;
            $app->computed_skill = $skill;

            return $app;
        });

        if ($request->has('instansi_id') && $request->instansi_id != '') {
            $gradedData = $gradedData->filter(function($app) use ($request) {
                return $app->position->instansi_id == $request->instansi_id;
            });
        }
        if ($request->has('asal_instansi') && $request->asal_instansi != '') {
            $gradedData = $gradedData->filter(function($app) use ($request) {
                return str_contains(strtolower($app->user->asal_instansi ?? ''), strtolower($request->asal_instansi));
            });
        }
        if ($request->has('predikat') && $request->predikat != '') {
            $gradedData = $gradedData->filter(function($app) use ($request) {
                return strtolower($app->computed_predikat) == strtolower($request->predikat);
            });
        }

        $sortedList = $gradedData->sortByDesc('computed_avg')->values();

        $stats = [
            'total' => $sortedList->count(),
            'total_dinilai' => $sortedList->count(),
            'avg_nilai' => $sortedList->count() > 0 ? round($sortedList->avg('computed_avg'), 2) : 0,
            'rata_rata_kota' => $sortedList->count() > 0 ? round($sortedList->avg('computed_avg'), 2) : 0,
            'sangat_baik' => $sortedList->where('computed_predikat', 'Sangat Baik')->count(),
            'baik' => $sortedList->where('computed_predikat', 'Baik')->count(),
            'cukup' => $sortedList->where('computed_predikat', 'Cukup')->count(),
            'kurang' => $sortedList->where('computed_predikat', 'Kurang')->count(),
        ];

        $totalApps = Application::whereIn('status', ['diterima', 'selesai'])->count();
        $statsGlobal = [
            'total_magang_selesai' => $totalApps,
            'persentase_dinilai' => $totalApps > 0 ? round(($sortedList->count() / $totalApps) * 100, 1) : 0,
            'avg_teknis' => $sortedList->count() > 0 ? round($sortedList->avg('computed_teknis'), 2) : 0,
            'avg_disiplin' => $sortedList->count() > 0 ? round($sortedList->avg('computed_disiplin'), 2) : 0,
            'avg_perilaku' => $sortedList->count() > 0 ? round($sortedList->avg('computed_perilaku'), 2) : 0,
        ];

        $gradedList = $sortedList->map(function ($app) {
            return [
                'id' => $app->id,
                'nama' => $app->user->name ?? '-',
                'asal_instansi' => $app->user->asal_instansi ?? '-',
                'instansi' => $app->position->instansi->nama_dinas ?? '-',
                'posisi' => $app->position->judul_posisi ?? $app->position->posisi ?? '-',
                'teknis' => round($app->computed_teknis, 2),
                'disiplin' => round($app->computed_disiplin, 2),
                'perilaku' => round($app->computed_perilaku, 2),
                'rata_rata' => round($app->computed_avg, 2),
                'predikat' => $app->computed_predikat,
                'nilai_rata_rata' => $app->nilai_rata_rata,
                'kerajinan' => round($app->computed_kerajinan, 2),
                'adaptasi' => round($app->computed_adaptasi, 2),
                'kreatifitas' => round($app->computed_kreatifitas, 2),
                'skill' => round($app->computed_skill, 2),
            ];
        })->values();

        $podium = $gradedList->take(3);

        return compact('podium', 'gradedList', 'stats', 'statsGlobal');
    }

    /**
     * Get Instansi Paling Disiplin Report Data (Admin Kota)
     */
    public function getInstansiDisiplinData(Request $request)
    {
        $query = Instansi::with(['applications' => function($q) {
            $q->whereIn('applications.status', ['diterima', 'selesai'])->with('attendances');
        }]);

        if ($request->filled('q')) {
            $query->where('nama_dinas', 'like', '%' . $request->q . '%');
        }

        $instansis = $query->get()->map(function($instansi) {
            $totalAttendances = 0;
            $totalTerlambat = 0;
            $totalAlpa = 0;
            $totalHadir = 0;
            $totalSakit = 0;
            $totalIzin = 0;

            $pelanggarList = [];

            foreach($instansi->applications as $app) {
                $pTerlambat = 0;
                $pAlpa = 0;

                foreach($app->attendances as $att) {
                    $totalAttendances++;
                    if ($att->status == 'alpa') {
                        $totalAlpa++;
                        $pAlpa++;
                    }
                    if ($att->status == 'hadir') {
                        $totalHadir++;
                        $jamMasuk = $instansi->jam_mulai_masuk ?: '08:00:00';
                        if ($att->clock_in > $jamMasuk) {
                            $totalTerlambat++;
                            $pTerlambat++;
                        }
                    }
                    if ($att->status == 'sakit') $totalSakit++;
                    if ($att->status == 'izin') $totalIzin++;
                }

                if ($pTerlambat > 0 || $pAlpa > 0) {
                    $pelanggarList[] = [
                        'nama' => $app->user->name ?? '-',
                        'kampus' => $app->user->asal_instansi ?? '-',
                        'posisi' => $app->position->judul_posisi ?? '-',
                        'terlambat' => $pTerlambat,
                        'alpa' => $pAlpa,
                    ];
                }
            }

            $instansi->total_attendances = $totalAttendances;
            $instansi->total_hadir = $totalHadir;
            $instansi->total_sakit = $totalSakit;
            $instansi->total_izin = $totalIzin;
            $instansi->total_alpa = $totalAlpa;
            $instansi->total_terlambat = $totalTerlambat;
            $instansi->total_pelanggaran = $totalTerlambat + $totalAlpa;
            $instansi->tingkat_disiplin = $totalAttendances > 0 ? 100 - (($instansi->total_pelanggaran / $totalAttendances) * 100) : 100;
            $instansi->pelanggar_list = collect($pelanggarList)->sortByDesc(function($p) {
                return $p['terlambat'] + $p['alpa'];
            })->values()->all();

            return $instansi;
        });

        if ($request->filled('disiplin_range')) {
            $range = $request->disiplin_range;
            $instansis = $instansis->filter(function($instansi) use ($range) {
                if ($range === 'sangat') {
                    return $instansi->tingkat_disiplin >= 90;
                } elseif ($range === 'cukup') {
                    return $instansi->tingkat_disiplin >= 70 && $instansi->tingkat_disiplin < 90;
                } elseif ($range === 'kurang') {
                    return $instansi->tingkat_disiplin < 70;
                }
                return true;
            });
        }

        $instansis = $instansis->sortByDesc('tingkat_disiplin')->values();

        $podium = $instansis->filter(function($i) { return $i->total_attendances > 0; })
                            ->sortByDesc('tingkat_disiplin')
                            ->values()
                            ->take(3);

        $stats = [
            'total_instansi' => $instansis->count(),
            'avg_disiplin' => $instansis->count() > 0 ? round($instansis->avg('tingkat_disiplin'), 1) : 100,
            'total_kehadiran' => $instansis->sum('total_attendances'),
            'total_pelanggaran' => $instansis->sum('total_pelanggaran'),
            'total_terlambat' => $instansis->sum('total_terlambat'),
            'total_alpa' => $instansis->sum('total_alpa'),
        ];

        return compact('podium', 'instansis', 'stats');
    }

    /**
     * Get Global Interns Report Data (Admin Kota)
     */
    public function getGlobalInternsData(Request $request)
    {
        $query = Application::with(['user', 'position.instansi']);

        if ($request->has('status') && $request->status !== '') {
            if ($request->status === 'pending') {
                $query->whereIn('status', ['pending', 'menunggu']);
            } elseif ($request->status !== 'semua') {
                $query->where('status', $request->status);
            }
        } else {
            $query->whereIn('status', ['diterima', 'selesai']);
        }

        if ($request->has('instansi_id') && $request->instansi_id != '') {
            $query->whereHas('position.instansi', function($q) use ($request) {
                $q->where('id', $request->instansi_id);
            });
        }

        if ($request->has('instansi') && $request->instansi != '') {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('asal_instansi', $request->instansi);
            });
        }

        if ($request->has('posisi') && $request->posisi != '') {
            $query->whereHas('position', function($q) use ($request) {
                $q->where('judul_posisi', 'like', '%' . $request->posisi . '%');
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = $request->start_date;
            $end = $request->end_date;
            $query->where(function($q) use ($start, $end) {
                $q->whereBetween('tanggal_mulai', [$start, $end])
                  ->orWhereBetween('tanggal_selesai', [$start, $end]);
            });
        } elseif ($request->filled('start_date')) {
            $query->where('tanggal_mulai', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->where('tanggal_selesai', '<=', $request->end_date);
        }

        $allInterns = $query->get()->sortBy(function($app) {
            return $app->position->instansi->nama_dinas ?? '';
        })->values();

        $stats = [
            'total' => $allInterns->count(),
            'aktif' => $allInterns->where('status', 'diterima')->count(),
            'selesai' => $allInterns->where('status', 'selesai')->count(),
            'pending' => $allInterns->whereIn('status', ['pending', 'menunggu'])->count(),
            'total_dinas' => $allInterns->pluck('position.instansi.id')->unique()->filter()->count(),
            'total_kampus' => $allInterns->pluck('user.asal_instansi')->unique()->filter()->count()
        ];

        return compact('allInterns', 'stats');
    }

    /**
     * Get Kinerja Mahasiswa Data (Admin Instansi / Dinas)
     */
    public function getKinerjaMahasiswaData(int $instansiId)
    {
        $kinerja = Application::whereHas('position', function($q) use ($instansiId) {
            $q->where('instansi_id', $instansiId);
        })->whereIn('status', ['diterima', 'selesai'])
        ->with(['user', 'position', 'logs', 'attendances', 'pembimbing_lapangan'])
        ->get()->map(function($app) {
            $total_logs = $app->logs->count();
            $approved_logs = $app->logs->where('status_validasi', 'disetujui')->count();
            $log_rate = $total_logs > 0 ? ($approved_logs / $total_logs) * 100 : 0;

            $total_attendance = $app->attendances->count();
            $hadir = $app->attendances->where('status', 'hadir')->count();
            $attendance_rate = $total_attendance > 0 ? ($hadir / $total_attendance) * 100 : 0;

            $avg_nilai = 0;
            if ($app->nilai_rata_rata) {
                $avg_nilai = (float) $app->nilai_rata_rata;
            } else {
                $t = (float) $app->nilai_teknis;
                $d = (float) $app->nilai_disiplin;
                $p = (float) $app->nilai_perilaku;
                if ($t > 0 || $d > 0 || $p > 0) {
                    $avg_nilai = ($t + $d + $p) / 3;
                }
            }

            $app->log_rate = $log_rate;
            $app->attendance_rate = $attendance_rate;
            $app->avg_nilai = $avg_nilai;

            return $app;
        })->sortByDesc('avg_nilai');

        $stats = [
            'total_peserta' => $kinerja->count(),
            'aktif' => $kinerja->where('status', 'diterima')->count(),
            'selesai' => $kinerja->where('status', 'selesai')->count(),
            'avg_kehadiran' => $kinerja->count() > 0 ? round($kinerja->avg('attendance_rate'), 1) : 0,
            'avg_logbook' => $kinerja->count() > 0 ? round($kinerja->avg('log_rate'), 1) : 0,
            'avg_nilai' => $kinerja->where('status', 'selesai')->where('avg_nilai', '>', 0)->count() > 0
                ? round($kinerja->where('status', 'selesai')->where('avg_nilai', '>', 0)->avg('avg_nilai'), 1)
                : 0
        ];

        return compact('kinerja', 'stats');
    }
}
