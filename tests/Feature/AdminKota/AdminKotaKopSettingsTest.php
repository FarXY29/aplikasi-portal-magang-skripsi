<?php

namespace Tests\Feature\AdminKota;

use App\Models\Setting;
use App\Models\User;
use App\Services\PdfExportService;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminKotaKopSettingsTest extends TestCase
{
    use DatabaseTransactions;

    protected User $adminKota;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);

        $this->adminKota = User::factory()->create([
            'role' => 'admin_kota',
        ]);
        $this->adminKota->assignRole('admin_kota');
    }

    public function test_admin_kota_can_view_kop_settings_form(): void
    {
        $response = $this->actingAs($this->adminKota)->get(route('admin.settings.index'));

        $response->assertOk();
        $response->assertSee('Kop Dokumen Laporan');
        $response->assertSee('kop_line1');
        $response->assertSee('kop_line2');
        $response->assertSee('kop_line3');
        $response->assertSee('kop_logo');
        $response->assertSee('Live Preview Kop Surat');
    }

    public function test_admin_kota_can_update_kop_text_settings(): void
    {
        $payload = [
            'app_name' => 'SiMagang Test',
            'announcement' => 'Pengumuman Test',
            'pejabat_name' => 'Dr. Test Nama',
            'pejabat_nip' => '198001012005011001',
            'pejabat_jabatan' => 'Kepala Dinas Test',
            'kop_line1' => 'PEMERINTAH KOTA BANJARMASIN UPDATE',
            'kop_line2' => 'DINAS KOMUNIKASI DAN INFORMATIKA',
            'kop_line3' => 'Jl. Pangeran Samudera No. 45, Banjarmasin',
        ];

        $response = $this->actingAs($this->adminKota)->post(route('admin.settings.update'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('PEMERINTAH KOTA BANJARMASIN UPDATE', Setting::where('key', 'kop_line1')->value('value'));
        $this->assertEquals('DINAS KOMUNIKASI DAN INFORMATIKA', Setting::where('key', 'kop_line2')->value('value'));
        $this->assertEquals('Jl. Pangeran Samudera No. 45, Banjarmasin', Setting::where('key', 'kop_line3')->value('value'));
    }

    public function test_admin_kota_can_upload_custom_kop_logo(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('custom_logo.png', 300, 300);

        $response = $this->actingAs($this->adminKota)->post(route('admin.settings.update'), [
            'kop_line1' => 'PEMERINTAH KOTA BANJARMASIN',
            'kop_line2' => 'DINAS KOMUNIKASI DAN INFORMATIKA',
            'kop_line3' => 'Jl. Pangeran Samudera No. 45',
            'kop_logo' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $storedPath = Setting::where('key', 'kop_logo')->value('value');
        $this->assertNotNull($storedPath);
        Storage::disk('public')->assertExists($storedPath);
    }

    public function test_pdf_export_service_injects_custom_kop_data(): void
    {
        Setting::updateOrCreate(['key' => 'kop_line1'], ['value' => 'PEMERINTAH KOTA BANJARMASIN CUSTOM']);
        Setting::updateOrCreate(['key' => 'kop_line2'], ['value' => 'BADAN PERENCANAAN PEMBANGUNAN DAERAH']);
        Setting::updateOrCreate(['key' => 'kop_line3'], ['value' => 'Jl. RE Martadinata No. 2']);

        $service = app(PdfExportService::class);
        $data = $service->injectKopData([]);

        $this->assertEquals('PEMERINTAH KOTA BANJARMASIN CUSTOM', $data['kop_line1']);
        $this->assertEquals('BADAN PERENCANAAN PEMBANGUNAN DAERAH', $data['kop_line2']);
        $this->assertEquals('Jl. RE Martadinata No. 2', $data['kop_line3']);
        $this->assertArrayHasKey('kop_logo_path', $data);
    }

    public function test_admin_kota_pdf_reports_render_successfully(): void
    {
        Setting::updateOrCreate(['key' => 'kop_line1'], ['value' => 'PEMERINTAH KOTA BANJARMASIN KHUSUS']);
        Setting::updateOrCreate(['key' => 'kop_line2'], ['value' => 'DINAS PENDIDIKAN DAN KEBUDAYAAN']);
        Setting::updateOrCreate(['key' => 'kop_line3'], ['value' => 'Jl. Pahlawan No. 10']);

        $routes = [
            'admin.laporan.print',
            'admin.instansi.print_pdf',
            'admin.laporan.peserta_global.print',
            'admin.laporan.grading.print',
            'admin.laporan.instansi_disiplin.print',
            'admin.laporan.durasi_magang.print',
            'admin.laporan.demografi_jurusan.print',
            'admin.laporan.penyerapan_kuota.print',
            'admin.certificates.export_pdf',
        ];

        foreach ($routes as $routeName) {
            $response = $this->actingAs($this->adminKota)->get(route($routeName));
            $response->assertOk();
            $this->assertEquals('application/pdf', $response->headers->get('content-type'), "Failed on route: {$routeName}");
        }
    }
}
