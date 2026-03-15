<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;

class AdminStatsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $totalStations = Station::count();
        $availableStations = Station::where('status', 'available')->count();
        $totalReservations = Reservation::count();
        $activeReservations = Reservation::where('status', 'active')->count();
        $totalUsers = User::where('role', 'user')->count();

        $occupancyRate = $totalStations > 0 ? round(($activeReservations / $totalStations) * 100, 2) : 0;

        return response()->json([
            'total_stations' => $totalStations,
            'available_stations' => $availableStations,
            'occupancy_rate_percentage' => $occupancyRate,
            'total_reservations' => $totalReservations,
            'active_reservations' => $activeReservations,
            'total_users' => $totalUsers,
        ]);
    }
}
