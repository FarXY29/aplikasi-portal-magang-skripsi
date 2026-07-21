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
}
