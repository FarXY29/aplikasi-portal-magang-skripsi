<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('instansis', function (Blueprint $table) {
            $table->string('email', 191)->nullable()->after('contact_whatsapp');
            $table->string('singkatan', 50)->nullable()->after('nama_dinas');
        });

        // Inisialisasi singkatan default untuk instansi yang sudah ada
        $instansis = DB::table('instansis')->get();
        foreach ($instansis as $instansi) {
            $nama = trim($instansi->nama_dinas ?? '');
            $singkatan = null;

            if (stripos($nama, 'komunikasi') !== false || stripos($nama, 'diskominfotik') !== false) {
                $singkatan = 'DISKOMINFOTIK';
            } elseif (stripos($nama, 'kesehatan') !== false || stripos($nama, 'dinkes') !== false) {
                $singkatan = 'DINKES';
            } elseif (stripos($nama, 'pendidikan') !== false || stripos($nama, 'disdik') !== false) {
                $singkatan = 'DISDIK';
            } elseif (stripos($nama, 'kesatuan bangsa') !== false || stripos($nama, 'bakesbangpol') !== false) {
                $singkatan = 'BAKESBANGPOL';
            } elseif (stripos($nama, 'sosial') !== false || stripos($nama, 'dinsos') !== false) {
                $singkatan = 'DINSOS';
            } elseif (stripos($nama, 'pekerjaan umum') !== false || stripos($nama, 'pupr') !== false) {
                $singkatan = 'PUPR';
            } elseif (stripos($nama, 'perhubungan') !== false || stripos($nama, 'dishub') !== false) {
                $singkatan = 'DISHUB';
            } elseif (stripos($nama, 'lingkungan hidup') !== false || stripos($nama, 'dlh') !== false) {
                $singkatan = 'DLH';
            } elseif (stripos($nama, 'pendapatan') !== false || stripos($nama, 'bapenda') !== false) {
                $singkatan = 'BAPENDA';
            } elseif (stripos($nama, 'kepegawaian') !== false || stripos($nama, 'bkd') !== false) {
                $singkatan = 'BKD';
            } elseif (!empty($nama)) {
                // Buat akronim dari huruf depan setiap kata bermakna
                $words = preg_split('/\s+/', preg_replace('/[^a-zA-Z0-9\s]/', '', $nama));
                $acronym = '';
                foreach ($words as $w) {
                    if (strlen($w) > 0 && !in_array(strtolower($w), ['dan', 'di', 'ke', 'dari', 'kota', 'pemerintah'])) {
                        $acronym .= strtoupper($w[0]);
                    }
                }
                $singkatan = !empty($acronym) ? substr($acronym, 0, 15) : 'SKPD';
            }

            if ($singkatan) {
                DB::table('instansis')->where('id', $instansi->id)->update(['singkatan' => $singkatan]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('instansis', function (Blueprint $table) {
            $table->dropColumn(['email', 'singkatan']);
        });
    }
};
