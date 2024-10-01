<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

include_once app_path('constants.php');

class ParkingController extends Controller
{
    public function index()
    {
        $url = env('BASE_URL') . '/parking/public';

        // Using file_get_contents
        $data = file_get_contents($url);

        // Decode the JSON data if necessary
        $decodedData = json_decode($data, true); // true for associative array

        return view('parking.parking', compact('decodedData'));
    }

    public function edit($id)
    {
        $parkingId = $id;
        $url = env('BASE_URL') . '/parking/single/' . $id;

        // Using file_get_contents
        $data = file_get_contents($url);

        // Decode the JSON data if necessary
        $decodedData = json_decode($data, true); // true for associative array

        // dd($decodedData);

        return view(
            'parking.edit-parking',
            compact(
                [
                    'decodedData',
                    'parkingId',
                ],
            ),
        );
    }


    public function update(Request $request, $id, $transactionId)
    {
        // Dump and die to see the request data (for debugging)
        // dd($request->all());

        // Define the URLs for the parking and transaction updates
        $urlParking = env('BASE_URL') . '/parking/edit/' . $id;
        $urlTransaction = env('BASE_URL') . '/transaction/edit/' . $transactionId;

        // Validate the incoming request data for parking
        $validatedParking = $request->validate([
            'plateNumber' => 'required|string',
            'pbt' => 'required|string',
            'location' => 'required|string',
            'userId' => 'required|string' // Include userId in validation
        ]);

        // Convert the amount to a float before validating
        $request->merge([
            'amount' => (float) $request->input('amount'),
        ]);

        // Validate the incoming request data for transaction
        $validatedTransaction = $request->validate([
            'status' => 'required|string',
            'amount' => 'required|numeric', // Ensure amount is numeric
        ]);

        // dd($validatedParking);

        // Send a PUT request for parking update
        $responseParking = Http::put($urlParking, $validatedParking);

        // Send a PUT request for transaction update
        $responseTransaction = Http::put($urlTransaction, $validatedTransaction);

        // Check if the parking update was successful
        if ($responseParking->successful() && $responseTransaction->successful()) {
            // Redirect or return a success message
            return redirect()->route('parking.parking_public')->with('status', 'Parking information updated successfully.');
        } else {
            // Handle the error accordingly
            return back()->withErrors([
                'error' => 'Failed to update parking information.',
                'parking_error' => $responseParking->body(), // Log the response body for debugging
                'transaction_error' => $responseTransaction->body() // Log the response body for debugging
            ]);
        }
    }

    public function destroy($id)
    {
        // Define the URL for the delete request
        $url = env('BASE_URL') . '/parking/delete/' . $id;

        // Send a DELETE request to the URL
        $response = Http::delete($url);

        // Check if the deletion was successful
        if ($response->successful()) {
            // Redirect or return a success message
            return redirect()->route('parking.parking_public')->with('status', 'Parking information deleted successfully.');
        } else {
            // Handle the error accordingly
            return back()->withErrors([
                'error' => 'Failed to delete parking information.',
                'response_error' => $response->body(), // Log the response body for debugging
            ]);
        }
    }
}
