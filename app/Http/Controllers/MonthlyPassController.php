<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MonthlyPassController extends Controller
{
    public function index()
    {
        return view('monthly_pass.monthly_pass');
    }

    public function createMonthlyPass()
    {
        // dd('hello');
        return view('monthly_pass.create-monthly-pass');
    }

    public function store(Request $request)
    {
        // Define the URLs for the monthly pass updates
        $url = env('BASE_URL') . '/monthlyPass/public/create';

        // Validate the incoming request data for parking
        $validatedMonthlyPass = $request->validate([
            'plateNumber' => 'required|string',
            'pbt' => 'required|string',
            'location' => 'required|string',
            'userId' => 'required|string', // Include userId in validation
            'amount' => 'required|numeric', // Ensure amount is numeric
            'duration' => 'required|string',
        ]);

        $validatedMonthlyPass['createdAt'] = now();

        // Send a PUT request for parking update
        $responseMonthlyPass = Http::post($url, $validatedMonthlyPass);

        // Check if the parking update was successful
        if ($responseMonthlyPass->successful()) {
            // Redirect or return a success message
            return redirect()->route('monthlyPass.monthly_pass_public')->with('status', 'Monthly Pass information created successfully.');
        } else {
            // Handle the error accordingly
            return back()->withErrors([
                'error' => 'Failed to create monthly pass information.',
                'monthly_pass_error' => $responseMonthlyPass->body(), // Log the response body for debugging
            ]);
        }
    }

    public function edit($id)
    {
        $monthlyPassId = $id;
        $url = env('BASE_URL') . '/monthlyPass/single/' . $id;

        // Using file_get_contents
        $data = file_get_contents($url);

        // Decode the JSON data if necessary
        $decodedData = json_decode($data, true); // true for associative array

        // dd($decodedData);

        return view(
            'monthly_pass.edit-monthly-pass',
            compact(
                [
                    'decodedData',
                    'monthlyPassId',
                ],
            ),
        );
    }

    public function update(Request $request, $id)
    {
        // Dump and die to see the request data (for debugging)
        // dd($request->all());

        // Define the URLs for the monthly pass updates
        $url = env('BASE_URL') . '/monthlyPass/edit/' . $id;

        // Validate the incoming request data for parking
        $validatedMonthlyPass = $request->validate([
            'plateNumber' => 'required|string',
            'pbt' => 'required|string',
            'location' => 'required|string',
            'userId' => 'required|string', // Include userId in validation
            'amount' => 'required|numeric', // Ensure amount is numeric
            'duration' => 'required|string',
        ]);

        // dd($validatedMonthlyPass);

        // Send a PUT request for parking update
        $responseMonthlyPass = Http::put($url, $validatedMonthlyPass);

        // Check if the parking update was successful
        if ($responseMonthlyPass->successful()) {
            // Redirect or return a success message
            return redirect()->route('monthlyPass.monthly_pass_public')->with('status', 'Monthly Pass information updated successfully.');
        } else {
            // Handle the error accordingly
            return back()->withErrors([
                'error' => 'Failed to update monthly pass information.',
                'monthly_pass_error' => $responseMonthlyPass->body(), // Log the response body for debugging
            ]);
        }
    }

    public function destroy($id)
    {
        // Define the URL for the delete request
        $url = env('BASE_URL') . '/monthlyPass/delete/' . $id;

        // Send a DELETE request to the URL
        $response = Http::delete($url);

        // Check if the deletion was successful
        if ($response->successful()) {
            // Redirect or return a success message
            return redirect()->route('monthlyPass.monthly_pass_public')->with('status', 'Monthly Pass information deleted successfully.');
        } else {
            // Handle the error accordingly
            return back()->withErrors([
                'error' => 'Failed to delete monthly pass information.',
                'response_error' => $response->body(), // Log the response body for debugging
            ]);
        }
    }
}
