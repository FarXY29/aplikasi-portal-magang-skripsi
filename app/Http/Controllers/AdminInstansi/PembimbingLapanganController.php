<?php

namespace App\Http\Controllers\AdminInstansi;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminInstansi\PembimbingLapanganRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PembimbingLapanganController extends Controller
{
    public function indexPembimbingLapangan()
    {
        $instansiId = Auth::user()->instansi_id;
        $pembimbing_lapangan = User::where('instansi_id', $instansiId)->where('role', 'pembimbing_lapangan')->get();
        return view('admin_instansi.pembimbing_lapangan.index', compact('pembimbing_lapangan'));
    }

    public function storePembimbingLapangan(PembimbingLapanganRequest $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pembimbing_lapangan',
            'instansi_id' => Auth::user()->instansi_id,
            'nik' => $request->nip
        ]);

        return back()->with('success', 'Akun Pembimbing Lapangan berhasil dibuat.');
    }

    public function editPembimbingLapangan($id)
    {
        $pembimbing_lapangan = User::where('id', $id)
                    ->where('instansi_id', Auth::user()->instansi_id)
                    ->where('role', 'pembimbing_lapangan')
                    ->firstOrFail();

        return view('admin_instansi.pembimbing_lapangan.edit', compact('pembimbing_lapangan'));
    }

    public function updatePembimbingLapangan(PembimbingLapanganRequest $request, $id)
    {
        $pembimbing_lapangan = User::where('id', $id)
                    ->where('instansi_id', Auth::user()->instansi_id)
                    ->firstOrFail();

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'nik' => $request->nip
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $pembimbing_lapangan->update($data);

        return redirect()->route('dinas.pembimbing_lapangan.index')->with('success', 'Data pembimbing_lapangan berhasil diperbarui.');
    }

    public function destroyPembimbingLapangan($id)
    {
        $user = User::where('id', $id)->where('instansi_id', Auth::user()->instansi_id)->firstOrFail();
        $user->delete();
        return back()->with('success', 'Akun pembimbing_lapangan dihapus.');
    }
}
