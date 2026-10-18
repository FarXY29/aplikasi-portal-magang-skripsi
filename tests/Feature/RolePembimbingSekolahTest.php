<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RolePembimbingSekolahTest extends TestCase
{
    use DatabaseTransactions;

    public function test_pembimbing_sekolah_can_access_dashboard()
    {
        $user = User::factory()->create(['role' => 'pembimbing']);

        $response = $this->actingAs($user)->get(route('pembimbing.dashboard'));
        $response->assertStatus(200);
    }
}
