<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RequestPasswordController;
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
});
