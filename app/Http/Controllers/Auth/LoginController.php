<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }


    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        // Add domain to the email input
        $emailPrefix = $request->input('email');
        $email = $emailPrefix . '@raisevest.com.my';
        $password = $request->input('password');

        // Attempt to log in the user using Laravel's Auth facade
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            // Login successful, regenerate session ID to prevent session fixation
            $request->session()->regenerate();

            // Redirect to the intended page or home
            return redirect()->intended('home');
        }

        // Authentication failed, redirect back with an error message
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // Log the user out
        Auth::logout();

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate the session ID
        $request->session()->regenerateToken();

        // Redirect to the login page or home
        return redirect()->route('auth.login')->with('status', 'You have been logged out successfully.');
    }
}
