<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ReserveBayController extends Controller
{
    public function index()
    {
        return view('reserve_bay.reserve_bay');
    }


    public function create()
    {
        return view('reserve_bay.create-reserve-bay');
    }

    public function store(Request $request)
    {
        // Define the URLs for the monthly pass updates
        $url = env('BASE_URL') . '/reservebay/public/create';

        // Validate the incoming request data for parking
        $validatedReserveBay = $request->validate([
            'companyName' => 'required|string',
            'companyRegistration' => 'required|string',
            'businessType' => 'required|string',
            'userId' => 'required|string', // Include userId in validation
            'address1' => 'required|string',
            'address2' => 'required|string',
            'address3' => 'nullable|string',
            'postcode' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'picFirstName' => 'required|string',
            'picLastName' => 'required|string',
            'email' => 'required|string|email',
            'idNumber' => 'required|string',
            'reason' => 'required|string',
            'lotNumber' => 'required|string',
            'location' => 'required|string',
            'designatedBayPicture' => 'nullable|string',
            'registerNumberPicture' => 'nullable|string',
            'idCardPicture' => 'nullable|string',
        ]);

        // Convert the amount to a int before validating
        $validatedReserveBay['totalLotRequired'] = (int) $request->input('totalLotRequired');

        // dd($validatedReserveBay);

        // Send a PUT request for parking update
        $responseReserveBay = Http::post($url, $validatedReserveBay);

        // dd($responseReserveBay);

        // Check if the parking update was successful
        if ($responseReserveBay->successful()) {
            $user = User::find(Auth::user()->id);

            ActivityLog::create([
                'user_id' => $user->id,
                'type' => 'Reserve Bay',
                'activity' => 'Create',
                'description' => $user->name . ' created a reserve bay for ' . $validatedReserveBay['companyName'],
            ]);

            // Redirect or return a success message
            return redirect()->route('reserveBay.reserve_bay')->with('status', 'Reserve Bay information created successfully.');
        } else {
            // Handle the error accordingly
            return back()->withErrors([
                'error' => 'Failed to create monthly pass information.',
                'error_response' => $responseReserveBay->body(), // Log the response body for debugging
            ]);
        }
    }

    public function edit($id)
    {
        $reserveBayId = $id;
        $url = env('BASE_URL') . '/reservebay/single/' . $id;

        // Using file_get_contents
        $data = file_get_contents($url);

        // Decode the JSON data if necessary
        $decodedData = json_decode($data, true); // true for associative array

        // dd($decodedData);

        return view(
            'reserve_bay.edit-reserve-bay',
            compact(
                [
                    'decodedData',
                    'reserveBayId',
                ],
            ),
        );
    }

    public function update(Request $request, $id)
    {
        // Dump and die to see the request data (for debugging)
        // dd($request->all());

        // Define the URLs for the monthly pass updates
        $url = env('BASE_URL') . '/reservebay/edit/' . $id;

        // Validate the incoming request data for parking
        $validatedReserveBay = $request->validate([
            'companyName' => 'required|string',
            'companyRegistration' => 'required|string',
            'businessType' => 'required|string',
            'userId' => 'required|string', // Include userId in validation
            'address1' => 'required|string',
            'address2' => 'required|string',
            'address3' => 'nullable|string',
            'postcode' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'picFirstName' => 'required|string',
            'picLastName' => 'required|string',
            'email' => 'required|string|email',
            'idNumber' => 'required|string',
            'reason' => 'required|string',
            'lotNumber' => 'required|string',
            'location' => 'required|string',
            'designatedBayPicture' => 'nullable|string',
            'registerNumberPicture' => 'nullable|string',
            'idCardPicture' => 'nullable|string',
        ]);

        // Convert the amount to a int before validating
        $validatedReserveBay['totalLotRequired'] = (int) $request->input('totalLotRequired');

        // dd($validatedMonthlyPass);

        // Send a PUT request for parking update
        $responseReserveBay = Http::put($url, $validatedReserveBay);

        // Check if the parking update was successful
        if ($responseReserveBay->successful()) {
            $user = User::find(Auth::user()->id);

            ActivityLog::create([
                'user_id' => $user->id,
                'type' => 'Reserve Bay',
                'activity' => 'Edit',
                'description' => $user->name . ' updated a reserve bay for ' . $validatedReserveBay['companyName'],
            ]);

            // Redirect or return a success message
            return redirect()->route('reserveBay.reserve_bay')->with('status', 'Reserve Bay information updated successfully.');
        } else {
            // Handle the error accordingly
            return back()->withErrors([
                'error' => 'Failed to update monthly pass information.',
                'error_response' => $responseReserveBay->body(), // Log the response body for debugging
            ]);
        }
    }

    public function destroy($id)
    {
        // Define the URL for the delete request
        $url = env('BASE_URL') . '/reservebay/delete/' . $id;

        // Send a DELETE request to the URL
        $response = Http::delete($url);

        // Check if the deletion was successful
        if ($response->successful()) {

            $user = User::find(Auth::user()->id);

            ActivityLog::create([
                'user_id' => $user->id,
                'type' => 'Reserve Bay',
                'activity' => 'Delete',
                'description' => $user->name . ' deleted a reserve bay',
            ]);

            // Redirect or return a success message
            return redirect()->route('reserveBay.reserve_bay')->with('status', 'Reserve Bay information deleted successfully.');
        } else {
            // Handle the error accordingly
            return back()->withErrors([
                'error' => 'Failed to delete reserve bay information.',
                'error_response' => $response->body(), // Log the response body for debugging
            ]);
        }
    }

    public function updateStatusApprove($id)
    {
        // Define the URLs for the reserve bay status updates
        $url = env('BASE_URL') . '/reservebay/edit/status/' . $id;

        $validated['status'] = 'APPROVED';

        // Send a PUT request for reserve bay status update
        $response = Http::put($url, $validated);

        // Check if the parking update was successful
        if ($response->successful()) {

            $url = env('BASE_URL') . '/reservebay/single/' . $id;

            // Using file_get_contents
            $data = file_get_contents($url);

            // Decode the JSON data if necessary
            $decodedData = json_decode($data, true); // true for associative array

            $user = User::find(Auth::user()->id);

            ActivityLog::create([
                'user_id' => $user->id,
                'type' => 'Reserve Bay',
                'activity' => 'Update Status',
                'description' => $user->name . ' updated a reserve bay status ' . $validated['status'] . ' for ' . $decodedData['companyName'],
            ]);

            // Redirect or return a success message
            return redirect()->route('reserveBay.reserve_bay')->with('status', 'Reserve Bay information updated successfully.');
        } else {
            // Handle the error accordingly
            return back()->withErrors([
                'error' => 'Failed to update monthly pass information.',
                'error_response' => $response->body(), // Log the response body for debugging
            ]);
        }
    }

    public function updateStatusReject($id)
    {
        // Define the URLs for the reserve bay status updates
        $url = env('BASE_URL') . '/reservebay/edit/status/' . $id;

        $validated['status'] = 'REJECTED';

        // Send a PUT request for reserve bay status update
        $response = Http::put($url, $validated);

        // Check if the parking update was successful
        if ($response->successful()) {

            $url = env('BASE_URL') . '/reservebay/single/' . $id;

            // Using file_get_contents
            $data = file_get_contents($url);

            // Decode the JSON data if necessary
            $decodedData = json_decode($data, true); // true for associative array

            $user = User::find(Auth::user()->id);

            ActivityLog::create([
                'user_id' => $user->id,
                'type' => 'Reserve Bay',
                'activity' => 'Update Status',
                'description' => $user->name . ' updated a reserve bay status ' . $validated['status'] . ' for ' . $decodedData['companyName'],
            ]);

            // Redirect or return a success message
            return redirect()->route('reserveBay.reserve_bay')->with('status', 'Reserve Bay information updated successfully.');
        } else {
            // Handle the error accordingly
            return back()->withErrors([
                'error' => 'Failed to update monthly pass information.',
                'error_response' => $response->body(), // Log the response body for debugging
            ]);
        }
    }
}
