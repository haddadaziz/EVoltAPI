<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Station;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_reserve_station()
    {
        $user = User::factory()->create(['role' => 'user']);
        $station = Station::create(['name' => 'St1', 'latitude' => 0, 'longitude' => 0, 'connector_type' => 'CHAdeMO', 'power_kw' => 50, 'status' => 'available']);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/reservations', [
            'station_id' => $station->id,
            'start_time' => Carbon::now()->addMinutes(10)->toDateTimeString(),
            'duration_minutes' => 30,
        ]);

        $response->assertStatus(201)->assertJsonPath('status', 'active');
    }
}
