<?php

use App\Http\Controllers\product_controller; 
use Illuminate\Support\Facades\Route;

Route::get('/product', [product_controller::class, 'index']); 
Route::post('/product', [product_controller::class, 'store']); 