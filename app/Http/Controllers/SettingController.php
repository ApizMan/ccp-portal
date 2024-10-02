<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
}
