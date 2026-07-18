<?php

namespace App\Http\Controllers\AdminInstansi;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminInstansi\LowonganRequest;
use App\Models\InternshipPosition;
use Illuminate\Support\Facades\Auth;

class LowonganController extends Controller
{
    public function indexLowongan()
    {
        $instansiId = Auth::user()->instansi_id;
        $lowongans = InternshipPosition::where('instansi_id', $instansiId)->get();
        return view('admin_instansi.lowongan.index', compact('lowongans'));
    }

    public function createLowongan()
    {
        return view('admin_instansi.lowongan.create');
    }

    public function storeLowongan(LowonganRequest $request)
    {
        InternshipPosition::create([
            'instansi_id' => Auth::user()->instansi_id,
            'judul_posisi' => $request->judul_posisi,
            'required_major' => $request->required_major,
            'deskripsi' => $request->deskripsi,
            'kuota' => $request->kuota,
            'batas_daftar' => $request->batas_daftar,
            'status' => 'buka'
        ]);

        return redirect()->route('dinas.lowongan.index')->with('success', 'Lowongan berhasil dibuat!');
    }

    public function editLowongan($id)
    {
        $loker = InternshipPosition::where('id', $id)
                    ->where('instansi_id', Auth::user()->instansi_id)
                    ->firstOrFail();
        $this->authorize('manage', $loker);

        return view('admin_instansi.lowongan.edit', compact('loker'));
    }

    public function updateLowongan(LowonganRequest $request, $id)
    {
        $loker = InternshipPosition::where('id', $id)
                    ->where('instansi_id', Auth::user()->instansi_id)
                    ->firstOrFail();
        $this->authorize('manage', $loker);

        $loker->update([
            'judul_posisi' => $request->judul_posisi,
            'required_major' => $request->required_major,
            'deskripsi' => $request->deskripsi,
            'kuota' => $request->kuota,
            'batas_daftar' => $request->batas_daftar,
            'status' => $request->status
        ]);

        return redirect()->route('dinas.lowongan.index')->with('success', 'Lowongan berhasil diperbarui!');
    }

    public function destroyLowongan($id)
    {
        $loker = InternshipPosition::where('id', $id)->where('instansi_id', Auth::user()->instansi_id)->firstOrFail();
        $this->authorize('manage', $loker);
        $loker->delete();
        return back()->with('success', 'Lowongan dihapus.');
    }
}
