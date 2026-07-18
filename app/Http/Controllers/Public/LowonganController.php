<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use Illuminate\Http\Request;

class LowonganController extends Controller
{
    /**
     * Tampilkan daftar lowongan dengan filter & pencarian di landing page / halaman lowongan
     */
    public function index(Request $request)
    {
        $query = InternshipPosition::with('instansi')
                    ->where('status', 'buka')
                    ->where('kuota', '>', 0); 

        if ($request->has('instansi_id') && $request->instansi_id != '') {
            $query->where('instansi_id', $request->instansi_id);
        }

        if ($request->has('posisi') && $request->posisi != '') {
            $query->where('judul_posisi', 'like', '%' . $request->posisi . '%');
        }

        if ($request->has('jurusan') && $request->jurusan != '') {
            $jurusan = $request->jurusan;
            $query->where(function($q) use ($jurusan) {
                $q->where('required_major', 'like', '%' . $jurusan . '%')
                  ->orWhere('required_major', 'like', '%semua%')
                  ->orWhere('required_major', '=', '-')
                  ->orWhereNull('required_major');
            });
        }

        if ($request->has('search') && $request->search != '') {
             $search = $request->search;
             $query->where(function($q) use ($search) {
                 $q->where('judul_posisi', 'like', "%{$search}%")
                   ->orWhereHas('instansi', function($sq) use ($search) {
                       $sq->where('nama_dinas', 'like', "%{$search}%");
                   });
             });
        }

        $lowongans = $query->latest()->paginate(9);
        $lowongans->appends($request->query())->fragment('lowongan'); 

        $cachedData = \Illuminate\Support\Facades\Cache::remember('landing_page_stats', 3600, function () {
            return [
                'instansis' => Instansi::orderBy('nama_dinas', 'asc')->get(),
                'totalInstansi' => Instansi::count(),
                'totalLowongan' => InternshipPosition::where('status', 'buka')->count(),
                'totalAlumni' => Application::where('status', 'selesai')->count(),
            ];
        });

        $instansis = $cachedData['instansis'];
        $totalInstansi = $cachedData['totalInstansi'];
        $totalLowongan = $cachedData['totalLowongan'];
        $totalAlumni = $cachedData['totalAlumni'];

        return view('public.welcome', compact(
            'lowongans', 'instansis', 
            'totalInstansi', 'totalLowongan', 'totalAlumni'
        )); 
    }

    /**
     * Tampilkan detail spesifik dari suatu lowongan
     */
    public function show($id)
    {
        $position = InternshipPosition::with('instansi')->findOrFail($id);
        return view('public.lowongan.show', compact('position'));
    }
}
