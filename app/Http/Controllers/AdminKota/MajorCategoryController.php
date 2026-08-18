<?php

namespace App\Http\Controllers\AdminKota;

use App\Http\Controllers\Controller;
use App\Models\MajorCategory;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MajorCategoryController extends Controller
{
    protected AuditLogService $auditLogService;

    public function __construct(AuditLogService $auditLogService)
    {
        $this->auditLogService = $auditLogService;
    }

    /**
     * Tampilkan daftar Rumpun Keilmuan.
     */
    public function index(Request $request)
    {
        $query = MajorCategory::withCount(['majors', 'internshipPositions']);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $categories = $query->orderBy('name', 'asc')->paginate(10)->withQueryString();

        return view('admin_kota.majors.categories.index', compact('categories'));
    }

    /**
     * Form Tambah Rumpun Keilmuan.
     */
    public function create()
    {
        return view('admin_kota.majors.categories.create');
    }

    /**
     * Simpan Rumpun Keilmuan Baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:major_categories,name',
            'code' => 'required|string|max:50|unique:major_categories,code',
            'description' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'Nama rumpun keilmuan wajib diisi.',
            'name.unique' => 'Nama rumpun keilmuan sudah ada dalam sistem.',
            'code.required' => 'Kode singkatan rumpun wajib diisi.',
            'code.unique' => 'Kode singkatan sudah digunakan.',
        ]);

        $category = MajorCategory::create($validated);

        $this->auditLogService->record('major_category.created', $category, [
            'name' => $category->name,
            'code' => $category->code,
        ]);

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Rumpun keilmuan ' . $category->name . ' berhasil ditambahkan.',
                'category' => $category,
            ], 201);
        }

        return redirect()->route('admin.master.major-categories.index')
            ->with('success', 'Rumpun keilmuan ' . $category->name . ' berhasil ditambahkan.');
    }

    /**
     * Form Edit Rumpun Keilmuan.
     */
    public function edit($id)
    {
        $category = MajorCategory::findOrFail($id);

        return view('admin_kota.majors.categories.edit', compact('category'));
    }

    /**
     * Update Rumpun Keilmuan.
     */
    public function update(Request $request, $id)
    {
        $category = MajorCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('major_categories', 'name')->ignore($category->id)],
            'code' => ['required', 'string', 'max:50', Rule::unique('major_categories', 'code')->ignore($category->id)],
            'description' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'Nama rumpun keilmuan wajib diisi.',
            'name.unique' => 'Nama rumpun keilmuan sudah ada dalam sistem.',
            'code.required' => 'Kode singkatan rumpun wajib diisi.',
            'code.unique' => 'Kode singkatan sudah digunakan.',
        ]);

        $category->update($validated);

        $this->auditLogService->record('major_category.updated', $category, [
            'name' => $category->name,
            'code' => $category->code,
        ]);

        return redirect()->route('admin.master.major-categories.index')
            ->with('success', 'Rumpun keilmuan ' . $category->name . ' berhasil diperbarui.');
    }

    /**
     * Hapus Rumpun Keilmuan.
     */
    public function destroy($id)
    {
        $category = MajorCategory::withCount(['majors', 'internshipPositions'])->findOrFail($id);

        if ($category->majors_count > 0 || $category->internship_positions_count > 0) {
            return back()->with('error', 'Rumpun keilmuan tidak dapat dihapus karena masih memiliki relasi data program studi atau lowongan.');
        }

        $categoryName = $category->name;
        $category->delete();

        $this->auditLogService->record('major_category.deleted', null, [
            'deleted_name' => $categoryName,
        ]);

        return redirect()->route('admin.master.major-categories.index')
            ->with('success', 'Rumpun keilmuan ' . $categoryName . ' berhasil dihapus.');
    }
}
