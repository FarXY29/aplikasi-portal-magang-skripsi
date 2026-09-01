<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\MajorCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LowonganController extends Controller
{
    /**
     * Tampilkan daftar lowongan dengan filter & pencarian di landing page / halaman lowongan
     */
    public function index(Request $request)
    {
        $query = InternshipPosition::with(['instansi', 'requiredMajorCategory'])
                    ->where('status', 'buka')
                    ->where('kuota', '>', 0); 

        // 1. Filter Instansi / Dinas
        if ($request->filled('instansi_id')) {
            $query->where('instansi_id', $request->instansi_id);
        }

        // 2. Filter Rumpun Keilmuan (MajorCategory)
        if ($request->filled('major_category_id')) {
            $catId = $request->major_category_id;
            $cat = MajorCategory::find($catId);
            $catCode = strtoupper((string) ($cat?->code ?? ''));
            $catName = (string) ($cat?->name ?? '');

            $query->where(function($q) use ($catId, $catCode, $catName) {
                $q->where('required_major_category_id', $catId);

                if (!empty($catName)) {
                    $q->orWhere('required_major', 'like', "%{$catName}%");
                }
                if (!empty($catCode)) {
                    $q->orWhere('required_major', 'like', "%{$catCode}%");
                }

                // Keyword mapping per rumpun code
                if (in_array($catCode, ['TIK', 'IT', 'KOMP', 'TI'])) {
                    $q->orWhere('required_major', 'like', '%informatika%')
                      ->orWhere('required_major', 'like', '%komputer%')
                      ->orWhere('required_major', 'like', '%software%')
                      ->orWhere('required_major', 'like', '%rpl%')
                      ->orWhere('required_major', 'like', '%tkj%')
                      ->orWhere('required_major', 'like', '%sistem informasi%');
                } elseif (in_array($catCode, ['EKBIS', 'AKT', 'MNJ', 'EKONOMI'])) {
                    $q->orWhere('required_major', 'like', '%akuntansi%')
                      ->orWhere('required_major', 'like', '%manajemen%')
                      ->orWhere('required_major', 'like', '%keuangan%')
                      ->orWhere('required_major', 'like', '%ekonomi%')
                      ->orWhere('required_major', 'like', '%bisnis%')
                      ->orWhere('required_major', 'like', '%pajak%');
                } elseif (in_array($catCode, ['HKM', 'HUKUM'])) {
                    $q->orWhere('required_major', 'like', '%hukum%')
                      ->orWhere('required_major', 'like', '%legal%')
                      ->orWhere('required_major', 'like', '%syariah%');
                } elseif (in_array($catCode, ['DSN', 'DKV', 'DESAIN', 'SENI'])) {
                    $q->orWhere('required_major', 'like', '%desain%')
                      ->orWhere('required_major', 'like', '%dkv%')
                      ->orWhere('required_major', 'like', '%grafis%')
                      ->orWhere('required_major', 'like', '%multimedia%')
                      ->orWhere('required_major', 'like', '%animasi%');
                } elseif (in_array($catCode, ['KES', 'KESH', 'MEDIS', 'KESEHATAN'])) {
                    $q->orWhere('required_major', 'like', '%kesehatan%')
                      ->orWhere('required_major', 'like', '%medis%')
                      ->orWhere('required_major', 'like', '%keperawatan%')
                      ->orWhere('required_major', 'like', '%kebidanan%')
                      ->orWhere('required_major', 'like', '%farmasi%')
                      ->orWhere('required_major', 'like', '%epidemiologi%');
                } elseif (in_array($catCode, ['TEK', 'TKN', 'TEKNIK'])) {
                    $q->orWhere('required_major', 'like', '%sipil%')
                      ->orWhere('required_major', 'like', '%arsitektur%')
                      ->orWhere('required_major', 'like', '%elektro%')
                      ->orWhere('required_major', 'like', '%mesin%')
                      ->orWhere('required_major', 'like', '%lingkungan%');
                }
            });
        }

        // 3. Filter Jurusan / Keyword Tertentu
        if ($request->filled('jurusan')) {
            $jurusan = trim($request->jurusan);
            $query->where(function($q) use ($jurusan) {
                $q->where('required_major', 'like', "%{$jurusan}%")
                  ->orWhereHas('requiredMajorCategory', function($sq) use ($jurusan) {
                      $sq->where('name', 'like', "%{$jurusan}%")
                        ->orWhere('code', 'like', "%{$jurusan}%");
                  });

                // Mapping kata kunci populer dari quick filter pills
                if (stripos($jurusan, 'informatika') !== false || stripos($jurusan, 'it') !== false) {
                    $q->orWhere('required_major', 'like', '%komputer%')
                      ->orWhere('required_major', 'like', '%sistem informasi%')
                      ->orWhere('required_major', 'like', '%rpl%')
                      ->orWhere('required_major', 'like', '%tkj%');
                } elseif (stripos($jurusan, 'akuntansi') !== false || stripos($jurusan, 'keuangan') !== false) {
                    $q->orWhere('required_major', 'like', '%akuntansi%')
                      ->orWhere('required_major', 'like', '%keuangan%')
                      ->orWhere('required_major', 'like', '%pajak%');
                } elseif (stripos($jurusan, 'administrasi') !== false) {
                    $q->orWhere('required_major', 'like', '%administrasi%')
                      ->orWhere('required_major', 'like', '%perkantoran%')
                      ->orWhere('required_major', 'like', '%manajemen%');
                } elseif (stripos($jurusan, 'desain') !== false) {
                    $q->orWhere('required_major', 'like', '%dkv%')
                      ->orWhere('required_major', 'like', '%grafis%')
                      ->orWhere('required_major', 'like', '%multimedia%');
                } elseif (stripos($jurusan, 'smk') !== false) {
                    $q->orWhere('required_major', 'like', '%smk%');
                } elseif (stripos($jurusan, 'sma') !== false) {
                    $q->orWhere('required_major', 'like', '%sma%');
                } elseif (stripos($jurusan, 's1') !== false) {
                    $q->orWhere('required_major', 'like', '%s1%');
                }
            });
        }

        // 4. Pencarian Terpadu (Posisi / Instansi / Keyword)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('judul_posisi', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhere('required_major', 'like', "%{$search}%")
                  ->orWhereHas('instansi', function($sq) use ($search) {
                      $sq->where('nama_dinas', 'like', "%{$search}%")
                        ->orWhere('alamat', 'like', "%{$search}%");
                  })
                  ->orWhereHas('requiredMajorCategory', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                  });
            });
        }

        // 5. Filter Posisi Langsung
        if ($request->filled('posisi')) {
            $query->where('judul_posisi', 'like', '%' . trim($request->posisi) . '%');
        }

        // 6. Pengurutan Data (Sorting)
        $sort = $request->get('sort', 'latest');
        if ($sort === 'deadline_asc') {
            $query->orderBy('batas_daftar', 'asc')->orderBy('id', 'desc');
        } elseif ($sort === 'quota_desc') {
            $query->orderBy('kuota', 'desc')->orderBy('id', 'desc');
        } else {
            $query->latest();
        }

        $lowongans = $query->paginate(9);
        $lowongans->appends($request->query())->fragment('lowongan'); 

        $cachedData = Cache::remember('landing_page_stats_v3', 3600, function () {
            return [
                'instansis' => Instansi::orderBy('nama_dinas', 'asc')->get(),
                'majorCategories' => MajorCategory::orderBy('name', 'asc')->get(),
                'totalInstansi' => Instansi::count(),
                'totalLowongan' => InternshipPosition::where('status', 'buka')->count(),
                'totalAlumni' => Application::where('status', 'selesai')->count(),
            ];
        });

        $instansis = $cachedData['instansis'];
        $majorCategories = $cachedData['majorCategories'];
        $totalInstansi = $cachedData['totalInstansi'];
        $totalLowongan = $cachedData['totalLowongan'];
        $totalAlumni = $cachedData['totalAlumni'];

        if ($request->ajax() || $request->header('X-Alpine-Fetch') || $request->query('partial') === 'grid') {
            return view('public.welcome._lowongan-grid', compact(
                'lowongans', 'instansis', 'majorCategories',
                'totalInstansi', 'totalLowongan', 'totalAlumni'
            ));
        }

        return view('public.welcome', compact(
            'lowongans', 'instansis', 'majorCategories',
            'totalInstansi', 'totalLowongan', 'totalAlumni'
        )); 
    }

    /**
     * Tampilkan detail spesifik dari suatu lowongan
     */
    public function show($id)
    {
        $position = InternshipPosition::with(['instansi', 'requiredMajorCategory'])
            ->where('status', 'buka')
            ->findOrFail($id);

        return view('public.lowongan.show', compact('position'));
    }
}
