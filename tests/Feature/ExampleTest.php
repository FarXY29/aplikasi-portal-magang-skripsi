<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_landing_page_has_semantic_elements_and_modern_fonts(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('fonts.googleapis.com/css2?family=Plus+Jakarta+Sans');
        $response->assertSee('family=Outfit');
        $response->assertSee('<body', false);
    }

    public function test_landing_page_has_redesigned_navbar_and_hero(): void
    {
        $response = $this->get('/');
        $response->assertSee('SiMagang');
        $response->assertSee('Cari Posisi');
        $response->assertSee('search-form', false);
    }

    public function test_landing_page_has_bento_stats_and_smart_allocation(): void
    {
        $response = $this->get('/');
        $response->assertSee('Instansi Pemerintah');
        $response->assertSee('Posisi Aktif');
        $response->assertSee('Alumni Magang');
    }

    public function test_landing_page_has_alur_magang_steps(): void
    {
        $response = $this->get('/');
        $response->assertSee('Buat Akun');
        $response->assertSee('Pilih Lowongan');
        $response->assertSee('Slot Periode');
        $response->assertSee('Mulai Magang');
    }

    public function test_landing_page_has_vacancy_filters_and_interactive_drawer(): void
    {
        $response = $this->get('/');
        $response->assertSee('lowongan');
        $response->assertSee('instansi_id');
        $response->assertSee('jurusan');
        $response->assertSee('filter-form', false);
    }

    public function test_landing_page_has_faq_and_footer_redesign(): void
    {
        $response = $this->get('/');
        $response->assertSee('Pertanyaan Populer');
        $response->assertSee('SiMagang Kota Banjarmasin');
        $response->assertSee('diskominfotik.banjarmasinkota.go.id');
    }
}
