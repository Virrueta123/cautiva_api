<?php

use App\Http\Controllers\product_controller; 
use Illuminate\Support\Facades\Route;

Route::get('/product', [product_controller::class, 'index']); 
Route::post('/product', [product_controller::class, 'store']); 

Route::get('/product/{identifier}', [product_controller::class, 'show']); 

Route::get('/product/barcode_print/{identifier}', [product_controller::class, 'barcode_print']);

Route::get('/product/barcode/{barcode}', [product_controller::class, 'barcode']); 

//actualizar producto
Route::patch('/product/{identifier}', [product_controller::class, 'update']);

// delete producto
Route::delete('/product/{identifier}', [product_controller::class, 'destroy']); 