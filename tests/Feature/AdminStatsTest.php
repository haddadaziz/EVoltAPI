<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminStatsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_stats()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin, 'sanctum')->getJson('/api/admin/stats');
        $response->assertStatus(200)->assertJsonStructure(['total_stations', 'occupancy_rate_percentage']);
    }

    public function test_user_cannot_view_stats()
    {
        $user = User::factory()->create(['role' => 'user']);
        $response = $this->actingAs($user, 'sanctum')->getJson('/api/admin/stats');
        $response->assertStatus(403);
    }
}
