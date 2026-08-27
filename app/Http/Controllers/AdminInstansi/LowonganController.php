<?php

namespace App\Http\Controllers\AdminInstansi;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminInstansi\LowonganRequest;
use App\Models\InternshipPosition;
use App\Models\MajorCategory;
use App\Services\HtmlSanitizer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LowonganController extends Controller
{
    public function indexLowongan(Request $request)
    {
        $instansiId = Auth::user()->instansi_id;
        $query = InternshipPosition::with('requiredMajorCategory')->where('instansi_id', $instansiId);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul_posisi', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhere('required_major', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $lowongans = $query->latest()->get();
        return view('admin_instansi.lowongan.index', compact('lowongans'));
    }

    public function createLowongan()
    {
        $categories = MajorCategory::with(['majors' => fn($q) => $q->where('is_active', true)])->orderBy('name')->get();
        return view('admin_instansi.lowongan.create', compact('categories'));
    }

    public function storeLowongan(LowonganRequest $request)
    {
        $category = null;
        if ($request->filled('required_major_category_id')) {
            $category = MajorCategory::find($request->required_major_category_id);
        }

        $requiredMajor = $request->required_major;
        if (empty($requiredMajor) && $category) {
            $requiredMajor = $category->name;
        }

        InternshipPosition::create([
            'instansi_id' => Auth::user()->instansi_id,
            'judul_posisi' => $request->judul_posisi,
            'required_major_category_id' => $request->required_major_category_id,
            'required_major' => $requiredMajor,
            'deskripsi' => $request->deskripsi,
            'deskripsi' => HtmlSanitizer::clean($request->deskripsi),
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

        $categories = MajorCategory::with(['majors' => fn($q) => $q->where('is_active', true)])->orderBy('name')->get();

        return view('admin_instansi.lowongan.edit', compact('loker', 'categories'));
    }

    public function updateLowongan(LowonganRequest $request, $id)
    {
        $loker = InternshipPosition::where('id', $id)
                    ->where('instansi_id', Auth::user()->instansi_id)
                    ->firstOrFail();
        $this->authorize('manage', $loker);

        $category = null;
        if ($request->filled('required_major_category_id')) {
            $category = MajorCategory::find($request->required_major_category_id);
        }

        $requiredMajor = $request->required_major;
        if (empty($requiredMajor) && $category) {
            $requiredMajor = $category->name;
        }

        $loker->update([
            'judul_posisi' => $request->judul_posisi,
            'required_major_category_id' => $request->required_major_category_id,
            'required_major' => $requiredMajor,
            'deskripsi' => $request->deskripsi,
            'deskripsi' => HtmlSanitizer::clean($request->deskripsi),
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
