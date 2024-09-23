<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str; // Add this line

class RequestPasswordController extends Controller
{
    public function index()
    {
        return view('auth.request-password');
    }

    public function set(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|string',
        ]);

        // Get the email prefix and append the domain
        $emailPrefix = $request->input('email');
        $email = $emailPrefix . '@raisevest.com.my';

        // Generate a random password
        $randomPassword = Str::random(10); // You can adjust the length as needed

        // Store user credentials in the database
        $user = DB::table('users')->updateOrInsert(
            [
                'email' => $email,
            ],
            [
                'password' => bcrypt($randomPassword),
                'name' => $emailPrefix,
            ],
        );

        // Send the password via email
        Mail::send('auth.password-reset', ['password' => $randomPassword], function ($message) use ($email) {
            $message->to($email)
                ->subject('Your Password');
        });

        return redirect()->route('auth.login')->with('status', 'A new password has been sent to your email.');
    }
}
