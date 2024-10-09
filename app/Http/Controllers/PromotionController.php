<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
