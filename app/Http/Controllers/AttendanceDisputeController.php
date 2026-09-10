<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceDispute;
use App\Services\AttendanceDisputeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceDisputeController extends Controller
{
    public function __construct(private AttendanceDisputeService $disputeService)
    {
    }

    /**
     * Submit an attendance dispute (for participant).
     */
    public function store(Request $request, $attendance)
    {
        $attendanceModel = $attendance instanceof Attendance
            ? $attendance->loadMissing('application')
            : Attendance::with('application')->findOrFail($attendance);

        $this->authorize('submit', [AttendanceDispute::class, $attendanceModel]);

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
            'proposed_status' => ['required', 'in:hadir,izin,sakit'],
            'evidence_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        $this->disputeService->submitDispute(
            $attendanceModel,
            array_merge($validated, ['evidence_file' => $request->file('evidence_file')]),
            Auth::user()
        );

        return back()->with('success', 'Sanggahan absensi berhasil diajukan dan sedang menunggu peninjauan.');
    }

    /**
     * Review an attendance dispute (for mentor / admin instansi / admin kota).
     */
    public function review(Request $request, $dispute)
    {
        $disputeModel = $dispute instanceof AttendanceDispute
            ? $dispute->loadMissing(['attendance.application.position'])
            : AttendanceDispute::with(['attendance.application.position'])->findOrFail($dispute);

        $this->authorize('review', $disputeModel);

        $validated = $request->validate([
            'action' => ['required', 'in:approve,reject'],
            'reviewer_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        try {
            $this->disputeService->reviewDispute(
                $disputeModel,
                $validated['action'],
                $validated['reviewer_notes'] ?? null,
                Auth::user()
            );
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        $message = $validated['action'] === 'approve'
            ? 'Sanggahan absensi disetujui. Status absensi telah diperbarui.'
            : 'Sanggahan absensi ditolak.';

        return back()->with('success', $message);
    }
}
