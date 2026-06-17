<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;

class RolePesertaTest extends TestCase
{
    use DatabaseTransactions;

    public function test_peserta_can_access_their_dashboard()
    {
        $user = User::factory()->create(['role' => 'peserta']);

        $response = $this->actingAs($user)->get(route('peserta.dashboard'));
        $response->assertStatus(200);
    }

    public function test_peserta_cannot_access_admin_dashboard()
    {
        $user = User::factory()->create(['role' => 'peserta']);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));
        // Usually redirects or 403
        $response->assertStatus(403);
    }
}
