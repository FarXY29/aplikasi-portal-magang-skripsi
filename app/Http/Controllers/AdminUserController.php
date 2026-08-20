<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\DailyLog;
use App\Models\Instansi;
use App\Models\Setting;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['instansi', 'roles']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('role') && $request->role != '') {
            $query->portalRole($request->role);
        }

        $users = $query->latest()->paginate(10);
        $users->appends($request->all());

        return view('admin_kota.users.index', compact('users'));
    }

    public function create()
    {
        $instansis = Instansi::all();

        return view('admin_kota.users.create', compact('instansis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => ['required', Rule::in(User::PORTAL_ROLES)],
            'instansi_id' => [
                User::usesInstansiId($request->input('role')) ? 'required' : 'nullable',
                'integer',
                Rule::exists('instansis', 'id'),
            ],
            'asal_instansi' => [
                User::usesAsalInstansi($request->input('role')) ? 'required' : 'nullable',
                'string',
                'max:255',
            ],
        ]);

        $usesInstansi = User::usesInstansiId($request->role);
        $usesAsalInstansi = User::usesAsalInstansi($request->role);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'instansi_id' => $usesInstansi ? $request->instansi_id : null,
            'asal_instansi' => $usesAsalInstansi ? $request->asal_instansi : null,
            'nik' => $request->nik,
            'phone' => $request->phone,
        ]);

        $user->syncPrimaryRole();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $instansis = Instansi::all();
        $portalRole = $user->getPrimaryPortalRole();

        return view('admin_kota.users.edit', compact('user', 'instansis', 'portalRole'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'role' => ['required', Rule::in(User::PORTAL_ROLES)],
            'instansi_id' => [
                User::usesInstansiId($request->input('role')) ? 'required' : 'nullable',
                'integer',
                Rule::exists('instansis', 'id'),
            ],
            'asal_instansi' => [
                User::usesAsalInstansi($request->input('role')) ? 'required' : 'nullable',
                'string',
                'max:255',
            ],
        ]);

        $usesInstansi = User::usesInstansiId($request->role);
        $usesAsalInstansi = User::usesAsalInstansi($request->role);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'instansi_id' => $usesInstansi ? $request->instansi_id : null,
            'asal_instansi' => $usesAsalInstansi ? $request->asal_instansi : null,
            'nik' => $request->nik,
            'phone' => $request->phone,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        $user->syncPrimaryRole();

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if (auth()->id() == $user->id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }
        $user->delete();

        return back()->with('success', 'Pengguna telah dihapus.');
    }

    // --- FITUR BARU: MONITORING LOGBOOK (SUPER ADMIN) ---

    // 1. Daftar Peserta untuk Dipantau
    public function logbooks(Request $request)
    {
        // Ambil hanya user dengan role 'peserta'
        $query = User::portalRole('peserta')->with([
            'applications' => fn ($applicationQuery) => $applicationQuery->latest('created_at'),
            'applications.position.instansi',
        ]);

        // Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($participantQuery) use ($search) {
                $participantQuery->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('asal_instansi', 'like', "%{$search}%");
            });
        }

        $participants = $query->latest()->paginate(10);
        $participants->appends($request->all());

        return view('admin_kota.users.logbooks', compact('participants'));
    }

    // 2. Detail Logbook Peserta Tertentu
    public function showLogbook($userId)
    {
        $user = User::findOrFail($userId);

        // Ambil aplikasi aktif/terakhir peserta ini
        $app = Application::where('user_id', $userId)
            ->with(['position.instansi', 'logs'])
            ->latest()
            ->first();

        if (! $app) {
            return back()->with('error', 'Peserta ini belum memiliki data magang.');
        }

        // Ambil logbooknya
        $logs = DailyLog::where('application_id', $app->id)->orderBy('tanggal', 'desc')->get();

        return view('admin_kota.users.logbook_detail', compact('user', 'app', 'logs'));
    }

    // Cetak Laporan Data Master Peserta (PDF)
    public function printParticipants(\App\Services\PdfExportService $pdfService)
    {
        // Ambil hanya user dengan role 'peserta'
        // Urutkan berdasarkan nama agar rapi, hanya kolom yang dipakai template PDF
        $participants = User::portalRole('peserta')
            ->select(['name', 'nik', 'asal_instansi', 'major', 'email', 'phone'])
            ->orderBy('name', 'asc')
            ->get();

        return $pdfService->stream('pdf.admin_kota.peserta', compact('participants'), 'Laporan-Master-Peserta.pdf', 'a4', 'landscape', true);
    }
}
