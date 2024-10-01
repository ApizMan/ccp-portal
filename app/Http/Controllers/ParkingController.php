<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
