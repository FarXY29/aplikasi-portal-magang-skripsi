<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class AdminSettingController extends Controller
{
    public function index()
    {
        // Ambil semua setting dan ubah jadi array key => value
        $settings = Setting::all()->pluck('value', 'key');
        
        return view('admin_kota.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        // 1. Simpan Nama Aplikasi
        Setting::updateOrCreate(
            ['key' => 'app_name'],
            ['value' => $request->input('app_name')]
        );

        // 2. Simpan Pengumuman
        Setting::updateOrCreate(
            ['key' => 'announcement'],
            ['value' => $request->input('announcement')]
        );

        // 3. Simpan Data Pejabat (Nama & NIP)
        Setting::updateOrCreate(['key' => 'pejabat_name'], ['value' => $request->input('pejabat_name')]);
        Setting::updateOrCreate(['key' => 'pejabat_nip'], ['value' => $request->input('pejabat_nip')]);
        Setting::updateOrCreate(['key' => 'pejabat_jabatan'], ['value' => $request->input('pejabat_jabatan')]);

        // 4. Handle Upload Tanda Tangan
        if ($request->hasFile('ttd_image')) {
            // Hapus file lama jika ada
            $oldImage = Setting::where('key', 'ttd_image')->value('value');
            if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }

            // Simpan file baru
            $path = $request->file('ttd_image')->store('settings', 'public');
            
            Setting::updateOrCreate(
                ['key' => 'ttd_image'],
                ['value' => $path]
            );
        }

        return back()->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }

    public function backupDatabase()
    {
        try {
            $filename = 'backup_' . env('DB_DATABASE') . '_' . date('Y-m-d_H-i-s') . '.sql';
            $path = storage_path('app/public/backups/');
            
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            
            $dump = new \Ifsnop\Mysqldump\Mysqldump('mysql:host=' . env('DB_HOST') . ';dbname=' . env('DB_DATABASE'), env('DB_USERNAME'), env('DB_PASSWORD'));
            $dump->start($path . $filename);
            
            return response()->download($path . $filename)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal melakukan backup database: ' . $e->getMessage());
        }
    }
}