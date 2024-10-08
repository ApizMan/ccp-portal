<?php

use Illuminate\Support\Facades\App;

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\RouteServiceProvider::class,
    Maatwebsite\Excel\ExcelServiceProvider::class,
    Barryvdh\DomPDF\ServiceProvider::class,
];
