<?php

namespace App\Http\Controllers;

use App\Exports\ReserveBayExport;
use App\Models\ActivityLog;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ReserveBayController extends Controller
{

    protected $data_reserve_bay;
    protected $data_users;
    protected $datas;

    public function index()
    {
        return view('reserve_bay.reserve_bay');
    }

    public function view($id)
    {
        $reserveBayId = $id;
        $url = env('BASE_URL') . '/reservebay/single/' . $id;

        // Using file_get_contents
        $data = file_get_contents($url);

        // Decode the JSON data if necessary
        $decodedData = json_decode($data, true); // true for associative array

        return view(
            'reserve_bay.view-reserve-bay',
            compact(
                [
                    'decodedData',
                    'reserveBayId',
                ],
            ),
        );
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
            'phoneNumber' => 'required|string',
            'email' => 'required|string|email',
            'idNumber' => 'required|string',
            'reason' => 'required|string',
            'lotNumber' => 'required|string',
            'location' => 'required|string',
            'designatedBayPicture' => 'nullable|max:2048',
            'registerNumberPicture' => 'nullable|max:2048',
            'idCardPicture' => 'nullable|max:2048',
        ]);

        // Check if a file was uploaded

        if ($request->hasFile('designatedBayPicture')) {
            // Get the uploaded file
            $file = $request->file('designatedBayPicture');

            // Define a unique file name and include the uploads folder
            $filename = 'uploads/' . time() . '-' . $file->getClientOriginalName();

            // Upload the file to Firebase Storage
            $storage = new StorageClient([
                'keyFilePath' => env('FIREBASE_CREDENTIALS'),
            ]);
            $bucket = $storage->bucket(env('FIREBASE_STORAGE_BUCKET'));

            // Upload the file
            $object = $bucket->upload(
                fopen($file->getRealPath(), 'r'),
                [
                    'name' => $filename,
                    'predefinedAcl' => 'publicRead', // Make the file publicly accessible
                ]
            );

            // Generate the public URL of the uploaded file
            $urlImage = "https://storage.googleapis.com/" . env('FIREBASE_STORAGE_BUCKET') . "/" . $filename;

            // Append the image URL to the validated data
            $validatedReserveBay['designatedBayPicture'] = $urlImage;
        }

        if ($request->hasFile('registerNumberPicture')) {
            // Get the uploaded file
            $file = $request->file('registerNumberPicture');

            // Define a unique file name and include the uploads folder
            $filename = 'uploads/' . time() . '-' . $file->getClientOriginalName();

            // Upload the file to Firebase Storage
            $storage = new StorageClient([
                'keyFilePath' => env('FIREBASE_CREDENTIALS'),
            ]);
            $bucket = $storage->bucket(env('FIREBASE_STORAGE_BUCKET'));

            // Upload the file
            $object = $bucket->upload(
                fopen($file->getRealPath(), 'r'),
                [
                    'name' => $filename,
                    'predefinedAcl' => 'publicRead', // Make the file publicly accessible
                ]
            );

            // Generate the public URL of the uploaded file
            $urlImage = "https://storage.googleapis.com/" . env('FIREBASE_STORAGE_BUCKET') . "/" . $filename;

            // Append the image URL to the validated data
            $validatedReserveBay['registerNumberPicture'] = $urlImage;
        }

        if ($request->hasFile('idCardPicture')) {
            // Get the uploaded file
            $file = $request->file('idCardPicture');

            // Define a unique file name and include the uploads folder
            $filename = 'uploads/' . time() . '-' . $file->getClientOriginalName();

            // Upload the file to Firebase Storage
            $storage = new StorageClient([
                'keyFilePath' => env('FIREBASE_CREDENTIALS'),
            ]);
            $bucket = $storage->bucket(env('FIREBASE_STORAGE_BUCKET'));

            // Upload the file
            $object = $bucket->upload(
                fopen($file->getRealPath(), 'r'),
                [
                    'name' => $filename,
                    'predefinedAcl' => 'publicRead', // Make the file publicly accessible
                ]
            );

            // Generate the public URL of the uploaded file
            $urlImage = "https://storage.googleapis.com/" . env('FIREBASE_STORAGE_BUCKET') . "/" . $filename;

            // Append the image URL to the validated data
            $validatedReserveBay['idCardPicture'] = $urlImage;
        }

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
            'phoneNumber' => 'required|string',
            'email' => 'required|string|email',
            'idNumber' => 'required|string',
            'reason' => 'required|string',
            'lotNumber' => 'required|string',
            'location' => 'required|string',
            'designatedBayPicture' => 'nullable|max:2048',
            'registerNumberPicture' => 'nullable|max:2048',
            'idCardPicture' => 'nullable|max:2048',
        ]);

        // Check if a file was uploaded
        if ($request->hasFile('designatedBayPicture')) {
            // Get the uploaded file
            $file = $request->file('designatedBayPicture');

            // Define a unique file name and include the uploads folder
            $filename = 'uploads/' . time() . '-' . $file->getClientOriginalName();

            // Upload the file to Firebase Storage
            $storage = new StorageClient([
                'keyFilePath' => env('FIREBASE_CREDENTIALS'),
            ]);
            $bucket = $storage->bucket(env('FIREBASE_STORAGE_BUCKET'));

            // Upload the file
            $object = $bucket->upload(
                fopen($file->getRealPath(), 'r'),
                [
                    'name' => $filename,
                    'predefinedAcl' => 'publicRead', // Make the file publicly accessible
                ]
            );

            // Generate the public URL of the uploaded file
            $urlImage = "https://storage.googleapis.com/" . env('FIREBASE_STORAGE_BUCKET') . "/" . $filename;

            // Append the image URL to the validated data
            $validatedReserveBay['designatedBayPicture'] = $urlImage;
        }

        if ($request->hasFile('registerNumberPicture')) {
            // Get the uploaded file
            $file = $request->file('registerNumberPicture');

            // Define a unique file name and include the uploads folder
            $filename = 'uploads/' . time() . '-' . $file->getClientOriginalName();

            // Upload the file to Firebase Storage
            $storage = new StorageClient([
                'keyFilePath' => env('FIREBASE_CREDENTIALS'),
            ]);
            $bucket = $storage->bucket(env('FIREBASE_STORAGE_BUCKET'));

            // Upload the file
            $object = $bucket->upload(
                fopen($file->getRealPath(), 'r'),
                [
                    'name' => $filename,
                    'predefinedAcl' => 'publicRead', // Make the file publicly accessible
                ]
            );

            // Generate the public URL of the uploaded file
            $urlImage = "https://storage.googleapis.com/" . env('FIREBASE_STORAGE_BUCKET') . "/" . $filename;

            // Append the image URL to the validated data
            $validatedReserveBay['registerNumberPicture'] = $urlImage;
        }

        if ($request->hasFile('idCardPicture')) {
            // Get the uploaded file
            $file = $request->file('idCardPicture');

            // Define a unique file name and include the uploads folder
            $filename = 'uploads/' . time() . '-' . $file->getClientOriginalName();

            // Upload the file to Firebase Storage
            $storage = new StorageClient([
                'keyFilePath' => env('FIREBASE_CREDENTIALS'),
            ]);
            $bucket = $storage->bucket(env('FIREBASE_STORAGE_BUCKET'));

            // Upload the file
            $object = $bucket->upload(
                fopen($file->getRealPath(), 'r'),
                [
                    'name' => $filename,
                    'predefinedAcl' => 'publicRead', // Make the file publicly accessible
                ]
            );

            // Generate the public URL of the uploaded file
            $urlImage = "https://storage.googleapis.com/" . env('FIREBASE_STORAGE_BUCKET') . "/" . $filename;

            // Append the image URL to the validated data
            $validatedReserveBay['idCardPicture'] = $urlImage;
        }

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

    public function updateStatusApproveView($id)
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
            return redirect()->route('reserveBay.reserve_bay_view', ['id' => $id])->with('status', 'Reserve Bay information updated successfully.');
        } else {
            // Handle the error accordingly
            return back()->withErrors([
                'error' => 'Failed to update monthly pass information.',
                'error_response' => $response->body(), // Log the response body for debugging
            ]);
        }
    }

    public function updateStatusRejectView($id)
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
            return redirect()->route('reserveBay.reserve_bay_view', ['id' => $id])->with('status', 'Reserve Bay information updated successfully.');
        } else {
            // Handle the error accordingly
            return back()->withErrors([
                'error' => 'Failed to update monthly pass information.',
                'error_response' => $response->body(), // Log the response body for debugging
            ]);
        }
    }

    public function exportExcel()
    {
        return Excel::download(new ReserveBayExport, 'reserve_bay_data.xlsx');
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
        $pdf = Pdf::loadView('reserve_bay.pdf_view', compact('data'));

        // dd($pdf);
        // Return the generated PDF for download.
        return $pdf->download('reserve_bay_data.pdf');
    }

    private function fetchData()
    {
        // Fetch parking data
        $url = env('BASE_URL') . '/reservebay/public';
        $data = file_get_contents($url);
        $this->data_reserve_bay = json_decode($data, true);

        // Fetch users data
        $url = env('BASE_URL') . '/auth/users';
        $data = file_get_contents($url);
        $this->data_users = json_decode($data, true);

        // Initialize $datas array
        $this->datas = [];

        // Build the $datas array based on relationships
        foreach ($this->data_reserve_bay as $reserveBay) {
            $userId = $reserveBay['userId'];

            // Find user by userId
            $user = array_filter($this->data_users, function ($user) use ($userId) {
                return $user['id'] === $userId;
            });

            $user = $user ? array_values($user)[0] : null;

            // dd($reserveBay);

            // Format the createdAt timestamp
            $createdAt = (new \DateTime($reserveBay['createdAt']))->format('d-m-Y H:i');

            // Add data to $datas array
            $this->datas[] = [
                'id' => $reserveBay['id'],
                'status' => $reserveBay['status'],
                'user' => $user ? array_values($user)[0] : null, // Store the user array
                'companyName' => $reserveBay['companyName'],
                'companyRegistration' => $reserveBay['companyRegistration'],
                'businessType' => $reserveBay['businessType'],
                'address1' => $reserveBay['address1'],
                'address2' => $reserveBay['address2'],
                'address3' => $reserveBay['address3'],
                'postcode' => $reserveBay['postcode'],
                'city' => $reserveBay['city'],
                'state' => $reserveBay['state'],
                'location' => $reserveBay['location'],
                'picFirstName' => $reserveBay['picFirstName'],
                'picLastName' => $reserveBay['picLastName'],
                'phoneNumber' => $reserveBay['phoneNumber'],
                'email' => $reserveBay['email'],
                'idNumber' => $reserveBay['idNumber'],
                'totalLotRequired' => $reserveBay['totalLotRequired'],
                'reason' => $reserveBay['reason'],
                'lotNumber' => $reserveBay['lotNumber'],
                'designatedBayPicture' => $reserveBay['designatedBayPicture'],
                'registerNumberPicture' => $reserveBay['registerNumberPicture'],
                'idCardPicture' => $reserveBay['idCardPicture'],
                'createdAt' => $createdAt, // Use formatted date
                'updatedAt' => (new \DateTime($reserveBay['updatedAt']))->format('d-m-Y H:i'), // Format updatedAt as well
            ];
        }
    }
}
