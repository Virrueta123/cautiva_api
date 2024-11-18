<?php

use App\Http\Controllers\product_controller; 
use Illuminate\Support\Facades\Route;

Route::post('/product', [product_controller::class, 'store']); 