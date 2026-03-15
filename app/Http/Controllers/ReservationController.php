<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Station;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        return response()->json($request->user()->reservations()->with('station')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'station_id' => 'required|exists:stations,id',
            'start_time' => 'required|date|after_or_equal:now',
            'duration_minutes' => 'required|integer|min:15',
        ]);

        $station = Station::findOrFail($validated['station_id']);

        if ($station->status !== 'available') {
            return response()->json(['message' => 'Station is not available'], 400);
        }

        $startTime = Carbon::parse($validated['start_time']);
        $endTime = $startTime->copy()->addMinutes((int)$validated['duration_minutes']);

        // Check for overlaps
        $conflict = Reservation::where('station_id', $station->id)
            ->where('status', 'active')
            ->where(function ($query) use ($startTime, $endTime) {
            // A logic simpler to check overlap: 
            // new_start < existing_end AND new_end > existing_start
            $query->where('start_time', '<', $endTime)
                ->where('end_time', '>', $startTime);
        })->exists();

        if ($conflict) {
            return response()->json(['message' => 'Time slot is already booked for this station'], 409);
        }

        $reservation = Reservation::create([
            'user_id' => $request->user()->id,
            'station_id' => $station->id,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => 'active',
        ]);

        return response()->json($reservation, 201);
    }

    public function show(Request $request, Reservation $reservation)
    {
        if ($reservation->user_id !== $request->user()->id && $request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return response()->json($reservation->load('station'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        if ($reservation->user_id !== $request->user()->id && $request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($reservation->status !== 'active') {
            return response()->json(['message' => 'Only active reservations can be modified'], 400);
        }

        $validated = $request->validate([
            'start_time' => 'sometimes|date|after_or_equal:now',
            'duration_minutes' => 'sometimes|integer|min:15',
        ]);

        $startTime = isset($validated['start_time']) ?Carbon::parse($validated['start_time']) : $reservation->start_time;
        if (isset($validated['duration_minutes'])) {
            $endTime = $startTime->copy()->addMinutes((int)$validated['duration_minutes']);
        }
        else {
            $endTime = $startTime->copy()->addMinutes($reservation->start_time->diffInMinutes($reservation->end_time));
        }

        // Check conflicts again, excluding current reservation
        $conflict = Reservation::where('station_id', $reservation->station_id)
            ->where('id', '!=', $reservation->id)
            ->where('status', 'active')
            ->where(function ($query) use ($startTime, $endTime) {
            $query->where('start_time', '<', $endTime)
                ->where('end_time', '>', $startTime);
        })->exists();

        if ($conflict) {
            return response()->json(['message' => 'Time slot is already booked for this station'], 409);
        }

        $reservation->update([
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);

        return response()->json($reservation);
    }

    public function destroy(Request $request, Reservation $reservation)
    {
        if ($reservation->user_id !== $request->user()->id && $request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $reservation->update(['status' => 'cancelled']);

        return response()->json(['message' => 'Reservation cancelled successfully']);
    }
}
