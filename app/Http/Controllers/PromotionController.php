<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PromotionController extends Controller
{
    public function index()
    {
        return view('promotions.monthly_pass.monthly-pass');
    }

    public function view($id)
    {
        $url = env('BASE_URL') . '/promotion/single/' . $id;

        // Using file_get_contents
        $data = file_get_contents($url);

        // Decode the JSON data if necessary
        $decodedData = json_decode($data, true); // true for associative array

        return view(
            'promotions.monthly_pass.view-monthly-pass',
            compact(
                [
                    'decodedData',
                ],
            ),
        );
    }

    public function create()
    {
        return view('promotions.monthly_pass.create-monthly-pass');
    }

    public function store(Request $request)
    {
        // Define the URLs for the monthly pass updates
        $url = env('BASE_URL') . '/promotion/public/create';

        // Validate the incoming request data
        $validatedData = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'rate' => 'required|numeric|decimal:0,2',
            'date' => 'required|date_format:Y-m-d\TH:i', // Validate the datetime-local format
            'expiredDate' => 'required|date_format:Y-m-d\TH:i',
            'image' => 'required|max:2048',
        ]);

        // Convert the 'date' field to ISO 8601 format with UTC timezone
        $dateTime = Carbon::parse($validatedData['date'])->toIso8601String();
        $validatedData['date'] = $dateTime;

        // Convert the 'date' field to ISO 8601 format with UTC timezone
        $dateTimeExpired = Carbon::parse($validatedData['expiredDate'])->toIso8601String();
        $validatedData['expiredDate'] = $dateTimeExpired;

        // Check if a file was uploaded
        if ($request->hasFile('image')) {
            // Get the uploaded file
            $file = $request->file('image');

            // dd($file);

            // Define a unique file name
            $filename = time() . '-' . $file->getClientOriginalName();

            // Store the file in the 'public/images' directory (you can customize the path)
            $path = $file->storeAs('public/images', $filename);

            // If you need to get the URL to the stored file
            $urlImage = Storage::url($path);

            // Append the image URL to the validated data
            $validatedData['image'] = env('APP_URL') . $urlImage;
        }

        // dd($validatedData);

        // Send a POST request with the validated data
        $response = Http::post($url, $validatedData);

        // dd($response);

        // Check if the promotion creation was successful
        if ($response->successful()) {
            $user = User::find(Auth::user()->id);

            // Log the activity
            ActivityLog::create([
                'user_id' => $user->id,
                'type' => 'Promotion Monthly Pass',
                'activity' => 'Create',
                'description' => $user->name . ' created a new promotion for ' . $validatedData['title'],
            ]);

            // Redirect or return a success message
            return redirect()->route('promotion.promotion.monthly_pass')->with('status', 'Promotions information created successfully.');
        } else {
            // Handle the error accordingly
            return back()->withErrors([
                'error' => 'Failed to create promotion information.',
                'error_response' => $response->body(), // Log the response body for debugging
            ]);
        }
    }

    public function edit($id)
    {
        $url = env('BASE_URL') . '/promotion/single/' . $id;

        // Using file_get_contents
        $data = file_get_contents($url);

        // Decode the JSON data if necessary
        $decodedData = json_decode($data, true); // true for associative array

        // dd($decodedData);

        return view(
            'promotions.monthly_pass.edit-monthly-pass',
            compact(
                [
                    'decodedData',
                ],
            ),
        );
    }

    public function update(Request $request, $id)
    {
        // Dump and die to see the request data (for debugging)
        // dd($request->all());

        // Define the URLs for the monthly pass updates
        $url = env('BASE_URL') . '/promotion/edit/' . $id;

        // Validate the incoming request data for parking
        $validatedData = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'rate' => 'required|numeric|decimal:0,2',
            'date' => 'required|date_format:Y-m-d\TH:i', // Validate the datetime-local format
            'expiredDate' => 'required|date_format:Y-m-d\TH:i',
            'image' => 'nullable|max:2048',
        ]);

        // Convert the 'date' field to ISO 8601 format with UTC timezone
        $dateTime = Carbon::parse($validatedData['date'])->toIso8601String();
        $validatedData['date'] = $dateTime;

        // Convert the 'date' field to ISO 8601 format with UTC timezone
        $dateTimeExpired = Carbon::parse($validatedData['expiredDate'])->toIso8601String();
        $validatedData['expiredDate'] = $dateTimeExpired;

        // Check if a file was uploaded
        if ($request->hasFile('image')) {
            // Get the uploaded file
            $file = $request->file('image');

            // dd($file);

            // Define a unique file name
            $filename = time() . '-' . $file->getClientOriginalName();

            // Store the file in the 'public/images' directory (you can customize the path)
            $path = $file->storeAs('public/images', $filename);

            // If you need to get the URL to the stored file
            $urlImage = Storage::url($path);

            // Append the image URL to the validated data
            $validatedData['image'] = env('APP_URL') . $urlImage;
        }

        // dd($validatedMonthlyPass);

        // Send a PUT request for parking update
        $response = Http::put($url, $validatedData);

        // Check if the promotion creation was successful
        if ($response->successful()) {
            $user = User::find(Auth::user()->id);

            // Log the activity
            ActivityLog::create([
                'user_id' => $user->id,
                'type' => 'Promotion Monthly Pass',
                'activity' => 'Edit',
                'description' => $user->name . ' edited the promotion for ' . $validatedData['title'],
            ]);

            // Redirect or return a success message
            return redirect()->route('promotion.promotion.monthly_pass')->with('status', 'Promotions information updated successfully.');
        } else {
            // Handle the error accordingly
            return back()->withErrors([
                'error' => 'Failed to update promotion information.',
                'error_response' => $response->body(), // Log the response body for debugging
            ]);
        }
    }

    public function destroy($id)
    {
        // Define the URL for the delete request
        $url = env('BASE_URL') . '/promotion/delete/' . $id;

        // Send a DELETE request to the URL
        $response = Http::delete($url);

        // Check if the deletion was successful
        if ($response->successful()) {
            $user = User::find(Auth::user()->id);

            // Log the activity
            ActivityLog::create([
                'user_id' => $user->id,
                'type' => 'Promotion Monthly Pass',
                'activity' => 'Delete',
                'description' => $user->name . ' deleted the promotion.',
            ]);

            // Redirect or return a success message
            return redirect()->route('promotion.promotion.monthly_pass')->with('status', 'Promotions information deleted successfully.');
        } else {
            // Handle the error accordingly
            return back()->withErrors([
                'error' => 'Failed to delete promotion information.',
                'error_response' => $response->body(), // Log the response body for debugging
            ]);
        }
    }
}
