<?php

use App\Http\Controllers\report_controller;
use Illuminate\Support\Facades\Route;

Route::get('/report/report_sale/{period}', [report_controller::class, 'report_sale']);

// data report
Route::get('/report/report_sale_year_data/{year}/{type_sale}', [report_controller::class, 'report_sale_year_data']);