<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\ParkingController;
use App\Http\Controllers\Api\RequestPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Login Interaction
Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::middleware('auth:sanctum')->post('/logout', [LoginController::class, 'logout']);


    Route::post('/set-password', [RequestPasswordController::class, 'set'])->name('set-password');
});

Route::middleware('auth:sanctum')->group(function () {
    // Parking
    Route::prefix('parking')->name('parking.')->group(function () {
        Route::get('/', [ParkingController::class, 'index'])->name('parking_public');
    });
});
