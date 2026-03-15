<?php

namespace App\Http\Controllers;

use App\Models\Station;
use Illuminate\Http\Request;

class StationController extends Controller
{
    public function index(Request $request)
    {
        $query = Station::query();

        // Optional filtering by availability
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filtering by connector type
        if ($request->has('connector_type')) {
            $query->where('connector_type', $request->connector_type);
        }

        // Filtering by minimum power
        if ($request->has('min_power_kw')) {
            $query->where('power_kw', '>=', $request->min_power_kw);
        }

        // Geography sorting/filtering could be added here later

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized access'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'connector_type' => 'required|string',
            'power_kw' => 'required|numeric',
        ]);

        $station = Station::create(array_merge($validated, ['status' => 'available']));

        return response()->json($station, 201);
    }

    public function show(Station $station)
    {
        return response()->json($station);
    }

    public function update(Request $request, Station $station)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized access'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string',
            'latitude' => 'sometimes|numeric',
            'longitude' => 'sometimes|numeric',
            'connector_type' => 'sometimes|string',
            'power_kw' => 'sometimes|numeric',
            'status' => 'sometimes|string|in:available,reserved,out_of_service',
        ]);

        $station->update($validated);

        return response()->json($station);
    }

    public function destroy(Request $request, Station $station)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized access'], 403);
        }

        $station->delete();

        return response()->json(['message' => 'Station deleted successfully']);
    }
}
