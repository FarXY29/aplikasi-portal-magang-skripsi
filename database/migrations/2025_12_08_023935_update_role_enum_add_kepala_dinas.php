<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Ubah kolom role agar menerima 'kepala_dinas'
        // Kita gunakan raw SQL karena mengubah ENUM di Laravel kadang butuh doctrine/dbal
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin_kota', 'admin_instansi', 'pembimbing_lapangan', 'peserta', 'pembimbing', 'kepala_dinas') NOT NULL DEFAULT 'peserta'");
    }

    public function down()
    {
        // Kembalikan ke enum lama (opsional)
    }
};
