<?php

namespace App\Services;

use App\Models\Application;
use App\Models\DailyLog;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PembimbingLogbookService
{
    /**
     * Siapkan data logbook: filter parsing, query, dropdown mahasiswa bimbingan.
     *
     * @return array{
     *     logs: \Illuminate\Database\Eloquent\Collection,
     *     filterType: string,
     *     selectedDate: string,
     *     interns: \Illuminate\Database\Eloquent\Collection
     * }
     */
    public function logbookData(int $applicationId, $request): array
    {
        $filterType = $request->input('filter_type', 'semua');
        $selectedDate = $request->input('date', date('Y-m-d'));
        try {
            $carbonDate = Carbon::parse($selectedDate);
        } catch (\Exception $e) {
            $carbonDate = Carbon::today();
            $selectedDate = $carbonDate->format('Y-m-d');
        }

        $query = DailyLog::where('application_id', $applicationId);

        // Filter berdasarkan Rentang Waktu
        if ($filterType === 'harian') {
            $query->where('tanggal', $selectedDate);
        } elseif ($filterType === 'mingguan') {
            $startOfWeek = $carbonDate->copy()->startOfWeek()->format('Y-m-d');
            $endOfWeek = $carbonDate->copy()->endOfWeek()->format('Y-m-d');
            $query->whereBetween('tanggal', [$startOfWeek, $endOfWeek]);
        } elseif ($filterType === 'bulanan') {
            $query->whereMonth('tanggal', $carbonDate->month)
                  ->whereYear('tanggal', $carbonDate->year);
        }

        // Filter berdasarkan Status Validasi
        if ($request->filled('status_validasi') && in_array($request->status_validasi, ['pending', 'disetujui', 'revisi'])) {
            $query->where('status_validasi', $request->status_validasi);
        }

        // Filter pencarian isi deskripsi kegiatan
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('kegiatan', 'like', "%{$search}%");
        }

        $logs = $query->orderBy('tanggal', 'desc')->get();

        // Data mahasiswa bimbingan lain untuk dropdown switcher
        $interns = Application::where('pembimbing_lapangan_id', Auth::id())
                    ->whereIn('status', ['diterima', 'selesai'])
                    ->with('user')
                    ->get();

        return compact('logs', 'filterType', 'selectedDate', 'interns');
    }
}
