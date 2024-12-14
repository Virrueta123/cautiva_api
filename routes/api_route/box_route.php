<?php

use App\Http\Controllers\box_controller;
use Illuminate\Support\Facades\Route;

Route::get('/box', [box_controller::class, 'index']); 

Route::post('/box', [box_controller::class, 'store']); 
Route::post('/box/close', [box_controller::class, 'close']); 