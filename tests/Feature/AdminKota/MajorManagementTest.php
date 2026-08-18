<?php

namespace Tests\Feature\AdminKota;

use App\Models\Major;
use App\Models\MajorCategory;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class MajorManagementTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_guest_and_non_admin_cannot_access_majors_management(): void
    {
        $response = $this->get(route('admin.master.majors.index'));
        $response->assertRedirect(route('login'));

        $peserta = User::factory()->create(['role' => 'peserta']);
        $response = $this->actingAs($peserta)->get(route('admin.master.majors.index'));
        $response->assertStatus(403);
    }

    public function test_admin_kota_can_view_majors_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin_kota']);

        $response = $this->actingAs($admin)->get(route('admin.master.majors.index'));
        $response->assertStatus(200);
        $response->assertSeeText('Master Program Studi & Jurusan');
    }

    public function test_admin_kota_can_create_major_category(): void
    {
        $admin = User::factory()->create(['role' => 'admin_kota']);

        $response = $this->actingAs($admin)->post(route('admin.master.major-categories.store'), [
            'name' => 'Kategori Uji Keilmuan Baru',
            'code' => 'UJI_CAT',
            'description' => 'Deskripsi pengujian rumpun ilmu baru.',
        ]);

        $response->assertRedirect(route('admin.master.major-categories.index'));
        $this->assertDatabaseHas('major_categories', [
            'code' => 'UJI_CAT',
            'name' => 'Kategori Uji Keilmuan Baru',
        ]);
    }

    public function test_admin_kota_can_create_major_category_via_json(): void
    {
        $admin = User::factory()->create(['role' => 'admin_kota']);

        $response = $this->actingAs($admin)->postJson(route('admin.master.major-categories.store'), [
            'name' => 'Rumpun Manual Modal Cepat',
            'code' => 'MANUAL_MODAL',
            'description' => 'Rumpun dibuat via modal interaktif.',
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'category' => [
                'code' => 'MANUAL_MODAL',
                'name' => 'Rumpun Manual Modal Cepat',
            ],
        ]);

        $this->assertDatabaseHas('major_categories', [
            'code' => 'MANUAL_MODAL',
            'name' => 'Rumpun Manual Modal Cepat',
        ]);
    }

    public function test_admin_kota_can_create_major(): void
    {
        $admin = User::factory()->create(['role' => 'admin_kota']);

        $category = MajorCategory::create([
            'name' => 'Rumpun Komputer Uji',
            'code' => 'KOMP_UJI',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.master.majors.store'), [
            'major_category_id' => $category->id,
            'name' => 'Kecerdasan Buatan & Robotika',
            'degree_level' => 'S1',
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.master.majors.index'));
        $this->assertDatabaseHas('majors', [
            'name' => 'Kecerdasan Buatan & Robotika',
            'degree_level' => 'S1',
            'major_category_id' => $category->id,
        ]);
    }

    public function test_admin_kota_can_toggle_major_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin_kota']);

        $category = MajorCategory::first() ?? MajorCategory::create([
            'name' => 'Rumpun Uji Toggle',
            'code' => 'TOGGLE_CAT',
        ]);

        $major = Major::create([
            'major_category_id' => $category->id,
            'name' => 'Jurusan Uji Toggle',
            'degree_level' => 'D3',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.master.majors.toggle', $major->id));
        $response->assertSessionHas('success');

        $major->refresh();
        $this->assertFalse($major->is_active);
    }
}
