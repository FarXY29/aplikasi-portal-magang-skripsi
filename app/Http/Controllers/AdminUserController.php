<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Instansi;
use App\Models\DailyLog;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminUserController extends Controller
{

    public function index(Request $request)
    {
        $query = User::with('instansi');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
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
            'role' => 'required',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'instansi_id' => $request->instansi_id,
            'asal_instansi' => $request->asal_instansi,
            'nik' => $request->nik,
            'phone' => $request->phone,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $instansis = Instansi::all();
        return view('admin_kota.users.edit', compact('user', 'instansis'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => 'required',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'instansi_id' => $request->instansi_id,
            'asal_instansi' => $request->asal_instansi,
            'nik' => $request->nik,
            'phone' => $request->phone,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if (auth()->id() == $user->id) return back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        $user->delete();
        return back()->with('success', 'Pengguna telah dihapus.');
    }

    // --- FITUR BARU: MONITORING LOGBOOK (SUPER ADMIN) ---

    // 1. Daftar Peserta untuk Dipantau
    public function logbooks(Request $request)
    {
        // Ambil hanya user dengan role 'peserta'
        $query = User::where('role', 'peserta')->with('applications.position.instansi');

        // Pencarian
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', "%{$request->search}%");
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

        if (!$app) {
            return back()->with('error', 'Peserta ini belum memiliki data magang.');
        }

        // Ambil logbooknya
        $logs = DailyLog::where('application_id', $app->id)->orderBy('tanggal', 'desc')->get();

        return view('admin_kota.users.logbook_detail', compact('user', 'app', 'logs'));
    }

    // Cetak Laporan Data Master Peserta (PDF)
    public function printParticipants()
    {
        // Ambil hanya user dengan role 'peserta'
        // Urutkan berdasarkan nama agar rapi
        $participants = User::where('role', 'peserta')
                            ->orderBy('name', 'asc')
                            ->get();

        // Load View PDF
        $pdf = Pdf::loadView('pdf.admin_kota.peserta', compact('participants'));

        // Setup Kertas A4 Landscape
        $pdf->setPaper('a4', 'landscape');

        // Stream (Tampilkan di browser)
        return $pdf->stream('Laporan-Master-Peserta.pdf');
    }

}