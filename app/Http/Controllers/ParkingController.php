<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use App\Exports\ParkingExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

include_once app_path('constants.php');

class ParkingController extends Controller
{
    protected $data_parking;
    protected $data_users;
    protected $data_transactions;
    protected $datas;

    public function index()
    {
        return view('parking.parking');
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
            $user = User::find(Auth::user()->id);

            ActivityLog::create([
                'user_id' => $user->id,
                'type' => 'Parking',
                'activity' => 'Edit',
                'description' => $user->name . ' updated a parking for ' . $validatedParking['plateNumber'],
            ]);

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
            $user = User::find(Auth::user()->id);

            ActivityLog::create([
                'user_id' => $user->id,
                'type' => 'Parking',
                'activity' => 'Delete',
                'description' => $user->name . ' deleted a parking',
            ]);

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

    public function exportExcel()
    {
        return Excel::download(new ParkingExport, 'parking_data.xlsx');
    }

    public function exportPDF()
    {
        // Increase memory limit
        ini_set('memory_limit', '512M'); // or '512M'

        // Fetch the data needed for the PDF.
        $this->fetchData();

        // Check if $this->datas is populated correctly.
        $data = $this->datas;

        // dd($data);

        // If there's no data, handle accordingly.
        if (empty($data)) {
            return back()->with('error', 'No data available for export.');
        }

        // Generate PDF using the data and view.
        $pdf = Pdf::loadView('parking.pdf_view', compact('data'));

        // dd($pdf);
        // Return the generated PDF for download.
        return $pdf->download('parking_data.pdf');
    }

    private function fetchData()
    {
        // Fetch parking data
        $url = env('BASE_URL') . '/parking/public';
        $data = file_get_contents($url);
        $this->data_parking = json_decode($data, true);

        // Fetch users data
        $url = env('BASE_URL') . '/auth/users';
        $data = file_get_contents($url);
        $this->data_users = json_decode($data, true);

        // Fetch transactions data
        $url = env('BASE_URL') . '/transaction/allTransactionWallet';
        $data = file_get_contents($url);
        $this->data_transactions = json_decode($data, true);

        // Initialize $datas array
        $this->datas = [];

        // Build the $datas array based on relationships
        foreach ($this->data_parking as $parking) {
            $userId = $parking['userId'];
            $walletTransactionId = $parking['walletTransactionId'];

            // Find user by userId
            $user = array_filter($this->data_users, function ($user) use ($userId) {
                return $user['id'] === $userId;
            });

            $user = $user ? array_values($user)[0] : null;

            // dd($user);

            // Find transaction by walletTransactionId
            $transaction = array_filter($this->data_transactions, function ($transaction) use ($walletTransactionId) {
                return $transaction['id'] === $walletTransactionId;
            });

            // Format the createdAt timestamp
            $createdAt = (new \DateTime($parking['createdAt']))->format('d-m-Y H:i');

            // Use array_values to reindex arrays from array_filter
            $transaction = $transaction ? array_values($transaction)[0] : null;

            // Add data to $datas array
            $this->datas[] = [
                'id' => $parking['id'],
                'user' => $user, // Store the user array
                'transaction' => $transaction, // Store the transaction array
                'plateNumber' => $parking['plateNumber'],
                'pbt' => $parking['pbt'],
                'location' => $parking['location'],
                'amount' => $transaction['amount'] ?? 'N/A', // Use amount from transaction if available
                'status' => $transaction['status'] ?? 'N/A', // Use status from transaction if available
                'createdAt' => $createdAt, // Use formatted date
                'updatedAt' => (new \DateTime($parking['updatedAt']))->format('d-m-Y H:i'), // Format updatedAt as well
            ];
        }
    }
}
