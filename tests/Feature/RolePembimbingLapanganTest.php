<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;

class RolePembimbingLapanganTest extends TestCase
{
    use DatabaseTransactions;

    public function test_pembimbing_lapangan_can_access_dashboard()
    {
        $user = User::factory()->create(['role' => 'pembimbing_lapangan']);

        $response = $this->actingAs($user)->get(route('pembimbing_lapangan.dashboard'));
        $response->assertStatus(200);
    }
}
