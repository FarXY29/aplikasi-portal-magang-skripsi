<?php

namespace App\Http\Controllers\AdminInstansi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function editPejabat()
    {
        $instansi = Auth::user()->instansi;
        return view('admin_instansi.profil.edit_pejabat', compact('instansi'));
    }

    public function updatePejabat(Request $request)
    {
        $request->validate([
            'nama_pejabat' => 'required|string|max:255',
            'nip_pejabat' => 'required|string|max:50',
            'jabatan_pejabat' => 'required|string|max:100',
            'ttd_kepala' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]); 

        $instansi = Auth::user()->instansi;

        $dataToUpdate = [
            'nama_pejabat' => $request->nama_pejabat,
            'nip_pejabat' => $request->nip_pejabat,
            'jabatan_pejabat' => $request->jabatan_pejabat,
        ];

        if ($request->hasFile('ttd_kepala')) {
            if ($instansi->ttd_kepala && Storage::disk('private')->exists($instansi->ttd_kepala)) {
                Storage::disk('private')->delete($instansi->ttd_kepala);
            } elseif ($instansi->ttd_kepala && Storage::disk('public')->exists($instansi->ttd_kepala)) {
                Storage::disk('public')->delete($instansi->ttd_kepala);
            }

            $path = $request->file('ttd_kepala')->store('signatures', 'private');
            $dataToUpdate['ttd_kepala'] = $path;
        }

        $instansi->update($dataToUpdate);

        return back()->with('success', 'Data pejabat penandatangan berhasil diperbarui!');
    }

    public function settings()
    {
        $instansi = Auth::user()->instansi;
        return view('admin_instansi.settings', compact('instansi'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'jam_mulai_masuk' => 'nullable|string|regex:/^\d{1,2}:\d{2}(:\d{2})?$/',
            'jam_mulai_pulang' => 'nullable|string|regex:/^\d{1,2}:\d{2}(:\d{2})?$/',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'radius_absen' => 'nullable|integer|min:10|max:10000',
        ]);

        $instansi = Auth::user()->instansi;
        
        $data = [];
        if ($request->has('jam_mulai_masuk')) $data['jam_mulai_masuk'] = $request->jam_mulai_masuk;
        if ($request->has('jam_mulai_pulang')) $data['jam_mulai_pulang'] = $request->jam_mulai_pulang;
        if ($request->has('latitude')) $data['latitude'] = $request->latitude;
        if ($request->has('longitude')) $data['longitude'] = $request->longitude;
        if ($request->has('radius_absen')) $data['radius_absen'] = $request->radius_absen;

        $instansi->update($data);

        return back()->with('success', 'Pengaturan instansi berhasil diperbarui.');
    }
}
