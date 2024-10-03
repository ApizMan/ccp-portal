<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReserveBayController extends Controller
{
    public function index(){
        return view('reserve_bay.reserve_bay');
    }
}
