<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RequestPasswordController;
use App\Http\Controllers\CompoundController;
use App\Http\Controllers\MonthlyPassController;
use App\Http\Controllers\ParkingController;
use App\Http\Controllers\PromotionController;
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

        Route::get('/parking/export-excel', [ParkingController::class, 'exportExcel'])->name('parking.export_excel');
        Route::get('/parking/export-pdf', [ParkingController::class, 'exportPDF'])->name('parking.export_pdf');
    });

    // Monthly Pass
    Route::prefix('monthlyPass')->name('monthlyPass.')->group(function () {
        Route::get('/', [MonthlyPassController::class, 'index'])->name('monthly_pass_public');
        Route::get('/create', [MonthlyPassController::class, 'createMonthlyPass'])->name('monthly_pass_create');
        Route::post('/store', [MonthlyPassController::class, 'store'])->name('monthly_pass_store');
        Route::get('/export-excel', [MonthlyPassController::class, 'exportExcel'])->name('monthlyPass.export_excel');
        Route::get('/export-pdf', [MonthlyPassController::class, 'exportPDF'])->name('monthlyPass.export_pdf');
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
        Route::get('/view/{id}', [ReserveBayController::class, 'view'])->name(name: 'reserve_bay_view');
        Route::get('/create', [ReserveBayController::class, 'create'])->name('reserve_bay_create');
        Route::post('/store', [ReserveBayController::class, 'store'])->name('reserve_bay_store');
        Route::get('/export-excel', [ReserveBayController::class, 'exportExcel'])->name('reserveBay.export_excel');
        Route::get('/export-pdf', [ReserveBayController::class, 'exportPDF'])->name('reserveBay.export_pdf');
        Route::get('/{id}', [ReserveBayController::class, 'edit'])->name(name: 'reserve_bay_edit');
        Route::put('/update/{id}', [ReserveBayController::class, 'update'])->name(name: 'reserve_bay_update');
        Route::delete('/delete/{id}', [ReserveBayController::class, 'destroy'])->name(name: 'reserve_bay_delete');

        // Approve or Reject from Dashboard
        Route::put('/update/status/approve/{id}', [ReserveBayController::class, 'updateStatusApprove'])->name(name: 'reserve_bay_update_status_approve');
        Route::put('/update/status/reject/{id}', [ReserveBayController::class, 'updateStatusReject'])->name(name: 'reserve_bay_update_status_reject');

        // Approve or Reject from View
        Route::put('/update/status/approve/view/{id}', [ReserveBayController::class, 'updateStatusApproveView'])->name(name: 'reserve_bay_update_status_approve_view');
        Route::put('/update/status/reject/view/{id}', [ReserveBayController::class, 'updateStatusRejectView'])->name(name: 'reserve_bay_update_status_reject_view');
    });

    // Promotion
    Route::prefix('promotion')->name('promotion.')->group(function () {
        Route::get('/monthly-pass', [PromotionController::class, 'index'])->name(name: 'promotion.monthly_pass');
        Route::get('/view/{id}', [PromotionController::class, 'view'])->name(name: 'promotion.monthly_pass_view');
        Route::get('/create', [PromotionController::class, 'create'])->name('promotion.monthly_pass_create');
        Route::post('/store', [PromotionController::class, 'store'])->name('promotion.monthly_pass_store');
        Route::get('/{id}', [PromotionController::class, 'edit'])->name(name: 'promotion.monthly_pass_edit');
        Route::put('/update/{id}', [PromotionController::class, 'update'])->name(name: 'promotion.monthly_pass_update');
        Route::delete('/delete/{id}', [PromotionController::class, 'destroy'])->name(name: 'promotion.monthly_pass_delete');
    });

    // Compound
    Route::prefix('compound')->name('compound.')->group(function () {
        Route::get('/', [CompoundController::class, 'index'])->name('compound_public');
        Route::get('/view/{id}', [CompoundController::class, 'view'])->name('compound_public_view');

        Route::get('/parking/export-excel', [CompoundController::class, 'exportExcel'])->name('compound.export_excel');
        Route::get('/parking/export-pdf', [CompoundController::class, 'exportPDF'])->name('compound.export_pdf');
    });

    // Activity Log
    Route::prefix('activityLog')->name('activityLog.')->group(function () {
        Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('index');
    });
});
