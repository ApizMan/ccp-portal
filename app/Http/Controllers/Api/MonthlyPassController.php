<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

include_once app_path('constants.php');

class MonthlyPassController extends Controller
{
    public function index()
    {
        $url = env('BASE_URL') . '/monthlyPass/public';

        // Using file_get_contents
        $data = file_get_contents($url);

        // Decode the JSON data if it's in JSON format
        $data = json_decode($data, true); // Convert to associative array

        return response()->json($data);
    }
}
