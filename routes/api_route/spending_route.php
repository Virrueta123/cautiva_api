
<?php

use App\Http\Controllers\spending_controller;
use Illuminate\Support\Facades\Route;
Route::get('/spending', [spending_controller::class, 'index']); 
Route::post('/spending', [spending_controller::class, 'store']); 
Route::get('/spending/{identifier}', [spending_controller::class, 'show']); 
Route::put('/spending/{identifier}', [spending_controller::class, 'update']); 
Route::delete('/spending/{identifier}', [spending_controller::class, 'destroy']); 
