<?php

use App\Http\Controllers\api_peru_dev_controller;
use Illuminate\Support\Facades\Route;

Route::get('/lookup_dni/{dni}', [api_peru_dev_controller::class, 'lookup_dni']);
Route::get('/lookup_ruc/{ruc}', [api_peru_dev_controller::class, 'lookup_ruc']);

