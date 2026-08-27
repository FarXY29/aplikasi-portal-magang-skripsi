<?php

namespace App\Http\Controllers\AdminKota;

use App\Http\Controllers\Controller;
use App\Models\Instansi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class InstansiController extends Controller
{
    /**
     * Daftar Instansi/OPD
     */
    public function indexInstansi(Request $request)
    {
        $query = Instansi::with('positions')->withCount([
            'applications' => fn ($applicationQuery) => $applicationQuery
                ->whereIn('applications.status', ['diterima', 'selesai']),
        ]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_dinas', 'like', "%{$search}%")
                  ->orWhere('kode_unit_kerja', 'like', "%{$search}%");
            });
        }

        $instansis = $query->latest()->paginate(10)->withQueryString();

        return view('admin_kota.instansi.index', compact('instansis'));
    }

    /**
     * Form Tambah Instansi
     */
    public function create()
    {
        return view('admin_kota.instansi.create');
    }

    /**
     * Simpan Instansi Baru & Buat Akun Admin Instansi
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_dinas' => 'required|string|max:255',
            'kode_unit_kerja' => 'required|string|max:50|unique:instansis',
            'alamat' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius_absen' => 'required|numeric|min:10',
            'ttd_kepala' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'email_admin' => 'required|email|unique:users,email',
            'password_admin' => ['required', Password::defaults()],
        ]);

        $instansi = DB::transaction(function () use ($request) {
            $data = $request->only(['nama_dinas', 'kode_unit_kerja', 'alamat', 'latitude', 'longitude', 'radius_absen']);

            if ($request->hasFile('ttd_kepala')) {
                $data['ttd_kepala'] = $request->file('ttd_kepala')->store('signatures', 'private');
            }

            $instansi = Instansi::create($data);

            $adminUser = User::create([
                'name' => 'Admin ' . $request->nama_dinas,
                'email' => $request->email_admin,
                'password' => Hash::make($request->password_admin),
                'role' => 'admin_instansi',
                'instansi_id' => $instansi->id,
                'email_verified_at' => now(),
            ]);
            $adminUser->syncPrimaryRole();

            return $instansi;
        });

        return redirect()->route('admin.instansi.index')->with('success', 'INSTANSI Baru & Akun Admin berhasil dibuat!');
    }

    /**
     * Form Edit Instansi
     */
    public function edit($id)
    {
        $instansi = Instansi::findOrFail($id);
        $adminUser = User::where('instansi_id', $instansi->id)->portalRole('admin_instansi')->first();
        return view('admin_kota.instansi.edit', compact('instansi', 'adminUser'));
    }

    /**
     * Update Instansi & Akun Admin
     */
    public function update(Request $request, $id)
    {
        $instansi = Instansi::findOrFail($id);
        $adminUser = User::where('instansi_id', $instansi->id)->portalRole('admin_instansi')->first();

        $request->validate([
            'nama_dinas' => 'required|string|max:255',
            'kode_unit_kerja' => 'required|string|max:50|unique:instansis,kode_unit_kerja,'.$instansi->id, 
            'alamat' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius_absen' => 'required|numeric|min:10',
            'ttd_kepala' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'email_admin' => $adminUser
                ? 'required|email|unique:users,email,'.$adminUser->id
                : 'nullable|email|unique:users,email|required_with:password_admin',
            'password_admin' => $adminUser
                ? ['nullable', Password::defaults()]
                : ['nullable', Password::defaults(), 'required_with:email_admin'],
        ]);

        DB::transaction(function () use ($instansi, $adminUser, $request) {
            $data = $request->only(['nama_dinas', 'kode_unit_kerja', 'alamat', 'latitude', 'longitude', 'radius_absen']);

            if ($request->hasFile('ttd_kepala')) {
                if ($instansi->ttd_kepala) {
                    if (Storage::disk('private')->exists($instansi->ttd_kepala)) {
                        Storage::disk('private')->delete($instansi->ttd_kepala);
                    } elseif (Storage::disk('public')->exists($instansi->ttd_kepala)) {
                        Storage::disk('public')->delete($instansi->ttd_kepala);
                    }
                }

                $data['ttd_kepala'] = $request->file('ttd_kepala')->store('signatures', 'private');
            }

            $instansi->update($data);

            if ($adminUser && $request->email_admin) {
                $adminUser->email = $request->email_admin;
                if ($request->filled('password_admin')) {
                    $adminUser->password = Hash::make($request->password_admin);
                }
                $adminUser->save();
                $adminUser->syncPrimaryRole();
            } elseif (!$adminUser && $request->email_admin && $request->filled('password_admin')) {
                $adminUser = User::create([
                    'name' => 'Admin ' . $request->nama_dinas,
                    'email' => $request->email_admin,
                    'password' => Hash::make($request->password_admin),
                    'role' => 'admin_instansi',
                    'instansi_id' => $instansi->id,
                    'email_verified_at' => now(),
                ]);
                $adminUser->syncPrimaryRole();
            }
        });

        return redirect()->route('admin.instansi.index')->with('success', 'Data INSTANSI berhasil diperbarui!');
    }

    /**
     * Hapus Instansi
     */
    public function destroy($id)
    {
        $instansi = Instansi::findOrFail($id);
        $instansi->delete();
        return back()->with('success', 'Data INSTANSI dan Akun Admin terkait telah dihapus.');
    }
}
