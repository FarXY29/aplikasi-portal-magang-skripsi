<?php

namespace App\Http\Controllers\AdminKota;

use App\Http\Controllers\Controller;
use App\Models\Major;
use App\Models\MajorCategory;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MajorController extends Controller
{
    protected AuditLogService $auditLogService;

    public function __construct(AuditLogService $auditLogService)
    {
        $this->auditLogService = $auditLogService;
    }

    /**
     * Tampilkan daftar Master Program Studi / Jurusan.
     */
    public function index(Request $request)
    {
        $query = Major::with('category')->withCount('users');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('category', function ($catQuery) use ($search) {
                      $catQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('code', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('category_id')) {
            $query->where('major_category_id', $request->category_id);
        }

        if ($request->filled('degree_level')) {
            $query->where('degree_level', $request->degree_level);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $majors = $query->orderBy('name', 'asc')->paginate(12)->withQueryString();
        $categories = MajorCategory::orderBy('name', 'asc')->get();

        return view('admin_kota.majors.index', compact('majors', 'categories'));
    }

    /**
     * Form Tambah Program Studi / Jurusan.
     */
    public function create()
    {
        $categories = MajorCategory::orderBy('name', 'asc')->get();

        return view('admin_kota.majors.create', compact('categories'));
    }

    /**
     * Simpan Program Studi Baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'major_category_id' => 'required|exists:major_categories,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('majors')->where(function ($query) use ($request) {
                    return $query->where('major_category_id', $request->major_category_id)
                                 ->where('degree_level', $request->degree_level);
                }),
            ],
            'degree_level' => ['required', Rule::in(['SMK', 'D3', 'D4', 'S1', 'S2'])],
            'is_active' => 'nullable|boolean',
        ], [
            'major_category_id.required' => 'Pilih rumpun keilmuan terlebih dahulu.',
            'major_category_id.exists' => 'Rumpun keilmuan yang dipilih tidak valid.',
            'name.required' => 'Nama program studi / jurusan wajib diisi.',
            'name.unique' => 'Program studi dengan jenjang dan rumpun yang sama sudah ada.',
            'degree_level.required' => 'Jenjang pendidikan wajib dipilih.',
        ]);

        $validated['is_active'] = $request->has('is_active') ? (bool) $request->is_active : true;

        $major = Major::create($validated);

        $this->auditLogService->record('major.created', $major, [
            'name' => $major->name,
            'degree_level' => $major->degree_level,
            'category_id' => $major->major_category_id,
        ]);

        return redirect()->route('admin.master.majors.index')
            ->with('success', 'Program studi ' . $major->name . ' (' . $major->degree_level . ') berhasil ditambahkan.');
    }

    /**
     * Form Edit Program Studi.
     */
    public function edit($id)
    {
        $major = Major::findOrFail($id);
        $categories = MajorCategory::orderBy('name', 'asc')->get();

        return view('admin_kota.majors.edit', compact('major', 'categories'));
    }

    /**
     * Update Data Program Studi.
     */
    public function update(Request $request, $id)
    {
        $major = Major::findOrFail($id);

        $validated = $request->validate([
            'major_category_id' => 'required|exists:major_categories,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('majors')->where(function ($query) use ($request) {
                    return $query->where('major_category_id', $request->major_category_id)
                                 ->where('degree_level', $request->degree_level);
                })->ignore($major->id),
            ],
            'degree_level' => ['required', Rule::in(['SMK', 'D3', 'D4', 'S1', 'S2'])],
            'is_active' => 'nullable|boolean',
        ], [
            'major_category_id.required' => 'Pilih rumpun keilmuan terlebih dahulu.',
            'major_category_id.exists' => 'Rumpun keilmuan tidak valid.',
            'name.required' => 'Nama program studi / jurusan wajib diisi.',
            'name.unique' => 'Program studi dengan jenjang dan rumpun yang sama sudah ada.',
            'degree_level.required' => 'Jenjang pendidikan wajib dipilih.',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $major->update($validated);

        $this->auditLogService->record('major.updated', $major, [
            'name' => $major->name,
            'degree_level' => $major->degree_level,
            'is_active' => $major->is_active,
        ]);

        return redirect()->route('admin.master.majors.index')
            ->with('success', 'Program studi ' . $major->name . ' (' . $major->degree_level . ') berhasil diperbarui.');
    }

    /**
     * Toggle status aktif/nonaktif program studi.
     */
    public function toggleStatus($id)
    {
        $major = Major::findOrFail($id);
        $major->is_active = !$major->is_active;
        $major->save();

        $statusText = $major->is_active ? 'diaktifkan' : 'dinonaktifkan';

        $this->auditLogService->record('major.status_toggled', $major, [
            'is_active' => $major->is_active,
        ]);

        return back()->with('success', 'Status program studi ' . $major->name . ' berhasil ' . $statusText . '.');
    }

    /**
     * Hapus Program Studi.
     */
    public function destroy($id)
    {
        $major = Major::withCount('users')->findOrFail($id);

        if ($major->users_count > 0) {
            return back()->with('error', 'Program studi tidak dapat dihapus karena telah terhubung dengan data ' . $major->users_count . ' peserta magang.');
        }

        $majorName = $major->name . ' (' . $major->degree_level . ')';
        $major->delete();

        $this->auditLogService->record('major.deleted', null, [
            'deleted_name' => $majorName,
        ]);

        return redirect()->route('admin.master.majors.index')
            ->with('success', 'Program studi ' . $majorName . ' berhasil dihapus.');
    }
}
