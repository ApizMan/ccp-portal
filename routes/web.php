<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RequestPasswordController;
use App\Http\Controllers\ParkingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/home');
    }
    return redirect('/auth/login');
});


// Login Interaction
Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/login', function () {
        if (Auth::check()) {
            return redirect('/home');
        }
        return view('auth.login'); // Your login view
    })->name('login');

    Route::post('/login', function (Request $request) {
        if (Auth::check()) {
            return redirect('/home');
        }
        return app(LoginController::class)->login($request);
    })->name('login.post');

    Route::get('/request-password', [RequestPasswordController::class, 'index'])->name('request-password');
    Route::post('/set-password', [RequestPasswordController::class, 'set'])->name('set-password');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', function () {
        return view('home.dashboard');
    })->name('home');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    // Parking
    Route::prefix('parking')->name('parking.')->group(function () {
        Route::get('/', [ParkingController::class, 'index'])->name('parking_public');
        Route::get('/{id}', [ParkingController::class, 'edit'])->name(name: 'parking_edit');
        Route::put('/update/{id}/{transactionId}', [ParkingController::class, 'update'])->name(name: 'parking_update');
        Route::delete('/delete/{id}', [ParkingController::class, 'destroy'])->name(name: 'parking_delete');
    });
});
