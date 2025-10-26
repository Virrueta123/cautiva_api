<?php

use App\Http\Controllers\client_controller;
use App\Http\Controllers\config_app_controller;
use Illuminate\Support\Facades\Route;

Route::get('/config_app', [config_app_controller::class, 'index']); 
 