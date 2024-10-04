<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RequestPasswordController;
use App\Http\Controllers\MonthlyPassController;
use App\Http\Controllers\ParkingController;
use App\Http\Controllers\ReserveBayController;
use App\Http\Controllers\SettingController;
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

    // Monthly Pass
    Route::prefix('monthlyPass')->name('monthlyPass.')->group(function () {
        Route::get('/', [MonthlyPassController::class, 'index'])->name('monthly_pass_public');
        Route::get('/create', [MonthlyPassController::class, 'createMonthlyPass'])->name('monthly_pass_create');
        Route::post('/store', [MonthlyPassController::class, 'store'])->name('monthly_pass_store');
        Route::get('/{id}', [MonthlyPassController::class, 'edit'])->name(name: 'monthly_pass_edit');
        Route::put('/update/{id}', [MonthlyPassController::class, 'update'])->name(name: 'monthly_pass_update');
        Route::delete('/delete/{id}', [MonthlyPassController::class, 'destroy'])->name(name: 'monthly_pass_delete');
    });

    // Settings
    Route::prefix('setting')->name('setting.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name(name: 'setting');
        Route::post('/onboardingKey', [SettingController::class, 'generateKey'])->name('setting_pegeypay');
        Route::post('/refreshAccessCode', [SettingController::class, 'refreshAccessCode'])->name('setting_pegeypay_refresh');
        Route::post('/refreshFPX', [SettingController::class, 'refreshFPX'])->name('setting_fpx_refresh');
        Route::put('/change-password', [SettingController::class, 'changePassword'])->name('change_password');
    });

    // Reserve Bay
    Route::prefix('reserveBay')->name('reserveBay.')->group(function () {
        Route::get('/', [ReserveBayController::class, 'index'])->name(name: 'reserve_bay');
        Route::get('/create', [ReserveBayController::class, 'create'])->name('reserve_bay_create');
        Route::post('/store', [ReserveBayController::class, 'store'])->name('reserve_bay_store');
        Route::get('/{id}', [ReserveBayController::class, 'edit'])->name(name: 'reserve_bay_edit');
        Route::put('/update/{id}', [ReserveBayController::class, 'update'])->name(name: 'reserve_bay_update');
        Route::delete('/delete/{id}', [ReserveBayController::class, 'destroy'])->name(name: 'reserve_bay_delete');

        Route::put('/update/status/approve/{id}', [ReserveBayController::class, 'updateStatusApprove'])->name(name: 'reserve_bay_update_status_approve');
        Route::put('/update/status/reject/{id}', [ReserveBayController::class, 'updateStatusReject'])->name(name: 'reserve_bay_update_status_reject');
    });

    // Activity Log
    Route::prefix('activityLog')->name('activityLog.')->group(function () {
        Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('index');
    });
});
