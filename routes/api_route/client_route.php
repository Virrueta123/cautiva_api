<?php

use App\Http\Controllers\client_controller; 
use Illuminate\Support\Facades\Route;

Route::get('/client', [client_controller::class, 'index']); 
Route::get('/client/{id}', [client_controller::class, 'show']); 
Route::post('/client', [client_controller::class, 'store']); 
Route::put('/client/{id}', [client_controller::class, 'update']);