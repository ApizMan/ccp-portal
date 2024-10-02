<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function index()
    {
        return view('setting');
    }

    public function generateKey(Request $request)
    {
        $url = env('BASE_URL') . '/payment/public/pay-key';

        // Validate the incoming request data for pegepay
        $validate = $request->validate([
            'onboarding_key' => 'required|string',
        ]);

        $validate['connection_type'] = "npd-wa";

        // Send a POST request for pegepay onboarding key
        $response = Http::post($url, $validate);

        // Check if the pegepay update was successful
        if ($response->successful()) {
            // Redirect or return a success message
            return redirect()->route('setting.setting')->with('status', 'Onboarding Key successfully generated.');
        } else {
            // Handle the error accordingly
            return back()->withErrors([
                'error' => 'Failed to generate onboarding key.',
            ]);
        }
    }

    public function refreshAccessCode()
    {

        $url = env('BASE_URL') . '/payment/public/refresh-token';

        // Send a POST request for access code pegepay
        $response = Http::post($url);

        // Check if the pegepay update was successful
        if ($response->successful()) {
            // Redirect or return a success message
            return redirect()->route('setting.setting')->with('status', 'Access Code successfully refreshed.');
        } else {
            // Handle the error accordingly
            return back()->withErrors([
                'error' => 'Failed to refresh access code.',
            ]);
        }
    }

    public function refreshFPX()
    {

        $url = env('BASE_URL') . '/paymentfpx/public';

        // Send a POST request for fpx
        $response = Http::post($url);

        // Check if the fpx update was successful
        if ($response->successful()) {
            // Redirect or return a success message
            return redirect()->route('setting.setting')->with('status', 'FPX successfully refreshed.');
        } else {
            // Handle the error accordingly
            return back()->withErrors([
                'error' => 'Failed to refresh fpx.',
            ]);
        }
    }

    public function changePassword(Request $request)
    {
        // Step 1: Validate the input fields
        $validator = Validator::make($request->all(), [
            'newPassword' => 'required|string|min:8|confirmed',
        ], [
            'newPassword.confirmed' => 'The new password and confirm password do not match.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Step 2: Update the authenticated user's password
        $user = User::find(Auth::user()->id);
        $password = Hash::make($request->input('newPassword'));
        $user->update(['password' => $password]);

        // Step 3: Redirect back with a success message
        return redirect()->route('setting.setting')->with('status', 'Password changed successfully.');
    }
}
