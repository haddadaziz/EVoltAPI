<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StationController;

Route::post('/register', [AuthController::class , 'register']);
Route::post('/login', [AuthController::class , 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
            return $request->user();
        }
        );

        Route::post('/logout', [AuthController::class , 'logout']);

        // Stations
        Route::get('/stations', [StationController::class , 'index']);
        Route::get('/stations/{station}', [StationController::class , 'show']);

        // Admin Stations
        Route::get('/admin/stats', [App\Http\Controllers\AdminStatsController::class , 'index']);
        Route::post('/admin/stations', [StationController::class , 'store']);
        Route::put('/admin/stations/{station}', [StationController::class , 'update']);
        Route::delete('/admin/stations/{station}', [StationController::class , 'destroy']);

        // Reservations
        Route::get('/reservations', [App\Http\Controllers\ReservationController::class , 'index']);
        Route::post('/reservations', [App\Http\Controllers\ReservationController::class , 'store']);
        Route::get('/reservations/{reservation}', [App\Http\Controllers\ReservationController::class , 'show']);
        Route::put('/reservations/{reservation}', [App\Http\Controllers\ReservationController::class , 'update']);
        Route::delete('/reservations/{reservation}', [App\Http\Controllers\ReservationController::class , 'destroy']);
    });
