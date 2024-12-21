<?php

use App\Http\Controllers\sale_controller;
use Illuminate\Support\Facades\Route;

Route::get('/sale', [sale_controller::class, 'index']);  
Route::get('/sale/{identifier}', [sale_controller::class, 'show']);
Route::get('/sale/print', [sale_controller::class, 'print']);  
Route::get('/sale/share/{identifier}', [sale_controller::class, 'share']); 
Route::post('/sale', [sale_controller::class, 'store']);