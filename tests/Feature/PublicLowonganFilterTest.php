<?php

namespace Tests\Feature;

use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\MajorCategory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PublicLowonganFilterTest extends TestCase
{
    use DatabaseTransactions;

    public function test_landing_page_can_filter_lowongan_by_instansi(): void
    {
        $dinasA = Instansi::create([
            'nama_dinas' => 'Dinas Komunikasi dan Informatika Test',
            'alamat' => 'Jl. A No. 1',
            'kode_unit_kerja' => 'DISKOMINFO-FLT',
        ]);

        $dinasB = Instansi::create([
            'nama_dinas' => 'Dinas Pendidikan Test',
            'alamat' => 'Jl. B No. 2',
            'kode_unit_kerja' => 'DISDIK-FLT',
        ]);

        $lokerA = InternshipPosition::create([
            'instansi_id' => $dinasA->id,
            'judul_posisi' => 'Web Developer Diskominfo',
            'required_major' => 'Teknik Informatika',
            'kuota' => 2,
            'status' => 'buka',
        ]);

        $lokerB = InternshipPosition::create([
            'instansi_id' => $dinasB->id,
            'judul_posisi' => 'Staff Edukasi Disdik',
            'required_major' => 'Pendidikan',
            'kuota' => 2,
            'status' => 'buka',
        ]);

        $response = $this->get(route('home', ['instansi_id' => $dinasA->id]));
        $response->assertOk();
        $response->assertSee('Web Developer Diskominfo');
        $response->assertDontSee('Staff Edukasi Disdik');
    }

    public function test_landing_page_can_filter_lowongan_by_major_category(): void
    {
        $dinas = Instansi::create([
            'nama_dinas' => 'Dinas Pekerjaan Umum Test',
            'alamat' => 'Jl. PU No. 10',
            'kode_unit_kerja' => 'DPU-FLT',
        ]);

        $catIT = MajorCategory::create([
            'code' => 'TIK',
            'name' => 'Teknologi Informasi & Komputer',
        ]);

        $catEkbis = MajorCategory::create([
            'code' => 'EKBIS',
            'name' => 'Ekonomi, Bisnis & Manajemen',
        ]);

        $lokerIT = InternshipPosition::create([
            'instansi_id' => $dinas->id,
            'judul_posisi' => 'Programmer GIS DPU',
            'required_major_category_id' => $catIT->id,
            'required_major' => 'S1 Komputer / Informatika',
            'kuota' => 2,
            'status' => 'buka',
        ]);

        $lokerEkbis = InternshipPosition::create([
            'instansi_id' => $dinas->id,
            'judul_posisi' => 'Verifikator Anggaran DPU',
            'required_major_category_id' => $catEkbis->id,
            'required_major' => 'S1 Akuntansi',
            'kuota' => 2,
            'status' => 'buka',
        ]);

        $response = $this->get(route('home', ['major_category_id' => $catIT->id]));
        $response->assertOk();
        $response->assertSee('Programmer GIS DPU');
        $response->assertDontSee('Verifikator Anggaran DPU');
    }

    public function test_landing_page_can_filter_lowongan_by_jurusan_keyword(): void
    {
        $dinas = Instansi::create([
            'nama_dinas' => 'Bappeda Test',
            'alamat' => 'Jl. Perencanaan No. 5',
            'kode_unit_kerja' => 'BAPPEDA-FLT',
        ]);

        $lokerIT = InternshipPosition::create([
            'instansi_id' => $dinas->id,
            'judul_posisi' => 'Analis Data TI',
            'required_major' => 'S1 Komputer / Informatika',
            'kuota' => 2,
            'status' => 'buka',
        ]);

        $lokerHukum = InternshipPosition::create([
            'instansi_id' => $dinas->id,
            'judul_posisi' => 'Staff Regulasi Kebijakan',
            'required_major' => 'S1 Ilmu Hukum',
            'kuota' => 2,
            'status' => 'buka',
        ]);

        $response = $this->get(route('home', ['jurusan' => 'Informatika']));
        $response->assertOk();
        $response->assertSee('Analis Data TI');
        $response->assertDontSee('Staff Regulasi Kebijakan');
    }

    public function test_landing_page_can_search_by_keyword(): void
    {
        $dinas = Instansi::create([
            'nama_dinas' => 'Inspektorat Kota Banjarmasin Test',
            'alamat' => 'Jl. Pengawasan No. 7',
            'kode_unit_kerja' => 'INSP-FLT',
        ]);

        $lokerAuditor = InternshipPosition::create([
            'instansi_id' => $dinas->id,
            'judul_posisi' => 'Asisten Auditor Internal',
            'required_major' => 'Akuntansi / Manajemen',
            'deskripsi' => 'Membantu proses audit kepatuhan keuangan daerah',
            'kuota' => 2,
            'status' => 'buka',
        ]);

        $response = $this->get(route('home', ['search' => 'Auditor']));
        $response->assertOk();
        $response->assertSee('Asisten Auditor Internal');
    }

    public function test_landing_page_renders_lowongan_popup_modal_structure(): void
    {
        $dinas = Instansi::create([
            'nama_dinas' => 'Dinas Perhubungan Test',
            'alamat' => 'Jl. Transport No. 99',
            'kode_unit_kerja' => 'DISHUB-FLT',
        ]);

        $loker = InternshipPosition::create([
            'instansi_id' => $dinas->id,
            'judul_posisi' => 'Staff Pengatur Lalu Lintas',
            'required_major' => 'Transportasi Darat',
            'deskripsi' => 'Pengawasan marka dan rambu jalan kota',
            'kuota' => 3,
            'status' => 'buka',
        ]);

        $response = $this->get(route('home'));
        $response->assertOk();
        $response->assertSee('Staff Pengatur Lalu Lintas');
        $response->assertSee('x-teleport="body"', false);
        $response->assertSee('Detail Lowongan Magang');
    }

    public function test_guest_can_view_lowongan_show_page(): void
    {
        $dinas = Instansi::create([
            'nama_dinas' => 'Dinas Kesehatan Test',
            'alamat' => 'Jl. Sehat No. 10',
            'kode_unit_kerja' => 'DINKES-FLT',
        ]);

        $loker = InternshipPosition::create([
            'instansi_id' => $dinas->id,
            'judul_posisi' => 'Asisten Apoteker Magang',
            'required_major' => 'Farmasi',
            'deskripsi' => 'Pengelolaan obat di instalasi farmasi',
            'kuota' => 2,
            'status' => 'buka',
        ]);

        $response = $this->get(route('lowongan.show', $loker->id));
        $response->assertOk();
        $response->assertSee('Asisten Apoteker Magang');
        $response->assertSee('Dinas Kesehatan Test');
        $response->assertSee('Farmasi');
    }
}
