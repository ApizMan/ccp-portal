<?php

namespace App\Http\Controllers;

use App\Exports\MonthlyPassExport;
use App\Models\ActivityLog;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

class MonthlyPassController extends Controller
{
    protected $data_monthly_pass;
    protected $data_users;
    protected $datas;
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
            'promotionId' => 'required|string',
            'amount' => 'required|numeric', // Ensure amount is numeric
            'duration' => 'required|string',
        ]);

        $validatedMonthlyPass['noReceipt'] = 'C' . str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

        $validatedMonthlyPass['createdAt'] = now();

        // dd($validatedMonthlyPass);

        // Send a PUT request for parking update
        $responseMonthlyPass = Http::post($url, $validatedMonthlyPass);

        // Check if the parking update was successful
        if ($responseMonthlyPass->successful()) {
            $user = User::find(Auth::user()->id);

            ActivityLog::create([
                'user_id' => $user->id,
                'type' => 'Monthly Pass',
                'activity' => 'Create',
                'description' => $user->name . ' created a monthly pass for ' . $validatedMonthlyPass['plateNumber'],
            ]);

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
            'promotionId' => 'required|string',
            'amount' => 'required|numeric', // Ensure amount is numeric
            'duration' => 'required|string',
        ]);

        // dd($validatedMonthlyPass);

        // Send a PUT request for parking update
        $responseMonthlyPass = Http::put($url, $validatedMonthlyPass);

        // Check if the parking update was successful
        if ($responseMonthlyPass->successful()) {
            $user = User::find(Auth::user()->id);

            ActivityLog::create([
                'user_id' => $user->id,
                'type' => 'Monthly Pass',
                'activity' => 'Edit',
                'description' => $user->name . ' updated a monthly pass for ' . $validatedMonthlyPass['plateNumber'],
            ]);

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

            $user = User::find(Auth::user()->id);

            ActivityLog::create([
                'user_id' => $user->id,
                'type' => 'Monthly Pass',
                'activity' => 'Delete',
                'description' => $user->name . ' deleted a monthly pass',
            ]);

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

    public function exportExcel()
    {
        return Excel::download(new MonthlyPassExport, 'monthly_pass_data.xlsx');
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
        $pdf = Pdf::loadView('monthly_pass.pdf_view', compact('data'));

        // dd($pdf);
        // Return the generated PDF for download.
        return $pdf->download('monthly_pass_data.pdf');
    }

    private function fetchData()
    {
        // Fetch parking data
        $url = env('BASE_URL') . '/monthlyPass/public';
        $data = file_get_contents($url);
        $this->data_monthly_pass = json_decode($data, true);

        // Fetch users data
        $url = env('BASE_URL') . '/auth/users';
        $data = file_get_contents($url);
        $this->data_users = json_decode($data, true);

        // Initialize $datas array
        $this->datas = [];

        // Build the $datas array based on relationships
        foreach ($this->data_monthly_pass as $monthlyPass) {
            $userId = $monthlyPass['userId'];

            // Find user by userId
            $user = array_filter($this->data_users, function ($user) use ($userId) {
                return $user['id'] === $userId;
            });

            $user = $user ? array_values($user)[0] : null;

            // dd($user);

            // Format the createdAt timestamp
            $createdAt = (new \DateTime($monthlyPass['createdAt']))->format('d-m-Y H:i');

            // Add data to $datas array
            $this->datas[] = [
                'id' => $monthlyPass['id'],
                'user' => $user, // Store the user array
                'plateNumber' => $monthlyPass['plateNumber'],
                'pbt' => $monthlyPass['pbt'],
                'location' => $monthlyPass['location'],
                'amount' => $monthlyPass['amount'] ?? 'N/A', // Use amount from transaction if available
                'duration' => $monthlyPass['duration'] ?? 'N/A', // Use status from transaction if available
                'createdAt' => $createdAt, // Use formatted date
                'updatedAt' => (new \DateTime($monthlyPass['updatedAt']))->format('d-m-Y H:i'), // Format updatedAt as well
            ];
        }
    }
}
