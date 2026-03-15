<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Station;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_list_stations()
    {
        $user = User::factory()->create(['role' => 'user']);
        $response = $this->actingAs($user, 'sanctum')->getJson('/api/stations');
        $response->assertStatus(200);
    }

    public function test_admin_can_create_station()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin, 'sanctum')->postJson('/api/admin/stations', [
            'name' => 'Test Station',
            'latitude' => 45.0,
            'longitude' => 4.0,
            'connector_type' => 'Type 2',
            'power_kw' => 50,
        ]);
        $response->assertStatus(201);
    }
}
