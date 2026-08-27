<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                $q->where('asal_instansi', 'like', '%'.$searchInstansi.'%');
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
    public function getDurasiMagangData(?Request $request = null)
    {
        $query = Instansi::with(['applications' => function ($q) {
            $q->whereIn('applications.status', ['diterima', 'selesai'])->whereNotNull('tanggal_mulai')->whereNotNull('tanggal_selesai');
        }]);

        if ($request && ($request->filled('q') || $request->filled('search'))) {
            $search = $request->input('q') ?: $request->input('search');
            $query->where('nama_dinas', 'like', '%'.$search.'%');
        }

        return $query->get()->map(function ($instansi) {
            $totalHari = 0;
            $count = 0;

            foreach ($instansi->applications as $app) {
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
    public function getDemografiJurusanData(?Request $request = null)
    {
        $query = InternshipPosition::select('required_major', DB::raw('count(*) as total_lowongan'), DB::raw('sum(kuota) as total_kuota'))
            ->groupBy('required_major');

        if ($request && ($request->filled('q') || $request->filled('search'))) {
            $search = $request->input('q') ?: $request->input('search');
            $query->where('required_major', 'like', '%'.$search.'%');
        }

        return $query->orderBy('total_kuota', 'desc')->get();
    }

    /**
     * Get Penyerapan Kuota Report Data (Admin Kota)
     */
    public function getPenyerapanKuotaData(?Request $request = null)
    {
        $query = Instansi::with(['positions' => function ($q) {
            $q->withCount(['applications as diterima' => function ($query) {
                $query->whereIn('applications.status', ['diterima', 'selesai']);
            }]);
        }]);

        if ($request && ($request->filled('q') || $request->filled('search'))) {
            $search = $request->input('q') ?: $request->input('search');
            $query->where('nama_dinas', 'like', '%'.$search.'%');
        }

        $instansis = $query->get()->map(function ($instansi) {
            $totalKuota = $instansi->positions->sum('kuota');
            $totalTerserap = $instansi->positions->sum('diterima');

            $instansi->total_kuota = $totalKuota;
            $instansi->total_terserap = $totalTerserap;
            $instansi->persentase_penyerapan = $totalKuota > 0 ? ($totalTerserap / $totalKuota) * 100 : 0;

            return $instansi;
        });

        if ($request && $request->filled('status_keterisian')) {
            $status = $request->status_keterisian;
            $instansis = $instansis->filter(function ($instansi) use ($status) {
                if ($status === 'optimal') {
                    return $instansi->persentase_penyerapan >= 80;
                }
                if ($status === 'cukup') {
                    return $instansi->persentase_penyerapan >= 50 && $instansi->persentase_penyerapan < 80;
                }
                if ($status === 'rendah') {
                    return $instansi->persentase_penyerapan < 50;
                }

                return true;
            });
        }

        return $instansis->sortByDesc('persentase_penyerapan')->values();
    }

    /**
     * Get Grading Report Data (Admin Kota)
     */
    public function getGradingReportData(Request $request)
    {
        $query = Application::with(['user', 'position.instansi'])
            ->where(function ($q) {
                $q->whereNotNull('nilai_rata_rata')
                    ->orWhereNotNull('nilai_teknis');
            });

        // Filter yang bisa dipindah ke SQL agar tidak load seluruh data
        if ($request->filled('q')) {
            $term = strtolower($request->q);
            $query->whereHas('user', function ($q) use ($term) {
                $q->whereRaw('LOWER(name) LIKE ?', ['%'.$term.'%']);
            });
        }
        if ($request->filled('instansi_id')) {
            $query->whereHas('position', function ($q) use ($request) {
                $q->where('instansi_id', $request->instansi_id);
            });
        }
        $campus = $request->input('instansi') ?: $request->input('asal_instansi');
        if (! empty($campus)) {
            $term = strtolower($campus);
            $query->whereHas('user', function ($q) use ($term) {
                $q->whereRaw('LOWER(asal_instansi) LIKE ?', ['%'.$term.'%']);
            });
        }

        $gradedData = $query->get()->map(function ($app) {
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

            if ($avg >= 86) {
                $predikat = 'Sangat Baik';
            } elseif ($avg >= 71) {
                $predikat = 'Baik';
            } elseif ($avg >= 56) {
                $predikat = 'Cukup';
            } else {
                $predikat = 'Kurang';
            }

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

        if ($request->filled('predikat')) {
            $gradedData = $gradedData->filter(function ($app) use ($request) {
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
        $instansiQuery = Instansi::query();

        if ($request->filled('q')) {
            $instansiQuery->where('nama_dinas', 'like', '%'.$request->q.'%');
        }

        $instansiIds = $instansiQuery->pluck('id');

        // Agregasi kehadiran per instansi via SQL (1 query), bukan load semua attendance ke PHP
        $aggRows = DB::table('instansis')
            ->whereIn('instansis.id', $instansiIds)
            ->leftJoin('internship_positions', 'internship_positions.instansi_id', '=', 'instansis.id')
            ->leftJoin('applications', function ($join) {
                $join->on('applications.internship_position_id', '=', 'internship_positions.id')
                    ->whereIn('applications.status', ['diterima', 'selesai']);
            })
            ->leftJoin('attendances', 'attendances.application_id', '=', 'applications.id')
            ->groupBy('instansis.id')
            ->select(
                'instansis.id',
                DB::raw('COUNT(attendances.id) as total_attendances'),
                DB::raw("SUM(attendances.status = 'hadir') as total_hadir"),
                DB::raw("SUM(attendances.status = 'sakit') as total_sakit"),
                DB::raw("SUM(attendances.status = 'izin') as total_izin"),
                DB::raw("SUM(attendances.status = 'alpa') as total_alpa"),
                DB::raw("SUM(attendances.status = 'hadir' AND attendances.clock_in > COALESCE(NULLIF(instansis.jam_mulai_masuk, ''), '08:00:00')) as total_terlambat")
            )
            ->get()
            ->keyBy('id');

        // Data pelanggar hanya untuk aplikasi yang punya pelanggaran (1 query agregasi + 1 eager load)
        $pelanggarRows = DB::table('attendances')
            ->join('applications', 'applications.id', '=', 'attendances.application_id')
            ->whereIn('applications.status', ['diterima', 'selesai'])
            ->join('internship_positions', 'internship_positions.id', '=', 'applications.internship_position_id')
            ->whereIn('internship_positions.instansi_id', $instansiIds)
            ->join('instansis', 'instansis.id', '=', 'internship_positions.instansi_id')
            ->groupBy('attendances.application_id')
            ->select(
                'attendances.application_id',
                DB::raw("SUM(attendances.status = 'alpa') as total_alpa"),
                DB::raw("SUM(attendances.status = 'hadir' AND attendances.clock_in > COALESCE(NULLIF(instansis.jam_mulai_masuk, ''), '08:00:00')) as total_terlambat")
            )
            ->get()
            ->filter(function ($row) {
                return ($row->total_alpa + $row->total_terlambat) > 0;
            });

        $pelanggarApps = $pelanggarRows->isNotEmpty()
            ? Application::whereIn('id', $pelanggarRows->pluck('application_id'))
                ->with(['user', 'position'])
                ->get()
                ->keyBy('id')
            : collect();

        $pelanggarByInstansi = [];
        foreach ($pelanggarRows as $row) {
            $app = $pelanggarApps[$row->application_id] ?? null;
            if (! $app || ! $app->position) {
                continue;
            }

            $pelanggarByInstansi[$app->position->instansi_id][] = [
                'nama' => $app->user->name ?? '-',
                'kampus' => $app->user->asal_instansi ?? '-',
                'posisi' => $app->position->judul_posisi ?? '-',
                'terlambat' => (int) $row->total_terlambat,
                'alpa' => (int) $row->total_alpa,
            ];
        }

        $instansis = Instansi::whereIn('id', $instansiIds)->get()->map(function ($instansi) use ($aggRows, $pelanggarByInstansi) {
            $row = $aggRows[$instansi->id] ?? null;

            $totalAttendances = (int) ($row->total_attendances ?? 0);
            $totalTerlambat = (int) ($row->total_terlambat ?? 0);
            $totalAlpa = (int) ($row->total_alpa ?? 0);

            $instansi->total_attendances = $totalAttendances;
            $instansi->total_hadir = (int) ($row->total_hadir ?? 0);
            $instansi->total_sakit = (int) ($row->total_sakit ?? 0);
            $instansi->total_izin = (int) ($row->total_izin ?? 0);
            $instansi->total_alpa = $totalAlpa;
            $instansi->total_terlambat = $totalTerlambat;
            $instansi->total_pelanggaran = $totalTerlambat + $totalAlpa;
            $instansi->tingkat_disiplin = $totalAttendances > 0 ? 100 - (($instansi->total_pelanggaran / $totalAttendances) * 100) : null;
            $instansi->pelanggar_list = collect($pelanggarByInstansi[$instansi->id] ?? [])->sortByDesc(function ($p) {
                return $p['terlambat'] + $p['alpa'];
            })->values()->all();

            return $instansi;
        });

        // Instansi tanpa presensi belum memiliki dasar untuk dinilai dan tidak boleh
        // masuk ranking atau menaikkan rata-rata disiplin.
        $instansis = $instansis
            ->filter(fn ($instansi) => $instansi->total_attendances > 0)
            ->values();

        if ($request->filled('disiplin_range')) {
            $range = $request->disiplin_range;
            $instansis = $instansis->filter(function ($instansi) use ($range) {
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
        $podium = $instansis->take(3);

        $stats = [
            'total_instansi' => $instansis->count(),
            'avg_disiplin' => $instansis->count() > 0 ? round($instansis->avg('tingkat_disiplin'), 1) : 0,
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
    public function getGlobalInternsData(Request $request, bool $paginate = true)
    {
        $query = Application::with(['user', 'position.instansi']);

        $status = $request->input('status', 'semua');

        if ($status === 'pending') {
            $query->whereIn('applications.status', ['pending', 'menunggu']);
        } elseif ($status !== 'semua' && $status !== '') {
            $query->where('applications.status', $status);
        }

        if ($request->has('instansi_id') && $request->instansi_id != '') {
            $query->whereHas('position.instansi', function ($q) use ($request) {
                $q->where('id', $request->instansi_id);
            });
        }

        if ($request->has('instansi') && $request->instansi != '') {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('asal_instansi', $request->instansi);
            });
        }

        if ($request->has('posisi') && $request->posisi != '') {
            $query->whereHas('position', function ($q) use ($request) {
                $q->where('judul_posisi', 'like', '%'.$request->posisi.'%');
            });
        }

        if ($request->filled('q')) {
            $term = $request->q;
            $query->where(function ($q) use ($term) {
                $q->whereHas('user', function ($sub) use ($term) {
                    $sub->where('name', 'like', '%'.$term.'%')
                        ->orWhere('email', 'like', '%'.$term.'%')
                        ->orWhere('asal_instansi', 'like', '%'.$term.'%');
                })->orWhereHas('position', function ($sub) use ($term) {
                    $sub->where('judul_posisi', 'like', '%'.$term.'%')
                        ->orWhereHas('instansi', function ($inst) use ($term) {
                            $inst->where('nama_dinas', 'like', '%'.$term.'%');
                        });
                });
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = $request->start_date;
            $end = $request->end_date;
            $query->where(function ($q) use ($start, $end) {
                $q->where(function ($sub) use ($start, $end) {
                    $sub->where('tanggal_mulai', '<=', $end)
                        ->where('tanggal_selesai', '>=', $start);
                })->orWhereBetween('tanggal_mulai', [$start, $end])
                  ->orWhereBetween('tanggal_selesai', [$start, $end]);
            });
        } elseif ($request->filled('start_date')) {
            $query->where(function ($q) use ($request) {
                $q->where('tanggal_selesai', '>=', $request->start_date)
                    ->orWhere('tanggal_mulai', '>=', $request->start_date);
            });
        } elseif ($request->filled('end_date')) {
            $query->where(function ($q) use ($request) {
                $q->where('tanggal_mulai', '<=', $request->end_date)
                    ->orWhere('tanggal_selesai', '<=', $request->end_date);
            });
        }

        // Statistik dihitung via agregasi SQL, bukan load seluruh tabel ke memori
        $statusTotals = (clone $query)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $stats = [
            'total' => (int) $statusTotals->sum(),
            'aktif' => (int) ($statusTotals['diterima'] ?? 0),
            'selesai' => (int) ($statusTotals['selesai'] ?? 0),
            'pending' => (int) ($statusTotals['pending'] ?? 0) + (int) ($statusTotals['menunggu'] ?? 0),
            'total_dinas' => (clone $query)
                ->join('internship_positions', 'applications.internship_position_id', '=', 'internship_positions.id')
                ->count(DB::raw('DISTINCT internship_positions.instansi_id')),
            'total_kampus' => (clone $query)
                ->join('users', 'applications.user_id', '=', 'users.id')
                ->count(DB::raw('DISTINCT users.asal_instansi')),
        ];

        // Urutan berdasar nama instansi dipindah ke SQL
        $sortedQuery = $query
            ->select('applications.*')
            ->join('internship_positions', 'applications.internship_position_id', '=', 'internship_positions.id')
            ->join('instansis', 'internship_positions.instansi_id', '=', 'instansis.id')
            ->orderBy('instansis.nama_dinas')
            ->orderBy('applications.created_at');

        if ($paginate) {
            // Pagination native SQL, hanya 10 baris per halaman yang di-load
            $allInterns = $sortedQuery->paginate(10)->withQueryString();
        } else {
            $allInterns = $sortedQuery->get();
        }

        return compact('allInterns', 'stats');
    }

    /**
     * Get Kinerja Mahasiswa / Peserta Data (Admin Instansi / Dinas)
     */
    public function getKinerjaPesertaData(int $instansiId)
    {
        return $this->getKinerjaMahasiswaData($instansiId);
    }

    public function getKinerjaMahasiswaData(int $instansiId)
    {
        $kinerja = Application::whereHas('position', function ($q) use ($instansiId) {
            $q->where('instansi_id', $instansiId);
        })->whereIn('status', ['diterima', 'selesai'])
            ->with(['user', 'position', 'logs', 'attendances', 'pembimbing_lapangan'])
            ->get()->map(function ($app) {
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
            'aktif' => $kinerja->where('status.value', 'diterima')->count(),
            'selesai' => $kinerja->where('status.value', 'selesai')->count(),
            'avg_kehadiran' => $kinerja->count() > 0 ? round($kinerja->avg('attendance_rate'), 1) : 0,
            'avg_logbook' => $kinerja->count() > 0 ? round($kinerja->avg('log_rate'), 1) : 0,
            'avg_nilai' => $kinerja->where('status.value', 'selesai')->where('avg_nilai', '>', 0)->count() > 0
                ? round($kinerja->where('status.value', 'selesai')->where('avg_nilai', '>', 0)->avg('avg_nilai'), 1)
                : 0,
        ];

        return compact('kinerja', 'stats');
    }
}
